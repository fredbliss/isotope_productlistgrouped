<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2014 terminal42 gmbh & Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://isotopeecommerce.org
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace IntelligentSpark\Module;

use Haste\Generator\RowClass;
use Haste\Http\Response\HtmlResponse;
use Haste\Input\Input;
use Contao\PageModel;
use Isotope\Isotope;
use Isotope\Module\ProductList;
use Isotope\Model\Attribute;
use Isotope\Model\Product;
use Isotope\Model\ProductCache;
use Isotope\Model\ProductType;
use Isotope\RequestCache\FilterQueryBuilder;
use Isotope\RequestCache\Sort;
use Isotope\Template;


/**
 * @property string $iso_list_layout
 * @property int    $iso_cols
 * @property bool   $iso_use_quantity
 * @property int    $iso_gallery
 * @property array  $iso_filterModules
 * @property array  $iso_productcache
 * @property string $iso_listingSortField
 * @property string $iso_listingSortDirection
 * @property bool   $iso_jump_first
 */
class ProductListGrouped extends ProductList
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_iso_productlist_grouped';


    protected function compile()
    {
        // return message if no filter is set
        if ($this->iso_emptyFilter && !\Input::get('isorc') && !\Input::get('keywords')) {
            $this->Template->message  = \Controller::replaceInsertTags($this->iso_noFilter);
            $this->Template->type     = 'noFilter';
            $this->Template->products = array();

            return;
        }

        global $objPage;
        $cacheKey      = $this->getCacheKey();
        $arrProducts   = null;
        $arrCacheIds   = null;

        // Try to load the products from cache
        if ($this->blnCacheProducts && ($objCache = ProductCache::findByUniqid($cacheKey)) !== null) {
            $arrCacheIds = $objCache->getProductIds();

            // Use the cache if keywords match. Otherwise we will use the product IDs as a "limit" for findProducts()
            if ($objCache->keywords == \Input::get('keywords')) {
                $arrCacheIds = $this->generatePagination($arrCacheIds);

                $objProducts = Product::findAvailableByIds($arrCacheIds, array(
                    'order' => \Database::getInstance()->findInSet(Product::getTable().'.id', $arrCacheIds)
                ));

                $arrProducts = (null === $objProducts) ? array() : $objProducts->getModels();

                // Cache is wrong, drop everything and run findProducts()
                if (count($arrProducts) != count($arrCacheIds)) {
                    $arrCacheIds = null;
                    $arrProducts = null;
                }
            }
        }

        if (!is_array($arrProducts)) {
            // Display "loading products" message and add cache flag
            if ($this->blnCacheProducts) {
                $blnCacheMessage = (bool) $this->iso_productcache[$cacheKey];

                if ($blnCacheMessage && !\Input::get('buildCache')) {
                    // Do not index or cache the page
                    $objPage->noSearch = 1;
                    $objPage->cache    = 0;

                    $this->Template          = new Template('mod_iso_productlist_caching');
                    $this->Template->message = $GLOBALS['TL_LANG']['MSC']['productcacheLoading'];

                    return;
                }

                // Start measuring how long it takes to load the products
                $start = microtime(true);

                // Load products
                $arrProducts = $this->findProducts($arrCacheIds);

                // Decide if we should show the "caching products" message the next time
                $end = microtime(true) - $start;
                $this->blnCacheProducts = $end > 1 ? true : false;

                $arrCacheMessage = $this->iso_productcache;
                if ($blnCacheMessage != $this->blnCacheProducts) {
                    $arrCacheMessage[$cacheKey] = $this->blnCacheProducts;

                    \Database::getInstance()
                        ->prepare('UPDATE tl_module SET iso_productcache=? WHERE id=?')
                        ->execute(serialize($arrCacheMessage), $this->id)
                    ;
                }

                // Do not write cache if table is locked. That's the case if another process is already writing cache
                if (ProductCache::isWritable()) {
                    \Database::getInstance()
                        ->lockTables(array(ProductCache::getTable() => 'WRITE', 'tl_iso_product' => 'READ'))
                    ;

                    $arrIds = array();
                    foreach ($arrProducts as $objProduct) {
                        $arrIds[] = $objProduct->id;
                    }

                    // Delete existing cache if necessary
                    ProductCache::deleteByUniqidOrExpired($cacheKey);

                    $objCache          = ProductCache::createForUniqid($cacheKey);
                    $objCache->expires = $this->getProductCacheExpiration();
                    $objCache->setProductIds($arrIds);
                    $objCache->save();

                    \Database::getInstance()->unlockTables();
                }
            } else {
                $arrProducts = $this->findProducts();
            }

            if (!empty($arrProducts)) {
                $arrProducts = $this->generatePagination($arrProducts);
            }
        }

        // No products found
        if (!is_array($arrProducts) || empty($arrProducts)) {
            $this->compileEmptyMessage();

            return;
        }

        $arrBuffer         = array();
        $arrGroups          = array();
        $arrAllCategories   = array();
        $arrDefaultOptions = $this->getDefaultProductOptions();

        /** @var \Isotope\Model\Product\Standard $objProduct */
        foreach ($arrProducts as $objProduct) {
            /** @var ProductType $type */
            $type = $objProduct->getRelated('type');

            $arrConfig = array(
                'module'        => $this,
                'template'      => $this->iso_list_layout ?: $type->list_template,
                'gallery'       => $this->iso_gallery ?: $type->list_gallery,
                'buttons'       => $this->iso_buttons,
                'useQuantity'   => $this->iso_use_quantity,
                'jumpTo'        => $this->findJumpToPage($objProduct),
            );

            if (\Environment::get('isAjaxRequest')
                && \Input::post('AJAX_MODULE') == $this->id
                && \Input::post('AJAX_PRODUCT') == $objProduct->getProductId()
            ) {
                $objResponse = new HtmlResponse($objProduct->generate($arrConfig));
                $objResponse->send();
            }

            $objProduct->mergeRow($arrDefaultOptions);

            // Must be done after setting options to generate the variant config into the URL
            if ($this->iso_jump_first && Input::getAutoItem('product', false, true) == '') {
                \Controller::redirect($objProduct->generateUrl($arrConfig['jumpTo']));
            }

            $arrCSS = deserialize($objProduct->cssID, true);

            $arrBuffer = array(
                'cssID'     => ($arrCSS[0] != '') ? ' id="' . $arrCSS[0] . '"' : '',
                'class'     => trim('product ' . ($objProduct->isNew() ? 'new ' : '') . $arrCSS[1]),
                'html'      => $objProduct->generate($arrConfig),
                'product'   => $objProduct,
            );

            //get first category only for grouping.
            $arrCategories = array_intersect($this->findCategories(),$objProduct->getCategories(true));
            $arrAllCategories = array_merge($arrAllCategories,$arrCategories);

            if(count($arrCategories)) {
                foreach($arrCategories as $i=>$id) {

                    if(!array_key_exists($i, $arrGroups)) {
                        $arrGroups[$id]['class'] = '';
                        $arrGroups[$id]['id'] = $id;
                        $arrGroups[$id]['content'] = '';
                        $arrGroups[$id]['title'] = '';
                    }

                    if($this->iso_perGroup > 0) {
                        if(array_key_exists($id,$arrGroups) && count($arrGroups[$id]['products'])<$this->iso_perGroup)
                            $arrGroups[$id]['products'][$objProduct->getId()] = $arrBuffer;
                    }else{
                        if(array_key_exists($id,$arrGroups)
                            $arrGroups[$id]['products'][$objProduct->getId()] = $arrBuffer;
                    }

                }
            }
        }

        // HOOK: to add any product field or attribute to mod_iso_productlist template
        if (isset($GLOBALS['ISO_HOOKS']['generateProductList'])
            && is_array($GLOBALS['ISO_HOOKS']['generateProductList'])
        ) {
            foreach ($GLOBALS['ISO_HOOKS']['generateProductList'] as $callback) {
                $objCallback = \System::importStatic($callback[0]);
                $arrBuffer   = $objCallback->{$callback[1]}($arrBuffer, $arrProducts, $this->Template, $this);
            }
        }

        //get additional page sorting information
        $objPages = \Database::getInstance()->execute("SELECT id,sorting FROM tl_page WHERE id IN(".implode(",",array_unique($arrAllCategories)).")");

        //reorder by sort value!
        while($objPages->next()) {
            $arrFinalGroups[$objPages->sorting] = $arrGroups[$objPages->id];
        }

        if(count($arrFinalGroups)) {
            ksort($arrFinalGroups);

            //this becomes a looped process to make sure each list of products gets it's formatting
            foreach($arrFinalGroups as $group) {

                RowClass::withKey('class')
                    ->addCount('product_')
                    ->addEvenOdd('product_')
                    ->addFirstLast('product_')
                    ->addGridRows($this->iso_cols)
                    ->addGridCols($this->iso_cols)
                    ->applyTo($group['products'])
                ;
            }
        }else{
            $arrFinalGroups = array();
        }


        $this->Template->groups = $arrFinalGroups;
    }
}
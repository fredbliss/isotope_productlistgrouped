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
use Isotope\Isotope;
use Isotope\Model\Attribute;
use Isotope\Model\Product;
use Isotope\Model\ProductCache;
use Isotope\Model\ProductType;
use Isotope\RequestCache\FilterQueryBuilder;
use Isotope\RequestCache\Sort;

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


}
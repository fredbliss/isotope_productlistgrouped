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

namespace IntelligentSpark\Hooks;

class CategoryScope {


    public function findCategories($objModule) {


        switch ($objModule->iso_category_scope) {
            case 'custom':
                return deserialize($objModule->iso_custom_categories);
                break;
            default:
                return array();
                break;
        }


    }
}
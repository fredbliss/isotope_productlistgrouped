<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2016 Intelligent Spark
 *
 * @package Isotope Shipping Zones Advanced
 * @link       http://isotopeecommerce.org
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Register PSR-0 namespace
 */
if (class_exists('NamespaceClassLoader')) {
    NamespaceClassLoader::add('IntelligentSpark', 'system/modules/isotope_productlistgrouped/library');
}
/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    'mod_iso_productlist_grouped'                  => 'system/modules/isotope_productlistgrouped/templates/modules',
    'mod_iso_productlist_grouped_caching'          => 'system/modules/isotope_productlistgrouped/templates/modules'
));
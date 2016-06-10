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

if (class_exists('NamespaceClassLoader')) {
    /**
     * Register PSR-0 namespace
     */
    NamespaceClassLoader::add('IntelligentSpark', 'system/modules/isotope_productlistgrouped/library');
} 


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    //'be_iso_integrity'                  => 'system/modules/isotope/templates/backend'

));
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

/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'iso_category_scope';

$GLOBALS['TL_DCA']['tl_module']['palettes']['iso_productlistgrouped'] = '{title_legend},name,headline,type;{config_legend},numberOfItems,perPage,iso_perGroup,iso_cols,iso_category_scope,iso_list_where,iso_newFilter,iso_filterModules,iso_listingSortField,iso_listingSortDirection;{redirect_legend},iso_addProductJumpTo,iso_jump_first;{reference_legend:hide},defineRoot;{template_legend:hide},customTpl,iso_list_layout,iso_gallery,iso_cols,iso_use_quantity,iso_hide_list,iso_includeMessages,iso_emptyMessage,iso_emptyFilter,iso_buttons;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['fields']['iso_category_scope']['options'][] = 'custom';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['iso_category_scope_custom'] = 'iso_custom_categories';

$GLOBALS['TL_DCA']['tl_module']['fields']['iso_perGroup'] = array
(
    'label'						=> &$GLOBALS['TL_LANG']['tl_module']['iso_perGroup'],
    'exclude'					=> true,
    'default'					=> 0,
    'inputType'					=> 'text',
    'eval'						=> array('maxlength'=>64, 'rgxp'=>'numeric', 'tl_class'=>'w50'),
    'sql'                       => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['iso_custom_categories'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['iso_custom_categories'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'foreignKey'              => 'tl_page.title',
    'eval'                    => array('tl_class'=>'clr', 'multiple'=>true, 'fieldType'=>'checkbox', 'orderField'=>'orderPages', 'mandatory'=>true),
    'sql'                     => "blob null",
    'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
);
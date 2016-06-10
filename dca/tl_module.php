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
$GLOBALS['TL_DCA']['tl_module']['palettes']['iso_productlistgrouped'] = '{title_legend},name,headline,type;{config_legend},perPage,iso_perGroup,iso_cols,iso_category_scope,iso_list_where,iso_filterModules,iso_listingSortField,iso_listingSortDirection;{redirect_legend},iso_reader_jumpTo,iso_addProductJumpTo,iso_jump_first;{reference_legend:hide},defineRoot;{template_legend:hide},iso_list_layout,iso_use_quantity,iso_hide_list,iso_includeMessages,iso_emptyMessage,iso_emptyFilter,iso_buttons;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['iso_category_scope_custom'] = 'iso_custom_categories';

$GLOBALS['TL_DCA']['tl_module']['fields']['iso_perGroup'] = array
(
    'label'						=> &$GLOBALS['TL_LANG']['tl_module']['iso_perGroup'],
    'exclude'					=> true,
    'default'					=> 0,
    'inputType'					=> 'text',
    'eval'						=> array('maxlength'=>64, 'rgxp'=>'numeric', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['iso_category_scope'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['iso_category_scope'],
    'exclude'                 => true,
    'inputType'               => 'radio',
    'default'				  => 'current_category',
    'options'				  => array('current_category', 'current_and_first_child', 'current_and_all_children', 'parent', 'product', 'article', 'global','custom'),
    'reference'				  => &$GLOBALS['TL_LANG']['tl_module']['iso_category_scope_ref'],
    'eval'					  => array('submitOnChange'=>true,'tl_class'=>'w50 w50h', 'helpwizard'=>true),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['iso_custom_categories'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['iso_custom_categories'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'eval'                    => array('tl_class'=>'clr', 'fieldType'=>'checkbox', 'allowMultiple'=>true, 'mandatory'=>true),
);
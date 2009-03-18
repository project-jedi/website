<?php
// $Id: xoops_version.php,v 1.6 2003/02/12 11:39:00 okazu Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
// Author: Raul Recio (AKA UNFOR)                                            //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

$modversion['name']		= _MI_PARTNERS_NAME;
$modversion['version']		= 1.1;
$modversion['author'] = 'Raul Recio (AKA UNFOR)';
$modversion['description']	= _MI_PARTNERS_DESC;
$modversion['credits']		= "The XOOPS Project";
$modversion['license']		= "GPL see LICENSE";
$modversion['help']		= "xoopspartners.html";
$modversion['official']		= 1;
$modversion['image']		= "images/logo.png";
$modversion['dirname']		= "xoopspartners";

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
// Tables created by sql file (without prefix!)
$modversion['tables'][0]	= "partners";

// Admin things
$modversion['hasAdmin']		= 1;
$modversion['adminindex']	= "admin/index.php";
$modversion['adminmenu']	= "admin/menu.php";

// Blocks
$modversion['blocks'][1]['file']	= "partners.php";
$modversion['blocks'][1]['name']	= _MI_PARTNERS_NAME;
$modversion['blocks'][1]['description']	= _MI_PARTNERS_DESC;
$modversion['blocks'][1]['show_func']	= "b_xoopsPartners_show";
$modversion['blocks'][1]['edit_func']	= "b_xoopsPartners_edit";
$modversion['blocks'][1]['options']	= "1|1|1|1|1|hits|DESC";
$modversion['blocks'][1]['template']	= 'xoopspartners_block_site.html';

// Menu
$modversion['hasMain']		= 1;

// Templates
$modversion['templates'][1]['file']	= 'xoopspartners_index.html';
$modversion['templates'][1]['description'] = 'Partners main Screen';
$modversion['templates'][2]['file']	= 'xoopspartners_join.html';
$modversion['templates'][2]['description'] = 'Shows Join to the partners Form';

// Config Settings (only for modules that need config settings generated automatically)

// name of config option for accessing its specified value. i.e. $xoopsModuleConfig['storyhome']
$modversion['config'][1]['name']	= 'cookietime';

// title of this config option displayed in config settings form
$modversion['config'][1]['title']	= '_MI_RECLICK';
$modversion['config'][1]['description']	= '';

// form element type used in config form for this option. can be one of either textbox, textarea, select, select_multi, yesno, group, group_multi
$modversion['config'][1]['formtype']	= 'select';

// value type of this config option. can be one of either int, text, float, array, or other
// form type of 'group_multi', 'select_multi' must always be 'array'
// form type of 'yesno', 'group' must be always be 'int'
$modversion['config'][1]['valuetype']	= 'int';

// the default value for this option
// ignore it if no default
// 'yesno' formtype must be either 0(no) or 1(yes)
$modversion['config'][1]['default']	= 86400;
$modversion['config'][1]['options']	= array('_MI_HOUR' => '3600','_MI_3HOURS' => '10800','_MI_5HOURS' =>  '18000','_MI_10HOURS'  =>  '36000','_MI_DAY' => '86400');

$modversion['config'][2]['name']	= 'modlimit';
$modversion['config'][2]['title']	= '_MI_MLIMIT';
$modversion['config'][2]['description']	= '_MI_MLIMITDSC';
$modversion['config'][2]['formtype']	= 'textbox';
$modversion['config'][2]['valuetype']	= 'int';
$modversion['config'][2]['default']	= 5;

$modversion['config'][3]['name']	= 'modshow';
$modversion['config'][3]['title']	= '_MI_MSHOW';
$modversion['config'][3]['description']	= '_MI_MSHOWDSC';
$modversion['config'][3]['formtype']	= 'select';
$modversion['config'][3]['valuetype']	= 'int';
$modversion['config'][3]['default']	= 1;
$modversion['config'][3]['options']	= array('_MI_IMAGES' => 1, '_MI_TEXT' => 2, '_MI_BOTH' => 3);

$modversion['config'][4]['name']	= 'modorder';
$modversion['config'][4]['title']	= '_MI_MORDER';
$modversion['config'][4]['description']	= '_MI_MORDERDSC';
$modversion['config'][4]['formtype']	= 'select';
$modversion['config'][4]['valuetype']	= 'text';
$modversion['config'][4]['default']	= 'hits';
$modversion['config'][4]['options']	= array('_MI_ID' => 'id', '_MI_HITS' => 'hits', '_MI_TITLE' => 'title', '_MI_WEIGHT' => 'weight');

$modversion['config'][5]['name']	= 'modorderd';
$modversion['config'][5]['title']	= '_MI_MORDER';
$modversion['config'][5]['description']	= '_MI_MORDERDSC';
$modversion['config'][5]['formtype']	= 'select';
$modversion['config'][5]['valuetype']	= 'text';
$modversion['config'][5]['default']	= 'DESC';
$modversion['config'][5]['options']	= array('_ASCENDING' => 'ASC', '_DESCENDING' => 'DESC');
?>
<?php
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
// Author: Tobias Liegl (AKA CHAPI)                                          //
// Site: http://www.chapi.de                                                 //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

$modversion['name']		    = _MIJS_JSTEERING_NAME;
$modversion['version']		= 1.5;
$modversion['author']       = 'Tobias Liegl (AKA CHAPI)';
$modversion['description']	= _MIJS_JSTEERING_DESC;
$modversion['credits']		= "The XOOPS Project";
$modversion['license']		= "GPL see LICENSE";
$modversion['help']		    = "";
$modversion['official']		= 0;
$modversion['image']		= "images/logo.png";
$modversion['dirname']		= _MIJS_DIR_NAME;

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0]	= _MIJS_DIR_NAME;

// Admin things
$modversion['hasAdmin']		= 1;
$modversion['adminindex']	= "admin/index.php";
$modversion['adminmenu']	= "admin/menu.php";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "jsteering_search";

// Menu
$modversion['hasMain'] = 1;
global $xoopsDB;

// Submenu Items

$result = $xoopsDB->query("SELECT storyid, title, homepage, submenu FROM ".$xoopsDB->prefix("jsteering")." WHERE homepage='0' AND submenu='1'");
$i = 1;

while (list($storyid, $title) = $xoopsDB->fetchRow($result))
{
	$modversion['sub'][$i]['name'] = $title;
	$modversion['sub'][$i]['url'] = "index.php?id=".$storyid."";
	$i++;
}

// Smarty
$modversion['use_smarty'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'tc_index.html';
$modversion['templates'][1]['description'] = "Page Layout";

// Blocks
$modversion['blocks'][1]['file'] = "tc_navigation.php";
$modversion['blocks'][1]['name'] = _MIJS_JTC_BNAME1;
$modversion['blocks'][1]['description'] = "Builds the navigation";
$modversion['blocks'][1]['show_func'] = "jsteering_block_nav";
$modversion['blocks'][1]['template'] = 'tc_nav_block.html';

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'index.php';

$modversion['config'][1]['name'] = 'tc_wysiwyg';
$modversion['config'][1]['title'] = '_MIJS_WYSIWYG';
$modversion['config'][1]['description'] = '_MIJS_WYSIWYG_DESC';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 0;
?>
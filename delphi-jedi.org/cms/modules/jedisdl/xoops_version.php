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

$modversion['name']	    = _MI_jedisdl_NAME;
$modversion['version']	    = 1.0;
$modversion['author']       = 'Tobias Liegl (AKA CHAPI)';
$modversion['description']  = _MI_jedisdl_DESC;
$modversion['credits']		= "The XOOPS Project";
$modversion['license']		= "GPL see LICENSE";
$modversion['help']		    = "";
$modversion['official']		= 0;
$modversion['image']		= "images/logo.png";
$modversion['dirname']		= _MI_JSDL_DIR_NAME;

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0]	= _MI_JSDL_DIR_NAME;

// Admin things
$modversion['hasAdmin']		= 1;
$modversion['adminindex']	= "admin/index.php";
$modversion['adminmenu']	= "admin/menu.php";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "jedisdl_search";

// Menu
$modversion['hasMain'] = 1;

// Smarty
$modversion['use_smarty'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'jedisdl_index.html';
$modversion['templates'][1]['description'] = "Page Layout";

// Blocks
$modversion['blocks'][1]['file'] = "tc_navigation.php";
$modversion['blocks'][1]['name'] = _MI_JSDL_BNAME1;
$modversion['blocks'][1]['description'] = "Builds the navigation";
$modversion['blocks'][1]['show_func'] = "jedisdl_block_nav";
$modversion['blocks'][1]['template'] = 'jedisdl_navigation_block.html';

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'index.php';
?>
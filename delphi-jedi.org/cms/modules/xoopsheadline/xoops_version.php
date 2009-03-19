<?php
// $Id: xoops_version.php,v 1.2 2003/04/17 10:11:38 okazu Exp $
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
//  ------------------------------------------------------------------------ //
$modversion['name'] = _MI_HEADLINES_NAME;
$modversion['version'] = 1.00;
$modversion['description'] = _MI_HEADLINES_DESC;
$modversion['author'] = "Kazumi Ono<br>( http://www.xoops.org/ http://jp.xoops.org/ http://www.myweb.ne.jp/ )";
$modversion['credits'] = "The XOOPS Project";
$modversion['help'] = "headlines.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/headline_slogo.png";
$modversion['dirname'] = "xoopsheadline";

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "xoopsheadline";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Blocks
$modversion['blocks'][1]['file'] = "headline.php";
$modversion['blocks'][1]['name'] = _MI_HEADLINES_BNAME;
$modversion['blocks'][1]['description'] = "Shows headline news via RDF/RSS news feed";
$modversion['blocks'][1]['show_func'] = 'b_xoopsheadline_show';
$modversion['blocks'][1]['template'] = 'xoopsheadline_block_rss.html';

// Menu
$modversion['hasMain'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'xoopsheadline_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'xoopsheadline_feed.html';
$modversion['templates'][2]['description'] = '';
?>
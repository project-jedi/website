<?php
// $Id: xoops_version.php,v 1.5 2003/02/12 11:38:59 okazu Exp $
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
$modversion['name'] = _MI_MEMBERS_NAME;
$modversion['version'] = 1.00;
$modversion['description'] = _MI_MEMBERS_DESC;
$modversion['credits'] = "The XOOPS Project";
$modversion['author'] = "Kazumi Ono<br />( http://www.myweb.ne.jp/ )";
$modversion['help'] = "xoopsmembers.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = "yes";
$modversion['image'] = "members_slogo.png";
$modversion['dirname'] = "xoopsmembers";

// Admin things
$modversion['hasAdmin'] = 0;
$modversion['adminmenu'] = "";

// Menu
$modversion['hasMain'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'xoopsmembers_searchform.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'xoopsmembers_searchresults.html';
$modversion['templates'][2]['description'] = '';
?>
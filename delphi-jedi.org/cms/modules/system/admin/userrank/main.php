<?php
// $Id: main.php,v 1.6 2003/03/30 06:11:46 okazu Exp $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Manage user rank.
 * @copyright XOOPS Project 
 * @todo	Fix register_globals!
 **/

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
	exit("Access Denied");
}

/**
 * load the rank management functions 
 */
include_once XOOPS_ROOT_PATH."/modules/system/admin/userrank/userrank.php";

$op = 'RankForumAdmin';

// hotfix for register_globals=off (Bunny)
extract($HTTP_POST_VARS);
extract($HTTP_POST_FILES);

if (isset($HTTP_GET_VARS['rank_id'])) {
	$rank_id = intval($HTTP_GET_VARS['rank_id']);
}

if (isset($HTTP_GET_VARS['op'])) {
	$op = $HTTP_GET_VARS['op'];
}

switch ($op) {
case "RankForumAdmin":
	RankForumAdmin();
    break;
case "RankForumEdit":
	RankForumEdit($rank_id);
    break;
case "RankForumDel":
	RankForumDel($rank_id, $ok);
    break;
case "RankForumAdd":
	RankForumAdd($rank_title,$rank_min,$rank_max,$rank_image,$rank_special);
    break;
case "RankForumSave":
	RankForumSave($rank_id, $rank_title, $rank_min, $rank_max, $rank_image, $rank_special, $old_rank);
    break;
default:
	RankForumAdmin();
    break;
}
?>
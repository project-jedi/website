<?php
// $Id: config.php,v 1.6 2003/03/31 09:19:52 mvandam Exp $
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

// You shouldn't have to change any of these
$bbUrl['root'] = XOOPS_URL."/modules/newbb/";
$bbUrl['admin'] = $bbUrl['root']."admin";
$bbUrl['images'] = $bbUrl['root']."images";
$bbUrl['smilies'] = $bbUrl['images']."smiles";

/* -- Cookie settings (lastvisit) -- */
// Most likely you can leave this be, however if you have problems
// logging into the forum set this to your domain name, without
// the http://
// For example, if your forum is at http://www.mysite.com/phpBB then
// set this value to
// $bbCookie['domain'] = "www.mysite.com";
$bbCookie['domain'] = "";

// It should be safe to leave these alone as well.
$bbCookie['path'] = str_replace(basename($HTTP_SERVER_VARS['PHP_SELF']),"",$HTTP_SERVER_VARS['PHP_SELF']);
$bbCookie['secure'] = false;

/* -- You shouldn't have to change anything after this point */
/* -- Images -- */
$bbImage['post'] = $bbUrl['images']."/post.gif";
$bbImage['newposts_forum'] = $bbUrl['images']."/folder_new_big.gif";
$bbImage['folder_forum'] = $bbUrl['images']."/folder_big.gif";
$bbImage['locked_forum'] = $bbUrl['images']."/folder_locked_big.gif";
$bbImage['folder_topic'] = $bbUrl['images']."/folder.gif";
$bbImage['hot_folder_topic'] = $bbUrl['images']."/hot_folder.gif";
$bbImage['newposts_topic'] = $bbUrl['images']."/red_folder.gif";
$bbImage['hot_newposts_topic'] = $bbUrl['images']."/hot_red_folder.gif";
$bbImage['locked_topic'] = $bbUrl['images']."/lock.gif";
$bbImage['locktopic'] = $bbUrl['images']."/lock_topic.gif";
$bbImage['deltopic'] = $bbUrl['images']."/del_topic.gif";
$bbImage['movetopic'] = $bbUrl['images']."/move_topic.gif";
$bbImage['unlocktopic'] = $bbUrl['images']."/unlock_topic.gif";
$bbImage['sticky'] = $bbUrl['images']."/sticky.gif";
$bbImage['unsticky'] = $bbUrl['images']."/unsticky.gif";
$bbImage['newtopic'] = $bbUrl['images']."/new_topic-dark.jpg";
$bbImage['folder_sticky'] = $bbUrl['images']."/folder_sticky.gif";

// set expire dates: one for a year, one for 10 minutes
$expiredate1 = time() + 3600 * 24 * 365;
$expiredate2 = time() + 600;

// update LastVisit cookie. This cookie is updated each time auth.php runs
setcookie("NewBBLastVisit", time(), $expiredate1,  $bbCookie['path'], $bbCookie['domain'], $bbCookie['secure']);

// set LastVisitTemp cookie, which only gets the time from the LastVisit
// cookie if it does not exist yet
// otherwise, it gets the time from the LastVisitTemp cookie
if (!isset($HTTP_COOKIE_VARS["NewBBLastVisitTemp"])) {
	if(isset($HTTP_COOKIE_VARS["NewBBLastVisit"])){
		$temptime = $HTTP_COOKIE_VARS["NewBBLastVisit"];
	}else{
		$temptime = time();
	}
}
else {
	$temptime = $HTTP_COOKIE_VARS["NewBBLastVisitTemp"];
}

// set cookie.
setcookie("NewBBLastVisitTemp", $temptime ,$expiredate2, $bbCookie['path'], $bbCookie['domain'], $bbCookie['secure']);

// set vars for all scripts
$last_visit = $temptime;

?>

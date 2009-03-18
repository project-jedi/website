<?php
// $Id: newtopic.php,v 1.8 2003/03/26 07:09:04 okazu Exp $
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

include 'header.php';
foreach (array('forum', 'order') as $getint) {
	${$getint} = isset($HTTP_GET_VARS[$getint]) ? intval($HTTP_GET_VARS[$getint]) : 0;
}
$viewmode = (isset($HTTP_GET_VARS['viewmode']) && $HTTP_GET_VARS['viewmode'] != 'flat') ? 'thread' : 'flat';
if ( empty($forum) ) {
	redirect_header("index.php", 2, _MD_ERRORFORUM);
	exit();
} else {
	$sql = "SELECT forum_type, forum_name, forum_access, allow_html, allow_sig, posts_per_page, hot_threshold, topics_per_page FROM ".$xoopsDB->prefix("bb_forums")." WHERE forum_id = $forum";
	if ( !$result = $xoopsDB->query($sql) ) {
		redirect_header('index.php',2,_MD_ERROROCCURED);
		exit();
	}
	$forumdata = $xoopsDB->fetchArray($result);
	if ( $forumdata['forum_type'] == 1 ) {
		// To get here, we have a logged-in user. So, check whether that user is allowed to post in
		// this private forum.
		$accesserror = 0; //initialize
		if ( $xoopsUser ) {
			//check if the user has forum admin right
			if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
				if ( !check_priv_forum_auth($xoopsUser->uid(), $forum, true) ) {
					$accesserror = 1;
				}
			}
		} else {
			$accesserror = 1;
		}
		if ( $accesserror == 1 ) {
			redirect_header("viewforum.php?order=$order&viewmode=$viewmode&forum=$forum",2,_MD_NORIGHTTOPOST);
			exit();
		}
		// Ok, looks like we're good.
	} else {
		$accesserror = 0;
		if ( $forumdata['forum_access'] == 3 ) {
			if ( $xoopsUser ) {
				if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
					if ( !is_moderator($forum, $xoopsUser->uid()) ) {
						$accesserror = 1;
					}
				}
			} else {
				$accesserror = 1;
			}
		} elseif ( $forumdata['forum_access'] == 1 && !$xoopsUser ) {
			$accesserror = 1;
		}
		if ( $accesserror == 1 ) {
			redirect_header("viewforum.php?order=$order&viewmode=$viewmode&forum=$forum",2,_MD_NORIGHTTOPOST);
			exit();
		}
    }
	include XOOPS_ROOT_PATH.'/header.php';
	$istopic = 1;
	$pid=0;
	$subject = "";
	$message = "";
	$myts =& MyTextSanitizer::getInstance();
	$hidden = "";
	unset($post_id);
	unset($topic_id);
	include 'include/forumform.inc.php';
	include XOOPS_ROOT_PATH.'/footer.php';
}
?>
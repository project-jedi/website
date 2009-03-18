<?php
// $Id: delete.php,v 1.7 2003/03/10 13:32:05 okazu Exp $
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

$ok = 0;
$forum = isset($HTTP_GET_VARS['forum']) ? intval($HTTP_GET_VARS['forum']) : 0;
$post_id = isset($HTTP_GET_VARS['post_id']) ? intval($HTTP_GET_VARS['post_id']) : 0;
$topic_id = isset($HTTP_GET_VARS['topic_id']) ? intval($HTTP_GET_VARS['topic_id']) : 0;
$order = isset($HTTP_GET_VARS['order']) ? intval($HTTP_GET_VARS['order']) : 0;
$viewmode = (isset($HTTP_GET_VARS['viewmode']) && $HTTP_GET_VARS['viewmode'] != 'flat') ? 'thread' : 'flat';
extract($HTTP_POST_VARS, EXTR_OVERWRITE);
if ( empty($forum) ) {
	redirect_header("index.php", 2, _MD_ERRORFORUM);
	exit();
} elseif ( empty($post_id) ) {
	redirect_header("viewforum.php?forum=$forum", 2, _MD_ERRORPOST);
	exit();
}

if ( $xoopsUser ) {
	if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
		if ( !is_moderator($forum, $xoopsUser->uid()) ) {
			redirect_header("viewtopic.php?topic_id=$topic_id&order=$order&viewmode=$viewmode&pid=$pid&forum=$forum", 2, _MD_DELNOTALLOWED);
			exit();
		}
	}
} else {
	redirect_header("viewtopic.php?topic_id=$topic_id&order=$order&viewmode=$viewmode&pid=$pid&forum=$forum", 2, _MD_DELNOTALLOWED);
	exit();
}

include_once 'class/class.forumposts.php';

if ( !empty($ok) ) {
	if ( !empty($post_id) ) {
		$post = new ForumPosts($post_id);
		$post->delete();
		sync($post->forum(), "forum");
		sync($post->topic(), "topic");
	}
	if ( $post->istopic() ) {
		redirect_header("viewforum.php?forum=$forum", 2, _MD_POSTSDELETED);
		exit();
	} else {
		redirect_header("viewtopic.php?topic_id=$topic_id&order=$order&viewmode=$viewmode&pid=$pid&forum=$forum", 2, _MD_POSTSDELETED);
		exit();
	}
} else {
	include XOOPS_ROOT_PATH."/header.php";
	xoops_confirm(array('post_id' => $post_id, 'viewmode' => $viewmode, 'order' => $order, 'forum' => $forum, 'topic_id' => $topic_id, 'ok' => 1), 'delete.php', _MD_AREUSUREDEL);
}
include XOOPS_ROOT_PATH.'/footer.php';
?>
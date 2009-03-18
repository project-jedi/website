<?php
// $Id: search.php,v 1.7 2003/05/02 18:19:43 okazu Exp $
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

if ( !isset($HTTP_POST_VARS['submit']) ) {
	$xoopsOption['template_main']= 'newbb_search.html';
	include XOOPS_ROOT_PATH.'/header.php';
	$xoopsTpl->assign("lang_keywords", _MD_KEYWORDS);
	$xoopsTpl->assign("lang_searchany", _MD_SEARCHANY);
	$xoopsTpl->assign("lang_searchall", _MD_SEARCHALL);
	$xoopsTpl->assign("lang_forumc", _MD_FORUMC);
	$xoopsTpl->assign("lang_searchallforums", _MD_SEARCHALLFORUMS);
	$xoopsTpl->assign("lang_sortby", _MD_SORTBY);
	$xoopsTpl->assign("lang_date", _MD_DATE);
	$xoopsTpl->assign("lang_topic", _MD_TOPIC);
	$xoopsTpl->assign("lang_forum", _MD_FORUM);
	$xoopsTpl->assign("lang_username", _MD_USERNAME);
	$xoopsTpl->assign("lang_searchin", _MD_SEARCHIN);
	$xoopsTpl->assign("lang_subject", _MD_SUBJECT);
	$xoopsTpl->assign("lang_body", _MD_BODY);

	$query = 'SELECT forum_name,forum_id FROM '.$xoopsDB->prefix('bb_forums').' WHERE forum_type != 1';
	if ( !$result = $xoopsDB->query($query) ) {
		exit("<big>"._MD_ERROROCCURED."</big><hr />"._MD_COULDNOTQUERY);
	}
	$select = '<select name="forum">';
	while ( $row = $xoopsDB->fetchArray($result) ) {
		$select .= '<option value="'.$row['forum_id'].'">'.$row['forum_name'].'</option>
		';
	}
	$select .= '</select>';
	$xoopsTpl->assign("forum_selection_box", $select);

} else {
	$xoopsOption['template_main']= 'newbb_searchresults.html';
	include XOOPS_ROOT_PATH."/header.php";
	$forum = (isset($HTTP_POST_VARS['forum']) && $HTTP_POST_VARS['forum'] != 'all') ? intval($HTTP_POST_VARS['forum']) : 'all';
	$xoopsTpl->assign("lang_keywords", _MD_KEYWORDS);
	$xoopsTpl->assign("lang_searchany", _MD_SEARCHANY);
	$xoopsTpl->assign("lang_searchall", _MD_SEARCHALL);
	$addquery = '';
	$subquery = '';
	$query = 'SELECT u.uid,f.forum_id, p.topic_id, u.uname, p.post_time,t.topic_title,t.topic_views,t.topic_replies,f.forum_name FROM '.$xoopsDB->prefix('bb_posts').' p, '.$xoopsDB->prefix('bb_posts_text').' pt, '.$xoopsDB->prefix('users').' u, '.$xoopsDB->prefix('bb_forums').' f,'.$xoopsDB->prefix('bb_topics').' t';
	$myts = MyTextSanitizer::getInstance();
	if ( isset($HTTP_POST_VARS['term']) && trim($HTTP_POST_VARS['term']) != "" ) {
		$terms = split(' ', $myts->oopsAddSlashes($HTTP_POST_VARS['term']));		// Get all the words into an array
		if ( strlen($terms[0]) < 4 ) {

		}
		$addquery .= "(pt.post_text LIKE '%$terms[0]%'";
		$subquery .= "(t.topic_title LIKE '%$terms[0]%'";
		if ( $HTTP_POST_VARS['addterms'] == "any" ) {					// AND/OR relates to the ANY or ALL on Search Page
			$andor = 'OR';
		} else {
			$andor = 'AND';
		}
		$size = count($terms);
		for ( $i = 1; $i < $size; $i++ ) {
			if ( strlen($terms[$i]) < 4 ) {

			}
			$addquery .= " $andor pt.post_text LIKE '%$terms[$i]%'";
			$subquery .= " $andor t.topic_title LIKE '%$terms[$i]%'";
		}
		$addquery .= ')';
		$subquery .= ')';
	}
	if ($forum !='all' ) {
		if ( isset($addquery) ) {
			$addquery .= ' AND ';
			$subquery .= ' AND ';
		}
		$forum = intval($HTTP_POST_VARS['forum']);
		$addquery .= ' p.forum_id='.$forum;
		$subquery .= ' p.forum_id='.$forum;
	}
	if ( isset($HTTP_POST_VARS['search_username']) && trim($HTTP_POST_VARS['search_username']) != "" ) {
		$search_username = $myts->oopsAddSlashes(trim($HTTP_POST_VARS['search_username']));
		if ( !$result = $xoopsDB->query("SELECT uid FROM ".$xoopsDB->prefix("users")." WHERE uname='$search_username'") ) {
			redirect_header('search.php',1,_MD_ERROROCCURED);
			exit();
		}
		$row = $xoopsDB->fetchArray($result);
		if ( !$row ) {
			redirect_header('search.php',1,_MD_USERNOEXIST);
			exit();
		}
		if ( isset($addquery) ) {
			$addquery .= " AND p.uid=".$row['uid']." AND u.uname='$search_username'";
			$subquery .= " AND p.uid=".$row['uid']." AND u.uname='$search_username'";
		} else {
			$addquery .= " p.uid=".$row['uid']." AND u.uname='$search_username'";
			$subquery .= " p.uid=".$row['uid']." AND u.uname='$search_username'";
		}
	}
	if ( isset($addquery) ) {
		switch ( $HTTP_POST_VARS['searchboth'] ) {
		case 'both':
			$query .= " WHERE ( ($subquery) OR ($addquery) ) AND ";
		    break;
		case 'title':
			$query .= " WHERE ( $subquery ) AND ";
			break;
		case 'text':
		default:
			$query .= " WHERE ( $addquery ) AND ";
			break;
		}
	} else {
		$query .= ' WHERE ';
	}
	$query .= ' p.post_id = pt.post_id AND p.topic_id = t.topic_id AND p.forum_id = f.forum_id AND p.uid = u.uid AND f.forum_type != 1';
	$allowed = array("t.topic_title", "t.topic_views", "t.topic_replies", "f.forum_name", "u.uname");
	$sortby = (!in_array($HTTP_POST_VARS['sortby'], $allowed)) ? "u.uid" : $HTTP_POST_VARS['sortby'];
	$query .= ' ORDER BY '.$sortby;
	if ( !$result = $xoopsDB->query($query,100,0) ) {
		exit("<big>"._MD_ERROROCCURED."</big><hr />"._MD_COULDNOTQUERY);
	}
	if ( !$row = $xoopsDB->getRowsNum($result) ) {
		$xoopsTpl->assign("lang_nomatch", _MD_NOMATCH);
	} else {
		while ( $row = $xoopsDB->fetchArray($result) ) {
			$xoopsTpl->append('results', array('forum_name' => $myts->makeTboxData4Show($row['forum_name']), 'forum_id' => $row['forum_id'], 'topic_id' => $row['topic_id'], 'topic_title' => $myts->makeTboxData4Show($row['topic_title']), 'topic_replies' => $row['topic_replies'], 'topic_views' => $row['topic_views'], 'user_id' => $row['uid'], 'user_name' => $myts->makeTboxData4Show($row['uname']), 'post_time' => formatTimestamp($row['post_time'], "m")));
		}
	}
}
$xoopsTpl->assign("lang_forumindex", sprintf(_MD_FORUMINDEX,$xoopsConfig['sitename']));
$xoopsTpl->assign("lang_search", _MD_SEARCH);
$xoopsTpl->assign("lang_forum", _MD_FORUM);
$xoopsTpl->assign("lang_topic", _MD_TOPIC);
$xoopsTpl->assign("lang_author", _MD_AUTHOR);
$xoopsTpl->assign('lang_replies', _MD_REPLIES);
$xoopsTpl->assign('lang_views', _MD_VIEWS);
$xoopsTpl->assign("lang_possttime", _MD_POSTTIME);
$xoopsTpl->assign("lang_searchresults", _MD_SEARCHRESULTS);
$xoopsTpl->assign("img_folder", $bbImage['folder_topic']);
include XOOPS_ROOT_PATH.'/footer.php';
?>
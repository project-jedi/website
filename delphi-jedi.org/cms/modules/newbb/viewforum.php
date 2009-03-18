<?php
// $Id: viewforum.php,v 1.14 2003/07/18 07:56:08 okazu Exp $
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

include "header.php";

$forum = intval($HTTP_GET_VARS['forum']);
if ( $forum < 1 ) {
	redirect_header("index.php", 2, _MD_ERRORFORUM);
	exit();
}
$sql = 'SELECT forum_type, forum_name, forum_access, allow_html, allow_sig, posts_per_page, hot_threshold, topics_per_page FROM '.$xoopsDB->prefix('bb_forums').' WHERE forum_id = '.$forum;
if ( !$result = $xoopsDB->query($sql) ) {
	redirect_header("index.php", 2, _MD_ERRORCONNECT);
	exit();
}
if ( !$forumdata = $xoopsDB->fetchArray($result) ) {
	redirect_header("index.php", 2, _MD_ERROREXIST);
	exit();
}
// this page uses smarty template
// this must be set before including main header.php
$xoopsOption['template_main'] = 'newbb_viewforum.html';
include XOOPS_ROOT_PATH."/header.php";
$can_post = 0;
$show_reg = 0;
if ( $forumdata['forum_type'] == 1 ) {
	// this is a private forum.
	$xoopsTpl->assign('is_private_forum', true);
	$accesserror = 0;
	if ( $xoopsUser ) {
		if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
			if ( !check_priv_forum_auth($xoopsUser->getVar("uid"), $forum, false) ) {
				$accesserror = 1;
			}
		}
	} else {
		$accesserror = 1;
	}
	if ( $accesserror == 1 ) {
		redirect_header("index.php",2,_MD_NORIGHTTOACCESS);
		exit();
	}
	$can_post = 1;
	$show_reg = 1;
} else {
	// this is not a priv forum
	$xoopsTpl->assign('is_private_forum', false);
	if ( $forumdata['forum_access'] == 1 ) {
		// this is a reg user only forum
		if ( $xoopsUser ) {
			$can_post = 1;
		} else {
			$show_reg = 1;
		}
	} elseif ( $forumdata['forum_access'] == 2 ) {
		// this is an open forum
		$can_post = 1;
	} else {
		// this is an admin/moderator only forum
		if ( $xoopsUser ) {
			if ( $xoopsUser->isAdmin() || is_moderator($forum, $xoopsUser->uid()) ) {
				$can_post = 1;
			}
		}
	}
}

$xoopsTpl->assign("forum_id", $forum);
if ( $can_post == 1 ) {
	$xoopsTpl->assign('viewer_can_post', true);
  	$xoopsTpl->assign('forum_post_or_register', "<a href=\"newtopic.php?forum=".$forum."\"><img src=\"".$bbImage['post']."\" alt=\""._MD_POSTNEW."\" /></a>");
} else {
	$xoopsTpl->assign('viewer_can_post', false);
	if ( $show_reg == 1 ) {
		$xoopsTpl->assign('forum_post_or_register', '<a href="'.XOOPS_URL.'/user.php?xoops_redirect='.$xoopsRequestUri.'">'._MD_REGTOPOST.'</a>');
	} else {
		$xoopsTpl->assign('forum_post_or_register', "");
	}
}
$xoopsTpl->assign('forum_index_title', sprintf(_MD_FORUMINDEX,$xoopsConfig['sitename']));
$xoopsTpl->assign('forum_image_folder', $bbImage['folder_topic']);
$myts =& MyTextSanitizer::getInstance();
$xoopsTpl->assign('forum_name', $myts->makeTboxData4Show($forumdata['forum_name']));
$xoopsTpl->assign('lang_moderatedby', _MD_MODERATEDBY);

$forum_moderators = "";
$count = 0;
$moderators = get_moderators($forum);
foreach ( $moderators as $mods ) {
	foreach ( $mods as $mod_id => $mod_name ) {
		if ( $count > 0 ) {
			$forum_moderators .= ", ";
		}
		$forum_moderators .=  '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$mod_id.'">'.$myts->makeTboxData4Show($mod_name).'</a>';
		$count = 1;
	}
}
$xoopsTpl->assign('forum_moderators', $forum_moderators);


$sel_sort_array = array("t.topic_title"=>_MD_TOPICTITLE, "t.topic_replies"=>_MD_NUMBERREPLIES, "u.uname"=>_MD_TOPICPOSTER, "t.topic_views"=>_MD_VIEWS, "p.post_time"=>_MD_LASTPOSTTIME);
if ( !isset($HTTP_GET_VARS['sortname']) || !in_array($HTTP_GET_VARS['sortname'], array_keys($sel_sort_array)) ) {
	$sortname = "p.post_time";
} else {
	$sortname = $HTTP_GET_VARS['sortname'];
}

$xoopsTpl->assign('lang_sortby', _MD_SORTEDBY);

$forum_selection_sort = '<select name="sortname">';
foreach ( $sel_sort_array as $sort_k => $sort_v ) {
	$forum_selection_sort .= '<option value="'.$sort_k.'"'.(($sortname == $sort_k) ? ' selected="selected"' : '').'>'.$sort_v.'</option>';
}
$forum_selection_sort .= '</select>';

// assign to template
$xoopsTpl->assign('forum_selection_sort', $forum_selection_sort);

$sortorder = (!isset($HTTP_GET_VARS['sortorder']) || $HTTP_GET_VARS['sortorder'] != "ASC") ? "DESC" : "ASC";
$forum_selection_order = '<select name="sortorder">';
$forum_selection_order .= '<option value="ASC"'.(($sortorder == "ASC") ? ' selected="selected"' : '').'>'._MD_ASCENDING.'</option>';
$forum_selection_order .= '<option value="DESC"'.(($sortorder == "DESC") ? ' selected="selected"' : '').'>'._MD_DESCENDING.'</option>';
$forum_selection_order .= '</select>';

// assign to template
$xoopsTpl->assign('forum_selection_order', $forum_selection_order);

$sortsince = !empty($HTTP_GET_VARS['sortsince']) ? intval($HTTP_GET_VARS['sortsince']) : 100;
$sel_since_array = array(1, 2, 5, 10, 20, 30, 40, 60, 75, 100);
$forum_selection_since = '<select name="sortsince">';
foreach ($sel_since_array as $sort_since_v) {
	$forum_selection_since .= '<option value="'.$sort_since_v.'"'.(($sortsince == $sort_since_v) ? ' selected="selected"' : '').'>'.sprintf(_MD_FROMLASTDAYS,$sort_since_v).'</option>';
}
$forum_selection_since .= '<option value="365"'.(($sortsince == 365) ? ' selected="selected"' : '').'>'.sprintf(_MD_THELASTYEAR,365).'</option>';
$forum_selection_since .= '<option value="1000"'.(($sortsince == 1000) ? ' selected="selected"' : '').'>'.sprintf(_MD_BEGINNING,1000).'</option>';
$forum_selection_since .= '</select>';

// assign to template
$xoopsTpl->assign('forum_selection_since', $forum_selection_since);
$xoopsTpl->assign('lang_go', _MD_GO);

$xoopsTpl->assign('h_topic_link', "viewforum.php?forum=$forum&amp;sortname=t.topic_title&amp;sortsince=$sortsince&amp;sortorder=". (($sortname == "t.topic_title" && $sortorder == "DESC") ? "ASC" : "DESC"));
$xoopsTpl->assign('lang_topic', _MD_TOPIC);

$xoopsTpl->assign('h_reply_link', "viewforum.php?forum=$forum&amp;sortname=t.topic_replies&amp;sortsince=$sortsince&amp;sortorder=". (($sortname == "t.topic_replies" && $sortorder == "DESC") ? "ASC" : "DESC"));
$xoopsTpl->assign('lang_replies', _MD_REPLIES);

$xoopsTpl->assign('h_poster_link', "viewforum.php?forum=$forum&amp;sortname=u.uname&amp;sortsince=$sortsince&amp;sortorder=". (($sortname == "u.uname" && $sortorder == "DESC") ? "ASC" : "DESC"));
$xoopsTpl->assign('lang_poster', _MD_POSTER);

$xoopsTpl->assign('h_views_link', "viewforum.php?forum=$forum&amp;sortname=t.topic_views&amp;sortsince=$sortsince&amp;sortorder=". (($sortname == "t.topic_views" && $sortorder == "DESC") ? "ASC" : "DESC"));
$xoopsTpl->assign('lang_views', _MD_VIEWS);

$xoopsTpl->assign('h_date_link', "viewforum.php?forum=$forum&amp;sortname=p.post_time&amp;sortsince=$sortsince&amp;sortorder=". (($sortname == "p.post_time" && $sortorder == "DESC") ? "ASC" : "DESC"));
$xoopsTpl->assign('lang_date', _MD_DATE);

$startdate = time() - (86400* $sortsince);
$start = !empty($HTTP_GET_VARS['start']) ? intval($HTTP_GET_VARS['start']) : 0;

$sql = 'SELECT t.*, u.uname, u2.uname as last_poster, p.post_time as last_post_time, p.icon FROM '.$xoopsDB->prefix("bb_topics").' t LEFT JOIN '.$xoopsDB->prefix('users').' u ON u.uid = t.topic_poster LEFT JOIN '.$xoopsDB->prefix('bb_posts').' p ON p.post_id = t.topic_last_post_id LEFT JOIN '.$xoopsDB->prefix('users').' u2 ON  u2.uid = p.uid WHERE t.forum_id = '.$forum.' AND (p.post_time > '.$startdate.' OR t.topic_sticky=1) ORDER BY topic_sticky DESC, '.$sortname.' '.$sortorder;
if ( !$result = $xoopsDB->query($sql,$forumdata['topics_per_page'],$start) ) {
	redirect_header('index.php',2,_MD_ERROROCCURED);
	exit();
}

// Read topic 'lastread' times from cookie, if exists
$topic_lastread = !empty($HTTP_COOKIE_VARS['newbb_topic_lastread']) ? unserialize($HTTP_COOKIE_VARS['newbb_topic_lastread']) : array();
while ( $myrow = $xoopsDB->fetchArray($result) ) {

 	if ( empty($myrow['last_poster']) ) {
		$myrow['last_poster'] = $xoopsConfig['anonymous'];
	}
	if ( $myrow['topic_sticky'] == 1 ) {
		$image = $bbImage['folder_sticky'];
	} elseif ( $myrow['topic_status'] == 1 ) {
		$image = $bbImage['locked_topic'];
	} else {
		if ( $myrow['topic_replies'] >= $forumdata['hot_threshold'] ) {
			if ( empty($topic_lastread[$myrow['topic_id']]) || ($topic_lastread[$myrow['topic_id']] < $myrow['last_post_time'] )) {
				$image = $bbImage['hot_newposts_topic'];
			} else {
				$image = $bbImage['hot_folder_topic'];
			}
		} else {
			if ( empty($topic_lastread[$myrow['topic_id']]) || ($topic_lastread[$myrow['topic_id']] < $myrow['last_post_time'] )) {
				$image = $bbImage['newposts_topic'];
			} else {
				$image = $bbImage['folder_topic'];
			}
		}
	}
	$pagination = '';
	$addlink = '';
	$topiclink = 'viewtopic.php?topic_id='.$myrow['topic_id'].'&amp;forum='.$forum;
	$totalpages = ceil(($myrow['topic_replies'] + 1) / $forumdata['posts_per_page']);
	if ( $totalpages > 1 ) {
		$pagination .= '&nbsp;&nbsp;&nbsp;<img src="'.XOOPS_URL.'/images/icons/posticon.gif" /> ';
		for ( $i = 1; $i <= $totalpages; $i++ ) {

			if ( $i > 3 && $i < $totalpages ) {
				$pagination .= "...";
			} else {
				$addlink = '&start='.(($i - 1) * $forumdata['posts_per_page']);
				$pagination .= '[<a href="'.$topiclink.$addlink.'">'.$i.'</a>]';
			}
		}
	}
	if ( $myrow['icon'] ) {
		$topic_icon = '<img src="'.XOOPS_URL.'/images/subject/'.$myrow['icon'].'" alt="" />';
	} else {
		$topic_icon = '<img src="'.XOOPS_URL.'/images/icons/no_posticon.gif" alt="" />';
	}
	if ( $myrow['topic_poster'] != 0 && $myrow['uname'] ) {
		$topic_poster = '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$myrow['topic_poster'].'">'.$myrow['uname'].'</a>';
	} else {
		$topic_poster = '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$myrow['topic_poster'].'">'.$xoopsConfig['anonymous'].'</a>';
	}
	$xoopsTpl->append('topics', array('topic_icon'=>$topic_icon, 'topic_folder'=>$image, 'topic_title'=>$myts->makeTboxData4Show($myrow['topic_title']), 'topic_link'=>$topiclink, 'topic_page_jump'=>$pagination, 'topic_replies'=>$myrow['topic_replies'], 'topic_poster'=>$topic_poster, 'topic_views'=>$myrow['topic_views'], 'topic_last_posttime'=>formatTimestamp($myrow['last_post_time']), 'topic_last_poster'=>$myts->makeTboxData4Show($myrow['last_poster'])));
}

$xoopsTpl->assign('lang_by', _MD_BY);

$xoopsTpl->assign('img_newposts', $bbImage['newposts_topic']);
$xoopsTpl->assign('img_hotnewposts', $bbImage['hot_newposts_topic']);
$xoopsTpl->assign('img_folder', $bbImage['folder_topic']);
$xoopsTpl->assign('img_hotfolder', $bbImage['hot_folder_topic']);
$xoopsTpl->assign('img_locked', $bbImage['locked_topic']);
$xoopsTpl->assign('img_sticky', $bbImage['folder_sticky']);
$xoopsTpl->assign('lang_newposts', _MD_NEWPOSTS);
$xoopsTpl->assign('lang_hotnewposts', _MD_MORETHAN);
$xoopsTpl->assign('lang_hotnonewposts', _MD_MORETHAN2);
$xoopsTpl->assign('lang_nonewposts', _MD_NONEWPOSTS);
$xoopsTpl->assign('lang_legend', _MD_LEGEND);
$xoopsTpl->assign('lang_topiclocked', _MD_TOPICLOCKED);
$xoopsTpl->assign('lang_topicsticky', _MD_TOPICSTICKY);
$xoopsTpl->assign("lang_search", _MD_SEARCH);
$xoopsTpl->assign("lang_advsearch", _MD_ADVSEARCH);

$sql = 'SELECT COUNT(*) FROM '.$xoopsDB->prefix('bb_topics').' WHERE forum_id = '.$forum.' AND (topic_time > '.$startdate.' OR topic_sticky = 1)';
if ( !$r = $xoopsDB->query($sql) ) {
	//redirect_header('index.php',2,_MD_ERROROCCURED);
	//exit();
}
list($all_topics) = $xoopsDB->fetchRow($r);
if ( $all_topics > $forumdata['topics_per_page'] ) {
	include XOOPS_ROOT_PATH.'/class/pagenav.php';
	$nav = new XoopsPageNav($all_topics, $forumdata['topics_per_page'], $start, "start", 'forum='.$forum.'&amp;sortname='.$sortname.'&amp;sortorder='.$sortorder.'&amp;sortsince='.$sortsince);
	$xoopsTpl->assign('forum_pagenav', $nav->renderNav(4));
} else {
	$xoopsTpl->assign('forum_pagenav', '');
}
$xoopsTpl->assign('forum_jumpbox', make_jumpbox($forum));
include XOOPS_ROOT_PATH."/footer.php";
?>

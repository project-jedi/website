<?php
// $Id: viewtopic.php,v 1.20 2003/09/04 21:55:46 okazu Exp $
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
$forum = isset($HTTP_GET_VARS['forum']) ? intval($HTTP_GET_VARS['forum']) : 0;
$topic_id = isset($HTTP_GET_VARS['topic_id']) ? intval($HTTP_GET_VARS['topic_id']) : 0;
if ( empty($forum) ) {
	redirect_header('index.php',2,_MD_ERRORFORUM);
	exit();
} elseif ( empty($topic_id) ) {
	redirect_header('viewforum.php?forum='.$forum,2,_MD_ERRORTOPIC);
	exit();
}
$topic_time = (isset($HTTP_GET_VARS['topic_time'])) ? intval($HTTP_GET_VARS['topic_time']) : 0;
$post_id = !empty($HTTP_GET_VARS['post_id']) ? intval($HTTP_GET_VARS['post_id']) : 0;

//use users preferences
if (is_object($xoopsUser)) {
	$viewmode = $xoopsUser->getVar('umode');
	$order = ($xoopsUser->getVar('uorder') == 1) ? 'DESC' : 'ASC';
} else {
	$viewmode = 'flat';
	$order = 'ASC';
}

// newbb does not have nested mode
if ($viewmode == 'nest') {
	$viewmode = 'thread';
}

// override mode/order if any requested
if (isset($HTTP_GET_VARS['viewmode']) && ($HTTP_GET_VARS['viewmode'] == 'flat' || $HTTP_GET_VARS['viewmode'] == 'thread')) {
	$viewmode = $HTTP_GET_VARS['viewmode'];
}
if (isset($HTTP_GET_VARS['order']) && ($HTTP_GET_VARS['order'] == 'ASC' || $HTTP_GET_VARS['order'] == 'DESC')) {
	$order = $HTTP_GET_VARS['order'];
}

if ($viewmode != 'flat') {
	$xoopsOption['template_main'] =  'newbb_viewtopic_thread.html';
} else {
	$xoopsOption['template_main'] =  'newbb_viewtopic_flat.html';
}

include XOOPS_ROOT_PATH.'/header.php';
include_once 'class/class.forumposts.php';

if ( isset($HTTP_GET_VARS['move']) && 'next' == $HTTP_GET_VARS['move'] ) {
	$sql = 'SELECT t.topic_id, t.topic_title, t.topic_time, t.topic_status, t.topic_sticky, t.topic_last_post_id, f.forum_id, f.forum_name, f.forum_access, f.forum_type, f.allow_html, f.allow_sig, f.posts_per_page, f.hot_threshold, f.topics_per_page FROM '.$xoopsDB->prefix('bb_topics').' t LEFT JOIN '.$xoopsDB->prefix('bb_forums').' f ON f.forum_id = t.forum_id WHERE t.topic_time > '.$topic_time.' AND t.forum_id = '.$forum.' ORDER BY t.topic_time ASC LIMIT 1';
} elseif ( isset($HTTP_GET_VARS['move']) && 'prev' == $HTTP_GET_VARS['move']) {
	$sql = 'SELECT t.topic_id, t.topic_title, t.topic_time, t.topic_status, t.topic_sticky, t.topic_last_post_id, f.forum_id, f.forum_name, f.forum_access, f.forum_type, f.allow_html, f.allow_sig, f.posts_per_page, f.hot_threshold, f.topics_per_page FROM '.$xoopsDB->prefix('bb_topics').' t LEFT JOIN '.$xoopsDB->prefix('bb_forums').' f ON f.forum_id = t.forum_id WHERE t.topic_time < '.$topic_time.' AND t.forum_id = '.$forum.' ORDER BY t.topic_time DESC LIMIT 1';
} else {
	$sql = 'SELECT t.topic_id, t.topic_title, t.topic_time, t.topic_status, t.topic_sticky, t.topic_last_post_id, f.forum_id, f.forum_name, f.forum_access, f.forum_type, f.allow_html, f.allow_sig, f.posts_per_page, f.hot_threshold, f.topics_per_page FROM '.$xoopsDB->prefix('bb_topics').' t LEFT JOIN '.$xoopsDB->prefix('bb_forums').' f ON f.forum_id = t.forum_id WHERE t.topic_id = '.$topic_id.' AND t.forum_id = '.$forum;
}

if ( !$result = $xoopsDB->query($sql) ) {
	redirect_header('viewforum.php?forum='.$forum,2,_MD_ERROROCCURED);
	exit();
}

if ( !$forumdata = $xoopsDB->fetchArray($result) ) {
	redirect_header('viewforum.php?forum='.$forum,2,_MD_FORUMNOEXIST);
	exit();
}
$xoopsTpl->assign('topic_id', $forumdata['topic_id']);
$topic_id = $forumdata['topic_id'];
$xoopsTpl->assign('forum_id', $forumdata['forum_id']);
$forum = $forumdata['forum_id'];
$can_post = 0;
$show_reg = 0;
if ( $forumdata['forum_type'] == 1 ) {
	// this is a private forum.
	$accesserror = 0;
	if ( $xoopsUser ) {
		if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
			if ( !check_priv_forum_auth($xoopsUser->getVar('uid'), $forum, false) ) {
				$accesserror = 1;
			}
		} else {
			$isadminormod = 1;
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
			if ( $xoopsUser->isAdmin($xoopsModule->mid()) || is_moderator($forum, $xoopsUser->getVar('uid')) ) {
				$can_post = 1;
				$isadminormod = 1;
			}
		}
	}
}
$myts =& MyTextSanitizer::getInstance();
$forumdata['topic_title'] = $myts->makeTboxData4Show($forumdata['topic_title']);$forumdata['forum_name'] = $myts->makeTboxData4Show($forumdata['forum_name']);
$xoopsTpl->assign(array('topic_title' => '<a href="'.$bbUrl['root'].'viewtopic.php?viewmode='.$viewmode.'&amp;topic_id='.$topic_id.'&amp;forum='.$forum.'">'.$forumdata['topic_title'].'</a>', 'forum_name' => $forumdata['forum_name'], 'topic_time' => $forumdata['topic_time'], 'lang_nexttopic' => _MD_NEXTTOPIC, 'lang_prevtopic' => _MD_PREVTOPIC));

// add image links to admin page if the user viewing this page is a forum admin
if ( $xoopsUser ) {
	$xoopsTpl->assign('viewer_userid', $xoopsUser->getVar('uid'));
	if ( !empty($isadminormod) || $xoopsUser->isAdmin($xoopsModule->mid()) || is_moderator( $forum, $xoopsUser->getVar('uid')) ) {
		// yup, the user is admin
		// the forum is locked?
		if ( $forumdata['topic_status'] != 1 ) {
			// nope
			$xoopsTpl->assign('topic_lock_image', '<a href="'.$bbUrl['root'].'topicmanager.php?mode=lock&amp;topic_id='.$topic_id.'&amp;forum='.$forum.'"><img src="'.$bbImage['locktopic'].'" alt="'._MD_LOCKTOPIC.'" /></a>');
		} else {
			// yup, it is..
			$xoopsTpl->assign('topic_lock_image', '<a href="'.$bbUrl['root'].'topicmanager.php?mode=unlock&amp;topic_id='.$topic_id.'&amp;forum='.$forum.'"><img src="'.$bbImage['unlocktopic'].'" alt="'._MD_UNLOCKTOPIC.'" /></a>');
		}
		$xoopsTpl->assign('topic_move_image', '<a href="'.$bbUrl['root'].'topicmanager.php?mode=move&amp;topic_id='.$topic_id.'&amp;forum='.$forum.'"><img src="'.$bbImage['movetopic'].'" alt="'._MD_MOVETOPIC.'" /></a>');
		$xoopsTpl->assign('topic_delete_image', '<a href="'.$bbUrl['root'].'topicmanager.php?mode=del&amp;topic_id='.$topic_id.'&amp;forum='.$forum.'"><img src="'.$bbImage['deltopic'].'" alt="'._MD_DELETETOPIC.'" /></a>');
		// is the topic sticky?
		if ( $forumdata['topic_sticky'] != 1 ) {
			// nope, not yet..
			$xoopsTpl->assign('topic_sticky_image', '<a href="'.$bbUrl['root'].'topicmanager.php?mode=sticky&amp;topic_id='.$topic_id.'&amp;forum='.$forum.'"><img src="'.$bbImage['sticky'].'" alt="'._MD_STICKYTOPIC.'" /></a>');
		} else {
			// yup it is sticking..
			$xoopsTpl->assign('topic_sticky_image', '<a href="'.$bbUrl['root'].'topicmanager.php?mode=unsticky&amp;topic_id='.$topic_id.'&amp;forum='.$forum.'"><img src="'.$bbImage['unsticky'].'" alt="'._MD_UNSTICKYTOPIC.'" /></a>');
		}
		// need to set this also
		$xoopsTpl->assign('viewer_is_admin', true);
	} else {
		// nope, the user is not a forum admin..
		$xoopsTpl->assign('viewer_is_admin', false);
	}
} else {
	// nope, the user is not a forum admin, not even registered
	$xoopsTpl->assign(array('viewer_is_admin' => false, 'viewer_userid' => 0));
}

function showTree(&$arr, $current=0, $key=0, $prefix='', $foundusers=array()){
	global $xoopsConfig;
	if ($key != 0) {
		if ( 0 != $arr[$key]['obj']->uid() ) {
			if (!isset($foundusers[$arr[$key]['obj']->uid()])) {
				$eachposter = new XoopsUser($arr[$key]['obj']->uid());
				$foundusers[$arr[$key]['obj']->uid()] =& $eachposter;
			} else {
				$eachposter =& $foundusers[$arr[$key]['obj']->uid()];
			}
			$poster_rank = $eachposter->rank();
			if ( $poster_rank['image'] != '' ) {
				$poster_rank['image'] = '<img src="'.XOOPS_UPLOAD_URL.'/'.$poster_rank['image'].'" alt="" />';
			}
			if ( $eachposter->isActive() ) {
				$posterarr =  array('poster_uid' => $eachposter->getVar('uid'), 'poster_uname' => '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$eachposter->getVar('uid').'">'.$eachposter->getVar('uname').'</a>');
			} else {
				$posterarr = array('poster_uid' =>0, 'poster_uname' => $xoopsConfig['anonymous']);
			}
		} else {
			$posterarr = array('poster_uid' =>0, 'poster_uname' => $xoopsConfig['anonymous']);
		}
		$posticon = $arr[$key]['obj']->icon();
		if ( isset($posticon) && $posticon != '' ) {
			$post_image = '<img src="'.XOOPS_URL.'/images/subject/'.$posticon.'" alt="" />';
		} else {
			$post_image =  '<img src="'.XOOPS_URL.'/images/icons/no_posticon.gif" alt="" />';
		}
		if ($current != $key) {
			$subject = '<a href="viewtopic.php?viewmode=thread&amp;topic_id='.$arr[$key]['obj']->topic().'&amp;forum='.$arr[$key]['obj']->forum().'&amp;post_id='.$arr[$key]['obj']->postid().'#'.$arr[$key]['obj']->postid().'">'.$arr[$key]['obj']->subject().'</a>';
			$GLOBALS['xoopsTpl']->append("topic_trees", array_merge($posterarr, array("post_id" => $arr[$key]['obj']->postid(), "post_parent_id" => $arr[$key]['obj']->parent(), "post_date" => formatTimestamp($arr[$key]['obj']->posttime(), "m"), "post_image" => $post_image, "post_title" => $subject, "post_prefix" => $prefix)));
		} else {
			$subject = '<b>'.$arr[$key]['obj']->subject().'</b>';
			$thisprefix = substr($prefix, 0, -6)."<b>&raquo;</b>";
			$GLOBALS['xoopsTpl']->append("topic_trees", array_merge($posterarr, array("post_id" => $arr[$key]['obj']->postid(), "post_parent_id" => $arr[$key]['obj']->parent(), "post_date" => formatTimestamp($arr[$key]['obj']->posttime(), "m"), "post_image" => $post_image, "post_title" => $subject, "post_prefix" => $thisprefix)));
		}
	}
	if ( isset($arr[$key]['replies']) && !empty($arr[$key]['replies']) ){
		$prefix .= "&nbsp;&nbsp;";
		foreach($arr[$key]['replies'] as $replykey) {
			$current = ( $current == 0 ) ? $replykey : $current;
			showTree($arr, $current, $replykey, $prefix, $foundusers);
		}
	}
}

if ($order == 'DESC') {
	$xoopsTpl->assign(array('order_current', 'DESC', 'order_other' => 'ASC', 'lang_order_other' => _OLDESTFIRST));
} else {
	$xoopsTpl->assign(array('order_current' => 'ASC', 'order_other' => 'DESC', 'lang_order_other' => _NEWESTFIRST));
}

// initialize the start number of select query
$start = !empty($HTTP_GET_VARS['start']) ? intval($HTTP_GET_VARS['start']) : 0;

$total_posts = get_total_posts($topic_id, 'topic');
if ($total_posts > 50) {
	$viewmode ="flat";
	// hide link to theaded view
	$xoopsTpl->assign('lang_threaded', "" );
	$xoopsTpl->assign('lang_flat', _FLAT );
} else {
	$xoopsTpl->assign(array('lang_threaded' => _THREADED, 'lang_flat' => _FLAT));
}

if ( $can_post == 1 ) {
	$xoopsTpl->assign(array('viewer_can_post' => true, 'forum_post_or_register' => "<a href=\"newtopic.php?forum=".$forum."\"><img src=\"".$bbImage['post']."\" alt=\""._MD_POSTNEW."\" /></a>"));
} else {
	$xoopsTpl->assign('viewer_can_post', false);
	if ( $show_reg == 1 ) {
		$xoopsTpl->assign('forum_post_or_register', '<a href="'.XOOPS_URL.'/user.php?xoops_redirect='.$xoopsRequestUri.'">'._MD_REGTOPOST.'</a>');
	} else {
		$xoopsTpl->assign('forum_post_or_register', '');
	}
}

if ( $viewmode == "thread" ) {
	$postsArray = ForumPosts::getAllPosts($topic_id, "ASC", $total_posts, 0);
	$xoopsTpl->assign('topic_viewmode', 'thread');

	$newObjArr = array();
	foreach ( $postsArray as $eachpost ) {
		$key1 = $eachpost->postid();
		if ( (!empty($post_id) && $post_id == $key1) || ( empty($post_id) && $eachpost->parent() == 0 ) ) {
			$post_text = $eachpost->text();
			if ( 0 != $eachpost->uid() ) {
				$eachposter = new XoopsUser($eachpost->uid());
				$poster_rank = $eachposter->rank();
				if ( $poster_rank['image'] != "" ) {
					$poster_rank['image'] = "<img src='".XOOPS_UPLOAD_URL."/".$poster_rank['image']."' alt='' />";
				}
				if ( $eachposter->isActive() ) {
					$poster_status = $eachposter->isOnline() ? _MD_ONLINE : '';
					$posterarr =  array('poster_uid' => $eachposter->getVar('uid'), 'poster_uname' => '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$eachposter->getVar('uid').'">'.$eachposter->getVar('uname').'</a>', 'poster_avatar' => $eachposter->getVar('user_avatar'), 'poster_from' => $eachposter->getVar('user_from'), 'poster_regdate' => formatTimestamp($eachposter->getVar('user_regdate'), 's'), 'poster_postnum' => $eachposter->getVar('posts'), 'poster_sendpmtext' => sprintf(_SENDPMTO,$eachposter->getVar('uname')), 'poster_rank_title' => $poster_rank['title'], 'poster_rank_image' => $poster_rank['image'], 'poster_status' => $poster_status);
					if ( 1 == $forumdata['allow_sig'] && $eachpost->attachsig() == 1 && $eachposter->attachsig() == 1 ) {
						$myts =& MytextSanitizer::getInstance();
						$post_text .= "<p><br />----------------<br />". $myts->makeTareaData4Show($eachposter->getVar("user_sig", "N"), 0, 1, 1)."</p>";
					}
				} else {
					$posterarr = array('poster_uid' =>0, 'poster_uname' => $xoopsConfig['anonymous'], 'poster_avatar' => '', 'poster_from' => '', 'poster_regdate' => '', 'poster_postnum' => '', 'poster_sendpmtext' => '', 'poster_rank_title' => '', 'poster_rank_image' => '');
				}
			} else {
				$posterarr = array('poster_uid' =>0, 'poster_uname' => $xoopsConfig['anonymous'], 'poster_avatar' => '', 'poster_from' => '', 'poster_regdate' => '', 'poster_postnum' => '', 'poster_sendpmtext' => '', 'poster_rank_title' => '', 'poster_rank_image' => '');
			}
			$posticon = $eachpost->icon();
			if ( isset($posticon) && $posticon != '' ) {
				$post_image = '<a name="'.$eachpost->postid().'"><img src="'.XOOPS_URL.'/images/subject/'.$eachpost->icon().'" alt="" /></a>';
			} else {
				$post_image =  '<a name="'.$eachpost->postid().'"><img src="'.XOOPS_URL.'/images/icons/posticon.gif" alt="" /></a>';
			}
			$xoopsTpl->append('topic_posts', array_merge($posterarr, array('post_id' => $eachpost->postid(), 'post_parent_id' => $eachpost->parent(), 'post_date' => formatTimestamp($eachpost->posttime(), 'm'), 'post_poster_ip'=> $eachpost->posterip(), 'post_image' => $post_image, 'post_title' => $eachpost->subject(), 'post_text' => $post_text)));
		}
		$newObjArr[$key1]['obj'] = $eachpost;
		$key2 = $eachpost->parent();
		$newObjArr[$key2]['replies'][] = $key1;
		$newObjArr[$key2]['leaf'] = $key1;
	}
	showTree($newObjArr, $post_id);
	$xoopsTpl->assign(array('lang_subject' => _MD_SUBJECT, 'lang_date' => _MD_DATE));
} else {
	$xoopsTpl->assign(array('topic_viewmode' => 'flat', 'lang_top', _MD_TOP, 'lang_subject' => _MD_SUBJECT, 'lang_bottom' => _MD_BOTTOM));
	$postsArray = ForumPosts::getAllPosts($topic_id, $order, $forumdata['posts_per_page'], $start);
	$foundusers = array();
	foreach ( $postsArray as $eachpost ) {
		$post_text = $eachpost->text();
		if ( 0 != $eachpost->uid() ) {
			if (!isset($foundusers['user'.$eachpost->uid()])) {
				$eachposter = new XoopsUser($eachpost->uid());
				$foundusers['user'.$eachpost->uid()] =& $eachposter;
			} else {
				$eachposter =& $foundusers['user'.$eachpost->uid()];
			}
			$poster_rank = $eachposter->rank();
			if ( $poster_rank['image'] != '' ) {
				$poster_rank['image'] = '<img src="'.XOOPS_UPLOAD_URL.'/'.$poster_rank['image'].'" alt="" />';
			}
			if ( $eachposter->isActive() ) {
				$poster_status = $eachposter->isOnline() ? _MD_ONLINE : '';
				$posterarr =  array('poster_uid' => $eachposter->getVar('uid'), 'poster_uname' => '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$eachposter->getVar('uid').'">'.$eachposter->getVar('uname').'</a>', 'poster_avatar' => $eachposter->getVar('user_avatar'), 'poster_from' => $eachposter->getVar('user_from'), 'poster_regdate' => formatTimestamp($eachposter->getVar('user_regdate'), 's'), 'poster_postnum' => $eachposter->getVar('posts'), 'poster_sendpmtext' => sprintf(_SENDPMTO,$eachposter->getVar('uname')), 'poster_rank_title' => $poster_rank['title'], 'poster_rank_image' => $poster_rank['image'], 'poster_status' => $poster_status);
				if ( 1 == $forumdata['allow_sig'] && $eachpost->attachsig() == 1 && $eachposter->attachsig() == 1 ) {
					$myts =& MytextSanitizer::getInstance();
					$post_text .= '<p><br />----------------<br />'. $myts->makeTareaData4Show($eachposter->getVar('user_sig', 'N'), 0, 1, 1).'</p>';
				}
			} else {
				$posterarr = array('poster_uid' =>0, 'poster_uname' => $xoopsConfig['anonymous'], 'poster_avatar' => '', 'poster_from' => '', 'poster_regdate' => '', 'poster_postnum' => '', 'poster_sendpmtext' => '', 'poster_rank_title' => '', 'poster_rank_image' => '');
			}
		} else {
			$posterarr = array('poster_uid' =>0, 'poster_uname' => $xoopsConfig['anonymous'], 'poster_avatar' => '', 'poster_from' => '', 'poster_regdate' => '', 'poster_postnum' => '', 'poster_sendpmtext' => '', 'poster_rank_title' => '', 'poster_rank_image' => '');
		}
		$posticon = $eachpost->icon();
		if ( isset($posticon) && $posticon != '' ) {
			$post_image = '<a name="'.$eachpost->postid().'"><img src="'.XOOPS_URL.'/images/subject/'.$eachpost->icon().'" alt="" /></a>';
		} else {
			$post_image =  '<a name="'.$eachpost->postid().'"><img src="'.XOOPS_URL.'/images/icons/no_posticon.gif" alt="" /></a>';
		}
		$xoopsTpl->append('topic_posts', array_merge($posterarr, array('post_id' => $eachpost->postid(), 'post_parent_id' => $eachpost->parent(), 'post_date' => formatTimestamp($eachpost->posttime(), 'm'), 'post_poster_ip'=> $eachpost->posterip(), 'post_image' => $post_image, 'post_title' => $eachpost->subject(), 'post_text' => $post_text)));
		unset($eachposter);
	}
	if ( $total_posts > $forumdata['posts_per_page'] ) {
		include XOOPS_ROOT_PATH.'/class/pagenav.php';
		$nav = new XoopsPageNav($total_posts, $forumdata['posts_per_page'], $start, "start", 'topic_id='.$topic_id.'&amp;forum='.$forum.'&amp;viewmode='.$viewmode.'&amp;order='.$order);
		$xoopsTpl->assign('forum_page_nav', $nav->renderNav(4));
	} else {
		$xoopsTpl->assign('forum_page_nav', '');
	}
}

// create jump box
$xoopsTpl->assign(array('forum_jumpbox' => make_jumpbox($forum), 'lang_forum_index' => sprintf(_MD_FORUMINDEX,$xoopsConfig['sitename']), 'lang_from' => _MD_FROM, 'lang_joined' => _MD_JOINED, 'lang_posts' => _MD_POSTS, 'lang_poster' => _MD_POSTER, 'lang_thread' => _MD_THREAD, 'lang_edit' => _EDIT, 'lang_delete' => _DELETE, 'lang_reply' => _REPLY, 'lang_postedon' => _MD_POSTEDON));

// Read in cookie of 'lastread' times
$topic_lastread = !empty($HTTP_COOKIE_VARS['newbb_topic_lastread']) ? unserialize($HTTP_COOKIE_VARS['newbb_topic_lastread']) : array();
// if cookie is not set for this topic, update view count and set cookie
if ( empty($topic_lastread[$topic_id]) ) {
	$sql = 'UPDATE '.$xoopsDB->prefix('bb_topics').' SET topic_views = topic_views + 1 WHERE topic_id ='. $topic_id;
	$xoopsDB->queryF($sql);
}
// Update cookie
// FIXME: doesn't check if 4kB limit of cookie is exceeded!
$topic_lastread[$topic_id] = time();
setcookie("newbb_topic_lastread", serialize($topic_lastread), time()+365*24*3600, $bbCookie['path'], $bbCookie['domain'], $bbCookie['secure']);
include XOOPS_ROOT_PATH.'/footer.php';
?>

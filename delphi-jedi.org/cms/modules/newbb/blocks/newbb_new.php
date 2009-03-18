<?php
// $Id: newbb_new.php,v 1.8 2003/09/11 13:33:38 okazu Exp $
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
// Recent private forums block (Bloc Forum privé©                            //
// Author: L'éñuipe de TheNetSpace ( http://www.thenetspace.com )            //
// ------------------------------------------------------------------------- //

function b_newbb_new_show($options) {
    $db =& Database::getInstance();
    $myts =& MyTextSanitizer::getInstance();
    $block = array();
    switch($options[2]) {
    case 'views':
        $order = 't.topic_views';
        break;
    case 'replies':
        $order = 't.topic_replies';
        break;
    case 'time':
    default:
        $order = 't.topic_time';
        break;
    }
    $query='SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.topic_time, t.topic_views, t.topic_replies, t.forum_id, f.forum_name FROM '.$db->prefix('bb_topics').' t, '.$db->prefix('bb_forums').' f WHERE f.forum_id=t.forum_id AND f.forum_type <> 1 ORDER BY '.$order.' DESC';
    if (!$result = $db->query($query,$options[0],0)) {
        return false;
    }
    if ( $options[1] != 0 ) {
        $block['full_view'] = true;
    } else {
        $block['full_view'] = false;
    }
    $block['lang_forum'] = _MB_NEWBB_FORUM;
    $block['lang_topic'] = _MB_NEWBB_TOPIC;
    $block['lang_replies'] = _MB_NEWBB_RPLS;
    $block['lang_views'] = _MB_NEWBB_VIEWS;
    $block['lang_lastpost'] = _MB_NEWBB_LPOST;
    $block['lang_visitforums'] = _MB_NEWBB_VSTFRMS;
    while ($arr = $db->fetchArray($result)) {
        $topic['forum_id'] = $arr['forum_id'];
        $topic['forum_name'] = $myts->makeTboxData4Show($arr['forum_name']);
        $topic['id'] = $arr['topic_id'];
        $topic['title'] = $myts->makeTboxData4Show($arr['topic_title']);
        $topic['replies'] = $arr['topic_replies'];
        $topic['views'] = $arr['topic_views'];
        $topic['post_id'] = $arr['topic_last_post_id'];
        $lastpostername = $db->query("SELECT post_id, uid FROM ".$db->prefix("bb_posts")." WHERE post_id = ".$topic['post_id']);
        while ($tmpdb=$db->fetchArray($lastpostername)) {
            $tmpuser = XoopsUser::getUnameFromId($tmpdb['uid']);
            if ( $options[1] != 0 ) {
                $topic['time'] = formatTimestamp($arr['topic_time'],'m')." $tmpuser";
            }
        }
        $block['topics'][] =& $topic;
        unset($topic);
    }
    return $block;
}

function b_newbb_new_private_show($options) {
    $db =& Database::getInstance();
    $myts =& MyTextSanitizer::getInstance();
    $block = array();
    switch($options[2]) {
    case 'views':
        $order = 't.topic_views';
        break;
    case 'replies':
        $order = 't.topic_replies';
        break;
    case 'time':
    default:
        $order = 't.topic_time';
        break;
    }
    $query='SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.topic_time, t.topic_views, t.topic_replies, t.forum_id, f.forum_name FROM '.$db->prefix('bb_topics').' t, '.$db->prefix('bb_forums').' f WHERE f.forum_id=t.forum_id AND f.forum_type = 1 ORDER BY '.$order.' DESC';

    if (!$result = $db->query($query,$options[0],0)) {
        return false;
    }
    if ( $options[1] != 0 ) {
        $block['full_view'] = true;
    } else {
        $block['full_view'] = false;
    }
    $block['lang_forum'] = _MB_NEWBB_FORUM;
    $block['lang_topic'] = _MB_NEWBB_TOPIC;
    $block['lang_replies'] = _MB_NEWBB_RPLS;
    $block['lang_views'] = _MB_NEWBB_VIEWS;
    $block['lang_lastpost'] = _MB_NEWBB_LPOST;
    $block['lang_visitforums'] = _MB_NEWBB_VSTFRMS;
    while ($arr = $db->fetchArray($result)) {
        $topic['forum_id'] = $arr['forum_id'];
        $topic['forum_name'] = $myts->makeTboxData4Show($arr['forum_name']);
        $topic['id'] = $arr['topic_id'];
        $topic['title'] = $myts->makeTboxData4Show($arr['topic_title']);
        $topic['replies'] = $arr['topic_replies'];
        $topic['views'] = $arr['topic_views'];
        $tmpuser2 = $arr['topic_last_post_id'];
        $lastpostername = $db->query("SELECT post_id, uid FROM ".$db->prefix("bb_posts")." WHERE post_id = $tmpuser2");
        while ($tmpdb=$db->fetchArray($lastpostername)) {
            $tmpuser = XoopsUser::getUnameFromId($tmpdb['uid']);
            if ( $options[1] != 0 ) {
                $topic['time'] = formatTimestamp($arr['topic_time'],'m')." $tmpuser";
            }
        }
        $block['topics'][] =& $topic;
        unset($topic);
    }
    return $block;
}

function b_newbb_new_edit($options) {
    $inputtag = "<input type='text' name='options[0]' value='".$options[0]."' />";
    $form = sprintf(_MB_NEWBB_DISPLAY,$inputtag);
    $form .= "<br />"._MB_NEWBB_DISPLAYF."&nbsp;<input type='radio' name='options[1]' value='1'";
    if ( $options[1] == 1 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._YES."<input type='radio' name='options[1]' value='0'";
    if ( $options[1] == 0 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._NO;
    $form .= '<input type="hidden" name="options[2]" value="'.$options[2].'">';
    return $form;
}

?>

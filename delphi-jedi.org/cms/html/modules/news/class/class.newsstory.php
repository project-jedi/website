<?php
// $Id: class.newsstory.php,v 1.10 2003/04/13 01:18:35 okazu Exp $
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
// ------------------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH."/class/xoopsstory.php";

class NewsStory extends XoopsStory
{
	var $newstopic;   // XoopsTopic object

	function NewsStory($storyid=-1)
	{
		$this->db =& Database::getInstance();
		$this->table = $this->db->prefix("stories");
		$this->topicstable = $this->db->prefix("topics");
		if (is_array($storyid)) {
			$this->makeStory($storyid);
			$this->newstopic = $this->topic();
		} elseif($storyid != -1) {
			$this->getStory(intval($storyid));
			$this->newstopic = $this->topic();
		}
	}

	function getAllPublished($limit=0, $start=0, $topic=0, $ihome=0, $asobject=true)
	{
		$db =& Database::getInstance();
		$myts =& MyTextSanitizer::getInstance();
		$ret = array();
		$sql = "SELECT * FROM ".$db->prefix("stories")." WHERE published > 0 AND published <= ".time()." AND (expired = 0 OR expired > ".time().")";
		if ( !empty($topic) ) {
			$sql .= " AND topicid=".intval($topic)." AND (ihome=1 OR ihome=0)";
		} else {
			if ( $ihome == 0 ) {
				$sql .= " AND ihome=0";
			}
		}
		if (!empty($uid) && intval($uid) > 0) {
			$sql .= ' AND uid='.$uid;
		}
 		$sql .= " ORDER BY published DESC";
		$result = $db->query($sql,intval($limit),intval($start));
		while ( $myrow = $db->fetchArray($result) ) {
			if ( $asobject ) {
				$ret[] = new NewsStory($myrow);
			} else {
				$ret[$myrow['storyid']] = $myts->makeTboxData4Show($myrow['title']);
			}
		}
		return $ret;
	}

	// added new function to get all expired stories
	function getAllExpired($limit=0, $start=0, $topic=0, $ihome=0, $asobject=true)
	{
		$db =& Database::getInstance();
		$myts =& MyTextSanitizer::getInstance();
		$ret = array();
		$sql = "SELECT * FROM ".$db->prefix("stories")." WHERE expired <= ".time()." AND expired > 0";
		if ( !empty($topic) ) {
			$sql .= " AND topicid=".intval($topic)." AND (ihome=1 OR ihome=0)";
		} else {
			if ( $ihome == 0 ) {
				$sql .= " AND ihome=0";
			}
		}
		if (!empty($uid) && intval($uid) > 0) {
			$sql .= ' AND uid='.$uid;
		}
 		$sql .= " ORDER BY expired DESC";
		$result = $db->query($sql,intval($limit),intval($start));
		while ( $myrow = $db->fetchArray($result) ) {
			if ( $asobject ) {
				$ret[] = new NewsStory($myrow);
			} else {
				$ret[$myrow['storyid']] = $myts->makeTboxData4Show($myrow['title']);
			}
		}
		return $ret;
	}

	function getAllAutoStory($limit=0, $asobject=true)
	{
		$db =& Database::getInstance();
		$myts =& MyTextSanitizer::getInstance();
		$ret = array();
		$sql = "SELECT * FROM ".$db->prefix("stories")." WHERE published > ".time()." ORDER BY published ASC";
		$result = $db->query($sql,$limit,0);
		while ( $myrow = $db->fetchArray($result) ) {
			if ( $asobject ) {
				$ret[] = new NewsStory($myrow);
			} else {
				$ret[$myrow['storyid']] = $myts->makeTboxData4Show($myrow['title']);
			}
		}
		return $ret;
	}

	function getAllSubmitted($limit=0, $asobject=true)
	{
		$db =& Database::getInstance();
		$myts =& MyTextSanitizer::getInstance();
		$ret = array();
		$sql = "SELECT * FROM ".$db->prefix("stories")." WHERE published=0 ORDER BY created DESC";
		$result = $db->query($sql,$limit,0);
		while ( $myrow = $db->fetchArray($result) ) {
			if ( $asobject ) {
				$ret[] = new NewsStory($myrow);
			} else {
				$ret[$myrow['storyid']] = $myts->makeTboxData4Show($myrow['title']);
			}
		}
		return $ret;
	}

	function getByTopic($topicid)
	{
		$ret = array();
		$db =& Database::getInstance();
		$result = $db->query("SELECT * FROM ".$db->prefix("stories")." WHERE topicid=".intval($topicid)."");
		while( $myrow = $db->fetchArray($result) ){
			$ret[] = new NewsStory($myrow);
		}
		return $ret;
	}

	function countByTopic($topicid=0)
	{
		$db =& Database::getInstance();
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("stories")."
		WHERE expired >= ".time()."";
		if ( $topicid != 0 ) {
			$sql .= " AND  topicid=".intval($topicid);
		}
		$result = $db->query($sql);
		list($count) = $db->fetchRow($result);
		return $count;
	}

	function countPublishedByTopic($topicid=0)
	{
		$db =& Database::getInstance();
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("stories")." WHERE published > 0 AND published <= ".time()." AND (expired = 0 OR expired > ".time().")";
		if ( !empty($topicid) ) {
			$sql .= " AND topicid=".intval($topicid);
		} else {
			$sql .= " AND ihome=0";
		}
		$result = $db->query($sql);
		list($count) = $db->fetchRow($result);
		return $count;
	}


	function topic_title()
	{
		return $this->newstopic->topic_title();
	}

	function adminlink()
	{
		$ret = "&nbsp;[ <a href='".XOOPS_URL."/modules/news/admin/index.php?op=edit&amp;storyid=".$this->storyid."'>"._EDIT."</a> | <a href='".XOOPS_URL."/modules/news/admin/index.php?op=delete&amp;storyid=".$this->storyid."'>"._DELETE."</a> ]&nbsp;";
		return $ret;
	}

	function imglink()
	{
		$ret = '';
		if ($this->newstopic->topic_imgurl() != '' && file_exists(XOOPS_ROOT_PATH."/modules/news/images/topics/".$this->newstopic->topic_imgurl())) {
			$ret = "<a href='".XOOPS_URL."/modules/news/index.php?storytopic=".$this->topicid."'><img src='".XOOPS_URL."/modules/news/images/topics/".$this->newstopic->topic_imgurl()."' alt='".$this->newstopic->topic_title()."' hspace='10' vspace='10' align='".$this->topicalign()."' /></a>";
		}
		return $ret;
	}

	function textlink()
	{
		$ret = "<a href='".XOOPS_URL."/modules/news/index.php?storytopic=".$this->topicid()."'>".$this->newstopic->topic_title()."</a>";
		return $ret;
	}
}
?>
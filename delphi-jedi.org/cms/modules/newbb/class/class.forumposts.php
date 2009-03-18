<?php
// $Id: class.forumposts.php,v 1.6 2003/03/20 20:09:52 w4z004 Exp $
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

include_once XOOPS_ROOT_PATH."/class/xoopstree.php";

class ForumPosts
{
	var $post_id;
	var $topic_id;
	var $forum_id;
	var $post_time;
	var $poster_ip;
	var $order;
	var $subject;
	var $post_text;
	var $pid;
	var $nohtml = 0;
	var $nosmiley = 0;
	var $uid;
	var $icon;
	var $attachsig;
	var $prefix;
	var $db;
	var $istopic = false;
	var $islocked = false;

	function ForumPosts($id=null)
	{
		$this->db =& Database::getInstance();
		if ( is_array($id) ) {
			$this->makePost($id);
		} elseif ( isset($id) ) {
			$this->getPost(intval($id));
		}
	}

	function setTopicId($value){
		$this->topic_id = $value;
	}

	function getTopicId() {
		return isset($this->topic_id) ? $this->topic_id : 0;
	}

	function setOrder($value){
		$this->order = $value;
	}


	function getAllPosts($topic_id, $order="ASC", $perpage=0, $start=0){
		$db =& Database::getInstance();
		$sql = 'SELECT p.*, t.post_text FROM '.$db->prefix('bb_posts').' p, '.$db->prefix('bb_posts_text').' t WHERE p.topic_id='.$topic_id.' AND p.post_id = t.post_id ORDER BY p.post_id ';
		$sql .= ($order != "DESC") ? "ASC" : "DESC";
		$result = $db->query($sql,$perpage,$start);
		$ret = array();
		while ( $myrow = $db->fetchArray($result) ) {
			$ret[] = new ForumPosts($myrow);
		}
		return $ret;
	}

	function setParent($value){
		$this->pid=$value;
	}

	function setSubject($value){
		$this->subject=$value;
	}

	function setText($value){
		$this->post_text=$value;
	}

	function setUid($value){
		$this->uid=$value;
	}

	function setForum($value){
		$this->forum_id=$value;
	}

	function setIp($value){
		$this->poster_ip=$value;
	}

	function setNohtml($value=0){
		$this->nohtml=$value;
	}

	function setNosmiley($value=0){
		$this->nosmiley=$value;
	}

	function setIcon($value){
		$this->icon=$value;
	}

	function setAttachsig($value){
		$this->attachsig=$value;
	}

	function store() {
		$myts =& MyTextSanitizer::getInstance();
		$subject =$myts->censorString($this->subject);
		$post_text =$myts->censorString($this->post_text);
		$subject = $myts->makeTboxData4Save($subject);
		$post_text = $myts->makeTareaData4Save($post_text);
		if ( empty($this->post_id) ) {
			if ( empty($this->topic_id) ) {
				$this->topic_id = $this->db->genId($this->db->prefix("bb_topics")."_topic_id_seq");
				$datetime = time();
				$sql = "INSERT INTO ".$this->db->prefix("bb_topics")." (topic_id, topic_title, topic_poster, forum_id, topic_time) VALUES (".$this->topic_id.",'$subject', ".$this->uid.", ".$this->forum_id.", $datetime)";
   				if ( !$result = $this->db->query($sql) ) {
					return false;
   				}
				if ( $this->topic_id == 0 ) {
					$this->topic_id = $this->db->getInsertId();
				}
			}
			if ( !isset($this->nohtml) || $this->nohtml != 1 ) {
				$this->nohtml = 0;
			}
			if ( !isset($this->nosmiley) || $this->nosmiley != 1 ) {
				$this->nosmiley = 0;
			}
			if ( !isset($this->attachsig) || $this->attachsig != 1 ) {
				$this->attachsig = 0;
			}
			$this->post_id = $this->db->genId($this->db->prefix("bb_posts")."_post_id_seq");
			$datetime = time();
			$sql = sprintf("INSERT INTO %s (post_id, pid, topic_id, forum_id, post_time, uid, poster_ip, subject, nohtml, nosmiley, icon, attachsig) VALUES (%u, %u, %u, %u, %u, %u, '%s', '%s', %u, %u, '%s', %u)", $this->db->prefix("bb_posts"), $this->post_id, $this->pid, $this->topic_id, $this->forum_id, $datetime, $this->uid, $this->poster_ip, $subject, $this->nohtml, $this->nosmiley, $this->icon, $this->attachsig);
			if ( !$result = $this->db->query($sql) ) {
				return false;
   			} else {
				if ( $this->post_id == 0 ) {
					$this->post_id = $this->db->getInsertId();
				}
				$sql = sprintf("INSERT INTO %s (post_id, post_text) VALUES (%u, '%s')", $this->db->prefix("bb_posts_text"), $this->post_id, $post_text);
   				if ( !$result = $this->db->query($sql) ) {
					$sql = sprintf("DELETE FROM %s WHERE post_id = %u", $this->db->prefix("bb_posts"), $this->post_id);
					$this->db->query($sql);
   					return false;
   				}
   			}
			if ( $this->pid == 0 ) {
				$sql = sprintf("UPDATE %s SET topic_last_post_id = %u, topic_time = %u WHERE topic_id = %u", $this->db->prefix("bb_topics"), $this->post_id, $datetime, $this->topic_id);
   				if ( !$result = $this->db->query($sql) ) {
   				}
				$sql = sprintf("UPDATE %s SET forum_posts = forum_posts+1, forum_topics = forum_topics+1, forum_last_post_id = %u WHERE forum_id = %u", $this->db->prefix("bb_forums"), $this->post_id, $this->forum_id);
				$result = $this->db->query($sql);
   				if ( !$result ) {
   				}
			} else {
				$sql = "UPDATE ".$this->db->prefix("bb_topics")." SET topic_replies=topic_replies+1, topic_last_post_id = ".$this->post_id.", topic_time = $datetime WHERE topic_id =".$this->topic_id."";
   				if ( !$result = $this->db->query($sql) ) {
   				}
				$sql = "UPDATE ".$this->db->prefix("bb_forums")." SET forum_posts = forum_posts+1, forum_last_post_id = ".$this->post_id." WHERE forum_id = ".$this->forum_id."";
				$result = $this->db->query($sql);
   				if ( !$result ) {
   				}
			}
		}else{
			if ( $this->istopic() ) {
				$sql = "UPDATE ".$this->db->prefix("bb_topics")." SET topic_title = '$subject' WHERE topic_id = ".$this->topic_id."";
			 	if ( !$result = $this->db->query($sql) ) {
			 		return false;
			 	}
			}
			if ( !isset($this->nohtml) || $this->nohtml != 1 ) {
				$this->nohtml = 0;
			}
			if ( !isset($this->nosmiley) || $this->nosmiley != 1 ) {
				$this->nosmiley = 0;
			}
			if ( !isset($this->attachsig) || $this->attachsig != 1 ) {
				$this->attachsig = 0;
			}
			$sql = "UPDATE ".$this->db->prefix("bb_posts")." SET subject='".$subject."', nohtml=".$this->nohtml.", nosmiley=".$this->nosmiley.", icon='".$this->icon."', attachsig=".$this->attachsig." WHERE post_id=".$this->post_id."";
			$result = $this->db->query($sql);
			if ( !$result ) {
				return false;
			} else {
				$sql = "UPDATE ".$this->db->prefix("bb_posts_text")." SET post_text = '".$post_text."' WHERE post_id =".$this->post_id."";
				$result = $this->db->query($sql);
				if ( !$result ) {
					return false;
				}
			}
		}
		return $this->post_id;
	}

	function getPost($id) {
		$sql = 'SELECT p.*, t.post_text, tp.topic_status FROM '.$this->db->prefix('bb_posts').' p LEFT JOIN '.$this->db->prefix('bb_posts_text').' t ON p.post_id=t.post_id LEFT JOIN '.$this->db->prefix('bb_topics').' tp ON tp.topic_id=p.topic_id WHERE p.post_id='.$id;
		$array = $this->db->fetchArray($this->db->query($sql));
		$this->post_id = $array['post_id'];
		$this->pid = $array['pid'];
		$this->topic_id = $array['topic_id'];
		$this->forum_id = $array['forum_id'];
		$this->post_time = $array['post_time'];
		$this->uid = $array['uid'];
		$this->poster_ip = $array['poster_ip'];
		$this->subject = $array['subject'];
		$this->nohtml = $array['nohtml'];
		$this->nosmiley = $array['nosmiley'];
		$this->icon = $array['icon'];
		$this->attachsig = $array['attachsig'];
		$this->post_text = $array['post_text'];
		if ($array['pid'] == 0) {
			$this->istopic = true;
		}
		if ($array['topic_status'] == 1) {
			$this->islocked = true;
		}
	}

	function makePost($array){
		foreach($array as $key=>$value){
			$this->$key = $value;
		}
	}

	function delete() {
		$sql = sprintf("DELETE FROM %s WHERE post_id = %u", $this->db->prefix("bb_posts"), $this->post_id);
		if ( !$result = $this->db->query($sql) ) {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE post_id = %u", $this->db->prefix("bb_posts_text"), $this->post_id);
		if ( !$result = $this->db->query($sql) ) {
			echo "Could not remove posts text for Post ID:".$this->post_id.".<br />";
		}
		if ( !empty($this->uid) ) {
			$sql = sprintf("UPDATE %s SET posts=posts-1 WHERE uid = %u", $this->db->prefix("users"), $this->uid);
			if ( !$result = $this->db->query($sql) ) {
			//	echo "Could not update user posts.";
			}
		}
		if ($this->istopic()) {
			$sql = sprintf("DELETE FROM %s WHERE topic_id = %u", $this->db->prefix("bb_topics"), $this->topic_id);
			if ( !$result = $this->db->query($sql) ) {
				echo "Could not delete topic.";
			}
		}
		$mytree = new XoopsTree($this->db->prefix("bb_posts"), "post_id", "pid");
		$arr = $mytree->getAllChild($this->post_id);
		$size = count($arr);
		if ( $size > 0 ) {
			for ( $i = 0; $i < $size; $i++ ) {
				$sql = sprintf("DELETE FROM %s WHERE post_id = %u", $this->db->prefix("bb_posts"), $arr[$i]['post_id']);
				if ( !$result = $this->db->query($sql) ) {
					echo "Could not delete post ".$arr[$i]['post_id']."";
				}
				$sql = sprintf("DELETE FROM %s WHERE post_id = %u", $this->db->prefix("bb_posts_text"), $arr[$i]['post_id']);
				if ( !$result = $this->db->query($sql) ) {
					echo "Could not delete post text ".$arr[$i]['post_id']."";
				}
				if ( !empty($arr[$i]['uid']) ) {
					$sql = "UPDATE ".$this->db->prefix("users")." SET posts=posts-1 WHERE uid=".$arr[$i]['uid']."";
					if ( !$result = $this->db->query($sql) ) {
					//	echo "Could not update user posts.";
					}
				}
			}
		}
	}

	function subject($format="Show") {
		$myts =& MyTextSanitizer::getInstance();
		$smiley = 1;
		if ( $this->nosmiley() ) {
			$smiley = 0;
		}
		switch ( $format ) {
			case "Show":
				$subject= $myts->makeTboxData4Show($this->subject,$smiley);
				break;
			case "Edit":
				$subject = $myts->makeTboxData4Edit($this->subject);
				break;
			case "Preview":
				$subject = $myts->makeTboxData4Preview($this->subject,$smiley);
				break;
			case "InForm":
				$subject = $myts->makeTboxData4PreviewInForm($this->subject);
				break;
		}
		return $subject;
	}

	function text($format="Show"){
		$myts =& MyTextSanitizer::getInstance();
		$smiley = 1;
		$html = 1;
		$bbcodes = 1;
		if ( $this->nohtml() ) {
			$html = 0;
		}
		if ( $this->nosmiley() ) {
			$smiley = 0;
		}
		switch ( $format ) {
			case "Show":
				$text = $myts->makeTareaData4Show($this->post_text,$html,$smiley,$bbcodes);
				break;
			case "Edit":
				$text = $myts->makeTareaData4Edit($this->post_text);
				break;
			case "Preview":
				$text = $myts->makeTareaData4Preview($this->post_text,$html,$smiley,$bbcodes);
				break;
			case "InForm":
				$text = $myts->makeTareaData4PreviewInForm($this->post_text);
				break;
			case "Quotes":
				$text = $myts->makeTareaData4InsideQuotes($this->post_text);
				break;
		}
		return $text;
	}

	function postid() {
		return $this->post_id;
	}

	function posttime(){
		return $this->post_time;
	}

	function uid(){
		return $this->uid;
	}

	function uname(){
		return XoopsUser::getUnameFromId($this->uid);
	}

	function posterip(){
		return $this->poster_ip;
	}

	function parent(){
		return $this->pid;
	}

	function topic(){
		return $this->topic_id;
	}

	function nohtml(){
		return $this->nohtml;
	}

	function nosmiley(){
		return $this->nosmiley;
	}

	function icon(){
		return $this->icon;
	}

	function forum(){
		return $this->forum_id;
	}

	function attachsig(){
		return $this->attachsig;
	}

	function prefix(){
		return $this->prefix;
	}

	function istopic() {
		if ($this->istopic) {
			return true;
		}
		return false;
	}

	function islocked() {
		if ($this->islocked) {
			return true;
		}
		return false;
	}
}

?>

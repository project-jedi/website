<?php
// $Id: index.php,v 1.16 2003/09/09 15:38:46 okazu Exp $
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
include '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH."/class/xoopstopic.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/modules/news/class/class.newsstory.php";
$op = 'default';
if (isset($HTTP_POST_VARS)) {
	foreach ($HTTP_POST_VARS as $k => $v) {
		${$k} = $v;
	}
}

if (isset($HTTP_GET_VARS['op'])) {
	$op = $HTTP_GET_VARS['op'];
	if (isset($HTTP_GET_VARS['storyid'])) {
		$storyid = intval($HTTP_GET_VARS['storyid']);
	}
}

/*
 * Show new submissions
 */
function newSubmissions(){
	global $xoopsConfig, $xoopsDB;
	$storyarray = NewsStory::getAllSubmitted();
	if ( count($storyarray) > 0 ) {
		echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
		echo "<div style='text-align: center;'><b>"._AM_NEWSUB."</b><br /><table width='100%' border='1'><tr class='bg2'><td align='center'>"._AM_TITLE."</td><td align='center'>"._AM_POSTED."</td><td align='center'>"._AM_POSTER."</td><td align='center'>"._AM_ACTION."</td></tr>\n";
		foreach($storyarray as $newstory){
			echo "<tr><td>\n";
			$title = $newstory->title();
			if ( !isset($title) || ($title == "") ) {
			    echo "<a href='index.php?op=edit&amp;storyid=".$newstory->storyid()."'>"._AD_NOSUBJECT."</a>\n";
			} else {
			    echo "&nbsp;<a href='index.php?op=edit&amp;storyid=".$newstory->storyid()."'>".$title."</a>\n";
			}
			echo "</td><td align='center' class='nw'>".formatTimestamp($newstory->created())."</td><td align='center'><a href='".XOOPS_URL."/userinfo.php?uid=".$newstory->uid()."'>".$newstory->uname()."</a></td><td align='right'><a href='index.php?op=delete&amp;storyid=".$newstory->storyid()."'>"._AM_DELETE."</a></td></tr>\n";
		}
		echo "</table></div>\n";
		echo"</td></tr></table>";
		echo "<br />";
	}
}

/*
 * Shows automated stories
 */
function autoStories(){
    	global $xoopsDB, $xoopsConfig, $xoopsModule;
	$storyarray = NewsStory::getAllAutoStory();
	if ( count($storyarray) > 0 ) {
		echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
    		echo "<div style='text-align: center;'><b>"._AM_AUTOARTICLES."</b><br />\n";
		echo "<table border='1' width='100%'><tr class='bg2'><td align='center'>"._AM_STORYID."</td><td align='center'>"._AM_TITLE."</td><td align='center'>"._AM_TOPIC."</td><td align='center'>"._AM_POSTER."</td><td align='center' class='nw'>"._AM_PROGRAMMED."</td><td align='center' class='nw'>"._AM_EXPIRED."</td><td align='center'>"._AM_ACTION."</td></tr>";
    		foreach($storyarray as $autostory){
			$topic = $autostory->topic();
			$expire = ($autostory->expired() > 0) ? formatTimestamp($autostory->expired()) : '';
        		echo "
        		<tr><td align='center'><b>".$autostory->storyid()."</b>
        		</td><td align='left'><a href='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/article.php?storyid=".$autostory->storyid()."'>".$autostory->title()."</a>
        		</td><td align='center'>".$topic->topic_title()."
        		</td><td align='center'><a href='".XOOPS_URL."/userinfo.php?uid=".$autostory->uid()."'>".$autostory->uname()."</a></td><td align='center' class='nw'>".formatTimestamp($autostory->published())."</td><td align='center'>".$expire."</td><td align='center'><a href='index.php?op=edit&amp;storyid=".$autostory->storyid()."'>"._AM_EDIT."</a>-<a href='index.php?op=delete&amp;storyid=".$autostory->storyid()."'>"._AM_DELETE."</a>";
        		echo "</td></tr>\n";
    		}
    		echo "</table>";
		echo "</div>";
		echo"</td></tr></table>";
		echo "<br />";
	}
}

/*
 * Shows last 10 published stories
 */
function lastStories() {
    	global $xoopsDB, $xoopsConfig, $xoopsModule;
	echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
    	echo "<div style='text-align: center;'><b>"._AM_LAST10ARTS."</b><br />";
	$storyarray = NewsStory::getAllPublished(10, 0, 0, 1);
    	echo "<table border='1' width='100%'><tr class='bg3'><td align='center'>"._AM_STORYID."</td><td align='center'>"._AM_TITLE."</td><td align='center'>"._AM_TOPIC."</td><td align='center'>"._AM_POSTER."</td><td align='center' class='nw'>"._AM_PUBLISHED."</td><td align='center' class='nw'>"._AM_EXPIRED."</td><td align='center'>"._AM_ACTION."</td></tr>";
    	foreach($storyarray as $eachstory){
		$published = formatTimestamp($eachstory->published());
		$expired = ($eachstory->expired() > 0) ? formatTimestamp($eachstory->expired()) : '---';
		$topic = $eachstory->topic();
        	echo "
        	<tr><td align='center'><b>".$eachstory->storyid()."</b>
        	</td><td align='left'><a href='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/article.php?storyid=".$eachstory->storyid()."'>".$eachstory->title()."</a>
        	</td><td align='center'>".$topic->topic_title()."
        	</td><td align='center'><a href='".XOOPS_URL."/userinfo.php?uid=".$eachstory->uid()."'>".$eachstory->uname()."</a></td><td align='center' class='nw'>".$published."</td><td align='center'>".$expired."</td><td align='center'><a href='index.php?op=edit&amp;storyid=".$eachstory->storyid()."'>"._AM_EDIT."</a>-<a href='index.php?op=delete&amp;storyid=".$eachstory->storyid()."'>"._AM_DELETE."</a>";
        	echo "</td></tr>\n";
    	}
    	echo "</table><br />";
	echo "<form action='index.php' method='post'>
    	"._AM_STORYID." <input type='text' name='storyid' size='10' />
    	<select name='op'>
    	<option value='edit' selected='selected'>"._AM_EDIT."</option>
    	<option value='delete'>"._AM_DELETE."</option>
    	</select>
    	<input type='submit' value='"._AM_GO."' />
    	</form>
	</div>
    	";
    	echo"</td></tr></table>";
}

// Added function to display expired stories
function expStories() {
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
    	echo "<div style='text-align: center;'><b>"._AM_EXPARTS."</b><br />";
	$storyarray = NewsStory::getAllExpired(10, 0, 0, 1);
	echo "<table border='1' width='100%'><tr class='bg3'><td align='center'>"._AM_STORYID."</td><td align='center'>"._AM_TITLE."</td><td align='center'>"._AM_TOPIC."</td><td align='center'>"._AM_POSTER."</td><td align='center' class='nw'>"._AM_PUBLISHED."</td><td align='center' class='nw'>"._AM_EXPIRED."</td><td align='center'>"._AM_ACTION."</td></tr>";
    	foreach($storyarray as $eachstory){
		$published = formatTimestamp($eachstory->published());
			$expired = formatTimestamp($eachstory->expired());
		$topic = $eachstory->topic();
		// added exired value field to table
        	echo "
        	<tr><td align='center'><b>".$eachstory->storyid()."</b>
        	</td><td align='left'><a href='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/article.php?storyid=".$eachstory->storyid()."'>".$eachstory->title()."</a>
        	</td><td align='center'>".$topic->topic_title()."
        	</td><td align='center'><a href='".XOOPS_URL."/userinfo.php?uid=".$eachstory->uid()."'>".$eachstory->uname()."</a></td><td align='center' class='nw'>".$published."</td><td align='center' class='nw'>".$expired."</td><td align='center'><a href='index.php?op=edit&amp;storyid=".$eachstory->storyid()."'>"._AM_EDIT."</a>-<a href='index.php?op=delete&amp;storyid=".$eachstory->storyid()."'>"._AM_DELETE."</a>";
        	echo "</td></tr>\n";
    	}
    	echo "</table><br />";
	echo "<form action='index.php' method='post'>
    	"._AM_STORYID." <input type='text' name='storyid' size='10' />
    	<select name='op'>
    	<option value='edit' selected='selected'>"._AM_EDIT."</option>
    	<option value='delete'>"._AM_DELETE."</option>
    	</select>
    	<input type='submit' value='"._AM_GO."' />
    	</form>
	</div>
    	";
    	echo"</td></tr></table>";
}

function topicsmanager(){
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	xoops_cp_header();
	echo "<h4>"._AM_CONFIG."</h4>";
	$xt = new XoopsTopic($xoopsDB->prefix("topics"));
	$topics_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/news/images/topics/");
	//$xoopsModule->printAdminMenu();
	//echo "<br />";
// Add a New Main Topic
    	echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
   	echo "<form method='post' action='index.php'>\n";
   	echo "<h4>"._AM_ADDMTOPIC."</h4><br />";
	echo "<b>"._AM_TOPICNAME."</b> "._AM_MAX40CHAR."<br /><input type='text' name='topic_title' size='20' maxlength='20' /><br />";
	echo "<b>"._AM_TOPICIMG."</b> (". sprintf(_AM_IMGNAEXLOC,"modules/".$xoopsModule->dirname()."/images/topics/").")<br />"._AM_FEXAMPLE."<br />";
	echo "<select size='1' name='topic_imgurl'>";
	echo "<option value=' '>------</option>";
	foreach($topics_array as $image){
		echo "<option value='".$image."'>".$image."</option>";
	}
	echo "</select><br /><br />";
	echo "<input type='hidden' name='topic_pid' value='0' />\n";
	echo "<input type='hidden' name='op' value='addTopic' />";
	echo "<input type='submit' value="._AM_ADD." /><br /></form>";
    	echo"</td></tr></table>";
    	echo "<br />";
// Add a New Sub-Topic
    	$result=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("topics")."");
	list($numrows)=$xoopsDB->fetchRow($result);
    	if ($numrows>0) {
		echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
    		echo "<form method='post' action='index.php'>";
    		echo "<h4>"._AM_ADDSUBTOPIC."</h4><br />";
		echo "<b>"._AM_TOPICNAME."</b> "._AM_MAX40CHAR."<br /><input type='text' name='topic_title' size='20' maxlength='40' />&nbsp;"._AM_IN."&nbsp;";
		$xt->makeTopicSelBox(0,0,"topic_pid");
		echo "<br />";
		echo "<b>"._AM_TOPICIMG."</b> (". sprintf(_AM_IMGNAEXLOC,"modules/".$xoopsModule->dirname()."/images/topics/").")<br />"._AM_FEXAMPLE."<br />";
		echo "<select size='1' name='topic_imgurl'>";
		echo "<option value=' '>------</option>";
		foreach($topics_array as $image){
			echo "<option value='".$image."'>".$image."</option>";
		}
		echo "</select><br /><br />";
    	echo "<input type='hidden' name='op' value='addTopic' />";
		echo "<input type='submit' value='"._AM_ADD."' /><br /></form>";
		echo"</td></tr></table>";
		echo "<br />";

// Modify Topic
    		echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
    		echo "
    		<form method='post' action='index.php'>
    		<h4>"._AM_MODIFYTOPIC."</h4><br />";
    		echo "<b>"._AM_TOPIC."</b><br />";
    		$xt->makeTopicSelBox();
    		echo "<br /><br />\n";
    		echo "<input type='hidden' name='op' value='modTopic' />\n";
    		echo "<input type='submit' value='"._AM_MODIFY."' />\n";
		echo "</form>";
		echo"</td></tr></table>";
    	}
}

function modTopic() {
    	global $xoopsDB, $HTTP_POST_VARS, $xoopsConfig;
	global $xoopsModule;
	$xt = new XoopsTopic($xoopsDB->prefix("topics"), $HTTP_POST_VARS['topic_id']);
	$topics_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/news/images/topics/");
	xoops_cp_header();
	echo "<h4>"._AM_CONFIG."</h4>";
	//$xoopsModule->printAdminMenu();
	//echo "<br />";
	echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
    echo "<h4>"._AM_MODIFYTOPIC."</h4><br />";
	if ($xt->topic_imgurl()) {
	echo "<div style='text-align: right;'><img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/topics/".$xt->topic_imgurl()."'></div>";
	}
	echo "<form action='index.php' method='post'>";
	echo "<b>"._AM_TOPICNAME."</b>&nbsp;"._AM_MAX40CHAR."<br /><input type='text' name='topic_title' size='20' maxlength='40' value='".$xt->topic_title('Edit')."' /><br />";
	echo "<b>"._AM_TOPICIMG."</b>&nbsp;(". sprintf(_AM_IMGNAEXLOC,"modules/".$xoopsModule->dirname()."/images/topics/").")<br />"._AM_FEXAMPLE."<br />";
	//echo "<input type='text' name='topic_imgurl' size='20' maxlength='20' value='".$xt->topic_imgurl()."' /><br />\n";
	echo "<select size='1' name='topic_imgurl'>";
	echo "<option value=' '>------</option>";
	foreach($topics_array as $image){
		if ( $image == $xt->topic_imgurl() ) {
			$opt_selected = "selected='selected'";
		}else{
			$opt_selected = "";
		}
	echo "<option value='".$image."' $opt_selected>".$image."</option>";
	}
	echo "</select><br />";
	echo "<b>"._AM_PARENTTOPIC."<b><br />\n";
	$xt->makeTopicSelBox(1, $xt->topic_pid(), "topic_pid");
	echo "\n<br /><br />";

	echo "<input type='hidden' name='topic_id' value='".$xt->topic_id()."' />\n";
	echo "<input type='hidden' name='op' value='modTopicS' />";
	echo "<input type='submit' value='"._AM_SAVECHANGE."' />&nbsp;<input type='button' value='"._AM_DEL."' onclick='location=\"index.php?topic_pid=".$xt->topic_pid()."&amp;topic_id=".$xt->topic_id()."&amp;op=delTopic\"' />\n";
	echo "&nbsp;<input type='button' value='"._AM_CANCEL."' onclick='javascript:history.go(-1)' />\n";
	echo "</form>";
    	echo"</td></tr></table>";
}
function modTopicS() {
    	global $xoopsDB, $HTTP_POST_VARS;
    	$xt = new XoopsTopic($xoopsDB->prefix("topics"), $HTTP_POST_VARS['topic_id']);
	if ( $HTTP_POST_VARS['topic_pid'] == $HTTP_POST_VARS['topic_id'] ) {
		echo "ERROR: Cannot select this topic for parent topic!";
		exit();
	}
	$xt->setTopicPid($HTTP_POST_VARS['topic_pid']);
	if (empty($HTTP_POST_VARS['topic_title'])) {
		redirect_header("index.php?op=topicsmanager", 2, _AM_ERRORTOPICNAME);
	}
	$xt->setTopicTitle($HTTP_POST_VARS['topic_title']);
	if ( isset($HTTP_POST_VARS['topic_imgurl']) && $HTTP_POST_VARS['topic_imgurl'] != "" ) {
		$xt->setTopicImgurl($HTTP_POST_VARS['topic_imgurl']);
	}
	$xt->store();
	redirect_header('index.php?op=topicsmanager',1,_AM_DBUPDATED);
	exit();
}
function delTopic() {
    global $xoopsDB, $HTTP_POST_VARS, $HTTP_GET_VARS, $xoopsConfig, $xoopsModule;
    if ( $HTTP_POST_VARS['ok'] != 1 ) {
		xoops_cp_header();
		echo "<h4>"._AM_CONFIG."</h4>";
		xoops_confirm(array('op' => 'delTopic', 'topic_id' => intval($HTTP_GET_VARS['topic_id']), 'ok' => 1), 'index.php', _AM_WAYSYWTDTTAL);
    } else {
		$xt = new XoopsTopic($xoopsDB->prefix("topics"),$HTTP_POST_VARS['topic_id']);
		//get all subtopics under the specified topic
		$topic_arr = $xt->getAllChildTopics();
		array_push($topic_arr, $xt);
		foreach($topic_arr as $eachtopic){
			//get all stories in each topic
			$story_arr = NewsStory::getByTopic($eachtopic->topic_id());
			foreach($story_arr as $eachstory){
				if (false != $eachstory->delete()) {
					xoops_comment_delete($xoopsModule->getVar('mid'), $eachstory->storyid());
					xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'story', $eachstory->storyid());
				}
			}
			//all stories for each topic is deleted, now delete the topic data
    		$eachtopic->delete();
			xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'category', $eachtopic->topic_id);
		}
		redirect_header('index.php?op=topicsmanager',1,_AM_DBUPDATED);
		exit();
    	}
}

function addTopic() {
	global $xoopsDB, $HTTP_POST_VARS;
	$xt = new XoopsTopic($xoopsDB->prefix("topics"));
	if (!$xt->topicExists($HTTP_POST_VARS['topic_pid'],$HTTP_POST_VARS['topic_pid'])) {
		$xt->setTopicPid($HTTP_POST_VARS['topic_pid']);
		if (empty($HTTP_POST_VARS['topic_title'])) {
			redirect_header("index.php?op=topicsmanager", 2, _AM_ERRORTOPICNAME);
		}
    	$xt->setTopicTitle($HTTP_POST_VARS['topic_title']);
    	if ( isset($HTTP_POST_VARS['topic_imgurl']) && $HTTP_POST_VARS['topic_imgurl'] != "" ) {
			$xt->setTopicImgurl($HTTP_POST_VARS['topic_imgurl']);
		}
		$xt->store();
		$notification_handler =& xoops_gethandler('notification');
		$tags = array();
		$tags['TOPIC_NAME'] = $HTTP_POST_VARS['topic_title'];
		$notification_handler->triggerEvent('global', 0, 'new_category', $tags);
		redirect_header('index.php?op=topicsmanager',1,_AM_DBUPDATED);
	} else {
		echo "Topic exists!";
	}
	exit();
}

switch($op){
	case "edit":
		xoops_cp_header();
		echo "<h4>"._AM_CONFIG."</h4>";
		//$xoopsModule->printAdminMenu();
		//echo "<br />";
		echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
		echo "<h4>"._AM_EDITARTICLE."</h4>";
		$story = new NewsStory($storyid);
		$title = $story->title("Edit");
		$hometext = $story->hometext("Edit");
		$bodytext = $story->bodytext("Edit");
		$nohtml = $story->nohtml();
		$nosmiley = $story->nosmiley();
		$ihome = $story->ihome();
		$topicid = $story->topicid();
		$published = $story->published();
		$expired = $story->expired();
//		$notifypub = $story->notifypub();
		$type = $story->type();
		$topicdisplay = $story->topicdisplay();
		$topicalign = $story->topicalign(false);
		$isedit = 1;
		include "storyform.inc.php";
		echo"</td></tr></table>";
		break;

	case "newarticle":
		xoops_cp_header();
		echo "<h4>"._AM_CONFIG."</h4>";
		include_once XOOPS_ROOT_PATH."/class/module.textsanitizer.php";
		//$xoopsModule->printAdminMenu();
		//echo "<br />";
		newSubmissions();
		autoStories();
		lastStories();
		expStories();
		echo "<br />";
		echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
		echo "<h4>"._AM_POSTNEWARTICLE."</h4>";
		$type = "admin";
		include "storyform.inc.php";
		echo"</td></tr></table>";
		break;

	case "preview":
		xoops_cp_header();
		echo "<h4>"._AM_CONFIG."</h4>";
		if(isset($storyid)){
			$story = new NewsStory($storyid);
			$published = $story->published();
			$expired = $story->expired();
		}else{
			$story = new NewsStory();
		}
		$story->setTitle($title);
		$story->setHomeText($hometext);
		$story->setBodyText($bodytext);
		if(isset($nohtml) && ($nohtml == 0 || $nohtml == 1)){
			$story->setNohtml($nohtml);
		}
		if(isset($nosmiley) && ($nosmiley == 0 || $nosmiley == 1)){
			$story->setNosmiley($nosmiley);
		}

		$xt = new XoopsTopic($xoopsDB->prefix("topics"));
		$p_title = $story->title("Preview");
		$p_hometext = $story->hometext("Preview");
		$p_bodytext = $story->bodytext("Preview");
		$title = $story->title("InForm");
		$hometext = $story->hometext("InForm");
		$bodytext = $story->bodytext("InForm");
		echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
		$timage = "";
		$warning = "";
		if ( $topicdisplay ) {
			if ( $topicalign == "L" ){
				$align = "left";
			} else {
				$align = "right";
			}
			if ( empty($topicid) ) {
	    			$warning = "<div style='text-align: center;'><blink><b>"._AR_SELECTTOPIC."</b></blink></div>";
				$timage = "";
			} else {
				$xt = new XoopsTopic($xoopsDB->prefix("topics"), $topicid);
				if ($xt->topic_imgurl() != '' && file_exists(XOOPS_ROOT_PATH.'/modules/news/images/topics/'.$xt->topic_imgurl())) {
					$timage = "<img src='".XOOPS_URL."/modules/news/images/topics/".$xt->topic_imgurl()."' align='$align' border='0' hspace='10' vspace=10' />";
				}
			}
		}
		if ( isset($p_bodytext) && $p_bodytext != "" ) {
    			echo "<p><b>".$p_title."</b><br /><br />".$timage."".$p_hometext."<br /><br />".$p_bodytext."<br /><br /></p>";
		} else {
			echo "<p><b>".$p_title."</b><br /><br />".$timage."".$p_hometext."<br /><br /></p>";
		}
		echo $warning;
		echo"</td></tr></table><br />";
		echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
		include "storyform.inc.php";
		echo"</td></tr></table>";
		break;

	case "save":
		if ( empty($storyid) ) {
			$story = new NewsStory();
			$story->setUid($xoopsUser->uid());
			if ( !empty($autodate) ) {
				$pubdate = mktime($autohour, $automin, 0, $automonth, $autoday, $autoyear);
				$offset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'];
				$pubdate = $pubdate - ($offset * 3600);
				$story->setPublished($pubdate);
			} else {
				$story->setPublished(time());
			}
			if ( !empty($autoexpdate) ) {
				$expdate = mktime($autoexphour, $autoexpmin, 0, $autoexpmonth, $autoexpday, $autoexpyear);
				$offset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'];
				$expdate = $expdate - ($offset * 3600);
				$story->setExpired($expdate);
			} else {
				$story->setExpired(0);
			}
			$story->setType($type);
			$story->setHostname(getenv("REMOTE_ADDR"));
//			$story->setNotifyPub($notifypub);
		} else {
			$story = new NewsStory($storyid);
			if ( !empty($autodate) ) {
    				$pubdate = mktime($autohour, $automin, 0, $automonth, $autoday, $autoyear);
				$offset = $xoopsUser->timezone();
				$offset = $offset - $xoopsConfig['server_TZ'];
				$pubdate = $pubdate - ($offset * 3600);
				$story->setPublished($pubdate);
			} elseif ( ($story->published() == 0) && $approve ) {
				$story->setPublished(time());
				$isnew = 1;
			} else {
				if ( !empty($movetotop) ) {
					$story->setPublished(time());
				}
			}
			if ( !empty($autoexpdate) ) {
				$expdate = mktime($autoexphour, $autoexpmin, 0, $autoexpmonth, $autoexpday, $autoexpyear);
				$offset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'];
				$expdate = $expdate - ($offset * 3600);
				$story->setExpired($expdate);
			}
		}
		$story->setApproved($approve);
		$story->setTopicId($topicid);
		$story->setTitle($title);
		$story->setHometext($hometext);
		$story->setBodytext($bodytext);
		$nohtml =  (empty($nohtml)) ? 0 : 1;
		$nosmiley =  (empty($nosmiley)) ? 0 : 1;
		$story->setNohtml($nohtml);
		$story->setNosmiley($nosmiley);
		$story->setIhome($ihome);
		$story->setTopicalign($topicalign);
		$story->setTopicdisplay($topicdisplay);
		$story->store();
		$notification_handler =& xoops_gethandler('notification');
		$tags = array();
		$tags['STORY_NAME'] = $story->title();
		$tags['STORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/article.php?storyid=' . $story->storyid();
		if (!empty($isnew)) {
			$notification_handler->triggerEvent('story', $story->storyid(), 'approve', $tags);
		}
		$notification_handler->triggerEvent('global', 0, 'new_story', $tags);
		/*
			$poster = new XoopsUser($story->uid());
			$subject = _AM_ARTPUBLISHED;
			$message = sprintf(_AM_HELLO,$poster->uname());
			$message .= "\n\n"._AM_YOURARTPUB."\n\n";
			$message .= _AM_TITLEC.$story->title()."\n"._AM_URLC.XOOPS_URL."/modules/".$xoopsModule->dirname()."/article.php?storyid=".$story->storyid()."\n"._AM_PUBLISHEDC.formatTimestamp($story->published(),"m",0)."\n\n";
			$message .= $xoopsConfig['sitename']."\n".XOOPS_URL."";
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setToEmails($poster->getVar("email"));
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject($subject);
			$xoopsMailer->setBody($message);
			$xoopsMailer->send();
		}
		*/
		redirect_header('index.php?op=newarticle',1,_AM_DBUPDATED);
		exit();
		break;

	case "delete":
		if ( !empty($ok) ) {
            if (empty($storyid)) {
                redirect_header('index.php?op=newarticle',2,_AM_EMPTYNODELETE);
                exit();
            }
			$story = new NewsStory($storyid);
			$story->delete();
			xoops_comment_delete($xoopsModule->getVar('mid'), $storyid);
			xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'story', $storyid);
			redirect_header('index.php?op=newarticle',1,_AM_DBUPDATED);
			exit();
		} else {
			xoops_cp_header();
			echo "<h4>"._AM_CONFIG."</h4>";
			xoops_confirm(array('op' => 'delete', 'storyid' => $storyid, 'ok' => 1), 'index.php', _AM_RUSUREDEL);
		}
		break;
	case "topicsmanager":
		topicsmanager();
		break;

	case "addTopic":
		addTopic();
		break;

	case "delTopic":
		delTopic();
		break;
	case "modTopic":
		modTopic();
		break;
	case "modTopicS":
		modTopicS();
		break;
	case "default":
	default:
		xoops_cp_header();
		echo "<h4>"._AM_CONFIG."</h4>";
		echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
		echo " - <b><a href='".XOOPS_URL.'/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod='.$xoopsModule->getVar('mid')."'>"._AM_GENERALCONF."</a></b><br /><br />\n";
		echo " - <b><a href='index.php?op=topicsmanager'>"._AM_TOPICSMNGR."</a></b>";
		echo "<br /><br />\n";
		echo " - <b><a href='index.php?op=newarticle'>"._AM_PEARTICLES."</a></b>\n";
    		echo"</td></tr></table>";
		break;
}

xoops_cp_footer();
?>

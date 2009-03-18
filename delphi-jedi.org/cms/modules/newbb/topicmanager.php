<?php
/***************************************************************************
                            topicmanager.php  -  description
                             -------------------
    begin                : Sat June 17 2000
    copyright            : (C) 2001 The phpBB Group
    email                : support@phpbb.com

    $Id: topicmanager.php,v 1.7 2003/03/18 14:52:48 okazu Exp $

 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
include "header.php";
$accesserror = 0;
if ( $xoopsUser ) {
	if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
		if ( !is_moderator($forum, $xoopsUser->uid()) ) {
			$accesserror = 1;
		}
	}
} else {
	$accesserror = 1;
}
if ( $accesserror == 1 ) {
	redirect_header("viewtopic.php?topic_id=$topic_id&amp;post_id=$post_id&amp;order=$order&amp;viewmode=$viewmode&amp;pid=$pid&amp;forum=$forum",3,_MD_YANTMOTFTYCPTF);
	exit();
}

include XOOPS_ROOT_PATH.'/header.php';
OpenTable();
if ( $HTTP_POST_VARS['submit'] ) {
	foreach (array('forum', 'topic_id', 'newforum') as $getint) {
	    ${$getint} = isset($HTTP_POST_VARS[$getint]) ? intval($HTTP_POST_VARS[$getint]) : 0;
    }
	switch ($HTTP_POST_VARS['mode']) {
	case 'del':
		// Update the users's post count, this might be slow on big topics but it makes other parts of the
	    // forum faster so we win out in the long run.
		$sql = "SELECT uid, post_id FROM ".$xoopsDB->prefix("bb_posts")." WHERE topic_id = $topic_id";
		if ( !$r = $xoopsDB->query($sql) ) {
			exit(_MD_COULDNOTQUERY);
		}
		while ( $row = $xoopsDB->fetchArray($r) ) {
			if ( $row['uid'] != 0 ) {
				$sql = sprintf("UPDATE %s SET posts = posts - 1 WHERE uid = %u", $xoopsDB->prefix("users"), $row['uid']);
	    		$xoopsDB->query($sql);
	 		}
		}

		// Get the post ID's we have to remove.
		$sql = "SELECT post_id FROM ".$xoopsDB->prefix("bb_posts")." WHERE topic_id = $topic_id";
		if ( !$r = $xoopsDB->query($sql) ) {
			exit(_MD_COULDNOTQUERY);
		}
		while ( $row = $xoopsDB->fetchArray($r) ) {
			$posts_to_remove[] = $row['post_id'];
		}
		
		$sql = sprintf("DELETE FROM %s WHERE topic_id = %u", $xoopsDB->prefix("bb_posts"), $topic_id);
		if ( !$result = $xoopsDB->query($sql) ) {
			exit(_MD_COULDNOTREMOVE);
		}
		$sql= sprintf("DELETE FROM %s WHERE topic_id = %u", $xoopsDB->prefix("bb_topics"), $topic_id);
		if ( !$result = $xoopsDB->query($sql) ) {
			exit(_MD_COULDNOTQUERY);
		}

		$sql = "DELETE FROM ".$xoopsDB->prefix("bb_posts_text")." WHERE ";
		for ( $x = 0; $x < count($posts_to_remove); $x++ ) {
			if ( $set ) {
				$sql .= " OR ";
			}
			$sql .= "post_id = ".$posts_to_remove[$x];
			$set = true;
		}

		if ( !$xoopsDB->query($sql) ) {
			exit(_MD_COULDNOTREMOVETXT);
		}
		sync($forum, 'forum');
		// RMV-NOTIFY
		xoops_notification_deletebyitem ($xoopsModule->getVar('mid'), 'thread', $topic_id);
		echo _MD_TTHBRFTD."<p><a href='viewforum.php?forum=$forum'>"._MD_RETURNTOTHEFORUM."</a></p><p><a href='index.php'>"._MD_RTTFI."</a></p>";
		break;
	case 'move':
		if ($newforum > 0) {
			$sql = sprintf("UPDATE %s SET forum_id = %u WHERE topic_id = %u", $xoopsDB->prefix("bb_topics"), $newforum, $topic_id);
	    	if ( !$r = $xoopsDB->query($sql) ) {
				exit(_MD_EPGBATA);
			}
			$sql = sprintf("UPDATE %s SET forum_id = %u WHERE topic_id = %u", $xoopsDB->prefix("bb_posts"), $newforum, $topic_id);
			if ( !$r = $xoopsDB->query($sql) ) {
				exit(_MD_EPGBATA);
			}
			sync($newforum, 'forum');
			sync($forum, 'forum');
		}
		echo _MD_TTHBM."<p><a href='viewtopic.php?topic_id=$topic_id&amp;forum=$newforum'>"._MD_VTUT."</a></p><p><a href='index.php'>"._MD_RTTFI."</a></p>";
		break;
	case 'lock':
		$sql = sprintf("UPDATE %s SET topic_status = 1 WHERE topic_id = %u", $xoopsDB->prefix("bb_topics"), $topic_id);
	    if ( !$r = $xoopsDB->query($sql) ) {
			exit(_MD_EPGBATA);
		}
		echo _MD_TTHBL."<p><a href='viewtopic.php?topic_id=$topic_id&amp;forum=$forum'>"._MD_VIEWTHETOPIC."</a></p><p><a href='index.php'>"._MD_RTTFI."</a></p>";
		break;
	case 'unlock':
		$sql = sprintf("UPDATE %s SET topic_status = 0 WHERE topic_id = %u", $xoopsDB->prefix("bb_topics"), $topic_id);
	    if ( !$r = $xoopsDB->query($sql) ) {
			exit("Error - Could not unlock the selected topic. Please go back and try again.");
		}
		echo _MD_TTHBU."<p><a href='viewtopic.php?topic_id=$topic_id&amp;forum=$forum'>"._MD_VIEWTHETOPIC."</a></p><p><a href='index.php'>"._MD_RTTFI."</a></p>";
		break;
	case 'sticky':
		$sql = sprintf("UPDATE %s SET topic_sticky = 1 WHERE topic_id = %u", $xoopsDB->prefix("bb_topics"), $topic_id);
	    if ( !$r = $xoopsDB->query($sql) ) {
			exit("Error - Could not sticky the selected topic. Please go back and try again.");
		}
		echo _MD_TTHBS."<p><a href='viewtopic.php?topic_id=$topic_id&amp;forum=$forum'>"._MD_VIEWTHETOPIC."</a></p><p><a href='index.php'>"._MD_RTTFI."</a></p>";
		break;
	case 'unsticky':
		$sql = sprintf("UPDATE %s SET topic_sticky = 0 WHERE topic_id = %u", $xoopsDB->prefix("bb_topics"), $topic_id);
	    if ( !$r = $xoopsDB->query($sql) ) {
			exit("Error - Could not unsticky the selected topic. Please go back and try again.");
		}
		echo _MD_TTHBUS."<p><a href='viewtopic.php?topic_id=$topic_id&amp;forum=$forum'>"._MD_VIEWTHETOPIC."</a></p><p><a href='index.php'>"._MD_RTTFI."</a></p>";
		break;
	}
} else {  // No submit
	foreach (array('forum', 'topic_id') as $getint) {
		${$getint} = isset($HTTP_GET_VARS[$getint]) ? intval($HTTP_GET_VARS[$getint]) : 0;
	}
	$mode = $HTTP_GET_VARS['mode'];
    echo "<form action='".$HTTP_SERVER_VARS['PHP_SELF']."' method='post'>
	<table border='0' cellpadding='1' cellspacing='0' align='center' width='95%'><tr><td class='bg2'>
	<table border='0' cellpadding='1' cellspacing='1' width='100%'>
	<tr class='bg3' align='left'>";
	switch ( $mode ) {
	case 'del':
		echo '<td colspan="2">'. _MD_OYPTDBATBOTFTTY .'</td>';
		break;
	case 'move':
		echo '<td colspan="2">'._MD_OYPTMBATBOTFTTY.'</td>';
		break;
	case 'lock':
		echo '<td colspan="2">'._MD_OYPTLBATBOTFTTY.'</td>';
		break;
	case 'unlock':
		echo '<td colspan="2">'._MD_OYPTUBATBOTFTTY.'</td>';
		break;
	case 'sticky':
		echo '<td colspan="2">'._MD_OYPTSBATBOTFTTY.'</td>';
		break;
	case 'unsticky':
		echo '<td colspan="2">'._MD_OYPTTBATBOTFTTY.'</td>';
		break;
	}
	echo '</tr>';

	if ( $mode == 'move' ) {
		echo '<tr>
		<td class="bg3">'._MD_MOVETOPICTO.'</td>
		<td class="bg1"><select name="newforum" size="0">';
		$sql = "SELECT forum_id, forum_name FROM ".$xoopsDB->prefix("bb_forums")." WHERE forum_id != $forum ORDER BY forum_id";
		if ( $result = $xoopsDB->query($sql) ) {
			if ( $myrow = $xoopsDB->fetchArray($result) ) {
				do {
					echo "<option value='".$myrow['forum_id']."'>".$myrow['forum_name']."</option>\n";
				} while ( $myrow = $xoopsDB->fetchArray($result) );
			} else {
				echo "<option value='-1'>"._MD_NOFORUMINDB."</option>\n";
			}
		} else {
			echo "<option value='-1'>"._MD_DATABASEERROR."</option>\n";
		}
		echo '</select></td></tr>';
	}
	echo '<tr class="bg3">
	<td colspan="2" align="center">';

	switch ( $mode ) {
	case 'del':
		echo '<input type="hidden" name="mode" value="del" />
		<input type="hidden" name="topic_id" value="'.$topic_id.'" />
		<input type="hidden" name="forum" value="'.$forum.'" />
		<input type="submit" name="submit" value="'._MD_DELTOPIC.'" />';
		break;
	case 'move':
		echo '<input type="hidden" name="mode" value="move" />
		<input type="hidden" name="topic_id" value="'.$topic_id.'" />
		<input type="hidden" name="forum" value="'.$forum.'" />
		<input type="submit" name="submit" value="'._MD_MOVETOPIC.'" />';
		break;
	case 'lock':
		echo '<input type="hidden" name="mode" value="lock" />
		<input type="hidden" name="topic_id" value="'.$topic_id.'" />
		<input type="hidden" name="forum" value="'.$forum.'" />
		<input type="submit" name="submit" value="'._MD_LOCKTOPIC.'" />';
		break;
	case 'unlock':
		echo '<input type="hidden" name="mode" value="unlock" />
		<input type="hidden" name="topic_id" value="'.$topic_id.'" />
		<input type="hidden" name="forum" value="'.$forum.'" />
		<input type="submit" name="submit" value="'._MD_UNLOCKTOPIC.'" />';
		break;
	case 'sticky':
		echo "<input type='hidden' name='mode' value='sticky' />
		<input type='hidden' name='topic_id' value='$topic_id' />
		<input type='hidden' name='forum' value='$forum' />
		<input type='submit' name='submit' value='"._MD_STICKYTOPIC."' />";
		break;
	case 'unsticky':
		echo "<input type='hidden' name='mode' value='unsticky' />
		<input type='hidden' name='topic_id' value='$topic_id' />
		<input type='hidden' name='forum' value='$forum' />
		<input type='submit' name='submit' value='". _MD_UNSTICKYTOPIC."' />";
		break;
	}
	echo '</td></tr>
	</form>
	</table></td></tr></table>';
}
CloseTable();
include XOOPS_ROOT_PATH.'/footer.php';
?>

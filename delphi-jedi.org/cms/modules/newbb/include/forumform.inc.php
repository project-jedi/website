<?php
// $Id: forumform.inc.php,v 1.14 2003/09/26 13:40:00 okazu Exp $
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

include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include XOOPS_ROOT_PATH."/include/xoopscodes.php";

echo "<form action='post.php' method='post' name='forumform' id='forumform' onsubmit='return xoopsValidate(\"subject\", \"message\", \"contents_submit\", \"".htmlspecialchars(_PLZCOMPLETE, ENT_QUOTES)."\", \"".htmlspecialchars(_MESSAGETOOLONG, ENT_QUOTES)."\", \"".htmlspecialchars(_ALLOWEDCHAR, ENT_QUOTES)."\", \"".htmlspecialchars(_CURRCHAR, ENT_QUOTES)."\");'><table cellspacing='1' class='outer' width='100%'><tr><td class='head' width='25%' valign='top'>". _MD_ABOUTPOST .":</td>";

if ( $forumdata['forum_type'] == 1 ) {
	echo "<td class='even'>". _MD_PRIVATE ."</td>";
} elseif ( $forumdata['forum_access'] == 1 ) {
	echo "<td class='even'>". _MD_REGCANPOST ."</td>";
} elseif ( $forumdata['forum_access'] == 2 ) {
	echo "<td class='even'>". _MD_ANONCANPOST ."</td>";
} elseif ( $forumdata['forum_access'] == 3 ) {
	echo "<td class='even'>". _MD_MODSCANPOST ."</td>";
}

echo "</tr>
<tr>
<td class='head' valign='top' nowrap='nowrap'>". _MD_SUBJECTC ."</td>
<td class='odd'>";

echo "<input type='text' id='subject' name='subject' size='60' maxlength='100' value='$subject' /></td></tr>
<tr>
<td class='head' valign='top' nowrap='nowrap'>". _MD_MESSAGEICON ."</td>
<td class='even'>
";

$lists = new XoopsLists;
$filelist = $lists->getSubjectsList();
$count = 1;
while ( list($key, $file) = each($filelist) ) {
	$checked = "";
	if ( isset($icon) && $file==$icon ) {
		$checked = " checked='checked'";
	}
	echo "<input type='radio' value='$file' name='icon'$checked />&nbsp;";
	echo "<img src='".XOOPS_URL."/images/subject/$file' alt='' />&nbsp;";
	if ( $count == 8 ) {
		echo "<br />";
		$count = 0;
	}
	$count++;
}

echo "</td></tr>
<tr align='left'>
<td class='head' valign='top' nowrap='nowrap'>". _MD_MESSAGEC ."
</td>
<td class='odd'>";
xoopsCodeTarea("message");

if ( !empty($isreply) && isset($hidden) && $hidden != "" ) {
	echo "<input type='hidden' name='isreply' value='1' />";
	echo "<input type='hidden' name='hidden' id='hidden' value='$hidden' />
	<input type='button' name='quote' class='formButton' value='"._MD_QUOTE."' onclick='xoopsGetElementById(\"message\").value=xoopsGetElementById(\"message\").value + xoopsGetElementById(\"hidden\").value; xoopsGetElementById(\"hidden\").value=\"\";' /><br />";
}
xoopsSmilies("message");

echo "</td></tr>
<tr>";
echo "<td class='head' valign='top' nowrap='nowrap'>"._MD_OPTIONS."</td>\n";
echo "<td class='even'>";

if ( $xoopsUser && $forumdata['forum_access'] == 2 && !empty($post_id) ) {
	echo "<input type='checkbox' name='noname' value='1'";
	if ( isset($noname) && $noname ) {
		echo " checked='checked'";
	}
	echo " />&nbsp;"._MD_POSTANONLY."<br />\n";
}

echo "<input type='checkbox' name='nosmiley' value='1'";
if ( isset($nosmiley) && $nosmiley ) {
	echo " checked='checked'";
}
echo " />&nbsp;"._MD_DISABLESMILEY."<br />\n";

if ( $forumdata['allow_html'] ) {
	echo "<input type='checkbox' name='nohtml' value='1'";
	if ( $nohtml ) {
		echo " checked='checked'";
	}
	echo " />&nbsp;"._MD_DISABLEHTML."<br />\n";
} else {
	echo "<input type='hidden' name='nohtml' value='1' />";
}

if ( $forumdata['allow_sig'] && $xoopsUser ) {
	echo "<input type='checkbox' name='attachsig' value='1'";
	if (isset($HTTP_POST_VARS['contents_preview'])) {
		if ( $attachsig ) {
			echo " checked='checked' />&nbsp;";
		} else {
			echo " />&nbsp;";
		}
	} else {
		if ($xoopsUser->getVar('attachsig') || !empty($attachsig)) {
			echo " checked='checked' />&nbsp;";
		} else {
			echo "/>&nbsp;";
		}
	}

	echo _MD_ATTACHSIG."<br />\n";
}

if (!empty($xoopsUser) && !empty($xoopsModuleConfig['notification_enabled'])) {
	echo "<input type='hidden' name='istopic' value='1' />";
	echo "<input type='checkbox' name='notify' value='1'";
	if (!empty($notify)) {
		// If 'notify' set, use that value (e.g. preview)
		echo ' checked="checked"';
	} else {
		// Otherwise, check previous subscribed status...
		$notification_handler =& xoops_gethandler('notification');
		if (!empty($topic_id) && $notification_handler->isSubscribed('thread', $topic_id, 'new_post', $xoopsModule->getVar('mid'), $xoopsUser->getVar('uid'))) {
			echo ' checked="checked"';
		}
	}	
	echo " />&nbsp;"._MD_NEWPOSTNOTIFY."<br />\n";
}

$post_id = isset($post_id) ? intval($post_id) : '';
$topic_id = isset($topic_id) ? intval($topic_id) : '';
$order = isset($order) ? intval($order) : '';
$pid = isset($pid) ? intval($pid) : 0;
echo "</td></tr>
<tr><td class='head'></td><td class='odd'>
<input type='hidden' name='pid' value='".intval($pid)."' />
<input type='hidden' name='post_id' value='".$post_id."' />
<input type='hidden' name='topic_id' value='".$topic_id."' />
<input type='hidden' name='forum' value='".intval($forum)."' />
<input type='hidden' name='viewmode' value='$viewmode' />
<input type='hidden' name='order' value='".$order."' />
<input type='submit' name='contents_preview' class='formButton' value='"._PREVIEW."' />&nbsp;<input type='submit' name='contents_submit' class='formButton' id='contents_submit' value='"._SUBMIT."' />
<input type='button' onclick='location=\"";
if ( isset($topic_id) && $topic_id != "" ) {
	echo "viewtopic.php?topic_id=".intval($topic_id)."&amp;forum=".intval($forum)."\"'";
} else {
	echo "viewforum.php?forum=".intval($forum)."\"'";
}
echo " class='formButton' value='"._MD_CANCELPOST."' />";
echo "</td></tr></table></form>\n";
?>

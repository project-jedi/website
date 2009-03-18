<?php
// $Id: pmlite.php,v 1.9 2003/03/28 03:45:23 w4z004 Exp $
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

$xoopsOption['pagetype'] = "pmsg";

include "mainfile.php";
$reply = !empty($HTTP_GET_VARS['reply']) ? 1 : 0;
$send = !empty($HTTP_GET_VARS['send']) ? 1 : 0;
$send2 = !empty($HTTP_GET_VARS['send2']) ? 1 : 0;
$to_userid = !empty($HTTP_GET_VARS['to_userid']) ? intval($HTTP_GET_VARS['to_userid']) : 0;
$msg_id = !empty($HTTP_GET_VARS['msg_id']) ? intval($HTTP_GET_VARS['msg_id']) : 0;
if ( empty($HTTP_GET_VARS['refresh'] ) && isset($HTTP_POST_VARS['op']) && $HTTP_POST_VARS['op'] != "submit" ) {
	$jump = "pmlite.php?refresh=".time()."";
	if ( $send == 1 ) {
		$jump .= "&amp;send=".$send."";
	} elseif ( $send2 == 1 ) {
		$jump .= "&amp;send2=".$send2."&amp;to_userid=".$to_userid."";
	} elseif ( $reply == 1 ) {
		$jump .= "&amp;reply=".$reply."&amp;msg_id=".$msg_id."";
	} else {
	}
	echo "<html><head><meta http-equiv='Refresh' content='0; url=".$jump."' /></head><body></body></html>";
	exit();
}
xoops_header();
if ($xoopsUser) {
	$myts =& MyTextSanitizer::getInstance();
	if (isset($HTTP_POST_VARS['op']) && $HTTP_POST_VARS['op'] == "submit") {
		$res = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("users")." WHERE uid=".intval($HTTP_POST_VARS['to_userid'])."");
        	list($count) = $xoopsDB->fetchRow($res);
        	if ($count != 1) {
			echo "<br /><br /><div><h4>"._PM_USERNOEXIST."<br />";
			echo _PM_PLZTRYAGAIN."</h4><br />";
            		echo "[ <a href='javascript:history.go(-1)'>"._PM_GOBACK."</a> ]</div>";
		} else {
			$pm_handler =& xoops_gethandler('privmessage');
			$pm =& $pm_handler->create();
			$pm->setVar("subject", $HTTP_POST_VARS['subject']);
			$pm->setVar("msg_text", $HTTP_POST_VARS['message']);
			$pm->setVar("to_userid", $HTTP_POST_VARS['to_userid']);
			$pm->setVar("from_userid", $xoopsUser->getVar("uid"));
			if (!$pm_handler->insert($pm)) {
				echo $pm->getHtmlErrors();
				echo "<br /><a href='javascript:history.go(-1)'>"._PM_GOBACK."</a>";
			} else {
				echo "<br /><br /><div style='text-align:center;'><h4>"._PM_MESSAGEPOSTED."</h4><br /><a href=\"javascript:window.opener.location='".XOOPS_URL."/viewpmsg.php';window.close();\">"._PM_CLICKHERE."</a><br /><br /><a href=\"javascript:window.close();\">"._PM_ORCLOSEWINDOW."</a></div>";
			}
		}
	} elseif ($reply == 1 || $send == 1 || $send2 == 1) {
		include XOOPS_ROOT_PATH."/include/xoopscodes.php";
		if ($reply == 1) {
			$pm_handler =& xoops_gethandler('privmessage');
			$pm =& $pm_handler->get($msg_id);
			if ($pm->getVar("to_userid") == $xoopsUser->getVar('uid')) {
				$pm_uname = XoopsUser::getUnameFromId($pm->getVar("from_userid"));
                $message  = "[quote]\n";
				$message .= sprintf(_PM_USERWROTE,$pm_uname);
				$message .= "\n".$pm->getVar("msg_text", "E")."\n[/quote]";
			} else {
				unset($pm);
				$reply = $send2 = 0;
			}
		}
		echo "<form action='pmlite.php' method='post' name='coolsus'>\n";
        	echo "<table width='300' align='center' class='outer'><tr><td class='head' width='25%'>"._PM_TO."</td>";
		if ( $reply == 1 ) {
			echo "<td class='even'><input type='hidden' name='to_userid' value='".$pm->getVar("from_userid")."' />".$pm_uname."</td>";
		} elseif ( $send2 == 1 ) {
			$to_username = XoopsUser::getUnameFromId($to_userid);
			echo "<td class='even'><input type='hidden' name='to_userid' value='".$to_userid."' />".$to_username."</td>";
		} else {
			echo "<td class='even'><select name='to_userid'>";
			$result = $xoopsDB->query("SELECT uid, uname FROM ".$xoopsDB->prefix("users")." WHERE level > 0 ORDER BY uname");
            while ( list($ftouid, $ftouname) = $xoopsDB->fetchRow($result) ) {
				echo "<option value='".$ftouid."'>".$myts->makeTboxData4Show($ftouname)."</option>";
			}
            echo "</select></td>";
        }
        echo "</tr>";
        echo "<tr><td class='head' width='25%'>"._PM_SUBJECTC."</td>";
		if ( $reply == 1 ) {
			$subject = $pm->getVar('subject', 'E');
			if (!preg_match("/^Re:/i",$subject)) {
				$subject = 'Re: '.$subject;
			}
			echo "<td class='even'><input type='text' name='subject' value='".$subject."' size='30' maxlength='100' /></td>";
		} else {
			echo "<td class='even'><input type='text' name='subject' size='30' maxlength='100' /></td>";
		}
		echo "</tr>";
        echo "<tr valign='top'><td class='head' width='25%'>"._PM_MESSAGEC."</td>";
		echo "<td class='even'>";
		xoopsCodeTarea("message",37,8);
		xoopsSmilies("message");
		echo "</td>";
		echo "</tr>";
        echo "<tr><td class='head'>&nbsp;</td><td class='even'>
		<input type='hidden' name='op' value='submit' />
		<input type='submit' class='formButton' name='submit' value='"._PM_SUBMIT."' />&nbsp;
		<input type='reset' class='formButton' value='"._PM_CLEAR."' />
		&nbsp;<input type='button' class='formButton' name='cancel' value='"._PM_CANCELSEND."' onclick='javascript:window.close();' />
		</td></tr></table>\n";
	   	echo "</form>\n";
	}
} else {
	echo _PM_SORRY."<br /><br /><a href='".XOOPS_URL."/register.php'>"._PM_REGISTERNOW."</a>.";
}

xoops_footer();

?>
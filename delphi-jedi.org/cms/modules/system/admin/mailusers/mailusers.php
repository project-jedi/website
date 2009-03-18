<?php
// $Id: mailusers.php,v 1.8 2003/08/11 06:08:31 mvandam Exp $
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

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
	exit("Access Denied");
} else {
	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$op = "form";

	if (!empty($HTTP_POST_VARS['op']) && $HTTP_POST_VARS['op'] == "send") {
		$op =  $HTTP_POST_VARS['op'];
	}

	if ( $op == "form" ) {
		xoops_cp_header();
		//OpenTable();
		$display_criteria = 1;
		include XOOPS_ROOT_PATH."/modules/system/admin/mailusers/mailform.php";
		$form->display();
		//CloseTable();
		xoops_cp_footer();
	}

	if ( $op == "send" && !empty($HTTP_POST_VARS['mail_send_to']) ) {
		$added = array();
		$added_id = array();
		$criteria = array();
		if ( !empty($HTTP_POST_VARS['mail_inactive']) ) {
			$criteria[] = "level = 0";
		} else {
			if (!empty($HTTP_POST_VARS['mail_mailok'])) {
				$criteria[] = 'user_mailok = 1';
			} else {
				if (!empty($HTTP_POST_VARS['mail_to_group'])) {
					$member_handler =& xoops_gethandler('member');
					$user_list = array();
					foreach ($HTTP_POST_VARS['mail_to_group'] as $groupid ) {
						$members =& $member_handler->getUsersByGroup($groupid, true);
						// RMV: changed this because makes more sense to me
						// if options all grouped by 'AND', not 'OR'
						foreach ($members as $member) {
							if (!in_array($member->getVar('uid'), $user_list)) {
								$user_list[] = $member->getVar('uid');
							}
						}
						//	if (!in_array($member->getVar('uid'), $added_id) ) {
						//		$added_id[] = $member->getVar('uid');
						//		$added[] =& $member;
						//		unset($member);
						//	}
						//}
					}
					if (!empty($user_list)) {
						$criteria[] = 'uid IN (' . join(',', $user_list) . ')';
					}
				}
			}
			if ( !empty($HTTP_POST_VARS['mail_lastlog_min']) ) {
				$f_mail_lastlog_min = trim($HTTP_POST_VARS['mail_lastlog_min']);
				$time = mktime(0,0,0,substr($f_mail_lastlog_min,5,2),substr($f_mail_lastlog_min,8,2),substr($f_mail_lastlog_min,0,4));
				if ( $time > 0 ) {
					$criteria[] = "last_login > $time";
				}
			}
			if ( !empty($HTTP_POST_VARS['mail_lastlog_max']) ) {
				$f_mail_lastlog_max = trim($HTTP_POST_VARS['mail_lastlog_max']);
				$time = mktime(0,0,0,substr($f_mail_lastlog_max,5,2),substr($f_mail_lastlog_max,8,2),substr($f_mail_lastlog_max,0,4));
				if ( $time > 0 ) {
					$criteria[] = "last_login < $time";
				}
			}
			if ( !empty($HTTP_POST_VARS['mail_idle_more']) && is_numeric($HTTP_POST_VARS['mail_idle_more']) ) {
				$f_mail_idle_more = intval(trim($HTTP_POST_VARS['mail_idle_more']));
				$time = 60 * 60 * 24 * $f_mail_idle_more;
				$time = time() - $time;
				if ( $time > 0 ) {
					$criteria[] = "last_login < $time";
				}
			}
			if ( !empty($HTTP_POST_VARS['mail_idle_less']) && is_numeric($HTTP_POST_VARS['mail_idle_less']) ) {
				$f_mail_idle_less = intval(trim($HTTP_POST_VARS['mail_idle_less']));
				$time = 60 * 60 * 24 * $f_mail_idle_less;
				$time = time() - $time;
				if ( $time > 0 ) {
					$criteria[] = "last_login > $time";
				}
			}
		}
		if ( !empty($HTTP_POST_VARS['mail_regd_min']) ) {
			$f_mail_regd_min = trim($HTTP_POST_VARS['mail_regd_min']);
			$time = mktime(0,0,0,substr($f_mail_regd_min,5,2),substr($f_mail_regd_min,8,2),substr($f_mail_regd_min,0,4));
			if ( $time > 0 ) {
				$criteria[] = "user_regdate > $time";
			}
		}
		if ( !empty($HTTP_POST_VARS['mail_regd_max']) ) {
			$f_mail_regd_max = trim($HTTP_POST_VARS['mail_regd_max']);
			$time = mktime(0,0,0,substr($f_mail_regd_max,5,2),substr($f_mail_regd_max,8,2),substr($f_mail_regd_max,0,4));
			if ( $time > 0 ) {
				$criteria[] = "user_regdate < $time";
			}
		}
		if ( !empty($criteria) ) {
			if ( empty($HTTP_POST_VARS['mail_inactive']) ) {
				$criteria[] = "level > 0";
			}
			$criteria_object = new CriteriaCompo();
			foreach ($criteria as $c) {
				list ($field, $op, $value) = split(' ', $c);
				$criteria_object->add(new Criteria($field,$value,$op), 'AND');
			}
			$member_handler =& xoops_gethandler('member');
			$getusers =& $member_handler->getUsers($criteria_object);
			foreach ($getusers as $getuser) {
				if ( !in_array($getuser->getVar("uid"), $added_id) ) {
					$added[] = $getuser;
					$added_id[] = $getuser->getVar("uid");
				}
			}
		}
		if ( !empty($HTTP_POST_VARS['mail_to_user']) ) {
			foreach ($HTTP_POST_VARS['mail_to_user'] as $to_user) {
				if ( !in_array($to_user, $added_id) ) {
					$added[] = new XoopsUser($to_user);
					$added_id[] = $to_user;
				}
			}
		}
		$added_count = count($added);
		xoops_cp_header();
		//OpenTable();
		if ( $added_count > 0 ) {
			$mail_start = !empty($HTTP_POST_VARS['mail_start']) ? $HTTP_POST_VARS['mail_start'] : 0;
			$mail_end = ($added_count > ($mail_start + 100)) ? ($mail_start + 100) : $added_count;
			$myts =& MyTextSanitizer::getInstance();
			$xoopsMailer =& getMailer();
			for ( $i = $mail_start; $i < $mail_end; $i++) {
				$xoopsMailer->setToUsers($added[$i]);
			}
			$xoopsMailer->setFromName($myts->oopsStripSlashesGPC($HTTP_POST_VARS['mail_fromname']));
			$xoopsMailer->setFromEmail($myts->oopsStripSlashesGPC($HTTP_POST_VARS['mail_fromemail']));
			$xoopsMailer->setSubject($myts->oopsStripSlashesGPC($HTTP_POST_VARS['mail_subject']));
			$xoopsMailer->setBody($myts->oopsStripSlashesGPC($HTTP_POST_VARS['mail_body']));
			if ( in_array("mail", $HTTP_POST_VARS['mail_send_to']) ) {
				$xoopsMailer->useMail();
			}
			if ( in_array("pm", $HTTP_POST_VARS['mail_send_to']) && empty($HTTP_POST_VARS['mail_inactive']) ) {
				$xoopsMailer->usePM();
			}
			$xoopsMailer->send(true);
			echo $xoopsMailer->getSuccess();
			echo $xoopsMailer->getErrors();


			if ( $added_count > $mail_end ) {
				$form = new XoopsThemeForm(_AM_SENDMTOUSERS, "mailusers", "admin.php?fct=mailusers");
				if ( !empty($HTTP_POST_VARS['mail_to_group']) ) {
					foreach ( $HTTP_POST_VARS['mail_to_group'] as $mailgroup) {
						$group_hidden = new XoopsFormHidden("mail_to_group[]", $mailgroup);
						$form->addElement($group_hidden);
					}
				}
				$inactive_hidden = new XoopsFormHidden("mail_inactive", $mail_inactive);
				$lastlog_min_hidden = new XoopsFormHidden("mail_lastlog_min", $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['mail_lastlog_min']));
				$lastlog_max_hidden = new XoopsFormHidden("mail_lastlog_max", $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['mail_lastlog_max']));
				$regd_min_hidden = new XoopsFormHidden("mail_regd_min", $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['mail_regd_max']));
				$regd_max_hidden = new XoopsFormHidden("mail_regd_max", $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['mail_regd_max']));
				$idle_more_hidden = new XoopsFormHidden("mail_idle_more", $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['mail_idle_more']));
				$idle_less_hidden = new XoopsFormHidden("mail_idle_less", $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['mail_idle_less']));
				$fname_hidden = new XoopsFormHidden("mail_fromname", $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['mail_fromname']));
				$femail_hidden = new XoopsFormHidden("mail_fromemail", $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['mail_fromemail']));
				$subject_hidden = new XoopsFormHidden("mail_subject", $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['mail_subject']));
				$body_hidden = new XoopsFormHidden("mail_body", $myts->makeTareaData4PreviewInForm($HTTP_POST_VARS['mail_body']));
				$start_hidden = new XoopsFormHidden("mail_start", $mail_end);
				$to_hidden = new XoopsFormHidden("mail_send_to[]", "mail");
				$op_hidden = new XoopsFormHidden("op", "send");
				$submit_button = new XoopsFormButton("", "mail_submit", _AM_SENDNEXT, "submit");
				$sent_label = new XoopsFormLabel(_AM_SENT, sprintf(_AM_SENTNUM, $HTTP_POST_VARS['mail_start']+1, $mail_end, $added_count));
				$form->addElement($sent_label);
				$form->addElement($inactive_hidden);
				$form->addElement($lastlog_min_hidden);
				$form->addElement($lastlog_max_hidden);
				$form->addElement($regd_min_hidden);
				$form->addElement($regd_max_hidden);
				$form->addElement($idle_more_hidden);
				$form->addElement($idle_less_hidden);
				$form->addElement($fname_hidden);
				$form->addElement($femail_hidden);
				$form->addElement($subject_hidden);
				$form->addElement($body_hidden);
				$form->addElement($to_hidden);
				$form->addElement($op_hidden);
				$form->addElement($start_hidden);
				$form->addElement($submit_button);
				$form->display();
			} else {
				echo "<h4>"._AM_SENDCOMP."</h4>";
			}
		} else {
			echo "<h4>"._AM_NOUSERMATCH."</h4>";
		}
		//CloseTable();
		xoops_cp_footer();
	}
}
?>

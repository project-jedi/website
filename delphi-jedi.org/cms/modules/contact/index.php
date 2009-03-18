<?php
// $Id: index.php,v 1.7 2003/03/26 04:42:53 okazu Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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

include "header.php";
if ( empty($HTTP_POST_VARS['submit']) ) {
	$xoopsOption['template_main'] = 'contact_contactusform.html';
	include XOOPS_ROOT_PATH."/header.php";
	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$company_v = "";
	$name_v = !empty($xoopsUser) ? $xoopsUser->getVar("uname", "E") : "";
	$email_v = !empty($xoopsUser) ? $xoopsUser->getVar("email", "E") : "";
	$url_v = !empty($xoopsUser) ? $xoopsUser->getVar("url", "E") : "";
	$icq_v = !empty($xoopsUser) ? $xoopsUser->getVar("user_icq", "E") : "";
	$location_v = !empty($xoopsUser) ? $xoopsUser->getVar("user_from", "E") : "";
	$comment_v = "";
	include "contactform.php";
	$contact_form->assign($xoopsTpl);
	include XOOPS_ROOT_PATH."/footer.php";
} else {
	extract($HTTP_POST_VARS);
	$myts =& MyTextSanitizer::getInstance();
	$usersEmail = $myts->stripSlashesGPC($usersEmail);
	$usersCompanyName = $myts->stripSlashesGPC($usersCompanyName);
	$usersCompanyLocation = $myts->stripSlashesGPC($usersCompanyLocation);
	$usersComments = $myts->stripSlashesGPC($usersComments);
	$usersName = $myts->stripSlashesGPC($usersName);

	$adminMessage = sprintf(_CT_SUBMITTED,$usersName);
	$adminMessage .= "\n";
	$adminMessage .= ""._CT_EMAIL." $usersEmail\n";
	if ( !empty($usersSite) ) {
		$adminMessage .= ""._CT_URL." $usersSite\n";
	}
	if ( !empty($usersICQ) ) {
		$adminMessage .= ""._CT_ICQ." $usersICQ\n";
	}
	if ( !empty($usersCompanyName) ) {
		$adminMessage .= _CT_COMPANY. " $usersCompanyName\n";
	}
	if ( !empty($usersCompanyLocation) ) {
		$adminMessage .= _CT_LOCATION." $usersCompanyLocation\n";
	}
	$adminMessage .= _CT_COMMENTS."\n";
	$adminMessage .= "\n$usersComments\n";
	$adminMessage .= "\n".$HTTP_SERVER_VARS['HTTP_USER_AGENT']."\n";
	$subject = $xoopsConfig['sitename']." - "._CT_CONTACTFORM;
	$xoopsMailer =& getMailer();
	$xoopsMailer->useMail();
	$xoopsMailer->setToEmails($xoopsConfig['adminmail']);
	$xoopsMailer->setFromEmail($usersEmail);
	$xoopsMailer->setFromName($xoopsConfig['sitename']);
	$xoopsMailer->setSubject($subject);
	$xoopsMailer->setBody($adminMessage);
	$xoopsMailer->send();
	$messagesent = sprintf(_CT_MESSAGESENT,$xoopsConfig['sitename'])."<br />"._CT_THANKYOU."";

	// uncomment the following lines if you want to send confirmation mail to the user
	/*
	$conf_subject = _CT_THANKYOU;
	$userMessage = sprintf(_CT_HELLO,$usersName);
	$userMessage .= "\n\n";
	$userMessage .= sprintf(_CT_THANKYOUCOMMENTS,$xoopsConfig['sitename']);
	$userMessage .= "\n";
	$userMessage .= sprintf(_CT_SENTTOWEBMASTER,$xoopsConfig['sitename']);
	$userMessage .= "\n";
	$userMessage .= _CT_YOURMESSAGE."\n";
	$userMessage .= "\n$usersComments\n\n";
	$userMessage .= "--------------\n";
	$userMessage .= "".$xoopsConfig['sitename']." "._CT_WEBMASTER."\n";
	$userMessage .= "".$xoopsConfig['adminmail']."";
	$xoopsMailer =& getMailer();
	$xoopsMailer->useMail();
	$xoopsMailer->setToEmails($usersEmail);
	$xoopsMailer->setFromEmail($usersEmail);
	$xoopsMailer->setFromName($xoopsConfig['sitename']);
	$xoopsMailer->setSubject($conf_subject);
	$xoopsMailer->setBody($userMessage);
	$xoopsMailer->send();
	$messagesent .= sprintf(_CT_SENTASCONFIRM,$usersEmail);
	*/

	redirect_header(XOOPS_URL."/index.php",2,$messagesent);
}
?>
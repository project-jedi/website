<?php
// $Id: contactform.php,v 1.5 2003/02/12 11:36:33 okazu Exp $
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

$name_text = new XoopsFormText(_CT_NAME, "usersName", 50, 100, $name_v);
$email_text = new XoopsFormText(_CT_EMAIL, "usersEmail", 50, 100, $email_v);
$url_text = new XoopsFormText(_CT_URL, "usersSite", 50, 100, $url_v);
$icq_text = new XoopsFormText(_CT_ICQ, "usersICQ", 50, 100, $icq_v);
$company_text = new XoopsFormText(_CT_COMPANY, "usersCompanyName", 50, 100, $company_v);
$location_text = new XoopsFormText(_CT_LOCATION, "usersCompanyLocation", 50, 100, $location_v);
$comment_textarea = new XoopsFormTextArea(_CT_COMMENTS, "usersComments", $comment_v);
$submit_button = new XoopsFormButton("", "submit", _CT_SUBMIT, "submit");
$contact_form = new XoopsThemeForm(_CT_CONTACTFORM, "contactform", "index.php");
$contact_form->addElement($name_text, true);
$contact_form->addElement($email_text, true);
$contact_form->addElement($url_text);
$contact_form->addElement($icq_text);
$contact_form->addElement($company_text);
$contact_form->addElement($location_text);
$contact_form->addElement($comment_textarea, true);
$contact_form->addElement($submit_button);
?>
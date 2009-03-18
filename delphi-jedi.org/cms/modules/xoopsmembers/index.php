<?php
// $Id: index.php,v 1.12 2003/07/08 12:38:10 okazu Exp $
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
include "../../mainfile.php";

$op = "form";
if ( isset($HTTP_POST_VARS['op']) && $HTTP_POST_VARS['op'] == "submit" ) {
	$op = "submit";
}

if ( $op == "form" ) {
	$xoopsOption['template_main'] = 'xoopsmembers_searchform.html';
	include XOOPS_ROOT_PATH."/header.php";
	$member_handler =& xoops_gethandler('member');
	$total = $member_handler->getUserCount(new Criteria('level', 0, '>'));
	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$uname_text = new XoopsFormText("", "user_uname", 30, 60);
	$uname_match = new XoopsFormSelectMatchOption("", "user_uname_match");
	$uname_tray = new XoopsFormElementTray(_MM_UNAME, "&nbsp;");
	$uname_tray->addElement($uname_match);
	$uname_tray->addElement($uname_text);
	$name_text = new XoopsFormText("", "user_name", 30, 60);
	$name_match = new XoopsFormSelectMatchOption("", "user_name_match");
	$name_tray = new XoopsFormElementTray(_MM_REALNAME, "&nbsp;");
	$name_tray->addElement($name_match);
	$name_tray->addElement($name_text);
	$email_text = new XoopsFormText("", "user_email", 30, 60);
	$email_match = new XoopsFormSelectMatchOption("", "user_email_match");
	$email_tray = new XoopsFormElementTray(_MM_EMAIL, "&nbsp;");
	$email_tray->addElement($email_match);
	$email_tray->addElement($email_text);
	$url_text = new XoopsFormText(_MM_URLC, "user_url", 30, 100);
	//$theme_select = new XoopsFormSelectTheme(_MM_THEME, "user_theme");
	//$timezone_select = new XoopsFormSelectTimezone(_MM_TIMEZONE, "user_timezone_offset");
	$icq_text = new XoopsFormText("", "user_icq", 30, 100);
	$icq_match = new XoopsFormSelectMatchOption("", "user_icq_match");
	$icq_tray = new XoopsFormElementTray(_MM_ICQ, "&nbsp;");
	$icq_tray->addElement($icq_match);
	$icq_tray->addElement($icq_text);
	$aim_text = new XoopsFormText("", "user_aim", 30, 100);
	$aim_match = new XoopsFormSelectMatchOption("", "user_aim_match");
	$aim_tray = new XoopsFormElementTray(_MM_AIM, "&nbsp;");
	$aim_tray->addElement($aim_match);
	$aim_tray->addElement($aim_text);
	$yim_text = new XoopsFormText("", "user_yim", 30, 100);
	$yim_match = new XoopsFormSelectMatchOption("", "user_yim_match");
	$yim_tray = new XoopsFormElementTray(_MM_YIM, "&nbsp;");
	$yim_tray->addElement($yim_match);
	$yim_tray->addElement($yim_text);
	$msnm_text = new XoopsFormText("", "user_msnm", 30, 100);
	$msnm_match = new XoopsFormSelectMatchOption("", "user_msnm_match");
	$msnm_tray = new XoopsFormElementTray(_MM_MSNM, "&nbsp;");
	$msnm_tray->addElement($msnm_match);
	$msnm_tray->addElement($msnm_text);
	$location_text = new XoopsFormText(_MM_LOCATION, "user_from", 30, 100);
	$occupation_text = new XoopsFormText(_MM_OCCUPATION, "user_occ", 30, 100);
	$interest_text = new XoopsFormText(_MM_INTEREST, "user_intrest", 30, 100);

	//$bio_text = new XoopsFormText(_MM_EXTRAINFO, "user_bio", 30, 100);
	$lastlog_more = new XoopsFormText(_MM_LASTLOGMORE, "user_lastlog_more", 10, 5);
	$lastlog_less = new XoopsFormText(_MM_LASTLOGLESS, "user_lastlog_less", 10, 5);
	$reg_more = new XoopsFormText(_MM_REGMORE, "user_reg_more", 10, 5);
	$reg_less = new XoopsFormText(_MM_REGLESS, "user_reg_less", 10, 5);
	$posts_more = new XoopsFormText(_MM_POSTSMORE, "user_posts_more", 10, 5);
	$posts_less = new XoopsFormText(_MM_POSTSLESS, "user_posts_less", 10, 5);
	$sort_select = new XoopsFormSelect(_MM_SORT, "user_sort");
	$sort_select->addOptionArray(array("uname"=>_MM_UNAME,"email"=>_MM_EMAIL,"last_login"=>_MM_LASTLOGIN,"user_regdate"=>_MM_REGDATE,"posts"=>_MM_POSTS));
	$order_select = new XoopsFormSelect(_MM_ORDER, "user_order");
	$order_select->addOptionArray(array("ASC"=>_MM_ASC,"DESC"=>_MM_DESC));
	$limit_text = new XoopsFormText(_MM_LIMIT, "limit", 6, 2);
	$op_hidden = new XoopsFormHidden("op", "submit");
	$submit_button = new XoopsFormButton("", "user_submit", _SUBMIT, "submit");

	$form = new XoopsThemeForm("", "searchform", "index.php");
	$form->addElement($uname_tray);
	$form->addElement($name_tray);
	$form->addElement($email_tray);
	//$form->addElement($theme_select);
	//$form->addElement($timezone_select);
	$form->addElement($icq_tray);
	$form->addElement($aim_tray);
	$form->addElement($yim_tray);
	$form->addElement($msnm_tray);
	$form->addElement($url_text);
	$form->addElement($location_text);
	$form->addElement($occupation_text);
	$form->addElement($interest_text);
	//$form->addElement($bio_text);
	$form->addElement($lastlog_more);
	$form->addElement($lastlog_less);
	$form->addElement($reg_more);
	$form->addElement($reg_less);
	$form->addElement($posts_more);
	$form->addElement($posts_less);
	$form->addElement($sort_select);
	$form->addElement($order_select);
	$form->addElement($limit_text);
	$form->addElement($op_hidden);
	$form->addElement($submit_button);
	$form->assign($xoopsTpl);
	$xoopsTpl->assign('lang_search', _MM_SEARCH);
	$xoopsTpl->assign('lang_totalusers', sprintf(_MM_TOTALUSERS, '<span style="color:#ff0000;">'.$total.'</span>'));
}

if ( $op == "submit" ) {
	$xoopsOption['template_main'] = 'xoopsmembers_searchresults.html';
	include XOOPS_ROOT_PATH."/header.php";
	$iamadmin = false;
	if ( $xoopsUser && $xoopsUser->isAdmin() ) {
		$iamadmin = true;
	}
	$myts =& MyTextSanitizer::getInstance();
	$criteria = new CriteriaCompo();
	if ( !empty($HTTP_POST_VARS['user_uname']) ) {
		$match = (!empty($HTTP_POST_VARS['user_uname_match'])) ? intval($HTTP_POST_VARS['user_uname_match']) : XOOPS_MATCH_START;
		switch ( $match ) {
		case XOOPS_MATCH_START:
			$criteria->add(new Criteria('uname', $myts->addSlashes(trim($HTTP_POST_VARS['user_uname'])).'%', 'LIKE'));
			break;
		case XOOPS_MATCH_END:
			$criteria->add(new Criteria('uname', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_uname'])), 'LIKE'));
			break;
		case XOOPS_MATCH_EQUAL:
			$criteria->add(new Criteria('uname', $myts->addSlashes(trim($HTTP_POST_VARS['user_uname']))));
			break;
		case XOOPS_MATCH_CONTAIN:
			$criteria->add(new Criteria('uname', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_uname'])).'%', 'LIKE'));
			break;
		}
	}
	if ( !empty($HTTP_POST_VARS['user_name']) ) {
		$match = (!empty($HTTP_POST_VARS['user_name_match'])) ? intval($HTTP_POST_VARS['user_name_match']) : XOOPS_MATCH_START;
		switch ($match) {
		case XOOPS_MATCH_START:
			$criteria->add(new Criteria('name', $myts->addSlashes(trim($HTTP_POST_VARS['user_name'])).'%', 'LIKE'));
			break;
		case XOOPS_MATCH_END:
			$criteria->add(new Criteria('name', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_name'])).'%', 'LIKE'));
			break;
		case XOOPS_MATCH_EQUAL:
			$criteria->add(new Criteria('name', $myts->addSlashes(trim($HTTP_POST_VARS['user_name']))));
			break;
		case XOOPS_MATCH_CONTAIN:
			$criteria->add(new Criteria('name', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_name'])).'%', 'LIKE'));
			break;
		}
	}
	if ( !empty($HTTP_POST_VARS['user_email']) ) {
		$match = (!empty($HTTP_POST_VARS['user_email_match'])) ? intval($HTTP_POST_VARS['user_email_match']) : XOOPS_MATCH_START;
		switch ($match) {
		case XOOPS_MATCH_START:
			$criteria->add(new Criteria('email', $myts->addSlashes(trim($HTTP_POST_VARS['user_email'])).'%', 'LIKE'));
			break;
		case XOOPS_MATCH_END:
			$criteria->add(new Criteria('email', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_email'])), 'LIKE'));
			break;
		case XOOPS_MATCH_EQUAL:
			$criteria->add(new Criteria('email', $myts->addSlashes(trim($HTTP_POST_VARS['user_email']))));
			break;
		case XOOPS_MATCH_CONTAIN:
			$criteria->add(new Criteria('email', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_email'])).'%', 'LIKE'));
			break;
		}
		if ( !$iamadmin ) {
			$criteria->add(new Criteria('user_viewemail', 1));
		}
	}
	if ( !empty($HTTP_POST_VARS['user_url']) ) {
		$url = formatURL(trim($HTTP_POST_VARS['user_url']));
		$criteria->add(new Criteria('url', $myts->addSlashes($url).'%', 'LIKE'));
	}
	if ( !empty($HTTP_POST_VARS['user_icq']) ) {
		$match = (!empty($HTTP_POST_VARS['user_icq_match'])) ? intval($HTTP_POST_VARS['user_icq_match']) : XOOPS_MATCH_START;
		switch ($match) {
		case XOOPS_MATCH_START:
			$criteria->add(new Criteria('user_icq', $myts->addSlashes(trim($HTTP_POST_VARS['user_icq'])).'%', 'LIKE'));
			break;
		case XOOPS_MATCH_END:
			$criteria->add(new Criteria('user_icq', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_icq'])), 'LIKE'));
			break;
		case XOOPS_MATCH_EQUAL:
			$criteria->add(new Criteria('user_icq', $myts->addSlashes(trim($HTTP_POST_VARS['user_icq']))));
			break;
		case XOOPS_MATCH_CONTAIN:
			$criteria->add(new Criteria('user_icq', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_icq'])).'%', 'LIKE'));
			break;
		}
	}
	if ( !empty($HTTP_POST_VARS['user_aim']) ) {
		$match = (!empty($HTTP_POST_VARS['user_aim_match'])) ? intval($HTTP_POST_VARS['user_aim_match']) : XOOPS_MATCH_START;
		switch ($match) {
		case XOOPS_MATCH_START:
			$criteria->add(new Criteria('user_aim', $myts->addSlashes(trim($HTTP_POST_VARS['user_aim'])).'%', 'LIKE'));
			break;
		case XOOPS_MATCH_END:
			$criteria->add(new Criteria('user_aim', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_aim'])), 'LIKE'));
			break;
		case XOOPS_MATCH_EQUAL:
			$criteria->add(new Criteria('user_aim', $myts->addSlashes(trim($HTTP_POST_VARS['user_aim']))));
			break;
		case XOOPS_MATCH_CONTAIN:
			$criteria->add(new Criteria('user_aim', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_aim'])).'%', 'LIKE'));
			break;
		}
	}
	if ( !empty($HTTP_POST_VARS['user_yim']) ) {
		$match = (!empty($HTTP_POST_VARS['user_yim_match'])) ? intval($HTTP_POST_VARS['user_yim_match']) : XOOPS_MATCH_START;
		switch ($match) {
		case XOOPS_MATCH_START:
			$criteria->add(new Criteria('user_yim', $myts->addSlashes(trim($HTTP_POST_VARS['user_yim'])).'%', 'LIKE'));
			break;
		case XOOPS_MATCH_END:
			$criteria->add(new Criteria('user_yim', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_yim'])), 'LIKE'));
			break;
		case XOOPS_MATCH_EQUAL:
			$criteria->add(new Criteria('user_yim', $myts->addSlashes(trim($HTTP_POST_VARS['user_yim']))));
			break;
		case XOOPS_MATCH_CONTAIN:
			$criteria->add(new Criteria('user_yim', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_yim'])).'%', 'LIKE'));
			break;
		}
	}
	if ( !empty($HTTP_POST_VARS['user_msnm']) ) {
		$match = (!empty($HTTP_POST_VARS['user_msnm_match'])) ? intval($HTTP_POST_VARS['user_msnm_match']) : XOOPS_MATCH_START;
		switch ($match) {
		case XOOPS_MATCH_START:
			$criteria->add(new Criteria('user_msnm', $myts->addSlashes(trim($HTTP_POST_VARS['user_msnm'])).'%', 'LIKE'));
			break;
		case XOOPS_MATCH_END:
			$criteria->add(new Criteria('user_msnm', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_msnm'])), 'LIKE'));
			break;
		case XOOPS_MATCH_EQUAL:
			$criteria->add(new Criteria('user_msnm', $myts->addSlashes(trim($HTTP_POST_VARS['user_msnm']))));
			break;
		case XOOPS_MATCH_CONTAIN:
			$criteria->add(new Criteria('user_msnm', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_msnm'])).'%', 'LIKE'));
			break;
		}
	}
	if ( !empty($HTTP_POST_VARS['user_from']) ) {
		$criteria->add(new Criteria('user_from', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_from'])).'%', 'LIKE'));
	}
	if ( !empty($HTTP_POST_VARS['user_intrest']) ) {
		$criteria->add(new Criteria('user_intrest', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_intrest'])).'%', 'LIKE'));
	}
	if ( !empty($HTTP_POST_VARS['user_occ']) ) {
		$criteria->add(new Criteria('user_occ', '%'.$myts->addSlashes(trim($HTTP_POST_VARS['user_occ'])).'%', 'LIKE'));
	}

	if ( !empty($HTTP_POST_VARS['user_lastlog_more']) && is_numeric($HTTP_POST_VARS['user_lastlog_more']) ) {
		$f_user_lastlog_more = intval(trim($HTTP_POST_VARS['user_lastlog_more']));
		$time = time() - (60 * 60 * 24 * $f_user_lastlog_more);
		if ( $time > 0 ) {
			$criteria->add(new Criteria('last_login', $time, '<'));
		}
	}
	if ( !empty($HTTP_POST_VARS['user_lastlog_less']) && is_numeric($HTTP_POST_VARS['user_lastlog_less']) ) {
		$f_user_lastlog_less = intval(trim($HTTP_POST_VARS['user_lastlog_less']));
		$time = time() - (60 * 60 * 24 * $f_user_lastlog_less);
		if ( $time > 0 ) {
			$criteria->add(new Criteria('last_login', $time, '>'));
		}
	}
	if ( !empty($HTTP_POST_VARS['user_reg_more']) && is_numeric($HTTP_POST_VARS['user_reg_more']) ) {
		$f_user_reg_more = intval(trim($HTTP_POST_VARS['user_reg_more']));
		$time = time() - (60 * 60 * 24 * $f_user_reg_more);
		if ( $time > 0 ) {
			$criteria->add(new Criteria('user_regdate', $time, '<'));
		}
	}
	if ( !empty($HTTP_POST_VARS['user_reg_less']) && is_numeric($HTTP_POST_VARS['user_reg_less']) ) {
		$f_user_reg_less = intval($HTTP_POST_VARS['user_reg_less']);
		$time = time() - (60 * 60 * 24 * $f_user_reg_less);
		if ( $time > 0 ) {
			$criteria->add(new Criteria('user_regdate', $time, '>'));
		}
	}
	if ( !empty($HTTP_POST_VARS['user_posts_more']) && is_numeric($HTTP_POST_VARS['user_posts_more']) ) {
		$criteria->add(new Criteria('posts', intval($HTTP_POST_VARS['user_posts_more']), '>'));
	}
	if ( !empty($HTTP_POST_VARS['user_posts_less']) && is_numeric($HTTP_POST_VARS['user_posts_less']) ) {
		$criteria->add(new Criteria('posts', intval($HTTP_POST_VARS['user_posts_less']), '<'));
	}
	$criteria->add(new Criteria('level', 0, '>'));
	$validsort = array("uname", "email", "last_login", "user_regdate", "posts");
	$sort = (!in_array($HTTP_POST_VARS['user_sort'], $validsort)) ? "uname" : $HTTP_POST_VARS['user_sort'];
	$order = "ASC";
	if ( isset($HTTP_POST_VARS['user_order']) && $HTTP_POST_VARS['user_order'] == "DESC") {
		$order = "DESC";
	}
	$limit = (!empty($HTTP_POST_VARS['limit'])) ? intval($HTTP_POST_VARS['limit']) : 20;
	if ( $limit == 0 || $limit > 50 ) {
		$limit = 50;
	}
	$start = (!empty($HTTP_POST_VARS['start'])) ? intval($HTTP_POST_VARS['start']) : 0;
	$member_handler =& xoops_gethandler('member');
	$total = $member_handler->getUserCount($criteria);
	$xoopsTpl->assign('lang_search', _MM_SEARCH);
	$xoopsTpl->assign('lang_results', _MM_RESULTS);
	$xoopsTpl->assign('total_found', $total);
	if ( $total == 0 ) {
		$xoopsTpl->assign('lang_nonefound', _MM_NOFOUND);
	} elseif ( $start < $total ) {
		$xoopsTpl->assign('lang_username', _MM_UNAME);
		$xoopsTpl->assign('lang_realname', _MM_REALNAME);
		$xoopsTpl->assign('lang_avatar', _MM_AVATAR);
		$xoopsTpl->assign('lang_email', _MM_EMAIL);
		$xoopsTpl->assign('lang_privmsg', _MM_PM);
		$xoopsTpl->assign('lang_regdate', _MM_REGDATE);
		$xoopsTpl->assign('lang_lastlogin', _MM_LASTLOGIN);
		$xoopsTpl->assign('lang_posts', _MM_POSTS);
		$xoopsTpl->assign('lang_url', _MM_URL);

		if ( $iamadmin ) {
			$xoopsTpl->assign('is_admin', true);
		}
		$criteria->setSort($sort);
		$criteria->setOrder($order);
		$criteria->setStart($start);
		$criteria->setLimit($limit);
		$foundusers =& $member_handler->getUsers($criteria, true);
		foreach (array_keys($foundusers) as $j) {
			$userdata['avatar'] = $foundusers[$j]->getVar("user_avatar") ? "<img src='".XOOPS_UPLOAD_URL."/".$foundusers[$j]->getVar("user_avatar")."' alt='' />" : "&nbsp;";
			$userdata['realname'] = $foundusers[$j]->getVar("name") ? $foundusers[$j]->getVar("name") : "&nbsp;";
			$userdata['name'] = $foundusers[$j]->getVar("uname");
			$userdata['id'] = $foundusers[$j]->getVar("uid");
			if ( $foundusers[$j]->getVar("user_viewemail") == 1 || $iamadmin ) {
				$userdata['email'] = "<a href='mailto:".$foundusers[$j]->getVar("email")."'><img src='".XOOPS_URL."/images/icons/email.gif' border='0' alt='".sprintf(_SENDEMAILTO,$foundusers[$j]->getVar("uname", "E"))."' /></a>";
			} else {
				$userdata['email'] = "&nbsp;";
			}
			if ( $xoopsUser ) {
				$userdata['pmlink'] = "<a href='javascript:openWithSelfMain(\"".XOOPS_URL."/pmlite.php?send2=1&amp;to_userid=".$foundusers[$j]->getVar("uid")."\",\"pmlite\",450,370);'><img src='".XOOPS_URL."/images/icons/pm.gif' border='0' alt='".sprintf(_SENDPMTO,$foundusers[$j]->getVar("uname", "E"))."' /></a>";
			} else {
				$userdata['pmlink'] = "&nbsp;";
			}
			if ( $foundusers[$j]->getVar("url","E") != "" ) {
				$userdata['website'] =  "<a href='".$foundusers[$j]->getVar("url","E")."' target='_blank'><img src='".XOOPS_URL."/images/icons/www.gif' border='0' alt='"._VISITWEBSITE."' /></a>";
			} else {
				$userdata['website'] =  "&nbsp;";
			}
			$userdata['registerdate'] = formatTimeStamp($foundusers[$j]->getVar("user_regdate"),"s");
			if ( $foundusers[$j]->getVar("last_login") != 0 ) {
				$userdata['lastlogin'] =  formatTimeStamp($foundusers[$j]->getVar("last_login"),"m");
			} else {
				$userdata['lastlogin'] =  "&nbsp;";
			}
			$userdata['posts'] = $foundusers[$j]->getVar("posts");
			if ( $iamadmin ) {
				$userdata['adminlink'] = "<a href='".XOOPS_URL."/modules/system/admin.php?fct=users&amp;uid=".$foundusers[$j]->getVar("uid")."&amp;op=modifyUser'>"._EDIT."</a> | <a href='".XOOPS_URL."/modules/system/admin.php?fct=users&amp;op=delUser&amp;uid=".$foundusers[$j]->getVar("uid")."'>"._DELETE."</a>";
			}
			$xoopsTpl->append('users', $userdata);
		}
		$totalpages = ceil($total / $limit);
		if ( $totalpages > 1 ) {
			$hiddenform = "<form name='findnext' action='index.php' method='post'>";
			foreach ( $HTTP_POST_VARS as $k => $v ) {
				$hiddenform .= "<input type='hidden' name='".$myts->oopsHtmlSpecialChars($k)."' value='".$myts->makeTboxData4PreviewInForm($v)."' />\n";
			}
			if (!isset($HTTP_POST_VARS['limit'])) {
				$hiddenform .= "<input type='hidden' name='limit' value='".$limit."' />\n";
			}
			if (!isset($HTTP_POST_VARS['start'])) {
				$hiddenform .= "<input type='hidden' name='start' value='".$start."' />\n";
			}
			$prev = $start - $limit;
			if ( $start - $limit >= 0 ) {
				$hiddenform .= "<a href='#0' onclick='javascript:document.findnext.start.value=".$prev.";document.findnext.submit();'>"._MM_PREVIOUS."</a>&nbsp;\n";
        	}
			$counter = 1;
			$currentpage = ($start+$limit) / $limit;
			while ( $counter <= $totalpages ) {
				if ( $counter == $currentpage ) {
					$hiddenform .= "<b>".$counter."</b> ";
				} elseif ( ($counter > $currentpage-4 && $counter < $currentpage+4) || $counter == 1 || $counter == $totalpages ) {
					if ( $counter == $totalpages && $currentpage < $totalpages-4 ) {
						$hiddenform .= "... ";
					}
					$hiddenform .= "<a href='#".$counter."' onclick='javascript:document.findnext.start.value=".($counter-1)*$limit.";document.findnext.submit();'>".$counter."</a> ";
					if ( $counter == 1 && $currentpage > 5 ) {
						$hiddenform .= "... ";
					}
				}
				$counter++;
			}
			$next = $start+$limit;
			if ( $total > $next ) {
				$hiddenform .= "&nbsp;<a href='#".$total."' onclick='javascript:document.findnext.start.value=".$next.";document.findnext.submit();'>"._MM_NEXT."</a>\n";
			}
			$hiddenform .= "</form>";
			$xoopsTpl->assign('pagenav', $hiddenform);
			$xoopsTpl->assign('lang_numfound', sprintf(_MM_USERSFOUND, $total));
		}
	}
}

include_once XOOPS_ROOT_PATH."/footer.php";
?>
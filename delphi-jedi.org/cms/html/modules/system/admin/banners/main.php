<?php
// $Id: main.php,v 1.9 2003/03/30 06:11:46 okazu Exp $
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
}
include_once XOOPS_ROOT_PATH."/modules/system/admin/banners/banners.php";
include_once XOOPS_ROOT_PATH."/class/module.textsanitizer.php";

// bug-compatibility ;-)
extract($HTTP_GET_VARS);
extract($HTTP_POST_VARS);

$op = !isset($op) ? 'BannersAdmin' : $op;
switch ( $op ) {
case "BannersAdmin":
	BannersAdmin();
	break;
case "BannersAdd":
	BannersAdd(@$name, $cid, $imageurl, $clickurl, $imptotal, intval(@$htmlbanner), $htmlcode);
	break;
case "BannerAddClient":
	BannerAddClient($name, $contact, $email, $login, $passwd, $extrainfo);
	break;
case "BannerFinishDelete":
	BannerFinishDelete($bid);
	break;
case "BannerDelete":
	BannerDelete($bid, $ok);
	break;
case "BannerEdit":
	BannerEdit($bid);
	break;
case "BannerChange":
	BannerChange($bid, $cid, $imptotal, $impadded, $imageurl, $clickurl, $htmlbanner, $htmlcode);
	break;
case "BannerClientDelete":
	BannerClientDelete($cid, @$ok);
	break;
case "BannerClientEdit":
	BannerClientEdit($cid);
	break;
case "BannerClientChange":
	BannerClientChange($cid, $name, $contact, $email, $extrainfo, $login, $passwd);
	break;
default:
	BannersAdmin();
	break;
}
?>
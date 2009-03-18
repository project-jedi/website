<?php
// $Id: index.php,v 1.7 2003/03/17 05:40:40 okazu Exp $
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
// ------------------------------------------------------------------------- //
// Author: Raul Recio (AKA UNFOR)                                            //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

include "header.php";
$xoopsOption['template_main'] = 'xoopspartners_index.html';
include XOOPS_ROOT_PATH."/header.php";
$part = new PartnerSystem();
if (! isset($start)){
	$start=0;
}

if ( !$start or $start == 0 and $xoopsModuleConfig['modlimit'] != 0) {
	$init = 0;
} elseif ( $start != 0 and $xoopsModuleConfig['modlimit'] != 0) {
	$init = $start;
}
$admin = 0;
if ($xoopsUser) {
	$xoopsTpl->assign("partner_join" ,"<a href='join.php'><b>"._MD_JOIN."</b></a>");
	if ( $xoopsUser->isAdmin() ) {
		$admin = 1;
	}
}
$query = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("partners")." WHERE status=1");
list($numrows) = $xoopsDB->fetchrow($query);
if( $xoopsModuleConfig['modlimit'] != 0 ) {
	$partners = $part->getAllPartners("status = 1",true,$xoopsModuleConfig['modorder'],$xoopsModuleConfig['modorderd'],$xoopsModuleConfig['modlimit'],$init);
}else{
	$partners = $part->getAllPartners("status = 1",true,$xoopsModuleConfig['modorder'],$xoopsModuleConfig['modorderd']);
}
foreach ( $partners as $part_obj ) {
	$array_partners[] = array(
			"id"                  =>  $part_obj->getVar("id"),
			"hits"                =>  $part_obj->getVar("hits"),
			"url"                 =>  $part_obj->getVar("url"),
			"image"               =>  $part_obj->getVar("image"),
			"title"               =>  $part_obj->getVar("title"),
			"description"         =>  $part_obj->getVar("description")
			);
}
$partner_count = count($array_partners);
for ( $i = 0; $i < $partner_count; $i++ ) {
	$ImagePartner = "<a href='vpartner.php?id=".$array_partners[$i]["id"]."' target='_blank'>";
	if ( !empty($array_partners[$i]["image"]) && ($xoopsModuleConfig['modshow'] == 1 || $xoopsModuleConfig['modshow'] == 3) ) {
		$ImagePartner .= "<img src='".$array_partners[$i]["image"]."' alt='".$array_partners[$i]["url"]."' width='102' height='47' border='0' />";
	}
	if ( $xoopsModuleConfig['modshow'] == 3 ) {
		$ImagePartner .= "<br />";
	}
	if ( empty($array_partners[$i]["image"]) || $xoopsModuleConfig['modshow'] == 2 || $xoopsModuleConfig['modshow'] == 3 ) {
		$ImagePartner .= $array_partners[$i]["title"];
	}
	$ImagePartner .= "</a>";
	$partner[$i]['id']           = $array_partners[$i]['id'];
	$partner[$i]['hits']         = $array_partners[$i]['hits'];
	$partner[$i]['url']          = $array_partners[$i]['url'];
	$partner[$i]['image']        = $ImagePartner;
	$partner[$i]['title']        = $array_partners[$i]['title'];
	$partner[$i]['description']  = $array_partners[$i]['description'];
	if ( $admin == 1 ) {
		$partner[$i]['admin_option']  = "<br />[<a href='admin/index.php?op=editPartner&amp;id=".$array_partners[$i]["id"]."'>"._MD_EDIT."</a>] [<a href='admin/index.php?op=delPartner&id=".$array_partners[$i]["id"]."'>"._MD_DELETE."</a>]";
	}
	$xoopsTpl->append("partners", $partner[$i]);
}
if ( $xoopsModuleConfig['modlimit'] != 0 ) {
	$nav = new XoopsPageNav($numrows,$xoopsModuleConfig['modlimit'],$start);
	$pagenav = $nav->renderImageNav();
}
$xoopsTpl->assign(array(
		"lang_partner"         => _MD_PARTNER,
		"lang_desc"            => _MD_DESCRIPTION,
		"lang_hits"            => _MD_HITS,
		"lang_no_partners"     => _MD_NOPART,
		"lang_main_partner"    => _MD_PARTNERS,
		"sitename"             => $xoopsConfig['sitename'],
		"pagenav"              => $pagenav
		));
include_once XOOPS_ROOT_PATH.'/footer.php';
?>
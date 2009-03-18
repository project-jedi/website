<?php
// $Id: partners.php,v 1.6 2003/03/28 04:04:51 w4z004 Exp $
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

function b_xoopsPartners_show($options)
{
	global $xoopsDB;
	$myts =& MyTextSanitizer::getInstance();
	$block = array();
	$arrayIds = array();
	if ( !empty($options[2]) ) {
		$arrayIds = xoopspartners_random($options[3]);
	} else {
		$arrayIds = xoopspartners_random($options[3],false,$options[5],$options[6]);
	}
	foreach ( $arrayIds as $id ) {
		$result = $xoopsDB->query("SELECT id, url, image, title FROM ".$xoopsDB->prefix("partners")." WHERE id=$id");
		list($id, $url, $image, $title) = $xoopsDB->fetchrow($result);
		$url   = $myts->makeTboxData4Show($url);
		$origtitle = $title;
		$title = $myts->makeTboxData4Show($title);
		$image = $myts->makeTboxData4Show($image);
			if ( strlen($origtitle) > 19 ) {$title = $myts->makeTboxData4Show(substr($origtitle, 0,	19))."..";
		}
		$partners['id'] = $id;
		$partners['url'] = $url;
		if ( !empty($image) && ($options[4] == 1 || $options[4] == 3) ) {
			$partners['image'] = $image;
		}
		if ( empty($image) || $options[4] == 2 || $options[4] == 3 ) {
			$partners['title'] = $title;
		} else {
			$partners['title'] = '';
		}
		$block['partners'][] = $partners;
	}
	if ($options[0] == 1) {
		$block['insertBr'] = true;
	}
	if( $options[1] == 1 ){
		$block['fadeImage'] = 'style="filter:alpha(opacity=20);" onmouseover="nereidFade(this,100,30,5)" onmouseout="nereidFade(this,50,30,5)"';
	}
	return $block;
}

function xoopspartners_random($NumberPartners,$random=true,$orden="",$desc="")
{
	global $xoopsDB;
	$PartnersId = array();
	$ArrayReturn = array();
	if ( $random ) {
		$result = $xoopsDB->query("SELECT id FROM " .$xoopsDB->prefix("partners"). " WHERE status = 1");
		$numrows = $xoopsDB->getRowsNum($result);
	} else {
		$result = $xoopsDB->query("SELECT id FROM " .$xoopsDB->prefix("partners"). " Where status = 1 ORDER BY ".$orden." ".$desc,$NumberPartners);
	}
	while ( $ret = $xoopsDB->fetchArray($result) ) {
		$PartnersId[]= $ret['id'];
	}
	if (($numrows <= $NumberPartners) or (!$random) ) {
		return $PartnersId;
		exit();
	}
	$NumberTotal = 0;
	$TotalPartner = count($PartnersId) - 1;
	while ($NumberPartners > $NumberTotal) {
		$RandomPart = mt_rand (0, $TotalPartner);
		if  ( !in_array($PartnersId[$RandomPart],$ArrayReturn) ) {
			$ArrayReturn[] = $PartnersId[$RandomPart];
			$NumberTotal++;
		}
	}
	return $ArrayReturn;
}

function b_xoopsPartners_edit($options)
{
	$form  = "<table border='0'>";
	$form .= "<tr><td>"._MB_PARTNERS_PSPACE."</td><td>";
	$chk   = "";
	if ($options[0] == 0) {
		$chk = " checked='checked'";
	}
	$form .= "<input type='radio' name='options[0]' value='0'".$chk." />"._NO."";
	$chk   = "";
	if ($options[0] == 1) {
		$chk = " checked='checked'";
	}
	$form .= "<input type='radio' name='options[0]' value='1'".$chk." />"._YES."</td></tr>";
	$form .= "<tr><td>"._MB_FADE."</td><td>";
	$chk   = "";
	if ( $options[1] == 0 ) {
		$chk = " checked='checked'";
	}
	$form .= "<input type='radio' name='options[1]' value='0'".$chk." />"._NO."";
	$chk   = "";
	if ( $options[1] == 1 ) {
		$chk = " checked='checked'";
	}
	$form .= "<input type='radio' name='options[1]' value='1'".$chk." />"._YES."</td></tr>";
	$form .= "<tr><td>"._MB_BRAND."</td><td>";
	$chk   = "";
	if ( $options[2] == 0 ) {
		$chk = " checked='checked'";
	}
	$form .= "<input type='radio' name='options[2]' value='0'".$chk." />"._NO."";
	$chk   = "";
	if ($options[2] == 1) {
		$chk = " checked='checked'";
	}
	$form .= "<input type='radio' name='options[2]' value='1'".$chk." />"._YES."</td></tr>";
	$form .= "<tr><td>"._MB_BLIMIT."</td><td>";
	$form .= "<input type='text' name='options[3]' size='16' value='".$options[3]."' /></td></tr>";
	$form .= "<tr><td>"._MB_BSHOW."</td><td>";
	$form .= "<select size='1' name='options[4]'>";
	$sel = "";
	if ( $options[4] == 1 ) {
		$sel = " selected='selected'";
	}
	$form .= "<option value='1' ".$sel.">"._MB_IMAGES."</option>";
	$sel = "";
	if ( $options[4] == 2 ) {
		$sel = " selected='selected'";
	}
	$form .= "<option value='2' ".$sel.">"._MB_TEXT."</option>";
	$sel = "";
	if ( $options[4] == 3 ) {
		$sel = " selected='selected'";
	}
	$form .= "<option value='3' ".$sel.">"._MB_BOTH."</option>";
	$form .= "</select></td></tr>";
	$form .= "<tr><td>"._MB_BORDER."</td><td>";
	$form .= "<select size='1' name='options[5]'>";
	$sel = "";
	if ( $options[5] == "id" ) {
		$sel = " selected='selected'";
	}
	$form .= "<option value='id' ".$sel.">"._MB_ID."</option>";
	$sel = "";
	if ( $options[5] == "hits" ) {
		$sel = " selected='selected'";
	}
	$form .= "<option value='hits' ".$sel.">"._MB_HITS."</option>";
	$sel = "";
	if ( $options[5] == "title" ) {
		$sel = " selected='selected'";
	}
	$form .= "<option value='title' ".$sel.">"._MB_TITLE."</option>";
	if ( $options[5] == "weight" ) {
		$sel = " selected='selected'";
	}
	$form .= "<option value='weight' ".$sel.">"._MB_WEIGHT."</option>";
	$form .= "</select> ";
	$form .= "<select size='1' name='options[6]'>";
	$sel = "";
	if ( $options[6] == "ASC" ) {
		$sel = " selected='selected'";
	}
	$form .= "<option value='ASC' ".$sel.">"._MB_ASC."</option>";
	$sel = "";
	if ( $options[6] == "DESC" ) {
		$sel = " selected='selected'";
	}
	$form .= "<option value='DESC' ".$sel.">"._MB_DESC."</option>";
	$form .= "</select></td></tr>";
	$form .= "</table>";
	return $form;
}
?>
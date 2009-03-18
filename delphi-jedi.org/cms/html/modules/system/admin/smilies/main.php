<?php
// $Id: main.php,v 1.4 2003/02/12 11:38:41 okazu Exp $
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
include_once XOOPS_ROOT_PATH."/modules/system/admin/smilies/smilies.php";
$op ='SmilesAdmin';
$ok = 0;
foreach ($HTTP_POST_VARS as $k => $v) {
	${$k} = $v;
}

if (isset($HTTP_GET_VARS['op']) && ($HTTP_GET_VARS['op'] == 'SmilesEdit' || $HTTP_GET_VARS['op'] == 'SmilesDel')) {
	$op = $HTTP_GET_VARS['op'];
	$id = isset($HTTP_GET_VARS['id']) ? intval($HTTP_GET_VARS['id']) : 0;
}

switch($op) {
case "update":
	$count = count($smile_id);
	for ($i = 0; $i < $count; $i++) {
		$smile_display[$i] = empty($smile_display[$i]) ? 0 : 1;
		if ($old_display[$i] != $smile_display[$i]) {
			$xoopsDB->query('UPDATE '.$xoopsDB->prefix('smiles').' SET display='.$smile_display[$i].' WHERE id ='.intval($smile_id[$i]));
		}
	}
	redirect_header('admin.php?fct=smilies',2,_AM_DBUPDATED);
	break;
case "SmilesAdd":
	SmilesAdd($smile_code, $smile_url, $smile_desc, $smile_display);
	break;
case "SmilesEdit":
	SmilesEdit($id);
	break;
case "SmilesSave":
	SmilesSave($id, $smile_code, $smile_url, $smile_desc, $smile_display, $old_smile);
	break;
case "SmilesDel":
	SmilesDel($id, $ok);
	break;
case "SmilesAdmin":
default:
	SmilesAdmin();
	break;
}
?>
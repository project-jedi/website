<?php
// $Id: vpartner.php,v 1.5 2003/02/12 11:39:00 okazu Exp $
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
$part = new PartnerSystem();
$id = $HTTP_GET_VARS['id'];
if ( empty($id) || !is_numeric($id) ) {
	redirect_header("index.php", 1, _XP_NOPART);
	exit();
}
$partners = $part->load($id);
if ( $part->getVar("url") ) {
	if ( !isset($HTTP_COOKIE_VARS['partners'][$id]) ) {
		setcookie("partners[$id]", $id, $xoopsModuleConfig['cookietime']);
		$part->setHits($id);
	}
	echo "<html><head><meta http-equiv='Refresh' content='0; URL=".$part->getVar("url")."'></head><body></body></html>";
} else {
	redirect_header("index.php", 1, _XP_NOPART);
}
?>
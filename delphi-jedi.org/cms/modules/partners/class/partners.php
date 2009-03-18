<?php
// $Id: partners.php,v 1.6 2003/02/12 11:39:00 okazu Exp $
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
// Author: Raul Recio (AKA UNFOR)                                            //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";

class PartnerSystem extends XoopsObject
{
	var $db;

    // constructor
	function PartnerSystem($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("weight", XOBJ_DTYPE_INT, null, false, 10);
		$this->initVar("hits", XOBJ_DTYPE_INT, null, true, 10);
		$this->initVar("url", XOBJ_DTYPE_TXTBOX, null, true);
		$this->initVar("image", XOBJ_DTYPE_TXTBOX, null, true);
		$this->initVar("title", XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("description", XOBJ_DTYPE_TXTBOX, null, true);
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
				$this->load(intval($id));
			}
		}
	}

	function load($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix("partners")." WHERE id=$id and status=1";
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
	}

	function getAllPartners($criteria=array(), $asobject=false, $sort="hits", $order="DESC", $limit=0, $start=0)
	{
		$db =& Database::getInstance();
		$ret = array();
		$where_query = "";
		if ( is_array($criteria) && count($criteria) > 0 ) {
			$where_query = " WHERE";
			foreach ( $criteria as $c ) {
				$where_query .= " $c AND";
			}
			$where_query = substr($where_query, 0, -4);
		} elseif ( !is_array($criteria) ) {
			$where_query = " WHERE ".$criteria;
		}
		if ( !$asobject ) {
			$sql = "SELECT id FROM ".$db->prefix("partners")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("partners")."".$where_query." ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new PartnerSystem($myrow);
			}
		}
		return $ret;
	}

	function setHits($id)
	{
		$db =& Database::getInstance();
		$db->queryF("UPDATE ".$db->prefix("partners")." SET hits=hits+1 WHERE id=$id");
	}
}
?>
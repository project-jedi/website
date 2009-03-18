<?php
// $Id: xoopspolloption.php,v 1.8 2003/02/25 14:28:17 okazu Exp $
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
include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";

class XoopsPollOption extends XoopsObject
{
	var $db;

	// constructor
	function XoopsPollOption($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("option_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("poll_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("option_text", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("option_count", XOBJ_DTYPE_INT, 0, false);
		$this->initVar("option_color", XOBJ_DTYPE_OTHER, null, false);
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
				$this->load(intval($id));
			}
		}
	}

	// public
	function store()
	{
		if ( !$this->cleanVars() ) {
			return false;
		}
		foreach ( $this->cleanVars as $k=>$v ) {
			$$k = $v;
		}
		if ( empty($option_id) ) {
			$option_id = $this->db->genId($this->db->prefix("xoopspoll_option")."_option_id_seq");
			$sql = "INSERT INTO ".$this->db->prefix("xoopspoll_option")." (option_id, poll_id, option_text, option_count, option_color) VALUES ($option_id, $poll_id, ".$this->db->quoteString($option_text).", $option_count, ".$this->db->quoteString($option_color).")";
		} else {
			$sql = "UPDATE ".$this->db->prefix("xoopspoll_option")." SET option_text=".$this->db->quoteString($option_text).", option_count=$option_count, option_color=".$this->db->quoteString($option_color)."  WHERE option_id=".$option_id."";
		}
		//echo $sql;
		if ( !$result = $this->db->query($sql) ) {
			$this->setErrors("Could not store data in the database.");
			return false;
		}
		if ( empty($option_id) ) {
			return $this->db->getInsertId();
		}
		return $option_id;
	}

	// private
	function load($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix("xoopspoll_option")." WHERE option_id=".$id."";
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
	}

	// public
	function delete()
	{
		$sql = sprintf("DELETE FROM %s WHERE option_id = %u", $this->db->prefix("xoopspoll_option"), $this->getVar("option_id"));
        	if ( !$this->db->query($sql) ) {
			return false;
		}
		return true;
	}

	// public
	function updateCount()
	{
		$votes = XoopsPollLog::getTotalVotesByOptionId($this->getVar("option_id"));
		$sql ="UPDATE ".$this->db->prefix("xoopspoll_option")." SET option_count=$votes WHERE option_id=".$this->getVar("option_id")."";
		$this->db->query($sql);
	}

	// public static
	function &getAllByPollId($poll_id)
	{
		$db =& Database::getInstance();
		$ret = array();
		$sql = "SELECT * FROM ".$db->prefix("xoopspoll_option")." WHERE poll_id=".intval($poll_id)." ORDER BY option_id";
		$result = $db->query($sql);
		while ( $myrow = $db->fetchArray($result) ) {
			$ret[] = new XoopsPollOption($myrow);
		}
		//echo $sql;
		return $ret;
	}

	// public static
	function deleteByPollId($poll_id)
	{
		$db =& Database::getInstance();
		$sql = sprintf("DELETE FROM %s WHERE poll_id = %u", $db->prefix("xoopspoll_option"), intval($poll_id));
        	if ( !$db->query($sql) ) {
			return false;
		}
		return true;
	}

	// public static
	function resetCountByPollId($poll_id)
	{
		$db =& Database::getInstance();
		$sql = "UPDATE ".$db->prefix("xoopspoll_option")." SET option_count=0 WHERE poll_id=".intval($poll_id);
        	if ( !$db->query($sql) ) {
			return false;
		}
		return true;
	}
}
?>
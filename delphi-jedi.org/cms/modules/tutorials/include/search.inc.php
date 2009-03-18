<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
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


function tutorial_search($queryarray, $andor, $limit, $offset, $userid){
	global $db;
	$sql = "SELECT tid,tname,tdesc,tcont,tauthor,date,submitter FROM ".$db->prefix("tutorials")." WHERE status >= 1";
	if ( $userid != 0 ) {
		$sql .= " AND submitter=".$userid." ";
	}
	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((tname LIKE '%$queryarray[0]%' OR tdesc LIKE '%$queryarray[0]%' OR tcont LIKE '%$queryarray[0]%' OR tauthor LIKE '%$queryarray[0]%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(tname LIKE '%$queryarray[$i]%' OR tdesc LIKE '%$queryarray[$i]%' OR tcont LIKE '%$queryarray[$i]%' OR tauthor LIKE '%$queryarray[$i]%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY date DESC";
	$result = $db->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
 	while($myrow = $db->fetch_array($result)){
		$ret[$i]['image'] = "images/bulb.gif";
		$ret[$i]['link'] = "index.php?op=viewtutorial&tid=".$myrow['tid'];
		$ret[$i]['title'] = $myrow['tname'];
		$ret[$i]['time'] = $myrow['date'];
		// datefunction is not perfect, but when gone, then there are mor silly dates
		$ret[$i]['uid'] = $myrow['submitter'];
		$i++;
	}
	return $ret;
}

?>
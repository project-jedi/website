<?php
// ------------------------------------------------------------------------- //
//						 C-JAY Content							             //
//				         Version:  V2				  	  					 //
//						  Module for										 //
//				XOOPS - PHP Content Management System				 		 //
//					<http://www.xoops.org/>						  			 //
// ------------------------------------------------------------------------- //
// Author: Christoph forlon Brecht          								 //
// Purpose: Module to wrap html or php-content into nice Xoops design.	     //
// email: master@c-jay.net										  			 //
// Site: http://c-jay.net													 //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify	 //
//  it under the terms of the GNU General Public License as published by	 //
//  the Free Software Foundation; either version 2 of the License, or 	     //
//  (at your option) any later version. 							         //
//															                 //
//  This program is distributed in the hope that it will be useful,		     //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of		     //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the		     //
//  GNU General Public License for more details.						     //
// ------------------------------------------------------------------------- //

function cjaycontent_search($queryarray, $andor, $limit, $offset, $userid)
{
	global $xoopsDB;
	$ret = array();
	if ( $userid != 0 ) {
		return $ret;
	}
	$sql = "SELECT id, title, content, date, submitter FROM ".$xoopsDB->prefix("cjaycontent")." WHERE hide=0 AND NOT (title LIKE '..%') ";
	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	$count = count($queryarray);
	if ( $count > 0 && is_array($queryarray) ) {
		$sql .= "AND ((title LIKE '%$queryarray[0]%' OR content LIKE '%$queryarray[0]%')";
		for ( $i = 1; $i < $count; $i++ ) {
			$sql .= " $andor ";
			$sql .= "(title LIKE '%$queryarray[$i]%' OR content LIKE '%$queryarray[$i]%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY id DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$i = 0;
 	while ( $myrow = $xoopsDB->fetchArray($result) ) {
		$ret[$i]['image'] = "fc.gif";
		$ret[$i]['link'] = "index.php?id=".$myrow['id']."";
		$ret[$i]['title'] = $myrow['title'];
		$ret[$i]['time'] = $myrow['date'];
		$ret[$i]['uid'] = $myrow['submitter'];
		$i++;
	}
	return $ret;
}
?>
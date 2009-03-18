<?php
/**
 * $Id: search.php v 1.01 20 November 2003 Catwolf Exp $
 * Module: WF-Channel
 * Version: v1.0.2
 * Release Date: 20 November 2003
 * Author: Catzwolf
 * Licence: GNU
**/

function wfchannel_search($queryarray, $andor, $limit, $offset, $userid){
	global $xoopsDB;
	
	$sql = "SELECT CID, pagetitle, created FROM ".$xoopsDB->prefix("wfschannel")." WHERE ";
	
	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " ((pagetitle LIKE '%$queryarray[0]%' OR pageheadline LIKE '%$queryarray[0]%' OR page LIKE '%$queryarray[0]%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(pagetitle LIKE '%$queryarray[$i]%' OR pageheadline  LIKE '%$queryarray[$i]%' OR page LIKE '%$queryarray[$i]%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY created DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
 	while($myrow = $xoopsDB->fetchArray($result)){
		//$ret[$i]['image'] = "images/forum.gif";
		$ret[$i]['link'] = "index.php?pagenum=".$myrow['CID']."";
		$ret[$i]['title'] = $myrow['pagetitle'];
		$ret[$i]['time'] = $myrow['created'];
		$i++;
	}
	return $ret;
}
?>
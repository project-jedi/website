<?php
/* 
* $Id: search.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

function wffaq_search($queryarray, $andor, $limit, $offset, $userid)
{
	global $xoopsDB;
	$ret = array();
	if ( $userid != 0 ) {
		return $ret;
	}
	$sql = "SELECT topicID, question, answer, uid, datesub FROM ".$xoopsDB->prefix("faqtopics")." WHERE submit = 1 ";
	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	$count = count($queryarray);
	if ( $count > 0 && is_array($queryarray) ) {
		$sql .= "AND ((question LIKE '%$queryarray[0]%' OR answer LIKE '%$queryarray[0]%')";
		for ( $i = 1; $i < $count; $i++ ) {
			$sql .= " $andor ";
			$sql .= "(question LIKE '%$queryarray[$i]%' OR answer LIKE '%$queryarray[$i]%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY topicID DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$i = 0;
 	while ( $myrow = $xoopsDB->fetchArray($result) ) {
		$ret[$i]['image'] = "images/wf.gif";
		$ret[$i]['link'] = "index.php?op=view&t=".$myrow['topicID'];
		$ret[$i]['title'] = $myrow['question'];
		$ret[$i]['time'] = $myrow['datesub'];
		$ret[$i]['uid'] = $myrow['uid'];
		$i++;
	}
	return $ret;
}
?>
<?php
// $Id: search.inc.php,v 1.6 2003/09/22 04:37:25 okazu Exp $
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

function newbb_search($queryarray, $andor, $limit, $offset, $userid){
	global $xoopsDB;
	$sql = "SELECT p.post_id,p.topic_id,p.forum_id,p.post_time,p.uid,p.subject FROM ".$xoopsDB->prefix("bb_posts")." p LEFT JOIN ".$xoopsDB->prefix("bb_posts_text")." t ON t.post_id=p.post_id LEFT JOIN ".$xoopsDB->prefix("bb_forums")." f ON f.forum_id=p.forum_id WHERE f.forum_type=0";
	if ( $userid != 0 ) {
		$sql .= " AND p.uid=".$userid." ";
	}
	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((p.subject LIKE '%$queryarray[0]%' OR t.post_text LIKE '%$queryarray[0]%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(p.subject LIKE '%$queryarray[$i]%' OR t.post_text LIKE '%$queryarray[$i]%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY p.post_time DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
 	while($myrow = $xoopsDB->fetchArray($result)){
		$ret[$i]['link'] = "viewtopic.php?topic_id=".$myrow['topic_id']."&amp;forum=".$myrow['forum_id']."#forumpost".$myrow['post_id'];
		$ret[$i]['title'] = $myrow['subject'];
		$ret[$i]['time'] = $myrow['post_time'];
		$ret[$i]['uid'] = $myrow['uid'];
		$i++;
	}
	return $ret;
}
?>
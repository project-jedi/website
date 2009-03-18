<?php
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
// Author: Tobias Liegl (AKA CHAPI)                                          //
// Site: http://www.chapi.de                                                 //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

function jedisdl_search($queryarray, $andor, $limit, $offset, $userid){
  global $xoopsDB, $xoopsConfig;

  if ( file_exists(XOOPS_ROOT_PATH."/modules/jedisdl/language/".$xoopsConfig['language']."/main.php") ) {
    include(XOOPS_ROOT_PATH."/modules/jedisdl/language/".$xoopsConfig['language']."/main.php");
  } elseif ( file_exists(XOOPS_ROOT_PATH."/modules/jedisdl/language/english/main.php") ) {
    include(XOOPS_ROOT_PATH."/modules/jedisdl/language/english/main.php");
  }
  
  $sql = "SELECT storyid, title, text FROM ".$xoopsDB->prefix(_MI_jedisdl_PREFIX)." WHERE visible='1'";

  if ( $userid != 0 ) {
		$sql .= " AND storyid='0' ";
  }

  // because count() returns 1 even if a supplied variable
  // is not an array, we must check if $querryarray is really an array
  if ( is_array($queryarray) && $count = count($queryarray) ) {
    $sql .= " AND ((text LIKE '%$queryarray[0]%' OR title LIKE '%$queryarray[0]%')";
    for($i=1;$i<$count;$i++){
      $sql .= " $andor ";
      $sql .= "(text LIKE '%$queryarray[$i]%' OR title LIKE '%$queryarray[$i]%')";
    }
    $sql .= ")";
  }
  
  $sql .= " ORDER BY storyid ASC";
  $result = $xoopsDB->query($sql,$limit,$offset);
  $ret = array();
  $i = 0;
  
  while($myrow = $xoopsDB->fetchArray($result)){
    $ret[$i]['image'] = "images/content.gif";
    $ret[$i]['link'] = "index.php?id=".$myrow['storyid'];
    $ret[$i]['title'] = $myrow['title'];
	$ret[$i]['uid'] = "0";
    $i++;
  }
  
  return $ret;
}
?>
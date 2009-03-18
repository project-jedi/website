<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                                                                           //
//                                                                           //
// ------------------------------------------------------------------------- //
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

// inc_perms_check.php

// This function returns the permissions level that a user has to a particular object.
function perms_level($obj_id) //, $group_array = array())
  {
  global $group_array, $xoopsDB, $xoopsUser;
  
  //  Obtain the entire list of permissions for the object.
  $query  = "SELECT user_id, group_id, user_perms, group_perms, everyone_perms ";
  $query .= "FROM ".$xoopsDB->prefix("dms_object_perms")." ";
  $query .= "WHERE ptr_obj_id='".$obj_id."'";
  
  $perms = mysql_query($query);
  
  $max_perm = 0;
  
  $user_id = $xoopsUser->getVar('uid');
  
  while($perms_data = mysql_fetch_array($perms))
    {
    
	if ( ($user_id == $perms_data['user_id']) && ($max_perm < $perms_data['user_perms']) )
	 $max_perm = $perms_data['user_perms'];

	$group_list = $xoopsUser->getGroups();
	do  
      {
      if ( (current($group_list) == $perms_data['group_id']) && ($max_perm < $perms_data['group_perms']) )
	   $max_perm = $perms_data['group_perms']; 
	  
      } while(next($group_list));
		 
	if ($perms_data['everyone_perms'] > $max_perm) $max_perm = $perms_data['everyone_perms'];
	}

  return $max_perm;
  }

?>

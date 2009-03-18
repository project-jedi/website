<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                  Written By:  Brian E. Reifsnyder                         //
//                        Copyright 2003                                     //
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

// inc_folder_select.php
include_once 'inc_perms_check.php';
include_once 'defines.php';

print "<SCRIPT LANGUAGE='Javascript'>\r";
print "  function check_for_dest()\r";
print "    {\r";
print "    var option_selected_flag = 0;\r";
print "    for (var i=0; i < document.frm_select_dest.rad_dest_folder_id.length; i++)\r";
print "      {\r";
print "      if(document.frm_select_dest.rad_dest_folder_id[i].checked) option_selected_flag++;\r";
print "      }\r";
print "      if(option_selected_flag > 0)\r";
print "        frm_select_dest.submit();\r";
print "      else\r";
print "        alert(\"Please select a destination folder.\");\r";
print "    }\r";
print "</SCRIPT>";  

$level = 0;
function list_folder($folder_owner)
  {
  global $active_folder, $exp_folders, $file_id, $group_query, $level, $location, $xoopsDB, $xoopsUser;
  
  $bg_color="";
  $user_id = $xoopsUser->getVar('uid');
    
  // Set up display offsets
  $index=0;
  while($index < $level)
    {
	$level_offset .= "&nbsp;&nbsp;&nbsp;";	
	$index++;
	}
  
  // If the user is an administrator, ignore the permissions entirely.
  if ($xoopsUser->isAdmin())
    {
    $query  = "SELECT * FROM ".$xoopsDB->prefix("dms_objects")." ";
    $query .= "WHERE (obj_owner='".$folder_owner."') ";
    $query .= "ORDER BY obj_type DESC, obj_name";
	}
  else
    {
    $query  = "SELECT obj_id, ".$xoopsDB->prefix("dms_objects").".ptr_obj_id, obj_type, obj_name, obj_status, obj_owner, obj_checked_out_user_id, ";
	$query .= "user_id, group_id, user_perms, group_perms, everyone_perms ";
	$query .= "FROM ".$xoopsDB->prefix("dms_object_perms")." ";
	$query .= "INNER JOIN ".$xoopsDB->prefix("dms_objects")." ON ";
	$query .= $xoopsDB->prefix("dms_object_perms").".ptr_obj_id = obj_id ";
    $query .= "WHERE (obj_owner='".$folder_owner."') ";
	$query .= " AND (";
    $query .= "    everyone_perms !='0'";
	$query .= $group_query;
	$query .= " OR user_id='".$user_id."'";
	$query .= ")";
	$query .= " AND (obj_status !='2') ";
	$query .= "GROUP BY obj_id ";
	$query .= "ORDER BY obj_type DESC, obj_name";
	}
	
//print $query;
//exit(0);
	
   
//  $result = mysql_query($query) or die(mysql_error());
  
  $result = $xoopsDB->query($query);
  $num_rows = $xoopsDB->getRowsNum($result);
  
  if ($num_rows > 0)
	{
	while($result_data = mysql_fetch_array($result))
      {
      if($xoopsUser->isAdmin())  $perm = OWNER;
	  else                       $perm = perms_level($result_data['obj_id']);
	  
	  // Set class to the active background color
      $class = "";
	    
	  // If this object is a folder, display it.
	  if($result_data['obj_type'] == FOLDER)
        {
		print "  <tr>\r";
		
		$index = 0;
		$exp_flag = 0;

		// Is folder expanded?
		while($exp_folders[$index] != -1)
		  { 
		  if ($exp_folders[$index] == $result_data['obj_id']) $exp_flag = 1;
		  $index++;
		  }
		
		// Display standard folders
		if ($result_data['obj_type']==FOLDER)
		  {
		  if (($exp_flag==1) && ($perm > BROWSE))
		    {
			print "    <td align='left' nowrap ".$class.">";
			print "<input type='radio' name='rad_dest_folder_id' value='".$result_data['obj_id']."'>";
			print $level_offset;
			print "<a href='folder_contract.php?ret_location=".$location."&folder_id=".$result_data['obj_id']."&file_id=".$file_id."'>";
			print "<img src='images/folder_open.png'></a>&nbsp;&nbsp;&nbsp;\r";
			}
          else
		    {
			if ($perm > BROWSE)
			 {
			  print "    <td align='left' nowrap ".$class.">";
			  print "<input type='radio' name='rad_dest_folder_id' value='".$result_data['obj_id']."'>";
			  print $level_offset;
			  print "<a href='folder_expand.php?ret_location=".$location."&folder_id=".$result_data['obj_id']."&file_id=".$file_id."'>";
			  print "<img src='images/folder_closed.png'></a>&nbsp;&nbsp;&nbsp;\r";
			  }
			else
			  {
			  print "    <td align='left' nowrap ".$class.">";
			  print "<input type='radio' name='rad_dest_folder_id' value='".$result_data['obj_id']."'>";
			  print $level_offset;
			  print "<img src='images/folder_closed.png'></a>&nbsp;&nbsp;&nbsp;\r";
			  }
			}
		  } 
 
		// If folder is not active, display the name and link to make it active, otherwise just display the name.
        if (($result_data['obj_id'] == $active_folder) || ($perm == BROWSE))
		  {
		  print "    ".$result_data['obj_name']."</td>\r";
		  }   
		else
		  {
		  print "    <a href='folder_expand.php?ret_location=".$location."&folder_id=".$result_data['obj_id']."&file_id=".$file_id."'>";
		  print $result_data['obj_name']."</a></td>\r";  
		  }
		
        print "    </td>\r";
		//print "    <td width='100%'></td>\r  </tr>\r";
		
		if (($exp_flag == 1) && ($perm > BROWSE))
	      {
	      $level++;
		  list_folder($result_data['obj_id']);
	      $level--;
		  }
		}

      }
	}
  }

  
// get list of groups that this user is a member of and create part of the query
// also, place these groups into an array for later use
$group_list = $xoopsUser->getGroups();
$group_array = array();
$index = 0;

$group_query = "";
do  
  {
  $group_query .= " OR group_id='".current($group_list)."'";
  $group_array[$index] = current($group_list);
  
  $index++;
  } while(next($group_list));
  
// Get list of expanded folders
$query = sprintf("SELECT * FROM %s WHERE user_id='%s'",$xoopsDB->prefix("dms_exp_folders"),$xoopsUser->getVar('uid'));
$result = $xoopsDB->query($query);

$index = 0;
while($result_data = mysql_fetch_array($result))
  {
  $exp_folders[$index]=$result_data['folder_id'];  
  $index++;
  } 
  $exp_folders[$index]=-1;

// Get active folder
$query = "SELECT folder_id from ".$xoopsDB->prefix("dms_active_folder")." WHERE user_id='".$xoopsUser->getVar('uid')."'";  
$result = mysql_query($query);
$active_folder = mysql_result($result,'folder_id');
if(!$active_folder>=1) $active_folder=0;

// Get the object type of the active folder, if applicable
/*
if ($active_folder!=0)
  {
  $query = "SELECT obj_type from ".$xoopsDB->prefix("dms_objects")." WHERE obj_id='".$active_folder."'";
  $result = mysql_query($query);
  $active_folder_type = mysql_result($result,'obj_type');
  }
else
  {
  $active_folder_type = 0;
  }
*/
          
// List all folders

print "<table>\r";
list_folder(0);
print "</table>\r";

?>

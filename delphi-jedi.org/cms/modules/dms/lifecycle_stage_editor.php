<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                  Written By:  Brian E. Reifsnyder                         //
//                        Copyright 7/22/2003                                //
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

// Main Menu
// lifecycle_manager.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';

$lifecycle_id = "";
$lifecycle_stage = "";

if($HTTP_POST_VARS["hdn_function"]) 
  {
  $function = $HTTP_POST_VARS["hdn_function"];
  $lifecycle_id = $HTTP_POST_VARS["hdn_lifecycle_id"];
  $lifecycle_stage = $HTTP_POST_VARS["hdn_lifecycle_stage"];
  }
else 
  {
  $function = $HTTP_GET_VARS["function"];
  $lifecycle_id = $HTTP_GET_VARS["lifecycle_id"];
  $lifecycle_stage = $HTTP_GET_VARS["lifecycle_stage"];
  }
 
if($lifecycle_id == "")
  {
  print "Invalid Lifecycle ID...Operation Terminated";
  exit(0);
  }  

if($lifecycle_stage == "")
  {
  print "Invalid Lifecycle Stage...Operation Terminated";
  exit(0);
  }  
  
if ($function == "CONTRACT")
  {
  $sql_query  = "DELETE FROM ".$xoopsDB->prefix("dms_exp_folders");
  $sql_query .= " WHERE user_id='".$xoopsUser->getVar('uid')."' and folder_id='".$HTTP_POST_VARS["hdn_folder_id"]."'";
  mysql_query($sql_query);
  
  // Make sure that this folder cannot be marked as active
  $sql_query  = "DELETE FROM ".$xoopsDB->prefix("dms_active_folder")." ";
  $sql_query .= "WHERE user_id='".$xoopsUser->getVar('uid')."' AND folder_id='".$HTTP_POST_VARS["hdn_folder_id"]."'";
  mysql_query($sql_query);
  } 
  
if ($function == "EXPAND")
  {
  //Make sure that this folder is not marked as expanded in order to prevent multiple entries.
  $sql_query  = "DELETE FROM ".$xoopsDB->prefix("dms_exp_folders");
  $sql_query .= " WHERE user_id='".$xoopsUser->getVar('uid')."' and folder_id='".$HTTP_POST_VARS["hdn_folder_id"]."'";
  mysql_query($sql_query);
  
  // Make sure that this folder, or any other folder, is not marked as active.
  $sql_query = "DELETE FROM ".$xoopsDB->prefix("dms_active_folder")." WHERE user_id='".$xoopsUser->getVar('uid')."'";
  mysql_query($sql_query);
        
  // Set the folder as expanded
  $sql_query  = "INSERT INTO ".$xoopsDB->prefix("dms_exp_folders")." ";
  $sql_query .= "(user_id,folder_id) VALUES ('".$xoopsUser->getVar('uid')."','".$HTTP_POST_VARS["hdn_folder_id"]."')";
  mysql_query($sql_query);
    
  // Set the folder as active
  $sql_query  = "INSERT INTO ".$xoopsDB->prefix("dms_active_folder")." ";
  $sql_query .= "(user_id,folder_id) VALUES ('".$xoopsUser->getVar('uid')."','".$HTTP_POST_VARS["hdn_folder_id"]."')";
  mysql_query($sql_query);
  }   
  
if ($function == "SELECT")
  {
  $sql_query  = "UPDATE ".$xoopsDB->prefix("dms_lifecycle_stages")." ";
  $sql_query .= "SET ";
  $sql_query .= "new_obj_location='".$HTTP_POST_VARS["hdn_folder_id"]."', ";
  $sql_query .= "lifecycle_stage='".$HTTP_POST_VARS["txt_lifecycle_stage"]."' ";
  $sql_query .= "WHERE row_id='".$HTTP_POST_VARS["hdn_row_id"]."'";
  mysql_query($sql_query);
  
  $lifecycle_stage = $HTTP_POST_VARS["txt_lifecycle_stage"];
  }
  
  include XOOPS_ROOT_PATH.'/header.php';

  print "<SCRIPT LANGUAGE='Javascript'>\r";
  
  print "function contract_folder(folder_id)\r";
  print "  {\r";
  print "  frm_lifecycle_stage_editor.hdn_function.value='CONTRACT';\r";
  print "  frm_lifecycle_stage_editor.hdn_folder_id.value=folder_id;\r";
  print "  frm_lifecycle_stage_editor.submit();\r";  
  print "  }\r";
  print "function expand_folder(folder_id)\r";
  print "  {\r";
  print "  frm_lifecycle_stage_editor.hdn_function.value='EXPAND';\r";
  print "  frm_lifecycle_stage_editor.hdn_folder_id.value=folder_id;\r";
  print "  frm_lifecycle_stage_editor.submit();\r";  
  print "  }\r";
  print "function select_dest_folder()\r";
  print "  {\r";
  print "  var value = 0;\r";
  print "  for (var i=0; i < frm_lifecycle_stage_editor.rad_folder_id.length; i++)\r";
  print "    {\r";
  print "    if(frm_lifecycle_stage_editor.rad_folder_id[i].checked)\r";
  print "     value = frm_lifecycle_stage_editor.rad_folder_id[i].value;\r";
  print "    }\r";
  print "    if(value > 0)\r";
  print "      {\r";
  print "      frm_lifecycle_stage_editor.hdn_function.value='SELECT';\r";
  print "      frm_lifecycle_stage_editor.hdn_folder_id.value=value;\r";
  print "      frm_lifecycle_stage_editor.submit();\r";  
  print "      }\r";
  print "    else\r";
  print "      alert(\"Please select a destination folder.\");\r";
  print "  }\r";
  
  print "</SCRIPT>\r";  
  
  print "<form method='post' name='frm_lifecycle_stage_editor' action='lifecycle_stage_editor.php'>\r";
  print "<table width='100%'>\r";
  
//  display_dms_header();
  
  print "  <tr>\r";
  
  // Gadget Column  
  print "    <td valign='top' width='260'>\r";
  print "      <table width='100%' cellspacing='0' cellpadding='0'>\r";
  print "        <tr>\r";
  print "          <td align='center' class='cNarrowHeader'>\r";
  print "            <table width='100%'>\r";
  print "              <tr>\r";
  print "                <td align='center' class='cNarrowHeader'>\r";
  print "                  Options\r";
  print "                </td>\r";
  print "              </tr>\r";
  print "            </table>\r";
  print "          </td>\r";
  print "        </tr>\r";
  
  print "        <tr>\r";
  print "          <td align='center' class='cNarrowContentSection'>\r";
  print "            <table width='100%' cellspacing='4' cellpadding='0'>\r";
  print "              <tr>\r";
  print "                <td align='center' class='cContentSection'>\r";
  print "                  <input type='button' name='btn_exit' value='Exit' onclick='location=\"lifecycle_editor.php?lifecycle_id=".$lifecycle_id."\";'>\r";
  print "                </td>\r";
  
  print "              </tr>\r";
  print "            </table>\r";
  print "          </td>\r";
  print "        </tr>\r";
  
  print "      </table>\r";
  print "    </td>\r";
  
  // Content
  print "    <td valign='top'>\r";
  print "      <table>\r";
  print "        <tr>\r";
  print "          <td colspan='1' class='cHeader'>\r";
  print "            <center><b><font size='2'>Lifecycle Stage Editor</font></b></center>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";
  
  print "      <BR>\r";

  print "      <table>\r";
  print "        <tr>\r";
  print "          <td colspan='1' class='cSubHeader'>\r";
  print "            <b>Stage Number</b>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";

  print "      <BR>\r";
  
  print "      <table>\r";
  print "        <tr>\r";
  print "          <td colspan='1' class='cContentSection'>\r";
  print "            <input type='text' name='txt_lifecycle_stage' value='".$lifecycle_stage."'>";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";
 
  
  print "      <BR>\r";
  
  print "      <table>\r";
  print "        <tr>\r";
  print "          <td colspan='1' class='cSubHeader'>\r";
  print "            <b>Destination Folder</b>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";

    
  $query  = "SELECT name ";
  $query .= "FROM ".$xoopsDB->prefix('dms_lifecycles')." ";
  $query .= "WHERE lifecycle_id='".$lifecycle_id."' ";
  $lifecycle_result = mysql_fetch_array($xoopsDB->query($query));
  
  $query  = "SELECT row_id, lifecycle_stage, new_obj_location ";
  $query .= "FROM ".$xoopsDB->prefix('dms_lifecycle_stages')." ";
  $query .= "WHERE lifecycle_id='".$lifecycle_id."' ";
  $query .= "AND lifecycle_stage='".$lifecycle_stage."'";
  $lifecycle_stage_result = mysql_fetch_array($xoopsDB->query($query));
  
//////////////////////   
// Folder Display Code
//////////////////////
  
include_once 'inc_perms_check.php';
include_once 'defines.php';

$level = 0;
function list_folder($folder_owner)
  {
  global $active_folder, $exp_folders, $file_id, $group_query, $level, $lifecycle_stage, $lifecycle_stage_result, $location, $xoopsDB, $xoopsUser;
   
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
		if ($result_data['obj_id'] == $lifecycle_stage_result['new_obj_location']) $radio_btn_string = " checked";
		else $radio_btn_string = "";

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
			print "<input type='radio' name='rad_folder_id' value='".$result_data['obj_id']."'".$radio_btn_string.">";
			print $level_offset;
			print "<a href='javascript:contract_folder(".$result_data['obj_id'].");'>";  // contract folder
			print "<img src='images/folder_open.png'></a>&nbsp;&nbsp;&nbsp;\r";
			}
          else
		    {
			if ($perm > BROWSE)
			 {
			  print "    <td align='left' nowrap ".$class.">";
			  print "<input type='radio' name='rad_folder_id' value='".$result_data['obj_id']."'".$radio_btn_string.">";
			  print $level_offset;
			  print "<a href='javascript:expand_folder(".$result_data['obj_id'].");'>"; // expand folder
			  print "<img src='images/folder_closed.png'></a>&nbsp;&nbsp;&nbsp;\r";
			  }
			else
			  {
			  print "    <td align='left' nowrap ".$class.">";
			  print "<input type='radio' name='rad_folder_id' value='".$result_data['obj_id']."'".$radio_btn_string.">";
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
		  print "    <a href='javascript:expand_folder(".$result_data['obj_id'].");'>"; // expand folder
		  print $result_data['obj_name']."</a></td>\r";  
		  }
		
        print "    </td>\r";
		
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
        
// List all folders

print "<table>\r";
list_folder(0);
print "</table>\r";



//////////////////////////  
// End Folder Display Code
//////////////////////////
    
  print "      <BR>\r";
  
  print "      <input type='button' name='btn_dest_update' value='Update' class='cContentSection' onclick='select_dest_folder();'>\r";
  
  print "    </td>\r";
  
  print "  </tr>\r";
  print "</table>\r";
  
  print "<input type='hidden' name='hdn_function' value=''>\r";
  print "<input type='hidden' name='hdn_row_id' value='".$lifecycle_stage_result["row_id"]."'>\r";
  print "<input type='hidden' name='hdn_folder_id' value=''>\r";
  print "<input type='hidden' name='hdn_lifecycle_id' value='".$lifecycle_id."'>\r";
  print "<input type='hidden' name='hdn_lifecycle_stage' value='".$lifecycle_stage."'>\r";
    
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';

  
?>

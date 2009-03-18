<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                  Written By:  Brian E. Reifsnyder                         //
//                        Copyright 5/13/2003                                //
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
// index.php

include_once 'defines.php';
include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/header.php';

include 'inc_perms_check.php';
include_once 'inc_dms_functions.php';

define('SEPARATOR_LIMIT',5);

$separator_counter = 0;
$level = 0;
function list_folder($folder_owner)
  {
  global $active_folder, $exp_folders, $group_query, $level, $separator_counter, $xoopsDB, $xoopsUser;
  
  $bg_color="";
  $bg_image="images/line.png";
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
    $query  = "SELECT obj_id, ".$xoopsDB->prefix("dms_objects").".ptr_obj_id, obj_type, obj_name, ";
	$query .= "obj_status, obj_owner, obj_checked_out_user_id, lifecycle_id, ";
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
	
	//print $query;
	//exit(0);
	}
   
//  $result = mysql_query($query) or die(mysql_error());
  
  $result = $xoopsDB->query($query);
  $num_rows = $xoopsDB->getRowsNum($result);
  
  if ($num_rows > 0)
    {
    while($result_data = mysql_fetch_array($result))
      {
      $separator_counter ++;
	  if ($separator_counter > SEPARATOR_LIMIT)
	    {
		print "  <tr>\r";
		print "  <td height='1' background='".$bg_image."' nowrap></td>\r";
		print "  <td background='".$bg_image."' nowrap></td>\r";
		print "  <td background='".$bg_image."' nowrap></td>\r";
		print "  <td background='".$bg_image."' nowrap></td>\r";
		print "  <td background='".$bg_image."' nowrap></td>\r";
		print "  <td background='".$bg_image."' nowrap></td>\r";
		print "  </tr>\r";
		
		$separator_counter = 1;
		}      
	  
	  if($xoopsUser->isAdmin())  $perm = OWNER;
	  else                       $perm = perms_level($result_data['obj_id']);
	  
	  // If folder is active, then set it to the active background color
	  if  ( $active_folder != 0 
	   && (($result_data['obj_id'] == $active_folder) 
	   || ($folder_owner == $active_folder) ) 
	   && ($perm > BROWSE) )
	   $class = "class='cSubHeader'";
      else $class = "";
	    
	  printf("  <tr>\r");
	  // If this object is a folder, examine it....otherwise, display the file and move on.
	  if(($result_data['obj_type'] == FOLDER) || ($result_data['obj_type'] == INBOXEMPTY) || ($result_data['obj_type'] == INBOXFULL))
        {
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
			if($result_data['obj_status'] == DELETED)
			  {
			  printf("    <td %s align='left'>%s<a href='folder_contract.php?folder_id=%s'><img src='images/folder_del_open.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset,$result_data['obj_id']);
			  }
			else
			  {
			  printf("    <td %s align='left'>%s<a href='folder_contract.php?folder_id=%s'><img src='images/folder_open.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset,$result_data['obj_id']);
			  }
			}
          else
		    {
			if ($perm > BROWSE)
			  {
			  if ($result_data['obj_status'] == DELETED) 
			    {
			    printf("    <td %s align='left'>%s<a href='folder_expand.php?folder_id=%s'><img src='images/folder_del_closed.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset,$result_data['obj_id']);
				}
			  else
			    {
			    printf("    <td %s align='left'>%s<a href='folder_expand.php?folder_id=%s'><img src='images/folder_closed.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset,$result_data['obj_id']);
			    }
			  }
			else
 			  if ($result_data['obj_status'] == DELETED)
			    {
			 printf("    <td %s align='left'>%s<img src='images/folder_del_closed.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset);
				}
			  else
			    {
			 printf("    <td %s align='left'>%s<img src='images/folder_closed.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset);
			    }
			}
		  } 
		
		// Display empty inbox folders
		if ($result_data['obj_type']==INBOXEMPTY)
		  {
		  if (($exp_flag==1) && ($perm > BROWSE)) 
		    {
			printf("    <td %s align='left'>%s<a href='folder_contract.php?folder_id=%s'><img src='images/inbox_empty.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset,$result_data['obj_id']);
			}
          else
		    {
			if ($perm > BROWSE)
			 printf("    <td %s align='left'>%s<a href='folder_expand.php?folder_id=%s'><img src='images/inbox_empty.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset,$result_data['obj_id']);
            else
			 printf("    <td %s align='left'>%s<img src='images/inbox_empty.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset);
			}
		  }
		  
		// Display full inbox folders
		if ($result_data['obj_type']==INBOXFULL)
		  {
		  if (($exp_flag==1) && ($perm > BROWSE))
		    {
		    printf("    <td %s align='left'>%s<a href='folder_contract.php?folder_id=%s'><img src='images/inbox_full.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset,$result_data['obj_id']);
			}
          else
		    {
			if ($perm > BROWSE)
			 printf("    <td %s align='left'>%s<a href='folder_expand.php?folder_id=%s'><img src='images/inbox_full.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset,$result_data['obj_id']);
			else
			 printf("    <td %s align='left'>%s<img src='images/inbox_full.png'></a>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset);
			}
		  }  
 
		// If folder is not active, display the name and link to make it active, otherwise just display the name.
        if (($result_data['obj_id'] == $active_folder) || ($perm == BROWSE))
		  {
		  printf("    %s</td>\r",$result_data['obj_name']);
		  }   
		else
		  {
		  printf("    <a href='folder_expand.php?folder_id=%d'>%s</a></td>\r",$result_data['obj_id'],$result_data['obj_name']);  
		  }
		
		printf("    <td></td>\r");
		  
        printf("    <td></td>\r");  // Checkin/Checkout (not used for a folder)
		
		if ($result_data['obj_status'] == DELETED)
		  {
		  printf("    <td align=center><a href='folder_restore.php?folder_id=%s'>Restore</a></td>\r",$result_data['obj_id']);  // Restore
		  }
		else  
		  {
		  printf("    <td></td>\r");          
		  }
		  
		if ( ($perm >= EDIT) && ( ($xoopsUser->isAdmin()) || ($result_data['obj_owner']!=0) ) )
		 printf("    <td align='center'><a href='folder_options.php?obj_id=%s'>Options</a></td>\r",$result_data['obj_id']);  // Options        
        else print "    <td></td>\r";
		
		printf("    <td></td>\r");            // Status
        printf("    <td></td>\r  </tr>\r");
		
		if (($exp_flag == 1) && ($perm > BROWSE))
	      {
	      $level++;
		  list_folder($result_data['obj_id']);
	      $level--;
		  }
		}
      else
	    {
		// Object is a file or link
		
		// Check if the object is a file or link
		if($result_data['obj_type'] != DOCLINK)
		  {
		  // Object is a file
		  printf("    <td %s align='left'>%s<img src='images/file_text.png'>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset);
		  		  
		  if ($perm > BROWSE)
		   printf("<a href='#' onclick='javascript:void(window.open(\"file_view.php?file_id=%s\"))'>%s</a></td>\r",$result_data['obj_id'],$result_data['obj_name']);
		  else
		   print $result_data['obj_name'];
		  
		  printf("    <td></td>\r");
		
		  // Checkin/Checkout
 		  switch ($result_data['obj_status'])
		    {
		    case NORMAL:
		      if ($perm >= EDIT)
			   printf("    <td align=center><a href='file_checkout.php?file_id=%s'>Edit</a></td>\r",$result_data['obj_id']);  
		      else print "    <td></td>\r";
			  break;
		    case CHECKEDOUT:
		      if (($user_id == $result_data['obj_checked_out_user_id']) && ($perm >= EDIT))
			   printf("    <td align=center><a href='file_checkin.php?file_id=%s'>Check-in</a></td>\r",$result_data['obj_id']);  
		      else
			   printf("    <td></td>\r");
			  break;
		    default:
		      printf("    <td></td>\r");       
		    }
		
		  switch ($result_data['obj_status'])
		    {
		    case NORMAL:
			  if (($perm >= READONLY) && ($result_data['lifecycle_id'] == 0))      
			   printf("    <td align=center><a href='file_route.php?file_id=%s'>Route</a></td>\r",$result_data['obj_id']);  // Route
			  else 
			    {
			    if ($result_data['lifecycle_id'] !=0)
				 print "    <td align=center><a href='lifecycle_promote.php?file_id=".$result_data['obj_id']."'>Promote</a></td>\r";  // Promote
				else print "    <td></td>\r";
			    }
			  break;  
		    case DELETED:
		      printf("    <td align=center><a href='file_restore.php?file_id=%s'>Restore</a></td>\r",$result_data['obj_id']);  // Restore
		      break;
		    default:
		      printf("    <td></td>\r");
		    }
		  
		  if ($perm >= EDIT)
		   printf("    <td align=center><a href='file_options.php?obj_id=%s'>Options</a></td>\r",$result_data['obj_id']);  // Properties
		  else
		   printf("    <td></td>\r");
		   
		  switch ($result_data['obj_status'])
		    {
		    case NORMAL:
		      printf("    <td align=center><img src='images/file_unlocked.png'></td>\r");  
		      break;
		    case CHECKEDOUT:
		      printf("    <td align=center><img src='images/file_locked.png'></td>\r");  
		      break;
		    default:
		      printf("    <td></td>\r");
		    }
		  }
		else
		  {
		  // Object is a link
		  $link_query  = "SELECT obj_id,obj_name,obj_status,current_version_row_id,obj_checked_out_user_id ";
		  $link_query .= "from ".$xoopsDB->prefix('dms_objects')." ";
		  $link_query .= "WHERE obj_id='".$result_data['ptr_obj_id']."'";  
		  $link_result = mysql_fetch_object(mysql_query($link_query));

		  if($xoopsUser->isAdmin())  $perm = OWNER;
		  else                       $perm = perms_level($result_data['ptr_obj_id']);
		    
		  printf("    <td %s align='left'>%s<img src='images/file_link.png'>&nbsp;&nbsp;&nbsp;\r",$class,$level_offset);
		  
		  if ($perm > BROWSE)
		    printf("<a href='#' onclick='javascript:void(window.open(\"file_view.php?file_id=%s\"))'>%s</a></td>\r",$link_result->obj_id,$link_result->obj_name);
          else
		    print $link_result->obj_name."</td>\r";
		  
		  printf("    <td></td>\r");
		
		  // Checkin/Checkout
 		  switch ($link_result->obj_status)
		    {
		    case NORMAL:
		      if ($perm >= EDIT)
			   printf("    <td align=center><a href='file_checkout.php?file_id=%s'>Edit</a></td>\r",$link_result->obj_id);  
		      else print "    <td></td>\r";
			  break;
		    case CHECKEDOUT:
		      if (($user_id == $link_result->obj_checked_out_user_id) && ($perm >= EDIT))
			   printf("    <td align=center><a href='file_checkin.php?file_id=%s'>Check-in</a></td>\r",$link_result->obj_id);  
		      else
			   printf("    <td></td>\r");
			  break;
		    default:
		      printf("    <td></td>\r");       
		    }
		  printf("    <td></td>\r");
		  printf("    <td align=center><a href='link_options.php?obj_id=%s'>Options</a></td>\r",$result_data['obj_id']);  // Properties

		  switch ($link_result->obj_status)
		    {
		    case NORMAL:
		      printf("    <td align=center><img src='images/file_unlocked.png'></td>\r");  
		      break;
		    case CHECKEDOUT:
		      printf("    <td align=center><img src='images/file_locked.png'></td>\r");  
		      break;
		    default:
		      printf("    <td></td>\r");
		    }
		  }
		printf("    <td></td>\r  </tr>\r");
		}
      }
	}
  else
    {
    // If folder is active, then set it to the active background color
	if (( $active_folder != 0) && ($folder_owner == $active_folder))
	 $class="class='cSubHeader'";
    else $class = "";

	printf("  <tr><td %s><center>Empty</center></td><td colspan='5'></td></tr>\r",$class);
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
      
print "<table width='100%'>\r";
display_dms_header(3);
print "  <tr><td colspan='3'><BR></td></tr>\r";
print "  <tr>\r";
print "    <td width='70%'><BR></td>\r";

if( ( ($xoopsUser->isAdmin()) || ($active_folder_type == FOLDER) ) 
 && ( ($xoopsUser->isAdmin()) || ($active_folder!=0) ) )
  {
  print "    <td width='15%' align='left' valign='top'>";
  print "                                              <a href='file_new.php'>Create Document</a><BR>";
  print "                                              <a href='file_import.php'>Import Document</a><BR>";
  print "                                              <a href='folder_new.php'>Create Folder</a>";
  print "                                              </td>\r";
  }
else
  {
  print "    <td width='15%' align='left'><BR></td>";
  }

print "    <td width='15%' align='left' valign='top'><a href='search.php'>Search</a>";

if ($xoopsUser->isAdmin()) print "<BR><a href='lifecycle_manager.php'>Lifecycles</a>";

print "      </td></tr>\r";
print "  <tr><td colspan='3'><BR><BR></td></tr>\r";
print "</table>\r";

print "<table width='100%' border='1' class='cContentSection'>\r";
print "  <tr>\r";
print "    <td align=center width='50%' class='cSubHeader'><b>Item:</b></b></td>\r";
print "    <td width='2%' class='cSubHeader'></td>\r";
print "    <td width='10%' class='cSubHeader'><b></b></td>\r";
print "    <td width='10%' class='cSubHeader'><b></b></td>\r";
print "    <td width='10%' class='cSubHeader'><b></b></td>\r";
print "    <td width='5%' align=center class='cSubHeader'><b>Status:</b></td>\r";
print "    <td></td>\r";
print "  </tr>\r";
      
// List all folders
list_folder(0);

printf("</table>\r");

include_once XOOPS_ROOT_PATH.'/footer.php';
?>

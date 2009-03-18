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

// index.php
// Administration Page

include_once '../../../mainfile.php';
include_once (XOOPS_ROOT_PATH."/class/xoopsmodule.php");
include_once (XOOPS_ROOT_PATH."/include/cp_functions.php");
global $db, $HTTP_POST_VARS;
xoops_cp_header();
OpenTable();

print '<b>DMS Configuration:</b><BR><BR>';

if ($HTTP_POST_VARS["txt_doc_path"])
  {
  $doc_path = $HTTP_POST_VARS["txt_doc_path"];
  $query = sprintf("UPDATE %s SET data = '%s' WHERE name='doc_path'",$xoopsDB->prefix("dms_config"),$doc_path);
  $xoopsDB->query($query);

  $query = sprintf("UPDATE %s SET data = '%s' WHERE name='dms_title'",$xoopsDB->prefix("dms_config"),$HTTP_POST_VARS['txt_dms_title']);
  $xoopsDB->query($query);
    
  $query = sprintf("UPDATE %s SET data = '%s' WHERE name='max_file_sys_counter'",$xoopsDB->prefix("dms_config"),$HTTP_POST_VARS["txt_max_file_sys_counter"]);
  $xoopsDB->query($query);

  $query = sprintf("UPDATE %s SET data = '%s' WHERE name='template_root_obj_id'",$xoopsDB->prefix("dms_config"),$HTTP_POST_VARS["txt_template_root_obj_id"]);
  $xoopsDB->query($query);
      
  print 'Update Complete<BR><BR>';
  }
  
print '<form method="post" action="index.php">';

$query = 'SELECT data from '.$xoopsDB->prefix("dms_config")." WHERE name='dms_title'";
$result = $xoopsDB->query($query);

print 'DMS Page Title:  ';
printf("<input type=text name='txt_dms_title' value='%s' size='60'><BR>",mysql_result($result,'data'));

$query = 'SELECT data FROM '.$xoopsDB->prefix("dms_config")." WHERE name='doc_path'";
$result = $xoopsDB->query($query);

print 'Document storage path:  ';
printf("<input type=text name='txt_doc_path' value='%s' size='60'><BR>",mysql_result($result,'data'));

$query = 'SELECT data FROM '.$xoopsDB->prefix("dms_config")." WHERE name='max_file_sys_counter'";
$result = $xoopsDB->query($query);

print 'Document Storage Tuning:  ';
printf("<input type=text name='txt_max_file_sys_counter' value='%s'><BR>",mysql_result($result,'data'));

$query = 'SELECT data FROM '.$xoopsDB->prefix("dms_config")." WHERE name='template_root_obj_id'";
$result = $xoopsDB->query($query);

print 'Template Root Directory ID:  ';
printf("<input type=text name='txt_template_root_obj_id' value='%s'><BR>",mysql_result($result,'data'));


print '<BR><BR>';
print '<input type="submit" value="Update"></form>';

CloseTable();
xoops_cp_footer();
?>

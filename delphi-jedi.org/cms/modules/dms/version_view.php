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

// version_view.php

include '../../mainfile.php';

// Get file information
$query = 'SELECT data FROM '.$xoopsDB->prefix("dms_config")." WHERE name='doc_path'";
$config = mysql_fetch_object(mysql_query($query));

/*
$query  = "SELECT current_version_row_id from ".$xoopsDB->prefix('dms_objects')." ";
$query .= "WHERE obj_id='".$HTTP_GET_VARS["file_id"]."'";  
$first_result = mysql_fetch_object(mysql_query($query));
*/
   
$query  = "SELECT file_name,file_type,file_size,file_path from ".$xoopsDB->prefix('dms_object_versions')." ";
$query .= "WHERE row_id='".$HTTP_GET_VARS["ver_id"]."'";  
$result = mysql_fetch_object(mysql_query($query));

// Code below was modified from opendocman for use with this DMS

// send headers to browser to initiate file download
header('Content-Length: '.$result->file_size);
// Pass the mimetype so the browser can open it
header('Cache-control: private');
header('Content-Type: ' . $result->file_type);
header('Content-Disposition: inline; filename=' . $result->file_name);
// Apache is sending Last Modified header, so we'll do it, too
//        $modified=filemtime($filename);
//        header('Last-Modified: '. date('D, j M Y G:i:s T',$modified));   // something like Thu, 03 Oct 2002 18:01:08 GMT
readfile(($config->data."/".$result->file_path));
?>

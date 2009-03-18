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

// inc_det_file_path.php

include_once '../../mainfile.php';

//  Determines the destination path and file name for a new file that is to be added to the document repository.  Also
//  creates the appropriate destination directory, increments the file system counters, and stores the file system
//  counters for future use.  
function dest_path_and_file()
{  
  global $xoopsDB, $xoopsUser;
  
  // Initialize magic_number.  This number is used to create unique file names in order to guarantee that 2 file names
  // will not be identical if 2 users upload a file at the exact same time.  100000 will allow almost 100000 users to use
  // this system.  Ok, the odds of this happening are slim; but, I want the odds to be zero.
  $magic_number = 100000;  
 
  // Get the location of the document repository
  $query = "SELECT data from ".$xoopsDB->prefix("dms_config")." WHERE name='doc_path'";
  $file_sys_root = mysql_result(mysql_query($query),'data');
   
  // Get the current value of max_file_sys_counter
  $query = "SELECT data from ".$xoopsDB->prefix("dms_config")." WHERE name='max_file_sys_counter'";
  $max_file_sys_counter = (integer) mysql_result(mysql_query($query),'data');
  
  // Determine the path and filename of the new file
  $query = "SELECT * from ".$xoopsDB->prefix("dms_file_sys_counters");
  $dms_file_sys_counters = mysql_fetch_array(mysql_query($query));
  
  $file_sys_dir_1     = $dms_file_sys_counters['layer_1'];
  $file_sys_dir_2     = $dms_file_sys_counters['layer_2'];
  $file_sys_dir_3     = $dms_file_sys_counters['layer_3'];
  $file_sys_file      = $dms_file_sys_counters['file'];
  $file_sys_file_name = ($file_sys_file * $magic_number) + $xoopsUser->getVar('uid');
  
  $dir_path_1 = $file_sys_root."/".$file_sys_dir_1;
  $dir_path_2 = $file_sys_root."/".$file_sys_dir_1."/".$file_sys_dir_2;
  $dir_path_3 = $file_sys_root."/".$file_sys_dir_1."/".$file_sys_dir_2."/".$file_sys_dir_3;
    
  //$doc_path              = $file_sys_root."/".$file_sys_dir_1."/".$file_sys_dir_2."/".$file_sys_dir_3;
  $path_and_file = $file_sys_dir_1."/".$file_sys_dir_2."/".$file_sys_dir_3."/".$file_sys_file_name;
  //$dest_path_and_file    = $doc_path."/".$file_sys_file_name;
 
  //print $path_and_file;
  //exit(0);
   
  // Determine the next file system counter values and save them for future use.
  $file_sys_file++;
  if ($file_sys_file > $max_file_sys_counter) 
    {
	$file_sys_file = 1;
	$file_sys_dir_3++;
	} 
  
  if ($file_sys_dir_3 > $max_file_sys_counter)
    {
	$file_sys_dir_3 = 1;
	$file_sys_dir_2++;
	}
	
  if ($file_sys_dir_2 > $max_file_sys_counter)
    {
	$file_sys_dir_2 = 1;
	$file_sys_dir_1++;
	}
	
  $query =  "UPDATE ".$xoopsDB->prefix("dms_file_sys_counters")." SET ";
  $query .= "layer_1 = '".(integer) $file_sys_dir_1."', ";
  $query .= "layer_2 = '".(integer) $file_sys_dir_2."', ";
  $query .= "layer_3 = '".(integer) $file_sys_dir_3."', ";
  $query .= "file    = '".(integer) $file_sys_file. "' ";
  
  mysql_query($query); 

  // Ensure that the final destination directories exist...if not, then create the directory or directories.
  if (!is_dir($dir_path_1)) 
    {
	mkdir($dir_path_1,0775);
    chmod($dir_path_1,0777);
	}
  
  if (!is_dir($dir_path_2))
    {
	mkdir($dir_path_2,0775);  
    chmod($dir_path_2,0777);
    }
	
  if (!is_dir($dir_path_3)) 
    {
	mkdir($dir_path_3,0775);
    chmod($dir_path_3,0777);
	}
	
  return($path_and_file);
}  
?>

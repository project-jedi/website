<?php
// ------------------------------------------------------------------------- //
//						 C-JAY Content							             //
//				         Version:  V2				  	  					 //
//						  Module for										 //
//				XOOPS - PHP Content Management System				 		 //
//					<http://www.xoops.org/>						  			 //
// ------------------------------------------------------------------------- //
// Author: Christoph forlon Brecht          								 //
// Purpose: Module to wrap html or php-content into nice Xoops design.	     //
// email: master@c-jay.net										  			 //
// Site: http://c-jay.net													 //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify	 //
//  it under the terms of the GNU General Public License as published by	 //
//  the Free Software Foundation; either version 2 of the License, or 	     //
//  (at your option) any later version. 							         //
//															                 //
//  This program is distributed in the hope that it will be useful,		     //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of		     //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the		     //
//  GNU General Public License for more details.						     //
// ------------------------------------------------------------------------- //
//$id = $_GET['id'];
include("../../mainfile.php");
$fc_start_id =1;

if($xoopsConfig['startpage'] == "cjaycontent"){
	$xoopsOption['show_rblock'] =1;
	include(XOOPS_ROOT_PATH."/header.php");
	$id = $fc_start_id;
	fc_wrap($id);
	include (XOOPS_ROOT_PATH."/footer.php");
	}
else{
	$xoopsOption['show_rblock'] =1;
	include (XOOPS_ROOT_PATH."/header.php");
	fc_wrap($id);
	include (XOOPS_ROOT_PATH."/footer.php");
}
	

// run wrapper


//*****************************************************************************************
//*** Functions-declaration ***************************************************************
//*****************************************************************************************
function fc_wrap($id){
	global $xoopsDB, $xoopsConfig, $xoopsTheme, $xoopsOption;
	OpenTable();

	//query Database (returns an array)
	$fc_item = fc_query_db_on_id($id);

	//$id exists?
	if( isset($fc_item['adress']) && $fc_item['adress'] != "" ){
		// include content
		$fc_include = XOOPS_ROOT_PATH."/modules/cjaycontent/content/".$fc_item['adress'];
		if (file_exists($fc_include)){
			include($fc_include);
			// increment hitcounter (hits)
			$res = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix()."_cjaycontent SET hits=hits+1 WHERE id=\"".$id."\"");
		}
		else{
			echo _CC_FILENOTFOUND.$fc_include;
		}
	}
	else{
		$fc_include = XOOPS_ROOT_PATH."/modules/cjaycontent/content/DO_NOT_DELETE.php";
		if (file_exists($fc_include)){
			include($fc_include);
			// increment hitcounter (hits)
			$res = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix()."_cjaycontent SET hits=hits+1 WHERE id=\"".$id."\"");
		}
		else{
			echo _CC_FILENOTFOUND.$fc_include;
		}
	}

	CloseTable();
	

	return 1;
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function fc_query_db_on_id($id){
	global $xoopsDB;

	//query Database (returns an array)
	$result = $xoopsDB->queryF("SELECT adress FROM ".$xoopsDB->prefix()."_cjaycontent WHERE id=\"".$id."\"",1);
	return $xoopsDB->fetchArray($result);
}
?>

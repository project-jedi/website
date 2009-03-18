<?php
/* 
* $Id: admin_header.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

include("../../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/class/xoopsmodule.php");
include(XOOPS_ROOT_PATH."/include/cp_functions.php");
include XOOPS_ROOT_PATH."/modules/wffaq/include/functions.php";
include XOOPS_ROOT_PATH."/class/xoopstree.php";
include XOOPS_ROOT_PATH."/class/xoopslists.php";
include XOOPS_ROOT_PATH."/class/xoopsformloader.php";

if ( $xoopsUser ) {
	$xoopsModule = XoopsModule::getByDirname("wffaq");
	if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
		redirect_header(XOOPS_URL."/",3,_NOPERM);
		exit();
	}
} else {
	redirect_header(XOOPS_URL."/",3,_NOPERM);
	exit();
}
if ( file_exists("../language/".$xoopsConfig['language']."/admin.php") ) {
	include("../language/".$xoopsConfig['language']."/admin.php");
} else {
	include("../language/english/admin.php");
}
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
        include("../language/".$xoopsConfig['language']."/main.php");
} else {
        include("../language/english/main.php");
}
?>
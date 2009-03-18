<?php
/* 
* $Id: upgrade.php v 1.0 17 November 2003 Catwolf Exp $
* Module: WF-Channel
* Version: v1.0.2
* Release Date: 17 November 2003
* Author: Catzwolf
* Licence: GNU
*/


include("header.php");

global $xoopsDB,$xoopsConfig;
include(XOOPS_ROOT_PATH."/header.php");


function install_header(){
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<title>WFChannel Upgrade</title>
	<meta http-equiv="Content-Type" content="text/html; charset=">
	<meta name="AUTHOR" content="WFCHANNEL">
	<meta name="GENERATOR" content="WFCHANNEL---->http://wfsections.xoops2.com">
	</head>
	<body>
	<div style='text-align:center'><h4>WFChannel Update</h4>
<?php
}

function install_footer(){
?>
	</body>
	</html>
<?php

}

//echo "Welcome to the WF-Channel update script";

foreach ($HTTP_POST_VARS as $k => $v) {
	${$k} = $v;
}

foreach ($HTTP_GET_VARS as $k => $v) {
	${$k} = $v;
}

if ( !isset($action) || $action == "" ) {
	$action = "message";
}

if ( $action == "message" ) {
	install_header();
	echo "
  <table width='100%' border='0'>
  <tr>
    <td align='center'><b>"._MD_UPDATE1."</b></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>";

	echo "
	<table width='50%' border='0'><tr><td colspan='2'>"._MD_UPDATE2."<br><br><b>"._MD_UPDATE3."<b></td></tr>

	<tr><td></td><td >"._MD_UPDATE4."</td></tr>
	<tr><td></td><td><span style='color:#ff0000;font-weight:bold;'>"._MD_UPDATE5."</span></td></tr>
	</table>
	";
	echo "<p>"._MD_UPDATE6."</p>";
	echo "<form action='".$HTTP_SERVER_VARS['PHP_SELF']."' method='post'><input type='submit' value='Start Upgrade' /><input type='hidden' value='upgrade' name='action' /></form>";
	install_footer();
include_once(XOOPS_ROOT_PATH."/footer.php");	
//	exit();
}

//  THIS IS THE UPDATE DATABASE FROM HERE!!!!!!!!! DO NOT TOUCH THIS!!!!!!!!

if ( $action == "upgrade" ) {
install_header();

echo "<p>"._MD_UPDATE24."</p>\n";
$count = 0;
	$result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("wfsrefer")." ");
	if ($result) {
		$error[] = "Skipped! Creating table <b>".$xoopsDB->prefix("wfsrefer")."</b>, it already exist.";
	}else{
		$xoopsDB->queryF("CREATE TABLE ".$xoopsDB->prefix("wfsrefer")." ( 
		titlerefer varchar(255) NOT NULL default '', 
		chanrefheadline text, 
		submenuitem int(10) NOT NULL default '10', 
		mainpage int(10) NOT NULL default '1', 
		referpagelogo varchar(255) NOT NULL default '', 
		emailaddress int(10) NOT NULL default '1', 
		usersblurb int(10) NOT NULL default '0', 
		defblurb varchar(255) NOT NULL default '', 
		smiley tinyint(11) NOT NULL default '0', 
		xcodes tinyint(11) NOT NULL default '0', 
		breaks tinyint(4) NOT NULL default '0', 
		html tinyint(11) NOT NULL default '1', 
		PRIMARY KEY  (submenuitem) 
		)");

		$xoopsDB->queryF("INSERT INTO  ".$xoopsDB->prefix("wfsrefer")." set titlerefer = 'Refer a friend', chanrefheadline = 'Let a friend know about us', submenuitem = '1', mainpage ='1', referpagelogo = 'referfriend.png', emailaddress = '1', usersblurb = '1', defblurb = '', smiley = '0', xcodes = '1', breaks ='1', html= '1'");
		echo "Created New Table ".$xoopsDB->prefix("wfsrefer").".<br />";
		$count++;
	}
 	
	//$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("wfsrefer")." (titlerefer, chanrefheadline, submenuitem, mainpage, referpagelogo, emailaddress, usersblurb, defblurb, smiley, xcodes, breaks, html) VALUES (, 'Let a friend know about us', 1, 1, 'referfriend.png', 1, 1, '', 0, 1, 1, 1)");

	$time = time();
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix("wfschannel")." ADD created int(10) NOT NULL default '".$time."' AFTER submenu");
	if ($result) {
		echo "Adding created to ".$xoopsDB->prefix("wfschannel").".<br />";
		$count++;
	}
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix("wfslinktous")." ADD texthtml varchar(255) NOT NULL default '' AFTER newsfeed");
	if ($result) {
		echo "Adding texthtml to ".$xoopsDB->prefix("wfslinktous").".<br />";
		$count++;
	}
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix("wfslinktous")." ADD titlelink varchar(255) NOT NULL default 'Link to Us' AFTER texthtml");
	if ($result) {
		echo "Adding titlelink to ".$xoopsDB->prefix("wfslinktous").".<br />";
		$count++;
	}
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix("wfslinktous")." ADD newsfeedjs mediumint(10) NOT NULL default '0' AFTER titlelink");
	if ($result) {
		echo "Adding newsfeedjs to ".$xoopsDB->prefix("wfslinktous").".<br />";
		$count++;
	}
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix("wfslinktous")." ADD newstitle varchar(255) NOT NULL default '' AFTER newsfeedjs");
	if ($result) {
		echo "Adding newstitle to ".$xoopsDB->prefix("wfslinktous").".<br />";
		$count++;
	}
	
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix("wfschannel")." ADD comments tinyint(11) NOT NULL default '0' AFTER created");
	if ($result) {
		echo "Adding comments to ".$xoopsDB->prefix("wfslinktous").".<br />";
		$count++;
	}
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix("wfschannel")." ADD allowcomments tinyint(11) NOT NULL default '0' AFTER comments");
	if ($result) {
		echo "Adding allowcomments to ".$xoopsDB->prefix("wfslinktous").".<br />";
		$count++;
	}
	
	if ($count == 0) {
		echo "<div>There where No updates to the database</div>";
	} else {
		echo "<br />";
		echo ""._MD_UPDATE22."";
	}	
	echo $result;
	echo "<p><span> <a href=''>"._MD_UPDATE23."</a></span></p>\n";

include_once(XOOPS_ROOT_PATH."/footer.php");
}
?>

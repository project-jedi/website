<?php
/* 
* $Id: upgrade.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

include("../../mainfile.php");
include(XOOPS_ROOT_PATH."/header.php");

#
# # Add new Table structure for table `xoops_faqtopics`
#

Global $xoopsUser, $xoopsDB, $xoopsConfig;

$result = mysql_query("ALTER TABLE ".$xoopsDB->prefix("faqtopics")." ADD uid int(6) default '1', ADD submit int(1) NOT NULL default '0', ADD summary text NOT NULL default '' ADD datesub int(11) NOT NULL default '1033141070', ADD counter int(8) unsigned NOT NULL default '0' ");
$result = mysql_query("ALTER TABLE ".$xoopsDB->prefix("faqtopics")." DELETE keywords");

#
## End of update hopefully
#



$result = mysql_query("SELECT * FROM ".$xoopsDB->prefix("faqtopics")." WHERE topicID <>'0' ");

    mysql_query("UPDATE ".$xoopsDB->prefix("faqtopics")." SET submit = submit + 1 WHERE submit = '0'" );

OpenTable();

Echo "Updates to the database completed!";



CloseTable();
include_once(XOOPS_ROOT_PATH."/footer.php");

?>

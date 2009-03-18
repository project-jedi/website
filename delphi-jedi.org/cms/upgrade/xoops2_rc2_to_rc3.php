<?php

include '../mainfile.php';

echo '<html><head><title></title></head><body>';

if (isset($HTTP_POST_VARS['submit'])) {

	if (!$xoopsDB->queryFromFile('./xoops2_rc2_to_rc3.sql')) {
		echo 'File xoops2_rc2_to_rc3.sql not found!';
	} else {
		$xoopsDB->queryF('ALTER TABLE '.$xoopsDB->prefix('themeset').' RENAME '.$xoopsDB->prefix('tplset'));

		$xoopsDB->queryF('ALTER TABLE '.$xoopsDB->prefix('imgset_themeset_link').' RENAME '.$xoopsDB->prefix('imgset_tplset_link'));

		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('config')." VALUES (0,0,6,'mailmethod','_MD_AM_MAILERMETHOD','mail','_MD_AM_MAILERMETHODDESC','select','text',3)");
		$configid = $xoopsDB->getInsertId();

		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('configoption')." VALUES (0,'PHP mail()','mail',".$configid.")");
		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('configoption')." VALUES (0,'sendmail','sendmail',".$configid.")");
		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('configoption')." VALUES (0,'SMTP','smtp',".$configid.")");
		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('configoption')." VALUES (0,'SMTPAuth','smtpauth',".$configid.")");
		$sql = "SELECT conf_order FROM ".$xoopsDB->prefix('config')." WHERE conf_name='sslpost_name'";
		$result = $xoopsDB->queryF($sql);
		list($conforder) = $xoopsDB->fetchRow($result);
		$conforder++;
		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('config')." VALUES (0, 0, 1, 'sslloginlink', '_MD_AM_SSLLINK', 'https://', '_MD_AM_SSLLINKDSC', 'textbox', 'text', ".$conforder.")");

		$sql = "SELECT conf_order FROM ".$xoopsDB->prefix('config')." WHERE conf_name='theme_set'";
		$result = $xoopsDB->queryF($sql);
		list($conforder) = $xoopsDB->fetchRow($result);
		$conforder++;
		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('config')." VALUES (0, 0, 1, 'theme_set_allowed', '_MD_AM_THEMEOK', '".serialize(array('default'))."', '_MD_AM_THEMEOKDSC', 'theme_multi', 'array', ".$conforder.")");

		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('group_permission')." (gperm_groupid, gperm_itemid) SELECT groupid, itemid FROM ".$xoopsDB->prefix('groups_blocks_link'));
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('group_permission')." SET gperm_name = 'block_read'");
    	$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('group_permission')." (gperm_groupid, gperm_itemid) SELECT groupid, itemid FROM ".$xoopsDB->prefix('groups_modules_link') ." WHERE permtype='A'");
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('group_permission')." SET gperm_name = 'module_admin' WHERE gperm_name = ''");
    	$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('group_permission')." (gperm_groupid, gperm_itemid) SELECT groupid, itemid FROM ".$xoopsDB->prefix('groups_modules_link')." WHERE permtype='R'");
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('group_permission')." SET gperm_name = 'module_read' WHERE gperm_name = ''");
		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('group_permission')." (gperm_groupid, gperm_itemid) SELECT groupid, itemid FROM ".$xoopsDB->prefix('imgcat_group_link')." WHERE permtype='R'");
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('group_permission')." SET gperm_name = 'imgcat_read' WHERE gperm_name = ''");
		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('group_permission')." (gperm_groupid, gperm_itemid) SELECT groupid, itemid FROM ".$xoopsDB->prefix('imgcat_group_link')." WHERE permtype='W'");
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('group_permission')." SET gperm_name = 'imgcat_write' WHERE gperm_name = ''");
		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('group_permission')." (gperm_groupid, gperm_itemid) SELECT groupid, itemid FROM ".$xoopsDB->prefix('groups_system_link'));
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('group_permission')." SET gperm_name = 'system_admin' WHERE gperm_name = ''");
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('group_permission')." SET gperm_modid = 1");
		$xoopsDB->queryF('DROP TABLE '.$xoopsDB->prefix('groups_blocks_link'));
		$xoopsDB->queryF('DROP TABLE '.$xoopsDB->prefix('groups_modules_link'));
		$xoopsDB->queryF('DROP TABLE '.$xoopsDB->prefix('imgcat_group_link'));
		$xoopsDB->queryF('DROP TABLE '.$xoopsDB->prefix('groups_system_link'));

		echo $xoopsLogger->dumpQueries();
		echo 'Upgraded to XOOPS2 RC3. Do not forget do delete this file from your server!';
	}

} else {
	echo 'Press the button below to upgrade to XOOPS2 RC3<br />
<form action="xoops2_rc2_to_rc3.php" method="post">
<input type="submit" name="submit" value="'._SUBMIT.'" />
</form>';
}

echo '</body></html>';
?>
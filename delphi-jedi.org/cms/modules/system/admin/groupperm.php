<?php
include '../../../include/cp_header.php';
$modid = isset($HTTP_POST_VARS['modid']) ? intval($HTTP_POST_VARS['modid']) : 0;
// we dont want system module permissions to be changed here
if ($modid <= 1 || !is_object($xoopsUser) || !$xoopsUser->isAdmin($modid)) {
	redirect_header(XOOPS_URL.'/index.php', 1, _NOPERM);
	exit();
}
$module_handler =& xoops_gethandler('module');
$module =& $module_handler->get($modid);
if (!is_object($module) || !$module->getVar('isactive')) {
	redirect_header(XOOPS_URL.'/admin.php', 1, _MODULENOEXIST);
	exit();
}
$member_handler =& xoops_gethandler('member');
$group_list =& $member_handler->getGroupList();
if (is_array($HTTP_POST_VARS['perms']) && !empty($HTTP_POST_VARS['perms'])) {
	$gperm_handler = xoops_gethandler('groupperm');
	if (false != $gperm_handler->deleteByModule($modid)) {
		foreach ($HTTP_POST_VARS['perms'] as $perm_name => $perm_data) {
			foreach ($perm_data['groups'] as $group_id => $item_ids) {
				foreach ($item_ids as $item_id => $selected) {
					if ($selected == 1) {
						// make sure that all parent ids are selected as well
						if ($perm_data['parents'][$item_id] != '') {
							$parent_ids = explode(':', $perm_data['parents'][$item_id]);
							foreach ($parent_ids as $pid) {
								if ($pid != 0 && !in_array($pid, array_keys($item_ids))) {
									// one of the parent items were not selected, so skip this item
									$msg[] = sprintf(_MD_AM_PERMADDNG, '<b>'.$perm_name.'</b>', '<b>'.$perm_data['itemname'][$item_id].'</b>', '<b>'.$group_list[$group_id].'</b>').' ('._MD_AM_PERMADDNGP.')';
									continue 2;
								}
							}
						}
						$gperm =& $gperm_handler->create();
						$gperm->setVar('gperm_groupid', $group_id);
						$gperm->setVar('gperm_name', $perm_name);
						$gperm->setVar('gperm_modid', $modid);
						$gperm->setVar('gperm_itemid', $item_id);
						if (!$gperm_handler->insert($gperm)) {
							$msg[] = sprintf(_MD_AM_PERMADDNG, '<b>'.$perm_name.'</b>', '<b>'.$perm_data['itemname'][$item_id].'</b>', '<b>'.$group_list[$group_id].'</b>');
						} else {
							$msg[] = sprintf(_MD_AM_PERMADDOK, '<b>'.$perm_name.'</b>', '<b>'.$perm_data['itemname'][$item_id].'</b>', '<b>'.$group_list[$group_id].'</b>');
						}
						unset($gperm);
					}
				}
			}
		}
	} else {
		$msg[] = sprintf(_MD_AM_PERMRESETNG, $module->getVar('name'));
	}
}

$backlink = XOOPS_URL.'/admin.php';
if ($module->getVar('hasadmin')) {
	$adminindex = $module->getInfo('adminindex');
	if ($adminindex) {
		$backlink = XOOPS_URL.'/modules/'.$module->getVar('dirname').'/'.$adminindex;
	}
}

$msg[] = '<br /><br /><a href="'.$backlink.'">'._BACK.'</a>';
xoops_cp_header();
xoops_result($msg);
xoops_cp_footer();
?>
<?php
/*
 * Smarty plugin
 * ------------------------------------------------------------- 
 * File:     resource.db.php
 * Type:     resource
 * Name:     db
 * Purpose:  Fetches templates from a database
 * -------------------------------------------------------------
 */
function smarty_resource_db_source($tpl_name, &$tpl_source, &$smarty)
{
    $tplfile_handler =& xoops_gethandler('tplfile');
	$tplobj =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], null, null, null, $tpl_name, true);
	if (count($tplobj) > 0) {
		if (false != $smarty->xoops_canUpdateFromFile()) {
			if ($GLOBALS['xoopsConfig']['default_theme'] != 'default') {
				switch ($tplobj[0]->getVar('tpl_type')) {
					case 'module':
						$filepath = XOOPS_THEME_PATH.'/'.$GLOBALS['xoopsConfig']['default_theme'].'/templates/'.$tplobj[0]->getVar('tpl_module').'/'.$tpl_name;
						break;
					case 'block':
						$filepath = XOOPS_THEME_PATH.'/'.$GLOBALS['xoopsConfig']['default_theme'].'/templates/'.$tplobj[0]->getVar('tpl_module').'/blocks/'.$tpl_name;
						break;
					default:
						$filepath = "";
						break;
				}
			} else {
				switch ($tplobj[0]->getVar('tpl_type')) {
					case 'module':
						$filepath = XOOPS_ROOT_PATH.'/modules/'.$tplobj[0]->getVar('tpl_module').'/templates/'.$tpl_name;
						break;
					case 'block':
						$filepath = XOOPS_ROOT_PATH.'/modules/'.$tplobj[0]->getVar('tpl_module').'/templates/blocks/'.$tpl_name;
						break;
					default:
						$filepath = "";
						break;
				}
			}
			if ($filepath != "" && file_exists($filepath)) {
				$file_modified = filemtime($filepath);
				if ($file_modified > $tplobj[0]->getVar('tpl_lastmodified')) {
					if (false != $fp = fopen($filepath, 'r')) {
						$filesource = fread($fp, filesize($filepath));
    					fclose($fp);
						$tplobj[0]->setVar('tpl_source', $filesource, true);
						$tplobj[0]->setVar('tpl_lastmodified', time());
						$tplobj[0]->setVar('tpl_lastimported', time());
    					$tplfile_handler->forceUpdate($tplobj[0]);
						$tpl_source = $filesource;
        				return true;
					}
				}
			}
		}
        $tpl_source = $tplobj[0]->getVar('tpl_source');
        return true;
    } else {
		return false;
	}
}

function smarty_resource_db_timestamp($tpl_name, &$tpl_timestamp, &$smarty)
{
    $tplfile_handler =& xoops_gethandler('tplfile');
    $tplobj =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], null, null, null, $tpl_name, false);
	if (count($tplobj) > 0) {
		if (false != $smarty->xoops_canUpdateFromFile()) {
			if ($GLOBALS['xoopsConfig']['default_theme'] != 'default') {
				switch ($tplobj[0]->getVar('tpl_type')) {
					case 'module':
						$filepath = XOOPS_THEME_PATH.'/'.$GLOBALS['xoopsConfig']['default_theme'].'/templates/'.$tplobj[0]->getVar('tpl_module').'/'.$tpl_name;
						break;
					case 'block':
						$filepath = XOOPS_THEME_PATH.'/'.$GLOBALS['xoopsConfig']['default_theme'].'/templates/'.$tplobj[0]->getVar('tpl_module').'/blocks/'.$tpl_name;
						break;
					default:
						$filepath = "";
						break;
				}
			} else {
				switch ($tplobj[0]->getVar('tpl_type')) {
					case 'module':
						$filepath = XOOPS_ROOT_PATH.'/modules/'.$tplobj[0]->getVar('tpl_module').'/templates/'.$tpl_name;
						break;
					case 'block':
						$filepath = XOOPS_ROOT_PATH.'/modules/'.$tplobj[0]->getVar('tpl_module').'/templates/blocks/'.$tpl_name;
						break;
					default:
						$filepath = "";
						break;
				}
			}
			if ($file_path != "" && file_exists($filepath)) {
				$file_modified = filemtime($filepath);
				if ($file_modified > $tplobj[0]->getVar('tpl_lastmodified')) {
					$tpl_timestamp = $file_modified;
					return true;
				}
			}
		}
        $tpl_timestamp = $tplobj[0]->getVar('tpl_lastmodified');
        return true;
    } else {
		return false;
	}
}

function smarty_resource_db_secure($tpl_name, &$smarty)
{
    // assume all templates are secure
    return true;
}

function smarty_resource_db_trusted($tpl_name, &$smarty)
{
    // not used for templates
}
?>
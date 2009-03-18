<?php

function &xoopsheadline_getrenderer(&$headline)
{
	include_once XOOPS_ROOT_PATH.'/modules/xoopsheadline/class/headlinerenderer.php';
	if (file_exists(XOOPS_ROOT_PATH.'/modules/xoopsheadline/language/'.$GLOBALS['xoopsConfig']['language'].'/headlinerenderer.php')) {
		include_once XOOPS_ROOT_PATH.'/modules/xoopsheadline/language/'.$GLOBALS['xoopsConfig']['language'].'/headlinerenderer.php';
		if (class_exists('XoopsHeadlineRendererLocal')) {
			return new XoopsHeadlineRendererLocal($headline);
		}
	}
	return new XoopsHeadlineRenderer($headline);
}
?>
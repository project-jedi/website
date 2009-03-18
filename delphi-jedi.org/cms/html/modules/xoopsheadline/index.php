<?php
// $Id: index.php,v 1.1 2003/04/11 12:56:00 okazu Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
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

include '../../mainfile.php';
include 'include/functions.php';
$hlman =& xoops_getmodulehandler('headline');;
$hlid = isset($HTTP_GET_VARS['id']) ? intval($HTTP_GET_VARS['id']) : 0;
$xoopsOption['template_main'] = 'xoopsheadline_index.html';
include XOOPS_ROOT_PATH.'/header.php';
$headlines =& $hlman->getObjects(new Criteria('headline_display', 1));
$count = count($headlines);
for ($i = 0; $i < $count; $i++) {
	$xoopsTpl->append('feed_sites', array('id' => $headlines[$i]->getVar('headline_id'), 'name' => $headlines[$i]->getVar('headline_name')));
}
$xoopsTpl->assign('lang_headlines', _HL_HEADLINES);
if ($hlid == 0) {
	$hlid = $headlines[0]->getVar('headline_id');
}
if ($hlid > 0) {
	$headline =& $hlman->get($hlid);
	if (is_object($headline)) {
		$renderer =& xoopsheadline_getrenderer($headline);
		if (!$renderer->renderFeed()) {
			if ($xoopsConfig['debug_mode'] == 2) {
				$xoopsTpl->assign('headline', '<p>'.sprintf(_HL_FAILGET, $headline->getVar('headline_name')).'<br />'.$renderer->getErrors().'</p>');
			}
		} else {
			$xoopsTpl->assign('headline', $renderer->getFeed());
		}
	}
}
include XOOPS_ROOT_PATH.'/footer.php';
?>
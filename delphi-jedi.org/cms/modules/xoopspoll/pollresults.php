<?php
// $Id: pollresults.php,v 1.9 2003/03/15 00:19:15 w4z004 Exp $
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

include "../../mainfile.php";
include XOOPS_ROOT_PATH."/modules/xoopspoll/include/constants.php";
include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspoll.php";
include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspolloption.php";
include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspolllog.php";
include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspollrenderer.php";

$poll_id = $HTTP_GET_VARS['poll_id'];

$poll_id = (!empty($poll_id)) ? intval($poll_id) : 0;
if (empty($poll_id)) {
	redirect_header("index.php",0);
	exit();
}
$xoopsOption['template_main'] = 'xoopspoll_results.html';
include XOOPS_ROOT_PATH."/header.php";

$poll = new XoopsPoll($poll_id);
$renderer = new XoopsPollRenderer($poll);
$renderer->assignResults($xoopsTpl);

include XOOPS_ROOT_PATH.'/include/comment_view.php';

include XOOPS_ROOT_PATH."/footer.php";
?>
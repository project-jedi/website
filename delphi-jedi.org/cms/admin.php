<?php
// $Id: admin.php,v 1.10 2003/09/04 11:45:38 okazu Exp $
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

$xoopsOption['pagetype'] = "admin";
include "mainfile.php";
include XOOPS_ROOT_PATH."/include/cp_functions.php";
/*********************************************************/
/* Admin Authentication                                  */
/*********************************************************/

if ( $xoopsUser ) {
	if ( !$xoopsUser->isAdmin() ) {
		redirect_header("index.php",2,_AD_NORIGHT);
		exit();
	}
} else {
	redirect_header("index.php",2,_AD_NORIGHT);
	exit();
}
$op = "list";

if ( !empty($HTTP_GET_VARS['op']) ) {
	$op = $HTTP_GET_VARS['op'];
}

if ( !empty($HTTP_POST_VARS['op']) ) {
	$op = $HTTP_POST_VARS['op'];
}

if (!file_exists(XOOPS_CACHE_PATH.'/adminmenu.php') && $op != 'generate') {
	xoops_header();
	xoops_confirm(array('op' => 'generate'), 'admin.php', _AD_PRESSGEN);
	xoops_footer();
	exit();
}

switch ($op) {
case "list":
	xoops_cp_header();
	// ###### Output warn messages for security ######
	if (is_dir(XOOPS_ROOT_PATH."/install/" )) {
		xoops_error(sprintf(_WARNINSTALL2,XOOPS_ROOT_PATH.'/install/'));
		echo '<br />';
	}
	if ( is_writable(XOOPS_ROOT_PATH."/mainfile.php" ) ) {
		xoops_error(sprintf(_WARNINWRITEABLE,XOOPS_ROOT_PATH.'/mainfile.php'));
		echo '<br />';
	}
	if (!empty($HTTP_GET_VARS['xoopsorgnews'])) {
	/*
		$rssurlfile = XOOPS_CACHE_PATH.'/adminnewsurl.xml';
		$rssurldata = '';
		if (!file_exists($rssurlfile) || filemtime($rssurlfile) < time() - 86400) {
			if (false !== $fp = fopen('http://www.xoops.org/backendurl.xml', 'r')) {
				while (!feof ($fp)) {
					$rssurldata .= fgets($fp, 4096);
				}
				fclose($fp);
				if (false !== $fp = fopen($rssurlfile, 'w')) {
					fwrite($fp, $rssurldata);
				}
				fclose($fp);
			}
		} else {
			if (false !== $fp = fopen($rssurlfile, 'r')) {
				while (!feof ($fp)) {
					$rssurldata .= fgets($fp, 4096);
				}
				fclose($fp);
			}
		}
*/
		$rssurl = 'http://www.xoops.org/backend.php';
/*
		if ($rssurldata != '') {
			include_once XOOPS_ROOT_PATH.'/class/xml/rpc/xmlrpcparser.php';
			$rpcparser = new XoopsXmlRpcParser($rssurldata);
			if (false != $rpcparser->parse()) {
				$params =& $rpcparser->getParam();
				$langcode = _LANGCODE;
				if (!is_array($params[0]) || !isset($params[0][$langcode])) {
					$rssurl = 'http://www.xoops.org/backend.php';
				} else {
					$rssurl = $params[0][$langcode];
				}
			} else {
				echo $rpcparser->getErrors();
			}
		}
*/
		$rssfile = XOOPS_CACHE_PATH.'/adminnews.xml';
		$rssdata = '';
		if (!file_exists($rssfile) || filemtime($rssfile) < time() - 86400) {
			if (false !== $fp = fopen($rssurl, 'r')) {
				while (!feof ($fp)) {
					$rssdata .= fgets($fp, 4096);
				}
				fclose($fp);
				if (false !== $fp = fopen($rssfile, 'w')) {
					fwrite($fp, $rssdata);
				}
				fclose($fp);
			}
		} else {
			if (false !== $fp = fopen($rssfile, 'r')) {
				while (!feof ($fp)) {
					$rssdata .= fgets($fp, 4096);
				}
				fclose($fp);
			}
		}
		if ($rssdata != '') {
			include_once XOOPS_ROOT_PATH.'/class/xml/rss/xmlrss2parser.php';
			$rss2parser = new XoopsXmlRss2Parser($rssdata);
			if (false != $rss2parser->parse()) {
				echo '<table class="outer" width="100%">';
				$items =& $rss2parser->getItems();
				$count = count($items);
				for ($i = 0; $i < $count; $i++) {
					echo '<tr class="head"><td><a href="'.$items[$i]['link'].'" target="_blank">';
					echo $items[$i]['title'].'</a> ('.$items[$i]['pubdate'].')</td></tr>';
					if ($items[$i]['description'] != "") {
						echo '<tr><td class="odd">'.$items[$i]['description'];
						if ($items[$i]['guid'] != "") {
							echo '&nbsp;&nbsp;<a href="'.$items[$i]['guid'].'" target="_blank">'._MORE.'</a>';
						}
						echo '</td></tr>';
					} elseif ($items[$i]['guid'] != "") {
						echo '<tr><td class="even" valign="top"></td><td colspan="2" class="odd"><a href="'.$items[$i]['guid'].'" target="_blank">'._MORE.'</a></td></tr>';
					}
				}
				echo '</table>';
			} else {
				echo $rss2parser->getErrors();
			}
		}
	}
	xoops_cp_footer();
	break;
case 'generate':
	xoops_module_write_admin_menu(xoops_module_get_admin_menu());
	redirect_header('admin.php', 1, _AD_LOGINADMIN);
	break;
default:
	break;
}
?>
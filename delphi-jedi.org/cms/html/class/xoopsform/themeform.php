<?php
// $Id: themeform.php,v 1.10 2003/09/29 20:45:01 okazu Exp $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * 
 * 
 * @package     kernel
 * @subpackage  form
 * 
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
/**
 * base class
 */
include_once XOOPS_ROOT_PATH."/class/xoopsform/form.php";

/**
 * Form that will output as a theme-enabled HTML table
 * 
 * Also adds JavaScript to validate required fields
 * 
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * 
 * @package     kernel
 * @subpackage  form
 */
class XoopsThemeForm extends XoopsForm
{
	/**
	 * Insert an empty row in the table to serve as a seperator.
	 * 
     * @param	string  $extra  HTML to be displayed in the empty row.
	 * @param	string	$class	CSS class name for <td> tag
	 */
	function insertBreak($extra = '', $class= '')
	{
    	$class = ($class != '') ? " class='$class'" : '';
     	if ($extra != '') {
			$extra = "<tr><td colspan='2'$class></td></tr>";
			$this->addElement($extra);
		} else {
			$extra = "<tr><td colspan='2'$class>$extra</td></tr>";
			$this->addElement($extra);
		}
	}

	/**
	 * create HTML to output the form as a theme-enabled table with validation.
     * 
	 * @return	string
	 */
	function render()
	{
		$required =& $this->getRequired();
		$ret = "<form name='".$this->getName()."' id='".$this->getName()."' action='".$this->getAction()."' method='".$this->getMethod()."' onsubmit='return xoopsFormValidate_".$this->getName()."();'".$this->getExtra().">\n<table width='100%' class='outer' cellspacing='1'><tr><th colspan='2'>".$this->getTitle()."</th></tr>";
		//$count = 0;
		foreach ( $this->getElements() as $ele ) {
			if (!is_object($ele)) {
				$ret .= $ele;
			} elseif (!$ele->isHidden()) {
				//if ($count % 2 == 0) {
					$class = 'even';
				//} else {
				//	$class = 'odd';
				//}
				$ret .= "<tr valign='top' align='left'><td class='head'>".$ele->getCaption();
				if ($ele->getDescription() != '') {
					$ret .= '<br /><br /><span style="font-weight: normal;">'.$ele->getDescription().'</span>';
				}
				$ret .= "</td><td class='$class'>".$ele->render()."</td></tr>";
				//$count++;
			} else {
				$ret .= $ele->render();
			}
		}
		$js = "
		<script type='text/javascript'>
		<!--
		function xoopsFormValidate_".$this->getName()."(){";
		$required =& $this->getRequired();
		$reqcount = count($required);
		for ($i = 0; $i < $reqcount; $i++) {
			$caption = trim($required[$i]->getCaption());
			if ($caption == '') {
				$caption = $required[$i]->getName();
			}
			$js .= "if ( window.document.".$this->getName().".".$required[$i]->getName().".value == \"\" ){alert( \"".sprintf(_FORM_ENTER, $caption)."\" );window.document.".$this->getName().".".$required[$i]->getName().".focus();return false;}";
		}
		$js .= "}
		//-->
		</script>";
		$ret .= "</table></form>\n";
		$ret .= $js;
		return $ret;
	}
}
?>
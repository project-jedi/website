<?php
// $Id: formelement.php,v 1.8 2003/06/27 10:41:54 okazu Exp $
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
 * Abstract base class for form elements
 * 
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * 
 * @package     kernel
 * @subpackage  form
 */
class XoopsFormElement {

	/**#@+
	 * @access private
	 */
	/**
     * "name" attribute of the element
	 * @var string  
	 */
	var $_name;

	/**
     * caption of the element
	 * @var	string  
	 */
	var $_caption;

	/**
     * hidden?
	 * @var	bool    
	 */
	var $_hidden = false;

	/**
     * extra attributes to go in the tag
	 * @var	string  
	 */
	var $_extra;

	/**
     * required field?
	 * @var	bool    
	 */
	var $_required = false;

	/**
     * description of the field
	 * @var	string  
	 */
	var $_description = "";
    /**#@-*/

    
	/**
	 * constructor
	 * 
	 */
	function XoopsFormElement(){
		exit("This class cannot be instantiated!");
	}

	/**
	 * Is this element a container of other elements?
	 * 
     * @return	bool false
	 */	
	function isContainer()
	{
		return false;
	}

	/**
	 * set the "name" attribute for the element
	 * 
     * @param	string  $name   "name" attribute for the element
	 */
	function setName($name) {
		$this->_name = trim($name);
	}

	/**
	 * get the "name" attribute for the element
	 * 
     * @param	bool    encode?
     * @return	string  "name" attribute
	 */
	function getName($encode=true) {
		if (false != $encode) {
			return str_replace("&amp;", "&", str_replace("'","&#039;",htmlspecialchars($this->_name)));
		}
		return $this->_name;
	}

	/**
	 * set the caption for the element
	 * 
     * @param	string  $caption
	 */
	function setCaption($caption) {
		$this->_caption = trim($caption);
	}

	/**
	 * get the caption for the element
	 * 
     * @return	string
	 */
	function getCaption() {
		return $this->_caption;
	}

	/**
	 * set the element's description
	 * 
     * @param	string  $description
	 */
	function setDescription($description) {
		$this->_description = trim($description);
	}

	/**
	 * get the element's description
	 * 
     * @return	string
	 */
	function getDescription() {
		return $this->_description;
	}

	/**
	 * flag the element as "hidden"
	 * 
	 */
	function setHidden() {
		$this->_hidden = true;
	}

	/**
	 * Find out if an element is "hidden".
	 * 
     * @return	bool
	 */
	function isHidden() {
		return $this->_hidden;
	}

	/**
	 * Add extra attributes to the element.
     * 
     * This string will be inserted verbatim and unvalidated in the 
     * element's tag. Know what you are doing!
	 * 
     * @param	string  $extra
	 */
	function setExtra($extra){
		$this->_extra = " ".trim($extra);
	}

	/**
	 * Get the extra attributes for the element
	 * 
     * @return	string
	 */
	function getExtra(){
		if (isset($this->_extra)) {
			return $this->_extra;
		}
	}

	/**
	 * Generates output for the element.
     * 
     * This method is abstract and must be overwritten by the child classes.
     * @abstract
	 */
	function render(){
	}
}
?>
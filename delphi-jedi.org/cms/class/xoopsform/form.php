<?php
// $Id: form.php,v 1.9 2003/06/28 07:05:54 okazu Exp $
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
// public abstruct
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
 * Abstract base class for forms
 * 
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * 
 * @package     kernel
 * @subpackage  form
 */
class XoopsForm {
	/**#@+
	 * @access  private
	 */
    /** 
     * "action" attribute for the html form
     * @var string  
     */
	var $_action;

	/**
     * "method" attribute for the form.
	 * @var string  
	 */
	var $_method;

	/**
     * "name" attribute of the form
	 * @var string  
	 */
	var $_name;

	/**
     * title for the form
	 * @var string  
	 */
	var $_title;

	/**
     * array of {@link XoopsFormElement} objects
     * @var  array   
     */
	var $_elements = array();

	/**
     * extra information for the <form> tag
	 * @var string  
	 */
	var $_extra;

	/**
     * required elements
	 * @var array   
	 */
	var $_required = array();
    
    /**#@-*/

	/**
	 * constructor
	 * 
     * @param	string  $title  title of the form
     * @param	string  $name   "name" attribute for the <form> tag
     * @param	string  $action "action" attribute for the <form> tag
     * @param   string  $method "method" attribute for the <form> tag
	 */
	function XoopsForm($title, $name, $action, $method="post"){
		$this->_title = $title;
		$this->_name = $name;
		$this->_action = $action;
		$this->_method = $method;
	}

	/**
	 * return the title of the form
     * 
     * @return	string
	 */
	function getTitle(){
		return $this->_title;
	}

	/**
	 * get the "name" attribute for the <form> tag
     * 
	 * @return	string
	 */
	function getName(){
		return $this->_name;
	}

	/**
	 * get the "action" attribute for the <form> tag
	 * 
     * @return	string
	 */
	function getAction(){
		return $this->_action;
	}

	/**
	 * get the "method" attribute for the <form> tag
	 * 
     * @return	string
	 */
	function getMethod(){
		return $this->_method;
	}

	/**
	 * Add an element to the form
	 * 
     * @param	object  &$formElement    reference to a {@link XoopsFormElement}
     * @param	bool    $required       is this a "required" element?
	 */
	function addElement(&$formElement, $required=false){
		$this->_elements[] =& $formElement;
		if ($required) {
			if (!$formElement->isContainer()) {
				$this->_required[] =& $formElement;
			} else {
				$required_elements =& $formElement->getRequired();
				$count = count($required_elements);
				for ($i = 0 ; $i < $count; $i++) {
					$this->_required[] =& $required_elements[$i];
				}
			}
		}
	}

	/**
	 * get an array of forms elements
	 * 
	 * @param	bool	get elements recursively?
     * @return	array   array of {@link XoopsFormElement}s
	 */
	function &getElements($recurse = false){
		if (!$recurse) {
			return $this->_elements;
		} else {
			$ret = array();
			$count = count($this->_elements);
			for ($i = 0; $i < $count; $i++) {
				if (!$this->_elements[$i]->isContainer()) {
					$ret[] =& $this->_elements[$i];
				} else {
					$elements =& $this->_elements[$i]->getElements(true);
					$count2 = count($elements);
					for ($j = 0; $j < $count2; $j++) {
						$ret[] =& $elements[$j];
					}
					unset($elements);
				}
			}
			return $ret;
		}
	}

	/**
	 * get an array of "name" attributes of form elements
	 * 
     * @return	array   array of form element names
	 */
	function getElementNames()
	{
		$ret = array();
		$elements =& $this->getElements(true);
		$count = count($elements);
		for ($i = 0; $i < $count; $i++) {
			$ret[] = $elements[$i]->getName();
		}
		return $ret;
	}

	/**
	 * get a reference to a {@link XoopsFormElement} object by its "name"
	 * 
	 * @param  string  $name	"name" attribute assigned to a {@link XoopsFormElement}
     * @return object  reference to a {@link XoopsFormElement}, false if not found
	 */
	function &getElementByName($name){
		$elements =& $this->getElements(true);
		$count = count($elements);
		for ($i = 0; $i < $count; $i++) {
			if ($name == $elements[$i]->getName()) {
				return $elements[$i];
			}
		}
		return false;
	}

	/**
	 * Sets the "value" attribute of a form element
	 * 
	 * @param	string $name	the "name" attribute of a form element
	 * @param	string $value	the "value" attribute of a form element
	 */
	function setElementValue($name, $value){
		$ele =& $this->getElementByName($name);
		if (is_object($ele) && method_exists($ele, 'setValue')) {
			$ele->setValue($value);
		}
	}

	/**
	 * Sets the "value" attribute of form elements in a batch
	 * 
	 * @param	array $values	array of name/value pairs to be assigned to form elements
	 */
	function setElementValues($values){
		if (is_array($values) && !empty($values)) {
			// will not use getElementByName() for performance..
			$elements =& $this->getElements(true);
			$count = count($elements);
			for ($i = 0; $i < $count; $i++) {
				$name = $elements[$i]->getName();
				if ($name && isset($values[$name]) && method_exists($elements[$i], 'setValue')) {
					$elements[$i]->setValue($value);
				}
			}
		}
	}

	/**
	 * Gets the "value" attribute of a form element
	 * 
	 * @param	string 	$name	the "name" attribute of a form element
	 * @return	string 	the "value" attribute assigned to a form element, null if not set
	 */
	function &getElementValue($name){
		$ele =& $this->getElementByName($name);
		if (is_object($ele) && method_exists($ele, 'getValue')) {
			return $ele->getValue($value);
		}
		return;
	}

	/**
	 * gets the "value" attribute of all form elements
	 * 
	 * @return	array 	array of name/value pairs assigned to form elements
	 */
	function &getElementValues(){
		// will not use getElementByName() for performance..
		$elements =& $this->getElements(true);
		$count = count($elements);
		$values = array();
		for ($i = 0; $i < $count; $i++) {
			$name = $elements[$i]->getName();
			if ($name && method_exists($elements[$i], 'getValue')) {
				$values[$name] =& $elements[$i]->getValue();
			}
		}
		return $values;
	}

	/**
	 * set the extra attributes for the <form> tag
	 * 
     * @param	string  $extra  extra attributes for the <form> tag
	 */
	function setExtra($extra){
		$this->_extra = " ".$extra;
	}

	/**
	 * get the extra attributes for the <form> tag
	 * 
     * @return	string
	 */
	function &getExtra(){
		if (isset($this->_extra)) {
			return $this->_extra;
		}
	}

	/**
	 * make an element "required"
	 * 
     * @param	object  &$formElement    reference to a {@link XoopsFormElement}
	 */
	function setRequired(&$formElement){
		$this->_required[] =& $formElement;
	}

	/**
	 * get an array of "required" form elements
	 * 
     * @return	array   array of {@link XoopsFormElement}s 
	 */
	function &getRequired(){
		return $this->_required;
	}

	/**
	 * insert a break in the form
     * 
     * This method is abstract. It must be overwritten in the child classes.
	 * 
     * @param	string  $extra  extra information for the break
     * @abstract
	 */
	function insertBreak($extra = null){
	}

	/**
	 * returns renderered form
	 * 
     * This method is abstract. It must be overwritten in the child classes.
     * 
     * @abstract
	 */
	function render(){
	}

	/**
	 * displays rendered form
	 */
	function display(){
		echo $this->render();
	}

	/**
	 * assign to smarty form template instead of displaying directly
	 * 
     * @param	object  &$tpl    reference to a {@link Smarty} object
     * @see     Smarty
	 */
	function assign(&$tpl){
		$i = 0;
		foreach ( $this->getElements() as $ele ) {
			if ( !$ele->isHidden() ) {
				$elements[$i]['caption'] = $ele->getCaption();
				$elements[$i]['body'] = $ele->render();
				$elements[$i]['hidden'] = false;
			} else {
				$elements[$i]['caption'] = '';
				$elements[$i]['body'] = $ele->render();
				$elements[$i]['hidden'] = true;
			}
			$i++;
		}
		$js = "
		<!-- Start Form Vaidation JavaScript //-->
		<script type='text/javascript'>
		<!--//
		function xoopsFormValidate_".$this->getName()."(){
		";
		$required =& $this->getRequired();
		$reqcount = count($required);
		for ($i = 0; $i < $reqcount; $i++) {
			$js .= "if ( window.document.".$this->getName().".".$required[$i]->getName().".value == \"\" ) {alert( \"".sprintf(_FORM_ENTER, $required[$i]->getCaption())."\" );window.document.".$this->getName().".".$required[$i]->getName().".focus();return false;\n}
				";
		}
		$js .= "}
		//-->
		</script>
		<!-- End Form Vaidation JavaScript //-->
		";
		$tpl->assign($this->getName(), array('title' => $this->getTitle(), 'name' => $this->getName(), 'action' => $this->getAction(),  'method' => $this->getMethod(), 'extra' => 'onsubmit="return xoopsFormValidate_'.$this->getName().'();"'.$this->getExtra(), 'javascript' => $js, 'elements' => $elements));
	}
}
?>
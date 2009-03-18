<?php
// $Id: headlinerenderer.php,v 1.2 2003/04/20 16:27:57 okazu Exp $
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
include_once XOOPS_ROOT_PATH.'/class/template.php';
include_once XOOPS_ROOT_PATH.'/modules/xoopsheadline/language/'.$GLOBALS['xoopsConfig']['language'].'/main.php';

class XoopsHeadlineRenderer
{
	// holds reference to xoopsheadline class object
	var $_hl;

	var $_tpl;

	// XoopTemplate object
	var $_tpl;

	var $_feed;

	var $_block;

	var $_errors = array();

	// RSS2 SAX parser
	var $_parser;


	function XoopsHeadlineRenderer(&$headline)
	{
		$this->_hl =& $headline;
		$this->_tpl = new XoopsTpl();
	}

	function updateCache()
	{
		if (!$fp = fopen($this->_hl->getVar('headline_rssurl'), 'r')) {
			$this->_setErrors('Could not open file: '.$this->_hl->getVar('headline_rssurl'));
			return false;
		}
		$data = '';
		while (!feof ($fp)) {
			$data .= fgets($fp, 4096);
		}
		fclose ($fp);
		$this->_hl->setVar('headline_xml', $this->convertToUtf8($data));
		$this->_hl->setVar('headline_updated', time());
		$headline_handler =& xoops_getmodulehandler('headline', 'xoopsheadline');
		return $headline_handler->insert($this->_hl);
	}

	function renderFeed($force_update = false)
	{
		if ($force_update || $this->_hl->cacheExpired()) {
			if (!$this->updateCache()) {
				return false;
			}
		}
		if (!$this->_parse()) {
			return false;
		}
		$this->_tpl->clear_all_assign();
		$this->_tpl->assign('xoops_url', XOOPS_URL);
		$channel_data =& $this->_parser->getChannelData();
		array_walk($channel_data, array($this, 'convertFromUtf8'));
		$this->_tpl->assign_by_ref('channel', $channel_data);
		if ($this->_hl->getVar('headline_mainimg') == 1) {
			$image_data =& $this->_parser->getImageData();
			array_walk($image_data, array($this, 'convertFromUtf8'));
			$this->_tpl->assign_by_ref('image', $image_data);
		}
		if ($this->_hl->getVar('headline_mainfull') == 1) {
			$this->_tpl->assign('show_full', true);
		} else {
			$this->_tpl->assign('show_full', false);
		}
		$items =& $this->_parser->getItems();
		$count = count($items);
		$max = ($count > $this->_hl->getVar('headline_mainmax')) ? $this->_hl->getVar('headline_mainmax') : $count;
		for ($i = 0; $i < $max; $i++) {
			array_walk($items[$i], array($this, 'convertFromUtf8'));
			$this->_tpl->append_by_ref('items', $items[$i]);
		}
		$this->_tpl->assign(array('lang_lastbuild' => _HL_LASTBUILD, 'lang_language' => _HL_LANGUAGE, 'lang_description' => _HL_DESCRIPTION, 'lang_webmaster' => _HL_WEBMASTER, 'lang_category' => _HL_CATEGORY, 'lang_generator' => _HL_GENERATOR, 'lang_title' => _HL_TITLE, 'lang_pubdate' => _HL_PUBDATE, 'lang_description' => _HL_DESCRIPTION, 'lang_more' => _MORE));
		$this->_feed =& $this->_tpl->fetch('db:xoopsheadline_feed.html');
		return true;
	}

	function renderBlock($force_update = false)
	{
		if ($force_update || $this->_hl->cacheExpired()) {
			if (!$this->updateCache()) {
				return false;
			}
		}
		if (!$this->_parse()) {
			return false;
		}
		$this->_tpl->clear_all_assign();
		$this->_tpl->assign('xoops_url', XOOPS_URL);
		$channel_data =& $this->_parser->getChannelData();
		array_walk($channel_data, array($this, 'convertFromUtf8'));
		$this->_tpl->assign_by_ref('channel', $channel_data);
		if ($this->_hl->getVar('headline_blockimg') == 1) {
			$image_data =& $this->_parser->getImageData();
			array_walk($image_data, array($this, 'convertFromUtf8'));
			$this->_tpl->assign_by_ref('image', $image_data);
		}
		$items =& $this->_parser->getItems();
		$count = count($items);
		$max = ($count > $this->_hl->getVar('headline_blockmax')) ? $this->_hl->getVar('headline_blockmax') : $count;
		for ($i = 0; $i < $max; $i++) {
			array_walk($items[$i], array($this, 'convertFromUtf8'));
			$this->_tpl->append_by_ref('items', $items[$i]);
		}
		$this->_tpl->assign(array('site_name' => $this->_hl->getVar('headline_name'), 'site_url' => $this->_hl->getVar('headline_url'), 'site_id' => $this->_hl->getVar('headline_id')));
		$this->_block =& $this->_tpl->fetch('file:'.XOOPS_ROOT_PATH.'/modules/xoopsheadline/blocks/headline_block.html');
		return true;
	}


	
	function &_parse()
	{
		if (isset($this->_parser)) {
			return true;
		}
		include_once XOOPS_ROOT_PATH.'/class/xml/rss/xmlrss2parser.php';
		$this->_parser = new XoopsXmlRss2Parser($this->_hl->getVar('headline_xml'));
		switch ($this->_hl->getVar('headline_encoding')) {
		case 'utf-8':
			$this->_parser->useUtfEncoding();
			break;
		case 'us-ascii':
			$this->_parser->useAsciiEncoding();
			break;
		default:
			$this->_parser->useIsoEncoding();
			break;
		}
		$result = $this->_parser->parse();
		if (!$result) {
			$this->_setErrors($this->_parser->getErrors(false));
			unset($this->_parser);
			return false;
		}
		return true;
	}

	function &getFeed()
	{
		return $this->_feed;
	}

	function &getBlock()
	{
		return $this->_block;
	}

	function _setErrors($err)
	{
		$this->_errors[] = $err;
	}

	function &getErrors($ashtml = true)
	{
		if (!$ashtml) {
			return $this->_errors;
		} else {
		$ret = '';
		if (count($this->_errors) > 0) {
			foreach ($this->_errors as $error) {
				$ret .= $error.'<br />';
			}
		}
		return $ret;
		}
	}

	// abstract
	// overide this method in /language/your_language/headlinerenderer.php
	// this method is called by the array_walk function
	// return void
	function convertFromUtf8(&$value, $key)
	{
	}

	// abstract
	// overide this method in /language/your_language/headlinerenderer.php
	// return string
	function &convertToUtf8(&$xmlfile)
	{
		return $xmlfile;
	}
}
?>
<?php
// $Id: xoopspollrenderer.php,v 1.8 2003/02/14 14:23:58 okazu Exp $
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
include_once XOOPS_ROOT_PATH."/modules/xoopspoll/language/".$xoopsConfig['language']."/main.php";

class XoopsPollRenderer
{
	// private
	// XoopsPoll class object
	var $poll;

	// constructor
	function XoopsPollRenderer(&$poll)
	{
		$this->poll =& $poll;
	}

	// public
	function renderForm()
	{
		$content = "<form action='".XOOPS_URL."/modules/xoopspoll/index.php' method='post'>";
		$content .= "<table width='100%' border='0' cellpadding='4' cellspacing='1'>\n";
		$content .= "<tr class='bg3'><td align='center' colspan='2'><input type='hidden' name='poll_id' value='".$this->poll->getVar("poll_id")."' />\n";
		$content .= "<b>".$this->poll->getVar("question")."</b></td></tr>\n";
		$options_arr =& XoopsPollOption::getAllByPollId($this->poll->getVar("poll_id"));
		$option_type = "radio";
		$option_name = "option_id";
		if ( $this->poll->getVar("multiple") == 1 ) {
			$option_type = "checkbox";
			$option_name .= "[]";
		}
		foreach ( $options_arr as $option ) {
			$content .= "<tr class='bg1'><td align='center'><input type='$option_type' name='$option_name' value='".$option->getVar("option_id")."' /></td><td align='left'>".$option->getVar("option_text"). "</td></tr>\n";
		}
        	$content .= "<tr class='bg3'><td align='center' colspan='2'><input type='submit' value='"._PL_VOTE."' />&nbsp;";
        	$content .= "<input type='button' value='"._PL_RESULTS."' class='button' onclick='location=\"".XOOPS_URL."/modules/xoopspoll/pollresults.php?poll_id=".$this->poll->getVar("poll_id")."\"' />";
        	$content .= "</td></tr></table></form>\n";
		return $content;
	}

    function assignForm(&$tpl)
    {
		$options_arr =& XoopsPollOption::getAllByPollId($this->poll->getVar("poll_id"));
		$option_type = "radio";
		$option_name = "option_id";
		if ( $this->poll->getVar("multiple") == 1 ) {
			$option_type = "checkbox";
			$option_name .= "[]";
		}
        $i = 0;
		foreach ( $options_arr as $option ) {
			$options[$i]['input'] = "<input type='$option_type' name='$option_name' value='".$option->getVar("option_id")."' />";
            $options[$i]['text']  = $option->getVar("option_text");
            $i++;
		}
		$tpl->assign('poll', array('question' => $this->poll->getVar("question"), 'pollId' => $this->poll->getVar("poll_id"), 'viewresults' => XOOPS_URL."/modules/xoopspoll/pollresults.php?poll_id=".$this->poll->getVar("poll_id"), 'action' => XOOPS_URL."/modules/xoopspoll/index.php", 'options' => $options));
    }

	// public
	function renderResults()
	{
		if ( !$this->poll->hasExpired() ) {
			$end_text = sprintf(_PL_ENDSAT, formatTimestamp($this->poll->getVar("end_time"), "m"));
		} else {
			$end_text = sprintf(_PL_ENDEDAT, formatTimestamp($this->poll->getVar("end_time"), "m"));
		}
		echo "<div style='text-align:center'><table width='60%' border='0' cellpadding='4' cellspacing='0'><tr class='bg3'><td><span style='font-weight:bold;'>".$this->poll->getVar("question")."</span></td></tr><tr class='bg1'><td align='right'>$end_text</td></tr></table>";

		echo "<table width='60%' border='0' cellpadding='4' cellspacing='0'>";
		$options_arr =& XoopsPollOption::getAllByPollId($this->poll->getVar("poll_id"));
		$total = $this->poll->getVar("votes");
		foreach ( $options_arr as $option ) {
			if ( $total > 0 ) {
				$percent = 100 * $option->getVar("option_count") / $total;
			} else {
				$percent = 0;
			}
			echo "<tr class='bg1'><td width='30%' align='left'>".$option->getVar("option_text")."</td><td width='70%' align='left'>";
			if ( $percent > 0 ) {
				$width = intval($percent)*2;
				echo "<img src='".XOOPS_URL."/modules/xoopspoll/images/colorbars/".$option->getVar("option_color", "E")."' height='14' width='".$width."' align='middle' alt='".intval($percent)." %' />";
			}
			printf(" %d %% (%d)", $percent, $option->getVar("option_count"));
			echo "</td></tr>";
		}
		echo "<tr class='bg1'><td colspan='2' align='center'><br /><b>".sprintf(_PL_TOTALVOTES, $total)."<br />".sprintf(_PL_TOTALVOTERS, $this->poll->getVar("voters"))."</b>";
		if ( !$this->poll->hasExpired() ) {
			echo "<br />[<a href='".XOOPS_URL."/modules/xoopspoll/index.php?poll_id=".$this->poll->getVar("poll_id")."'>"._PL_VOTE."</a>]";
		}
		echo "</td></tr></table></div><br />";
	}

    function assignResults(&$tpl)
    {
		if ( !$this->poll->hasExpired() ) {
			$end_text = sprintf(_PL_ENDSAT, formatTimestamp($this->poll->getVar("end_time"), "m"));
		} else {
			$end_text = sprintf(_PL_ENDEDAT, formatTimestamp($this->poll->getVar("end_time"), "m"));
		}
		$options_arr =& XoopsPollOption::getAllByPollId($this->poll->getVar("poll_id"));
		$total = $this->poll->getVar("votes");
        $i = 0;
		foreach ( $options_arr as $option ) {
			if ( $total > 0 ) {
				$percent = 100 * $option->getVar("option_count") / $total;
			} else {
				$percent = 0;
			}
			$options[$i]['text'] = $option->getVar("option_text");
			if ( $percent > 0 ) {
				$width = intval($percent)*2;
				$options[$i]['image'] = "<img src='".XOOPS_URL."/modules/xoopspoll/images/colorbars/".$option->getVar("option_color", "E")."' height='14' width='".$width."' align='middle' alt='".intval($percent)." %' />";
			}
			$options[$i]['percent'] = sprintf(" %d %% (%d)", $percent, $option->getVar("option_count"));
			$options[$i]['total'] = $option->getVar("option_count");
			$i++;
		}
		if ( !$this->poll->hasExpired() ) {
			$vote = "<a href='".XOOPS_URL."/modules/xoopspoll/index.php?poll_id=".$this->poll->getVar("poll_id")."'>"._PL_VOTE."</a>";
		}
		$tpl->assign('poll', array('question' => $this->poll->getVar("question"),'end_text' => $end_text,'totalVotes' => sprintf(_PL_TOTALVOTES, $total), 'totalVoters' => sprintf(_PL_TOTALVOTERS, $this->poll->getVar("voters")),'vote' => $vote, 'options' => $options));
    }
}
?>
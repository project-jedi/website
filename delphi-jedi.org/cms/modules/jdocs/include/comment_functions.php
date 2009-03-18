<?php
/**
 * $Id: comment_functions.php v 1.00 20 November 2003 Catwolf Exp $
 * Module: WF-Channel
 * Version: v1.0.2
 * Release Date: 20 November 2003
 * Author: Catzwolf
 * Licence: GNU
**/

// comment callback functions

function wfchannel_com_update($download_id, $total_num){
	$db =& Database::getInstance();
	$sql = 'UPDATE '.$db->prefix('wfschannel').' SET comments = '.$total_num.' WHERE CID = '.$pagenum;
	$db->query($sql);
}

function wfchannel_com_approve(&$comment){
	// notification mail here
}
?>
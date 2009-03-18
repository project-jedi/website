<?php
// comment callback functions

function mylinks_com_update($link_id, $total_num){
	$db =& Database::getInstance();
	$sql = 'UPDATE '.$db->prefix('mylinks_links').' SET comments = '.$total_num.' WHERE lid = '.$link_id;
	$db->query($sql);
}

function mylinks_com_approve(&$comment){
	// notification mail here
}
?>
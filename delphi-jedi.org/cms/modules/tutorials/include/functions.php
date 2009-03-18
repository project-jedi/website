<?php
//returns the total number of items in items table that are accociated with a given table $table id
function getTotalItems($sel_id, $status1="", $status2=""){
	global $db, $mytree;
	$count = 0;
	$arr = array();
	$query = "select count(*) from ".$db->prefix("tutorials")." where cid=".$sel_id."";
	if($status1 != ""){
		$query .= " and status=$status1";
	}
	$result = $db->query($query);
	list($thing) = $db->fetch_row($result);
	$count = $thing;
	$query = "select count(*) from ".$db->prefix("tutorials")." where cid=".$sel_id."";
	if($status2 != ""){
		$query .= " and status=$status2";
	}
	$result = $db->query($query);
	list($thing) = $db->fetch_row($result);
	$count += $thing;
	$arr = $mytree->getAllChildId($sel_id);
	$size = sizeof($arr);
	for($i=0;$i<$size;$i++){
		$query2 = "select count(*) from ".$db->prefix("tutorials")." where cid=".$arr[$i]."";
		if($status1 != ""){
			$query2 .= " and status=$status1";
		}
		$result2 = $db->query($query2);
		list($thing) = $db->fetch_row($result2);
		$count += $thing;
		$query2 = "select count(*) from ".$db->prefix("tutorials")." where cid=".$arr[$i]."";
		if($status2 != ""){
			$query2 .= " and status=$status2";
		}
		$result2 = $db->query($query2);
		list($thing) = $db->fetch_row($result2);
		$count += $thing;
	}
	return $count;
}

function newgraphic($time, $status) {
    $count = 7;	//7 days
	$startdate = (time()-(86400 * $count));
	$newgfx = "";
    if ($startdate < $time) {
		if($status==1){
			$newgfx = "&nbsp;<img src=\"".XOOPS_URL."/modules/tutorials/images/newred.gif\" />";
		}elseif($status==3){
			$newgfx = "&nbsp;<img src=\"".XOOPS_URL."/modules/tutorials/images/update.gif\" />";
       	}
    }
    return $newgfx;
}

function popgraphic($hits, $popular) {
	$popgfx = "";
    if ($hits>=$popular) {
    	$popgfx = "&nbsp;<img src =\"".XOOPS_URL."/modules/tutorials/images/pop.gif\" alt=\""._MD_POPULAR."\" />";
    }
    return $popgfx;
}

function TextForm($url , $value) {
	return '<form action="'.$url.'" method="post"><input type="submit" value="'.$value.'" /></form>';
}
?>
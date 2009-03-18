<?php


include_once("admin_header.php");
xoops_cp_header();
$myts =& MyTextSanitizer::getInstance();
echo "<br />";
$op = "main";
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
switch($op) {
        case "edit":
			im_admin_edit($id);
			fc_footer();
			break;
        case "del":
			im_admin_del($id);
			im_admin_cleanW();
            im_admin_list();
			fc_footer();
			break;
        case "move":
			im_admin_move($id,$w);
			im_admin_cleanW();
            im_admin_list();
			fc_footer();
			break;
        case "new":
			im_admin_new();
			fc_footer();
			break;
        case "insert":
			im_admin_insert();
			im_admin_cleanW();
            im_admin_list();
			fc_footer();
			break;
        case "edited":
			im_admin_edited();
			im_admin_cleanW();
            im_admin_list();
			fc_footer();
			break;
		case "main":
        default:
			im_admin_cleanW();
            im_admin_list();
            fc_footer();
            break;
}

xoops_cp_footer();
//include_once("admin_footer.php");

function im_admin_edited () {
	global $users, $target, $pGroups, $hide, $title, $link, $id, $xoopsDB;
	$cgroups=array();
	$member_handler =& xoops_gethandler('member');
	$lgroups =& $member_handler->getGroups();
	if (is_array($pGroups)){  // implode geht nur wenn pGroups ein Array ist, deswegen Fallunterscheidung
	   $groups=implode("|",$pGroups);
	}
	else{
	   $groups= $pGroups;
    }
	$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("imenu")." SET `target` = '".$target."',`groups` = '".$groups."', `users` = '".$users."', `link` = '".$link."', `title` = '".$title."', `hide` = '".$hide."' WHERE `id` = '".$id."'");
}

function im_admin_insert () {
	global $users, $target, $pGroups, $weight, $link, $title, $hide, $xoopsDB;
	im_admin_makeplace($weight);
	$cgroups=array();
	$member_handler =& xoops_gethandler('member');
	$lgroups =& $member_handler->getGroups();
	$groups=implode("|",$pGroups);
	$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("imenu")." ( `id` , `groups` , `users` , `target` , `title` , `hide` , `link` , `weight` ) "
					."VALUES ("
					."'', '".$groups."', '".$users."', '".$target."', '".$title."', '".$hide."', '".$link."', '".$weight."'"
					.");");
}

function im_admin_edit ($id){
	global $xoopsDB,$xoopsGroups;
	$res = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("imenu")." WHERE id='".$id."'");
	$row = $xoopsDB->fetcharray($res);
	$member_handler =& xoops_gethandler('member');
	$groups =& $member_handler->getGroups();
	echo '<form name="form1" method="post" action="index.php?op=edited"><div align="center"><table class="outer"><tr><th colspan="2">'._AM_IMENU_NEWLINK.'</th></tr><tr><td class="head">'._AM_IMENU_TITLE.'</td>'
		.'<td class="even"><input name="title" type="text" id="title" value="'.$row['title'].'"></td>'
		.'</tr><tr><td class="head">'._AM_IMENU_LINK.'</td>'
		.'<td class="odd"><input name="link" type="text" id="link" size="50" value="'.$row['link'].'"></td>'
		.'</tr><tr><td class="head">'._AM_IMENU_HIDE.'</td>'
		.'<td class="even"><input name="hide" type="text" id="hide" size="4" value="'.$row['hide'].'"> '
		.'</td></tr><tr><td class="head">'._AM_IMENU_TARGET.'</td>'
		.'<td class="even"><input name="target" type="text" id="target" value="'.$row['target'].'"></td>'
		.'</tr><tr>'
		.'<td class="head">'._AM_IMENU_GROUPS.'</td>'
		.'<td class="even">';
		for ($i=0; $i<count($groups);$i++){
			// checked
			$group=$groups[$i]->getVar('name');
			$uGroups=explode("|",$row['groups']);
			$checked="";
			$groupid =$groups[$i]->getVar('groupid');
			if (in_array($groupid,$uGroups)) {
				$checked=" checked";
			}                                                       
			echo "<input name=\"pGroups[$i]\" type=\"checkbox\" id=\"pGroups[$i]\" value=\"$groupid\"$checked>$group<br>";  //bei id nicht $pGroups[$i]
		}
		echo '</td>'
		.'</tr><tr><td valign="top" class="head">'._AM_IMENU_USERS.'</td>'
		.'<td class="even"><textarea name="users" cols="40" rows="5" id="users">'.$row['users'].'</textarea></td>'
		.'</tr></table><br>'
		.'<input name="id" type="hidden" id="id" value="'.$row['id'].'">'
		.'<input type="submit" name="Submit" value="'._AM_IMENU_SUBMIT.'">'
		.'</div></form>';
}

function im_admin_makeplace ($w) {
	global $xoopsDB;
	$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("imenu")." SET `weight` = (`weight`+1) WHERE `weight` > '".($w-1)."';");

}

function im_admin_del ($id) {
	global $xoopsDB;
	$xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("imenu")." WHERE id='".$id."'");
}

function im_admin_move ($id,$w) {
	global $xoopsDB,$dir;
	if ($dir=='down'){
		$w++;
	}
	im_admin_makeplace($w);
	$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("imenu")." SET `weight` = ".$w." WHERE `id` = '".$id."';");
}


function im_admin_new(){
	$member_handler =& xoops_gethandler('member');
	$groups =& $member_handler->getGroups();
	echo '<form name="form1" method="post" action="index.php?op=insert"><div align="center"><table class="outer"><tr><th colspan="2">'._AM_IMENU_NEWLINK.'</th></tr><tr><td class="head">'._AM_IMENU_TITLE.'</td>'
		.'<td class="even"><input name="title" type="text" id="title"></td>'
		.'</tr><tr><td class="head">'._AM_IMENU_LINK.'</td>'
		.'<td class="odd"><input name="link" type="text" id="link" size="50"></td>'
		.'</tr><tr><td class="head">'._AM_IMENU_HIDE.'</td>'
		.'<td class="even"><input name="hide" type="text" id="hide" size="4" value="0"> '
		.'</tr><tr><td class="head">'._AM_IMENU_WEIGHT.'</td>'
		.'<td class="even"><input name="weight" type="text" id="hide" size="4" value="0"> '
		.'</td></tr><tr><td class="head">'._AM_IMENU_TARGET.'</td>'
		.'<td class="even"><input name="target" type="text" id="target" value="_self"></td>'
		.'</tr><tr>'
		.'<td class="head">'._AM_IMENU_GROUPS.'</td>'
		.'<td class="even">';
		for ($i=0; $i<count($groups);$i++){
			// checked
			$group=$groups[$i]->getVar('name');
			$groupid=$groups[$i]->getVar('groupid');
			$checked=" checked";
			echo "<input name=\"pGroups[$i]\" type=\"checkbox\" id=\"pGroups[$i]\" value=\"$groupid\" $checked>$group<br>";    //bei id nicht $pGroups[$i]
		}
		echo '</td>'
		.'</tr><tr><td valign="top" class="head">'._AM_IMENU_USERS.'</td>'
		.'<td class="even"><textarea name="users" cols="40" rows="5" id="users"></textarea></td>'
		.'</tr></table><br>'
		.'<input name="id" type="hidden" id="id" value="">'
		.'<input type="submit" name="Submit" value="'._AM_IMENU_SUBMIT.'">'
		.'</div></form>';
}

function im_admin_list(){
        global $xoopsDB, $xoopsConfig;
		
        OpenTable();
		echo '<center><a href="index.php?op=new">'._AM_IMENU_NEWLINK.'</a></center>';
		CloseTable();
		echo '<br><br><table class="outer" width="100%">'
				.'<tr><th colspan="4">iMenu</th></tr>'
				.'<tr>'
				.'<td class="head">'._AM_IMENU_TITLE.'</td>'
				.'<td class="head">'._AM_IMENU_HIDE.'</td>'
				.'<td class="head">'._AM_IMENU_LINK.'</td>'
				.'<td class="head">'._AM_IMENU_FUNCTIONS.'</td>'
				.'</tr>';
		$result=$xoopsDB->queryF("SELECT id, link, title, hide, weight FROM ".$xoopsDB->prefix("imenu")." WHERE 1 ORDER BY weight ASC");
		while ($row=$xoopsDB->fetcharray($result)) {
			echo '<tr>'
				.'<td class="even">'.$row['title'].'</td>'
				.'<td class="odd">'.$row['hide'].'</td>'
				.'<td class="even">'.$row['link'].'</td>'
				.'<td class="odd"><small><a href="index.php?op=del&id='.$row['id'].'">['._AM_IMENU_DEL.']</a>'
				.'<a href="index.php?op=edit&id='.$row['id'].'">['._AM_IMENU_EDIT.']</a>'
				.'<a href="index.php?op=move&dir=up&id='.$row['id'].'&w='.($row['weight']-1).'">['._AM_IMENU_UP.']</a>'
				.'<a href="index.php?op=move&dir=down&id='.$row['id'].'&w='.($row['weight']+1).'">['._AM_IMENU_DOWN.']</a></small></td>'
				.'</tr>';
		}
		echo "</table>";
}

function im_admin_cleanW() {
	global $xoopsDB;
	$res = $xoopsDB->queryF("SELECT id FROM ".$xoopsDB->prefix("imenu")." WHERE 1 ORDER BY weight ASC");
	$i=0;
	while ($row=$xoopsDB->fetcharray($res)) {
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("imenu")." SET `weight` = '".$i."' WHERE `id` = '".$row['id']."';");
		$i++;
	}
}

function fc_footer(){
        echo "<small><br>iMenu v2 by <a href=\"http://www.luinithil.com.com\">luinithil</a><br>Updates: <a target\"_blank\" href=\"http://www.luinithil.com\">http://www.luinithil.com</a></small>";
}
?>

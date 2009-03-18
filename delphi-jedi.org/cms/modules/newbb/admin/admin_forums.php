<?php
/***************************************************************************
                          admin_forums.php  -  description
                             -------------------
    begin                : Wed July 19 2000
    copyright            : (C) 2001 The phpBB Group
    email                : support@phpbb.com

    $Id: admin_forums.php,v 1.12 2003/03/16 22:39:47 w4z004 Exp $
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

include '../../../include/cp_header.php';
include '../functions.php';
include '../config.php';

if (isset($HTTP_GET_VARS['mode'])){
	$mode = $HTTP_GET_VARS['mode'];
}

foreach ($HTTP_POST_VARS as $k => $v) {
	${$k} = $v;
}

switch (trim($mode)) {
case 'editforum':
	$myts =& MyTextSanitizer::getInstance();
    if ( isset($HTTP_POST_VARS['save']) && $HTTP_POST_VARS['save'] != "" ) {
		if ( !$HTTP_POST_VARS['delete'] ) {
			$name = $myts->makeTboxData4Save($HTTP_POST_VARS['name']);
	 		$desc = $myts->makeTareaData4Save($HTTP_POST_VARS['desc']);

			$sql = sprintf("UPDATE %s SET forum_name = '%s', forum_desc = '%s', forum_type = '%s', cat_id = %u, forum_access = %u, allow_html = '%s', allow_sig = '%s', posts_per_page = %u, hot_threshold = %u, topics_per_page = %u WHERE forum_id = %u", $xoopsDB->prefix("bb_forums"), $name, $desc, $type, $cat, $forum_access, $html, $sig, $ppp, $hot, $tpp, $forum);

			if ( !$r = $xoopsDB->query($sql) ) {
				redirect_header("./index.php", 1);
				exit();
			}
			$count = 0;
			if ( isset($HTTP_POST_VARS["mods"]) ) {
				while ( list($null, $mod) = each($HTTP_POST_VARS["mods"]) ) {
					$mod_data = new XoopsUser($mod);
					if ( $mod_data->isActive() ) {
						$sql = sprintf("INSERT INTO %s (forum_id, user_id) VALUES (%u, %u)", $xoopsDB->prefix("bb_forum_mods"), $forum, $mod);
						if ( !$xoopsDB->query($sql) ) {
		    				redirect_header("./index.php", 1);
							exit();
						}
					}
				}
			}

			if ( !isset($mods) ) {
				$current_mods = "SELECT count(*) AS total FROM ".$xoopsDB->prefix("bb_forum_mods")." WHERE forum_id = $forum";
				$r = $xoopsDB->query($current_mods);
				list($total) = $xoopsDB->fetchRow($r);
			} else {
				$total = count($mods) + 1;
			}

			if ( isset($rem_mods) && $total > 1 ) {
				while ( list($null, $mod) = each($HTTP_POST_VARS["rem_mods"]) ) {
					$sql = sprintf("DELETE FROM %s WHERE forum_id = %u AND user_id = %u", $xoopsDB->prefix("bb_forum_mods"), $forum, $mod);
					if ( !$xoopsDB->query($sql) ) {
						redirect_header("./index.php", 1);
						exit();
					}
				}
			} else {
				if ( isset($rem_mods) ) {
					$mod_not_removed = 1;
				}
			}
			if ( $mod_not_removed ) {
	           	redirect_header("./index.php", 1, _MD_A_FORUMUPDATED."<br />"._MD_A_HTSMHNBRBITHBTWNLBAMOTF);
            }else{
	           	redirect_header("./index.php", 1, _MD_A_FORUMUPDATED);
			}
		} else {
			$sql = "SELECT post_id FROM ".$xoopsDB->prefix("bb_posts")." WHERE forum_id = $forum";
    		if ( !$r = $xoopsDB->query($sql) ) {
				redirect_header("./index.php", 1);
				exit();
			}
			if ( $xoopsDB->getRowsNum($r) > 0 ) {
				$sql = "DELETE FROM ".$xoopsDB->prefix("bb_posts_text")." WHERE ";
				$looped = false;
				while ( $ids = $xoopsDB->fetchArray($r) ) {
					if ( $looped == true ) {
						$sql .= " OR ";
					}
					$sql .= "post_id = ".$ids["post_id"]." ";
					$looped = true;
				}
				if ( !$r = $xoopsDB->query($sql) ) {
					redirect_header("./index.php", 1);
					exit();
				}
				$sql = sprintf("DELETE FROM %s WHERE forum_id = %u", $xoopsDB->prefix("bb_posts"), $forum);
				if ( !$r = $xoopsDB->query($sql) ) {
					redirect_header("./index.php", 1);
					exit();
				}
			}
			// RMV-NOTIFY
			xoops_notification_deletebyitem ($xoopsModule->getVar('mid'), 'forum', $forum);
			// Get list of all topics in forum, to delete them too
			$sql = sprintf("SELECT topic_id FROM %s WHERE forum_id = %u", $xoopsDB->prefix("bb_topics"), $forum);
			if ($r = $xoopsDB->query($sql)) {
				while ($row = $xoopsDB->fetchArray($r)) {
					xoops_notification_deletebyitem ($xoopsModule->getVar('mid'), 'thread', $row['topic_id']);
				}
			}

			$sql = sprintf("DELETE FROM %s WHERE forum_id = %u", $xoopsDB->prefix("bb_topics"), $forum);
	 		if ( !$r = $xoopsDB->query($sql) ) {
				redirect_header("./index.php", 1);
				exit();
			}
			$sql = sprintf("DELETE FROM %s WHERE forum_id = %u", $xoopsDB->prefix("bb_forums"), $forum);
	 		if ( !$r = $xoopsDB->query($sql) ) {
				redirect_header("./index.php", 1);
				exit();
			}

			$sql = sprintf("DELETE FROM %s WHERE forum_id = %u", $xoopsDB->prefix("bb_forum_mods"), $forum);
	 		if ( !$r = $xoopsDB->query($sql) ) {
				redirect_header("./index.php", 1);
				exit();
			}
           	redirect_header("./index.php", 1, _MD_A_FORUMREMOVED);
		}
	}
	if ( isset($HTTP_POST_VARS['submit']) && !isset($HTTP_POST_VARS['save']) ) {
		$sql = "SELECT * FROM ".$xoopsDB->prefix("bb_forums")." WHERE forum_id = $forum";
		if ( !$result = $xoopsDB->query($sql) ) {
			redirect_header("./index.php", 1);
			exit();
		}
		if ( !$myrow = $xoopsDB->fetchArray($result) ) {
             redirect_header("./index.php", 1, _MD_A_NOSUCHFORUM);
		}
		$name = $myts->makeTboxData4Edit($myrow['forum_name']);
		$desc = $myts->makeTareaData4Edit($myrow['forum_desc']);
		xoops_cp_header();
		echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr><td class=\"odd\">";
		echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
		?>
		<form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>" method='post'>
		<table border="0" cellpadding="1" cellspacing="0" align='center' Valign="TOP" width="95%"><tr><td class='bg2'>
		<table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr class='bg3' align='left'>
        <td align='center' colspan="2"><span class='fg2'><b><?php echo _MD_A_EDITTHISFORUM;?></b></span></td>
		</tr>
		<tr class='bg1' align='left'>
        <td colspan=2><input type="CHECKBOX" name='delete' value="1"><span class='fg2'> <?php echo _MD_A_DTFTWARAPITF;?></span></td>
		</tr>
		<tr class='bg1' align='left'>
        <td><span class='fg2'><?php echo _MD_A_FORUMNAME;?></span></td>
        <td><input type='text' name='name' SIZE="40" MAXLENGTH="150" value="<?php echo $name?>"></td>
		</tr>
		<tr class='bg1' align='left'>
        <td><span class='fg2'><?php echo _MD_A_FORUMDESCRIPTION;?></span></td>
        <td><TEXTAREA name='desc' ROWS="15" COLS="45" WRAP="VIRTUAL"><?php echo $desc?></TEXTAREA></td>
		</tr>
		<tr class='bg1' align='left'>
        <td valign="top"><span class='fg2'><?php echo _MD_A_MODERATOR;?></span></td>
        <td><b>Current:</b><br />
		<?php
		$sql = "SELECT u.uname, u.uid FROM ".$xoopsDB->prefix("users")." u, ".$xoopsDB->prefix("bb_forum_mods")." f WHERE f.forum_id = $forum AND u.uid = f.user_id";
		if ( !$r = $xoopsDB->query($sql) ) {
			echo"</td></tr></table>";
			xoops_cp_footer();
			exit();
		}
		if ( $row = $xoopsDB->fetchArray($r) ) {
			do {
				echo $row['uname']." (<input type=\"checkbox\" name=\"rem_mods[]\" value=\"".$row['uid']."\"> "._MD_A_REMOVE.")<br />";
				$current_mods[] = $row['uid'];
			} while ( $row = $xoopsDB->fetchArray($r) );
			echo "<br />";
		} else {
			echo _MD_A_NOMODERATORASSIGNED."<br /><br />\n";
		}

		?>
		<b>Add:</b><br />
		<select name='mods[]' size="5" multiple>
		<?php
		$sql = "SELECT uid, uname FROM ".$xoopsDB->prefix("users")." WHERE uid > 0 AND level > 0 ";
		while ( list($null, $currMod) = each($current_mods) ) {
			$sql .= "AND uid != $currMod ";
		}
		$sql .= "ORDER BY uname";
		if ( !$r = $xoopsDB->query($sql) ) {
			echo"</td></tr></table>";
			xoops_cp_footer();
			exit();
		}
		if ( $row = $xoopsDB->fetchArray($r) ) {
			do {
				$s = "";
				if ( $row['uid'] == $myrow['forum_moderator'] ) {
					$s = "SELECTED";
				}
				echo "<option value=\"".$row['uid']."\" $s>".$row['uname']."</option>\n";
			} while ( $row = $xoopsDB->fetchArray($r) );
		} else {
			echo "<option value=\"0\">"._MD_A_NONE."</option>\n";
		}
		?>
        </select></td>
		</tr>
		<tr class='bg1' align='left'>
        <td><span class='fg2'><?php echo _MD_A_CATEGORY;?></span></td>
        <td><select name='cat'>
		<?php
		$sql = "SELECT * FROM ".$xoopsDB->prefix("bb_categories")."";
		if ( !$r = $xoopsDB->query($sql) ) {
			echo"</td></tr></table>";
			xoops_cp_footer();
			exit();
		}
		if ( $row = $xoopsDB->fetchArray($r) ) {
			do {
				$s = "";
				if ( $row['cat_id'] == $myrow['cat_id'] ) {
					$s = "SELECTED";
				}
				echo "<option value=\"".$row['cat_id']."\" $s>".$row['cat_title']."</option>\n";
			} while ( $row = $xoopsDB->fetchArray($r) );
		} else {
			echo "<option value=\"0\">"._MD_A_NONE."</option>\n";
		}
		?>
        </select></td>
		<?php
		if ( $myrow['forum_access'] == 1 ) {
			$access1 = "SELECTED";
		}
		if ( $myrow['forum_access'] == 2 ) {
			$access2 = "SELECTED";
		}
		if ( $myrow['forum_access'] == 3 ) {
			$access3 = "SELECTED";
		}
		?>
		</tr>
		<tr class='bg1' align='left'>
		<td><span class='fg2'>Access Level:</span></td>
		<td><select name='forum_access'>
	    <option value="2" <?php echo $access2?>><?php echo _MD_A_ANONYMOUSPOST;?></option>
	    <option value="1" <?php echo $access1?>><?php echo _MD_A_REGISTERUSERONLY;?></option>
	    <option value="3" <?php echo $access3?>><?php echo _MD_A_MODERATORANDADMINONLY;?></option>
	    </select>
        </td>
		</tr>
		<tr class='bg1' align='left'>
		<td><span class='fg2'><?php echo _MD_A_TYPE;?></span></td>
		<td><select name='type'>
		<?php
		if ( $myrow['forum_type'] == 1 ) {
			$priv = "SELECTED";
		} else {
			$pub = "SELECTED";
		}
		?>
		<option value="0" <?php echo $pub?>><?php echo _MD_A_PUBLIC;?></option>
		<option value="1" <?php echo $priv?>><?php echo _MD_A_PRIVATE;?></option>
		</select>
		</td>
		</tr>
		<?php
		$html_yes = $html_no = $sig_yes = $sig_no = "";
		if ( $myrow['allow_html'] == 1 ) {
			$html_yes = "checked='checked'";
		} else {
			$html_no = "checked='checked'";
		}
		if ( $myrow['allow_sig'] == 1) {
			$sig_yes = "checked='checked'";
		} else {
			$sig_no = "checked='checked'";
		}
		echo "<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_ALLOWHTML ."</span></td>			<td><input type='radio' name='html' value='1'".$html_yes." />"._MD_A_YES."<input type='radio' name='html' value='0'".$html_no." />"._MD_A_NO ."</td></tr>
		<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_ALLOWSIGNATURES ."</span></td>
		<td><input type='radio' name='sig' value='1'$sig_yes />". _MD_A_YES ."<input type='radio' name='sig' value='0'$sig_no />". _MD_A_NO ."</td></tr>
		<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_HOTTOPICTHRESHOLD ."</span></td><td><input type='text' name='hot' size='3' maxlength='3' value='". $myrow['hot_threshold']."' /></td></tr>
		<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_POSTPERPAGE ."</span><br /><span class='fg2'><i>". _MD_A_TITNOPPTTWBDPPOT ."</i></span></td>
		<td><input type='text' name='ppp' size='3' maxlength='3' value='".$myrow['posts_per_page']."' /></td></tr>
		<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_TOPICPERFORUM ."</span><br /><span class='fg2'><i>". _MD_A_TITNOTPFTWBDPPOAF ."</i></span></td><td><input type='text' name='tpp' size='3' maxlength='3' value='". $myrow['topics_per_page'] ."' /></td></tr>";
		echo "<tr class='bg3' align='left'><td align='center' colspan='2'><input type='hidden' name='mode' value='editforum' /><input type='hidden' name='forum' value='$forum' /><input type='submit' name='save' value='". _MD_A_SAVECHANGES ."' />&nbsp;&nbsp;<input type='reset' value='". _MD_A_CLEAR ."' /></td></tr>";
		echo "</tr></table></td></tr></table>";
	}
	if ( !isset($HTTP_POST_VARS['submit']) && !isset($HTTP_POST_VARS['save']) ) {
		xoops_cp_header();
		echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr><td class=\"odd\">";
		echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
		?>
		<form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>" method='post'>
		<table border="0" cellpadding="1" cellspacing="0" align='center' Valign="TOP" width="95%"><tr><td class='bg2'>
		<table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr class='bg3' align='left'>
		<td align='center' colspan="2"><span class='fg2'><b><?php echo _MD_A_SELECTFORUMEDIT;?></b><span class='fg2'></td>
		</tr>
		<tr class='bg1' align='left'>
		<td align='center' colspan="2"><select name='forum' SIZE="0">
		<?php
		$sql = "SELECT forum_name, forum_id FROM ".$xoopsDB->prefix("bb_forums")." ORDER BY forum_id";
		if ( $result = $xoopsDB->query($sql) ) {
			if ( $myrow = $xoopsDB->fetchArray($result) ) {
				do {
					$name = $myts->makeTboxData4Show($myrow['forum_name']);
					echo "<option value=\"".$myrow['forum_id']."\">$name</option>\n";
				} while ( $myrow = $xoopsDB->fetchArray($result) );
			} else {
				echo "<option value=\"-1\">"._MD_A_NOFORUMINDATABASE."</option>\n";
			}
		} else {
			echo "<option value=\"-1\">"._MD_A_DATABASEERROR."</option>\n";
		}
		?>
		</select></td>
		</tr>
		<tr class='bg3' align='left'>
		<td align='center' colspan="2">
		<input type="hidden" name='mode' value="editforum">
		<input type='submit' name='submit' value="<?php echo _MD_A_EDIT;?>">&nbsp;&nbsp;
		</td>
		</tr>
		</tr>
		</table></td></tr></table>
		<?php
	}
	break;
case 'editcat':
	$myts =& MyTextSanitizer::getInstance();
   	if ( isset($HTTP_POST_VARS['submit']) && isset($HTTP_POST_VARS['save']) ) {
		$new_title = $myts->makeTboxData4Save($HTTP_POST_VARS['new_title']);
		$sql = "UPDATE ".$xoopsDB->prefix("bb_categories")." SET cat_title = '$new_title' WHERE cat_id = $cat_id";
		if ( !$result = $xoopsDB->query($sql) ) {
			redirect_header("./index.php", 1);
			exit();
   		} else {
			redirect_header("./index.php", 1, _MD_A_CATEGORYUPDATED);
	 	}
	} else if(isset($HTTP_POST_VARS['submit']) && $HTTP_POST_VARS['submit'] != "") {
		$sql = "SELECT cat_title FROM ".$xoopsDB->prefix("bb_categories")." WHERE cat_id = '$cat'";
   		if ( !$result = $xoopsDB->query($sql) ) {
			redirect_header("./index.php", 1);
			exit();
   		}
		xoops_cp_header();
		echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr><td class=\"odd\">";
		echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
   		$cat_data = $xoopsDB->fetchArray($result);
   		$cat_title = $myts->makeTboxData4Edit($cat_data["cat_title"]);
		?>
		<form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>" method='post'>
		<table border="0" cellpadding="1" cellspacing="0" align='center' Valign="TOP" width="95%"><tr><td class='bg2'>
		<table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr class='bg3' align='left'>
		<td align='center' colspan="2"><span class='fg2'><b><?php echo _MD_A_EDITCATEGORY;?> <?php echo $cat_title ?></b><span class='fg2'></td>
		</tr>
		<tr class='bg1' align='left'>
		<td><?php echo _MD_A_CATEGORYTITLE;?></td>
		<td><input type="text" name="new_title" value="<?php echo $cat_title ?>" size="45" maxlength="100"></td>
		</tr>
		<tr class='bg3' align='left'>
		<td align='center' colspan="2">
		<input type="hidden" name='mode' value="editcat" />
		<input type="hidden" name="save" value="TRUE" />
		<input type="hidden" name="cat_id" value="<?php echo $cat?>" />
		<input type='submit' name='submit' value="<?php echo _MD_A_SAVECHANGES;?>" />
		</td>
		</tr>
		</tr>
		</table></td></tr></table>
		<?php
	} else {
		$sql = "SELECT cat_id, cat_title FROM ".$xoopsDB->prefix("bb_categories")." ORDER BY cat_order";
   		if ( !$result = $xoopsDB->query($sql) ) {
			redirect_header("./index.php", 1);
			exit();
   		}
		xoops_cp_header();
		echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr><td class=\"odd\">";
		echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
		?>
		<form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>" method='post'>
		<table border="0" cellpadding="1" cellspacing="0" align='center' Valign="TOP" width="95%"><tr><td class='bg2'>
		<table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr class='bg3' align='left'>
		<td align='center' colspan="2"><span class='fg2'><b><?php echo _MD_A_SELECTACATEGORYEDIT;?></b><span class='fg2'></td>
		</tr>
		<tr class='bg1' align='left'>
		<td align='center' colspan="2"><select name='cat' SIZE="0">
		<?php
		while ( $cat_data = $xoopsDB->fetchArray($result) ) {
			echo "<option value=\"".$cat_data["cat_id"]."\">".$myts->makeTboxData4Show($cat_data["cat_title"])."</option>\n";
		}
		?>
		</select></td>
		<tr class='bg3' align='left'>
		<td align='center' colspan="2">
		<input type="hidden" name='mode' value="editcat">
		<input type='submit' name='submit' value="<?php echo _MD_A_EDIT;?>">&nbsp;&nbsp;
		</td>
		</tr>
		</tr>
		</table></td></tr></table>
		<?php
   	}
	break;
case 'remcat':
	$myts =& MyTextSanitizer::getInstance();
    if ( isset($HTTP_POST_VARS['submit']) && $HTTP_POST_VARS['submit'] != "" ) {
		$sql = sprintf("DELETE FROM %s WHERE cat_id = %u", $xoopsDB->prefix("bb_categories"), $cat);
		if ( !$r = $xoopsDB->query($sql) ) {
			redirect_header("./index.php", 1);
			exit();
		}
		redirect_header("./index.php", 1, _MD_A_CATEGORYDELETED);
	} else {
		xoops_cp_header();
		echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr><td class=\"odd\">";
		echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
		?>
		<form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>" method='post'>
		<table border="0" cellpadding="1" cellspacing="0" align='center' width="95%"><tr><td class='bg2'>
		<table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr class='bg3' align='left'>
		<td align='center' colspan="2"><span class='fg2'><b><?php echo _MD_A_RMVACAT;?></b></span></td>
		</tr>
		<tr class='bg3' align='left'>
		<td align='center' colspan="2"><span class='fg2'><i><?php echo _MD_A_NTWNRTFUTCYMDTVTEFS;?></i></span></td>
		</tr>
		<tr class='bg1'>
		<td align='center' colspan="2"><span class='fg2'>
		<select name='cat'>
		<?php
		$sql = "SELECT * FROM ".$xoopsDB->prefix("bb_categories")." ORDER BY cat_title";
		if ( !$r = $xoopsDB->query($sql) ) {
			echo"</td></tr></table>";
			xoops_cp_footer();
			exit();
		}
		while (  $m = $xoopsDB->fetchArray($r) ) {
			echo "<option value=\"".$m['cat_id']."\">".$myts->makeTboxData4Show($m['cat_title'])."</option>\n";
		}
		?>
		</select>
		<input type='hidden' name='mode' value='<?php echo $mode ?>' /></td>
		</tr>
		<tr class='bg3'>
		<td align='center' colspan="2"><span class='fg2'>
		<input type="submit" name="submit" value="<?php echo _MD_A_REMOVECATEGORY;?>" /></td></tr>
		</table></table></form>
		<?php
	}
	break;
case 'addcat':
	$myts =& MyTextSanitizer::getInstance();
    if ( isset($HTTP_POST_VARS['submit']) && $HTTP_POST_VARS['submit'] != "" ) {
		$nextid = $xoopsDB->genId($xoopsDB->prefix("bb_categories")."_cat_id_seq");
		$sql = "SELECT max(cat_order) AS highest FROM ".$xoopsDB->prefix("bb_categories")."";
		if ( !$r = $xoopsDB->query($sql) ) {
			redirect_header("./index.php", 1);
			exit();
		}
		list($highest) = $xoopsDB->fetchRow($r);
		$highest++;
		$title = $myts->makeTboxData4Save($title);
		$sql = "INSERT INTO ".$xoopsDB->prefix("bb_categories")." (cat_id, cat_title, cat_order) VALUES ($nextid, '$title', '$highest')";
		if ( !$result = $xoopsDB->query($sql) ) {
			redirect_header("./index.php", 1);
			exit();
		}
		redirect_header("./index.php", 1, _MD_A_CATEGORYCREATED);
	} else {
		xoops_cp_header();
		echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr><td class=\"odd\">";
		echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
		?>
		<form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>" method='post'>
		<table border="0" cellpadding="1" cellspacing="0" align='center' Valign="TOP" width="95%"><tr><td class='bg2'>
		<table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr class='bg3' align='left'>
		<td align='center' colspan="2"><span class='fg2'><b><?php echo _MD_A_CREATENEWCATEGORY;?></b></span></td>
		</tr>
		<tr class='bg1' align='left'>
		<td><span class='fg2'><?php echo _MD_A_CATEGORYTITLE;?></span></td>
		<td><input type="text" name="title" size="40" maxlength="100"></td>
		</tr>
		<tr class='bg3' align="left">
		<td align='center' colspan="2">
		<input type="hidden" name="mode" value="addcat" />
		<input type="submit" name="submit" value="<?php echo _MD_A_CREATENEWCATEGORY;?>" />&nbsp;&nbsp;
		<input type="reset" value="<?php echo _MD_A_CLEAR;?>" />
		</td>
		</tr>
		</tr>
		</table></td></tr></table>
		<?php
	}
	break;
case 'addforum':
	$myts =& MyTextSanitizer::getInstance();
    if ( isset($HTTP_POST_VARS['submit']) && $HTTP_POST_VARS['submit'] != "" ) {
		if ( $name == '' || $desc == '' || !is_array($mods) ) {
            xoops_cp_header();
		    echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
			."<tr><td class=\"odd\">";
		    echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
			echo _MD_A_YDNFOATPOTFDYAA;
			echo"</td></tr></table>";
			xoops_cp_footer();
			exit();
		}
      	$desc = $myts->makeTareaData4Save($HTTP_POST_VARS['desc']);
      	$name = $myts->makeTboxData4Save($HTTP_POST_VARS['name']);
		$html = intval($HTTP_POST_VARS['html']);
		$sig = intval($HTTP_POST_VARS['sig']);
		$ppp = intval($HTTP_POST_VARS['ppp']);
		$hot = intval($HTTP_POST_VARS['hot']);
		$tpp = intval($HTTP_POST_VARS['tpp']);
		$nextid = $xoopsDB->genId($xoopsDB->prefix("bb_forums")."_forum_id_seq");
		$sql = "INSERT INTO ".$xoopsDB->prefix("bb_forums")." (forum_id, forum_name, forum_desc, forum_access, cat_id, forum_type, allow_html, allow_sig,posts_per_page,hot_threshold,topics_per_page) VALUES ($nextid, '$name', '$desc', $forum_access, $cat, $type, '$html', '$sig', $ppp, $hot, $tpp)";
		if ( !$result = $xoopsDB->query($sql) ) {
			redirect_header("./index.php", 1);
			exit();
		}
		if ( $nextid == 0 ) {
			$nextid = $xoopsDB->getInsertId();
		}
		// RMV-NOTIFY
		$tags = array();
		$tags['FORUM_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewforum.php?forum=' . $nextid;
		$tags['FORUM_NAME'] = $name; 	
		$tags['FORUM_DESCRIPTION'] = $desc; 	
		$notification_handler =& xoops_gethandler('notification');
		$notification_handler->triggerEvents('global', 0, 'new_forum', $tags);

		$count = 0;
		while ( list($mod_number, $mod) = each($HTTP_POST_VARS["mods"]) ) {
			$mod_data = new XoopsUser($mod);
			if ( $mod_data->isActive() && $mod_data->level() < 2 ) {
				if ( !isset($user_query) ) {
					$user_query = "UPDATE ".$xoopsDB->prefix("users")." SET level = 2 WHERE ";
				}
				if ( $count > 0 ) {
					$user_query .= "OR ";
				}
				$user_query .= "uid = $mod ";
				$count++;
			}
			$mod_query = "INSERT INTO ".$xoopsDB->prefix("bb_forum_mods")." (forum_id, user_id) VALUES ($nextid, $mod)";
	 		if ( !$xoopsDB->query($mod_query) ) {
				redirect_header("./index.php", 1);
				exit();
			}
		}

		if ( isset($user_query) ) {
			if ( !$xoopsDB->query($user_query) ) {
				redirect_header("./index.php", 1);
				exit();
			}
		}
       	redirect_header("./index.php", 1, _MD_A_FORUMCREATED);
	} else {
		$sql = "SELECT count(*) AS total FROM ".$xoopsDB->prefix("bb_categories")."";
		if ( !$r = $xoopsDB->query($sql) ) {
			redirect_header("./index.php", 1);
			exit();
		}
    xoops_cp_header();
    echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr><td class=\"odd\">";
    echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
		list($total) = $xoopsDB->fetchRow($r);
		if ( $total < 1 || !isset($total) ) {
			echo _MD_A_EYMAACBYAF;
			echo"</td></tr></table>";
			xoops_cp_footer();
			exit();
		}
		?>
		<form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>" method='post'>
		<table border="0" cellpadding="1" cellspacing="0" align='center' Valign="TOP" width="95%"><tr><td class='bg2'>
		<table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr class='bg3' align='left'>
		<td align='center' colspan="2"><span class='fg2'><b><?php echo _MD_A_CREATENEWFORUM;?></b></span></td>
		</tr>
		<tr class='bg1' align='left'>
		<td><span class='fg2'><?php echo _MD_A_FORUMNAME;?></span></td>
		<td><input type='text' name='name' SIZE="40" MAXLENGTH="150"></td>
		</tr>
		<tr class='bg1' align='left'>
		<td><span class='fg2'><?php echo _MD_A_FORUMDESCRIPTION;?></span></td>
		<td><TEXTAREA name='desc' ROWS="15" COLS="45" WRAP="VIRTUAL"></TEXTAREA></td>
		</tr>
		<tr class='bg1' align='left'>
		<td><span class='fg2'><?php echo _MD_A_MODERATOR;?></span></td>
		<td><select name='mods[]' size='5' multiple='multiple'>
		<?php
		$sql = "SELECT uid, uname FROM ".$xoopsDB->prefix("users")." WHERE uid > 0 AND level > 0 ORDER BY uname";
		if ( !$result = $xoopsDB->query($sql) ) {
			echo"</td></tr></table>";
			xoops_cp_footer();
			exit();
		}
		if ( $myrow = $xoopsDB->fetchArray($result) ) {
			do {
				echo "<option value=\"".$myrow['uid']."\">".$myrow['uname']."</option>\n";
			} while ( $myrow = $xoopsDB->fetchArray($result) );
		} else {
			echo "<option value=\"0\">"._MD_A_NONE."</option>\n";
		}
		?>
		</select></td>
		</tr>
		<tr class='bg1' align='left'>
		<td><span class='fg2'><?php echo _MD_A_CATEGORY;?></span></td>
		<td><select name="cat">
		<?php
		$sql = "SELECT * FROM ".$xoopsDB->prefix("bb_categories")."";
		if ( !$result = $xoopsDB->query($sql) ) {
			echo"</td></tr></table>";
			xoops_cp_footer();
			exit();
		}
		if ( $myrow = $xoopsDB->fetchArray($result) ) {
			do {
				echo "<option value=\"".$myrow['cat_id']."\">".$myrow['cat_title']."</option>\n";
			} while ( $myrow = $xoopsDB->fetchArray($result) );
		} else {
			echo "<option value=\"0\">"._MD_A_NONE."</option>\n";
		}
		?>
		</select></td>
		</tr>
		<tr class='bg1' align='left'>
		<td><span class='fg2'><?php echo _MD_A_ACCESSLEVEL;?></span></td>
		<td><select name="forum_access">
		<option value="2"><?php echo _MD_A_ANONYMOUSPOST;?></option>
		<option value="1"><?php echo _MD_A_REGISTERUSERONLY;?></option>
		<option value="3"><?php echo _MD_A_MODERATORANDADMINONLY;?></option>
		</select>
		</td>
		</tr>
		<tr class='bg1' align='left'>
		<td><span class='fg2'><?php echo _MD_A_TYPE;?></span></td>
        <td><select name="type">
        <option value="0"><?php echo _MD_A_PUBLIC;?></option>
        <option value="1"><?php echo _MD_A_PRIVATE;?></option>
        </select>
        </td>
		</tr>
		<?php
		echo "<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_ALLOWHTML ."</span></td>			<td><input type='radio' name='html' value='1' />"._MD_A_YES."<input type='radio' name='html' value='0' checked='checked'  />"._MD_A_NO ."</td></tr>
		<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_ALLOWSIGNATURES ."</span></td>
		<td><input type='radio' name='sig' value='1' checked='checked' />". _MD_A_YES ."<input type='radio' name='sig' value='0' />". _MD_A_NO ."</td></tr>
		<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_HOTTOPICTHRESHOLD ."</span></td><td><input type='text' name='hot' size='3' maxlength='3' value='10' /></td></tr>
		<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_POSTPERPAGE ."</span><br /><span class='fg2'><i>". _MD_A_TITNOPPTTWBDPPOT ."</i></span></td>
		<td><input type='text' name='ppp' size='3' maxlength='3' value='10' /></td></tr>
		<tr class='bg1' align='left'><td><span class='fg2'>". _MD_A_TOPICPERFORUM ."</span><br /><span class='fg2'><i>". _MD_A_TITNOTPFTWBDPPOAF ."</i></span></td><td><input type='text' name='tpp' size='3' maxlength='3' value='20' /></td></tr>";
		?>
		<tr class='bg3' align='left'>
		<td align='center' colspan="2">
		<input type="hidden" name="mode" value="addforum" />
		<input type="submit" name="submit" value="<?php echo _MD_A_CREATENEWFORUM;?>" />&nbsp;&nbsp;
		<input type="reset" value="<?php echo _MD_A_CLEAR;?>" />
		</td>
		</tr>
		</tr>
		</table></td></tr></table>
		<?php
	}
	break;
case 'catorder':
	$myts =& MyTextSanitizer::getInstance();
    xoops_cp_header();
    echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr><td class=\"odd\">";
    echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
    //    update catagories set cat_order = cat_order + 1 WHERE cat_order >= 2; update catagories set cat_order = cat_order - 2 where cat_id = 3;
	if ( isset($up) && $up != "" ) {
		if ( $current_order > 1 ) {
			$order = $current_order - 1;
			$sql1 = "UPDATE ".$xoopsDB->prefix("bb_categories")." SET cat_order = $order WHERE cat_id = $cat_id";
			if ( !$r = $xoopsDB->query($sql1) ) {
				echo"</td></tr></table>";
				xoops_cp_footer();
				exit();
			}
			$sql2 = "UPDATE ".$xoopsDB->prefix("bb_categories")." SET cat_order = $current_order WHERE cat_id = $last_id";
			if ( !$r = $xoopsDB->query($sql2) ) {
				echo"</td></tr></table>";
				xoops_cp_footer();
				exit();
			}
			echo "<div>"._MD_A_CATEGORYMOVEUP."</div><br />";
		} else {
			echo "<div>"._MD_A_TCIATHU."</div><br />";
		}
	} else if ( isset($down) && $down != "" ) {
		$sql = "SELECT cat_order FROM ".$xoopsDB->prefix("bb_categories")." ORDER BY cat_order DESC";
		if ( !$r  = $xoopsDB->query($sql,1,0) ) {
			echo"</td></tr></table>";
			xoops_cp_footer();
			exit();
		}
		list($last_number) = $xoopsDB->fetchRow($r);
		if ( $last_number != $current_order ) {
			$order = $current_order + 1;
			$sql = "UPDATE ".$xoopsDB->prefix("bb_categories")." SET cat_order = $current_order WHERE cat_order = $order";
			if ( !$r  = $xoopsDB->query($sql) ) {
				echo"</td></tr></table>";
				xoops_cp_footer();
				exit();
			}
			$sql = "UPDATE ".$xoopsDB->prefix("bb_categories")." SET cat_order = $order where cat_id = $cat_id";
			if ( !$r  = $xoopsDB->query($sql) ) {
				echo"</td></tr></table>";
				xoops_cp_footer();
				exit();
			}
			echo "<div>"._MD_A_CATEGORYMOVEDOWN."</div><br />";
		} else {
			echo "<div>"._MD_A_TCIATLD."</div><br />";
		}
	}
	?>
	<form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>" method='post'>
    <table border="0" cellpadding="1" cellspacing="0" align='center' Valign="TOP" width="95%"><tr><td class='bg2'>
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr class='bg3' align='left'>
    <td align='center' colspan="3"><span class='fg2'><b><?php echo _MD_A_SETCATEGORYORDER;?></b></span><br />
    <?php echo _MD_A_TODHITOTCWDOTIP;?><br />
    <?php echo _MD_A_ECWMTCPUODITO;?></td>
    </tr>
    <tr class='bg3' align='center'>
    <td><?php echo _MD_A_CATEGORY1;?></td><td><?php echo _MD_A_MOVEUP;?></td><td><?php echo _MD_A_MOVEDOWN;?></td>
    </tr>
	<?php
    $sql = "SELECT * FROM ".$xoopsDB->prefix("bb_categories")." ORDER BY cat_order";
	if ( !$r = $xoopsDB->query($sql) ) {
		exit();
	}
	while ( $m = $xoopsDB->fetchArray($r) ) {
		echo "<!-- New Row -->\n";
		echo "<form action=\"".$HTTP_SERVER_VARS['PHP_SELF']."\" METHOD=\"POST\">\n";
		echo "<tr class='bg1' align='center'>\n";
		echo "<td>".$myts->makeTboxData4Show($m['cat_title'])."</td>\n";
		echo "<td><input type=\"hidden\" name=\"mode\" value=\"$mode\">\n";
		echo "<input type=\"hidden\" name=\"cat_id\" value=\"".$m['cat_id']."\">\n";
		echo "<input type=\"hidden\" name=\"last_id\" value=\"";
		if ( isset($last_id) ) {
			echo $last_id;
		}
		echo "\">\n";
		echo "<input type=\"hidden\" name=\"current_order\" value=\"".$m['cat_order']."\"><input type=\"submit\" name=\"up\" value=\""._MD_A_MOVEUP."\"></td>\n";
		echo "<td><input type=\"submit\" name=\"down\" value=\""._MD_A_MOVEDOWN."\"></td></tr></form>\n<!-- End of Row -->\n";
		$last_id = $m['cat_id'];
	}
	?>
    </TABLE></TABLE>
	<?php
	break;
case 'sync':
	if ( $submit ) {
		flush();
		sync(null, "all forums");
		flush();
		sync(null, "all topics");
		redirect_header("./index.php", 1, _MD_A_SYNCHING);
	} else {
		xoops_cp_header();
		echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr><td class=\"odd\">";
		echo "<a href='./index.php'><h4>"._MD_A_FORUMCONF."</h4></a>";
		?>
		<table border="0" cellpadding="1" cellspacing="0" align="center" width="95%"><tr><td class='bg2'>
		<table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr class='bg3' align='left'>
		<td><?php echo _MD_A_CLICKBELOWSYNC;?></td>
		</tr>
		<tr class='bg1' align='center'>
		<td><form action="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>" method="post">
		<input type="hidden" name="mode" value="<?php echo $mode?>"><input type="submit" name="submit" value="<?php echo _MD_A_SYNCFORUM;?>"></form></td>
		</td>
		</tr>
		</table>
		</td></tr></table>
		<?php
	}
	break;
}

echo"</td></tr></table>";
xoops_cp_footer();
?>

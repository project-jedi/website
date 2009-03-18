<?PHP
    $result = $db->query("select tid from ".$db->prefix("tutorials")." where cid=$xcid and status=1 or status=3 order by ".$orderby."");
	$number = $db->num_rows($result);
	if ($number == 0) {
		echo "<center>"._MD_NORESULTS."</center>";
	}
	$result = $db->query("select tid,cid,gid,tname,tdesc,timg,tlink,tauthor,status,codes,hits,rating,votes,date,submitter,timgwidth,timgheight from ".$db->prefix("tutorials")." where cid=$xcid and gid=$gid and status=1 or status=3 order by ".$orderby."");
	while (list($tid, $cid, $gid, $tname, $tdesc, $timg, $tlink, $tauthor, $status, $codes, $hits, $rating, $votes, $date, $submitter, $timgwidth, $timgheight) = $db->fetch_row($result)) {
		$tname = $myts->makeTboxData4Show($tname);
		$tdesc = $myts->makeTboxData4Show($tdesc);
		$tdesc = stripslashes($tdesc);
		if ($timg != "") {
			$timg = $myts->makeTboxData4Show($timg);
		}
		$tauthor = $myts->makeTboxData4Show($tauthor);
		if ($tlink != "") {
			$tlink = $myts->makeTboxData4Show($tlink);
		}
		$rating = number_format($rating, 2);

		if ($tutorial_visdefault == 1) {
			$show = $tutorial_visualize;
		} else {
			$show = $tutorial_default;
		}
		if ($tlink != "") {
			if ($framebrowse == 1 || $codes >= 10) {
				$link_url = "viewexttutorial.php?tid=$tid";
				$link_target = "";
			} else {
				$link_url = $tlink;
				$link_target = " target=\"_blank\"";
			}
		} else {
			$link_url = "viewtutorial.php?tid=$tid";
			$link_target = "";
		}
		if ($timg != "") {
			if (ereg("http://",$timg)) {
				$imgpath = $timg;
			} else {
				$imgpath = "".IMAGE_URL."/$timg";
			}
			if ($timgwidth > 0 && $timgheight > 0){
				$setsize ="width=".$timgwidth." height=".$timgheight;
			} else {
	 			$setsize ="";
	 		}
			$show = str_replace("[image]","<a href=\"".$link_url."\"".$link_target."><img src=\"".$imgpath."\" border=1 alt=\"$tname\" ".$setsize."></a>",$show);
			$show = str_replace("[image left]","<a href=\"".$link_url."\"".$link_target."><img src=\"".$imgpath."\" border=1 alt=\"$tname\" ".$setsize." align=\"left\"></a>",$show);
			$show = str_replace("[image right]","<a href=\"".$link_url."\"".$link_target."><img src=\"".$imgpath."\" border=1 alt=\"$tname\" ".$setsize." align=\"right\"></a>",$show);
		} else {
			$show = str_replace("[image]","",$show);
		}
		$newupdate = newgraphic($date, $status);
		$pop = popgraphic($hits, $popular);
		$show = str_replace("[title]","<a href=\"".$link_url."\"".$link_target.">".$tname."</a>".$newupdate."".$pop."",$show);
		if( $tauthor != XoopsUser::getUnameFromId($submitter)) {
			$showt = str_replace("[author]",sprintf(_MD_WRITTENBY,$tauthor),$show);
		} else {
			$show = str_replace("[author]",sprintf(_MD_WRITTENBY,"<a href=\"".XOOPS_URL."/userinfo.php?uid=".$submitter."\">".$tauthor."</a>"),$show);
		}
		$show = str_replace("[author]",sprintf(_MD_AUTHOR,$tauthor),$show);
		$date = formatTimestamp($date,"m");
		$show = str_replace("[date]",sprintf(_MD_CREATEDATE,$date),$show);
		$show = str_replace("[hits]","(".sprintf(_MD_TUTORIALREAD,$hits).")",$show);
		if ( $rating!="0" || $rating!="0.0" ) {
 			if ($votes == 1) {
				$votestring = _MD_ONEVOTE;
			} else {
				$votestring = sprintf(_MD_NUMVOTES,$votes);
			}
			$show = str_replace("[rating]",_MD_RATINGC.": $rating",$show);
			$show = str_replace("[votes]","$votestring",$show);
		} else {
				$show = str_replace("[rating]","",$show);
				$show = str_replace("[votes]","",$show);
		}
		$show = str_replace("[ratethis]","&nbsp;<a href=\"rate.php?tid=$tid\"><b>"._MD_RATETHIS."</b></a>",$show);
		$show = str_replace("[description]","$tdesc",$show);
		$show = str_replace("[link]","<a href=\"".$link_url."\"".$link_target.">"._MD_LETSGO."</a>",$show);
		if ($tlink == "") {
			$show = str_replace("[print]","<a href=\"printpage.php?tid=$tid\"><img src=\"".XOOPS_URL."/modules/tutorials/images/print.gif\" border=0 Alt=\"Print\" width=15 height=11\"></a>",$show);
		} else {
			$show = str_replace("[print]","",$show);
		}
		if ( $xoopsUser ) {
			if ( $xoopsUser->isAdmin($xoopsModule->mid()) ) {
				$editorlink = "(<a href=\"".XOOPS_URL."/modules/tutorials/admin/index.php?op=editTutorial&tid=$tid\">"._MD_MODIFY."</a>)";
			} elseif ($xoopsUser->uid() == $submitter) {
				$editorlink = "(<a href=\"".XOOPS_URL."/modules/tutorials/submit.php?op=editTutorial&tid=$tid\">"._MD_MODIFY."</a>)";
			}
		}
		$show = str_replace("[edit]",$editorlink,$show);
		echo "$show <br />";
    }
?>
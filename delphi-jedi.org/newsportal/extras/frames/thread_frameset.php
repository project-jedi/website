<? header("Expires: ".gmdate("D, d M Y H:i:s",time()+7200)." GMT");

   include "config.inc";
   $title.= ' - '.$group;
   include "head.inc"; ?>

<frameset rows="40,50%,50%" frameborder="3" border="3" framespacing="0">
  <frame src="topframe.php?group=<? echo urlencode($group); ?>" name="topframe" scrolling="no">
  <frame src="<? echo $file_framethread; ?>?group=<? echo urlencode($group); ?>" name="thread">
  <frame src="article_blank.php" name="article">
</frameset>

<noframes>
<H1 align="center"><? echo $title; ?></H1>

<p>Your Browser doesn't support Frames, but that's no Problem.</p>

<UL>
<li><a href="<? echo $file_framethread.'?group='.$group; ?>">Read
Articles</a></li>
<? if (!$readonly) 
  echo "<li><a target=\"$frame_post\" ";
  echo "href=\"$file_post?newsgroups=".urlencode($group)."&type=new\">";
  echo $text_thread["button_write"]."</a></li>";
?>
</UL>
</noframes>
<? include "tail.inc"; ?>

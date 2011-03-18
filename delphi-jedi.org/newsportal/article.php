<?
  header("Expires: ".gmdate("D, d M Y H:i:s",time()+(3600*24))." GMT");
  include "config.inc";
  include "$file_newsportal";

  $message=read_message($id,0,$group);
  if (!$message) {
    header ("HTTP/1.0 404 Not Found");
    $subject=$title;
    $title.=' - Article not found';
  } else {
    $subject=htmlspecialchars($message->header->subject);
    $title.= ' - '.$subject;
  }
  include "head_article.inc";
?>

<p align="center">
<? if (!$readonly)
  echo '[<a target="'.$frame_post.'" href="'.$file_post.'?type=reply&amp;id='.$id.'&amp;group='.urlencode($group).'">'.$text_article["button_answer"].'</a>]'."\n";
?>
</p>
<p>
<?
  flush();
// Code for the next and previous button, not ready
/*
$spoolopenmodus=0;
$ns=OpenNNTPconnection();
$headers = readOverview($ns,$group,1,$spoolopenmodus);
$next = $headers[$message->header->id];
echo "-".$next->subject."-";
echo '[<a href="'.$next->number.'.html">Nächster</a>]';
*/
?>
</p>
<?
  if (!$message)
    echo $text_error["article_not_found"];
  else
    show_article($group,$id,0,$message);
?>
<p align="center">
<? if (!$readonly)
  echo '[<a target="'.$frame_post.'" href="'.$file_post.'?type=reply&amp;id='.$id.'&amp;group='.urlencode($group).'">'.$text_article["button_answer"].'</a>]'."\n";
?>
</p>

<? usleep(100000); include "tail_article.inc"; ?>

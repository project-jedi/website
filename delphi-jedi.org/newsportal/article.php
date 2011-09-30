<?
  if ((!isset($ajax)) || ($ajax == false))
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

  if (isset($dyn)) {
    if ($dyn != 'false') {
      $ajax = true;
    } else {
      $ajax = false;
    }
  } else {
    if ((isset($dynamic)) && ($dynamic == true)) {
      $ajax = true;
    } else {
      $ajax = false;
    }
  }
  
  if ($ajax == false) {
    include "head_article.inc";
  
    echo '<p align="center">';

    if (!$readonly)
      echo '[<a target="'.$frame_post.'" href="'.$file_post.'?type=reply&amp;id='.$id.'&amp;group='.urlencode($group).'">'.$text_article["button_answer"].'</a>]'."\n";

    echo '</p>';
    echo '<p></p>';
  }
  
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

  if (!$message)
    echo "<p>".$text_error["article_not_found"]."</p>";
  else
    show_article($group,$id,0,$message);

  if (!$readonly) {
    echo '<div align="center" id="rpl'.$id.'">';
    echo '[<a target="'.$frame_post.'" href="'.$file_post.'?type=reply&amp;id='.$id.'&amp;group='.urlencode($group).'" ';
    if ($ajax == true)
      echo 'onclick="return showanswer(&#39;'.$file_post.'&#39;,&#39;'.$id.'&#39;,&#39;'.urlencode($group).'&#39;)" ';
    echo '>'.$text_article["button_answer"].'</a>]'."\n";
    echo '</div>';
  }

  usleep(100000);
  if ($ajax == false) {
    include "tail_article.inc";
  }
?>

<?
  header("Expires: ".gmdate("D, d M Y H:i:s",time()+7200)." GMT");

  include "config.inc";
  require_once('recaptcha.php');
  require_once('extras/recaptcha/recaptchalib.php');

  $group=$_GET['group'];
  $title.= ' - '.$group;
   
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
  
  if ($ajax == true) {
    $dyn = 'true';
  } else {
    $dyn = 'false';
  }
  
  include "head.inc";
  
  if ($ajax == true) {
    include "topframe.php";
    include "thread.php";
  } else {
 ?>

<frameset rows="130,*" frameborder="1" border="3" framespacing="0">
  <frame src="topframe.php?group=<? echo urlencode($group); ?>" name="topframe" scrolling="no" noresize="true">
  <frameset cols="45%,*" frameborder="1" border="3" framespacing="3">
    <frame src="<? echo $file_framethread; ?>?group=<? echo urlencode($group); ?>" name="thread">
    <frame src="article_blank.php" name="article">
  </frameset>
</frameset>

<noframes>
<H1 align="center"><? echo $title; ?></H1>

<p>Your Browser doesn't support Frames, but that's no Problem.</p>

<UL>
<li><a href="<? echo $file_framethread.'?group='.$group; ?>">Read
Articles</a></li>
<?
    if (!$readonly) {
      echo "<li><a target=\"$frame_post\" ";
      echo "href=\"$file_post?newsgroups=".urlencode($group)."&type=new\">";
      echo $text_thread["button_write"]."</a></li>";
    }
?>
</UL>
</noframes>

<? }
   include "tail.inc";
?>

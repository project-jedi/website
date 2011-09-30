<?
  if ((!isset($ajax)) || ($ajax == false))
    header("Expires: ".gmdate("D, d M Y H:i:s",time()+7200)." GMT");

  include "config.inc";
  
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
    echo '<a name="top">';
  }

  include("$file_newsportal");
  $group=$_GET['group'];
  $ns=OpenNNTPconnection($server,$port);
  flush();
  if ($ns != false) {
    $headers = readOverview($ns,$group,1);
    $article_count=count($headers);
    if ($articles_per_page != 0) { 
      if ((!isset($first)) || (!isset($last))) {
        if ($startpage=="first") {
          $first=1;
          $last=$articles_per_page;
        } else {
          $first=$article_count - (($article_count -1) % $articles_per_page);
          $last=$article_count;
        }
      }
      if ($ajax == false) {
        echo '<p align="center">';
        showPageSelectMenu($group,count($headers),$first);
        echo '</p>';
      }
    } else {
      $first=0;
      $last=$article_count;
    }
    showHeaders($headers,$group,$first,$last,$ajax);
    closeNNTPconnection($ns);
  }
  
  if ($ajax == true) {
    if ($last < $article_count)
      echo '<div id="bottom"><a href="/'.$file_framethread.'?group='.$group.'&first='.($first+$articles_per_page).'&last='.($last+$articles_per_page).'" onclick="return showthread(&#39;'.$file_framethread.'&#39;,&#39;'.urlencode($first+$articles_per_page).'&#39;,&#39;'.urlencode($last+$articles_per_page).'&#39;,&#39;'.urlencode($group).'&#39);">'.$text_thread["next"].'</a></div>';
  } else {
    echo '<p align="right"><a href="#top"><? echo $text_thread["button_top"];?></a></p>';
    echo '<? include "tail_article.inc"; ?>';
  }
?>


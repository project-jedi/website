<? header("Expires: ".gmdate("D, d M Y H:i:s",time()+7200)." GMT");

  include "config.inc";
  include "head_article.inc"; ?>

<a name="top">
<?
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
    echo '<p align="center">';
    showPageSelectMenu($group,count($headers),$first);
    echo '</p>';
  } else {
    $first=0;
    $last=$article_count;
  }
  showHeaders($headers,$group,$first,$last);
  closeNNTPconnection($ns);
}
?>

<p align="right"><a href="#top"><? echo $text_thread["button_top"];?></a></p>

<? include "tail_article.inc"; ?>

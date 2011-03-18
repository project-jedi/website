<?
  $url=explode("/",$PATH_INFO);
  $group=$url[1];
  include "config.inc";
  $title.= ' - '.$group;
  include "head.inc"; ?>

<a name="top"></a>
<h1 align="center"><?php echo $group; ?></h1>

<p>Lese Overview- und Artikeldaten ein...</p>

<? 
include("$file_newsportal");
$ns=OpenNNTPconnection($server,$port);
flush();
if ($ns != false) {
  $headers = readOverview($ns,$group,1,true);
  closeNNTPconnection($ns);
}
?>

<p align="right"><a href="#top"><? echo $text_thread["button_top"];?></a></p>

<? include "tail.inc"; ?>

<? $title = "Newsportal - NNTP<->HTTP Gateway";

   include "head_article.inc";
   include "config.inc"; ?>

<?
require("$file_newsportal");
flush();
$ns=OpenNNTPconnection($server,$port);

if ($ns != false) {
  ?><pre><?
  $head=readPlainHeader($ns,$group,$id);
  for ($i=0; $i<count($head); $i++)
    echo $head[$i]."\n";
  $body=readMessage($ns,$id,"");
  for ($i=0; $i<count($body); $i++)
    echo $body[$i]."\n";
  ?></pre><?
}
closeNNTPconnection($ns);
?>

<? include "tail_article.inc"; ?>

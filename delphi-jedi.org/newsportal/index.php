<? header("Expires: ".gmdate("D, d M Y H:i:s",time()+7200)." GMT");

   include "config.inc";
   include "head_article.inc"; ?>

<h1 align="center"><? echo htmlspecialchars($title); ?></h1>

<br />

<?
include("$file_newsportal");
flush();
$newsgroups=readgroups($server,$port);
showgroups($newsgroups);
?>

<p align="right"><small>
"<a href="http://florian-amrhein.de/newsportal/">News-Portal</a>"
was written by <a href="http://florian-amrhein.de">Florian Amrhein</a>.
</small></p>

<? include "tail_article.inc"; ?>

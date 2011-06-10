<? header("Expires: ".gmdate("D, d M Y H:i:s",time()+7200)." GMT");

   include "config.inc";
   include "head_article.inc"; ?>

<table width="100%" border="0" cellpadding="5">
  <tr>
    <td width="152"> <!-- 142 + 2*5 -->
      <a href="http://www.delphi-jedi.org">
        <img src="/logo.jpg" border="0" alt="Project JEDI"/>
      </a>
      <br/>
      <span style="font-size:10px;"><a href="http://www.delphi-jedi.org">Back to homepage</a></span>
    </td>
    <td>
      <h1 align="center"><? echo htmlspecialchars($title); ?></h1>
    </td>
  </tr>
</table>
<br />

<p align="left">
  This webportal mirrors the content of the NNTP server located at
  <a href="nntp://news.delphi-jedi.org/">
    nntp://news.delphi-jedi.org/
  </a>
</p>
<p align="left">
  These newsgroups are private newsgroups. They may be used only for discussing about Project JEDI and related subprojects.
</p>

<div width="100%" style="position:relative; padding-top:20;">
<?
include("$file_newsportal");
flush();
$newsgroups=readgroups($server,$port);
showgroups($newsgroups);
?>
</div>

<div style="position:absolute; bottom:0">
  <p align="right">
    <small>
      Powered by "<a href="http://florian-amrhein.de/newsportal/"> 
         News-Portal
      </a>",
      written by
      <a href="http://florian-amrhein.de">
        Florian Amrhein
      </a>.
    </small>
  <p>
</div>
<? include "tail_article.inc"; ?>
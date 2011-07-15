<? header("Expires: ".gmdate("D, d M Y H:i:s",time()+(3600*24))." GMT");
   include "config.inc";
   $group=$_GET['group'];
   $title.= ' - '.$group;
   include "head_article.inc";
 ?>

<table width=100%>
  <tr valign="center">
    <td width="20%" nowrap="true">
      <a href="http://www.delphi-jedi.org">
        <img src="/logo.jpg" border="0" alt="Project JEDI"/>
      </a>
      <br/>
      <span style="font-size:10px;"><a href="http://www.delphi-jedi.org">Back to homepage</a></span>
    </td>
    <td width="80%" align="center">
      <font size="+1">
        <b>
          <? echo $group;?>
        </b>
      </font>
      <br/>
      [<a target="<? echo $frame_groups;?>" href="<? echo $file_groups ?>">
         <? echo $text_thread["button_grouplist"] ?>
       </a>]
      <? if (!$readonly)
        echo "[<a target=\"$frame_post\" ";
        echo "href=\"$file_post?newsgroups=".urlencode($group)."&type=new\">";
        echo $text_thread["button_write"]."</a>]";
      ?>
    </td>
  </tr>
</table>

<p align="left">
  This webportal mirrors the content of the NNTP server located at news.delphi-jedi.org.
</p>
<p align="left">
  These newsgroups are private newsgroups. They may be used only for discussing about Project JEDI and related subprojects.
</p>

<? include "tail_article.inc"; ?>

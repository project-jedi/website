<? header("Expires: ".gmdate("D, d M Y H:i:s",time()+(3600*24))." GMT");
   include "config.inc";
   $group=$_GET['group'];
   $title.= ' - '.$group;
   include "head_article.inc";
 ?>

<table width=100%>
<tr valign="center">
<td width="20%" nowrap="true"><font size="+1"><b><? echo $group;?></b></font></td>
<td width="80%" align="center">
[<a target="<? echo $frame_groups;?>" href="<? echo $file_groups ?>"><? echo $text_thread["button_grouplist"] ?></a>]
<? if (!$readonly)
  echo "[<a target=\"$frame_post\" ";
  echo "href=\"$file_post?newsgroups=".urlencode($group)."&type=new\">";
  echo $text_thread["button_write"]."</a>]";
?>
</tr>
</table>

<? include "tail_article.inc"; ?>

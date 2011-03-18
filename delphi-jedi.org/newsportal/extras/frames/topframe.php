<? header("Expires: ".gmdate("D, d M Y H:i:s",time()+(3600*24))." GMT");
   include "config.inc";
   $title.= ' - '.$group;
   include "head.inc";
 ?>

<table width=100%>
<tr>
<td><b><? echo $group;?></b></td>
<td align=left>
[<a target="<? echo $frame_groups;?>" href="<? echo $file_groups ?>"><? echo $text_thread["button_grouplist"] ?></a>]
<? if (!$readonly) 
  echo "[<a target=\"$frame_post\" ";
  echo "href=\"$file_post?newsgroups=".urlencode($group)."&type=new\">";
  echo $text_thread["button_write"]."</a>]";
?>
</tr>
</table>

<? include "tail.inc"; ?>

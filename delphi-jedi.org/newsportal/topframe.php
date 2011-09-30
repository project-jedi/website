<?
  if ((!isset($ajax)) || ($ajax == false))
    header("Expires: ".gmdate("D, d M Y H:i:s",time()+(3600*24))." GMT");
    
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
  
  $group=$_GET['group'];
  $title.= ' - '.$group;
  if ($ajax == false)
    include "head_article.inc";
 ?>

<table width=100%>
  <tr valign="center">
    <td width="152">
      <a href="http://www.delphi-jedi.org">
        <img src="/logo.jpg" border="0" alt="Project JEDI"/>
      </a>
      <br/>
      <span style="font-size:10px;"><a href="http://www.delphi-jedi.org">Back to homepage</a></span>
    </td>
    <td>
      This webportal mirrors the content of the NNTP server located at news.delphi-jedi.org.<br/>
      These newsgroups are private newsgroups. They may be used only for discussing about Project JEDI and related subprojects.
    </td>
  </tr>
</table>

<p align="center">
      <font size="+1">
        <b>
          <? echo $group;?>
        </b>
      </font>
      <br/>
      [<a target="<? echo $frame_groups;?>" href="<? echo $file_groups ?>">
         <? echo $text_thread["button_grouplist"] ?>
       </a>]
<?
  if (!$readonly) {
    echo '[<a target="$frame_post" ';
    echo 'href="'.$file_post.'?newsgroups='.urlencode($group).'&type=new">';
    echo $text_thread["button_write"].'</a>]';
  }
  if ($ajax == true) {
    echo '[<a target="_top" href="'.$file_thread.'?group='.$group.'&dyn=false">'.$text_thread["frame"].'</a>]';
  } else {
    echo '[<a target="_top" href="'.$file_thread.'?group='.$group.'&dyn=true">'.$text_thread["dynamic"].'</a>]';  
  }
?>
</p>

<? 
  if ($ajax == false)
    include "tail_article.inc";
?>

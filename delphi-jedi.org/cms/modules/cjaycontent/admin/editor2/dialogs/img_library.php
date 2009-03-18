<?php 
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Image library dialog
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// $Revision: 1.7 $, $Date: 2003/04/21 15:09:56 $
// ================================================
include './../../../../../mainfile.php';
// include wysiwyg config
include '../config/spaw_control.config.php';
include $spaw_root.'class/lang.class.php';


$theme = 'default';
$theme_path = XOOPS_URL.'/modules/cjaycontent/admin/editor2/lib/themes/'.$theme.'/';

$l = new SPAW_Lang(empty($HTTP_POST_VARS['lang'])?$HTTP_GET_VARS['lang']:$HTTP_POST_VARS['lang']);
$l->setBlock('image_insert');
?>

<?php 
$imglib = $HTTP_POST_VARS['lib'];
if (empty($imglib)) $imglib = $HTTP_GET_VARS['lib'];

$value_found = false;
// callback function for preventing listing of non-library directory
function is_array_value($value, $key, $_imglib)
{
  global $value_found;
  // echo $value.'-'.$_imglib.'<br>';
  if (is_array($value)) array_walk($value, 'is_array_value',$_imglib);
  if ($value == $_imglib){
    $value_found=true;
  }
}
array_walk($spaw_imglibs, 'is_array_value',$imglib);

if (!$value_found || empty($imglib))
{
  $imglib = $spaw_imglibs[0]['value'];
}
$lib_options = liboptions($spaw_imglibs,'',$imglib);


$img = $HTTP_POST_VARS['imglist'];

$preview = XOOPS_URL.'/'.$imglib.$img;

$errors = array();
if ($HTTP_POST_FILES['img_file']['size']>0)
{
  if ($img = uploadImg('img_file'))
  {
    $preview = XOOPS_URL.'/'.$imglib.$img;
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
  <title><?php echo $l->m('title')?></title>
	<meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset()?>">
  <link rel="stylesheet" type="text/css" href="<?php echo $theme_path.'css/'?>dialog.css">
  <script language="javascript" src="utils.js"></script>
  
  <script language="javascript">
  <!--
    function selectClick()
    {
      if (document.libbrowser.lib.selectedIndex>=0 && document.libbrowser.imglist.selectedIndex>=0)
      {
        window.returnValue = '<?php echo XOOPS_URL.'/'?>'+document.libbrowser.lib.options[document.libbrowser.lib.selectedIndex].value + document.libbrowser.imglist.options[document.libbrowser.imglist.selectedIndex].value;
        window.close();
      }
      else
      {
        alert('<?php echo $l->m('error').': '.$l->m('error_no_image')?>');
      }
    }
    
    function Init()
    {
      resizeDialogToContent();
    }
  //-->
  </script>
</head>

<body onLoad="Init()" dir="<?php echo $l->getDir();?>">
  <script language="javascript">
  <!--
    window.name = 'imglibrary';
  //-->
  </script>

<form name="libbrowser" method="post" action="img_library.php" enctype="multipart/form-data" target="imglibrary">
<input type="hidden" name="theme" value="<?php echo $theme?>">
<input type="hidden" name="lang" value="<?php echo $l->lang?>">
<div style="border: 1 solid Black; padding: 5 5 5 5;">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
  <td valign="top" align="left"><b><?php echo $l->m('library')?>:</b></td>
  <td valign="top" align="left">&nbsp;</td>
  <td valign="top" align="left"><b><?php echo $l->m('preview')?>:</b></td>
</tr>
<tr>
  <td valign="top" align="left">
  <select name="lib" size="1" class="input" style="width: 150px;" onChange="libbrowser.submit();">
    <?php echo $lib_options?>
  </select>
  </td>
  <td valign="top" align="left" rowspan="3">&nbsp;</td>
  <td valign="top" align="left" rowspan="3">
  <iframe name="imgpreview" src="<?php echo $preview?>" style="width: 200px; height: 100%;" scrolling="Auto" marginheight="0" marginwidth="0" frameborder="0"></iframe>
  </td>
</tr>
<tr>
  <td valign="top" align="left"><b><?php echo $l->m('images')?>:</b></td>
</tr>
<tr>
  <td valign="top" align="left">
  <?php 
    if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
      $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
    else
      $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];
    
    $d = @dir($_root.$imglib);
  ?>
  <select name="imglist" size="15" class="input" style="width: 150px;" 
    onchange="if (this.selectedIndex &gt;=0) imgpreview.location.href = '<?php echo XOOPS_URL.'/'.$imglib?>' + this.options[this.selectedIndex].value;" ondblclick="selectClick();">
  <?php 
    if ($d) 
    {
      while (false !== ($entry = $d->read())) {
        if (is_file($_root.$imglib.$entry))
        {
          ?>
          <option value="<?php echo $entry?>" <?php echo ($entry == $img)?'selected':''?>><?php echo $entry?></option>
          <?php 
        }
      }
      $d->close();
    }
    else
    {
      $errors[] = $l->m('error_no_dir');
    }
  ?>


  </select>
  </td>
</tr>
<tr>
  <td valign="top" align="left" colspan="3">
  <input type="button" value="<?php echo $l->m('select')?>" class="bt" onclick="selectClick();">&nbsp;<input type="button" value="<?php echo $l->m('cancel')?>" class="bt" onclick="window.close();">
  </td>
</tr>
</table>
</div>

<?php  if ($spaw_upload_allowed) { ?>
<div style="border: 1 solid Black; padding: 5 5 5 5;">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
  <td valign="top" align="left">
    <?php  
    if (!empty($errors))
    {
      echo '<span class="error">';
      foreach ($errors as $err)
      {
        echo $err.'<br>';
      }
      echo '</span>';
    }
    ?>

  <?php 
  if ($d) {
  ?>
    <b><?php echo $l->m('upload')?>:</b> <input type="file" name="img_file" class="input"><br>
    <input type="submit" name="btnupload" class="bt" value="<?php echo $l->m('upload_button')?>">
  <?php 
  }
  ?>
  </td>
</tr>
</table>
</div>
<?php  } ?>
</form>
</body>
</html>

<?php 
function liboptions($arr, $prefix = '', $sel = '')
{
  $buf = '';
  foreach($arr as $lib) {
    $buf .= '<option value="'.$lib['value'].'"'.(($lib['value'] == $sel)?' selected':'').'>'.$prefix.$lib['text'].'</option>'."\n";
  }
  return $buf;
}

function uploadImg($img) {

  global $HTTP_POST_FILES;
  global $HTTP_SERVER_VARS;
  global $spaw_valid_imgs;
  global $imglib;
  global $errors;
  global $l;
  global $spaw_upload_allowed;
  
  if (!$spaw_upload_allowed) return false;

  if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
    $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
  else
    $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];
  
  if ($HTTP_POST_FILES[$img]['size']>0) {
    $data['type'] = $HTTP_POST_FILES[$img]['type'];
    $data['name'] = $HTTP_POST_FILES[$img]['name'];
    $data['size'] = $HTTP_POST_FILES[$img]['size'];
    $data['tmp_name'] = $HTTP_POST_FILES[$img]['tmp_name'];

    // get file extension
    $ext = strtolower(substr(strrchr($data['name'],'.'), 1));
    if (in_array($ext,$spaw_valid_imgs)) {
      $dir_name = $_root.$imglib;

      $img_name = $data['name'];
      $i = 1;
      while (file_exists($dir_name.$img_name)) {
        $img_name = ereg_replace('(.*)(\.[a-zA-Z]+)$', '\1_'.$i.'\2', $data['name']);
        $i++;
      }
      if (!move_uploaded_file($data['tmp_name'], $dir_name.$img_name)) {
        $errors[] = $l->m('error_uploading');
        return false;
      }

      return $img_name;
    }
    else
    {
      $errors[] = $l->m('error_wrong_type');
    }
  }
  return false;
}
?>
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

// include wysiwyg config
include '../config/spaw_control.config.php';
include $spaw_root.'class/lang.class.php';

$theme = empty($HTTP_POST_VARS['theme'])?(empty($HTTP_GET_VARS['theme'])?$spaw_default_theme:$HTTP_GET_VARS['theme']):$HTTP_POST_VARS['theme'];
//$theme_path = $spaw_dir.'lib/themes/'.$theme.'/';
$theme_path = $spaw_root.'lib/themes/'.$theme.'/';

$l = new SPAW_Lang(empty($HTTP_POST_VARS['lang'])?$HTTP_GET_VARS['lang']:$HTTP_POST_VARS['lang']);
$l->setBlock('image_insert');
?>

<?php 
$lib = $HTTP_POST_VARS['lib'];
if (empty($lib)) $lib = $HTTP_GET_VARS['lib'];

$value_found = false;
// callback function for preventing listing of non-library directory
function is_array_value($value, $key, $_imglib)
{
  global $value_found,$lib;
  // echo $value.'-'.$_imglib.'<br>';
  
  if (in_array($_imglib,$value)){
    $value_found=true;
    $lib = $spaw_imglibs[$key]['catID'];
    var_dump($key);
  }
}
//array_walk($spaw_imglibs, 'is_array_value',$imglib);
foreach ($spaw_imglibs as $spawimg){
    if ($lib == $spawimg['catID']){
        $imagelib= $spawimg['value'];
        $imagetype= $spawimg['storetype'];
        $value_found=true;
        break;
        }

}
if (!$value_found || empty($lib))
{
  $imglib = $spaw_imglibs[0]['value'];
  $lib = $spaw_imglibs[0]['catID'];
  $imagetype= $spawimg['storetype'];
}

$lib_options = liboptions($spaw_imglibs,'',$lib);


$img = $HTTP_POST_VARS['imglist'];

$preview = '';

$errors = array();
if ($HTTP_POST_FILES['img_file']['size']>0)
{
  if ($img = uploadImg('img_file'))
  {
    $preview = $spaw_base_url.$imglib.$img;
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
		//+document.libbrowser.lib.options[document.libbrowser.lib.selectedIndex].value 
        window.returnValue = '<?php if ($imagetype == "file") { echo XOOPS_URL.'/uploads/'; } else { echo XOOPS_URL.'/image.php?id='; }?>'+document.libbrowser.imglist.options[document.libbrowser.imglist.selectedIndex].value;
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
/*
    if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
      $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
    else
      $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];
*/
	$_root = XOOPS_ROOT_PATH.'/';    
    $d = @dir($_root.$imglib);
  ?>
  <select name="imglist" size="15" class="input" style="width: 150px;" 
    onchange="if (this.selectedIndex &gt;=0) imgpreview.location.href = '<?php if ($imagetype == "file") { echo XOOPS_URL.'/uploads/'; } else { echo XOOPS_URL.'/image.php?id='; }?>' + this.options[this.selectedIndex].value;" ondblclick="selectClick();">
  <?php 
	  global $xoopsDB;

	  if ($imagetype == "file") {
		  $result = $xoopsDB->query("SELECT image_name,image_nicename FROM ".$xoopsDB->prefix(image)." WHERE imgcat_id = $lib");
		  while($image = $xoopsDB->fetcharray($result)){
		  ?>
		  
		  <option value="<?php echo $image["image_name"]?>" <?php echo ($image["image_name"] == $img)?'selected':''?>><?php echo $image["image_nicename"]?></option>
          <?php 
		  }
	  } else {
		  $result = $xoopsDB->query("SELECT image_id, image_name,image_nicename FROM ".$xoopsDB->prefix(image)." WHERE imgcat_id = $lib");
		  while($image = $xoopsDB->fetcharray($result)){
		  ?>
		  
		  <option value="<?php echo $image["image_id"]?>" <?php echo ($image["image_id"] == $img)?'selected':''?>><?php echo $image["image_nicename"]?></option>
          <?php 
		  }
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
    $buf .= '<option value="'.$lib['catID'].'"'.(($lib['catID'] == $sel)?' selected':'').'>'.$prefix.$lib['text'].'</option>'."\n";
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

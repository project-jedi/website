<?
header("Expires: ".gmdate("D, d M Y H:i:s",time()+(3600*24))." GMT");
$url=explode("/",$PATH_INFO);
$group=$url[1];
$id=$url[2];
$attachment=$url[3];
include "config.inc";
require("$file_newsportal");
if (!isset($attachment))
  $attachment=0;
$message=read_message($id,$attachment,$group);
if (!$message) {
  header ("HTTP/1.0 404 Not Found");
  echo "The Attachment doesn't exists";
} else {
  header("Content-type: ".$message->header->content_type[$attachment]);
  show_article("",$id,$attachment,$message);
}
?>

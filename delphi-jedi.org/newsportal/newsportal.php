<?
/*
          Newsportal NNTP<->HTTP Gateway 
 
 Version: 0.24pre6

 this Script is licensed under the GNU Public License

 Author: Florian Amrhein
 eMail: florian.amrhein@gmx.de
 Homepage: http://florian-amrhein.de
*/

/*
 * the name and the description of a newsgroup
 */
class newsgroupType {
  var $name;
  var $description;
  var $count;
}

/*
 * Stores a complete article:
 * - The parsed Header as an headerType
 * - The bodies and attachments as an array of array of lines
 */
class messageType {
  var $header;
  var $body;
}



/*
 * Stores the Header of an article
 */
class headerType {
  var $number; // the Number of an article inside a group
  var $id;     // Message-ID
  var $from;   // eMail of the author
  var $name;   // Name of the author
  var $subject; // the subject
  var $newsgroups;  // the Newsgroups where the article belongs to
  var $followup;
  var $date;
  var $organization;
  var $references;
  var $content_transfer_encoding;
  var $mime_version;
  var $content_type;   // array, Content-Type of the Body (Index=0) and the
                       // Attachments (Index>0)
  var $content_type_charset;  // like content_type
  var $content_type_name;     // array of the names of the attachments
  var $content_type_boundary; // The boundary of an multipart-article.
  var $answers;
  var $isAnswer;
  var $username;
  var $user_agent;
  var $isReply;
}

/*
 * opens the connection to the NNTP-Server
 *
 * $server: adress of the NNTP-Server
 * $port: port of the server
 */
function OpenNNTPconnection($nserver=0,$nport=0) {
  global $text_error,$server_auth_user,$server_auth_pass,$readonly;
  global $server,$port;
  $authorize=((isset($server_auth_user)) && (isset($server_auth_pass)) &&
              ($server_auth_user != ""));
  if ($nserver==0) $nserver=$server;
  if ($nport==0) $nport=$port;
  $ns=fsockopen($nserver,$nport);
  $weg=lieszeile($ns);  // kill the first line
  if (substr($weg,0,2) != "20") {
    echo '<p><font color="red">'.$text_error["error:"].$weg."</font></p>";
  } else {
    if ($ns != false) {
      fputs($ns,"mode reader\r\n");
      $weg=lieszeile($ns);  // and once more
      if ((substr($weg,0,2) != "20") && 
          ((!$authorize) || ((substr($weg,0,3) != "480") && ($authorize)))) {
        echo '<p><font color="red">'.$text_error["error:"].$weg."</font></p>";
      }
    }
    if ((isset($server_auth_user)) && (isset($server_auth_pass)) &&
        ($server_auth_user != "")) {
      fputs($ns,"authinfo user $server_auth_user\r\n");
      $weg=lieszeile($ns);
      fputs($ns,"authinfo pass $server_auth_pass\r\n"); 
      $weg=lieszeile($ns);
      if (substr($weg,0,3) != "281") {
        echo '<p><font color="red">'.$text_error["error:"]."</font></p>";
        echo "<p>".$text_error["auth_error"]."</p>";
      }
    }
  }
  if ($ns==false) echo "<p>".$text_error["connection_failed"]."</p>";
  return $ns;
}

/*
 * Close a NNTP connection
 *
 * $ns: the handle of the connection
 */
function closeNNTPconnection(&$ns) {
  usleep(300000);
  if ($ns != false) {
    fputs($ns,"quit\r\n");
    fclose($ns);
  }
}

/*
 * Validates an email adress
 *
 * $address: a string containing the email-address to be validated
 *
 * returns true if the address passes the tests, false otherwise.
 */
function validate_email($address)
{
  global $validate_email;
  $return=true;
  if (($validate_email >= 1) && ($return == true))
    $return = (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_A-z{|}~]+'.'@'.
               '[-!#$%&\'*+\\/0-9=?A-Z^_A-z{|}~]+\.'.
               '[-!#$%&\'*+\\./0-9=?A-Z^_A-z{|}~]+$',$address));
  if (($validate_email >= 2) && ($return == true)) {
    $addressarray=address_decode($address,"garantiertungueltig");
    $return=checkdnsrr($addressarray[0]["host"],"MX");
    if (!$return) $return=checkdnsrr($addressarray[0]["host"],"A");
  }
  return($return);
}

/*
 * decodes a block of 7bit-data in uuencoded format to it's original
 * 8bit format.
 * The headerline containing filename and permissions doesn't have to
 * be included.
 * 
 * $data: The uuencoded data as a string
 *
 * returns the 8bit data as a string
 *
 * Note: this function is very slow and doesn't recognize incorrect code.
 */
function uudecode_line($line) {
  $data=substr($line,1);
  $length=ord($line[0])-32;
  $decoded="";
  for ($i=0; $i<(strlen($data)>>2); $i++) {
    $pack=substr($data,$i<<2,4);
    $upack="";
    $bitmaske=0;
    for ($o=0; $o<4; $o++) {
      $g=((ord($pack[3-$o])-32));
      if ($g==64) $g=0;
      $bitmaske=$bitmaske | ($g << (6*$o));
    }
    $schablone=255;
    for ($o=0; $o<3; $o++) {
      $c=($bitmaske & $schablone) >> ($o << 3);
      $schablone=($schablone << 8);
      $upack=chr($c).$upack;
    }
    $decoded.=$upack;
  }
  $decoded=substr($decoded,0,$length);
  return $decoded;
}
function uudecode($data) {
  $d=explode("\n",$data);
  $u="";
  for ($i=0; $i<count($d)-1; $i++)
    $u.=uudecode_line($d[$i]);
  return $u;
}

/*
 * returns the mimetype of an filename
 *
 * $name: the complete filename of a file
 *
 * returns a string containing the mimetype
 */
function get_mimetype_by_filename($name) {
  $ending=strtolower(strrchr($name,"."));
  switch($ending) {
    case ".jpg":
      $type="image/jpeg";
      break;
    case ".gif":
      $type="image/gif";
      break;
    default:
      $type="text/plain";
  }
  return $type;
}

function showPageSelectMenu($group,$article_count,$first) {
  global $articles_per_page,$file_thread,$file_framethread,$name;
  if (isset($file_framethread)) {
    $thread=$file_framethread;
  } else {
    $thread=$file_thread;
  }

  $pages=ceil($article_count / $articles_per_page);
  if ($article_count > $articles_per_page)
    for ($i = 0; $i < $pages; $i++) {
      echo '[';
      if ($first != $i*$articles_per_page+1)
        echo '<a href="'.$thread.'?group='.$group.
             '&amp;first='.($i*$articles_per_page+1).'&amp;last='.
             ($i+1)*$articles_per_page.'">';
      echo ($i*$articles_per_page+1).'-';
      if ($i == $pages-1) {
        echo $article_count;
      } else {
        echo ($i+1)*$articles_per_page;
      }
      if ($first != $i*$articles_per_page+1)
        echo '</a>';
      echo '] ';
    }
}

function testGroup($groupname) {
  global $testgroup;
  if ($testgroup) {
    $gf=fopen("groups.txt","r");
    while (!feof($gf)) {
      $read=trim(lieszeile($gf));
      $pos=strpos($read," ");
      if ($pos != false) {
        if (substr($read,0,$pos)==trim($groupname)) return true;
      } else {
        if ($read == trim($groupname)) return true;
      }
    }
    fclose($gf);
    return false;
  } else {
    return true;
  }
}

function testGroups($newsgroups) {
  $groups=explode(",",$newsgroups);
  $count=count($groups);
  $return="";
  $o=0;
  for ($i=0; $i<$count; $i++) {
    if (testgroup($groups[$i])) {
      if ($o>0) $return.=",";
      $o++;
      $return.=$groups[$i];
    }
  }
  return($return);
}

/*
 * read one line from the NNTP-server
 */
function lieszeile(&$ns) {
  if ($ns != false) {
    $t=str_replace("\n","",str_replace("\r","",fgets($ns,1200)));
    return $t;
  }
}

/*
 * Split an internet-address string into its parts. An address string could
 * be for example:
 * - user@host.domain (Realname)
 * - "Realname" <user@host.domain>
 * - user@host.domain
 *
 * The address will be split into user, host (incl. domain) and realname
 *
 * $adrstring: The string containing the address in internet format
 * $defaulthost: The name of the host which should be returned if the
 *               address-string doesn't contain a hostname.
 *
 * returns an hash containing the fields "mailbox", "host" and "personal"
 */
function address_decode($adrstring,$defaulthost) {
  $parsestring=trim($adrstring);
  $len=strlen($parsestring);
  $at_pos=strpos($parsestring,'@');     // find @
  $ka_pos=strpos($parsestring,"(");     // find (
  $kz_pos=strpos($parsestring,')');     // find )
  $ha_pos=strpos($parsestring,'<');     // find <
  $hz_pos=strpos($parsestring,'>');     // find >
  $space_pos=strpos($parsestring,')');  // find ' '
  $email="";
  $mailbox="";
  $host="";
  $personal="";
  if ($space_pos != false) {
    if (($ka_pos != false) && ($kz_pos != false)) {
      $personal=substr($parsestring,$ka_pos+1,$kz_pos-$ka_pos-1);
      $email=trim(substr($parsestring,0,$ka_pos-1));
    }
  } else {
    $email=$adrstring;
  }
  if (($ha_pos != false) && ($hz_pos != false)) {
    $email=trim(substr($parsestring,$ha_pos+1,$hz_pos-$ha_pos-1));
    $personal=substr($parsestring,0,$ha_pos-1);
  }
  if ($at_pos != false) {
    $mailbox=substr($email,0,strpos($email,'@'));
    $host=substr($email,strpos($email,'@')+1);
  } else {
    $mailbox=$email;
    $host=$defaulthost;
  }
  $personal=trim($personal);
  if (substr($personal,0,1) == '"') $personal=substr($personal,1);
  if (substr($personal,strlen($personal)-1,1) == '"')
    $personal=substr($personal,0,strlen($personal)-1);
  $result["mailbox"]=trim($mailbox);
  $result["host"]=trim($host);
  if ($personal!="") $result["personal"]=$personal;
  $complete[]=$result;
  return ($complete);
}



function readGroups($server,$port) {
  $ns=OpenNNTPconnection($server,$port);
  if ($ns == false) return false;
  $gf=fopen("groups.txt","r");
  while (!feof($gf)) {
    $gruppe=new newsgroupType;
    $tmp=trim(lieszeile($gf));
    $pos=strpos($tmp," ");
    if ($pos != false) {
      $gruppe->name=substr($tmp,0,$pos);
      $desc=substr($tmp,$pos);
    } else {
      $gruppe->name=$tmp;
      fputs($ns,"xgtitle $gruppe->name\r\n");
      $response=liesZeile($ns);
      if (strcmp(substr($response,0,3),"282") == 0) {
        $neu=liesZeile($ns);
        do {
          $response=$neu;
          if ($neu != ".") $neu=liesZeile($ns);
        } while ($neu != ".");
        $desc=strrchr($response,"\t");
        if (strcmp($response,".") == 0) {
          $desc="-";
        }
      } else {
        $desc=$response;
      }
      if (strcmp(substr($response,0,3),"500") == 0)
        $desc="-";
    }
    if (strcmp($desc,"") == 0) $desc="-";
    $gruppe->description=$desc;
    fputs($ns,"group ".$gruppe->name."\r\n"); 
    $response=liesZeile($ns);
    $t=strchr($response," ");
    $t=substr($t,1,strlen($t)-1);
    $gruppe->count=substr($t,0,strpos($t," "));
    if ((strcmp(trim($gruppe->name),"") != 0) &&
        (substr($gruppe->name,0,1) != "#"))
      $newsgroups[]=$gruppe;
  }
  fclose($gf);
  closeNNTPconnection($ns);
  return $newsgroups;
}

/*
 * print the group names from an array to the webpage
 */
function showgroups($gruppen) {
  if ($gruppen == false) return;
  global $file_thread,$text_groups;
  $c = count($gruppen);
  echo "<table>\n";
  echo "<tr><td>#</td><td><b>".$text_groups["newsgroup"].
       "</b></td><td><b>".$text_groups["description"]."</b></td></tr>\n";
  for($i = 0 ; $i < $c ; $i++) {
    $g = $gruppen[$i];
    echo "<tr><td>";
    echo "$g->count</td><td>";
    echo '<a ';
    if ((isset($frame_threadframeset)) && ($frame_threadframeset != ""))
      echo 'target="'.$frame_threadframeset.'" ';
    echo 'href="'.$file_thread.'?group='.urlencode($g->name).'">'.$g->name."</a></td>\n";
    echo "<td>$g->description</td></tr>\n";
    flush();
  }
  echo "</table>\n";
}

/*
 * gets a list of aviable articles in the group $groupname
 */
function getArticleList(&$von,$groupname) {
  fputs($von,"listgroup $groupname \r\n");
  $zeile=lieszeile($von);
  $zeile=lieszeile($von);
  while(strcmp($zeile,".") != 0) {
    $articleList[] = trim($zeile);
    $zeile=lieszeile($von);
  }
  if (!isset($articleList)) $articleList="-";
  return $articleList;
}

/*
 * Decode quoted-printable or base64 encoded headerlines
 *
 * $value: The to be decoded line
 *
 * returns the decoded line
 */
function headerDecode($value) {
  if (eregi('=\?.*\?.\?.*\?=',$value)) { // is there anything encoded?
    if (eregi('=\?.*\?Q\?.*\?=',$value)) {  // quoted-printable decoding
      $result1=eregi_replace('(.*)=\?.*\?Q\?(.*)\?=(.*)','\1',$value);
      $result2=eregi_replace('(.*)=\?.*\?Q\?(.*)\?=(.*)','\2',$value);
      $result3=eregi_replace('(.*)=\?.*\?Q\?(.*)\?=(.*)','\3',$value);
      $result2=str_replace("_"," ",quoted_printable_decode($result2));
      $newvalue=$result1.$result2.$result3;
    }
    if (eregi('=\?.*\?B\?.*\?=',$value)) {  // base64 decoding
      $result1=eregi_replace('(.*)=\?.*\?B\?(.*)\?=(.*)','\1',$value);
      $result2=eregi_replace('(.*)=\?.*\?B\?(.*)\?=(.*)','\2',$value);
      $result3=eregi_replace('(.*)=\?.*\?B\?(.*)\?=(.*)','\3',$value);
      $result2=base64_decode($result2);
      $newvalue=$result1.$result2.$result3;
    }
    if (!isset($newvalue)) // nothig of them, must be an unknown encoding...
      $newvalue=$value;
    else
      $newvalue=headerDecode($newvalue);  // maybe there are more encoded
    return($newvalue);                    // parts
  } else {   // there wasn't anything encoded, return the original string
    return($value);
  }
}

function getTimestamp($value) {
  $months=array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12);
  $value=str_replace("  "," ",$value);
  $d=split(" ",$value,5);
  if (strcmp(substr($d[0],strlen($d[0])-1,1),",") == 0) {
    $date[0]=$d[1];  // day
    $date[1]=$d[2];  // month
    $date[2]=$d[3];  // year
    $date[3]=$d[4];  // hours:minutes:seconds
  } else {
    $date[0]=$d[0];  // day
    $date[1]=$d[1];  // month
    $date[2]=$d[2];  // year
    $date[3]=$d[3];  // hours:minutes:seconds
  }
  $time=split(":",$date[3]);
  date_default_timezone_set("Europe/Paris");
  $timestamp=mktime($time[0],$time[1],$time[2],$months[$date[1]],$date[0],$date[2]);
  return $timestamp;
}

function parse_header($hdr,$number="") {
  for ($i=count($hdr)-1; $i>0; $i--)
    if (preg_match("/^(\x09|\x20)/",$hdr[$i]))
      $hdr[$i-1]=$hdr[$i-1]." ".ltrim($hdr[$i]);
  $header = new headerType;
  $header->isAnswer=false;
  for ($count=0;$count<count($hdr);$count++) {
    $variable=substr($hdr[$count],0,strpos($hdr[$count]," "));
    $value=trim(substr($hdr[$count],strpos($hdr[$count]," ")+1));
      switch (strtolower($variable)) {
        case "from:": 
          $fromline=address_decode(headerDecode($value),"nirgendwo");
          if (!isset($fromline[0]["host"])) $fromline[0]["host"]="";
          $header->from=$fromline[0]["mailbox"]."@".$fromline[0]["host"];
          $header->username=$fromline[0]["mailbox"];
          if (!isset($fromline[0]["personal"])) {
            $header->name="";
          } else {
            $header->name=$fromline[0]["personal"];
          }
          break;
        case "message-id:":
          $header->id=$value;
          break;
        case "subject:":
          $header->subject=headerDecode($value);
          break;
        case "newsgroups:":
          $header->newsgroups=$value;
          break;
        case "organization:":
          $header->organization=$value;
          break;
        case "content-transfer-encoding:":
          $header->content_transfer_encoding=trim(strtolower($value));
          break; 
        case "content-type:":
          $header->content_type=array();
          $subheader=split(";",$value);
          $header->content_type[0]=strtolower(trim($subheader[0]));
          for ($i=1; $i<count($subheader); $i++) {
            $gleichpos=strpos($subheader[$i],"=");
            if ($gleichpos) {
              $subvariable=trim(substr($subheader[$i],0,$gleichpos));
              $subvalue=trim(substr($subheader[$i],$gleichpos+1));
              if (($subvalue[0]=='"') &&
                  ($subvalue[strlen($subvalue)-1]=='"'))
                $subvalue=substr($subvalue,1,strlen($subvalue)-2);
              switch($subvariable) {
                case "charset":
                  $header->content_type_charset=array(strtolower($subvalue));
                  break;
                case "name":
                  $header->content_type_name=array($subvalue);
                  break;
                case "boundary":
                  $header->content_type_boundary=$subvalue;
              }
            }
          }
          break;
        case "references:":
          $ref=trim($value);
          while (strpos($ref,"> <") != false) {
            $header->references[]=substr($ref,0,strpos($ref," "));
            $ref=substr($ref,strpos($ref,"> <")+2);
          }
          $header->references[]=trim($ref);
          break;
        case "date:":
          $header->date=getTimestamp(trim($value));
          break;
        case "followup-to:":
          $header->followup=trim($value);
          break;
        case "x-newsreader:":
        case "x-mailer:":
        case "user-agent:":
          $header->user_agent=trim($value);
          break;
        case "x-face:": // not ready
//          echo "<p>-".base64_decode($value)."-</p>";
          break;
      }
  }
  if (!isset($header->content_type[0]))
    $header->content_type[0]="text/plain";
  if (!isset($header->content_transfer_encoding))
    $header->content_transfer_encoding="8bit";
  if ($number != "") $header->number=$number;
  return $header;
}

function decode_body($body,$encoding) {
  $bodyzeile="";
  switch ($encoding) {
    case "base64":
      $body=base64_decode($body);
      break;
    case "quoted-printable":
      $body=Quoted_printable_decode($body);
      $body=str_replace("=\n","",$body);
//    default:
//      $body=str_replace("\n..\n","\n.\n",$body);
  }
  return $body;
}

function parse_message($rawmessage) {
  global $attachment_delete_alternative,$attachment_uudecode;
  // Read the header of the message:
  $count_rawmessage=count($rawmessage);
  $message = new messageType;
  $rawheader=array();
  $i=0;
  while ($rawmessage[$i] != "") {
    $rawheader[]=$rawmessage[$i];
    $i++;
  }
  // Parse the Header:
  $message->header=parse_header($rawheader);
  // Now we know if the message is a mime-multipart message:
  $content_type=split("/",$message->header->content_type[0]);
  if ($content_type[0]=="multipart") {
    $message->header->content_type=array();
    // We have multible bodies, so we split the message into its parts
    $boundary="--".$message->header->content_type_boundary;
    // lets find the first part
    while($rawmessage[$i] != $boundary)
      $i++;
    $i++;
    $part=array();
    while($i<=$count_rawmessage) {
      if (($rawmessage[$i]==$boundary) || ($i==$count_rawmessage-1) ||
          ($rawmessage[$i]==$boundary.'--')) {
        $partmessage=parse_message($part);
        // merge the content-types of the message with those of the part
        for ($o=0; $o<count($partmessage->header->content_type); $o++) {
          $message->header->content_type[]=
            $partmessage->header->content_type[$o];
          $message->header->content_type_charset[]=
            $partmessage->header->content_type_charset[$o];
          $message->header->content_type_name[]=
            $partmessage->header->content_type_name[$o];
          $message->body[]=$partmessage->body[$o];
        }
        $part=array();
      } else {
        if ($i<$count_rawmessage)
          $part[]=$rawmessage[$i];
      }
      if ($rawmessage[$i]==$boundary.'--') break;
      $i++;
    }
    // Is this a multipart/alternative multipart-message? Do we have to
    // delete all non plain/text parts?
    if (($attachment_delete_alternative) &&
        ($content_type[1]=="alternative")) {
      $plaintext=false;
      for ($o=0; $o<count($message->header->content_type); $o++) {
        if ($message->header->content_type[$o]=="text/plain")
          $plaintext=true; // we found at least one text/plain
      }
      if ($plaintext) {    // now we can delete the other parts
        for ($o=0; $o<count($message->header->content_type); $o++) {
          if ($message->header->content_type[$o]!="text/plain") {
            unset($message->header->content_type[$o]);
            unset($message->header->content_type_name[$o]);
            unset($message->header->content_type_charset[$o]);
            unset($message->body[$o]);
          }
        }
      }
    }
  } else {
    // No mime-attachments in the message:
    $body="";
    $uueatt=0; // as default we have no uuencoded attachments
    for($i++;$i<$count_rawmessage; $i++) {
      // do we have an inlay uuencoded file?
      if ((strtolower(substr($rawmessage[$i],0,5))!="begin") ||
          ($attachment_uudecode==false)) {
        $body.=$rawmessage[$i]."\n";
      // yes, it seems, we have!
      } else {
        $old_i=$i;
        $uue_infoline_raw=$rawmessage[$i];
        $uue_infoline=explode(" ",$uue_infoline_raw);
        $uue_data="";
        $i++;
        while($rawmessage[$i]!="end") {
          if (strlen(trim($rawmessage[$i])) > 2)
            $uue_data.=$rawmessage[$i]."\n";
          $i++;
        }
        // now write the data in an attachment
        $uueatt++;
        $message->body[$uueatt]=uudecode($uue_data);
        $message->header->content_type_name[$uueatt]="";
        for ($o=2; $o<count($uue_infoline); $o++)
          $message->header->content_type_name[$uueatt].=$uue_infoline[$o];
        $message->header->content_type[$uueatt]=
          get_mimetype_by_filename($message->header->content_type_name[$uueatt]);
      }
    }
    if ($message->header->content_type[0]=="text/plain") {
      $body=trim($body);
      if ($body=="") $body=" ";
    }
    $body=decode_body($body,$message->header->content_transfer_encoding);
    $message->body[0]=$body;
  }
    if (!isset($message->header->content_type_charset))
      $message->header->content_type_charset=array("ISO-8859-1");
    if (!isset($message->header->content_type_name))
      $message->header->content_type_name=array("unnamed");
  for ($o=0; $o<count($message->body); $o++) {
    if (!isset($message->header->content_type_charset[$o]))
      $message->header->content_type_charset[$o]="ISO-8859-1";
    if (!isset($message->header->content_type_name[$o]))
      $message->header->content_type_name[$o]="unnamed";
  }
  return $message;
}

/*
 * read an article from the newsserver or the spool-directory
 *
 * $id: the Message-ID of an article
 * $bodynum: the number of the attachment:
 *          -1: return only the header without any bodies or attachments.
 *           0: the body
 *           1: the first attachment...
 *
 * The function returns an article as an messageType or false if the article
 * doesn't exists on the newsserver or doesn't contain the given
 * attachment.
 */
function read_message($id,$bodynum=0,$group="") {
  global $cache_articles,$spooldir,$text_error;
  if (!testGroup($group)) {
    echo "<p>".$text_error["read_access_denied"]."</p>";
    return;
  }
  $message = new messageType;
  if ((isset($cache_articles)) && ($cache_articles == true)) {
    if ((ereg('^[0-9]+$',$id)) && ($group != ''))
      $filename=$group.'_'.$id;
    else
      $filename=base64_encode($id);
    $cachefilename_header=$spooldir."/".$filename.'.header';
    $cachefilename_body=$spooldir."/".$filename.'.body';
    if (file_exists($cachefilename_header)) {
      $cachefile=fopen($cachefilename_header,"r");
      $message->header=unserialize(fread($cachefile,filesize($cachefilename_header)));
      fclose($cachefile);
    } else {
      unset($message->header);
    }
    // Is a non-existing attachment of an article requested?
    if ((isset($message->header)) &&
        ($bodynum!= -1) &&
        (!isset($message->header->content_type[$bodynum])))
      return false;
    if ((file_exists($cachefilename_body.$bodynum)) &&
        ($bodynum != -1)) {
      $cachefile=fopen($cachefilename_body.$bodynum,"r");
      $message->body[$bodynum]=
        fread($cachefile,filesize($cachefilename_body.$bodynum));
      fclose($cachefile);
    }
  }
  if ((!isset($message->header)) ||
      ((!isset($message->body[$bodynum])) &&
       ($bodynum != -1))) {
    if (!isset($ns)) $ns=openNNTPconnection();
    if ($group != "") {
      fputs($ns,"group ".$group."\r\n");
      $zeile=lieszeile($ns);
    }
    fputs($ns,'article '.$id."\r\n");
    $zeile=lieszeile($ns);
    if (substr($zeile,0,3) != "220")
      return false;
    $rawmessage=array();
    $line=lieszeile($ns);
    while(strcmp($line,".") != 0) {
      $rawmessage[]=$line;
      $line=lieszeile($ns);
    }
    $message=parse_message($rawmessage);
    if (ereg('^[0-9]+$',$id)) $message->header->number=$id;
    // write header, body and attachments to disk
    if ((isset($cache_articles)) && ($cache_articles == true)) {
      $cachefile=fopen($cachefilename_header,"w");
      if ($cachefile) {
        fputs($cachefile,serialize($message->header));
      }
      fclose($cachefile);
      for ($i=0; $i<count($message->header->content_type); $i++) {
        if (isset($message->body[$i])) {
          $cachefile=fopen($cachefilename_body.$i,"w");
          fwrite($cachefile,$message->body[$i]);
          fclose($cachefile);
        }
      }
    }
  }
  return $message;
}

function textwrap($text, $wrap=80, $break="\n"){
  $len = strlen($text);
  if ($len > $wrap) {
    $h = '';        // massaged text
    $lastWhite = 0; // position of last whitespace char
    $lastChar = 0;  // position of last char
    $lastBreak = 0; // position of last break
    // while there is text to process
    while ($lastChar < $len) {
      $char = substr($text, $lastChar, 1); // get the next character
      // if we are beyond the wrap boundry and there is a place to break
      if (($lastChar - $lastBreak > $wrap) && ($lastWhite > $lastBreak)) {
        $h .= substr($text, $lastBreak, ($lastWhite - $lastBreak)) . $break;
        $lastChar = $lastWhite + 1;
        $lastBreak = $lastChar;
      }
      // You may wish to include other characters as valid whitespace...
      if ($char == ' ' || $char == chr(13) || $char == chr(10)) {
        $lastWhite = $lastChar; // note the position of the last whitespace
      }
      $lastChar = $lastChar + 1; // advance the last character position by one
    }
    $h .= substr($text, $lastBreak); // build line
  } else {
    $h = $text; // in this case everything can fit on one line
  }
  return $h;
}

/*
 * makes URLs clickable
 *
 * $comment: A text-line probably containing links.
 *
 * the function returns the text-line with HTML-Links to the links or
 * email-adresses.
 */
function html_parse($comment) {
  global $frame_externallink;
  if ((isset($frame_externallink)) && ($frame_externallink != "")) { 
    $target=' TARGET="'.$frame_externallink.'" ';
  } else {
    $target=' ';
  }
  $ncomment = eregi_replace( 'http://([-a-z0-9_./~@?=%#&;]+)', '<a'.$target.'href="http://\1">http://\1</a>', $comment);
  if ($ncomment == $comment)
    $ncomment = eregi_replace( '(www\.[-a-z]+\.(de|int|eu|dk|org|net|at|ch|com))','<a'.$target.'href="http://\1">\1</a>',$comment);
  $comment=$ncomment;
  $comment = eregi_replace( 'https://([-a-z0-9_./~@?=%#&;\n]+)', '<a'.$target.'href="https://\1">https://\1</a>', $comment);
  $comment = eregi_replace( 'gopher://([-a-z0-9_./~@?=%\n]+)','<a'.$target.'href="gopher://\1">gopher://\1</a>', $comment);
  $comment = eregi_replace( 'news://([-a-z0-9_./~@?=%\n]+)','<a'.$target.'href="news://\1">news://\1</a>', $comment);
  $comment = eregi_replace( 'ftp://([-a-z0-9_./~@?=%\n]+)', '<a'.$target.'href="ftp://\1">ftp://\1</a>', $comment);
  $comment = eregi_replace( '([-a-z0-9_./n]+)@([-a-z0-9_.]+)','<a href="mailto:\1@\2">\1@\2</a>', $comment);
  return($comment);
}




/*
 * read the header of an article in plaintext into an array
 * $articleNumber can be the number of an article or its message-id.
 */
function readPlainHeader(&$von,$group,$articleNumber) {
  fputs($von,"group $group\r\n");
  $zeile=lieszeile($von);
  fputs($von,"head $articleNumber\r\n");
  $zeile=lieszeile($von);
  if (substr($zeile,0,3) != "221") {
    echo "<p>".$text_error["article_not_found"]."</p>";
    $header=false;
  } else {
    $zeile=lieszeile($von);
    $body="";
    while(strcmp(trim($zeile),".") != 0) {
      $body .= $zeile."\n";
      $zeile=lieszeile($von);
    }
    return split("\n",str_replace("\r\n","\n",$body));
  }
}

function readArticles(&$von,$groupname,$articleList) {
  for($i = 0; $i <= count($articleList)-1 ; $i++) {
    $temp=read_header($von,$articleList[$i]);
    $articles[$temp->id] = $temp;
  }
  return $articles;
}

/*
 * Remove re:, aw: etc. from a subject.
 *
 * $subject: a string containing the complete Subject
 *
 * The function removes the re:, aw: etc. from $subject end returns true
 * if it removed anything, and false if not.
 */
function splitSubject(&$subject) {
  $s=eregi_replace('^(aw:|re:|re\[2\]:| )+','',$subject);
  $return=($s != $subject);
  $subject=$s;
  return $return;
}

function interpretOverviewLine($zeile,$overviewformat,$groupname) {
  $return="";
  $overviewfmt=explode("\t",$overviewformat);
  echo " ";                // keep the connection to the webbrowser alive
  flush();                 // while generating the message-tree
  $over=split("\t",$zeile,count($overviewfmt)-1);
  $article=new headerType;
  for ($i=0; $i<count($overviewfmt)-1; $i++) {
    if ($overviewfmt[$i]=="Subject:") {
      $subject=headerDecode($over[$i+1]);
      $article->isReply=splitSubject($subject);
      $article->subject=$subject;
    }
    if ($overviewfmt[$i]=="Date:") {
      $article->date=getTimestamp($over[$i+1]);
    }
    if ($overviewfmt[$i]=="From:") {
      $fromline=address_decode(headerDecode($over[$i+1]),"nirgendwo");
      $article->from=$fromline[0]["mailbox"]."@".$fromline[0]["host"];
      $article->username=$fromline[0]["mailbox"];
      if (!isset($fromline[0]["personal"])) {
        $article->name=$fromline[0]["mailbox"];
        if (strpos($article->name,'%')) {
          $article->name=substr($article->name,0,strpos($article->name,'%'));
        }
        $article->name=strtr($article->name,'_',' ');
      } else {
        $article->name=$fromline[0]["personal"];
      }
    }
    if ($overviewfmt[$i]=="Message-ID:") $article->id=$over[$i+1];
    if (($overviewfmt[$i]=="References:") && ($over[$i+1] != ""))
      $article->references=explode(" ",$over[$i+1]);
  }
  $article->number=$over[0];
  $article->isAnswer=false;
  return($article);
}

/*
 * Rebuild the Overview-File 
 */
function rebuildOverview(&$von,$groupname,$poll) {
  global $spooldir,$maxarticles,$maxfetch,$initialfetch,$maxarticles_extra;
  global $text_error,$text_thread,$compress_spoolfiles,$server;
  $idstring="0.22,".$server.",".$compress_spoolfiles.",".$maxarticles.",".
            $maxarticles_extra.",".$maxfetch.",".$initialfetch;
  fputs($von,"list overview.fmt\r\n");  // find out the format of the
  $tmp=liesZeile($von);                 // xover-command
  $zeile=liesZeile($von);
  while (strcmp($zeile,".") != 0) {
    $overviewfmt[]=$zeile;
    $zeile=liesZeile($von);
  }
  $overviewformat=implode("\t",$overviewfmt);
  $spoolfilename=$spooldir."/".$groupname."-data.dat";
  fputs($von,"group $groupname\r\n");   // select a group
  $groupinfo=explode(" ",liesZeile($von));
  if (substr($groupinfo[0],0,1) != 2) {
    echo '<p><font color="red">'.$text_error["error:"]."</font></p>";
    echo "<p>".$text_thread["no_such_group"]."</p>";
    flush();
  } else {
    $infofilename=$spooldir."/".$groupname."-info.txt";
    $spoolopenmodus="n";
    if (!((file_exists($infofilename)) && (file_exists($spoolfilename)))) {
      $spoolopenmodus="w";
    } else {
      $infofile=fopen($infofilename,"r");
      $oldid=fgets($infofile,100);
      if (trim($oldid) != $idstring) {
        echo "<!-- Database Error, rebuilding Database...-->\n";
        $spoolopenmodus="w";
      }
      $oldgroupinfo=explode(" ",trim(fgets($infofile,200)));
      fclose($infofile);
      if ($groupinfo[3] < $oldgroupinfo[1]) {
        $spoolopenmodus="w";
      }
      if ($maxarticles == 0) {
        if ($groupinfo[2] != $oldgroupinfo[0]) $spoolopenmodus="w";
      } else {
        if ($groupinfo[2] > $oldgroupinfo[0]) $spoolopenmodus="w";
      }
      if (($spoolopenmodus == "n") && ($groupinfo[3] > $oldgroupinfo[1]))
        $spoolopenmodus="a";
    }
    if ($spoolopenmodus=="a") {
      $firstarticle=$oldgroupinfo[1]+1;
      $lastarticle=$groupinfo[3];
    }
    if ($spoolopenmodus=="w") {
      $firstarticle=$groupinfo[2];
      $lastarticle=$groupinfo[3];
    }
    if ($spoolopenmodus != "n") {
      if ($maxarticles != 0) {
        if ($spoolopenmodus == "w") {
          $firstarticle=$lastarticle-$maxarticles+1;
          if ($firstarticle < $groupinfo[2])
            $firstarticle=$groupinfo[2];
        } else {
          if ($lastarticle-$oldgroupinfo[0]+1 > $maxarticles + $maxarticles_extra) {
            $firstarticle=$lastarticle-$maxarticles+1;
            $spoolopenmodus="w";
          }
        }
      }
      if (($maxfetch!=0) && (($lastarticle-$firstarticle+1) > $maxfetch)) {
        if ($spoolopenmodus=="w") {
          $tofetch=($initialfetch != 0) ? $initialfetch : $maxfetch;
          $lastarticle=$firstarticle+$tofetch-1;
        } else {
          $lastarticle=$firstarticle+$maxfetch-1;
        }
      }
    }
    echo "<!--openmodus: ".$spoolopenmodus."-->\n";
    if ($spoolopenmodus != "w") $headers=loadThreadData($groupname);
    if ($spoolopenmodus != "n") {
      fputs($von,"xover ".$firstarticle."-".$lastarticle."\r\n");  // and read the overview
      $tmp=liesZeile($von);
      if (substr($tmp,0,3) == "224") {
        $zeile=liesZeile($von);
        while ($zeile != ".") {
          $article=interpretOverviewLine($zeile,$overviewformat,$groupname);
          $headers[$article->id]=$article;
          if($poll) {
            echo $article->number.", "; flush();
            read_message($article->number,0,$groupname);
          }
          $zeile=lieszeile($von);
        }
      }
      if (file_exists($spoolfilename)) unlink($spoolfilename);
      if ((isset($headers)) && (count($headers)>0)) {
        $infofile=fopen($infofilename,"w");
        if ($spoolopenmodus=="a") $firstarticle=$oldgroupinfo[0];
        fputs($infofile,$idstring."\n");
        fputs($infofile,$firstarticle." ".$lastarticle."\r\n");
        fclose($infofile);
        reset($headers);
        $c=current($headers);      // read one article
        for ($i=0 ; $i<=count($headers)-1 ; $i++) {
          if (($c->isAnswer == false) &&
             (isset($c->references[0]))) {   // is the article an answer to an
                                            // other article?
            $o=count($c->references)-1;
            $ref=$c->references[$o];
            while (($o >= 0) && (!isset($headers[$ref]))) {  // try to find a
              $ref=$c->references[$o];                       // matching article
              $o--;                                          // to the reference
            }
            if ($o >= 0) {          // found a matching article?
              $c->isAnswer=true;    // so the Article is an answer
              $headers[$c->id]=$c;
              $headers[$ref]->answers[]=$c->id;  // the referenced article gets
                                                 // the ID of the article
            }
          }
          $c=next($headers);
        }
        reset($headers);
        saveThreadData($headers,$groupname);
      }
    }
    return((isset($headers)) ? $headers : false);
  }
}  


/*
 * Read the Overview.
 * Format of the overview-file:
 *    message-id
 *    date
 *    subject
 *    author
 *    email
 *    references
 */
  function mycompare($a,$b) {
    global $thread_sorting;
    if ($a->date==$b->date) $r=0;
    $r=($a->date<$b->date) ? -1 : 1;
    return $r*$thread_sorting;
  }
function readOverview(&$von,$groupname,$readmode = 1,$poll=false) {
  global $text_error, $maxarticles;
  global $spooldir,$thread_sorting;
  if (!testGroup($groupname)) {
    echo "<p>".$text_error["read_access_denied"]."</p>";
    return;
  }
  if ($von == false) return false;
  if (($von!=false) && ($readmode > 0)) 
    $articles=rebuildOverview($von,$groupname,$poll);
  if ((isset($articles)) && ($articles)) {
    if (($thread_sorting != 0) && (count($articles)>0))
      uasort($articles,'mycompare');
    return $articles;
  } else {
    return false;
  }
}

function str_change($str,$pos,$char) {
  return(substr($str,0,$pos).$char.substr($str,$pos+1,strlen($str)-$pos));
}

function formatDate($c) {
  global $age_count,$age_time,$age_color;
  $return="";
  $currentTime=time();
  $color="";
  if ($age_count > 0)
  {
    for($t = $age_count; $t >= 1; $t--)
    {
      if ($currentTime - $c->date < $age_time[$t])
      {
        $color = $age_color[$t];
      }
    }
  }
  if ($color != "") $return .= '<font color="'.$color.'">';
  $return .= date("d.m.",$c->date);                 // format the date
  if ($color != "") $return .= '</font>';
  return($return);
}

function calculateTree($newtree,$depth,$num,$liste,$c) {
  if ((isset($c->answers[0])) && (count($c->answers)>0)) {
    $newtree.="*";
  } else {
    if ($depth == 1) {
      $newtree.="o";
    } else {
      $newtree.="-";
    }
  }
  if (($num == count($liste)-1) && ($depth>1)) {
    $newtree=str_change($newtree,$depth-2,"`");
  }
  return($newtree);
}


/*
 * Format the message-tree
 * Zeichen im Baum:
 *  o : leerer Kasten            k1.gif
 *  * : Kasten mit Zeichen drin  k2.gif
 *  i : vertikale Linie          I.gif
 *  - : horizontale Linie        s.gif
 *  + : T-Stueck                 T.gif
 *  ` : Winkel                   L.gif
 */
function formatTreeGraphic($newtree) {
  global $imgdir;
  $return="";
  for ($o=0 ; $o<strlen($newtree) ; $o++) {
    $return .= '<img src="'.$imgdir.'/';
    $k=substr($newtree,$o,1);
    $alt=$k;
    switch ($k) {
      case "o":
        $return .= 'k1.gif';
        break;
      case "*":
        $return .= 'k2.gif';
        break;
      case "i":
        $return .= 'I.gif';
        $alt='|';
        break;
      case "-":
        $return .= 's.gif';
        break;
      case "+":
        $return .= 'T.gif';
        break;
      case "`":
        $return .= 'L.gif';
        break;
      case ".":
        $return .= 'e.gif';
        $alt='&nbsp;';
        break;
      }
    $return .= '" alt="'.$alt.'"';
    if (strcmp($k,".") == 0) $return .=(' width="12" height="9"');
    $return .= ' />';
  }
  return($return);
}

function formatTreeText($tree) {
  $tree=str_replace("i","|",$tree);
  $tree=str_replace(".","&nbsp;",$tree);
  return($tree);
}

function formatSubject($c,$group) {
  if ($c->isReply) {
    $re="Re: ";
  } else {
    $re="";
  }
  global $file_article, $thread_maxSubject, $frame_article;
  $return='<a ';
  if ((isset($frame_article)) && ($frame_article != ""))
    $return .= 'target="'.$frame_article.'" ';
  $return .= 'class="thread" href="'.$file_article.
       '?id='.urlencode($c->number).'&group='.urlencode($group).'">'.
       $re.htmlspecialchars(substr(trim($c->subject),0,$thread_maxSubject))."</a>";
  return($return);
}

function formatAuthor($c) {
  $return = '<a href="mailto:'.trim($c->from).'">';
  if (trim($c->name)!="") { 
    $return .= htmlspecialchars(trim($c->name));
  } else {
    if (isset($c->username)) {
      $s = strpos($c->username,"%");
      if ($s != false) {
        $return .= htmlspecialchars(substr($c->username,0,$s));
      } else {
        $return .= htmlspecialchars($c->username);
      }
    }
  }
  $return .= "</a>";
  return($return);
}

function showThread(&$headers,&$liste,$depth,$tree,$group,$article_first=0,$article_last=0,&$article_count) {
  global $thread_treestyle;
  global $thread_showDate,$thread_showSubject;
  global $thread_showAuthor,$imgdir;
  global $file_article,$thread_maxSubject;
  global $age_count,$age_time,$age_color;
  global $frame_article,$thread_fontPre,$thread_fontPost;
  if ($thread_treestyle==3) echo "\n<UL>\n";
  for ($i = 0 ; $i<count($liste) ; $i++) {
    $c=$headers[$liste[$i]];    // read the first article
    $article_count++; 
    switch ($thread_treestyle) {
      case 4:  // thread
      case 5:  // thread, graphic
      case 6:  // thread, table
      case 7:  // thread, table, graphic
        $newtree=calculateTree($tree,$depth,$i,$liste,$c);
    }
    if (($article_first == 0) ||
        (($article_count >= $article_first) &&
         ($article_count <= $article_last))) {
      switch ($thread_treestyle) {
        case 0: // simple list
          echo $thread_fontPre;
          if ($thread_showDate) echo formatDate($c)." ";
          if ($thread_showSubject) echo formatSubject($c,$group)." ";
          if ($thread_showAuthor) echo "(".formatAuthor($c).")";
          echo $thread_fontPost;
          echo "<br>\n";
          break;
        case 1: // html-auflistung, kein baum
          echo "<li><nobr>".$thread_fontPre;
          if ($thread_showDate)
            echo formatDate($c)." ";
          if ($thread_showSubject)
            echo formatSubject($c,$group)." ";
          if ($thread_showAuthor)
            echo "<i>(".formatAuthor($c).")</i>";
          echo $thread_fontPost."</nobr></li>";
          break;
        case 2:   // table
          echo "<tr>";
          if ($thread_showDate)
            echo "<td>".$thread_fontPre.formatDate($c)." ".$thread_fontPost."</td>";
          if ($thread_showSubject) {
            echo '<td nowrap="nowrap">'.$thread_fontPre.formatSubject($c,$group);
            echo $thread_fontPost."</td>";
          }
          if ($thread_showAuthor) {
            echo "<td></td>";
            echo '<td nowrap="nowrap">'.$thread_fontPre.formatAuthor($c);
            echo $thread_fontPost."</td>";
          }
          echo "</tr>\n";
          break;
        case 3: // html-tree
          echo "<li><nobr>".$thread_fontPre;
          if ($thread_showDate)
            echo formatDate($c)." ";
          if ($thread_showSubject)
            echo formatSubject($c,$group)." ";
          if ($thread_showAuthor)
            echo "<i>(".formatAuthor($c).")</i>";
          echo $thread_fontPost."</nobr>\n";
          break;
        case 4:  // thread
          echo "<nobr><tt>".$thread_fontPre;
          if ($thread_showDate)
            echo formatDate($c)." ";
          /*echo formatTreeText($newtree)." ";*/
          echo formatTreeText($newtree).$thread_fontPost."</tt> ".$thread_fontPre;
          if ($thread_showSubject)
            echo formatSubject($c,$group)." ";
          if ($thread_showAuthor)
            echo "<i>(".formatAuthor($c).")</i>";
          echo $thread_fontPost."</nobr><br>";
          break;
        case 5:  // thread, graphic
          echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr>\n";
          if ($thread_showDate)
            echo '<td nowrap="nowrap">'.$thread_fontPre.formatDate($c)." ".$thread_fontPost."</td>";
          echo "<td>".$thread_fontPre.formatTreeGraphic($newtree).$thread_fontPost."</td>";
          if ($thread_showSubject)
            echo '<td nowrap="nowrap">'.$thread_fontPre."&nbsp;".formatSubject($c,$group)." ";
          if ($thread_showAuthor)
            echo "(".formatAuthor($c).")".$thread_fontPost."</td>";
          echo "</tr></table>";
          break;
        case 6:  // thread, table
          echo "<tr>";
          if ($thread_showDate)
            echo '<td nowrap="nowrap"><tt>'.$thread_fontPre.formatDate($c)." ".$thread_fontPost."</tt></td>";
          /*echo '<td nowrap="nowrap"><tt>'.$thread_fontPre.formatTreeText($newtree)." ";*/
          echo '<td nowrap="nowrap"><tt>'.$thread_fontPre.formatTreeText($newtree)."</tt>";
          if ($thread_showSubject) {
            echo formatSubject($c,$group).$thread_fontPost."</td>";
            echo "<td></td>";
          }
          if ($thread_showAuthor)
            /*echo '<td nowrap="nowrap"><tt>'.$thread_fontPre.formatAuthor($c).$thread_fontPost."</tt></td>";*/
            echo '<td nowrap="nowrap">'.$thread_fontPre.formatAuthor($c).$thread_fontPost."</td>";
          echo "</tr>";
          break;
        case 7:  // thread, table, graphic
          echo "<tr>";
          if ($thread_showDate)
            echo '<td nowrap="nowrap">'.$thread_fontPre.formatDate($c)." ".$thread_fontPost."</td>";
          echo "<td><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
          echo "<td>".formatTreeGraphic($newtree)."</td>";
          if ($thread_showSubject)
            echo '<td nowrap="nowrap">'.$thread_fontPre."&nbsp;".formatSubject($c,$group).$thread_fontPost."</td>";
          echo "</table></td>";
          if ($thread_showSubject) echo "<td></td>";
          if ($thread_showAuthor)
            echo '<td nowrap="nowrap">'.$thread_fontPre.formatAuthor($c).$thread_fontPost."</td>";
          echo "</tr>";
          break;
      }
    }
    if ((isset($c->answers[0])) && (count($c->answers)>0) &&
        ($article_count<=$article_last)) {
      if ($thread_treestyle >= 4) {
        if (substr($newtree,$depth-2,1) == "+")
          $newtree=str_change($newtree,$depth-2,"i");
        $newtree=str_change($newtree,$depth-1,"+");
        $newtree=strtr($newtree,"`",".");
      }
      if (!isset($newtree)) $newtree="";
      showThread($headers,$c->answers,$depth+1,$newtree."",$group,
                 $article_first,$article_last,$article_count);
    }
    flush();
  }
  if ($thread_treestyle==3) echo "</UL>";
}


/*
 * Load a thread from disk
 *
 * $group: name of the newsgroup, is needed to create the filename
 * 
 * the function returns an array of headerType containing the 
 * overview-data of the thread.
 */
function loadThreadData($group) {
  global $spooldir,$compress_spoolfiles;
  $filename=$spooldir."/".$group."-data.dat";
  if ($compress_spoolfiles) {
    $file=gzopen("$spooldir/$group-data.dat","r");
    $headers=unserialize(gzread($file,1000000));
    gzclose($file);
  } else {
    $file=fopen($filename,"r");
    $headers=unserialize(fread($file,filesize($filename)));
    fclose($file);
  }
  return($headers);
}


/*
 * Save the thread to disk
 *
 * $header: is an array of headerType containing all overview-information
 *          of a newsgroup
 * $group: name of the newsgroup, is needed to create the filename
 */
function saveThreadData($headers,$group) {
  global $spooldir,$compress_spoolfiles;
  if ($compress_spoolfiles) {
    $file=gzopen("$spooldir/$group-data.dat","w");
    gzputs($file,serialize($headers));
    gzclose($file);
  } else {
    $file=fopen("$spooldir/$group-data.dat","w");
    fputs($file,serialize($headers));
    fclose($file);
  }
}


function showHeaders(&$headers,$group,$article_first=0,$article_last=0) {
  global $thread_showDate, $thread_showTable;
  global $thread_showAuthor,$thread_showSubject;
  global $text_thread,$thread_treestyle;
  $article_count=0;
  if ($headers == false) {
    echo "<p><b>".$text_thread["no_articles"]."</b></p>";
  } else {
    reset($headers);
    $c=current($headers);
    for ($i=0; $i<=count($headers)-1; $i++) {  // create the array $liste
      if ($c->isAnswer == false) {             // where are all the articles
        $liste[]=$c->id;                       // in that don't have
      }                                        // references
      $c=next($headers);
    }
    reset($liste);
    if (count($liste)>0) {
      if (($thread_treestyle==2) || ($thread_treestyle==6) ||
          ($thread_treestyle==7)) {
        echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        if ($thread_showDate) echo "<td><b>".$text_thread["date"]."</b>&nbsp;</td>";
        if ($thread_showSubject) echo "<td><b>".$text_thread["subject"]."</b></td>";
        if ($thread_showAuthor) {
          echo "<td>&nbsp;&nbsp;</td>";
          echo "<td><b>".$text_thread["author"]."</b></td>\n";
        }
        echo "</tr>\n";
        showThread($headers,$liste,1,"",$group,$article_first,$article_last,
                   $article_count);
        echo "</table>\n";
      } else {
        if ($thread_treestyle==1) echo "<ul>\n";
        showThread($headers,$liste,1,"",$group,$article_first,$article_last,
                   $article_count);
        if ($thread_treestyle==1) echo "</ul>\n";
      }
    }
  }
}


/*
 * Print the header of a message to the webpage
 *
 * $head: the header of the message as an headerType
 * $group: the name of the newsgroup, is needed for the links to post.php3
 *         and the header.
 */
function show_header($head,$group) {
  global $article_show,$text_header,$file_article,$attachment_show;
  global $file_attachment;
  if ($article_show["Subject"]) echo "<b>".$text_header["subject"]."</b>".htmlspecialchars($head->subject)."<br>";
  if ($article_show["From"]) {
    echo "<b>".$text_header["from"]."</b>".'<a href="mailto:'.htmlspecialchars($head->from).'">'.$head->from.'</a> ';
    if ($head->name != "") echo '('.htmlspecialchars($head->name).')';
    echo "<br>";
  }
  if ($article_show["Newsgroups"]) 
    echo "<b>".$text_header["newsgroups"]."</b>".htmlspecialchars(str_replace(',',', ',$head->newsgroups))."<br>\n";
  if (isset($head->followup) && ($article_show["Followup"]) && ($head->followup != "")) 
    echo "<b>".$text_header["followup"]."</b>".htmlspecialchars($head->followup)."<br>\n";
  if ((isset($head->organization)) && ($article_show["Organization"]) &&
     ($head->organization != ""))
    echo "<b>".$text_header["organization"]."</b>".
         html_parse(htmlspecialchars($head->organization))."<br>\n";
  if ($article_show["Date"])
    echo "<b>".$text_header["date"]."</b>".date($text_header["date_format"],$head->date)."<br>\n";
  if ($article_show["Message-ID"])
    echo "<b>".$text_header["message-id"]."</b>".htmlspecialchars($head->id)."<br>\n";
  if (($article_show["References"]) && (isset($head->references[0]))) {
    echo "<b>".$text_header["references"]."</b>";
    for ($i=0; $i<=count($head->references)-1; $i++) {
      $ref=$head->references[$i];
      echo ' '.'<a href="'.$file_article.'?group='.urlencode($group).
           '&id='.urlencode($ref).'">'.($i+1).'</a>';
    }
    echo "<br>";
  }
  if (isset($head->user_agent)) {
    if ((isset($article_show["User-Agent"])) &&
       ($article_show["User-Agent"])) {
      echo "<b>".$text_header["user-agent"]."</b>".htmlspecialchars($head->user_agent)."<br>\n";
    } else {
      echo "<!-- User-Agent: ".htmlspecialchars($head->user_agent)." -->\n";
    }
  }
  if ((isset($attachment_show)) && ($attachment_show==true) &&
      (isset($head->content_type[1]))) {
    echo "<b>".$text_header["attachments"]."</b>";
    for ($i=1; $i<count($head->content_type); $i++) {
      echo '<a href="'.$file_attachment.'/'.urlencode($group).'/'.
           urlencode($head->number).'/'.
           $i.'/'.
           urlencode($head->content_type_name[$i]).'">'.
           $head->content_type_name[$i].'</a> ('.
           $head->content_type[$i].')';
      if ($i<count($head->content_type)-1) echo ', ';
    }
  }
}


/*
function showAntwortKnopf($group,$id) {
  global $file_post;
  echo "<form action=\"$file_post\" method=post>\n";
  echo "<input type=submit value=\"Antworten\"\n>";
  echo "<input type=hidden name=\"type\" value=\"reply\">\n";
  echo "<input type=hidden name=\"id\" value=\"$id\">\n";
  echo "<input type=hidden name=\"group\" value=\"$group\">\n";
  echo "</form>\n";
}
*/

/*
 * print an article to the webpage
 *
 * $group: The name of the newsgroup
 * $id: the ID of the article inside the group or the message-id
 * $attachment: The number of the attachment of the article.
 *              0 means the normal textbody.
 */
function show_article($group,$id,$attachment=0,$article_data=false) {
  global $file_article;
  global $text_header,$article_showthread;
  if ($article_data == false)
    $article_data=read_message($id,$attachment,$group);
  $head=$article_data->header;
  $body=$article_data->body[$attachment];
  if (isset($head)) {
    if (($head->content_type[$attachment]=="text/plain") &&
        ($attachment==0)){
      show_header($head,$group);
      echo "<pre>\n";
      $body=split("\n",$body);
      for ($i=0; $i<=count($body)-1; $i++) {
        $b=textwrap($body[$i],80,"\n");
        if ((strpos(substr($b,0,strpos($b," ")),'>') != false ) ||
            (strcmp(substr($b,0,1),'>') == 0) ||
            (strcmp(substr($b,0,1),':') == 0)) {
          echo "<i>".html_parse(htmlspecialchars($b))."</i>\n";
        } else {
          echo html_parse(htmlspecialchars($b)."\n");
        }
      }
      echo "\n</pre>\n";
    } else {
      echo $body;
    }
  }
  if ($article_showthread > 0) {
  }
}

/*
 * Encode lines with 8bit-characters to quote-printable
 *
 * $line: the to be encoded line
 *
 * the function returns a sting containing the quoted-printable encoded
 * $line
 */
function quoted_printable_encode($line) {
  $qp_table=array(
     '=00', '=01', '=02', '=03', '=04', '=05',
     '=06', '=07', '=08', '=09', '=0A', '=0B',
     '=0C', '=0D', '=0E', '=0F', '=10', '=11',
     '=12', '=13', '=14', '=15', '=16', '=17',
     '=18', '=19', '=1A', '=1B', '=1C', '=1D',
     '=1E', '=1F', '_',   '!',   '"',   '#',
     '$',   '%',   '&',   "'",   '(',   ')',
     '*',   '+',   ',',   '-',   '.',   '/',
     '0',   '1',   '2',   '3',   '4',   '5',
     '6',   '7',   '8',   '9',   ':',   ';',
     '<',   '=3D', '>',   '=3F', '@',   'A',
     'B',   'C',   'D',   'E',   'F',   'G',
     'H',   'I',   'J',   'K',   'L',   'M',
     'N',   'O',   'P',   'Q',   'R',   'S',
     'T',   'U',   'V',   'W',   'X',   'Y',
     'Z',   '[',   '\\',  ']',   '^',   '=5F',
     '',    'a',   'b',   'c',   'd',   'e',
     'f',   'g',   'h',   'i',   'j',   'k',
     'l',   'm',   'n',   'o',   'p',   'q',
     'r',   's',   't',   'u',   'v',   'w',
     'x',   'y',   'z',   '{',   '|',   '}',
     '~',   '=7F', '=80', '=81', '=82', '=83',
     '=84', '=85', '=86', '=87', '=88', '=89',
     '=8A', '=8B', '=8C', '=8D', '=8E', '=8F',
     '=90', '=91', '=92', '=93', '=94', '=95',
     '=96', '=97', '=98', '=99', '=9A', '=9B',
     '=9C', '=9D', '=9E', '=9F', '=A0', '=A1',
     '=A2', '=A3', '=A4', '=A5', '=A6', '=A7',
     '=A8', '=A9', '=AA', '=AB', '=AC', '=AD',
     '=AE', '=AF', '=B0', '=B1', '=B2', '=B3',
     '=B4', '=B5', '=B6', '=B7', '=B8', '=B9',
     '=BA', '=BB', '=BC', '=BD', '=BE', '=BF',
     '=C0', '=C1', '=C2', '=C3', '=C4', '=C5',
     '=C6', '=C7', '=C8', '=C9', '=CA', '=CB',
     '=CC', '=CD', '=CE', '=CF', '=D0', '=D1',
     '=D2', '=D3', '=D4', '=D5', '=D6', '=D7',
     '=D8', '=D9', '=DA', '=DB', '=DC', '=DD',
     '=DE', '=DF', '=E0', '=E1', '=E2', '=E3',
     '=E4', '=E5', '=E6', '=E7', '=E8', '=E9',
     '=EA', '=EB', '=EC', '=ED', '=EE', '=EF',
     '=F0', '=F1', '=F2', '=F3', '=F4', '=F5',
     '=F6', '=F7', '=F8', '=F9', '=FA', '=FB',
     '=FC', '=FD', '=FE', '=FF');
  // are there "forbidden" characters in the string?
  for($i=0; $i<strlen($line) && ord($line[$i])<=127 ; $i++);
  if ($i<strlen($line)) { // yes, there are. So lets encode them!
    $from=$i;
    for($to=strlen($line)-1; ord($line[$to])<=127; $to--);
    // lets scan for the start and the end of the to be encoded _words_
    for(;$from>0 && $line[$from] != ' '; $from--);
    if($from>0) $from++;
    for(;$to<strlen($line) && $line[$to] != ' '; $to++);
    // split the string into the to be encoded middle and the rest
    $begin=substr($line,0,$from);
    $middle=substr($line,$from,$to-$from);
    $end=substr($line,$to);
    // ok, now lets encode $middle...
    $newmiddle="";
    for($i=0; $i<strlen($middle); $i++)
      $newmiddle .= $qp_table[ord($middle[$i])];
    // now we glue the parts together...
    $line=$begin.'=?ISO-8859-15?Q?'.$newmiddle.'?='.$end;
  }
  return $line;
}



/*
 * Post an article to a newsgroup
 *
 * $subject: The Subject of the article
 * $from: The authors name and email of the article
 * $newsgroups: The groups to post to
 * $ref: The references of the article
 * $body: The article itself
 */
function verschicken($subject,$from,$newsgroups,$ref,$body) {
  global $server,$port,$send_poster_host,$organization,$text_error;
  global $file_footer;
  flush();
  $ns=OpenNNTPconnection($server,$port);
  if ($ns != false) {
    fputs($ns,"post\r\n");
    $weg=lieszeile($ns);
    fputs($ns,'Subject: '.quoted_printable_encode($subject)."\r\n");
    fputs($ns,'From: '.$from."\r\n");
    fputs($ns,'Newsgroups: '.$newsgroups."\r\n");
    fputs($ns,"Mime-Version: 1.0\r\n");
    fputs($ns,"Content-Type: text/plain; charset=ISO-8859-15\r\n");
    fputs($ns,"Content-Transfer-Encoding: 8bit\r\n");
    fputs($ns,"User-Agent: NewsPortal 0.24pre6, http://florian-amrhein.de/newsportal/\r\n");
    if ($send_poster_host)
      fputs($ns,'X-HTTP-Posting-Host: '.gethostbyaddr(getenv("REMOTE_ADDR"))."\r\n");
    if ($ref!=false) fputs($ns,'References: '.$ref."\r\n");
    if (isset($organization))
      fputs($ns,'Organization: '.quoted_printable_encode($organization)."\r\n");
    if ((isset($file_footer)) && ($file_footer!="")) {
      $footerfile=fopen($file_footer,"r");
      $body.="\n".fread($footerfile,filesize($file_footer));
      fclose($footerfile);
    }
    $body=str_replace("\n.\r","\n..\r",$body);
    $body=str_replace("\r",'',$body);
    $b=split("\n",$body);
    $body="";
    for ($i=0; $i<count($b); $i++) {
      if ((strpos(substr($b[$i],0,strpos($b[$i]," ")),">") != false ) | (strcmp(substr($b[$i],0,1),">") == 0)) {
        $body .= textwrap(stripSlashes($b[$i]),78,"\r\n")."\r\n";
      } else {
        $body .= textwrap(stripSlashes($b[$i]),74,"\r\n")."\r\n";
      }
    }
    fputs($ns,"\r\n".$body."\r\n.\r\n");
    $message=lieszeile($ns);
    closeNNTPconnection($ns);
  } else {
    $message=$text_error["post_failed"];
  }
  return $message;
}

?>

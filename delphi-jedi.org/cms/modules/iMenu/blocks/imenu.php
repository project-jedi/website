<?php
function b_imenu_show($options) {
    //include XOOPS_ROOT_PATH.'/mainfile.php';
        global $xoopsDB, $xoopsUser, $xoopsConfig;
	$myts =& MyTextSanitizer::getInstance();
        $block = array();

        $result = $xoopsDB->query("SELECT groups, users, link, title, target FROM ".$xoopsDB->prefix("imenu")." WHERE hide=0 ORDER BY weight ASC");

        while($fc_item = $xoopsDB->fetchArray($result)){
			$cgroups=explode("|",$fc_item['groups']);
			$users=explode("|",$fc_item['users']);
			unset($uGroups);
			if ($xoopsUser){
                $uGroups= $xoopsUser->getGroups();
                //$uid=$xoopsUser->getVar('uid'); mit uname ist ein Schritt kürzer
			    $uName=$xoopsUser->uname();
                }
            else {
                
                $uGroups[]= XOOPS_GROUP_ANONYMOUS;
                $uName= $xoopsConfig['anonymous'];
                }
		
			$igroups=array_intersect($uGroups,$cgroups);
			if ((count($igroups)>0) or (in_array($uName, $users))) {
                $fc_title = $myts->makeTboxData4Show($fc_item['title']);
				if ( !XOOPS_USE_MULTIBYTES ){
					if (strlen($fc_title) >= 40) {
						$fc_title = substr($fc_title,0,39)."...";
					}
				}
				$item['target']= $fc_item['target'];
                $item['link'] = $fc_item['link'];
                $item['title'] = $fc_item['title'];
                
				if ((substr($fc_item['link'],0,1)=="[") and (substr($fc_item['link'],strlen($fc_item['link'])-1,1)=="]")) {
					$item['link']= XOOPS_URL.'/modules/'.substr($fc_item['link'],1,strlen($fc_item['link'])-2).'/';
				}
				//$im['item'] = $out;
				$block['contents'][] = $item;
			}
        }

        return $block;
}

?>

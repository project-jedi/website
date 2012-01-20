<?php

# Sourceforge Logo Extension
#
# Tag :
#   <SourceforgeLogo project="project_name" groupid="group_id" type="logo_type" width="logo_width" height="logo_height" style="css_stytle" alt="logo_alt"/>
#
# Enjoy !

$wgExtensionFunctions[] = 'SourceforgeLogo';
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'Sourceforge Logo Extension',
        'description' => 'Display Sourceforge Logo given a project id at Sourceforge',
        'author' => 'Florent Ouchet',
        'url' => 'http://wiki.delphi-jedi.org/index.php?title=User:Outchy/Sourceforge_Logo'
);
 
function SourceforgeLogo() {
        global $wgParser;
        $wgParser->setHook('SourceforgeLogo', 'renderSourceforgeLogo');
}
 
# The callback function for converting the args to HTML output
function renderSourceforgeLogo($input, array $args) {
	$output='<a href="http://sourceforge.net/projects/'.htmlspecialchars($args["project"]).'">';
        $output.='<img src="http://sflogo.sourceforge.net/sflogo.php?group_id='.htmlspecialchars($args["groupid"]).'&type='.htmlspecialchars($args["type"]).'" ';
        $output.='width="'.htmlspecialchars($args["width"]).'" ';
        $output.='height="'.htmlspecialchars($args["height"]).'" ';
	$output.='style="'.htmlspecialchars($args["style"]).'" ';
        $output.='alt="'.htmlspecialchars($args["alt"]).'" ';
        $output.='/></a>';
        return $output;
}

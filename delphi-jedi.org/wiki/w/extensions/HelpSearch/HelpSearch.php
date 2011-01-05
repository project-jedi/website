<?php

# JCL Help Search Engine Extension
# highly inspired by "Google Custom Search Engine Extension"
# http://www.mediawiki.org/wiki/Extension:Google_Custom_Search_Engine
# 
# Tag :
#   <JCLHelpSearch></JCLHelpSearch> or <JCLHelpSearch/>
#
# Enjoy !

$wgExtensionFunctions[] = 'HelpSearch';
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'Help Search Extension',
        'description' => 'Help Search',
        'author' => 'Florent Ouchet',
        'url' => 'http://wiki.delphi-jedi.org/index.php?title=User:Outchy/Help_Search'
);
 
function HelpSearch() {
        global $wgParser;
        $wgParser->setHook('JCLHelpSearch', 'renderJCLHelpSearch');
        $wgParser->setHook('JEDIHelpSearch', 'renderJEDIHelpSearch');
        $wgParser->setHook('JVCLHelpSearch', 'renderJVCLHelpSearch');
        $wgParser->setHook('ALLHelpSearch', 'renderALLHelpSearch');
}
 
# The callback function for converting the input text to HTML output
function renderJEDIHelpSearch($input) {
        $output='<form id="powersearch" method="get" action="/index.php"><input name="title" type="hidden" value="Special:Search"/><input name="ns102" type="hidden" value="1"/><input name="search" size="10" value="" type="text" id="powerSearchText" />&nbsp;<input type="submit" value="Go!" name="fulltext"/></form>';
        return $output;
}
function renderJCLHelpSearch($input) {
        $output='<form id="powersearch" method="get" action="/index.php"><input name="title" type="hidden" value="Special:Search"/><input name="ns104" type="hidden" value="1"/><input name="search" size="10" value="" type="text" id="powerSearchText" />&nbsp;<input type="submit" value="Go!" name="fulltext"/></form>';
        return $output;
}
function renderJVCLHelpSearch($input) {
        $output='<form id="powersearch" method="get" action="/index.php"><input name="title" type="hidden" value="Special:Search"/><input name="ns106" type="hidden" value="1"/><input name="search" size="10" value="" type="text" id="powerSearchText" />&nbsp;<input type="submit" value="Go!" name="fulltext"/></form>';
        return $output;
}
function renderALLHelpSearch($input) {
        $output='<form id="powersearch" method="get" action="/index.php"><input name="title" type="hidden" value="Special:Search"/><input name="ns102" type="hidden" value="1"/><input name="ns104" type="hidden" value="1"/><input name="ns106" type="hidden" value="1"/><input name="search" size="10" value="" type="text" id="powerSearchText" />&nbsp;<input type="submit" value="Go!" name="fulltext"/></form>';
        return $output;
}

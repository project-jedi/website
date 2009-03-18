# phpMyAdmin SQL Dump
# version 2.5.3
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Nov 17, 2003 at 01:42 AM
# Server version: 3.23.56
# PHP Version: 4.3.3
# 
# Database : `xoopsv3a`
# 

# --------------------------------------------------------

#
# Table structure for table `xoops_wfschannel`
#

CREATE TABLE wfschannel (
  CID tinyint(4) NOT NULL auto_increment,
  pagetitle varchar(255) NOT NULL default '0',
  pageheadline varchar(255) NOT NULL default '0',
  page text NOT NULL,
  weight int(11) NOT NULL default '1',
  html int(11) NOT NULL default '0',
  smiley int(11) NOT NULL default '1',
  xcodes int(11) NOT NULL default '1',
  breaks int(10) NOT NULL default '1',
  defaultpage int(10) NOT NULL default '0',
  indeximage varchar(255) NOT NULL default 'logo.png',
  htmlfile varchar(255) NOT NULL default '',
  mainpage int(11) NOT NULL default '0',
  submenu int(11) NOT NULL default '0',
  created int(10) NOT NULL default '0',
  comments tinyint(11) NOT NULL default '0',
  allowcomments tinyint(11) NOT NULL default '0',
  PRIMARY KEY  (CID),
  UNIQUE KEY topicID (CID),
  FULLTEXT KEY answer (page)
) TYPE=MyISAM COMMENT='WF-Channel by Catzwolf';

#
# Table structure for table `wfslinktous`
#

CREATE TABLE wfslinktous (
  submenuitem int(10) NOT NULL default '10',
  textlink varchar(255) NOT NULL default '',
  linkpagelogo varchar(255) NOT NULL default '',
  button varchar(255) NOT NULL default '',
  logo varchar(255) NOT NULL default '',
  banner varchar(255) NOT NULL default '',
  mainpage int(10) NOT NULL default '1',
  newsfeed int(10) NOT NULL default '0',
  texthtml varchar(255) NOT NULL default '',
  titlelink varchar(255) NOT NULL default '',
  newsfeedjs mediumint(10) NOT NULL default '0',
  newstitle varchar(255) NOT NULL default '',
  PRIMARY KEY  (submenuitem)
) TYPE=MyISAM;

#
# Dumping data for table `xoops_wfslinktous`
#

INSERT INTO wfslinktous (submenuitem, textlink, linkpagelogo, button, logo, banner, mainpage, newsfeed, texthtml, titlelink, newsfeedjs, newstitle) VALUES (1, 'http://localhost', 'linktous.png', 'poweredby.gif', 'logo.gif', 'xoops_banner_2.gif', 1, 1, '', 'Link to us', 0, '');

# --------------------------------------------------------

#
# Table structure for table `wfsrefer`
#

CREATE TABLE wfsrefer (
  titlerefer varchar(255) NOT NULL default '',
  chanrefheadline text NOT NULL,
  submenuitem int(10) NOT NULL default '10',
  mainpage int(10) NOT NULL default '1',
  referpagelogo varchar(255) NOT NULL default '',
  emailaddress int(10) NOT NULL default '1',
  usersblurb int(10) NOT NULL default '0',
  defblurb varchar(255) NOT NULL default '',
  smiley tinyint(11) NOT NULL default '0',
  xcodes tinyint(11) NOT NULL default '0',
  breaks tinyint(4) NOT NULL default '0',
  html tinyint(11) NOT NULL default '1',
  PRIMARY KEY  (submenuitem)
) TYPE=MyISAM;

#
# Dumping data for table `wfsrefer`
#

INSERT INTO wfsrefer (titlerefer, chanrefheadline, submenuitem, mainpage, referpagelogo, emailaddress, usersblurb, defblurb, smiley, xcodes, breaks, html) VALUES ('Refer a friend', 'Let a friend know about us', 1, 1, 'referfriend.png', 1, 1, '', 0, 1, 1, 1);

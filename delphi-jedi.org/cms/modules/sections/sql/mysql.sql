# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `seccont`
#

CREATE TABLE seccont (
  artid int(11) NOT NULL auto_increment,
  secid int(11) NOT NULL default '0',
  title text NOT NULL,
  content text NOT NULL,
  counter int(11) NOT NULL default '0',
  PRIMARY KEY  (artid),
  KEY idxseccontsecid (secid),
  KEY idxseccontcounterdesc (counter)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `sections`
#

CREATE TABLE sections (
  secid int(11) NOT NULL auto_increment,
  secname varchar(40) NOT NULL default '',
  image varchar(50) NOT NULL default '',
  PRIMARY KEY  (secid),
  KEY idxsectionssecname (secname)
) TYPE=MyISAM;

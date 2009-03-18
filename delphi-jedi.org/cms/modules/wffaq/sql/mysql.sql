# phpMyAdmin MySQL-Dump
# version 2.2.6
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `faq_categories`
#

CREATE TABLE faqcategories (
  catID tinyint(4) NOT NULL auto_increment,
  name char(50) NOT NULL default '',
  description char(125) NOT NULL default '',
  total int(11) NOT NULL default '0',
  PRIMARY KEY  (catID),
  UNIQUE KEY catID (catID)
) TYPE=MyISAM COMMENT='WF-FAQ by Catzwolf';
# --------------------------------------------------------

#
# Table structure for table `faq_topics`
#

CREATE TABLE faqtopics (
  topicID tinyint(4) NOT NULL auto_increment,
  catID tinyint(4) NOT NULL default '0',
  question varchar(75) NOT NULL default '0',
  answer text NOT NULL,
  summary text NOT NULL,
  uid int(6) default '1',
  submit int(1) NOT NULL default '0',
  datesub int(11) NOT NULL default '1033141070',
  counter int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (topicID),
  UNIQUE KEY topicID (topicID),
  FULLTEXT KEY answer (answer)
) TYPE=MyISAM COMMENT='WF-FAQ by Catzwolf';


#
# Table structure for table `xoopsfaq_categories`
#

CREATE TABLE xoopsfaq_categories (
  category_id tinyint(3) unsigned NOT NULL auto_increment,
  category_title varchar(255) NOT NULL default '',
  category_order tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (category_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `xoopsfaq_contents`
#

CREATE TABLE xoopsfaq_contents (
  contents_id smallint(5) unsigned NOT NULL auto_increment,
  category_id tinyint(3) unsigned NOT NULL default '0',
  contents_title varchar(255) NOT NULL default '',
  contents_contents text NOT NULL,
  contents_time int(10) unsigned NOT NULL default '0',
  contents_order smallint(5) unsigned NOT NULL default '0',
  contents_visible tinyint(1) unsigned NOT NULL default '1',
  contents_nohtml tinyint(1) unsigned NOT NULL default '0',
  contents_nosmiley tinyint(1) unsigned NOT NULL default '0',
  contents_noxcode tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (contents_id),
  KEY contents_title (contents_title(40)),
  KEY contents_visible_category_id (contents_visible,category_id)
) TYPE=MyISAM;

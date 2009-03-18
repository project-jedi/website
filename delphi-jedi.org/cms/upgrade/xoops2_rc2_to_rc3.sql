ALTER TABLE xoopscomments ADD com_exparams varchar(255) NOT NULL after com_status;

update config set conf_name = 'theme_set' where conf_name = 'default_theme';

update config set conf_value = 'default' where conf_name = 'theme_set';

INSERT INTO config VALUES (0,0,1,'template_set','_MD_AM_DTPLSET','default','_MD_AM_DTPLSETDSC','tplset','other',14);

ALTER table tplfile change tpl_themeset tpl_tplset varchar(50) NOT NULL default '';

ALTER TABLE imgset_themeset_link CHANGE themeset_name tplset_name VARCHAR(50) NOT NULL;

ALTER TABLE themeset CHANGE themeset_id tplset_id INT(7) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE themeset_name tplset_name VARCHAR(50) NOT NULL, CHANGE themeset_desc tplset_desc VARCHAR(255) NOT NULL, CHANGE themeset_credits tplset_credits TEXT NOT NULL, CHANGE themeset_created tplset_created INT(10) UNSIGNED DEFAULT '0' NOT NULL;

INSERT INTO configcategory VALUES (6, '_MD_AM_MAILER', 0);

INSERT INTO config VALUES (0,0,6,'smtphost','_MD_AM_SMTPHOST','a:1:{i:0;s:0:\"\";}', '_MD_AM_SMTPHOSTDESC','textarea','array',5);
INSERT INTO config VALUES (0,0,6,'smtpuser','_MD_AM_SMTPUSER','','_MD_AM_SMTPUSERDESC','textbox','text',6);
INSERT INTO config VALUES (0,0,6,'smtppass','_MD_AM_SMTPPASS','','_MD_AM_SMTPPASSDESC','password','text',7);
INSERT INTO config VALUES (0,0,6,'sendmailpath','_MD_AM_SENDMAILPATH','/usr/sbin/sendmail','_MD_AM_SENDMAILPATHDESC','textbox','text',4);
INSERT INTO config VALUES (0,0,6,'from','_MD_AM_MAILFROM','','_MD_AM_MAILFROMDESC','textbox','text', 1);
INSERT INTO config VALUES (0,0,6,'fromname','_MD_AM_MAILFROMNAME','','_MD_AM_MAILFROMNAMEDESC','textbox','text',2);

CREATE TABLE group_permission (
  gperm_id int(10) unsigned NOT NULL auto_increment,
  gperm_groupid smallint(5) unsigned NOT NULL default '0',
  gperm_itemid mediumint(8) unsigned NOT NULL default '0',
  gperm_modid mediumint(5) unsigned NOT NULL default '0',
  gperm_name varchar(50) NOT NULL default '',
  PRIMARY KEY  (gperm_id),
  KEY groupid (gperm_groupid),
  KEY itemid (gperm_itemid),
  KEY gperm_modid (gperm_modid,gperm_name(10))
) TYPE=MyISAM;

CREATE TABLE xoopsnotifications (
  not_id mediumint(8) unsigned NOT NULL auto_increment,
  not_modid smallint(5) unsigned NOT NULL default '0',
  not_itemid mediumint(8) unsigned NOT NULL default '0',
  not_category varchar(30) NOT NULL default '',
  not_event varchar(30) NOT NULL default '',
  not_uid mediumint(8) unsigned NOT NULL default '0',
  not_mode tinyint(1) NOT NULL default 0,
  PRIMARY KEY (not_id),
  KEY not_modid (not_modid),
  KEY not_itemid (not_itemid),
  KEY not_class (not_category),
  KEY not_uid (not_uid),
  KEY not_event (not_event)
) TYPE=MyISAM;

ALTER TABLE modules ADD hasnotification tinyint(1) unsigned NOT NULL default '0';

ALTER TABLE users ADD notify_mode tinyint(1) NOT NULL default '0' after uorder, ADD notify_method tinyint(1) NOT NULL default '1' after uorder;

DELETE from tplfile WHERE tpl_type = 'skin' OR tpl_type = 'css';
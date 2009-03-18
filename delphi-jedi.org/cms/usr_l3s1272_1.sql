# phpMyAdmin MySQL-Dump
# version 2.4.0
# http://www.phpmyadmin.net/ (download page)
#
# Host: sql.zeus01.de
# Erstellungszeit: 06. Februar 2004 um 18:20
# Server Version: 4.0.16
# PHP-Version: 4.3.4
# Datenbank: `usr_l3s1272_1`
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_avatar`
#

DROP TABLE IF EXISTS xoops_avatar;
CREATE TABLE xoops_avatar (
  avatar_id mediumint(8) unsigned NOT NULL auto_increment,
  avatar_file varchar(30) NOT NULL default '',
  avatar_name varchar(100) NOT NULL default '',
  avatar_mimetype varchar(30) NOT NULL default '',
  avatar_created int(10) NOT NULL default '0',
  avatar_display tinyint(1) unsigned NOT NULL default '0',
  avatar_weight smallint(5) unsigned NOT NULL default '0',
  avatar_type char(1) NOT NULL default '',
  PRIMARY KEY  (avatar_id),
  KEY avatar_type (avatar_type,avatar_display)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_avatar`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_avatar_user_link`
#

DROP TABLE IF EXISTS xoops_avatar_user_link;
CREATE TABLE xoops_avatar_user_link (
  avatar_id mediumint(8) unsigned NOT NULL default '0',
  user_id mediumint(8) unsigned NOT NULL default '0',
  KEY avatar_user_id (avatar_id,user_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_avatar_user_link`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_banner`
#

DROP TABLE IF EXISTS xoops_banner;
CREATE TABLE xoops_banner (
  bid smallint(5) unsigned NOT NULL auto_increment,
  cid tinyint(3) unsigned NOT NULL default '0',
  imptotal mediumint(8) unsigned NOT NULL default '0',
  impmade mediumint(8) unsigned NOT NULL default '0',
  clicks mediumint(8) unsigned NOT NULL default '0',
  imageurl varchar(255) NOT NULL default '',
  clickurl varchar(255) NOT NULL default '',
  date int(10) NOT NULL default '0',
  htmlbanner tinyint(1) NOT NULL default '0',
  htmlcode text NOT NULL,
  PRIMARY KEY  (bid),
  KEY idxbannercid (cid),
  KEY idxbannerbidcid (bid,cid)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_banner`
#

INSERT INTO xoops_banner VALUES (1, 1, 0, 2, 0, 'http://l3s1272.zeus01.de/xoops/images/banners/xoops_banner.gif', 'http://www.xoops.org/', 1008813250, 0, '');
INSERT INTO xoops_banner VALUES (2, 1, 0, 1, 0, 'http://l3s1272.zeus01.de/xoops/images/banners/xoops_banner_2.gif', 'http://www.xoops.org/', 1008813250, 0, '');
INSERT INTO xoops_banner VALUES (3, 1, 0, 2, 0, 'http://l3s1272.zeus01.de/xoops/images/banners/banner.swf', 'http://www.xoops.org/', 1008813250, 0, '');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_bannerclient`
#

DROP TABLE IF EXISTS xoops_bannerclient;
CREATE TABLE xoops_bannerclient (
  cid smallint(5) unsigned NOT NULL auto_increment,
  name varchar(60) NOT NULL default '',
  contact varchar(60) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  login varchar(10) NOT NULL default '',
  passwd varchar(10) NOT NULL default '',
  extrainfo text NOT NULL,
  PRIMARY KEY  (cid),
  KEY login (login)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_bannerclient`
#

INSERT INTO xoops_bannerclient VALUES (1, 'XOOPS', 'XOOPS Dev Team', 'webmaster@xoops.org', '', '', '');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_bannerfinish`
#

DROP TABLE IF EXISTS xoops_bannerfinish;
CREATE TABLE xoops_bannerfinish (
  bid smallint(5) unsigned NOT NULL auto_increment,
  cid smallint(5) unsigned NOT NULL default '0',
  impressions mediumint(8) unsigned NOT NULL default '0',
  clicks mediumint(8) unsigned NOT NULL default '0',
  datestart int(10) unsigned NOT NULL default '0',
  dateend int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (bid),
  KEY cid (cid)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_bannerfinish`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_block_module_link`
#

DROP TABLE IF EXISTS xoops_block_module_link;
CREATE TABLE xoops_block_module_link (
  block_id mediumint(8) unsigned NOT NULL default '0',
  module_id smallint(5) NOT NULL default '0',
  KEY module_id (module_id),
  KEY block_id (block_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_block_module_link`
#

INSERT INTO xoops_block_module_link VALUES (1, 0);
INSERT INTO xoops_block_module_link VALUES (2, 0);
INSERT INTO xoops_block_module_link VALUES (3, 0);
INSERT INTO xoops_block_module_link VALUES (4, 0);
INSERT INTO xoops_block_module_link VALUES (5, 0);
INSERT INTO xoops_block_module_link VALUES (6, 0);
INSERT INTO xoops_block_module_link VALUES (7, 0);
INSERT INTO xoops_block_module_link VALUES (8, 0);
INSERT INTO xoops_block_module_link VALUES (9, 0);
INSERT INTO xoops_block_module_link VALUES (10, 0);
INSERT INTO xoops_block_module_link VALUES (11, 0);
INSERT INTO xoops_block_module_link VALUES (12, 0);
INSERT INTO xoops_block_module_link VALUES (13, -1);
INSERT INTO xoops_block_module_link VALUES (14, -1);
INSERT INTO xoops_block_module_link VALUES (15, -1);
INSERT INTO xoops_block_module_link VALUES (16, -1);
INSERT INTO xoops_block_module_link VALUES (17, -1);
INSERT INTO xoops_block_module_link VALUES (18, -1);
INSERT INTO xoops_block_module_link VALUES (19, -1);
INSERT INTO xoops_block_module_link VALUES (27, -1);
INSERT INTO xoops_block_module_link VALUES (21, -1);
INSERT INTO xoops_block_module_link VALUES (22, -1);
INSERT INTO xoops_block_module_link VALUES (23, -1);
INSERT INTO xoops_block_module_link VALUES (24, -1);
INSERT INTO xoops_block_module_link VALUES (25, -1);
INSERT INTO xoops_block_module_link VALUES (28, 0);
INSERT INTO xoops_block_module_link VALUES (29, 11);
INSERT INTO xoops_block_module_link VALUES (30, 12);
INSERT INTO xoops_block_module_link VALUES (32, -1);
INSERT INTO xoops_block_module_link VALUES (33, -1);
INSERT INTO xoops_block_module_link VALUES (37, 13);
INSERT INTO xoops_block_module_link VALUES (37, 19);
INSERT INTO xoops_block_module_link VALUES (38, 0);
INSERT INTO xoops_block_module_link VALUES (39, 0);
INSERT INTO xoops_block_module_link VALUES (40, 0);
INSERT INTO xoops_block_module_link VALUES (41, 22);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_cjaycontent`
#

DROP TABLE IF EXISTS xoops_cjaycontent;
CREATE TABLE xoops_cjaycontent (
  id int(5) unsigned NOT NULL auto_increment,
  title varchar(150) NOT NULL default '',
  type tinyint(10) unsigned NOT NULL default '0',
  design tinyint(4) unsigned NOT NULL default '0',
  hide tinyint(4) unsigned NOT NULL default '0',
  adress varchar(255) default NULL,
  comment varchar(255) default NULL,
  content text NOT NULL,
  submitter varchar(255) default NULL,
  date int(10) unsigned NOT NULL default '0',
  image varchar(255) default NULL,
  hits int(6) unsigned NOT NULL default '0',
  weight int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_cjaycontent`
#

INSERT INTO xoops_cjaycontent VALUES (1, 'C-JAY Contet Start', 0, 0, 1, 'DO_NOT_DELETE.php', 'DO NOT DELETE THIS FILE!!', '', '1', 1050232144, NULL, 37, 0);
INSERT INTO xoops_cjaycontent VALUES (2, 'Testpage', 0, 0, 0, 'j1.html', 'A simple page', '', '1', 1067728378, NULL, 0, 0);
INSERT INTO xoops_cjaycontent VALUES (3, 'Sponsors', 0, 0, 0, 'j1.html', '', '', '1', 1067741794, NULL, 0, 0);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_config`
#

DROP TABLE IF EXISTS xoops_config;
CREATE TABLE xoops_config (
  conf_id smallint(5) unsigned NOT NULL auto_increment,
  conf_modid smallint(5) unsigned NOT NULL default '0',
  conf_catid smallint(5) unsigned NOT NULL default '0',
  conf_name varchar(25) NOT NULL default '',
  conf_title varchar(30) NOT NULL default '',
  conf_value text NOT NULL,
  conf_desc varchar(30) NOT NULL default '',
  conf_formtype varchar(15) NOT NULL default '',
  conf_valuetype varchar(10) NOT NULL default '',
  conf_order smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (conf_id),
  KEY conf_mod_cat_id (conf_modid,conf_catid)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_config`
#

INSERT INTO xoops_config VALUES (1, 0, 1, 'sitename', '_MD_AM_SITENAME', 'Project JEDI', '_MD_AM_SITENAMEDSC', 'textbox', 'text', 0);
INSERT INTO xoops_config VALUES (2, 0, 1, 'slogan', '_MD_AM_SLOGAN', 'JOINT ENDEAVOUR OF DELPHI INNOVATORS', '_MD_AM_SLOGANDSC', 'textbox', 'text', 2);
INSERT INTO xoops_config VALUES (3, 0, 1, 'language', '_MD_AM_LANGUAGE', 'english', '_MD_AM_LANGUAGEDSC', 'language', 'other', 4);
INSERT INTO xoops_config VALUES (4, 0, 1, 'startpage', '_MD_AM_STARTPAGE', 'news', '_MD_AM_STARTPAGEDSC', 'startpage', 'other', 6);
INSERT INTO xoops_config VALUES (5, 0, 1, 'server_TZ', '_MD_AM_SERVERTZ', '0', '_MD_AM_SERVERTZDSC', 'timezone', 'float', 8);
INSERT INTO xoops_config VALUES (6, 0, 1, 'default_TZ', '_MD_AM_DEFAULTTZ', '0', '_MD_AM_DEFAULTTZDSC', 'timezone', 'float', 10);
INSERT INTO xoops_config VALUES (7, 0, 1, 'theme_set', '_MD_AM_DTHEME', 'phpkaox', '_MD_AM_DTHEMEDSC', 'theme', 'other', 12);
INSERT INTO xoops_config VALUES (8, 0, 1, 'anonymous', '_MD_AM_ANONNAME', 'guest', '_MD_AM_ANONNAMEDSC', 'textbox', 'text', 15);
INSERT INTO xoops_config VALUES (9, 0, 1, 'gzip_compression', '_MD_AM_USEGZIP', '1', '_MD_AM_USEGZIPDSC', 'yesno', 'int', 16);
INSERT INTO xoops_config VALUES (10, 0, 1, 'usercookie', '_MD_AM_USERCOOKIE', 'pjedi', '_MD_AM_USERCOOKIEDSC', 'textbox', 'text', 18);
INSERT INTO xoops_config VALUES (11, 0, 1, 'session_expire', '_MD_AM_SESSEXPIRE', '15', '_MD_AM_SESSEXPIREDSC', 'textbox', 'int', 22);
INSERT INTO xoops_config VALUES (12, 0, 1, 'banners', '_MD_AM_BANNERS', '0', '_MD_AM_BANNERSDSC', 'yesno', 'int', 26);
INSERT INTO xoops_config VALUES (13, 0, 1, 'debug_mode', '_MD_AM_DEBUGMODE', '0', '_MD_AM_DEBUGMODEDSC', 'select', 'int', 24);
INSERT INTO xoops_config VALUES (14, 0, 1, 'my_ip', '_MD_AM_MYIP', '127.0.0.1', '_MD_AM_MYIPDSC', 'textbox', 'text', 29);
INSERT INTO xoops_config VALUES (15, 0, 1, 'use_ssl', '_MD_AM_USESSL', '0', '_MD_AM_USESSLDSC', 'yesno', 'int', 30);
INSERT INTO xoops_config VALUES (16, 0, 1, 'session_name', '_MD_AM_SESSNAME', 'pjedi_session', '_MD_AM_SESSNAMEDSC', 'textbox', 'text', 20);
INSERT INTO xoops_config VALUES (17, 0, 2, 'minpass', '_MD_AM_MINPASS', '5', '_MD_AM_MINPASSDSC', 'textbox', 'int', 1);
INSERT INTO xoops_config VALUES (18, 0, 2, 'minuname', '_MD_AM_MINUNAME', '5', '_MD_AM_MINUNAMEDSC', 'textbox', 'int', 2);
INSERT INTO xoops_config VALUES (19, 0, 2, 'new_user_notify', '_MD_AM_NEWUNOTIFY', '1', '_MD_AM_NEWUNOTIFYDSC', 'yesno', 'int', 4);
INSERT INTO xoops_config VALUES (20, 0, 2, 'new_user_notify_group', '_MD_AM_NOTIFYTO', '1', '_MD_AM_NOTIFYTODSC', 'group', 'int', 6);
INSERT INTO xoops_config VALUES (21, 0, 2, 'activation_type', '_MD_AM_ACTVTYPE', '0', '_MD_AM_ACTVTYPEDSC', 'select', 'int', 8);
INSERT INTO xoops_config VALUES (22, 0, 2, 'activation_group', '_MD_AM_ACTVGROUP', '1', '_MD_AM_ACTVGROUPDSC', 'group', 'int', 10);
INSERT INTO xoops_config VALUES (23, 0, 2, 'uname_test_level', '_MD_AM_UNAMELVL', '0', '_MD_AM_UNAMELVLDSC', 'select', 'int', 12);
INSERT INTO xoops_config VALUES (24, 0, 2, 'avatar_allow_upload', '_MD_AM_AVATARALLOW', '1', '_MD_AM_AVATARALWDSC', 'yesno', 'int', 14);
INSERT INTO xoops_config VALUES (27, 0, 2, 'avatar_width', '_MD_AM_AVATARW', '80', '_MD_AM_AVATARWDSC', 'textbox', 'int', 16);
INSERT INTO xoops_config VALUES (28, 0, 2, 'avatar_height', '_MD_AM_AVATARH', '80', '_MD_AM_AVATARHDSC', 'textbox', 'int', 18);
INSERT INTO xoops_config VALUES (29, 0, 2, 'avatar_maxsize', '_MD_AM_AVATARMAX', '35000', '_MD_AM_AVATARMAXDSC', 'textbox', 'int', 20);
INSERT INTO xoops_config VALUES (30, 0, 1, 'adminmail', '_MD_AM_ADMINML', 'ma.thoma@gmx.de', '_MD_AM_ADMINMLDSC', 'textbox', 'text', 3);
INSERT INTO xoops_config VALUES (31, 0, 2, 'self_delete', '_MD_AM_SELFDELETE', '1', '_MD_AM_SELFDELETEDSC', 'yesno', 'int', 22);
INSERT INTO xoops_config VALUES (32, 0, 1, 'com_mode', '_MD_AM_COMMODE', 'nest', '_MD_AM_COMMODEDSC', 'select', 'text', 34);
INSERT INTO xoops_config VALUES (33, 0, 1, 'com_order', '_MD_AM_COMORDER', '0', '_MD_AM_COMORDERDSC', 'select', 'int', 36);
INSERT INTO xoops_config VALUES (34, 0, 2, 'bad_unames', '_MD_AM_BADUNAMES', 'a:3:{i:0;s:9:"webmaster";i:1;s:6:"^xoops";i:2;s:6:"^admin";}', '_MD_AM_BADUNAMESDSC', 'textarea', 'array', 24);
INSERT INTO xoops_config VALUES (35, 0, 2, 'bad_emails', '_MD_AM_BADEMAILS', 'a:1:{i:0;s:10:"xoops.org$";}', '_MD_AM_BADEMAILSDSC', 'textarea', 'array', 26);
INSERT INTO xoops_config VALUES (36, 0, 2, 'maxuname', '_MD_AM_MAXUNAME', '10', '_MD_AM_MAXUNAMEDSC', 'textbox', 'int', 3);
INSERT INTO xoops_config VALUES (37, 0, 1, 'bad_ips', '_MD_AM_BADIPS', 'a:1:{i:0;s:9:"127.0.0.1";}', '_MD_AM_BADIPSDSC', 'textarea', 'array', 42);
INSERT INTO xoops_config VALUES (38, 0, 3, 'meta_keywords', '_MD_AM_METAKEY', 'Project JEDI, JEDI, open source, Delphi, API translations, JEDI Code Library, JEDI Visual Component Library, JEDI VCL, JCL, JVCL, JVCS', '_MD_AM_METAKEYDSC', 'textarea', 'text', 0);
INSERT INTO xoops_config VALUES (39, 0, 3, 'footer', '_MD_AM_FOOTER', '<hr> <br>\r\nThis site and the pages contained within are Copyright © 1997-2003, Project JEDI. For questions and comments regarding this site please contact the Webmaster.', '_MD_AM_FOOTERDSC', 'textarea', 'text', 20);
INSERT INTO xoops_config VALUES (40, 0, 4, 'censor_enable', '_MD_AM_DOCENSOR', '0', '_MD_AM_DOCENSORDSC', 'yesno', 'int', 0);
INSERT INTO xoops_config VALUES (41, 0, 4, 'censor_words', '_MD_AM_CENSORWRD', 'a:2:{i:0;s:4:"fuck";i:1;s:4:"shit";}', '_MD_AM_CENSORWRDDSC', 'textarea', 'array', 1);
INSERT INTO xoops_config VALUES (42, 0, 4, 'censor_replace', '_MD_AM_CENSORRPLC', '#OOPS#', '_MD_AM_CENSORRPLCDSC', 'textbox', 'text', 2);
INSERT INTO xoops_config VALUES (43, 0, 3, 'meta_robots', '_MD_AM_METAROBOTS', 'index,follow', '_MD_AM_METAROBOTSDSC', 'select', 'text', 2);
INSERT INTO xoops_config VALUES (44, 0, 5, 'enable_search', '_MD_AM_DOSEARCH', '1', '_MD_AM_DOSEARCHDSC', 'yesno', 'int', 0);
INSERT INTO xoops_config VALUES (45, 0, 5, 'keyword_min', '_MD_AM_MINSEARCH', '3', '_MD_AM_MINSEARCHDSC', 'textbox', 'int', 1);
INSERT INTO xoops_config VALUES (46, 0, 2, 'avatar_minposts', '_MD_AM_AVATARMP', '0', '_MD_AM_AVATARMPDSC', 'textbox', 'int', 15);
INSERT INTO xoops_config VALUES (47, 0, 1, 'enable_badips', '_MD_AM_DOBADIPS', '0', '_MD_AM_DOBADIPSDSC', 'yesno', 'int', 40);
INSERT INTO xoops_config VALUES (48, 0, 3, 'meta_rating', '_MD_AM_METARATING', 'general', '_MD_AM_METARATINGDSC', 'select', 'text', 4);
INSERT INTO xoops_config VALUES (49, 0, 3, 'meta_author', '_MD_AM_METAAUTHOR', 'Project JEDI', '_MD_AM_METAAUTHORDSC', 'textbox', 'text', 6);
INSERT INTO xoops_config VALUES (50, 0, 3, 'meta_copyright', '_MD_AM_METACOPYR', 'Copyright © 2001-2003', '_MD_AM_METACOPYRDSC', 'textbox', 'text', 8);
INSERT INTO xoops_config VALUES (51, 0, 3, 'meta_description', '_MD_AM_METADESC', 'Project JEDI is an international community of Delphi developers with a mission to exploit our pooled efforts, experiences and resources to make Delphi and Kylix--the greatest Windows and Linux application development  tools--even greater.', '_MD_AM_METADESCDSC', 'textarea', 'text', 1);
INSERT INTO xoops_config VALUES (52, 0, 2, 'allow_chgmail', '_MD_AM_ALLWCHGMAIL', '0', '_MD_AM_ALLWCHGMAILDSC', 'yesno', 'int', 3);
INSERT INTO xoops_config VALUES (53, 0, 1, 'use_mysession', '_MD_AM_USEMYSESS', '0', '_MD_AM_USEMYSESSDSC', 'yesno', 'int', 19);
INSERT INTO xoops_config VALUES (54, 0, 2, 'reg_dispdsclmr', '_MD_AM_DSPDSCLMR', '1', '_MD_AM_DSPDSCLMRDSC', 'yesno', 'int', 30);
INSERT INTO xoops_config VALUES (55, 0, 2, 'reg_disclaimer', '_MD_AM_REGDSCLMR', 'While the administrators and moderators of this site will attempt to remove or edit any generally objectionable material as quickly as possible, it is impossible to review every message. Therefore you acknowledge that all posts made to this site express the views and opinions of the author and not the administrators, moderators or webmaster (except for posts by these people) and hence will not be held liable. \r\n\r\nYou agree not to post any abusive, obscene, vulgar, slanderous, hateful, threatening, sexually-orientated or any other material that may violate any applicable laws. Furthermore material that is an invasion of anyone\'s privacy, harmful to other users, or harmful to the interests of Project JEDI, its sponsors or individuals in its community must not be posted.\r\n\r\nDoing so may lead to you being immediately and permanently banned (and your service provider being informed). The IP address of all posts is recorded to aid in enforcing these conditions. Creating multiple accounts for a single user is not allowed. You agree that the webmaster, administrator and moderators of this site have the right to remove, edit, move or close any topic at any time should they see fit. As a user you agree to any information you have entered above being stored in a database. While this information will not be disclosed to any third party without your consent Project JEDI, the webmaster, administrator and moderators cannot be held responsible for any hacking attempt that may lead to the data being compromised. \r\n\r\nThis site system uses cookies to store information on your local computer. These cookies do not contain any of the information you have entered above, they serve only to improve your viewing pleasure. The email address is used only for confirming your registration details and password (and for sending new passwords should you forget your current one). \r\n\r\nBy clicking Register below you agree to be bound by these conditions.', '_MD_AM_REGDSCLMRDSC', 'textarea', 'text', 32);
INSERT INTO xoops_config VALUES (56, 0, 2, 'allow_register', '_MD_AM_ALLOWREG', '1', '_MD_AM_ALLOWREGDSC', 'yesno', 'int', 0);
INSERT INTO xoops_config VALUES (57, 0, 1, 'theme_fromfile', '_MD_AM_THEMEFILE', '1', '_MD_AM_THEMEFILEDSC', 'yesno', 'int', 13);
INSERT INTO xoops_config VALUES (58, 0, 1, 'closesite', '_MD_AM_CLOSESITE', '0', '_MD_AM_CLOSESITEDSC', 'yesno', 'int', 26);
INSERT INTO xoops_config VALUES (59, 0, 1, 'closesite_okgrp', '_MD_AM_CLOSESITEOK', 'a:1:{i:0;s:1:"1";}', '_MD_AM_CLOSESITEOKDSC', 'group_multi', 'array', 27);
INSERT INTO xoops_config VALUES (60, 0, 1, 'closesite_text', '_MD_AM_CLOSESITETXT', 'The site is currently closed for maintainance. Please come back later.', '_MD_AM_CLOSESITETXTDSC', 'textarea', 'text', 28);
INSERT INTO xoops_config VALUES (61, 0, 1, 'sslpost_name', '_MD_AM_SSLPOST', 'jedi_ssl', '_MD_AM_SSLPOSTDSC', 'textbox', 'text', 31);
INSERT INTO xoops_config VALUES (62, 0, 1, 'module_cache', '_MD_AM_MODCACHE', 'a:13:{i:2;s:1:"0";i:3;s:1:"0";i:4;s:1:"0";i:5;s:1:"0";i:6;s:1:"0";i:8;s:1:"0";i:11;s:1:"0";i:12;s:1:"0";i:13;s:1:"0";i:14;s:1:"0";i:15;s:1:"0";i:19;s:1:"0";i:7;s:1:"0";}', '_MD_AM_MODCACHEDSC', 'module_cache', 'array', 50);
INSERT INTO xoops_config VALUES (63, 0, 1, 'template_set', '_MD_AM_DTPLSET', 'Project JEDI', '_MD_AM_DTPLSETDSC', 'tplset', 'other', 14);
INSERT INTO xoops_config VALUES (64, 0, 6, 'mailmethod', '_MD_AM_MAILERMETHOD', 'mail', '_MD_AM_MAILERMETHODDESC', 'select', 'text', 4);
INSERT INTO xoops_config VALUES (65, 0, 6, 'smtphost', '_MD_AM_SMTPHOST', 'a:1:{i:0;s:0:"";}', '_MD_AM_SMTPHOSTDESC', 'textarea', 'array', 6);
INSERT INTO xoops_config VALUES (66, 0, 6, 'smtpuser', '_MD_AM_SMTPUSER', '', '_MD_AM_SMTPUSERDESC', 'textbox', 'text', 7);
INSERT INTO xoops_config VALUES (67, 0, 6, 'smtppass', '_MD_AM_SMTPPASS', '', '_MD_AM_SMTPPASSDESC', 'password', 'text', 8);
INSERT INTO xoops_config VALUES (68, 0, 6, 'sendmailpath', '_MD_AM_SENDMAILPATH', '/usr/sbin/sendmail', '_MD_AM_SENDMAILPATHDESC', 'textbox', 'text', 5);
INSERT INTO xoops_config VALUES (69, 0, 6, 'from', '_MD_AM_MAILFROM', '', '_MD_AM_MAILFROMDESC', 'textbox', 'text', 1);
INSERT INTO xoops_config VALUES (70, 0, 6, 'fromname', '_MD_AM_MAILFROMNAME', '', '_MD_AM_MAILFROMNAMEDESC', 'textbox', 'text', 2);
INSERT INTO xoops_config VALUES (71, 0, 1, 'sslloginlink', '_MD_AM_SSLLINK', 'https://', '_MD_AM_SSLLINKDSC', 'textbox', 'text', 33);
INSERT INTO xoops_config VALUES (72, 0, 1, 'theme_set_allowed', '_MD_AM_THEMEOK', 'a:1:{i:0;s:7:"phpkaox";}', '_MD_AM_THEMEOKDSC', 'theme_multi', 'array', 13);
INSERT INTO xoops_config VALUES (73, 0, 6, 'fromuid', '_MD_AM_MAILFROMUID', '1', '_MD_AM_MAILFROMUIDDESC', 'user', 'int', 3);
INSERT INTO xoops_config VALUES (132, 2, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 7);
INSERT INTO xoops_config VALUES (131, 2, 0, 'news_lbrconversion', '_MI_LBRCONVERT', '0', '_MI_LBRCONVERT_DESC', 'yesno', 'int', 6);
INSERT INTO xoops_config VALUES (130, 2, 0, 'news_user_wysiwyg', '_MI_USER_WYSIWYG', '0', '_MI_USER_WYSIWYG_DESC', 'yesno', 'int', 5);
INSERT INTO xoops_config VALUES (129, 2, 0, 'news_wysiwyg', '_MI_WYSIWYG', '0', '_MI_WYSIWYG_DESC', 'yesno', 'int', 4);
INSERT INTO xoops_config VALUES (125, 2, 0, 'storyhome', '_MI_STORYHOME', '5', '_MI_STORYHOMEDSC', 'select', 'int', 0);
INSERT INTO xoops_config VALUES (126, 2, 0, 'displaynav', '_MI_DISPLAYNAV', '0', '_MI_DISPLAYNAVDSC', 'yesno', 'int', 1);
INSERT INTO xoops_config VALUES (127, 2, 0, 'anonpost', '_MI_ANONPOST', '0', '', 'yesno', 'int', 2);
INSERT INTO xoops_config VALUES (128, 2, 0, 'autoapprove', '_MI_AUTOAPPROVE', '0', '_MI_AUTOAPPROVEDSC', 'yesno', 'int', 3);
INSERT INTO xoops_config VALUES (82, 4, 0, 'popular', '_MI_MYDOWNLOADS_POPULAR', '500', '_MI_MYDOWNLOADS_POPULARDSC', 'select', 'int', 0);
INSERT INTO xoops_config VALUES (83, 4, 0, 'newdownloads', '_MI_MYDOWNLOADS_NEWDLS', '5', '_MI_MYDOWNLOADS_NEWDLSDSC', 'select', 'int', 1);
INSERT INTO xoops_config VALUES (84, 4, 0, 'perpage', '_MI_MYDOWNLOADS_PERPAGE', '15', '_MI_MYDOWNLOADS_PERPAGEDSC', 'select', 'int', 2);
INSERT INTO xoops_config VALUES (85, 4, 0, 'anonpost', '_MI_MYDOWNLOADS_ANONPOST', '1', '', 'yesno', 'int', 3);
INSERT INTO xoops_config VALUES (86, 4, 0, 'autoapprove', '_MI_MYDOWNLOADS_AUTOAPPROVE', '0', '_MI_MYDOWNLOADS_AUTOAPPROVEDSC', 'yesno', 'int', 4);
INSERT INTO xoops_config VALUES (87, 4, 0, 'useshots', '_MI_MYDOWNLOADS_USESHOTS', '1', '_MI_MYDOWNLOADS_USESHOTSDSC', 'yesno', 'int', 5);
INSERT INTO xoops_config VALUES (88, 4, 0, 'shotwidth', '_MI_MYDOWNLOADS_SHOTWIDTH', '140', '_MI_MYDOWNLOADS_SHOTWIDTHDSC', 'textbox', 'int', 6);
INSERT INTO xoops_config VALUES (89, 4, 0, 'check_host', '_MI_MYDOWNLOADS_CHECKHOST', '0', '', 'yesno', 'int', 7);
INSERT INTO xoops_config VALUES (90, 4, 0, 'referers', '_MI_MYDOWNLOADS_REFERERS', 'a:1:{i:0;s:17:"l3s1272.zeus01.de";}', '_MI_MYDOWNLOADS_REFERERSDSC', 'textarea', 'array', 8);
INSERT INTO xoops_config VALUES (91, 4, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 9);
INSERT INTO xoops_config VALUES (92, 4, 0, 'com_anonpost', '_CM_COMANONPOST', '1', '', 'yesno', 'int', 10);
INSERT INTO xoops_config VALUES (93, 4, 0, 'notification_enabled', '_NOT_CONFIG_ENABLE', '3', '_NOT_CONFIG_ENABLEDSC', 'select', 'int', 11);
INSERT INTO xoops_config VALUES (94, 4, 0, 'notification_events', '_NOT_CONFIG_EVENTS', 'a:11:{i:0;s:19:"global-new_category";i:1;s:18:"global-file_modify";i:2;s:18:"global-file_broken";i:3;s:18:"global-file_submit";i:4;s:15:"global-new_file";i:5;s:20:"category-file_submit";i:6;s:17:"category-new_file";i:7;s:17:"category-bookmark";i:8;s:12:"file-comment";i:9;s:19:"file-comment_submit";i:10;s:13:"file-bookmark";}', '_NOT_CONFIG_EVENTSDSC', 'select_multi', 'array', 12);
INSERT INTO xoops_config VALUES (95, 8, 0, 'cookietime', '_MI_RECLICK', '86400', '', 'select', 'int', 0);
INSERT INTO xoops_config VALUES (96, 8, 0, 'modlimit', '_MI_MLIMIT', '15', '_MI_MLIMITDSC', 'textbox', 'int', 1);
INSERT INTO xoops_config VALUES (97, 8, 0, 'modshow', '_MI_MSHOW', '1', '_MI_MSHOWDSC', 'select', 'int', 2);
INSERT INTO xoops_config VALUES (98, 8, 0, 'modorder', '_MI_MORDER', 'weight', '_MI_MORDERDSC', 'select', 'text', 3);
INSERT INTO xoops_config VALUES (99, 8, 0, 'modorderd', '_MI_MORDER', 'DESC', '_MI_MORDERDSC', 'select', 'text', 4);
INSERT INTO xoops_config VALUES (100, 11, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 0);
INSERT INTO xoops_config VALUES (101, 11, 0, 'com_anonpost', '_CM_COMANONPOST', '0', '', 'yesno', 'int', 1);
INSERT INTO xoops_config VALUES (102, 12, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 0);
INSERT INTO xoops_config VALUES (103, 12, 0, 'com_anonpost', '_CM_COMANONPOST', '0', '', 'yesno', 'int', 1);
INSERT INTO xoops_config VALUES (104, 13, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 0);
INSERT INTO xoops_config VALUES (105, 13, 0, 'com_anonpost', '_CM_COMANONPOST', '0', '', 'yesno', 'int', 1);
INSERT INTO xoops_config VALUES (106, 15, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 0);
INSERT INTO xoops_config VALUES (107, 15, 0, 'com_anonpost', '_CM_COMANONPOST', '0', '', 'yesno', 'int', 1);
INSERT INTO xoops_config VALUES (133, 2, 0, 'com_anonpost', '_CM_COMANONPOST', '0', '', 'yesno', 'int', 8);
INSERT INTO xoops_config VALUES (123, 19, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 1);
INSERT INTO xoops_config VALUES (124, 19, 0, 'com_anonpost', '_CM_COMANONPOST', '0', '', 'yesno', 'int', 2);
INSERT INTO xoops_config VALUES (122, 19, 0, 'jvcs_wysiwyg', '_MI_WYSIWYG', '1', '_MI_WYSIWYG_DESC', 'yesno', 'int', 0);
INSERT INTO xoops_config VALUES (134, 2, 0, 'notification_enabled', '_NOT_CONFIG_ENABLE', '3', '_NOT_CONFIG_ENABLEDSC', 'select', 'int', 9);
INSERT INTO xoops_config VALUES (135, 2, 0, 'notification_events', '_NOT_CONFIG_EVENTS', 'a:6:{i:0;s:19:"global-new_category";i:1;s:19:"global-story_submit";i:2;s:16:"global-new_story";i:3;s:13:"story-comment";i:4;s:20:"story-comment_submit";i:5;s:14:"story-bookmark";}', '_NOT_CONFIG_EVENTSDSC', 'select_multi', 'array', 10);
INSERT INTO xoops_config VALUES (155, 20, 0, 'perpage', '_MI_CHAN_PERPAGE', '10', '_MI_MYDOWNLOADS_PERPAGEDSC', 'select', 'int', 6);
INSERT INTO xoops_config VALUES (154, 20, 0, 'maximgheight', '_MI_CHAN_IMGHEIGHT', '600', '_MI_CHAN_IMGHEIGHTDSC', 'textbox', 'int', 5);
INSERT INTO xoops_config VALUES (153, 20, 0, 'maximgwidth', '_MI_CHAN_IMGWIDTH', '600', '_MI_CHAN_IMGWIDTHDSC', 'textbox', 'int', 4);
INSERT INTO xoops_config VALUES (152, 20, 0, 'maxfilesize', '_MI_CHAN_MAXFILESIZE', '50000', '_MI_CHAN_MAXFILESIZEDSC', 'textbox', 'int', 3);
INSERT INTO xoops_config VALUES (151, 20, 0, 'linkimages', '_MI_CHAN_LINKIMAGES', 'modules/wfchannel/images/linkimages', '_MI_CHAN_UPLOADDIRDSC', 'textbox', 'text', 2);
INSERT INTO xoops_config VALUES (150, 20, 0, 'uploaddir', '_MI_CHAN_UPLOADDIR', 'modules/wfchannel/images', '_MI_CHAN_UPLOADDIRDSC', 'textbox', 'text', 1);
INSERT INTO xoops_config VALUES (149, 20, 0, 'htmluploaddir', '_MI_CHAN_HTMLUPLOADDIR', 'modules/wfchannel/html', '_MI_CHAN_HTMLUPLOADDIRDSC', 'textbox', 'text', 0);
INSERT INTO xoops_config VALUES (147, 21, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 0);
INSERT INTO xoops_config VALUES (148, 21, 0, 'com_anonpost', '_CM_COMANONPOST', '0', '', 'yesno', 'int', 1);
INSERT INTO xoops_config VALUES (156, 20, 0, 'anonlink', '_MI_CHAN_LINK', '0', '', 'yesno', 'int', 7);
INSERT INTO xoops_config VALUES (157, 20, 0, 'anonrefer', '_MI_CHAN_ANONREFER', '0', '', 'yesno', 'int', 8);
INSERT INTO xoops_config VALUES (158, 20, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 9);
INSERT INTO xoops_config VALUES (159, 20, 0, 'com_anonpost', '_CM_COMANONPOST', '0', '', 'yesno', 'int', 10);
INSERT INTO xoops_config VALUES (160, 22, 0, 'jgraphics_wysiwyg', '_MI_WYSIWYG', '0', '_MI_WYSIWYG_DESC', 'yesno', 'int', 0);
INSERT INTO xoops_config VALUES (161, 22, 0, 'com_rule', '_CM_COMRULES', '1', '', 'select', 'int', 1);
INSERT INTO xoops_config VALUES (162, 22, 0, 'com_anonpost', '_CM_COMANONPOST', '0', '', 'yesno', 'int', 2);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_configcategory`
#

DROP TABLE IF EXISTS xoops_configcategory;
CREATE TABLE xoops_configcategory (
  confcat_id smallint(5) unsigned NOT NULL auto_increment,
  confcat_name varchar(25) NOT NULL default '',
  confcat_order smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (confcat_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_configcategory`
#

INSERT INTO xoops_configcategory VALUES (1, '_MD_AM_GENERAL', 0);
INSERT INTO xoops_configcategory VALUES (2, '_MD_AM_USERSETTINGS', 0);
INSERT INTO xoops_configcategory VALUES (3, '_MD_AM_METAFOOTER', 0);
INSERT INTO xoops_configcategory VALUES (4, '_MD_AM_CENSOR', 0);
INSERT INTO xoops_configcategory VALUES (5, '_MD_AM_SEARCH', 0);
INSERT INTO xoops_configcategory VALUES (6, '_MD_AM_MAILER', 0);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_configoption`
#

DROP TABLE IF EXISTS xoops_configoption;
CREATE TABLE xoops_configoption (
  confop_id mediumint(8) unsigned NOT NULL auto_increment,
  confop_name varchar(255) NOT NULL default '',
  confop_value varchar(255) NOT NULL default '',
  conf_id smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (confop_id),
  KEY conf_id (conf_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_configoption`
#

INSERT INTO xoops_configoption VALUES (1, '_MD_AM_DEBUGMODE1', '1', 13);
INSERT INTO xoops_configoption VALUES (2, '_MD_AM_DEBUGMODE2', '2', 13);
INSERT INTO xoops_configoption VALUES (3, '_NESTED', 'nest', 32);
INSERT INTO xoops_configoption VALUES (4, '_FLAT', 'flat', 32);
INSERT INTO xoops_configoption VALUES (5, '_THREADED', 'thread', 32);
INSERT INTO xoops_configoption VALUES (6, '_OLDESTFIRST', '0', 33);
INSERT INTO xoops_configoption VALUES (7, '_NEWESTFIRST', '1', 33);
INSERT INTO xoops_configoption VALUES (8, '_MD_AM_USERACTV', '0', 21);
INSERT INTO xoops_configoption VALUES (9, '_MD_AM_AUTOACTV', '1', 21);
INSERT INTO xoops_configoption VALUES (10, '_MD_AM_ADMINACTV', '2', 21);
INSERT INTO xoops_configoption VALUES (11, '_MD_AM_STRICT', '0', 23);
INSERT INTO xoops_configoption VALUES (12, '_MD_AM_MEDIUM', '1', 23);
INSERT INTO xoops_configoption VALUES (13, '_MD_AM_LIGHT', '2', 23);
INSERT INTO xoops_configoption VALUES (14, '_MD_AM_DEBUGMODE3', '3', 13);
INSERT INTO xoops_configoption VALUES (15, '_MD_AM_INDEXFOLLOW', 'index,follow', 43);
INSERT INTO xoops_configoption VALUES (16, '_MD_AM_NOINDEXFOLLOW', 'noindex,follow', 43);
INSERT INTO xoops_configoption VALUES (17, '_MD_AM_INDEXNOFOLLOW', 'index,nofollow', 43);
INSERT INTO xoops_configoption VALUES (18, '_MD_AM_NOINDEXNOFOLLOW', 'noindex,nofollow', 43);
INSERT INTO xoops_configoption VALUES (19, '_MD_AM_METAOGEN', 'general', 48);
INSERT INTO xoops_configoption VALUES (20, '_MD_AM_METAO14YRS', '14 years', 48);
INSERT INTO xoops_configoption VALUES (21, '_MD_AM_METAOREST', 'restricted', 48);
INSERT INTO xoops_configoption VALUES (22, '_MD_AM_METAOMAT', 'mature', 48);
INSERT INTO xoops_configoption VALUES (23, '_MD_AM_DEBUGMODE0', '0', 13);
INSERT INTO xoops_configoption VALUES (24, 'PHP mail()', 'mail', 64);
INSERT INTO xoops_configoption VALUES (25, 'sendmail', 'sendmail', 64);
INSERT INTO xoops_configoption VALUES (26, 'SMTP', 'smtp', 64);
INSERT INTO xoops_configoption VALUES (27, 'SMTPAuth', 'smtpauth', 64);
INSERT INTO xoops_configoption VALUES (160, 'Global : New Topic', 'global-new_category', 135);
INSERT INTO xoops_configoption VALUES (159, '_NOT_CONFIG_ENABLEBOTH', '3', 134);
INSERT INTO xoops_configoption VALUES (158, '_NOT_CONFIG_ENABLEINLINE', '2', 134);
INSERT INTO xoops_configoption VALUES (157, '_NOT_CONFIG_ENABLEBLOCK', '1', 134);
INSERT INTO xoops_configoption VALUES (156, '_NOT_CONFIG_DISABLE', '0', 134);
INSERT INTO xoops_configoption VALUES (155, '_CM_COMAPPROVEADMIN', '3', 132);
INSERT INTO xoops_configoption VALUES (154, '_CM_COMAPPROVEUSER', '2', 132);
INSERT INTO xoops_configoption VALUES (153, '_CM_COMAPPROVEALL', '1', 132);
INSERT INTO xoops_configoption VALUES (152, '_CM_COMNOCOM', '0', 132);
INSERT INTO xoops_configoption VALUES (151, '30', '30', 125);
INSERT INTO xoops_configoption VALUES (150, '25', '25', 125);
INSERT INTO xoops_configoption VALUES (149, '20', '20', 125);
INSERT INTO xoops_configoption VALUES (148, '15', '15', 125);
INSERT INTO xoops_configoption VALUES (147, '10', '10', 125);
INSERT INTO xoops_configoption VALUES (146, '5', '5', 125);
INSERT INTO xoops_configoption VALUES (48, '5', '5', 82);
INSERT INTO xoops_configoption VALUES (49, '10', '10', 82);
INSERT INTO xoops_configoption VALUES (50, '50', '50', 82);
INSERT INTO xoops_configoption VALUES (51, '100', '100', 82);
INSERT INTO xoops_configoption VALUES (52, '200', '200', 82);
INSERT INTO xoops_configoption VALUES (53, '500', '500', 82);
INSERT INTO xoops_configoption VALUES (54, '1000', '1000', 82);
INSERT INTO xoops_configoption VALUES (55, '5', '5', 83);
INSERT INTO xoops_configoption VALUES (56, '10', '10', 83);
INSERT INTO xoops_configoption VALUES (57, '15', '15', 83);
INSERT INTO xoops_configoption VALUES (58, '20', '20', 83);
INSERT INTO xoops_configoption VALUES (59, '25', '25', 83);
INSERT INTO xoops_configoption VALUES (60, '30', '30', 83);
INSERT INTO xoops_configoption VALUES (61, '50', '50', 83);
INSERT INTO xoops_configoption VALUES (62, '5', '5', 84);
INSERT INTO xoops_configoption VALUES (63, '10', '10', 84);
INSERT INTO xoops_configoption VALUES (64, '15', '15', 84);
INSERT INTO xoops_configoption VALUES (65, '20', '20', 84);
INSERT INTO xoops_configoption VALUES (66, '25', '25', 84);
INSERT INTO xoops_configoption VALUES (67, '30', '30', 84);
INSERT INTO xoops_configoption VALUES (68, '50', '50', 84);
INSERT INTO xoops_configoption VALUES (69, '_CM_COMNOCOM', '0', 91);
INSERT INTO xoops_configoption VALUES (70, '_CM_COMAPPROVEALL', '1', 91);
INSERT INTO xoops_configoption VALUES (71, '_CM_COMAPPROVEUSER', '2', 91);
INSERT INTO xoops_configoption VALUES (72, '_CM_COMAPPROVEADMIN', '3', 91);
INSERT INTO xoops_configoption VALUES (73, '_NOT_CONFIG_DISABLE', '0', 93);
INSERT INTO xoops_configoption VALUES (74, '_NOT_CONFIG_ENABLEBLOCK', '1', 93);
INSERT INTO xoops_configoption VALUES (75, '_NOT_CONFIG_ENABLEINLINE', '2', 93);
INSERT INTO xoops_configoption VALUES (76, '_NOT_CONFIG_ENABLEBOTH', '3', 93);
INSERT INTO xoops_configoption VALUES (77, 'Global : New Category', 'global-new_category', 94);
INSERT INTO xoops_configoption VALUES (78, 'Global : Modify File Requested', 'global-file_modify', 94);
INSERT INTO xoops_configoption VALUES (79, 'Global : Broken File Submitted', 'global-file_broken', 94);
INSERT INTO xoops_configoption VALUES (80, 'Global : File Submitted', 'global-file_submit', 94);
INSERT INTO xoops_configoption VALUES (81, 'Global : New File', 'global-new_file', 94);
INSERT INTO xoops_configoption VALUES (82, 'Category : File Submitted', 'category-file_submit', 94);
INSERT INTO xoops_configoption VALUES (83, 'Category : New File', 'category-new_file', 94);
INSERT INTO xoops_configoption VALUES (84, 'Category : Bookmark', 'category-bookmark', 94);
INSERT INTO xoops_configoption VALUES (85, 'File : Comment Added', 'file-comment', 94);
INSERT INTO xoops_configoption VALUES (86, 'File : Comment Submitted', 'file-comment_submit', 94);
INSERT INTO xoops_configoption VALUES (87, 'File : Bookmark', 'file-bookmark', 94);
INSERT INTO xoops_configoption VALUES (88, '_MI_HOUR', '3600', 95);
INSERT INTO xoops_configoption VALUES (89, '_MI_3HOURS', '10800', 95);
INSERT INTO xoops_configoption VALUES (90, '_MI_5HOURS', '18000', 95);
INSERT INTO xoops_configoption VALUES (91, '_MI_10HOURS', '36000', 95);
INSERT INTO xoops_configoption VALUES (92, '_MI_DAY', '86400', 95);
INSERT INTO xoops_configoption VALUES (93, '_MI_IMAGES', '1', 97);
INSERT INTO xoops_configoption VALUES (94, '_MI_TEXT', '2', 97);
INSERT INTO xoops_configoption VALUES (95, '_MI_BOTH', '3', 97);
INSERT INTO xoops_configoption VALUES (96, '_MI_ID', 'id', 98);
INSERT INTO xoops_configoption VALUES (97, '_MI_HITS', 'hits', 98);
INSERT INTO xoops_configoption VALUES (98, '_MI_TITLE', 'title', 98);
INSERT INTO xoops_configoption VALUES (99, '_MI_WEIGHT', 'weight', 98);
INSERT INTO xoops_configoption VALUES (100, '_ASCENDING', 'ASC', 99);
INSERT INTO xoops_configoption VALUES (101, '_DESCENDING', 'DESC', 99);
INSERT INTO xoops_configoption VALUES (102, '_CM_COMNOCOM', '0', 100);
INSERT INTO xoops_configoption VALUES (103, '_CM_COMAPPROVEALL', '1', 100);
INSERT INTO xoops_configoption VALUES (104, '_CM_COMAPPROVEUSER', '2', 100);
INSERT INTO xoops_configoption VALUES (105, '_CM_COMAPPROVEADMIN', '3', 100);
INSERT INTO xoops_configoption VALUES (106, '_CM_COMNOCOM', '0', 102);
INSERT INTO xoops_configoption VALUES (107, '_CM_COMAPPROVEALL', '1', 102);
INSERT INTO xoops_configoption VALUES (108, '_CM_COMAPPROVEUSER', '2', 102);
INSERT INTO xoops_configoption VALUES (109, '_CM_COMAPPROVEADMIN', '3', 102);
INSERT INTO xoops_configoption VALUES (110, '_CM_COMNOCOM', '0', 104);
INSERT INTO xoops_configoption VALUES (111, '_CM_COMAPPROVEALL', '1', 104);
INSERT INTO xoops_configoption VALUES (112, '_CM_COMAPPROVEUSER', '2', 104);
INSERT INTO xoops_configoption VALUES (113, '_CM_COMAPPROVEADMIN', '3', 104);
INSERT INTO xoops_configoption VALUES (114, '_CM_COMNOCOM', '0', 106);
INSERT INTO xoops_configoption VALUES (115, '_CM_COMAPPROVEALL', '1', 106);
INSERT INTO xoops_configoption VALUES (116, '_CM_COMAPPROVEUSER', '2', 106);
INSERT INTO xoops_configoption VALUES (117, '_CM_COMAPPROVEADMIN', '3', 106);
INSERT INTO xoops_configoption VALUES (142, '_CM_COMNOCOM', '0', 123);
INSERT INTO xoops_configoption VALUES (143, '_CM_COMAPPROVEALL', '1', 123);
INSERT INTO xoops_configoption VALUES (144, '_CM_COMAPPROVEUSER', '2', 123);
INSERT INTO xoops_configoption VALUES (145, '_CM_COMAPPROVEADMIN', '3', 123);
INSERT INTO xoops_configoption VALUES (161, 'Global : New Story Submitted', 'global-story_submit', 135);
INSERT INTO xoops_configoption VALUES (162, 'Global : New Story', 'global-new_story', 135);
INSERT INTO xoops_configoption VALUES (163, 'Story : Comment Added', 'story-comment', 135);
INSERT INTO xoops_configoption VALUES (164, 'Story : Comment Submitted', 'story-comment_submit', 135);
INSERT INTO xoops_configoption VALUES (165, 'Story : Bookmark', 'story-bookmark', 135);
INSERT INTO xoops_configoption VALUES (189, '_CM_COMAPPROVEALL', '1', 158);
INSERT INTO xoops_configoption VALUES (188, '_CM_COMNOCOM', '0', 158);
INSERT INTO xoops_configoption VALUES (187, '50', '50', 155);
INSERT INTO xoops_configoption VALUES (186, '30', '30', 155);
INSERT INTO xoops_configoption VALUES (185, '25', '25', 155);
INSERT INTO xoops_configoption VALUES (184, '20', '20', 155);
INSERT INTO xoops_configoption VALUES (183, '15', '15', 155);
INSERT INTO xoops_configoption VALUES (182, '10', '10', 155);
INSERT INTO xoops_configoption VALUES (181, '5', '5', 155);
INSERT INTO xoops_configoption VALUES (177, '_CM_COMNOCOM', '0', 147);
INSERT INTO xoops_configoption VALUES (178, '_CM_COMAPPROVEALL', '1', 147);
INSERT INTO xoops_configoption VALUES (179, '_CM_COMAPPROVEUSER', '2', 147);
INSERT INTO xoops_configoption VALUES (180, '_CM_COMAPPROVEADMIN', '3', 147);
INSERT INTO xoops_configoption VALUES (190, '_CM_COMAPPROVEUSER', '2', 158);
INSERT INTO xoops_configoption VALUES (191, '_CM_COMAPPROVEADMIN', '3', 158);
INSERT INTO xoops_configoption VALUES (192, '_CM_COMNOCOM', '0', 161);
INSERT INTO xoops_configoption VALUES (193, '_CM_COMAPPROVEALL', '1', 161);
INSERT INTO xoops_configoption VALUES (194, '_CM_COMAPPROVEUSER', '2', 161);
INSERT INTO xoops_configoption VALUES (195, '_CM_COMAPPROVEADMIN', '3', 161);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_active_folder`
#

DROP TABLE IF EXISTS xoops_dms_active_folder;
CREATE TABLE xoops_dms_active_folder (
  user_id bigint(14) unsigned NOT NULL default '0',
  folder_id bigint(14) unsigned NOT NULL default '0'
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_active_folder`
#

INSERT INTO xoops_dms_active_folder VALUES (1, 1);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_config`
#

DROP TABLE IF EXISTS xoops_dms_config;
CREATE TABLE xoops_dms_config (
  name varchar(25) NOT NULL default '',
  data varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_config`
#

INSERT INTO xoops_dms_config VALUES ('doc_path', '/test');
INSERT INTO xoops_dms_config VALUES ('dms_title', 'Hello');
INSERT INTO xoops_dms_config VALUES ('max_file_sys_counter', '1000');
INSERT INTO xoops_dms_config VALUES ('template_root_obj_id', '1');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_exp_folders`
#

DROP TABLE IF EXISTS xoops_dms_exp_folders;
CREATE TABLE xoops_dms_exp_folders (
  user_id bigint(14) unsigned NOT NULL default '0',
  folder_id bigint(14) unsigned NOT NULL default '0'
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_exp_folders`
#

INSERT INTO xoops_dms_exp_folders VALUES (1, 1);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_file_sys_counters`
#

DROP TABLE IF EXISTS xoops_dms_file_sys_counters;
CREATE TABLE xoops_dms_file_sys_counters (
  layer_1 bigint(5) unsigned NOT NULL default '0',
  layer_2 bigint(5) unsigned NOT NULL default '0',
  layer_3 bigint(5) unsigned NOT NULL default '0',
  file bigint(5) unsigned NOT NULL default '0'
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_file_sys_counters`
#

INSERT INTO xoops_dms_file_sys_counters VALUES (1, 1, 1, 3);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_lifecycle_apply_perms`
#

DROP TABLE IF EXISTS xoops_dms_lifecycle_apply_perms;
CREATE TABLE xoops_dms_lifecycle_apply_perms (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  lifecycle_id bigint(14) unsigned NOT NULL default '0',
  user_id bigint(14) unsigned NOT NULL default '0',
  group_id bigint(14) unsigned NOT NULL default '0',
  user_perms tinyint(2) NOT NULL default '0',
  group_perms tinyint(2) NOT NULL default '0',
  everyone_perms tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (row_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_lifecycle_apply_perms`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_lifecycle_doc_perms`
#

DROP TABLE IF EXISTS xoops_dms_lifecycle_doc_perms;
CREATE TABLE xoops_dms_lifecycle_doc_perms (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  lifecycle_id bigint(14) unsigned NOT NULL default '0',
  lifecycle_stage tinyint(2) unsigned NOT NULL default '0',
  user_id bigint(14) unsigned NOT NULL default '0',
  group_id bigint(14) unsigned NOT NULL default '0',
  user_perms tinyint(2) NOT NULL default '0',
  group_perms tinyint(2) NOT NULL default '0',
  everyone_perms tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (row_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_lifecycle_doc_perms`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_lifecycle_stages`
#

DROP TABLE IF EXISTS xoops_dms_lifecycle_stages;
CREATE TABLE xoops_dms_lifecycle_stages (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  lifecycle_id bigint(14) unsigned NOT NULL default '0',
  lifecycle_stage tinyint(2) unsigned NOT NULL default '0',
  new_obj_location bigint(14) unsigned NOT NULL default '0',
  PRIMARY KEY  (row_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_lifecycle_stages`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_lifecycles`
#

DROP TABLE IF EXISTS xoops_dms_lifecycles;
CREATE TABLE xoops_dms_lifecycles (
  lifecycle_id bigint(14) unsigned NOT NULL auto_increment,
  lifecycle_name varchar(255) NOT NULL default '',
  lifecycle_descript varchar(255) NOT NULL default '',
  PRIMARY KEY  (lifecycle_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_lifecycles`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_object_perms`
#

DROP TABLE IF EXISTS xoops_dms_object_perms;
CREATE TABLE xoops_dms_object_perms (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  ptr_obj_id bigint(14) unsigned NOT NULL default '0',
  user_id bigint(14) unsigned NOT NULL default '0',
  group_id bigint(14) unsigned NOT NULL default '0',
  user_perms tinyint(2) NOT NULL default '0',
  group_perms tinyint(2) NOT NULL default '0',
  everyone_perms tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (row_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_object_perms`
#

INSERT INTO xoops_dms_object_perms VALUES (1, 1, 1, 0, 4, 0, 0);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_object_properties`
#

DROP TABLE IF EXISTS xoops_dms_object_properties;
CREATE TABLE xoops_dms_object_properties (
  obj_id bigint(14) unsigned NOT NULL default '0',
  obj_descript varchar(255) NOT NULL default '',
  obj_keywords varchar(255) NOT NULL default '',
  obj_authors varchar(255) NOT NULL default '',
  obj_mms_nums varchar(255) NOT NULL default '',
  PRIMARY KEY  (obj_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_object_properties`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_object_versions`
#

DROP TABLE IF EXISTS xoops_dms_object_versions;
CREATE TABLE xoops_dms_object_versions (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  obj_id bigint(14) unsigned NOT NULL default '0',
  major_version smallint(5) unsigned NOT NULL default '0',
  minor_version smallint(5) unsigned NOT NULL default '0',
  sub_minor_version smallint(5) unsigned NOT NULL default '0',
  file_path varchar(255) NOT NULL default '',
  file_name varchar(255) NOT NULL default '',
  file_type varchar(50) NOT NULL default '',
  file_size varchar(10) NOT NULL default '',
  PRIMARY KEY  (row_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_object_versions`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_objects`
#

DROP TABLE IF EXISTS xoops_dms_objects;
CREATE TABLE xoops_dms_objects (
  obj_id bigint(14) unsigned NOT NULL auto_increment,
  ptr_obj_id bigint(14) unsigned NOT NULL default '0',
  obj_type tinyint(2) unsigned NOT NULL default '0',
  obj_name varchar(255) NOT NULL default '',
  obj_status tinyint(2) unsigned NOT NULL default '0',
  obj_owner bigint(14) NOT NULL default '0',
  obj_checked_out_user_id bigint(14) NOT NULL default '0',
  current_version_row_id bigint(14) unsigned NOT NULL default '0',
  lifecycle_id bigint(14) unsigned NOT NULL default '0',
  lifecycle_stage bigint(14) unsigned NOT NULL default '0',
  lifecycle_suspend_flag tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (obj_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_objects`
#

INSERT INTO xoops_dms_objects VALUES (1, 0, 1, 'test', 0, 0, 0, 0, 0, 0, 0);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_dms_template_objects`
#

DROP TABLE IF EXISTS xoops_dms_template_objects;
CREATE TABLE xoops_dms_template_objects (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  obj_id bigint(14) unsigned NOT NULL default '0',
  PRIMARY KEY  (row_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_dms_template_objects`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_group_permission`
#

DROP TABLE IF EXISTS xoops_group_permission;
CREATE TABLE xoops_group_permission (
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

#
# Daten für Tabelle `xoops_group_permission`
#

INSERT INTO xoops_group_permission VALUES (1, 1, 1, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (2, 1, 1, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (203, 1, 24, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (5, 1, 1, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (202, 1, 7, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (8, 1, 2, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (11, 1, 3, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (201, 1, 7, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (827, 1, 39, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (14, 1, 4, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (830, 6, 14, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (1131, 3, 33, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (17, 1, 5, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1130, 3, 37, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (20, 1, 6, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (829, 6, 5, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (547, 4, 9, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (23, 1, 7, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (828, 2, 39, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (797, 2, 38, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (26, 1, 8, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (796, 1, 38, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (795, 2, 37, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (29, 1, 9, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (794, 2, 32, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1129, 3, 32, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (32, 1, 10, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (793, 2, 30, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1128, 3, 30, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (35, 1, 11, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (792, 2, 29, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1127, 3, 29, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (38, 1, 12, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (791, 2, 2, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1126, 3, 2, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (41, 1, 2, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (42, 1, 2, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (43, 1, 13, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (44, 1, 14, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (45, 1, 15, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (46, 1, 16, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (790, 2, 1, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (789, 2, 33, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (788, 2, 28, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (787, 2, 27, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (786, 2, 25, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1125, 3, 1, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1124, 3, 5, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1123, 3, 38, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1122, 3, 40, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1121, 3, 41, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (70, 1, 3, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (71, 1, 3, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (785, 2, 24, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1120, 3, 39, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (92, 1, 4, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (93, 1, 4, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (94, 1, 17, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (95, 1, 18, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (784, 2, 23, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (783, 2, 22, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (782, 2, 21, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1119, 3, 16, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (119, 1, 5, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (120, 1, 5, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (121, 1, 19, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (781, 2, 19, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (780, 2, 18, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (779, 2, 17, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1118, 3, 15, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1117, 3, 14, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (148, 1, 6, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (149, 1, 6, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (150, 1, 21, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (151, 1, 22, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (152, 1, 23, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (778, 2, 16, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (777, 2, 15, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (776, 2, 14, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (775, 2, 12, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (774, 2, 11, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (773, 2, 10, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1116, 3, 12, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1115, 3, 11, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (546, 4, 8, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (545, 4, 7, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (544, 4, 6, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (543, 4, 5, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (542, 4, 4, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (541, 4, 3, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (540, 4, 1, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (539, 4, 7, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (538, 4, 12, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (537, 4, 11, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (536, 4, 4, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (535, 4, 3, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (534, 4, 2, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (533, 4, 1, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (532, 4, 12, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (531, 4, 4, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (530, 4, 3, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (529, 4, 2, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (528, 4, 2, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (254, 1, 8, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (255, 1, 8, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (256, 1, 25, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (772, 2, 9, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (771, 2, 8, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (283, 1, 9, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (284, 1, 9, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (285, 1, 27, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (770, 2, 7, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (769, 2, 6, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (288, 1, 10, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (289, 1, 10, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (290, 1, 28, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (768, 2, 5, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (767, 2, 4, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1114, 3, 10, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (315, 1, 11, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (316, 1, 11, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (317, 1, 29, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (766, 2, 3, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (765, 2, 1, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1113, 3, 9, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1112, 3, 8, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (367, 1, 12, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (368, 1, 12, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (369, 1, 30, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (764, 2, 19, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (763, 2, 13, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1111, 3, 7, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1110, 3, 6, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (527, 4, 11, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (526, 4, 15, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (1109, 3, 4, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (525, 4, 4, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (524, 4, 1, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (523, 4, 7, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (522, 4, 14, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (521, 4, 5, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (548, 4, 11, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (549, 4, 12, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (550, 4, 14, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (551, 4, 15, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (552, 4, 16, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (553, 4, 17, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (554, 4, 18, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (555, 4, 19, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (556, 4, 21, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (557, 4, 22, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (558, 4, 23, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (559, 4, 24, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (560, 4, 1, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (561, 4, 2, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (562, 4, 13, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (563, 4, 29, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (564, 4, 30, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (565, 1, 32, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (762, 2, 12, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1108, 3, 3, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (594, 1, 13, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (595, 1, 13, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (761, 2, 11, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (597, 1, 14, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (598, 1, 14, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (760, 2, 4, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (600, 1, 15, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (601, 1, 15, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (602, 1, 33, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (759, 2, 3, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (758, 2, 2, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (678, 1, 19, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (679, 1, 19, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (680, 1, 37, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1107, 3, 1, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1106, 3, 22, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1105, 3, 21, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1104, 3, 20, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (751, 5, 8, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (750, 5, 1, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (1103, 3, 19, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (749, 5, 5, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (752, 5, 15, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (753, 5, 2, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (754, 5, 13, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (755, 5, 19, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (756, 5, 1, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (757, 5, 1, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (831, 6, 7, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (832, 6, 4, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (833, 6, 15, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (834, 6, 2, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (835, 6, 12, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (836, 6, 1, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (837, 6, 1, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (838, 1, 40, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (839, 2, 40, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1102, 3, 15, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (870, 1, 20, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (871, 1, 20, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (872, 2, 20, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1101, 3, 13, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (904, 1, 21, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (905, 1, 21, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (906, 2, 21, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1100, 3, 12, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1099, 3, 11, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1098, 3, 4, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1021, 7, 22, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (1020, 7, 2, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (1019, 7, 8, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (1018, 7, 7, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (1017, 7, 5, 1, 'system_admin');
INSERT INTO xoops_group_permission VALUES (1012, 1, 22, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (1013, 1, 22, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1014, 1, 41, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1015, 2, 22, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1016, 2, 41, 1, 'block_read');
INSERT INTO xoops_group_permission VALUES (1022, 7, 1, 1, 'module_admin');
INSERT INTO xoops_group_permission VALUES (1023, 7, 1, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1097, 3, 3, 1, 'module_read');
INSERT INTO xoops_group_permission VALUES (1096, 3, 2, 1, 'module_read');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_groups`
#

DROP TABLE IF EXISTS xoops_groups;
CREATE TABLE xoops_groups (
  groupid smallint(5) unsigned NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  description text NOT NULL,
  group_type varchar(10) NOT NULL default '',
  PRIMARY KEY  (groupid),
  KEY group_type (group_type)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_groups`
#

INSERT INTO xoops_groups VALUES (1, 'Webmasters', 'Webmasters of this site', 'Admin');
INSERT INTO xoops_groups VALUES (2, 'Registered Users', 'Registered Users Group', 'User');
INSERT INTO xoops_groups VALUES (3, 'Anonymous Users', 'Anonymous Users Group', 'Anonymous');
INSERT INTO xoops_groups VALUES (4, 'Team captain', '', 'Admin');
INSERT INTO xoops_groups VALUES (5, 'JEDI VCS Team', 'JEDI VCS Team Group - write access to VCS stuff.', 'Admin');
INSERT INTO xoops_groups VALUES (6, 'JEDI SDL Team', 'JEDI SDL team account.', 'Admin');
INSERT INTO xoops_groups VALUES (7, 'JEDI Graphics Team', '', 'Admin');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_groups_users_link`
#

DROP TABLE IF EXISTS xoops_groups_users_link;
CREATE TABLE xoops_groups_users_link (
  linkid mediumint(8) unsigned NOT NULL auto_increment,
  groupid smallint(5) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (linkid),
  KEY groupid_uid (groupid,uid)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_groups_users_link`
#

INSERT INTO xoops_groups_users_link VALUES (1, 1, 1);
INSERT INTO xoops_groups_users_link VALUES (2, 2, 1);
INSERT INTO xoops_groups_users_link VALUES (3, 2, 2);
INSERT INTO xoops_groups_users_link VALUES (4, 4, 2);
INSERT INTO xoops_groups_users_link VALUES (5, 2, 3);
INSERT INTO xoops_groups_users_link VALUES (6, 4, 3);
INSERT INTO xoops_groups_users_link VALUES (7, 2, 4);
INSERT INTO xoops_groups_users_link VALUES (8, 4, 4);
INSERT INTO xoops_groups_users_link VALUES (15, 2, 7);
INSERT INTO xoops_groups_users_link VALUES (10, 2, 5);
INSERT INTO xoops_groups_users_link VALUES (11, 2, 6);
INSERT INTO xoops_groups_users_link VALUES (13, 5, 6);
INSERT INTO xoops_groups_users_link VALUES (14, 5, 3);
INSERT INTO xoops_groups_users_link VALUES (16, 1, 7);
INSERT INTO xoops_groups_users_link VALUES (17, 2, 8);
INSERT INTO xoops_groups_users_link VALUES (18, 6, 4);
INSERT INTO xoops_groups_users_link VALUES (19, 1, 8);
INSERT INTO xoops_groups_users_link VALUES (20, 1, 5);
INSERT INTO xoops_groups_users_link VALUES (21, 2, 9);
INSERT INTO xoops_groups_users_link VALUES (22, 2, 10);
INSERT INTO xoops_groups_users_link VALUES (23, 2, 11);
INSERT INTO xoops_groups_users_link VALUES (24, 7, 10);
INSERT INTO xoops_groups_users_link VALUES (25, 7, 11);
INSERT INTO xoops_groups_users_link VALUES (26, 2, 12);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_image`
#

DROP TABLE IF EXISTS xoops_image;
CREATE TABLE xoops_image (
  image_id mediumint(8) unsigned NOT NULL auto_increment,
  image_name varchar(30) NOT NULL default '',
  image_nicename varchar(255) NOT NULL default '',
  image_mimetype varchar(30) NOT NULL default '',
  image_created int(10) unsigned NOT NULL default '0',
  image_display tinyint(1) unsigned NOT NULL default '0',
  image_weight smallint(5) unsigned NOT NULL default '0',
  imgcat_id smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (image_id),
  KEY imgcat_id (imgcat_id),
  KEY image_display (image_display)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_image`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_imagebody`
#

DROP TABLE IF EXISTS xoops_imagebody;
CREATE TABLE xoops_imagebody (
  image_id mediumint(8) unsigned NOT NULL default '0',
  image_body mediumblob,
  KEY image_id (image_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_imagebody`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_imagecategory`
#

DROP TABLE IF EXISTS xoops_imagecategory;
CREATE TABLE xoops_imagecategory (
  imgcat_id smallint(5) unsigned NOT NULL auto_increment,
  imgcat_name varchar(100) NOT NULL default '',
  imgcat_maxsize int(8) unsigned NOT NULL default '0',
  imgcat_maxwidth smallint(3) unsigned NOT NULL default '0',
  imgcat_maxheight smallint(3) unsigned NOT NULL default '0',
  imgcat_display tinyint(1) unsigned NOT NULL default '0',
  imgcat_weight smallint(3) unsigned NOT NULL default '0',
  imgcat_type char(1) NOT NULL default '',
  imgcat_storetype varchar(5) NOT NULL default '',
  PRIMARY KEY  (imgcat_id),
  KEY imgcat_display (imgcat_display)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_imagecategory`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_imenu`
#

DROP TABLE IF EXISTS xoops_imenu;
CREATE TABLE xoops_imenu (
  id int(5) unsigned NOT NULL auto_increment,
  title varchar(150) NOT NULL default '',
  hide tinyint(4) unsigned NOT NULL default '0',
  link varchar(255) default NULL,
  target varchar(255) default NULL,
  groups varchar(255) default NULL,
  users varchar(255) default NULL,
  weight tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY index (id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_imenu`
#

INSERT INTO xoops_imenu VALUES (12, 'JEDI VCS', 0, 'http://jedivcs.sourceforge.net', '_self', '1|2|3|4', '', 1);
INSERT INTO xoops_imenu VALUES (13, 'JEDI VCL', 0, '', '_self', '1|2|3|4|5', '', 0);
INSERT INTO xoops_imenu VALUES (11, 'Code Library', 0, 'http://homepages.borland.com/jedi/jcl', '_self', '1|2|3|4', '', 2);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_imgset`
#

DROP TABLE IF EXISTS xoops_imgset;
CREATE TABLE xoops_imgset (
  imgset_id smallint(5) unsigned NOT NULL auto_increment,
  imgset_name varchar(50) NOT NULL default '',
  imgset_refid mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (imgset_id),
  KEY imgset_refid (imgset_refid)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_imgset`
#

INSERT INTO xoops_imgset VALUES (1, 'default', 0);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_imgset_tplset_link`
#

DROP TABLE IF EXISTS xoops_imgset_tplset_link;
CREATE TABLE xoops_imgset_tplset_link (
  imgset_id smallint(5) unsigned NOT NULL default '0',
  tplset_name varchar(50) NOT NULL default '',
  KEY tplset_name (tplset_name(10))
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_imgset_tplset_link`
#

INSERT INTO xoops_imgset_tplset_link VALUES (1, 'default');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_imgsetimg`
#

DROP TABLE IF EXISTS xoops_imgsetimg;
CREATE TABLE xoops_imgsetimg (
  imgsetimg_id mediumint(8) unsigned NOT NULL auto_increment,
  imgsetimg_file varchar(50) NOT NULL default '',
  imgsetimg_body blob NOT NULL,
  imgsetimg_imgset smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (imgsetimg_id),
  KEY imgsetimg_imgset (imgsetimg_imgset)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_imgsetimg`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_jedifaq_categories`
#

DROP TABLE IF EXISTS xoops_jedifaq_categories;
CREATE TABLE xoops_jedifaq_categories (
  category_id tinyint(3) unsigned NOT NULL auto_increment,
  category_title varchar(255) NOT NULL default '',
  category_order tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (category_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_jedifaq_categories`
#

INSERT INTO xoops_jedifaq_categories VALUES (1, 'General Information', 0);
INSERT INTO xoops_jedifaq_categories VALUES (2, 'Licensing - General', 0);
INSERT INTO xoops_jedifaq_categories VALUES (3, 'Licensing - Author Perspective', 0);
INSERT INTO xoops_jedifaq_categories VALUES (4, 'Licensing - User Perspective', 0);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_jedifaq_contents`
#

DROP TABLE IF EXISTS xoops_jedifaq_contents;
CREATE TABLE xoops_jedifaq_contents (
  contents_id smallint(5) unsigned NOT NULL auto_increment,
  category_id tinyint(3) unsigned NOT NULL default '0',
  contents_title text NOT NULL,
  contents_contents text NOT NULL,
  contents_time int(10) unsigned NOT NULL default '0',
  contents_order smallint(5) unsigned NOT NULL default '0',
  contents_visible tinyint(1) unsigned NOT NULL default '1',
  contents_nohtml tinyint(1) unsigned NOT NULL default '0',
  contents_nosmiley tinyint(1) unsigned NOT NULL default '0',
  contents_noxcode tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (contents_id),
  KEY contents_visible_category_id (contents_visible,category_id)
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_jedifaq_contents`
#

INSERT INTO xoops_jedifaq_contents VALUES (1, 1, 'Where does the acronym come from?', 'The \'JEDI\' acronym for the [b]J[/b]oint [b]E[/b]ndeavor of [b]D[/b]elphi [b]I[/b]nnovators is inspired by the knights of the Rebel Alliance in George Lucas\' monumental Star Wars trilogy. It is the challenge of members of Project JEDI to provide balance in a microuniverse shrouded by the oppressive forces of the Empire. \r\n\r\nMay the source be with you.\r\n', 1070736564, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (2, 2, 'Project JEDI and the Open Source', 'Project JEDI is a strong advocate of the "Open Source" principle in software development. \r\nOur library of free Delphi source code comprises\r\n\r\n-  API Header Conversions\r\n-  Component and Utility packs related to API conversions\r\n-  JEDI Code Library (JCL)\r\n-  JEDI VCL (JVCL)\r\n-  Games and game engines \r\n \r\nThe JEDI libraries are being built up by way of both donations of completed or partly-completed work freely given by developers and "start-from-scratch" development projects involving teams of project members. \r\n\r\nThe choice of a licensing scheme for protecting the copyright of these people\'s work in the Public Domain, while keeping open the door for subsequent development, was made after an intense project researching the options. The driver of the original licensing project was Michael Beck.\r\n \r\n', 1070919425, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (3, 2, 'What license is used by Project JEDI?', 'The license we chose for all Project JEDI code was the [url=http://www.mozilla.org/MPL/MPL-1.1.html]Mozilla Public Licence ("MPL") version 1.1. [/url] \r\n\r\nMozilla is the Open Source initiative formulated by Netscape for the next generation of their web browsers. [url=http://www.mozilla.org/NPL/]Netscape states[/url]  "We believe this license satisfies the [url=http://www.debian.org/social_contract.html]Debian Free Software Guidelines[/url] which provide a commonly accepted definition of "free software," much like other free software licenses such as GPL or BSD."\r\n\r\nProject JEDI\'s implementation of the MPL allows developers to use its code in their applications ("Larger Work") regardless of whether the intended distribution will be in the public domain or as commercial applications, as long as the licence conditions are met. For a more detailed explanation, an [url=http://www.mozilla.org/MPL/annotated.html]annotated version[/url] of the MPL is available.\r\n  \r\nIf you have any comments or questions about licensing, please email them to us: [email]mailto:legal@delphi-jedi.org[/email]', 1070920349, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (4, 3, 'Do I retain copyright once I publish source under the MPL?', 'Absolutely. You still retain all your copyrights.', 1070920539, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (5, 3, 'Can I release the code under a different (possibly commercial type) license?', 'Yes. Since you have the original copyright, you can do it, but you can do it only for your own code, and not for any contributions from others.\r\n', 1070920629, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (6, 3, 'In two years Acme, Inc. comes with a great new license, which I would love to use. Am I always bound to MPL for my released code?', 'You can use a Dual License approach, i.e. you keep the code under MPL, and you add another license, e.g. GPL. The user will have then the option to use the one s/he prefers.\r\nOr, as the Initial Contributor, with the original copyright, you can release it under the other license. [i]Please note: even if you release the code under new license, users of your original MPL-released code can continue to use under MPL as before.[/i]', 1070920727, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (7, 3, 'I\'ve contributed code to JEDI under MPL, but now I\'ve changed my mind and don\'t want this code to be OpenSource.', 'Once you release code as OpenSource, you cannot take it back as long as the user conforms to the original license. Of course, as the Initial developer you can re-release it under different license, incl. a commercial one (see above) \r\n', 1070920788, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (8, 3, 'I think, JEDI could benefit from having cryptographic functions. I would like to donate some (DES, Tripple DES etc.), which are covered by patent rights (RSA, for example)? How should I do it?', 'All contributions are "Subject to third party intellectual property (IP) claims." Thus, if you are aware of any patents infringements, before submitting make sure that you: \r\n- secure the rights to use the IP in your contribution (e.g. by paying a fee) \r\n- modify the code so it doesn\'t infringe (in our case, provide other, non-patented cryptographic functions) \r\n- in a worst case scenario, if the two above are not possible, do not submit the code \r\n\r\n[i]Please note: different countries may have different patents laws. Therefore in some countries it could be legal to use patented IP (e.g. because the patent expired), while in others not. Check with your local Patent Office. [/i]', 1070920892, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (9, 4, 'Can I use the MPL code in commercial software? If yes, am I obligated to credit the author?', 'Yes, you can use the MPL code in any commercial software. Since you have to include the MPL code, the credit is included in the license header. While not required, it is also customary to credit the author in "AboutBox".\r\n', 1070920928, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (10, 4, 'Must I release the source code of used JEDI Code?', 'Only of those covered by MPL, together with any modifications to them.\r\n\r\n', 1070920972, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (11, 4, 'Must I publish my apps under MPL if I used MPL licensed code (the viral aspect) ?', 'No. That\'s the big advantage over GPL - you can use different code, mix MPL and commercial code, but you don\'t have to release either the application, nor the non-MPL code under MPL. Basically, what is MPL, will stay MPL, but it doesn\'t have any impact on the non-MPL code.\r\n\r\n', 1070920996, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (12, 4, 'If a bug in MPL licensed code renders my clients machine unbootable, who can I hold responsible for that?', 'Nobody. You use MPL licensed code at your own risk. Since it is provided to you in a source code form, you can inspect it, test it, making sure that it does, what you want it to do.\r\n', 1070921063, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (13, 4, 'Must I publish modifications to MPL licensed code?', 'Yes. This is one of the MPL requirements. You are getting a free source code, but you have to publish all modifications to the code, unless you have done the changes for your internal use.\r\n', 1070921084, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (14, 4, 'Must I publish code based on MPL licensed code under MPL?', 'Yes. You cannot change the license terms. Only the Initial Developer can add an additional license (see dual license)\r\n', 1070921105, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (15, 4, 'If I subclass the MPL code, do I still have to publish the new code? After all I didn\'t modify the code at all!', 'That\'s a tricky one. By the letter of the law, since you didn\'t touch the original code, you might claim that it is a "new" code, therefore no need for MPL. However, by the \'spirit of the law\', Inheritance (or subclassing) is a modification of the functionality of a given class, and as such a "derived work", so even if you didn\'t touch the original code, you are still making changes.\r\n\r\n', 1070921124, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (16, 4, 'I am proposing a modification to a JEDI-VCL component, which has a dual license (MPL and GPL). This new file also needs to include a new class. Should the source files for the new class be put in JEDI-VCL using MPL with GPL dual-license or can it be put in another location and use only the MPL?', 'The license of a file can\'t be changed without the consent of the copyright owner. And a new file derived from an existing file inherits the licensing from the existing file. In the case of this component, it has to stay MPL/GPL.\r\n', 1071026810, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (17, 4, 'Am I correct in assuming that simply including unmodified header files and linking with a library covered by the MPL does not place any legal restrictions or obligations on my commercial product and its source code?', 'It places no obligations on the code YOU wrote, but there are still obligations for the code you included. These include source distribution (for included MPL code, not YOUR code), and some notification requirements.\r\n', 1071036349, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (18, 4, 'I am considering using an XML parser that has being covered by the MPL v1.1 (or alternatively the GPL) in a commercial product. I will simply use the DLL libraries without modification, including the necessary header files in my own code. When I distribute (sell) my own product I would, of course, need to distribute the DLL libraries as well. Am I obligated to distribute the (unmodified) source code that produced the libraries with which I link?', 'No, you don\'t have to "physically" distribute the source code, but you have to give credit to the authors and provide a link to location where the users can download the source code (see next question) ', 1071036394, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (19, 4, 'Am I obligated to make my use of the particular libraries known to users of my product?', 'Absouletly, it\'s spelled out in the license. You need to credit the source of copyrighted code that is not yours in both the product and its documentation. ', 1071036417, 0, 1, 1, 0, 0);
INSERT INTO xoops_jedifaq_contents VALUES (20, 4, 'We are using JCL and JVCL in our products. We did not modify the source codes in any way. Can some one please tell me if we need to add a Copyright statement in our product\'s license text and if so, which text is required? ', 'This is normally done in "AboutBox" and the documentation. To make it simple, if you use JVCL you can add statement like:\r\n\r\n[i][b]"This program contains JVCL source code that can be obtained from http://jvcl.sourceforge.net" [/b][/i]\r\n\r\nor  \r\n\r\n[i][b]""JVCL portions are licensed from Project JEDI, and the source code can be obtained from http://jvcl.sourceforge.net" [/b][/i]\r\n \r\n\r\nIf you look at Microsoft Office programs, you can see bunch of similar legal statements in their "About Box".\r\n\r\nBy including such statement in the AboutBox AND your documentation, you have fulfilled the requirement to:\r\n\r\na) provide credit to the author \r\nb) provide the user with the location where they can download JVCL source code \r\n\r\n', 1071036534, 0, 1, 1, 0, 0);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `xoops_jedisdl`
#

DROP TABLE IF EXISTS xoops_jedisdl;
CREATE TABLE xoops_jedisdl (
  storyid int(8) NOT NULL auto_increment,
  blockid int(8) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  text text,
  visible tinyint(1) NOT NULL default '0',
  homepage tinyint(1) NOT NULL default '0',
  nohtml tinyint(1) NOT NULL default '0',
  nosmiley tinyint(1) NOT NULL default '0',
  nocomments tinyint(1) NOT NULL default '0',
  link tinyint(1) NOT NULL default '0',
  address varchar(255) default NULL,
  PRIMARY KEY  (storyid),
  KEY title (title(40))
) TYPE=MyISAM;

#
# Daten für Tabelle `xoops_jedisdl`
#

INSERT INTO xoops_jedisdl VALUES (1, 0, 'Home', '<h1>Welcome to the <i>JEDI</i>-SDL Home page.<br></h1>\r\n<p>\r\nHere you should be able to find most things related to the JEDI-SDL \r\nAPI Object Pascal header translations for <a href="http://www.libsdl.org">SDL</a>\r\n(Simple DirectMedia Library ). \r\n<a href="http://www.libsdl.org">SDL</a> works on \r\nLinux, Win32, Playstation 2, BeOS, MacOS, Solaris, IRIX, FreeBSD and AmigaOS.\r\n</p>\r\n\r\n<p>\r\nCurrently <i>JEDI</i>-SDL supports <a href="http://www.borland.com/delphi/">Delphi</a>, <a href="http://www.borland.com/kylix/">Kylix</a> and the <a href="http://www.freepascal.org/">FreePascal</a> compilers and the platforms that they support.\r\n</p>\r\n\r\n<h2>Status Report</h2>\r\n<font size="-1"><font color="#009900"><b>Monday 17th of February, 2003</b></font> - Update to JEDI-SDL Gallery page!<br>\r\nThe <a href="/Jedi:TEAM_SDL_GALLERY:468174">GALLERY</a> page now has more links and a few screen shots. I will put more up as people send them in.<br>\r\n<br>\r\n<font color="#009900"><b>Saturday 10th of August, 2002</b></font> - JEDI-SDL v0.5 has now available for public consumption!<br>\r\nThe feature list for v0.5 is as follows...<br>\r\n</font></font>\r\n<ul>\r\n<li><font size="+1"><font size="-1">The JEDI-SDL project is now also set up on Sourceforge ( <a href="http://sf.net/projects/jedi-sdl/">http://sf.net/projects/jedi-sdl/</a> ) so the latest code is\r\nalways available from there.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Windows - We now have a semi-automated setup under Windows ( thanks to David House and the Jedi JCL team ). Once you have extracted the zip file, simply double\r\nclick on the "JEDISDLWin32Installer.exe" to have the correct paths added to your respective IDEs. All IDEs from Delphi 4 - 7 are supported and it also adds a link to the .CHM help file under the\r\nTools menu.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Linux - Alternatively if you use Linux or want to to manually install the paths, then make sure you read the "Getting Started.html" file ( ideal for those who are\r\nnew to JEDI-SDL ) and is now included as a guide to help getting everything setup for smooth compilation.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Also included is a guide of how to use Sourceforge using TortoiseCVS under Windows ( Linux guide is under development ). Both documents can be found in the\r\n"documentation" directory.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Improved FreePascal support has been added.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Various bug fixes as pointed out on the JEDI-SDL mailing list.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">SDL_Mixer has been updated to version 1.2.1 and includes an Effects API.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Demo directories are now split into 2D and 3D related sub-directories.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">There are now both Kylix ( K prefix ) and Delphi ( D prefix ) project groups for all the demos. They can be found in Demos and the 2D and 3D\r\ndirectories.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">New Units</font></font>\r\n<ul>\r\n<li><font size="+1"><font size="-1">SDLStreams.pas - Chris Bruner has created a wrapper that uses Streams to load BMPs.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">SDLUtils.pas - Pascal only version of some Utility functions.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">SDLi386Utils.pas - Intel Assembler versions of the SDLUtils.pas functions.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">SDL_ttf.pas - Port of the SDL True Type font support unit.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">SDL_Sound.pas - Port of the SDL Sound library ( untested ).</font></font></li>\r\n</ul>\r\n</li>\r\n\r\n<li><font size="+1"><font size="-1">New 2D Demos</font></font>\r\n<ul>\r\n<li><font size="+1"><font size="-1">Pan and Zoom Demo - How to Pan and Zoom an SDL surface.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Isometric Demo - I ported my old DelphiX isometric demo over to SDL.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">TestTimer demo - Shows hows how to use AddTimer and RemoveTimer.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">MpegPlayer - I have updated and improved Anders Ohlsson\'s MPegPlayer and component and it now works and installs into D4, D5, D6, D7, K1, K2 &mp;\r\nK3.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Showfont - Demo to show how to use SDL_ttf.dll.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">SmpegPlayer - A console MPEG player that uses smpeg and SDL_Mixer.</font></font></li>\r\n</ul>\r\n</li>\r\n\r\n<li><font size="+1"><font size="-1">New 3D Demos</font></font>\r\n<ul>\r\n<li><font size="+1"><font size="-1">DeathTruckTion 1.1 - A slightly updated version of this fully functional 3D network game.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">TerrainDemo - Terrain demo ported from the book "OpenGL Game programming" by Hawkins and Astle.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">TestGL - the standard SDL/OpenGL Test demo. Shows how to mix 2D and 3D rendering using OpenGL.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">glfont - Demo to show how to us SDL_ttf with OpenGL.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Particle Engine - Ariel Jacob\'s OpenGL Particle Engine.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Motion Blur - Phil Freeman\'s Motion Blur Demo.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Dynamic Light - Phil Freeman\'s Dynamic Light Demo.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Environment Map - Phil Freeman\'s Environment Map Demo</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">GLMovie - is an MPEG Player that uses OpenGL to render the movie.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">NeHe - Quite a few more NeHe demos are now included.</font></font></li>\r\n</ul>\r\n</li>\r\n\r\n<li><font size="+1"><font size="-1">New Network Demos</font></font>\r\n<ul>\r\n<li><font size="+1"><font size="-1">There are now 3 SDL_Net Server demos and 4 SDL_Client demos as submitted by Dean Ellis.</font></font></li>\r\n</ul>\r\n</li>\r\n</ul>\r\n\r\n<font size="+1"><font size="-1"><font color="#009900"><b>Friday 22nd of February, 2002</b></font> - Just a quick note to say that Work is progressing on Version 0.5 beta and should be out soon.<br>\r\nI think I have fixed the links to the beta 4 release files, in the <a href="/Jedi:TEAM_SDL_DOWNLOADS:468174">downloads</a> section.<br>\r\n<br>\r\n<font color="#009900"><b>Tuesday 6th of November 2001</b></font> - I am pleased to announce that JEDI-SDL Beta 4 has been included on the soon to be released Kylix 2, Companion CD.<br>\r\nMy thanks go out to everyone who worked to make this possible.<br>\r\nDominique Louis.<br>\r\n<br>\r\n<font color="#009900"><b>Wednesday 10th of October 2001</b></font> - In the <a href="/Jedi:TEAM_SDL_DOWNLOADS:468174">downloads</a> section you should find the latest beta of the headers ( currently\r\nat Beta 4 ). Beta 4 brings the following enhancements...<br>\r\n</font></font>\r\n<ul>\r\n<li><font size="+1"><font size="-1">The JEDI-SDL home page is now located @ http://www.delphi-jedi.org/Jedi:TEAM_SDL_HOME</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">All Demos ( including OpenGL Demos ) now compile under both Kylix and Delphi.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">I have added quite a few more OpenGL examples, we are now up to Nehe tutorial 12.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">All OpenGL demos also show how to handle Window resizing.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Included an OpenGL demo called Puntos by Gustavo Maximo.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">I Ported Jan Horn\'s OpenGL MetaBalls and also SkyBox demo to SDL.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">I Ported Ilkka Tuomioja\'s OpenGL Quake 2 Model Viewer/Animator to SDL.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">NOTE : All OpenGL demos require Mike Lischke\'s OpenGL12.pas, which is now included in the distribution.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">I also fixed a conversion bug to do with SDL_MustLock and also a conversion omission to do with various events.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Fixed a conversion bug with SDL_CDOpen ( as suggested on the mailing list ).</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Added the GetPixel and PuxPixel functions to the SDLUtils.pas file.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Jason Farmer has donated SFont, a simple, yet effective Font library he converted for JEDI-SDL. It contains 4 Demos show how to best use it.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Added TUInt8Array and PUIntArray to SDL.pas after suggestions from Matthias Thoma and Eric Grange.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">In the <a href="/Jedi:TEAM_SDL_DOWNLOADS:468174">downloads</a> section there is a fully functional 3D network game called <a href= \r\n"ftp://delphi-jedi.org/api/Cross_Platform/files/JEDI-SDL/Demos/Dttsrc.zip">DeathTrucTion v1.0</a> written by the TNTeam that makes use of JEDI-SDL and is just too big to include with this\r\ndistribution but is well worth looking at as it works under Windows and Linux!</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Gustavo Maxima is working on translating the JEDI-SDL Documentation to Spanish and Portugese.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">The Mouse Demo has now been speeded up considerably and it is very responsive now.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Róbert Kisnémeth, has been hard at work, and has donated some new demos he has created with a SpriteEngine ( which he also donated ). He has also\r\ndonated a couple of games called BlitzBomber and Oxygene ( which uses the SpriteEngine ) and added a couple of useful functions to SDLUtils.pas. The Functions added are SDL_FlipV, SDL_FlipH,\r\nSDL_NewPutPixel ( assembler version ), SDL_AddPixel, SDL_SubPixel, SDL_DrawLine, SDL_AddLine, SDL_SubLine, SDL_AddSurface, SDL_SubSurface, SDL_MonoSurface &mp; SDL_TexturedSurface. He has also\r\ndonated a Font Blitting class and demo called SDL_MonoFonts which supports alignment like Left, Right and Center.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Jason Farmer has donated a set of Image Filtering functions which add quite a few interesting effects. Check the SDL_Filter sub-directory for more\r\ninfo.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">Christian Hackbart also donated an OpenGL BlockOut clone.</font></font></li>\r\n</ul>\r\n\r\n<font size="+1"><font size="-1"><font color="#009900"><b>Monday 10th of July 2001</b></font> - In the <a href="/Jedi:TEAM_SDL_DOWNLOADS:468174">downloads</a> section you should find the latest beta\r\nof the headers ( currently at Beta 3 ). Beta 3 brings the following enhancements...<br>\r\n</font></font>\r\n<ul>\r\n<li><font size="+1"><font size="-1">I have added conversions for SDL_env.h, SDL_Mixer.h and SDL_Net.h while Matthias Thoma has added conversions for SDL_Image.h and SMPEG.h.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">This version is also SDL version 1.2.0 compliant.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">This release also adds demos for the SDL_Image, SDL_Mixer and SDL_Net libraries.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">There are now also some OpenGL demos that make some use of SDL as well as a demo on how to use the Mouse with Clickable regions.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">A conversion bug, that was pointed out by Clem Vasseur, has also been fixed.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">There is now a mailing list that has been set up at <a href="http://groups.yahoo.com/group/JEDI-SDL/join/">http://groups.yahoo.com/group/JEDI-SDL/join/</a> so we\r\ncan all learn from each other how to use these libraries.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">There is also a documentation directory that is currently in HTML format. All code examples in the documentation have been converted to Object Pascal but are\r\nuntested.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">I Also fixed a few conversion bugs which I came across while converting the documentation.</font></font></li>\r\n</ul>\r\n\r\n<font size="+1"><font size="-1">There are now 5 standard examples included with this JEDI-SDL distribution.<br>\r\n</font></font>\r\n<ol>\r\n<li><font size="+1"><font size="-1">Is the TestWin application, which is based on the testwin application that comes with the SDL SDK, only my version has a gui front end to the options available and\r\nhas been compiled under Delphi 4.03. It should be compatible with Delphi 3.0 onwards ( though Delphi 2 compatibility has not been tested ).</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">A Plasma example which was converted from one found on the Demos page of the SDL site.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">A Voxel terrain following demo, which was converted from one found on the Demos page of the SDL site. This one should be of interest to others as it shows how to\r\nhandle keyboard events when using SDL.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">A Mouse handling demo that shows how to use transparency and clickable regions.</font></font></li>\r\n\r\n<li><font size="+1"><font size="-1">A Space Invaders style game called Aliens which shows the use of SDL, SDL_Image and SDL_Mixer. This game shows how to handle sound, keyboards and some basic\r\ncollision detection. It is a conversion of one found on the SDL Demos page.<br>\r\n</font></font></li>\r\n</ol>\r\n\r\n<font size="+1"><font size="-1">There are also 4 OpenGL demos that are based on the <a href="http://nehe.gamedev.net">NeHe tutorials</a>.<br>\r\n<br>\r\n &nbsp; The Latest Stable Release version of the JEDI-SDL.zip file can always be found in the <a href="/Jedi:TEAM_SDL_DOWNLOADS:468174">downloads</a> section of this site.<br>\r\n<br>\r\n The Latest Alpha/Unstable version of the JEDI-SDLu.zip file can always be found in the <a href="http://groups.yahoo.com/group/JEDI-SDL/files/">JEDI-SDL mailing list</a>\'s file area.<br>\r\n<br>\r\nSo crank up Delphi, Kylix or FreePascal and <a href="/Jedi:TEAM_SDL_DOWNLOADS:468174">download</a> these headers. Your help on the testing panel would be welcome! Please contact the <a href= \r\n"mailto:ma.thoma@gmx.de">Testing Coordinator</a> (Matthias Thoma).</font></font>\r\n<p><font size="+1"><font size="-1"><font size="+1">Other SDL Work<br>\r\n <font size="-1"><a href="mailto:nitrogen@nrj.com">Clem Vasseur</a>, one of the members of the <a href="http://groups.yahoo.com/group/JEDI-SDL/join">JEDI-SDL mailing</a>, has just released <a href= \r\n"http://dtt.spinet.org">DeathTruckTion v1.0</a> which is a 3D network game he and a few friends put together for a school project.<br>\r\n</font></font></font></font></p>\r\n\r\n<table cellpadding="3">\r\n<tr>\r\n<td width="320" valign="middle" align="center"><img src="http://deathtrucktion.spinet.org/pics/screenshots/sshot7.jpg" width="320" height="240"></td>\r\n<td><font face="Verdana" size="-1">This is what he had to say.... BTW, my school project is finished... it\'s a "crappy" 3D game using OpenGL, SDL ( and of course <a href= \r\n"http://www.delphi-jedi.org/Jedi:TEAM_SDL_HOME">JEDI-SDL</a> :-), FMod and Indy ( network components for server and client ), which works great under Windows and Linux. The game itself is\r\nuninteresting, but maybe the source is :). I have no idea about the license, because I am using graphics from other games which is not quite legal anyway. Since I didn\'t figure out how to upload the\r\n( source ) file to the yahoogroup thingy, I _temporarily_ uploaded it <a href= \r\n"http://www.savagesoftware.com.au/DelphiGamer/dodownload.php?link=ftp://delphi-jedi.org/api/Cross_Platform/files/JEDI-SDL/Demos/Dttsrc.zip&mp;ID=102">here</a>.</font></td>\r\n</tr>\r\n</table>\r\n\r\n<p><font size="+1"><font size="-1"><font size="+1"><font size="-1"><font face="Verdana" size="-1">Basically you can do whatever you want with the code... but I don\'t think it\'s a good demo for\r\nJEDI-SDL, it may be a little bit too complex for a demo :). Anyway, if you find something interesting in it you are free to use it in your own projects, or release some part like you want. For those\r\nwho are trying to do some 3D with Delphi/OpenGL, the TNT-3D engine might be interesting... but it\'s completely undocumented. If someone wants to write a tutorial about it or something like that I\r\nwill explain the inner working of the engine :)</font></font></font></font></font></p>\r\n\r\n<p><font size="+1"><font size="-1"><font size="+1"><font size="-1"><font face="Verdana" size="-1">Clem does not have any time to continue developing the game and engine so I suggested he create a\r\nSourceForge account so that others who may be interested can develop the game, engine change the textures etc further. The current homepage of the TNTeam and it\'s demo can be found @ <a href= \r\n"http://dtt.spinet.org">http://dtt.spinet.org</a> and you can cop a look at some screen shots @ <a href="http
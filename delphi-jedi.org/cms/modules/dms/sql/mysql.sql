#
# Table structure for `dms_config`
#

CREATE TABLE dms_config (
  name varchar(25) NOT NULL default '',
  data varchar(255) NOT NULL default ''
)  TYPE=MyISAM;

INSERT INTO dms_config 
  VALUES ('doc_path','');
INSERT INTO dms_config
  VALUES ('dms_title','');
INSERT INTO dms_config
  VALUES ('max_file_sys_counter','1000');
INSERT INTO dms_config
  VALUES ('template_root_obj_id','');
  
#
# Table structure for `dms_file_sys_counters`
#

CREATE TABLE dms_file_sys_counters (
  layer_1 bigint(5) unsigned NOT NULL default '0',
  layer_2 bigint(5) unsigned NOT NULL default '0',
  layer_3 bigint(5) unsigned NOT NULL default '0',
  file bigint(5) unsigned not NULL default '0'
)  TYPE=MyISAM;

INSERT INTO dms_file_sys_counters
  VALUES ('1','1','1','1');
  
#
# Table structure for table `dms_objects`
#

CREATE TABLE dms_objects (
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
  lifecycle_suspend_flag tinyint(2) unsigned NOT NULL  default '0',
  PRIMARY KEY (obj_id)
)  TYPE=MyISAM;

#
# Table structure for table `dms_object_perms`
#

CREATE TABLE dms_object_perms (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  ptr_obj_id bigint(14) unsigned NOT NULL default '0',
  user_id bigint(14) unsigned NOT NULL default '0',
  group_id bigint(14) unsigned NOT NULL default '0',
  user_perms tinyint(2) NOT NULL default '0',
  group_perms tinyint(2) NOT NULL default '0',
  everyone_perms tinyint(2) NOT NULL default '0',
  PRIMARY KEY (row_id)
) TYPE=MyISAM;

#
# Table structure for `dms_object_versions`
#

CREATE TABLE dms_object_versions (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  obj_id bigint(14) unsigned NOT NULL default '0',
  major_version smallint(5) unsigned NOT NULL default '0',
  minor_version smallint(5) unsigned NOT NULL default '0',
  sub_minor_version smallint(5) unsigned NOT NULL default '0',
  file_path varchar(255) NOT NULL default '',
  file_name varchar(255) NOT NULL default '',
  file_type varchar(50) NOT NULL default '',
  file_size varchar(10) NOT NULL default '',
  PRIMARY KEY (row_id)
) TYPE=MyISAM;

#
# Table structure for `dms_object_properties`
#

CREATE TABLE dms_object_properties (
  obj_id bigint(14) unsigned NOT NULL,
  obj_descript varchar(255) NOT NULL default '',
  obj_keywords varchar(255) NOT NULL default '',
  obj_authors varchar(255) NOT NULL default '',
  obj_mms_nums varchar(255) NOT NULL default '',
  PRIMARY KEY (obj_id)
) TYPE=MyISAM;

#
# Table structure for `dms_exp_folders`
#

CREATE TABLE dms_exp_folders (
  user_id bigint(14) unsigned NOT NULL default '',
  folder_id bigint(14) unsigned NOT NULL default ''
)  TYPE=MyISAM;

#
# Table structure for `dms_active_folder`
#

CREATE TABLE dms_active_folder (
  user_id bigint(14) unsigned NOT NULL default '',
  folder_id bigint(14) unsigned NOT NULL default ''
)  TYPE=MyISAM;

#
# Table structure for `dms_template_objects`
#

CREATE TABLE dms_template_objects (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  obj_id bigint(14) unsigned NOT NULL default '',
  PRIMARY KEY (row_id)
)  TYPE=MyISAM;

#
# Table structure for table `dms_lifecycles`
#
  
CREATE TABLE dms_lifecycles (  
  lifecycle_id bigint(14) unsigned NOT NULL auto_increment,
  lifecycle_name varchar(255) NOT NULL default '',
  lifecycle_descript varchar(255) NOT NULL default '',
  PRIMARY KEY (lifecycle_id)
)  TYPE=MyISAM;

#
# Table structure for `dms_lifecycle_stages`
#

CREATE TABLE dms_lifecycle_stages (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  lifecycle_id bigint (14) unsigned NOT NULL,
  lifecycle_stage tinyint(2) unsigned NOT NULL default '0' ,
  new_obj_location bigint(14) unsigned NOT NULL default '0',
  PRIMARY KEY (row_id)
)  TYPE=MyISAM;

#
# Table structure for table `dms_lifecycle_apply_perms`
#

CREATE TABLE dms_lifecycle_apply_perms(
  row_id bigint(14) unsigned NOT NULL auto_increment,
  lifecycle_id bigint(14) unsigned NOT NULL default '0',
  user_id bigint(14) unsigned NOT NULL default '0',
  group_id bigint(14) unsigned NOT NULL default '0',
  user_perms tinyint(2) NOT NULL default '0',
  group_perms tinyint(2) NOT NULL default '0',
  everyone_perms tinyint(2) NOT NULL default '0',
  PRIMARY KEY (row_id)
) TYPE=MyISAM;

#
# Table structure for table `dms_lifecycle_doc_perms`
#

CREATE TABLE dms_lifecycle_doc_perms (
  row_id bigint(14) unsigned NOT NULL auto_increment,
  lifecycle_id bigint(14) unsigned NOT NULL default '0',
  lifecycle_stage tinyint(2) unsigned NOT NULL default '0',
  user_id bigint(14) unsigned NOT NULL default '0',
  group_id bigint(14) unsigned NOT NULL default '0',
  user_perms tinyint(2) NOT NULL default '0',
  group_perms tinyint(2) NOT NULL default '0',
  everyone_perms tinyint(2) NOT NULL default '0',
  PRIMARY KEY (row_id)
) TYPE=MyISAM;




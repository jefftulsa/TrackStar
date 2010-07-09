-- Disable foreign keys
SET FOREIGN_KEY_CHECKS = 0 ;

-- Create tables section -------------------------------------------------

-- Table tbl_project

CREATE TABLE IF NOT EXISTS `tbl_project` (
  `id` INTEGER NOT NULL auto_increment,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `create_time` DATETIME default NULL,
  `create_user_id` INTEGER default NULL,
  `update_time` DATETIME default NULL,
  `update_user_id` INTEGER default NULL,
  PRIMARY KEY  (`id`)
) ENGINE = InnoDB
;

-- DROP TABLE IF EXISTS `tbl_issue` ;

CREATE TABLE IF NOT EXISTS `tbl_issue` 
( 
  `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` varchar(2000), 
  `project_id` INTEGER,
  `type_id` INTEGER,
  `status_id` INTEGER,
  `owner_id` INTEGER,
  `requester_id` INTEGER,
  `create_time` DATETIME,
  `create_user_id` INTEGER,
  `update_time` DATETIME,
  `update_user_id` INTEGER  
) ENGINE = InnoDB
; 

-- DROP TABLE IF EXISTS `tbl_user` ;

-- Table User

CREATE TABLE IF NOT EXISTS `tbl_user` 
(
  `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `email` Varchar(256) NOT NULL,
  `username` Varchar(256),
  `password` Varchar(256),
  `last_login_time` Datetime,
  `create_time` DATETIME,
  `create_user_id` INTEGER,
  `update_time` DATETIME,
  `update_user_id` INTEGER
) ENGINE = InnoDB
; 

-- DROP TABLE IF EXISTS `tbl_project_user_assignment` ;

CREATE TABLE IF NOT EXISTS `tbl_project_user_assignment`
(
  `project_id` Int(11) NOT NULL,
  `user_id` Int(11) NOT NULL,
  `create_time` DATETIME,
  `create_user_id` INTEGER,
  `update_time` DATETIME,
  `update_user_id` INTEGER,
 PRIMARY KEY (`project_id`,`user_id`)
) ENGINE = InnoDB
;

CREATE TABLE IF NOT EXISTS `tbl_comment`
(
  `id` Int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `content` TEXT NOT NULL,
  `issue_id` Int(11),
  `create_time` DATETIME,
  `create_user_id` INTEGER,
  `update_time` DATETIME,
  `update_user_id` INTEGER
) ENGINE = InnoDB
;


CREATE TABLE IF NOT EXISTS `AuthItem`
(
  `name` VARCHAR(64) NOT NULL,
  `type` Int(11) NOT NULL,
  `description` TEXT,
  `bizrule` TEXT,
  `data` TEXT,
  `update_user_id` INTEGER,
  PRIMARY KEY (`name`)
) ENGINE = InnoDB
;

CREATE TABLE IF NOT EXISTS `AuthItemChild`
(
  `parent` VARCHAR(64) NOT NULL,
  `child` VARCHAR(64) NOT NULL,
   PRIMARY KEY (`parent`, `child`)
) ENGINE = InnoDB
;

CREATE TABLE IF NOT EXISTS `AuthAssignment`
(
  `itemname` VARCHAR(64) NOT NULL,
  `userid` VARCHAR(64) NOT NULL,
  `bizrule` TEXT,
  `data` TEXT,
  PRIMARY KEY (`itemname`, `userid`)
) ENGINE = InnoDB
;

CREATE TABLE IF NOT EXISTS `tbl_project_user_role`
(
   `project_id` INTEGER NOT NULL,
   `user_id` INTEGER NOT NULL,  
   `role` VARCHAR(64) NOT NULL, 
   PRIMARY KEY (`project_id`,`user_id`,`role`)          
) ENGINE = InnoDB
;

CREATE TABLE IF NOT EXISTS `tbl_sys_message`
(
   `id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   `message` TEXT NOT NULL,  
   `create_time` DATETIME,
   `create_user_id` INTEGER,
   `update_time` DATETIME,
   `update_user_id` INTEGER 
) ENGINE = InnoDB
;

-- The Relationships 
ALTER TABLE `tbl_issue` ADD CONSTRAINT `FK_issue_project` FOREIGN KEY (`project_id`) REFERENCES `tbl_project` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `tbl_issue` ADD CONSTRAINT `FK_issue_owner` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT; 

ALTER TABLE `tbl_issue` ADD CONSTRAINT `FK_issue_requester` FOREIGN KEY (`requester_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT; 

ALTER TABLE `tbl_project_user_assignment` ADD CONSTRAINT `FK_project_user` FOREIGN KEY (`project_id`) REFERENCES `tbl_project` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `tbl_project_user_assignment` ADD CONSTRAINT `FK_user_project` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;   

ALTER TABLE `AuthItemChild` ADD CONSTRAINT `FK_parent_authitem` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `AuthItemChild` ADD CONSTRAINT `FK_child_authitem` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `AuthAssignment` ADD CONSTRAINT `FK_itemname_authitem` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
 
ALTER TABLE `tbl_project_user_role` ADD CONSTRAINT `FK_project_id` FOREIGN KEY (`project_id`) REFERENCES `tbl_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
   
ALTER TABLE `tbl_project_user_role` ADD CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `tbl_project_user_role` ADD CONSTRAINT `FK_role_name` FOREIGN KEY (`role`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `tbl_comment` ADD CONSTRAINT `FK_comment_issue` FOREIGN KEY (`issue_id`) REFERENCES `tbl_issue` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `tbl_comment` ADD CONSTRAINT `FK_comment_author` FOREIGN KEY (`create_user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Insert some seed data so we can just begin using the database
-- INSERT INTO `tbl_user` 
--  (`email`, `username`, `password`) 
-- VALUES 
--  ('test1@notanaddress.com','Test_User_One', MD5('test1')),
--  ('test2@notanaddress.com','Test_User_Two', MD5('test2'))    
-- ;

-- Enable foreign keys
SET FOREIGN_KEY_CHECKS = 1 ;
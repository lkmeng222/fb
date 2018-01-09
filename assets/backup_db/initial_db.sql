-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 03, 2016 at 03:06 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";



--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id` varchar(128) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
    `data` blob NOT NULL,
    KEY `ci_sessions_timestamp` (`timestamp`)
);


--
-- Table structure for table `email_config`
--

CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `email_config`
--




--
-- Table structure for table `forget_password`
--

CREATE TABLE IF NOT EXISTS `forget_password` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `confirmation_code` varchar(15) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `forget_password`
--



--
-- Table structure for table `native_api`
--

CREATE TABLE IF NOT EXISTS `native_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `native_api`
--

--
-- Table structure for table `payment_config`
--

CREATE TABLE IF NOT EXISTS `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(250) NOT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `payment_config` (`id`, `paypal_email`, `currency`, `deleted`) VALUES
(1, 'Paypalemail@example.com', 'USD', '0');
--
-- Dumping data for table `payment_config`
--


--
-- Table structure for table `transaction_history`
--

CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_status` varchar(200) NOT NULL,
  `first_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `paypal_email` varchar(200) NOT NULL,
  `receiver_email` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `payment_date` varchar(250) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `transaction_id` varchar(150) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cycle_start_date` date NOT NULL,
  `cycle_expired_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `transaction_history`
--
ALTER TABLE `transaction_history` ADD `package_id` INT NOT NULL;
ALTER TABLE `transaction_history` ADD `stripe_card_source` TEXT NOT NULL; 

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `password` varchar(99) NOT NULL,
  `address` text NOT NULL,
  `user_type` enum('Member','Admin') NOT NULL,
  `status` enum('1','0') NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activation_code` varchar(20) DEFAULT NULL,
  `expired_date` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `address`, `user_type`, `status`, `add_date`, `activation_code`, `expired_date`, `deleted`) VALUES
(1, 'Xerone IT', 'admin@gmail.com', '+88 01729 853 6452', '259534db5d66c3effb7aa2dbbee67ab0', 'Rajshahi', 'Admin', '1', '2016-01-01 00:00:00', NULL, '0000-00-00 00:00:00', '0');

-- --------------------------------------------------------




  
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84;

INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (28, 'FB Chat Plugin', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (65, 'Account Import', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (69, 'CTA Poster', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (76, 'Campaign', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (77, 'Messge Send Button', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (78, 'Daily Auto Lead Scan', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (79, 'Send Message', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (80, 'Auto Private Reply', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (81, 'Messenger Ad JSON Script', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (82, 'Page Inbox Manager', '0');
INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES (83, 'Page Notification Manager', '0');

-- module insert will goes here by mostofa

CREATE TABLE `package` (
`id` INT NOT NULL AUTO_INCREMENT ,
`package_name` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`module_ids` VARCHAR( 250 ) NOT NULL ,
`price` FLOAT NOT NULL ,
`deleted` INT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB ;

ALTER TABLE `package` CHANGE `deleted` `deleted` ENUM( '0', '1' ) NOT NULL;
ALTER TABLE `package` ADD `validity` INT NOT NULL AFTER `price`;
ALTER TABLE `package` ADD `is_default` ENUM( '0', '1' ) NOT NULL AFTER `validity`;
ALTER TABLE `package` CHANGE `is_default` `is_default` ENUM( '0', '1' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `package` CHANGE `price` `price` VARCHAR( 20 ) NOT NULL DEFAULT '0';
INSERT INTO `package` (`id`, `package_name`, `module_ids`, `price`, `validity`, `is_default`, `deleted`) VALUES (1, 'Trial', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20', 'Trial', '7', '1', '0');

ALTER TABLE `users` ADD `package_id` INT NOT NULL AFTER `expired_date`;

ALTER TABLE `package` ENGINE = InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;



ALTER TABLE `package` ADD `monthly_limit` TEXT NULL AFTER `module_ids` ,
ADD `bulk_limit` TEXT NULL AFTER `monthly_limit`;
UPDATE `package` SET `package_name` = 'Trial',
`module_ids` = '65,69,76,77,78,79,80,81,82,83,12,84,28,13,1,2',
`monthly_limit` = '{"65":0,"69":0,"76":0,"77":0,"78":0,"79":"0","80":0,"81":0,"82":0,"83":0,"12":0,"84":0,"28":0,"13":0,"1":0,"2":0}',
`bulk_limit` = '{"65":0,"69":0,"76":0,"77":0,"78":0,"79":"0","80":0,"81":0,"82":0,"83":0,"12":0,"84":0,"28":0,"13":0,"1":0,"2":0}',
`price` = 'Trial' WHERE `package`.`id` =1;


CREATE TABLE `usage_log` (
`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`module_id` INT NOT NULL ,
`user_id` INT NOT NULL ,
`usage_month` INT NOT NULL ,
`usage_year` YEAR NOT NULL ,
`usage_count` INT NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;



CREATE TABLE `login_config` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`api_id` VARCHAR( 250 ) NULL ,
`api_secret` VARCHAR( 250 ) NULL ,
`google_client_id` VARCHAR( 250 ) NULL ,
`google_client_secret` VARCHAR( 250 ) NULL ,
`status` ENUM( '0', '1' ) NOT NULL DEFAULT '1',
`deleted` ENUM( '0', '1' ) NOT NULL DEFAULT '0'
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `login_config` ADD `app_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `id` ;
ALTER TABLE `login_config` CHANGE `google_client_id` `google_client_id` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `payment_config` CHANGE `currency` `currency` ENUM( 'USD', 'AUD', 'CAD', 'EUR', 'ILS', 'NZD', 'RUB', 'SGD', 'SEK', 'BRL' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;



ALTER TABLE `payment_config` ADD `stripe_secret_key` VARCHAR( 150 ) NOT NULL AFTER `paypal_email`;

ALTER TABLE `payment_config` ADD `stripe_publishable_key` VARCHAR( 150 ) NOT NULL AFTER `stripe_secret_key`;





CREATE TABLE `facebook_config` (
  `id` int(11) NOT NULL,
  `app_name` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `api_id` varchar(250) DEFAULT NULL,
  `api_secret` varchar(250) DEFAULT NULL,
  `user_access_token` varchar(500) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



ALTER TABLE `facebook_config`
  ADD PRIMARY KEY (`id`);



ALTER TABLE `facebook_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;



ALTER TABLE `login_config` ADD `user_access_token` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `api_secret`;

ALTER TABLE `users` ADD `brand_logo` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `deleted`, ADD `brand_url` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `brand_logo`;




CREATE
 ALGORITHM = UNDEFINED
 VIEW `view_usage_log`
 (id,module_id,user_id,usage_month,usage_year,usage_count)
 AS select * from usage_log where `usage_month`=MONTH(curdate()) and `usage_year`= YEAR(curdate()) ;



CREATE TABLE IF NOT EXISTS `facebook_page_insight_page_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page_id` varchar(250) DEFAULT NULL,
  `page_name` text,
  `page_email` varchar(250) DEFAULT NULL,
  `page_cover` longtext,
  `page_profile` longtext,
  `page_access_token` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `url_id` int(11) NOT NULL,
  `search_engine_url_id` int(11) NOT NULL,
  `found_email` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



ALTER TABLE `payment_config` CHANGE `currency` `currency` ENUM('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK','BRL','VND') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;


ALTER TABLE `users` CHANGE `address` `address` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `users` ADD `vat_no` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `brand_url`;
ALTER TABLE `users` ADD `currency` ENUM('USD', 'AUD', 'CAD', 'EUR', 'ILS', 'NZD', 'RUB', 'SGD', 'SEK', 'BRL') NOT NULL DEFAULT 'USD' AFTER `vat_no`;
ALTER TABLE `users` ADD `company_email`  VARCHAR(200) NULL AFTER `currency`;
ALTER TABLE `users` ADD `paypal_email` VARCHAR(100) NOT NULL AFTER `company_email`;
ALTER TABLE `users` ADD `purchase_date` DATETIME NOT NULL AFTER `add_date`;
ALTER TABLE `users` ADD `last_login_at` DATETIME NOT NULL AFTER `purchase_date`;


CREATE TABLE IF NOT EXISTS `facebook_rx_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) DEFAULT NULL,
  `api_id` varchar(250) DEFAULT NULL,
  `api_secret` varchar(250) DEFAULT NULL,
  `numeric_id` text NOT NULL,
  `user_access_token` varchar(500) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;





CREATE TABLE IF NOT EXISTS `facebook_rx_conversion_user_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page_id` varchar(100) NOT NULL,
  `client_username` varchar(200) NOT NULL,
  `client_thread_id` varchar(200) NOT NULL,
  `client_id` varchar(100) NOT NULL,
  `permission` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `facebook_rx_cta_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `facebook_rx_fb_user_info_id` int(11) NOT NULL,
  `campaign_name` varchar(200) NOT NULL,
  `page_group_user_id` varchar(200) NOT NULL COMMENT 'auto_like_post_comment',
  `page_or_group_or_user` enum('page') NOT NULL COMMENT 'cta post is only available for page',
  `cta_type` varchar(100) NOT NULL,
  `cta_value` text NOT NULL,
  `message` text NOT NULL,
  `link` text NOT NULL,
  `link_preview_image` text NOT NULL,
  `link_caption` varchar(200) NOT NULL,
  `link_description` text NOT NULL,
  `auto_share_post` enum('0','1') NOT NULL DEFAULT '0',
  `auto_share_this_post_by_pages` text NOT NULL,
  `auto_share_to_profile` enum('0','1') NOT NULL DEFAULT '0',
  `auto_like_post` enum('0','1') NOT NULL DEFAULT '0',
  `auto_private_reply` enum('0','1') NOT NULL DEFAULT '0',
  `auto_private_reply_text` text NOT NULL,
  `auto_private_reply_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'taken by cronjob or not',
  `auto_private_reply_count` int(11) NOT NULL,
  `auto_private_reply_done_ids` text NOT NULL,
  `auto_comment` enum('0','1') NOT NULL DEFAULT '0',
  `auto_comment_text` varchar(200) NOT NULL,
  `posting_status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT 'pending,processing,completed',
  `post_id` varchar(200) NOT NULL COMMENT 'fb post id',
  `post_url` text NOT NULL,
  `last_updated_at` datetime NOT NULL,
  `schedule_time` datetime NOT NULL,
  `time_zone` varchar(100) NOT NULL,
  `post_auto_comment_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto comment is done by cron job',
  `post_auto_like_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto like is done by cron job',
  `post_auto_share_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto share is done by cron job',
  `error_mesage` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`facebook_rx_fb_user_info_id`),
  KEY `posting_status` (`posting_status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `facebook_rx_fb_group_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `facebook_rx_fb_user_info_id` int(11) NOT NULL,
  `group_id` varchar(200) NOT NULL,
  `group_cover` text,
  `group_profile` text,
  `group_name` varchar(200) DEFAULT NULL,
  `group_access_token` text NOT NULL,
  `add_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `facebook_rx_fb_page_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `facebook_rx_fb_user_info_id` int(11) NOT NULL,
  `page_id` varchar(200) NOT NULL,
  `page_cover` text,
  `page_profile` text,
  `page_name` varchar(200) DEFAULT NULL,
  `page_access_token` text NOT NULL,
  `page_email` varchar(200) DEFAULT NULL,
  `add_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `facebook_rx_fb_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facebook_rx_config_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `access_token` text NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `fb_id` varchar(200) NOT NULL,
  `add_date` date NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



ALTER TABLE `facebook_rx_config` CHANGE `numeric_id` `numeric_id` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `facebook_rx_fb_page_info` ADD `deleted` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `add_date`;
ALTER TABLE `facebook_rx_fb_group_info` ADD `deleted` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `add_date`;


ALTER TABLE `facebook_rx_config` ADD `user_id` INT NOT NULL AFTER `deleted`;
ALTER TABLE `facebook_rx_config` ADD `use_by` ENUM('only_me','everyone') NOT NULL DEFAULT 'only_me' AFTER `user_id`;



-- added by mostofa 26/02/2017

CREATE TABLE IF NOT EXISTS `ad_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section1_html` longtext,
  `section1_html_mobile` longtext,
  `section2_html` longtext,
  `section3_html` longtext,
  `section4_html` longtext,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `facebook_ex_autoreply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facebook_rx_fb_user_info_id` int(11) NOT NULL,
  `auto_reply_campaign_name` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `page_info_table_id` int(11) NOT NULL,
  `page_name` text,
  `post_id` varchar(200) NOT NULL,
  `post_created_at` varchar(255) DEFAULT NULL,
  `post_description` longtext,
  `reply_type` varchar(200) NOT NULL,
  `nofilter_word_found_text` longtext NOT NULL,
  `auto_reply_text` longtext NOT NULL,
  `auto_private_reply_status` enum('0','1') NOT NULL DEFAULT '0',
  `auto_private_reply_count` int(11) NOT NULL,
  `auto_private_reply_done_ids` text,
  `auto_reply_done_info` text,
  `last_updated_at` datetime NOT NULL,
  `last_reply_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`page_info_table_id`,`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE `facebook_ex_autoreply` CHANGE `auto_reply_done_info` `auto_reply_done_info` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `facebook_ex_autoreply` CHANGE `auto_private_reply_done_ids` `auto_private_reply_done_ids` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;



CREATE TABLE IF NOT EXISTS `facebook_ex_conversation_campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `page_ids` text NOT NULL COMMENT 'comma separated page auto ids',
  `fb_page_ids` text NOT NULL COMMENT 'comma separated fb page ids',
  `page_ids_names` text NOT NULL COMMENT 'associative array',
  `do_not_send_to` text NOT NULL,
  `campaign_name` varchar(200) NOT NULL,
  `campaign_type` enum('page-wise','lead-wise') NOT NULL DEFAULT 'page-wise',
  `campaign_message` text NOT NULL,
  `attached_url` text NOT NULL,
  `attached_video` text NOT NULL,
  `schedule_time` datetime NOT NULL,
  `time_zone` text NOT NULL,
  `posting_status` enum('0','1','2') NOT NULL,
  `is_spam_caught` enum('0','1') NOT NULL DEFAULT '0',
  `error_message` varchar(200) NOT NULL,
  `total_thread` int(11) NOT NULL,
  `successfully_sent` int(11) NOT NULL,
  `added_at` datetime NOT NULL,
  `completed_at` datetime NOT NULL,
  `report` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`posting_status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE `facebook_ex_conversation_campaign` ADD `unsubscribe_button` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `report`, ADD `delay_time` INT NOT NULL DEFAULT '0' COMMENT '0 means random' AFTER `unsubscribe_button`;



ALTER TABLE `facebook_rx_fb_page_info` ADD `auto_sync_lead` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `deleted`, ADD `last_lead_sync` DATETIME NOT NULL AFTER `auto_sync_lead`;
ALTER TABLE `facebook_rx_fb_page_info` ADD `current_lead_count` INT NOT NULL AFTER `last_lead_sync`;
ALTER TABLE `facebook_rx_fb_page_info` ADD `current_subscribed_lead_count` INT NOT NULL AFTER `current_lead_count`, ADD `current_unsubscribed_lead_count` INT NOT NULL AFTER `current_subscribed_lead_count`;
ALTER TABLE `facebook_rx_fb_page_info` ADD `username` VARCHAR(255) NOT NULL AFTER `page_name`;
ALTER TABLE `facebook_rx_fb_page_info` ADD INDEX( `page_id`);
ALTER TABLE `facebook_rx_fb_page_info` ADD INDEX( `user_id`, `page_id`);
ALTER TABLE `facebook_rx_fb_page_info` ADD `msg_manager` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `current_unsubscribed_lead_count`;

ALTER TABLE `facebook_rx_conversion_user_list` ADD `subscribed_at` DATETIME NOT NULL AFTER `permission`, ADD `unsubscribed_at` DATETIME NOT NULL AFTER `subscribed_at`;
ALTER TABLE `facebook_rx_conversion_user_list` ADD UNIQUE( `user_id`, `page_id`, `client_id`);



CREATE TABLE `fb_chat_plugin` (
  `id` int(11) NOT NULL,
  `user_id` varchar(200) DEFAULT NULL,
  `domain_name` varchar(250) DEFAULT NULL,
  `fb_page_url` text,
  `add_date` date NOT NULL,
  `deleted` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `fb_chat_plugin`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `fb_chat_plugin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `fb_chat_plugin` ADD `js_code` TEXT NOT NULL AFTER `fb_page_url`;
ALTER TABLE `fb_chat_plugin` ADD `domain_code` VARCHAR(200) NULL AFTER `js_code`;
ALTER TABLE `fb_chat_plugin` ADD `message_header` VARCHAR( 255 ) NOT NULL AFTER `domain_name` ;




CREATE TABLE `fb_msg_manager_notification_settings` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `facebook_rx_config_id` INT NOT NULL , `email_address` VARCHAR(255) NOT NULL , `time_interval` VARCHAR(100) NOT NULL , `status` ENUM('yes','no') NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `fb_msg_manager_notification_settings` ADD `last_email_time` DATETIME NOT NULL AFTER `status`;
ALTER TABLE `fb_msg_manager_notification_settings` CHANGE `email_address` `email_address` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `time_interval` `time_interval` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `fb_msg_manager_notification_settings` CHANGE `status` `is_enabled` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `fb_msg_manager_notification_settings` CHANGE `facebook_rx_config_id` `facebook_rx_fb_user_info_id` INT(11) NOT NULL;
ALTER TABLE `fb_msg_manager_notification_settings` ADD `has_business_account` ENUM('yes','no') NOT NULL AFTER `is_enabled`;
ALTER TABLE `fb_msg_manager_notification_settings` CHANGE `has_business_account` `has_business_account` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no';
ALTER TABLE `fb_msg_manager_notification_settings` ADD `time_zone` VARCHAR(255) NOT NULL AFTER `email_address`;


CREATE TABLE `fb_msg_manager` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `fb_rx_config_id` INT NOT NULL , `from_user` VARCHAR(255) NULL , `from_user_id` VARCHAR(255) NULL , `last_snippet` LONGTEXT NOT NULL , `message_count` VARCHAR(255) NULL , `thread_id` VARCHAR(100) NOT NULL , `inbox_link` TEXT NOT NULL , `unread_count` VARCHAR(255) NULL , `sync_time` DATETIME NOT NULL , `last_update_time` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `fb_msg_manager` CHANGE `fb_rx_config_id` `facebook_rx_fb_user_info_id` INT(11) NOT NULL;
ALTER TABLE `fb_msg_manager` ADD UNIQUE (`thread_id`);
ALTER TABLE `fb_msg_manager` DROP INDEX `thread_id`, ADD UNIQUE `thread_id` (`thread_id`, `user_id`, `facebook_rx_fb_user_info_id`) USING BTREE;
ALTER TABLE `fb_msg_manager` ADD `page_table_id` INT(12) NOT NULL AFTER `facebook_rx_fb_user_info_id`;
ALTER TABLE `fb_msg_manager` CHANGE `last_update_time` `last_update_time` DATETIME NOT NULL COMMENT 'this time in +00 UTC format, We need to convert it to the user time zone';


ALTER TABLE `facebook_ex_autoreply` ADD `comment_reply_enabled` ENUM('no','yes') NOT NULL AFTER `reply_type`;
ALTER TABLE `facebook_ex_autoreply` ADD `multiple_reply` ENUM('no','yes') NOT NULL AFTER `reply_type`;
ALTER TABLE `facebook_ex_autoreply` ADD `auto_like_comment` ENUM('no','yes') NOT NULL AFTER `reply_type`;

ALTER TABLE `facebook_rx_conversion_user_list` ADD `contact_group_id` VARCHAR(255) NOT NULL AFTER `page_id`;
ALTER TABLE `facebook_rx_conversion_user_list` DROP INDEX `user_id`, ADD INDEX `user_id` (`contact_group_id`) USING BTREE;

ALTER TABLE `facebook_ex_conversation_campaign` CHANGE `campaign_type` `campaign_type` ENUM('page-wise','lead-wise','group-wise') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'page-wise';
ALTER TABLE `facebook_ex_conversation_campaign` ADD `group_ids` TEXT NOT NULL COMMENT 'comma seperated group ids if group wise' AFTER `user_id`;

CREATE TABLE IF NOT EXISTS `facebook_rx_conversion_contact_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `payment_config` CHANGE `currency` `currency` ENUM('USD','AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','MXN','TWD','NZD','NOK','PHP','PLN','GBP','RUB','SGD','SEK','CHF','VND') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `facebook_ex_autoreply` CHANGE `auto_private_reply_status` `auto_private_reply_status` ENUM('0','1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0';
ALTER TABLE `facebook_ex_autoreply` ADD `error_message` TEXT NOT NULL AFTER `last_reply_time`;


CREATE TABLE IF NOT EXISTS `facebook_ex_conversation_campaign_send` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `client_thread_id` varchar(255) NOT NULL,
  `client_username` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `message_sent_id` varchar(255) NOT NULL,
  `sent_time` datetime NOT NULL,
  `lead_id` int(11) NOT NULL,
  `processed` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `facebook_ex_conversation_campaign_send` ADD `page_name` VARCHAR(255) NOT NULL AFTER `page_id`;
ALTER TABLE `facebook_ex_conversation_campaign` ADD `is_try_again` ENUM('0','1') NOT NULL DEFAULT '1' AFTER `posting_status`;
ALTER TABLE `facebook_ex_conversation_campaign` ADD `last_try_error_count` INT NOT NULL AFTER `is_try_again`;




ALTER TABLE `facebook_ex_autoreply` ADD `is_delete_offensive` ENUM('hide', 'delete') NOT NULL AFTER `error_message`, ADD `offensive_words` LONGTEXT NOT NULL AFTER `is_delete_offensive`, ADD `private_message_offensive_words` LONGTEXT NOT NULL AFTER `offensive_words`;
ALTER TABLE `facebook_ex_autoreply` ADD `hide_comment_after_comment_reply` ENUM('no', 'yes') NOT NULL AFTER `error_message`;
ALTER TABLE `facebook_ex_autoreply` ADD `hidden_comment_count` INT(11) NOT NULL AFTER `private_message_offensive_words`, ADD `deleted_comment_count` INT(11) NOT NULL AFTER `hidden_comment_count`;
ALTER TABLE `facebook_ex_autoreply` ADD `auto_comment_reply_count` INT(11) NOT NULL AFTER `deleted_comment_count`;

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `add_ons_id` int(11) NOT NULL,
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;


INSERT INTO `menu` (`id`, `name`, `icon`, `url`, `serial`, `module_access`, `have_child`, `only_admin`, `only_member`, `add_ons_id`, `is_external`) VALUES
(1, 'dashboard', 'fa fa-dashboard', 'facebook_ex_dashboard/index', 1, '', '0', '0', '0', 0, '0'),
(2, 'App Settings', 'fa fa-cog', 'facebook_rx_config/index', 2, '65', '0', '0', '1', 0, '0'),
(3, 'Payment', 'fa fa-paypal', 'payment/member_payment_history', 3, '', '0', '0', '1', 0, '0'),
(4, 'usage log', 'fa fa-list-ol', 'payment/usage_history', 4, '', '0', '0', '1', 0, '0'),
(5, 'Administration', 'fa fa-user-plus', '#', 5, '', '1', '1', '0', 0, '0'),
(6, 'import account', 'fa fa-cloud-download', 'facebook_rx_account_import/index', 6, '65', '0', '0', '0', 0, '0'),
(7, 'facebook lead', 'fa fa-group', '#', 7, '76', '1', '0', '0', 0, '0'),
(10, 'bulk message campaign', 'fa fa-envelope', '#', 8, '76', '1', '0', '0', 0, '0'),
(14, 'lead generator', 'fa fa-flash', '#', 9, '77,80,81,69,28', '1', '0', '0', 0, '0'),
(15, 'page inbox & notification', 'fa fa-commenting', '#', 10, '82,83', '1', '0', '0', 0, '0'),
(16, 'Cron job', 'fa fa-clock-o', 'native_api/index', 12, '', '0', '1', '0', 0, '0'),
(18, 'add-ons', 'fa fa-plug', 'addons/lists', 11, '', '0', '1', '0', 0, '0'),
(19, 'check update', 'fa fa-angle-double-up', 'update_system/index', 13, '', '0', '1', '0', 0, '0');

DROP TABLE IF EXISTS `menu_child_1`;
CREATE TABLE IF NOT EXISTS `menu_child_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;


INSERT INTO `menu_child_1` (`id`, `name`, `url`, `serial`, `icon`, `module_access`, `parent_id`, `have_child`, `only_admin`, `only_member`) VALUES
(1, 'User Management', 'admin/user_management', 1, 'fa fa-user', '', 5, '0', '1', '0'),
(2, 'Send Notification', 'admin/notify_members', 2, 'fa fa-bell-o', '', 5, '0', '1', '0'),
(3, 'Settings', '#', 3, 'fa fa-cog', '', 5, '1', '1', '0'),
(4, 'Payment', '#', 4, 'fa fa-paypal', '', 5, '1', '1', '0'),
(5, 'enable auto reply', 'facebook_ex_autoreply/index', 1, 'fa fa-reply-all', '80', 14, '0', '1', '0'),
(6, 'auto reply report', 'facebook_ex_autoreply/all_auto_reply_report', 2, 'fa fa-list-ol', '80', 14, '0', '1', '0'),
(7, '''send message'' button', 'facebook_ex_message_button/index', 3, 'fa fa-comment', '77', 14, '0', '1', '0'),
(8, 'messanger ad json script', 'facebook_ex_json_messanger/index', 4, 'fa fa-code', '81', 14, '0', '1', '0'),
(9, 'call to action poster', 'facebook_rx_cta_poster/cta_post_list/1', 5, 'fa fa-hand-o-up', '69', 14, '0', '1', '0'),
(10, 'facebook chat plugin', 'fb_chat_plugin_custom/index', 6, 'fa fa-wechat', '28', 14, '0', '1', '0'),
(11, 'settings', 'fb_msg_manager/index', 1, 'fa fa-cog', '', 15, '0', '0', '0'),
(12, 'message dashboard', 'fb_msg_manager/message_dashboard', 2, 'fa fa-dashboard', '82', 15, '0', '1', '0'),
(13, 'notification dashboard', 'fb_msg_manager_notification/index', 3, 'fa fa-dashboard', '83', 15, '0', '1', '0'),
(14, 'import lead', 'facebook_ex_import_lead/index', 1, 'fa fa-download', '76', 7, '0', '0', '0'),
(15, 'lead group', 'facebook_ex_import_lead/contact_group', 2, 'fa  fa-columns', '76', 7, '0', '0', '0'),
(16, 'lead list', 'facebook_ex_import_lead/contact_list', 3, 'fa fa-user', '76', 7, '0', '0', '0'),
(17, 'Multi-page Campaign', 'facebook_ex_campaign/create_multipage_campaign', 1, 'fa fa-clone', '76', 10, '0', '0', '0'),
(18, 'Multi-group Campaign', 'facebook_ex_campaign/create_multigroup_campaign', 2, 'fa fa-object-ungroup', '76', 10, '0', '0', '0'),
(19, 'Custom Campaign', 'facebook_ex_campaign/custom_campaign', 3, 'fa fa-random', '76', 10, '0', '0', '0'),
(20, 'Campaign Report', 'facebook_ex_campaign/campaign_report', 4, 'fa fa-th-list', '76', 10, '0', '0', '0');
ALTER TABLE `menu_child_1` ADD `is_external` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `only_member`;

DROP TABLE IF EXISTS `menu_child_2`;
CREATE TABLE IF NOT EXISTS `menu_child_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_child` int(11) NOT NULL,
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO `menu_child_2` (`id`, `name`, `url`, `serial`, `icon`, `module_access`, `parent_child`, `only_admin`, `only_member`) VALUES
(1, 'Email Settings', 'admin_config_email/index', 2, 'fa fa-envelope', '', 3, '1', '0'),
(2, 'General Settings', 'admin_config/configuration', 1, 'fa fa-cog', '', 3, '1', '0'),
(3, 'Analytics Settings', 'admin_config/analytics_config', 3, 'fa fa-pie-chart', '', 3, '1', '0'),
(4, 'Purchase Code Settings', 'admin_config/purchase_code_configuration', 4, 'fa fa-code', '', 3, '1', '0'),
(5, 'advertisement settings', 'admin_config_ad/ad_config', 5, 'fa fa-bullhorn', '', 3, '1', '0'),
(6, 'social login settings', 'admin_config_login/login_config', 6, 'fa fa-sign-in', '', 3, '1', '0'),
(7, 'Facebook API Settings', 'facebook_rx_config/index', 7, 'fa fa-facebook-official', '', 3, '1', '0'),
(8, 'Dashboard', 'payment/payment_dashboard_admin', 1, 'fa fa-dashboard', '', 4, '1', '0'),
(9, 'Package Settings', 'payment/package_settings', 2, 'fa fa-cube', '', 4, '1', '0'),
(10, 'Payment Settings', 'payment/payment_setting_admin', 3, 'fa fa-cog', '', 4, '1', '0'),
(11, 'Payment History', 'payment/admin_payment_history', 4, 'fa fa-history', '', 4, '1', '0');
ALTER TABLE `menu_child_2` ADD `is_external` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `only_member`;

CREATE TABLE IF NOT EXISTS `add_ons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `add_on_name` varchar(255) NOT NULL,
  `unique_name` varchar(255) NOT NULL,
  `installed_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`unique_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `add_ons` ADD `version` FLOAT NOT NULL AFTER `unique_name`;
ALTER TABLE `add_ons` ADD `purchase_code` VARCHAR(100) NOT NULL AFTER `installed_at`;
ALTER TABLE `add_ons` ADD `module_folder_name` VARCHAR(255) NOT NULL AFTER `purchase_code`;
ALTER TABLE `add_ons` ADD `project_id` INT NOT NULL AFTER `module_folder_name`;
ALTER TABLE `add_ons` ADD `update_at` DATETIME NOT NULL AFTER `installed_at`;
ALTER TABLE `add_ons` CHANGE `version` `version` VARCHAR(255) NOT NULL;

ALTER TABLE `modules` ADD `add_ons_id` INT NOT NULL AFTER `module_name`;
INSERT INTO `modules` (`id`, `module_name`, `add_ons_id`, `deleted`) VALUES (74, 'CTA Poster Auto Like/Share/Comment', '0', '0');


CREATE TABLE IF NOT EXISTS `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `current` enum('1','0') NOT NULL DEFAULT '1',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `version` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `facebook_rx_fb_user_info` ADD `need_to_delete` ENUM('0','1') NOT NULL AFTER `deleted`;
ALTER TABLE `facebook_ex_conversation_campaign` CHANGE `posting_status` `posting_status` ENUM('0','1','2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
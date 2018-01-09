<?php

class Update extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();     
        set_time_limit(0);
        $this->load->helpers(array('my_helper'));
        $this->load->database();
        $this->load->model('basic');
        $query = 'SET SESSION group_concat_max_len=9990000000000000000';
        $this->db->query($query);
        $q= "SET SESSION wait_timeout=50000";
        $this->db->query($q);
        $query="SET SESSION sql_mode = ''";
        $this->db->query($query);
        if(function_exists('ini_set')){
          ini_set('memory_limit', '-1');
        }
    }


    public function index()
    {
        $this->v4_0_1to4_1();
    }

    public function v4_0_1to4_1()
    {
      // writing application/config/my_config
        $app_my_config_data = "<?php ";
        $app_my_config_data.= "\n\$config['default_page_url'] = '".$this->config->item('default_page_url')."';\n";
        $app_my_config_data.= "\$config['product_version'] = '4.1';\n";
        $app_my_config_data.= "\$config['institute_address1'] = '".$this->config->item('institute_address1')."';\n";
        $app_my_config_data.= "\$config['institute_address2'] = '".$this->config->item('institute_address2')."';\n";
        $app_my_config_data.= "\$config['institute_email'] = '".$this->config->item('institute_email')."';\n";
        $app_my_config_data.= "\$config['institute_mobile'] = '".$this->config->item('institute_mobile')."';\n\n";
        $app_my_config_data.= "\$config['slogan'] = '".$this->config->item('slogan')."';\n";
        $app_my_config_data.= "\$config['product_name'] = '".$this->config->item('product_name')."';\n";
        $app_my_config_data.= "\$config['product_short_name'] = '".$this->config->item('product_short_name')."';\n";
        $app_my_config_data.= "\$config['developed_by'] = '".$this->config->item('developed_by')."';\n";
        $app_my_config_data.= "\$config['developed_by_href'] = '".$this->config->item('developed_by_href')."';\n";
        $app_my_config_data.= "\$config['developed_by_title'] = '".$this->config->item('developed_by_title')."';\n";
        $app_my_config_data.= "\$config['developed_by_prefix'] = '".$this->config->item('developed_by_prefix')."' ;\n";
        $app_my_config_data.= "\$config['support_email'] = '".$this->config->item('support_email')."' ;\n";
        $app_my_config_data.= "\$config['support_mobile'] = '".$this->config->item('support_mobile')."' ;\n";                
        $app_my_config_data.= "\$config['time_zone'] = '".$this->config->item('time_zone')."';\n";              
        $app_my_config_data.= "\$config['language'] = '".$this->config->item('language')."';\n";
        $app_my_config_data.= "\$config['sess_use_database'] = '".$this->config->item('sess_use_database')."';\n";
        $app_my_config_data.= "\$config['sess_table_name'] = '".$this->config->item('sess_table_name')."';\n";  

        if($this->config->item('number_of_message_to_be_sent_in_try') != '')
          $app_my_config_data.= "\$config['number_of_message_to_be_sent_in_try'] = ".$this->config->item('number_of_message_to_be_sent_in_try').";\n";   
        if($this->config->item('update_report_after_time') != '')  
          $app_my_config_data.= "\$config['update_report_after_time'] = ".$this->config->item('update_report_after_time').";\n";  

        if($this->config->item('theme') != '')    
          $app_my_config_data.= "\$config['theme'] = '".$this->config->item('theme')."';\n";   

        if($this->config->item('display_landing_page') != '')  
          $app_my_config_data.= "\$config['display_landing_page'] = '".$this->config->item('display_landing_page')."';\n"; 

        $app_my_config_data.= "\$config['auto_reply_delay_time'] = 10;\n";     
        $app_my_config_data.= "\$config['auto_reply_campaign_live_duration'] = 50;\n";     
        file_put_contents(APPPATH.'config/my_config.php', $app_my_config_data, LOCK_EX);  //writting  application/config/my_config


        $lines="UPDATE `version` SET `current`='0';
        INSERT INTO version(version, current, date) VALUES ('4.1','1',CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE version='4.1',current='1',date=CURRENT_TIMESTAMP;
        ALTER TABLE `facebook_ex_conversation_campaign` CHANGE `posting_status` `posting_status` ENUM('0','1','2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
        $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }
       echo $count." queries executed. ";


        echo $this->config->item('product_short_name')." has been updated successfully to v4.1";
    }

    public function v_4_0to4_0_1()
    {
      // writting client js
      $client_js_content=file_get_contents('js/my_chat_custom.js');
      $client_js_content_new=str_replace("base_url_replace/", site_url(), $client_js_content);
      file_put_contents('js/my_chat_custom.js', $client_js_content_new, LOCK_EX);

       $lines="UPDATE `version` SET `current`='0';
        INSERT INTO version(version, current, date) VALUES ('4.0.1','1',CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE version='4.0.1',current='1',date=CURRENT_TIMESTAMP";
       $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }
       echo $count." queries executed. ";

      // writting client js

      echo $this->config->item('product_short_name')." has been updated successfully to version 4.0.1";
    }

    public function v_3_2tov4_0()
    {
        $lines="ALTER TABLE `facebook_ex_autoreply` ADD `is_delete_offensive` ENUM('hide', 'delete') NOT NULL AFTER `error_message`, ADD `offensive_words` LONGTEXT NOT NULL AFTER `is_delete_offensive`, ADD `private_message_offensive_words` LONGTEXT NOT NULL AFTER `offensive_words`;
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
        INSERT INTO `version` (`id`, `version`, `current`, `date`) VALUES
        (1, 4, '1', '2017-10-20 00:00:00');
        ALTER TABLE `facebook_rx_fb_user_info` ADD `need_to_delete` ENUM('0','1') NOT NULL AFTER `deleted`";

        $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }

        // writing application/config/my_config
        // $app_my_config_data = "<?php ";
        // $app_my_config_data.= "\n\$config['default_page_url'] = '".$this->config->item('default_page_url')."';\n";
        // $app_my_config_data.= "\$config['product_version'] = '".$this->config->item('product_version')."';\n\n";
        // $app_my_config_data.= "\$config['institute_address1'] = '".$this->config->item('institute_address1')."';\n";
        // $app_my_config_data.= "\$config['institute_address2'] = '".$this->config->item('institute_address2')."';\n";
        // $app_my_config_data.= "\$config['institute_email'] = '".$this->config->item('institute_email')."';\n";
        // $app_my_config_data.= "\$config['institute_mobile'] = '".$this->config->item('institute_mobile')."';\n\n";
        // $app_my_config_data.= "\$config['slogan'] = '".$this->config->item('slogan')."';\n";
        // $app_my_config_data.= "\$config['product_name'] = '".$this->config->item('product_name')."';\n";
        // $app_my_config_data.= "\$config['product_short_name'] = '".$this->config->item('product_short_name')."';\n";
        // $app_my_config_data.= "\$config['developed_by'] = '".$this->config->item('developed_by')."';\n";
        // $app_my_config_data.= "\$config['developed_by_href'] = '".$this->config->item('developed_by_href')."';\n";
        // $app_my_config_data.= "\$config['developed_by_title'] = '".$this->config->item('developed_by_title')."';\n";
        // $app_my_config_data.= "\$config['developed_by_prefix'] = '".$this->config->item('developed_by_prefix')."' ;\n";
        // $app_my_config_data.= "\$config['support_email'] = '".$this->config->item('support_email')."' ;\n";
        // $app_my_config_data.= "\$config['support_mobile'] = '".$this->config->item('support_mobile')."' ;\n";                
        // $app_my_config_data.= "\$config['time_zone'] = '".$this->config->item('time_zone')."';\n";              
        // $app_my_config_data.= "\$config['language'] = '".$this->config->item('language')."';\n";
        // $app_my_config_data.= "\$config['sess_use_database'] = FALSE;\n";
        // $app_my_config_data.= "\$config['sess_table_name'] = 'ci_sessions';\n";     
        // file_put_contents(APPPATH.'config/my_config.php', $app_my_config_data, LOCK_EX);  //writting  application/config/my_config


        echo $this->config->item('product_short_name')." has been updated successfully to v4.0 , ".$count." queries executed.";

        
    }

    public function v_3_0to3_1()
    {
        $lines="CREATE TABLE IF NOT EXISTS `facebook_ex_conversation_campaign_send` (
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
        ALTER TABLE `facebook_ex_conversation_campaign` ADD `last_try_error_count` INT NOT NULL AFTER `is_try_again`";
       
        // Loop through each line

        $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }
        echo $this->config->item('product_short_name')." has been updated successfully to v3.1 , ".$count." queries executed.";
    }


    public function v_2_2to2_3()
    {
        $lines="ALTER TABLE `payment_config` CHANGE `currency` `currency` ENUM('USD','AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','MXN','TWD','NZD','NOK','PHP','PLN','GBP','RUB','SGD','SEK','CHF','VND') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
        ALTER TABLE `facebook_ex_autoreply` CHANGE `auto_private_reply_status` `auto_private_reply_status` ENUM('0','1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0';
        ALTER TABLE `facebook_ex_autoreply` ADD `error_message` TEXT NOT NULL AFTER `last_reply_time`;
        UPDATE `facebook_ex_autoreply` SET `auto_private_reply_status`='0'";
       
        // Loop through each line

        $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }
        echo $this->config->item('product_short_name')." has been updated successfully.".$count." queries executed.";
    }



    function v_1_1to2_0()
    {
        // writting client js
        $client_js_content=file_get_contents('js/my_chat_custom.js');
        $client_js_content_new=str_replace("base_url_replace/", site_url(), $client_js_content);
        file_put_contents('js/my_chat_custom.js', $client_js_content_new, LOCK_EX);
        // writting client js
                
        $lines="ALTER TABLE `facebook_ex_autoreply` ADD `comment_reply_enabled` ENUM('no','yes') NOT NULL AFTER `reply_type`;
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
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8";


       
        // Loop through each line

        $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }
        echo $this->config->item('product_short_name')." has been updated to v2.0 successfully.".$count." queries executed.";
    }


    public function auto_reply_fix()
    {
        $lines="UPDATE `facebook_ex_autoreply` SET `auto_private_reply_status`='0'";
       
        // Loop through each line

        $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }
        echo $this->config->item('product_short_name')." has been updated successfully.".$count." queries executed.";
    }




    function delete_update()
    {
        unlink(APPPATH."controllers/update.php");
    }
 


}

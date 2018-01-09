<?php

require_once("Home.php"); // including home controller

/**
* class admin_config
* @category controller
*/
class Admin_config extends Home
{
    /**
    * load constructor method
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged_in')!= 1) {
            redirect('home/login_page', 'location');
        }

        if ($this->session->userdata('user_type')!= 'Admin') {
            redirect('home/login_page', 'location');
        }

        $this->important_feature();
        $this->periodic_check();
    }

    /**
    * load index method. redirect to config
    * @access public
    * @return void
    */
    public function index()
    {
        $this->configuration();
    }

    /**
    * load config form method
    * @access public
    * @return void
    */
    public function configuration()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
           redirect('home/login_page', 'location');
        }
        
        $data['body'] = "admin/config/edit_config";
        $data['time_zone'] = $this->_time_zone_list();        
        $data['language_info'] = $this->_language_list();
        $data['page_title'] = $this->lang->line('general settings');
        $this->_viewcontroller($data);
    }

    /**
    * method to edit config
    * @access public
    * @return void
    */
    public function edit_config()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        if ($_POST) 
        {
            // validation
            $this->form_validation->set_rules('institute_name',       '<b>'.$this->lang->line("company name").'</b>',             'trim');
            $this->form_validation->set_rules('institute_address',    '<b>'.$this->lang->line("company address").'</b>',          'trim');
            $this->form_validation->set_rules('institute_email',      '<b>'.$this->lang->line("company email").'</b>',            'trim|required');
            $this->form_validation->set_rules('institute_mobile',     '<b>'.$this->lang->line("company phone/ mobile").'</b>',    'trim');
            $this->form_validation->set_rules('time_zone',            '<b>'.$this->lang->line("time zone").'</b>',                'trim');
            $this->form_validation->set_rules('slogan',               '<b>'.$this->lang->line("slogan").'</b>',                 'trim');

            $this->form_validation->set_rules('language',             '<b>'.$this->lang->line("language").'</b>',                 'trim');

            $this->form_validation->set_rules('product_name',         '<b>'.$this->lang->line("product name").'</b>',                 'trim');
            $this->form_validation->set_rules('product_short_name',   '<b>'.$this->lang->line("product short name").'</b>',                 'trim');
            $this->form_validation->set_rules('backup_mode',   '<b>'.$this->lang->line("Backup Mode").'</b>',                 'trim');
            $this->form_validation->set_rules('number_of_message_to_be_sent_in_try',   '<b>'.$this->lang->line("number of message send per cron job").'</b>',                 'trim');
            $this->form_validation->set_rules('update_report_after_time',   '<b>'.$this->lang->line("message sending report update frequency").'</b>',                 'trim');
            $this->form_validation->set_rules('theme',   '<b>'.$this->lang->line("theme").'</b>',                 'trim');
            $this->form_validation->set_rules('display_landing_page',   '<b>'.$this->lang->line("display landing page").'</b>',                 'trim');

            $this->form_validation->set_rules('auto_reply_delay_time',   '<b>'.$this->lang->line("delay used in auto-reply (seconds)").'</b>',                 'trim');
            $this->form_validation->set_rules('auto_reply_campaign_live_duration',   '<b>'.$this->lang->line("auto-reply campaign live duration (days)").'</b>',                 'trim');
            // go to config form page if validation wrong
            if ($this->form_validation->run() == false) {
                return $this->configuration();
            } else {
                // assign
                $institute_name=addslashes(strip_tags($this->input->post('institute_name', true)));
                $institute_address=addslashes(strip_tags($this->input->post('institute_address', true)));
                $institute_email=addslashes(strip_tags($this->input->post('institute_email', true)));
                $institute_mobile=addslashes(strip_tags($this->input->post('institute_mobile', true)));
                $time_zone=addslashes(strip_tags($this->input->post('time_zone', true)));
                $language=addslashes(strip_tags($this->input->post('language', true)));
                $slogan=addslashes(strip_tags($this->input->post('slogan', true)));
                $product_name=addslashes(strip_tags($this->input->post('product_name', true)));
                $product_short_name=addslashes(strip_tags($this->input->post('product_short_name', true)));
                $backup_mode=addslashes(strip_tags($this->input->post('backup_mode', true)));
                $number_of_message_to_be_sent_in_try=addslashes(strip_tags($this->input->post('number_of_message_to_be_sent_in_try', true)));
                $update_report_after_time=addslashes(strip_tags($this->input->post('update_report_after_time', true)));
                $theme=addslashes(strip_tags($this->input->post('theme', true)));
                $display_landing_page=addslashes(strip_tags($this->input->post('display_landing_page', true)));

                $auto_reply_delay_time=addslashes(strip_tags($this->input->post('auto_reply_delay_time', true)));
                $auto_reply_campaign_live_duration=addslashes(strip_tags($this->input->post('auto_reply_campaign_live_duration', true)));

                $base_path=realpath(APPPATH . '../assets/images');

                $this->load->library('upload');

                if ($_FILES['logo']['size'] != 0) {
                    $photo = "logo.png";
                    $config = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo,
                        'max_size' => '200',
                        'max_width' => '600',
                        'max_height' => '300'
                        );
                    $this->upload->initialize($config);
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('logo')) {
                        $this->session->set_userdata('logo_error', $this->upload->display_errors());
                        return $this->configuration();
                    }
                }

                if ($_FILES['favicon']['size'] != 0) {
                    $photo = "favicon.png";
                    $config2 = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo,
                        'max_size' => '50',
                        'max_width' => '32',
                        'max_height' => '32'
                        );
                    $this->upload->initialize($config2);
                    $this->load->library('upload', $config2);

                    if (!$this->upload->do_upload('favicon')) {
                        $this->session->set_userdata('favicon_error', $this->upload->display_errors());
                        return $this->configuration();
                    }
                }

                // writing application/config/my_config
                $app_my_config_data = "<?php ";
                $app_my_config_data.= "\n\$config['default_page_url'] = '".$this->config->item('default_page_url')."';\n";
                $app_my_config_data.= "\$config['product_version'] = '".$this->config->item('product_version')."';\n\n";
                $app_my_config_data.= "\$config['institute_address1'] = '$institute_name';\n";
                $app_my_config_data.= "\$config['institute_address2'] = '$institute_address';\n";
                $app_my_config_data.= "\$config['institute_email'] = '$institute_email';\n";
                $app_my_config_data.= "\$config['institute_mobile'] = '$institute_mobile';\n\n";
                $app_my_config_data.= "\$config['slogan'] = '$slogan';\n";
                $app_my_config_data.= "\$config['product_name'] = '$product_name';\n";
                $app_my_config_data.= "\$config['product_short_name'] = '$product_short_name';\n\n";
                $app_my_config_data.= "\$config['developed_by'] = '".$this->config->item('developed_by')."';\n";
                $app_my_config_data.= "\$config['developed_by_href'] = '".$this->config->item('developed_by_href')."';\n";
                $app_my_config_data.= "\$config['developed_by_title'] = '".$this->config->item('developed_by_title')."';\n";
                $app_my_config_data.= "\$config['developed_by_prefix'] = '".$this->config->item('developed_by_prefix')."' ;\n";
                $app_my_config_data.= "\$config['support_email'] = '".$this->config->item('support_email')."' ;\n";
                $app_my_config_data.= "\$config['support_mobile'] = '".$this->config->item('support_mobile')."' ;\n";                
                $app_my_config_data.= "\$config['time_zone'] = '$time_zone';\n";                
                $app_my_config_data.= "\$config['language'] = '$language';\n";
                $app_my_config_data.= "\$config['sess_use_database'] = FALSE;\n";
                $app_my_config_data.= "\$config['sess_table_name'] = 'ci_sessions';\n";
                if($number_of_message_to_be_sent_in_try!="")
                $app_my_config_data.= "\$config['number_of_message_to_be_sent_in_try'] = $number_of_message_to_be_sent_in_try;\n";
                if($update_report_after_time!="")
                $app_my_config_data.= "\$config['update_report_after_time'] = $update_report_after_time;\n";
                $app_my_config_data.= "\$config['theme'] = '".$theme."';\n";
                $app_my_config_data.= "\$config['display_landing_page'] = '".$display_landing_page."';\n";
                
                $app_my_config_data.= "\$config['auto_reply_delay_time'] = '".$auto_reply_delay_time."';\n";
                $app_my_config_data.= "\$config['auto_reply_campaign_live_duration'] = '".$auto_reply_campaign_live_duration."';\n";

                file_put_contents(APPPATH.'config/my_config.php', $app_my_config_data, LOCK_EX);                  //writting  application/config/my_config
                
                if($backup_mode == 'yes') $mode_to_write = 1;
                else $mode_to_write = 0;   
                // mostofa
                $admin_info = $this->basic->get_data("users",array("where"=>array('user_type'=>'Admin')),array('id'));
                $admin_ids = array();
                foreach($admin_info as $value)
                {
                    array_push($admin_ids, $value['id']);
                }

                $admin_app_info = $this->basic->get_data("facebook_rx_config",array("where_in"=>array("user_id"=>$admin_ids)),array("id"));
                $admin_app_ids = array();
                foreach($admin_app_info as $apps)
                {
                    array_push($admin_app_ids, $apps['id']);
                }

                if($mode_to_write == 1)
                {
                    if(!empty($admin_app_ids))
                    {
                        $this->db->where_in('facebook_rx_config_id', $admin_app_ids);
                        $this->db->where_not_in('user_id', $admin_ids);
                        $this->db->update("facebook_rx_fb_user_info",array("need_to_delete"=>"1"));
                    }
                }
                else
                {
                    if(!empty($admin_app_ids))
                    {
                        $this->db->where_in('facebook_rx_config_id', $admin_app_ids);
                        $this->db->where_not_in('user_id', $admin_ids);
                        $this->db->update("facebook_rx_fb_user_info",array("need_to_delete"=>"0"));
                    }
                }
                // mostofa

                // Messenger Bot
                if($this->basic->is_exist("modules",$where=array('id'=>200)))
                {                    
                    $admin_app_info = $this->basic->get_data("messenger_bot_config",array("where_in"=>array("user_id"=>$admin_ids)),array("id"));
                    $admin_app_ids = array();
                    foreach($admin_app_info as $apps)
                    {
                        array_push($admin_app_ids, $apps['id']);
                    }

                    if($mode_to_write == 1)
                    {
                        if(!empty($admin_app_ids))
                        {
                            $this->db->where_in('messenger_bot_config_id', $admin_app_ids);
                            $this->db->where_not_in('user_id', $admin_ids);
                            $this->db->update("messenger_bot_user_info",array("need_to_delete"=>"1"));
                        }
                    }
                    else
                    {
                        if(!empty($admin_app_ids))
                        {
                            $this->db->where_in('messenger_bot_config_id', $admin_app_ids);
                            $this->db->where_not_in('user_id', $admin_ids);
                            $this->db->update("messenger_bot_user_info",array("need_to_delete"=>"0"));
                        }
                    }
                }
                // Messenger Bot

                $app_package_config_data = "<?php ";
                $app_package_config_data.= "\n\$config['backup_mode'] = '$mode_to_write';\n";
                file_put_contents(APPPATH.'config/package_config.php', $app_package_config_data, LOCK_EX); 
                $this->session->unset_userdata("selected_language");

              
                $this->session->set_flashdata('success_message', 1);
                redirect('admin_config/configuration', 'location');
            }
        }
    }

    public function analytics_config()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
           redirect('home/login_page', 'location');
        }
        
        $data['body'] = "admin/config/analytics_config";
        $data['page_title'] = $this->lang->line('analytics settings');
        $this->_viewcontroller($data);
    }


    public function analytics_config_action()
    {
        $this->load->helper('file');
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata("user_type")!="Admin"){
            redirect('home/access_forbidden', 'location');
        } 
        
        $pixel_code = $this->input->post('pixel_code');
        $google_code = $this->input->post('google_code');

        file_put_contents(APPPATH.'views/include/fb_px.php', $pixel_code, LOCK_EX);
        file_put_contents(APPPATH.'views/include/google_code.php', $google_code, LOCK_EX);

        $this->session->set_flashdata('success_message', 1);
        redirect('admin_config/analytics_config','location');
    }


    public function purchase_code_configuration()
    {
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin'){
            redirect('home/login_page', 'location');
        }
        $data['body'] = "admin/config/edit_purchase_code_config";
        $data['time_zone'] = $this->_time_zone_list();
        $data['page_title'] = $this->lang->line('purchase code settings');
        $this->_viewcontroller($data);
    }

    public function edit_purchase_code_config()
    {   
        $this->load->helper('file');
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata("user_type")!="Admin"){
            redirect('home/access_forbidden', 'location');
        } 
        $file_data = file_get_contents(APPPATH . 'core/licence.txt');
        $file_data_array = json_decode($file_data, true);

        $purchase_code = $file_data_array['purchase_code'];
        $only_domain = $file_data_array['domain'];

        $url = "http://xeroneit.net/development/envato_license_activation/delete_purchase_code.php?purchase_code={$purchase_code}&domain={$only_domain}&item_name=FBInboxer";
        $credentials = $this->get_general_content_with_checking($url);
        $response = json_decode($credentials,true);

        if(isset($response['error']))
        {
            $url = "http://getbddoctor.com/secure/envato_credential_check/delete_purchase_code.php?purchase_code={$purchase_code}&domain={$only_domain}&item_name=FBInboxer";
            $credentials = $this->get_general_content_with_checking($url);
            $response = json_decode($credentials,true);
        }

        $delete_option = json_decode($response['content'],true);
        if(isset($delete_option['status']) && $delete_option['status'] == 1 )
        {
            $path_core = APPPATH . 'core/licence.txt';
            $path_config = APPPATH . 'config/licence.txt';
            if(file_exists($path_core)){
                unlink($path_core);
            }
            if(file_exists($path_config)){
                unlink($path_config);
            }
            $this->session->set_flashdata('my_success_message', 1);
            redirect('home/credential_check','location');
        }
        else
        {
            $this->session->set_flashdata('delete_error_message', 1);
            redirect('admin_config/purchase_code_configuration','location');
        }  
    }
    
}

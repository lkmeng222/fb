<?php
require_once("Home.php");

class Native_api extends Home
{
    public $user_id;
    
    public function __construct()
    {
        parent::__construct();
        $this->upload_path = realpath( APPPATH . '../upload');
        
    }


    public function api_member_validity($user_id='')
    {
        if($user_id!='') {
            $where['where'] = array('id'=>$user_id);
            $user_expire_date = $this->basic->get_data('users',$where,$select=array('expired_date'));
            $expire_date = strtotime($user_expire_date[0]['expired_date']);
            $current_date = strtotime(date("Y-m-d"));
            $package_data=$this->basic->get_data("users",$where=array("where"=>array("users.id"=>$user_id)),$select="package.price as price, users.user_type",$join=array('package'=>"users.package_id=package.id,left"));

            if(is_array($package_data) && array_key_exists(0, $package_data) && $package_data[0]['user_type'] == 'Admin' )
                return true;

            $price = '';
            if(is_array($package_data) && array_key_exists(0, $package_data))
            $price=$package_data[0]["price"];
            if($price=="Trial") $price=1;

            
            if ($expire_date < $current_date && ($price>0 && $price!=""))
            return false;
            else return true;
            

        }
    }


    public function index()
    {
       $this->get_api();
    }

    public function _api_key_generator()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access))
        redirect('home/login_page', 'location');

        $this->member_validity();
        $val=$this->session->userdata("user_id")."-".substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 7 ).time()
        .substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789') , 0 , 7 );
        return $val;
    }

    public function get_api()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access))
        redirect('home/login_page', 'location');

        $this->member_validity();

        $data['body'] = "api/native_api";
        $data['page_title'] = 'API';
        $api_data=$this->basic->get_data("native_api",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $data["api_key"]="";
        if(count($api_data)>0) $data["api_key"]=$api_data[0]["api_key"];
        $this->_viewcontroller($data);
    }

    public function get_api_action()
    { 
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login', 'location');

        if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access))
        redirect('home/login_page', 'location');

        $api_key=$this->_api_key_generator(); 
        if($this->basic->is_exist("native_api",array("api_key"=>$api_key)))
        $this->get_api_action();

        $user_id=$this->session->userdata("user_id");        
        if($this->basic->is_exist("native_api",array("user_id"=>$user_id)))
        $this->basic->update_data("native_api",array("user_id"=>$user_id),array("api_key"=>$api_key));
        else $this->basic->insert_data("native_api",array("api_key"=>$api_key,"user_id"=>$user_id));
            
        redirect('native_api/get_api', 'location');
    }



    public function api_key_check($api_key="")
    {
        $user_id="";
        if($api_key!="")
        {
            $explde_api_key=explode('-',$api_key);
            $user_id="";
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];
        }

        if($api_key=="")
        {        
            echo "API Key is required.";    
            exit();
        }

        if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
           echo "API Key does not match with any user.";
           exit();
        }

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any authentic user.";
            exit();
        }              
       

    }


    public function get_fb_rx_config($fb_user_id=0)
    {
        if($fb_user_id==0) return 0;

        $getdata= $this->basic->get_data("facebook_rx_fb_user_info",array("where"=>array("id"=>$fb_user_id)),array("facebook_rx_config_id"));
        $return_val = isset($getdata[0]["facebook_rx_config_id"]) ? $getdata[0]["facebook_rx_config_id"] : 0;

        return $return_val; 
       
    }

    
    public function send_notification($api_key="")
    {
        $this->api_key_check($api_key);    

        $current_date = date("Y-m-d");
        $tenth_day_before_expire = date("Y-m-d", strtotime("$current_date + 10 days"));
        $one_day_before_expire = date("Y-m-d", strtotime("$current_date + 1 days"));
        $one_day_after_expire = date("Y-m-d", strtotime("$current_date - 1 days"));

        // echo $tenth_day_before_expire."<br/>".$one_day_before_expire."<br/>".$one_day_after_expire;

        //send notification to members before 10 days of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $tenth_day_before_expire
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');
        $subject = "Payment Notification";
        foreach ($info as $value) 
        {
            $message = "Dear {$value['first_name']} {$value['last_name']},<br/> your account will expire after 10 days, Please pay your fees.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
            $to = $value['email'];
            $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
        }

        //send notificatio to members before 1 day of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $one_day_before_expire
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');
        $subject = "Payment Notification";
        foreach ($info as $value) {
            $message = "Dear {$value['first_name']} {$value['last_name']},<br/> your account will expire tomorrow, Please pay your fees.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
            $to = $value['email'];
            $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
        }

        //send notificatio to members after 1 day of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $one_day_after_expire
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');
        $subject = "Payment Notification";
        foreach ($info as $value) {
            $message = "Dear {$value['name']},<br/> your account has been expired, Please pay your fees for continuity.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
            $to = $value['email'];
            $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
        }

    }


    /******We are replying 50 post's comment by each call. and updating the status as processing. So we can run the cron job in a small time interval,  We can run it by 15 minutes. *******/


    public function send_auto_private_reply_on_comment_on_fbexciter($api_key="")
    {

        $this->api_key_check($api_key);

        /***    Get post info where we need to check for auto reply ***/

        /**     post which posted last 100 days ago   **/
        $auto_reply_campaign_live_duration=$this->config->item('auto_reply_campaign_live_duration');
        $last_date = date("Y-m-d H:i:s",strtotime("-{$auto_reply_campaign_live_duration} days"));


        $where['where']=array('auto_private_reply_status'=>"0","auto_private_reply_count <="=>"100000","last_updated_at >="=>$last_date);

        $select="facebook_ex_autoreply.id as column_id,post_id,auto_private_reply_done_ids,page_id,page_access_token,auto_reply_text,facebook_ex_autoreply.facebook_rx_fb_user_info_id,multiple_reply,comment_reply_enabled,reply_type,auto_like_comment,auto_reply_done_info,nofilter_word_found_text,hide_comment_after_comment_reply,is_delete_offensive,offensive_words,private_message_offensive_words,hidden_comment_count,deleted_comment_count,auto_comment_reply_count,users.deleted as user_deleted,users.status as user_status";

        $join=array(
            'facebook_rx_fb_page_info'=>"facebook_rx_fb_page_info.id=facebook_ex_autoreply.page_info_table_id,left",
            'users' => 'facebook_ex_autoreply.user_id=users.id,left'
            );

        $post_info= $this->basic->get_data("facebook_ex_autoreply",$where,$select,$join,$limit='50', $start='0', $order_by='last_reply_time ASC');

        // echo $this->db->last_query();
        // echo "<pre>";
        // print_r($post_info);
        // exit();

        /****** Get all id of this post for updating status as processing ******/
        $updating_post_column=array();
        foreach($post_info as $p_info){
            $updating_post_column[]= $p_info['column_id'];
        }

        /** Updating these post auto_private_reply_status=1 means processing **/
        if(!empty($updating_post_column)){
            $this->db->where_in("id",$updating_post_column);
            $this->db->update("facebook_ex_autoreply",array("auto_private_reply_status"=>"1"));
        }


        /***    Start Sending Private reply ****/
        $config_id_database=array();
        // setting fb confid id for library call
        $this->load->library("fb_rx_login");

        foreach($post_info as $info){
            if($info['user_deleted'] == '1' || $info['user_status']=="0") continue;

            /***    get all comment from post **/
            $auto_like_comment = $info['auto_like_comment'];
            $post_id=   $info['post_id'];
            $page_id = $info['page_id'];
            $post_access_token = $info['page_access_token'];

            // comment hide and delete section
            $private_message_offensive_words = $info['private_message_offensive_words'];
            $hidden_comment_count = 0;
            $deleted_comment_count = 0;
            $auto_comment_reply_count = 0;
            $hidden_comment_count = $info['hidden_comment_count'];
            $deleted_comment_count = $info['deleted_comment_count'];
            $auto_comment_reply_count = $info['auto_comment_reply_count'];
            $hide_comment_after_comment_reply = $info['hide_comment_after_comment_reply'];
            $is_delete_offensive = $info['is_delete_offensive'];
            $offensive_words = $info['offensive_words'];
            // comment hide and delete section

            $previous_replied_list= json_decode($info['auto_private_reply_done_ids']);
            $previous_replied_info= json_decode($info['auto_reply_done_info'],true);

            // added by mostofa on 27-04-2017 to prevent duplicate reply
            $previous_replied_names = array();
            foreach($previous_replied_info as $replied_info)
            {
                if(isset($replied_info['commenter_id']))
                    array_push($previous_replied_names, $replied_info['commenter_id']);
            }

            $auto_reply_private_message_raw= $info['auto_reply_text'];
            $auto_reply_type= $info['reply_type'];

            $default_reply_no_filter = json_decode($info['nofilter_word_found_text'],true);
            if(is_array($default_reply_no_filter))
            {
                $default_reply_no_filter_comment = $default_reply_no_filter[0]['comment_reply'];
                $default_reply_no_filter_private = $default_reply_no_filter[0]['private_reply'];
                $default_reply_no_filter_comment_image_link = $default_reply_no_filter[0]['image_link'];
                $default_reply_no_filter_comment_video_link = $default_reply_no_filter[0]['video_link'];
            }
            else
            {
                $default_reply_no_filter_comment = "";
                $default_reply_no_filter_private = $info['nofilter_word_found_text'];
            }


            $comment_reply_enabled = $info['comment_reply_enabled'];
            $multiple_reply = $info['multiple_reply'];


            // setting fb confid id for library call
            $fb_rx_fb_user_info_id= $info['facebook_rx_fb_user_info_id'];
            if(!isset($config_id_database[$fb_rx_fb_user_info_id]))
            {
                $config_id_database[$fb_rx_fb_user_info_id] = $this->get_fb_rx_config($fb_rx_fb_user_info_id);
            }
            
            $skip_error_message = '';
            $post_column_id= $info['column_id'];
            $comment_list=array();
            $new_replied_list =array();
            $new_replied_info=array();
            $new_replied_list= $previous_replied_list;
            $new_replied_info=$previous_replied_info;
            if($config_id_database[$fb_rx_fb_user_info_id] == 0)
            {
                $skip_error_message = "Corresponding Facebook account has been removed from database";
                goto skipped;
            }


            // setting fb confid id for library call
            $this->fb_rx_login->app_initialize($config_id_database[$fb_rx_fb_user_info_id]);


            try{
                $comment_list = $this->fb_rx_login->get_all_comment_of_post($post_id,$post_access_token);                
            }
            catch(Exception $e){
                //$skip_error_message = $e->getMessage();;
                goto skipped;
            }

            // if(!isset($comment_list[$post_id]['comments']))
            // {
            //     if(isset($comment_list['error']))
            //         $skip_error_message = $comment_list['error']['message'];
            //     goto skipped; 
            // } 


            foreach($comment_list as $comment_info){
                $comment_id        = $comment_info['id'];   

                $comment_text      = $comment_info['message'];
                if(function_exists('iconv') && function_exists('mb_detect_encoding')){

                    $encoded_comment =  mb_detect_encoding($comment_text);
                    if(isset($encoded_comment)){
                        $comment_text = iconv( $encoded_comment, "UTF-8//TRANSLIT", $comment_text );
                    }
                }

                $commenter_name    = $comment_info['from']['name'];
                $commenter_id  = $comment_info['from']['id'];
                $commenter_name_array    = explode(' ', $commenter_name);
                $commenter_last_name = array_pop($commenter_name_array);
                $commenter_first_name = implode(' ', $commenter_name_array);

                $comment_time = $comment_info['created_time']['date'];

                $auto_reply_private_message="";
                // added by mostofa on 26-04-2017
                $auto_reply_comment_message="";


                // do not reply if the commenter is page itself
                if($page_id==$commenter_id) continue;

                // comment hide and delete section
                $is_delete=0;
                $offensive_words_array = explode(',', $offensive_words);
                foreach ($offensive_words_array as $key => $value)
                {
                    if(function_exists('iconv') && function_exists('mb_detect_encoding'))
                    {
                        $encoded_offensive_word =  mb_detect_encoding($value);
                        if(isset($encoded_offensive_word)){
                           $value = iconv( $encoded_offensive_word, "UTF-8//TRANSLIT", $value );
                        }
                    }
                    $pos = stripos($comment_text,trim($value));


                    if($pos!==FALSE)
                    {
                        if($is_delete_offensive == 'delete')
                        {
                            try{
                                $comment_result_info=array(
                                    "id" => $comment_id,
                                    "comment_text" =>$comment_text,
                                    "name"      =>$commenter_name,
                                    "commenter_id"      =>$commenter_id,
                                    "comment_time" =>$comment_time,
                                    "reply_time"   =>date("Y-m-d H:i:s")
                                );

                                if($private_message_offensive_words != '')
                                {

                                    $auto_reply_private_message = str_replace('#LEAD_USER_NAME#',$commenter_name,$private_message_offensive_words);
                                    $auto_reply_private_message = str_replace("#LEAD_USER_FIRST_NAME#",$commenter_first_name,$auto_reply_private_message);
                                    $auto_reply_private_message = str_replace("#LEAD_USER_LAST_NAME#",$commenter_last_name,$auto_reply_private_message);

                                    try{
                                        $send_reply_info=$this->fb_rx_login->send_private_reply($auto_reply_private_message,$comment_id,$post_access_token);
                                        $comment_result_info['reply_status']= "success";
                                        $comment_result_info['reply_text']= $auto_reply_private_message;
                                        $comment_result_info['reply_id']=isset($send_reply_info['id'])?$send_reply_info['id']:"";
                                        // increase auto reply count
                                        $new_replied_list[]=$comment_id;
                                    }catch(Exception $e)
                                    {
                                        $comment_result_info['reply_status']= $e->getMessage();
                                        $comment_result_info['reply_text']= $auto_reply_private_message;
                                        $comment_result_info['reply_id']="";
                                    }
                                    
                                }
                                else
                                {
                                    $comment_result_info['reply_text'] = '';
                                    $comment_result_info['reply_status']= '';
                                    $comment_result_info['reply_id']="";
                                }

                                $this->fb_rx_login->delete_comment($comment_id,$post_access_token);
                        
                                $comment_result_info['reply_status_comment'] = "comment deleted";
                                $comment_result_info['comment_reply_text'] = '';         
                                
                                $new_replied_info[]=$comment_result_info;
                                
                                $deleted_comment_count++;
                                $is_delete=1; 
                                break;
                              
                            }
                            catch(Exception $e){
                                
                            }
                        }
                        if($is_delete_offensive == 'hide')
                        {
                            if(!in_array($comment_id,$previous_replied_list))
                            {
                                try{
                                    $this->fb_rx_login->hide_comment($comment_id,$post_access_token);
                                    $comment_result_info=array(
                                        "id" => $comment_id,
                                        "comment_text" =>$comment_text,
                                        "name"      =>$commenter_name,
                                        "commenter_id"      =>$commenter_id,
                                        "comment_time" =>$comment_time,
                                        "reply_time"   =>date("Y-m-d H:i:s")
                                    );
                                    $comment_result_info['reply_status_comment'] = "comment hidden";
                                    $comment_result_info['comment_reply_text'] = '';
                                    $comment_result_info['reply_text'] = '';
                                    $comment_result_info['reply_status']= '';
                                    $comment_result_info['reply_id']="";       
                                    
                                    $new_replied_info[]=$comment_result_info;
                                    $hidden_comment_count++;
                                    // increase auto reply count
                                    $new_replied_list[]=$comment_id;
                                    
                                }
                                catch(Exception $e){

                                }
                            }
                            // $is_delete = 1;
                            // break;
                        }
                    }
                }

                if($is_delete) continue;
                // comment hide and delete section



                /** If already Replied, dont sent again **/
                if(in_array($comment_id,$previous_replied_list)) continue;
                // added by mostofa on 27-04-2017 to prevent duplicate reply
                if($multiple_reply == 'no')
                {                 
                    if(in_array($commenter_id,$previous_replied_names)) continue;
                }
                

                /**    If not sent, then sent him reply ***/
                $new_replied_list[]=$comment_id;



                if($auto_reply_type=='generic'){
                    $auto_generic_reply__array=json_decode($auto_reply_private_message_raw,TRUE);

                    // image or video in comment section
                    $comment_image_link = $auto_generic_reply__array[0]['image_link'];
                    $comment_gif_link = '';
                    if($comment_image_link != '')
                    {
                        $image_link_array = explode('.', $comment_image_link);
                        $ext = array_pop($image_link_array);
                        if($ext != 'png' && $ext != 'PNG' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG')
                        {
                            $comment_gif_link = $comment_image_link;
                            $comment_image_link = '';
                        }
                    }
                    $comment_video_link = $auto_generic_reply__array[0]['video_link'];
                    if($comment_video_link != '')
                    {                        
                        $comment_video_link = str_replace(base_url(), '', $auto_generic_reply__array[0]['video_link']);
                        $comment_video_link = FCPATH.$comment_video_link;
                    }
                    // image or video in comment section

                    if(is_array($auto_generic_reply__array))
                    {
                        $auto_generic_reply__array[0]['private_reply'] = $auto_generic_reply__array[0]['private_reply'];
                        $auto_generic_reply__array[0]['comment_reply'] = $auto_generic_reply__array[0]['comment_reply'];
                    }
                    else
                    {
                        $auto_generic_reply__array[0]['private_reply'] = $auto_reply_private_message_raw;
                        $auto_generic_reply__array[0]['comment_reply'] = "";
                    }

                    $auto_reply_private_message = str_replace('#LEAD_USER_NAME#',$commenter_name,$auto_generic_reply__array[0]['private_reply']);
                    $auto_reply_private_message = str_replace("#LEAD_USER_FIRST_NAME#",$commenter_first_name,$auto_reply_private_message);
                    $auto_reply_private_message = str_replace("#LEAD_USER_LAST_NAME#",$commenter_last_name,$auto_reply_private_message);
                    // added by mostofa on 26-04-2017
                    $auto_reply_comment_message = str_replace('#LEAD_USER_NAME#',$commenter_name,$auto_generic_reply__array[0]['comment_reply']);
                    $auto_reply_comment_message = str_replace("#LEAD_USER_FIRST_NAME#",$commenter_first_name,$auto_reply_comment_message);
                    $auto_reply_comment_message = str_replace("#LEAD_USER_LAST_NAME#",$commenter_last_name,$auto_reply_comment_message);
                    $auto_reply_comment_message = str_replace("#TAG_USER#","@[".$commenter_id."]",$auto_reply_comment_message);
                }



                if($auto_reply_type=="filter"){

                    $auto_reply_private_message_array=json_decode($auto_reply_private_message_raw,TRUE);    

                    foreach($auto_reply_private_message_array as $message_info){

                        $filter_word= $message_info['filter_word'];
                        $filter_word = explode(",",$filter_word);


                        foreach($filter_word as $f_word){

                            if(function_exists('iconv') && function_exists('mb_detect_encoding')){
                                $encoded_word =  mb_detect_encoding($f_word);
                                if(isset($encoded_word)){
                                    $f_word = iconv( $encoded_word, "UTF-8//TRANSLIT", $f_word );
                                }
                            }

                            $pos= stripos($comment_text,trim($f_word));

                            if($pos!==FALSE){

                                // image or video in comment section
                                $comment_image_link = $message_info['image_link'];
                                $comment_gif_link = '';
                                if($comment_image_link != '')
                                {
                                    $image_link_array = explode('.', $comment_image_link);
                                    $ext = array_pop($image_link_array);
                                    if($ext != 'png' && $ext != 'PNG' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG')
                                    {
                                        $comment_gif_link = $comment_image_link;
                                        $comment_image_link = '';
                                    }
                                }
                                $comment_video_link = $message_info['video_link'];
                                if($comment_video_link != '')
                                {
                                    $comment_video_link = str_replace(base_url(), '', $message_info['video_link']);
                                    $comment_video_link = FCPATH.$comment_video_link;
                                }
                                // image or video in comment section

                                $auto_reply_private_message_individual = $message_info['reply_text'];
                                $auto_reply_comment_message_individual = $message_info['comment_reply_text'];

                                $auto_reply_private_message = str_replace('#LEAD_USER_NAME#',$commenter_name,$auto_reply_private_message_individual);
                                $auto_reply_private_message = str_replace("#LEAD_USER_FIRST_NAME#",$commenter_first_name,$auto_reply_private_message);
                                $auto_reply_private_message = str_replace("#LEAD_USER_LAST_NAME#",$commenter_last_name,$auto_reply_private_message);

                                // added by mostofa on 26-04-2017
                                $auto_reply_comment_message = str_replace('#LEAD_USER_NAME#',$commenter_name,$auto_reply_comment_message_individual);
                                $auto_reply_comment_message = str_replace("#LEAD_USER_FIRST_NAME#",$commenter_first_name,$auto_reply_comment_message);
                                $auto_reply_comment_message = str_replace("#LEAD_USER_LAST_NAME#",$commenter_last_name,$auto_reply_comment_message);
                                $auto_reply_comment_message = str_replace("#TAG_USER#","@[".$commenter_id."]",$auto_reply_comment_message);
                                break;
                            }

                        }   

                        if($pos!==FALSE) break;

                    }

                    if($auto_reply_private_message==""){


                        $auto_reply_private_message = str_replace('#LEAD_USER_NAME#',$commenter_name,$default_reply_no_filter_private);
                        $auto_reply_private_message = str_replace("#LEAD_USER_FIRST_NAME#",$commenter_first_name,$auto_reply_private_message);
                        $auto_reply_private_message = str_replace("#LEAD_USER_LAST_NAME#",$commenter_last_name,$auto_reply_private_message);
                    }

                    if($auto_reply_comment_message=='')
                    {
                        // image or video in comment section
                        $comment_image_link = $default_reply_no_filter_comment_image_link;
                        $comment_gif_link = '';
                        if($comment_image_link != '')
                        {
                            $image_link_array = explode('.', $comment_image_link);
                            $ext = array_pop($image_link_array);
                            if($ext != 'png' && $ext != 'PNG' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG')
                            {
                                $comment_gif_link = $comment_image_link;
                                $comment_image_link = '';
                            }
                        }
                        $comment_video_link = $default_reply_no_filter_comment_video_link;
                        if($comment_video_link != '')
                        {
                            $comment_video_link = str_replace(base_url(), '', $default_reply_no_filter_comment_video_link);
                            $comment_video_link = FCPATH.$comment_video_link;
                        }
                        // image or video in comment section
                        // added by mostofa on 26-04-2017
                        $auto_reply_comment_message = str_replace('#LEAD_USER_NAME#',$commenter_name,$default_reply_no_filter_comment);
                        $auto_reply_comment_message = str_replace("#LEAD_USER_FIRST_NAME#",$commenter_first_name,$auto_reply_comment_message);
                        $auto_reply_comment_message = str_replace("#LEAD_USER_LAST_NAME#",$commenter_last_name,$auto_reply_comment_message);
                        $auto_reply_comment_message = str_replace("#TAG_USER#","@[".$commenter_id."]",$auto_reply_comment_message);
                    }




                }


                $comment_result_info=array(
                    "id" => $comment_id,
                    "comment_text" =>$comment_text,
                    "name"      =>$commenter_name,
                    "commenter_id"      =>$commenter_id,
                    "comment_time" =>$comment_time,
                    "reply_time"   =>date("Y-m-d H:i:s")
                    );
                $auto_reply_comment_message = spintax_process($auto_reply_comment_message);
                $auto_reply_private_message = spintax_process($auto_reply_private_message);
                
                // added by mostofa on 27-04-2017
                $comment_result_info['comment_reply_text'] = $auto_reply_comment_message;                
                $comment_result_info['reply_text'] = $auto_reply_private_message;

                if($comment_reply_enabled == 'yes' && $auto_reply_comment_message!='')
                {
                    try
                    {
                        // $reply_info = $this->fb_rx_login->auto_comment($auto_reply_comment_message,$comment_id,$post_access_token);
                        $reply_info = $this->fb_rx_login->auto_comment($auto_reply_comment_message,$comment_id,$post_access_token,$comment_image_link,$comment_video_link,$comment_gif_link);
                        $comment_result_info['reply_status_comment']= "success";
                        $auto_comment_reply_count++;
                        if($hide_comment_after_comment_reply == 'yes')
                        {
                            try{
                                $this->fb_rx_login->hide_comment($comment_id,$post_access_token);
                                $comment_result_info['reply_status_comment'] = "comment hidden";
                                $hidden_comment_count++;
                            }catch(Exception $e){

                            }
                        }
                    }
                    catch(Exception $e){
                        $comment_result_info['reply_status_comment']= $e->getMessage();
                    }
                }


                try{

                    if($auto_reply_private_message!=""){
                        $send_reply_info=$this->fb_rx_login->send_private_reply($auto_reply_private_message,$comment_id,$post_access_token);                     
                        $auto_reply_delay_time=$this->config->item('auto_reply_delay_time');
                        sleep($auto_reply_delay_time);


                        if(isset($send_reply_info['error'])){
                            $comment_result_info['reply_status']= $send_reply_info['error']['message'];
                            $comment_result_info['reply_id']="";
                        }

                        else{

                            $comment_result_info['reply_status']= "success";
                            $comment_result_info['reply_id']=isset($send_reply_info['id'])?$send_reply_info['id']:"";
                        }   
                    }


                    else{
                        $comment_result_info['reply_status']= "Not Replied ! No match found corresponding filter words";
                        $comment_result_info['reply_id']="";
                    }
                }

                catch(Exception $e){
                    $comment_result_info['reply_status']= $e->getMessage();
                    $comment_result_info['reply_id']="";
                }







                // added by mostofa on 26-04-2017 for comment reply
                if($auto_like_comment == 'yes')
                {
                    try
                    {
                        $this->fb_rx_login->auto_like($comment_id,$post_access_token);
                    }
                    catch(Exception $e){

                    }


                }


                $new_replied_info[]=$comment_result_info;

            }


            skipped:
            /*****  Update post *****/
            $auto_private_reply_count_new=count($new_replied_list);
            $new_replied_list=json_encode($new_replied_list);
            $new_replied_info=json_encode($new_replied_info);
            $update_data = array("auto_private_reply_status"=>"0",
                "auto_private_reply_done_ids"=>$new_replied_list,
                "auto_private_reply_count"=>$auto_private_reply_count_new,
                "auto_reply_done_info" =>$new_replied_info,
                "last_reply_time" => date("Y-m-d H:i:s"),
                "hidden_comment_count" => $hidden_comment_count,
                "deleted_comment_count" => $deleted_comment_count,
                "auto_comment_reply_count" => $auto_comment_reply_count
                );
            if($skip_error_message != '')
            {
                $update_data['auto_private_reply_status'] = '1';
                $update_data['error_message'] = $skip_error_message;
            }
            $this->basic->update_data("facebook_ex_autoreply",array("id"=>$post_column_id),$update_data);


        }

    }






    public function auto_lead_list_sync($api_key="")
    {
    
        $this->api_key_check($api_key);
        $this->load->library('Fb_rx_login');

        $auto_sync_data = $this->basic->get_data("facebook_rx_fb_page_info",array("where"=>array("auto_sync_lead"=>"1")));

        foreach ($auto_sync_data as $key2 => $value2) 
        {
            $facebook_rx_fb_page_info_id = $value2['id'];
            $get_concersation_info = $this->fb_rx_login->get_all_conversation_page($value2['page_access_token'],$value2['page_id']);
            $success = 0;
            $total=0;

            $facebook_rx_fb_user_info_id = $value2['facebook_rx_fb_user_info_id'];                
            $db_page_id =  $value2['page_id'];
            $db_user_id =  $value2['user_id'];
            foreach($get_concersation_info as &$item) 
            {                
                $db_client_id  =  $item['id'];
                $db_client_thread_id  =   $item['thead_id'];
                $db_client_name  =  $this->db->escape($item['name']);
                $db_permission  =  '1';
                $subscribed_at=date("Y-m-d H:i:s");

                $this->basic->execute_complex_query("INSERT IGNORE INTO facebook_rx_conversion_user_list(page_id,user_id,client_thread_id,client_id,client_username,permission,subscribed_at) VALUES('$db_page_id',$db_user_id,'$db_client_thread_id','$db_client_id',$db_client_name,'$db_permission','$subscribed_at');");
                if($this->db->affected_rows() != 0) $success++ ;

                $total++;
            }
            $this->basic->update_data("facebook_rx_fb_page_info",array("page_id"=>$db_page_id,"user_id"=>$db_user_id,"facebook_rx_fb_user_info_id"=>$facebook_rx_fb_user_info_id),array("last_lead_sync"=>date("Y-m-d H:i:s"),"current_lead_count"=>$total));

            $sql = "SELECT count(id) as permission_count FROM `facebook_rx_conversion_user_list` WHERE page_id='$db_page_id' AND permission='1' AND user_id=".$db_user_id;
            $count_data = $this->db->query($sql)->row_array();

            // how many are subscribed and how many are unsubscribed
            $subscribed = isset($count_data["permission_count"]) ? $count_data["permission_count"] : 0;
            $unsubscribed = abs($total - $subscribed); 
            $this->basic->update_data("facebook_rx_fb_page_info",array("page_id"=>$db_page_id,"facebook_rx_fb_user_info_id"=>$facebook_rx_fb_user_info_id),array("current_subscribed_lead_count"=>$subscribed,"current_unsubscribed_lead_count"=>$unsubscribed));
        
        } 
       
        
    }



    public function fb_exciter_send_inbox_message($api_key="")
    {
        $this->api_key_check($api_key);
        $number_of_message_to_be_sent_in_try=$this->config->item("number_of_message_to_be_sent_in_try");
        if($number_of_message_to_be_sent_in_try==0) $number_of_message_to_be_sent_in_try="";
        $update_report_after_time=$this->config->item("update_report_after_time"); 
        if($update_report_after_time=="" || $update_report_after_time==0) $update_report_after_time=10;
        $number_of_campaign_to_be_processed = 1; // max number of campaign that can be processed by this cron job
        // $number_of_message_tob_be_sent = 50000;  // max number of message that can be sent in an hour

        /****** Get all campaign from database where status=0 means pending ******/
        $where['or_where'] = array('posting_status'=>"0","is_try_again"=>"1");
        $join = array('users'=>'facebook_ex_conversation_campaign.user_id=users.id,left');
        $campaign_info= $this->basic->get_data("facebook_ex_conversation_campaign",$where,$select=array("facebook_ex_conversation_campaign.*","users.deleted as user_deleted","users.status as user_status"),$join,$limit=50, $start=0, $order_by='schedule_time ASC');  

        // echo $this->db->last_query();
        // echo "<pre>";
        // print_r($campaign_info);
        // exit();

        $access_token_database_database = array(); //  [campaign_id][page_auto_id] =>access token
        $facebook_rx_fb_user_info_id_database = array(); // campaign_id => facebook_rx_fb_user_info_id
        $facebook_rx_config_id_database = array(); // facebook_rx_fb_user_info_id => facebook_rx_config_id
        $campaign_id_array=array();  // all selected campaign id array
        $campaign_info_fildered = array(); // valid for process, campign info array
        $page_ids_names = array(); // page_auto id => page name

        // echo $this->db->last_query(); exit();
        $valid_campaign_count = 1;
        foreach($campaign_info as $info)
        {
            if($info['user_deleted'] == '1' || $info['user_status']=="0") continue;

            $campaign_id= $info['id'];
            $time_zone= $info['time_zone'];
            $schedule_time= $info['schedule_time']; 
            $total_thread = $info["total_thread"];
            $page_ids = explode(',', $info["page_ids"]); // auto ids
            $user_id = $info["user_id"];
            
            // $count_total_thread = $count_total_thread + $total_thread;            

            if($time_zone) date_default_timezone_set($time_zone);            
            $now_time = date("Y-m-d H:i:s");

            if((strtotime($now_time) < strtotime($schedule_time)) && $time_zone!="") continue; 
            if($valid_campaign_count > $number_of_campaign_to_be_processed) break; 

            // get access token and fb user id
            $token_info =  $this->basic->get_data('facebook_rx_fb_page_info',array("where_in"=>array('id'=>$page_ids,'user_id'=>array($user_id))),array("page_access_token","facebook_rx_fb_user_info_id","id","page_name"));
            foreach ($token_info as $key => $value) 
            {
                $access_token_database_database[$campaign_id][$value["id"]] = $value['page_access_token'];
                $facebook_rx_fb_user_info_id = $value["facebook_rx_fb_user_info_id"];
                $facebook_rx_fb_user_info_id_database[$campaign_id] = $facebook_rx_fb_user_info_id;
                $page_ids_names[$value["id"]] = $value["page_name"];
            }
           
            // valid campaign info and campig ids
            $campaign_info_fildered[] = $info;
            $campaign_id_array[] = $info['id']; 
            $valid_campaign_count++;      
        }


        if(count($campaign_id_array)==0) exit();        

        $this->db->where_in("id",$campaign_id_array);
        $this->db->update("facebook_ex_conversation_campaign",array("posting_status"=>"1","is_try_again"=>"0"));

        // get config id
        $getdata= $this->basic->get_data("facebook_rx_fb_user_info",array("where_in"=>array("id"=>$facebook_rx_fb_user_info_id_database)),array("id","facebook_rx_config_id"));
        foreach ($getdata as $key => $value) 
        {
            $facebook_rx_config_id_database[$value["id"]] = $value["facebook_rx_config_id"];
        } 


        $this->load->library("fb_rx_login");
        // echo "<pre>";print_r($campaign_info); 
        // print_r($campaign_info_fildered); exit();
        // send message
        foreach($campaign_info_fildered as $info)
        {
            $campaign_id= $info['id'];            
            $campaign_message= $info['campaign_message'];  
            $video_url = $info["attached_video"];   
            $link = $info["attached_url"]; 
            $user_id = $info["user_id"]; 
            $delay_time = $info["delay_time"];
            $unsubscribe_button = $info["unsubscribe_button"];
            $catch_error_count=$info["last_try_error_count"];
            $successfully_sent=$info["successfully_sent"];

            $fb_rx_fb_user_info_id = $facebook_rx_fb_user_info_id_database[$campaign_id]; // find gb user id for this campaign
            // $this->session->set_userdata("fb_rx_login_database_id", $facebook_rx_config_id_database[$fb_rx_fb_user_info_id]);    // find fb config id for this fb user info and set to session to call library
            $this->fb_rx_login->app_initialize($facebook_rx_config_id_database[$fb_rx_fb_user_info_id]);

            $report = json_decode($info["report"],true); // get json lead list from database and decode it
            $i=0;
            $send_report = $report;
            $is_spam_caught_send = "0"; // is facebook marked this message as spam?
        
            $campaign_lead=$this->basic->get_data("facebook_ex_conversation_campaign_send",array("where"=>array("campaign_id"=>$campaign_id,"processed"=>"0")),'','',$number_of_message_to_be_sent_in_try);

            foreach($campaign_lead as $key => $value) 
            {
                if($catch_error_count>10)  // if 10 catch block error then stop sending, mark as complete
                {
                    $send_report_json= json_encode($send_report);
                    $this->basic->update_data("facebook_ex_conversation_campaign",array("id"=>$campaign_id),array("report"=>$send_report_json,"posting_status"=>'2','successfully_sent'=>$successfully_sent,'completed_at'=>date("Y-m-d H:i:s"),"is_spam_caught"=>$is_spam_caught_send,"error_message"=>$error_msg,"is_try_again"=>"0","last_try_error_count"=>$catch_error_count));
                    break;
                }

                $page_id_send  = $value["page_id"];

                $send_table_id = $value['id'];
                $client_thread_id_send = $value['client_thread_id'];
                $client_id_send = $value['client_id'];
                $client_username_send = $value['client_username'];
                $client_username_send_array = explode(' ', $client_username_send);
                $client_last_name = array_pop($client_username_send_array);
                $client_first_name = implode(' ', $client_username_send_array);
                
                $error_msg="";
                $message_error_code = "";
                $message_sent_id = "";

                if(!isset($access_token_database_database[$campaign_id][$page_id_send])) continue;
                $page_access_token_send = $access_token_database_database[$campaign_id][$page_id_send]; // get access toke from our access token database

                //  generating message
                $campaign_message_send = $campaign_message;
                $campaign_message_send = str_replace('#LEAD_USER_FIRST_NAME#',$client_first_name,$campaign_message_send);
                $campaign_message_send = str_replace('#LEAD_USER_LAST_NAME#',$client_last_name,$campaign_message_send);
                
                if($video_url!="") $campaign_message_send = $campaign_message_send."\n".$video_url;
                else if($link!="") $campaign_message_send = $campaign_message_send."\n".$link;

                // generate unsubscribe link
                if($unsubscribe_button=="1")
                {
                    $code = $this->_random_number_generator(6)."_".$value["lead_id"]."_".$page_id_send."_".$this->_random_number_generator(6);
                    $code= base64_encode($code);
                    $code= urlencode($code);
                    $unsubscribe_link =site_url("home/ul/".$code);
                    $campaign_message_send = $campaign_message_send."\n\nUnsubscribe link : \n".$unsubscribe_link."\n";
                }

                
                try
                {
                    $campaign_message_send = spintax_process($campaign_message_send);
                    $response = $this->fb_rx_login->send_message_to_thread($client_thread_id_send,$campaign_message_send,$page_access_token_send);
                    if(isset($response['id']))
                    {
                        $message_sent_id = $response['id']; 
                        $successfully_sent++; 
                    }
                    else 
                    {
                        if(isset($response["error"]["message"])) $message_sent_id = $response["error"]["message"];  
                        if(isset($response["error"]["code"])) $message_error_code = $response["error"]["code"]; 

                        if($message_error_code=="368") // if facebook marked message as spam 
                        {
                            $error_msg=$message_sent_id;
                            $is_spam_caught_send = "1";
                        }

                        else if($message_error_code=="230") //user blocked page
                        {
                            $this->basic->update_data("facebook_rx_conversion_user_list",array("id"=>$value["lead_id"]),array("permission"=>"0"));
                            if($this->db->affected_rows()>0)
                            {
                                $this->basic->execute_complex_query("UPDATE facebook_rx_fb_page_info SET current_subscribed_lead_count=current_subscribed_lead_count-1,current_unsubscribed_lead_count=current_unsubscribed_lead_count+1 WHERE id='{$page_id_send}'");
                            }
                        }
                        else
                        {
                            $error_msg = $message_sent_id;
                            $catch_error_count++;
                        }
                    } 

                    if($delay_time==0)
                    sleep(rand(3,12));
                    else sleep($delay_time);                  
                    
                }

                catch(Exception $e) 
                {
                  $error_msg = $e->getMessage();
                  // $catch_error_count++;
                }

                // generating new report with send message info
                $now_sent_time=date("Y-m-d H:i:s");
                $send_report[$page_id_send][$client_thread_id_send] = array
                ( 
                    "client_username"=>$client_username_send,
                    "client_id"=>$client_id_send,
                    "message_sent_id"=> $message_sent_id,
                    "sent_time"=> $now_sent_time,
                    "page_name" => $page_ids_names[$page_id_send],
                    "lead_id" => $value["lead_id"]
                );

                $i++;  
                // after 10 send update report in database
                if($i%$update_report_after_time==0)
                {
                    $send_report_json= json_encode($send_report);
                    $this->basic->update_data("facebook_ex_conversation_campaign",array("id"=>$campaign_id),array("report"=>$send_report_json,'successfully_sent'=>$successfully_sent,"error_message"=>$error_msg,"last_try_error_count"=>$catch_error_count));
                }
                
                if($message_error_code=="368")  // if facebook marked message as spam , then stop sending, mark as complete
                {
                    $send_report_json= json_encode($send_report);
                    $this->basic->update_data("facebook_ex_conversation_campaign",array("id"=>$campaign_id),array("report"=>$send_report_json,"posting_status"=>'2','successfully_sent'=>$successfully_sent,'completed_at'=>date("Y-m-d H:i:s"),"is_spam_caught"=>$is_spam_caught_send,"error_message"=>$error_msg,"is_try_again"=>"0","last_try_error_count"=>$catch_error_count));
                    break;
                }

                // updating a lead, marked as processed
                $this->basic->update_data("facebook_ex_conversation_campaign_send",array("id"=>$send_table_id),array('processed'=>'1',"sent_time"=>$now_sent_time,"message_sent_id"=>$message_sent_id,"page_name"=>$page_ids_names[$page_id_send]));
            
            } 

            // one campaign completed, now update database finally
            $send_report_json= json_encode($send_report);
            if((count($campaign_lead)<$number_of_message_to_be_sent_in_try) || $number_of_message_to_be_sent_in_try=="" || $catch_error_count>10 || $message_error_code=="368")
            {
                $complete_update=array("report"=>$send_report_json,"posting_status"=>'2','successfully_sent'=>$successfully_sent,'completed_at'=>date("Y-m-d H:i:s"),"is_spam_caught"=>$is_spam_caught_send,"is_try_again"=>"0","last_try_error_count"=>$catch_error_count);
                if(isset($error_msg))
                $complete_update["error_message"]=$error_msg;
                $this->basic->update_data("facebook_ex_conversation_campaign",array("id"=>$campaign_id),$complete_update);
            }
            else // suppose update_report_after_time=20 but there are 19 message to sent, need to update report in that case
            {
                $this->basic->update_data("facebook_ex_conversation_campaign",array("id"=>$campaign_id),array("report"=>$send_report_json,'successfully_sent'=>$successfully_sent,"is_try_again"=>"1"));
            }
        }          
   
    }
    
    
    
    
    public function sync_page_messages($api_key="",$user_id="",$facebook_rx_fb_user_info_id=""){
        $this->api_key_check($api_key);
        $this->load->library('Fb_rx_login');
        
        if($user_id)
            $where['user_id'] = $user_id;
        if($facebook_rx_fb_user_info_id)
            $where['facebook_rx_fb_user_info_id']=$facebook_rx_fb_user_info_id;
            
        $where['msg_manager']='1';
        
        $where_simple['where']=$where;
        
        $pages_info_for_sync = $this->basic->get_data("facebook_rx_fb_page_info",$where_simple);
        
        foreach($pages_info_for_sync as $page){
        
            $facebook_rx_fb_page_info_id = $page['facebook_rx_fb_user_info_id'];
            $user_id = $page['user_id'];
            $page_table_id= $page['id'];
            
            $get_concersation_info = $this->fb_rx_login->get_all_conversation_page($page['page_access_token'],$page['page_id']);
            
            foreach($get_concersation_info as $conversion_info){
            
                $from_user     = $this->db->escape($conversion_info['name']);
                $from_user_id  = $conversion_info['id'];
                $last_snippet  = $this->db->escape($conversion_info['snippet']);
                $message_count = $conversion_info['message_count'];
                $thread_id     = $conversion_info['thead_id'];
                $inbox_link    = $conversion_info['link'];
                $unread_count  = $conversion_info['unread_count'];
                $sync_time     = date("Y-m-d H:i:s");
                $last_update_time=date('Y-m-d H:i:s',strtotime($conversion_info['updated_time']));
                
                /***Insert into database **/
                
                 $sql="Insert into fb_msg_manager(user_id,facebook_rx_fb_user_info_id,from_user,from_user_id,last_snippet,message_count,thread_id,inbox_link,unread_count,sync_time,last_update_time,page_table_id) 
                    values ('$user_id','$facebook_rx_fb_page_info_id',$from_user,'$from_user_id',$last_snippet,'$message_count','$thread_id','$inbox_link','$unread_count','$sync_time','$last_update_time','$page_table_id')
                    
                    ON DUPLICATE KEY UPDATE  
                    
                last_snippet = $last_snippet, message_count= '$message_count', inbox_link='$inbox_link', unread_count='$unread_count',
                    sync_time='$sync_time',last_update_time='$last_update_time'";
                
                $this->basic->execute_complex_query($sql);
                
                    
            }
                
        }
        
        
    }


    //once a day
    public function delete_junk_data($api_key="")
    {
        $this->api_key_check($api_key);
        $this->basic->delete_data("facebook_ex_conversation_campaign_send",array("processed"=>"1"));
    }


    public function send_messenger_notification($api_key="")
    {
        $this->api_key_check($api_key);
        $where['where'] = array('is_enabled'=>'yes');
        $details = $this->basic->get_data('fb_msg_manager_notification_settings',$where);

        foreach($details as $detail)
        {
            $user_info = $this->basic->get_data('users',array('where'=>array('id'=>$detail['user_id'])),array('name'));
            $user_name = $user_info[0]['name'];

            $last_sent_time = $detail['last_email_time'];
            $time_interval = $detail['time_interval']+10;
            $plus_time = "+".$time_interval." minutes";
            $compare_time = date("Y-m-d H:i:s",strtotime($plus_time,strtotime($last_sent_time)));
            $present_time = date("Y-m-d H:i:s");

            $compare_value = strtotime($compare_time);
            $current_value = strtotime($present_time);

            if($compare_value <= $current_value)
            {
                $where = array();
                $where['where'] = array(
                    'fb_msg_manager.user_id'=>$detail['user_id'],
                    'fb_msg_manager.facebook_rx_fb_user_info_id'=>$detail['facebook_rx_fb_user_info_id'],
                    'unread_count !=' => '0'
                    );
                $join = array("facebook_rx_fb_page_info"=>"fb_msg_manager.page_table_id=facebook_rx_fb_page_info.id,left");
                $select = array('fb_msg_manager.*','facebook_rx_fb_page_info.page_name');

                $message_info = $this->basic->get_data('fb_msg_manager',$where,$select,$join);

                $str_header = "<b>Hello ".$user_name.",</b><br/><br/>";
                $str_table = '<br/><br/><table border="1" style="border-collapse:collapse;">
                                <th bgcolor="#fafafa" style="padding:5px">Page Name</th>
                                <th bgcolor="#fafafa" style="padding:5px">From</th>
                                <th bgcolor="#fafafa" style="padding:5px">Message</th>';
                
                foreach($message_info as $message)
                {
                    $str_table .= '<tr>
                                    <td style="padding:5px">'.$message['page_name'].'</td>
                                    <td style="padding:5px">'.$message['from_user'].'</td>
                                    <td style="padding:5px">'.$message['last_snippet'].'</td>
                                </tr>';
                }
                $str_table .= "</table><br/><br/>Thanks for using our service.<br/><b>".$this->config->item('product_name')." Team </b>";

                $total_unread = count($message_info);
                $str_subject = "You have {$total_unread} unread facebook page conversations";

                $str = $str_header.$str_subject.$str_table;

                $from = $this->config->item('institute_email');
                $to = $detail['email_address'];
                $subject = $this->config->item('product_name')." | ".$str_subject;
                $mask = $this->config->item('product_name');
                $html = 1;

                $this->_mail_sender($from, $to, $subject, $str, $mask, $html);
                    $this->basic->update_data('fb_msg_manager_notification_settings',array('user_id'=>$detail['user_id'],'facebook_rx_fb_user_info_id'=>$detail['facebook_rx_fb_user_info_id']),array('last_email_time'=>date("Y-m-d H:i:s")));
            }
            else
                continue;
        }


    }

    public function cta_poster_cron_job($api_key="")
    {
        $this->api_key_check($api_key);

        $this->load->library('Fb_rx_login');
        
        $where['where']=array("posting_status"=>"0");
        
        $select="schedule_time,time_zone,cta_value,facebook_rx_cta_post.id as column_id,page_id,page_group_user_id,page_access_token,cta_type,message,link,link_preview_image,link_description,link_caption,facebook_rx_cta_post.facebook_rx_fb_user_info_id";        
        $join=array('facebook_rx_fb_page_info'=>"facebook_rx_fb_page_info.id=facebook_rx_cta_post.page_group_user_id,left");
        
        /***    Taking fist 200 post for auto reply ***/
        $post_info= $this->basic->get_data("facebook_rx_cta_post",$where,$select,$join,$limit=200, $start=0,$order_by='schedule_time ASC');
        
        $campaign_id_array=array();

        foreach($post_info as $info)
        {
            $time_zone= $info['time_zone'];
            $schedule_time= $info['schedule_time']; 

            if($time_zone) date_default_timezone_set($time_zone);            
            $now_time = date("Y-m-d H:i:s");
            
            if(strtotime($now_time) < strtotime($schedule_time)) continue; 

            $campaign_id_array[] = $info['column_id'];       
        }

        if(empty($campaign_id_array)) exit();
        $this->db->where_in("id",$campaign_id_array);
        $this->db->update("facebook_rx_cta_post",array("posting_status"=>"1"));

        $config_id_database = array();
        foreach($post_info as $info)
        {
            
            $page_id =   $info['page_id'];
            $page_access_token = $info['page_access_token'];
            $post_column_id= $info['column_id'];

            if(!in_array($post_column_id, $campaign_id_array)) continue;

            $cta_type = $info["cta_type"];
            $cta_value = $info["cta_value"];
            $message = $info["message"];
            $link = $info["link"];
            $link_preview_image = $info["link_preview_image"];
            $link_caption = $info["link_caption"];
            $link_description = $info["link_description"];

            $time_zone= $info['time_zone'];
            $schedule_time= $info['schedule_time'];   

            // setting fb confid id for library call
            $fb_rx_fb_user_info_id= $info['facebook_rx_fb_user_info_id'];
            if(!isset($config_id_database[$fb_rx_fb_user_info_id]))
            {
                $config_id_database[$fb_rx_fb_user_info_id] = $this->get_fb_rx_config($fb_rx_fb_user_info_id);
            }
            // $this->session->set_userdata("fb_rx_login_database_id", $config_id_database[$fb_rx_fb_user_info_id]);
            $this->fb_rx_login->app_initialize($config_id_database[$fb_rx_fb_user_info_id]);
            // setting fb confid id for library call         
                                    
            $response =array();
            $error_msg ="";


            try
            {
                $response = $this->fb_rx_login->cta_post($message, $link,"","",$cta_type,$cta_value,"","",$page_access_token,$page_id);                   
            }
            catch(Exception $e) 
            {
              $error_msg = $e->getMessage();
            }
            
            $object_id=isset($response["id"]) ? $response["id"] : "";
            
            $temp_data=array();
            try
            {
                $temp_data=$this->fb_rx_login->get_post_permalink($object_id,$page_access_token);
            }
            catch(Exception $e) 
            {
                $error_msg1 = $e->getMessage();
            }

            $post_url= isset($temp_data["permalink_url"]) ? $temp_data["permalink_url"] : "";               

            $update_data = array("posting_status"=>'2',"post_id"=>$object_id,"post_url"=>$post_url,"error_mesage"=>$error_msg,"last_updated_at"=>date("Y-m-d H:i:s"));

            $this->basic->update_data("facebook_rx_cta_post",array("id"=>$post_column_id),$update_data);

            sleep(rand ( 1 , 10 ));


        }
            
    }
    
}
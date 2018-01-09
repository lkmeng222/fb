<?php

require_once("Home.php"); // including home controller

/**
* class config
* @category controller
*/
class Facebook_ex_dashboard extends Home
{
	public $user_id;
    /**
    * load constructor method
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');
        $this->user_id=$this->session->userdata('user_id');
    
        set_time_limit(0);
        $this->important_feature();
        $this->member_validity();     
    }

    /**
    * load index method. redirect to config
    * @access public
    * @return void
    */
    public function index()
    {
        $this->fb_ex_admin_dashboard();
    }

    public function fb_ex_admin_dashboard()
    {	
    	// $page_info = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('user_id'=>$this->user_id,'deleted'=>'0')),array('id'));
        // $data['page_number'] = count($page_info);

        $subscriber_info = $this->basic->get_data('facebook_rx_conversion_user_list',array('where' => array('user_id' => $this->user_id,'permission'=>'1')),array('id'));
        $data['subscriber_number'] = count($subscriber_info);

        $unsubscriber_info = $this->basic->get_data('facebook_rx_conversion_user_list',array('where' => array('user_id' => $this->user_id,'permission'=>'0')),array('id'));
        $data['unsubscriber_number'] = count($unsubscriber_info);

        $message_info = $this->basic->get_data('facebook_ex_conversation_campaign',array('where' => array('user_id' => $this->user_id, 'posting_status' => '2')),array('sum(successfully_sent) as total_sent_message'));
        $data['message_number'] = $message_info[0]['total_sent_message'];

        $auto_reply_enable = $this->basic->get_data('facebook_ex_autoreply',array('where' => array('user_id' => $this->user_id)),array('count(id) as auto_reply_enable'));
        $data['auto_reply_enable'] = $auto_reply_enable[0]['auto_reply_enable'];
        
        $chat_plugin_enable = $this->basic->get_data('fb_chat_plugin',array('where' => array('user_id' => $this->user_id)),array('count(id) as chat_plugin_enable'));
        $data['chat_plugin_enable'] = $chat_plugin_enable[0]['chat_plugin_enable'];


        $date_for_month = date("m");
        $date_for_year_rr = date("Y");
        $subscribergained_this_month = $this->db->query('SELECT COUNT(id) as sub_this_month FROM facebook_rx_conversion_user_list WHERE MONTH(subscribed_at) = "'.$date_for_month.'" && YEAR(subscribed_at) = "'.$date_for_year_rr.'" && user_id = "'.$this->user_id.'" && permission = "1"')->row();
        $data['subscribergained_this_month'] = $subscribergained_this_month->sub_this_month;

        $unsubscribergained_this_month = $this->db->query('SELECT COUNT(id) as unsub_this_month FROM facebook_rx_conversion_user_list WHERE MONTH(subscribed_at) = "'.$date_for_month.'" && YEAR(subscribed_at) = "'.$date_for_year_rr.'" && user_id = "'.$this->user_id.'" && permission = "0"')->row();
        $data['unsubscribergained_this_month'] = $unsubscribergained_this_month->unsub_this_month;



        $campaign_info = $this->basic->get_data('facebook_ex_conversation_campaign',array('where' => array('user_id' => $this->user_id)));
        $colors_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
        $campaign_details = array();
        $campaign_details['pending'] = 0;
        $campaign_details['processing'] = 0;
        $campaign_details['completed'] = 0;
        foreach($campaign_info as $value)
        {
            if($value['posting_status'] == '0')
                $campaign_details['pending']++;

            if($value['posting_status'] == '1')
                $campaign_details['processing']++;

            if($value['posting_status'] == '2')
                $campaign_details['completed']++;
        }
        $campaign_info_chart_data = array(
            0 => array(
                'value' => $campaign_details['pending'],
                'color' => $colors_array[6],
                'highlight' => $colors_array[6],
                'label' => 'Pending'
                ),
            1 => array(
                'value' => $campaign_details['processing'],
                'color' => $colors_array[7],
                'highlight' => $colors_array[7],
                'label' => 'Processing'
                ),
            2 => array(
                'value' => $campaign_details['completed'],
                'color' => $colors_array[8],
                'highlight' => $colors_array[8],
                'label' => 'Completed'
                ),
        );

        $data['campaign_details_completed'] = $campaign_details['completed'];
        $data['campaign_details_processing'] = $campaign_details['processing'];
        $data['campaign_details_pending'] = $campaign_details['pending'];

        $campaign_info_list_data = '<li><i class="fa fa-circle-o" style="color: '.$colors_array[6].';"></i> Pending : '.$campaign_details["pending"].'</li>';
        $campaign_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[7].';"></i> Processing : '.$campaign_details["processing"].'</li>';
        $campaign_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[8].';"></i> Completed : '.$campaign_details["completed"].'</li>';

        $data['campaign_info_chart_data'] = json_encode($campaign_info_chart_data);
        $data['campaign_info_list_data'] = $campaign_info_list_data;


        $total_auto_replay = $this->basic->get_data('facebook_ex_autoreply', array('where' => array('user_id' => $this->user_id)), array('sum(auto_private_reply_count) as total_auto_replay_data'));
        $data['total_auto_replay'] = $total_auto_replay[0]['total_auto_replay_data'];

        $last_auto_reply_post_info = $this->basic->get_data('facebook_ex_autoreply',array('where' => array('user_id' => $this->user_id)),$select='auto_reply_done_info,post_description',$join='',$limit='3',$start=NULL,'last_reply_time DESC');
        

        $i=0;
        $array1=array();
        foreach ($last_auto_reply_post_info as $key => $value) 
        {
            $decode = json_decode($value['auto_reply_done_info'],true);
            
            foreach ($decode as $key2 => $value2) 
            {
                $array1[$i] = $value2;

                $pieces = explode(" ", $value['post_description']);
                $post_name_data = implode(" ", array_splice($pieces, 0, 4));

                $array1[$i]['post_name'] =  $post_name_data."...";
                $i++;
            }
        }

        $ord = array();
        foreach ($array1 as $key => $value){
            $ord[] = strtotime($value['comment_time']);
        }
        array_multisort($ord, SORT_DESC, $array1);
        $firstThreeElements = array_slice($array1, 0, 3);
    
        
        

        $last_campaign_completed_info = $this->basic->get_data('facebook_ex_conversation_campaign',array('where' => array('user_id' => $this->user_id,'posting_status' => '2')),$select='',$join='',$limit='3',$start=NULL,'added_at DESC');
        
        $last_campaign_pending_info = $this->basic->get_data('facebook_ex_conversation_campaign',array('where' => array('user_id' => $this->user_id,'posting_status' => '0')),$select='',$join='',$limit='3',$start=NULL,'added_at DESC');

        $data['my_last_auto_reply_data'] = $firstThreeElements;
        $data['last_campaign_completed_info'] = $last_campaign_completed_info;
        $data['last_campaign_pending_info'] = $last_campaign_pending_info;
        
        $year = date('Y')-1;
        $last_issue_year = date("$year-m-d");
        $where['where'] = array("date_format(completed_at,'%Y-%m-%d') >=" => $last_issue_year,'user_id' => $this->user_id,'posting_status' => '2');
        $select = array('successfully_sent','completed_at');
        $order_by = "completed_at ASC";
        $message_12_months = $this->basic->get_data('facebook_ex_conversation_campaign', $where, $select, $join='', $limit='', $start=null, $order_by);
        $i=0;
        foreach ($message_12_months as $result) {
            $completed_at_month = date('n', strtotime($result['completed_at']));
            $completed_at_year = date('Y', strtotime($result['completed_at']));

            if (isset($yearly_message[$completed_at_month][$completed_at_year])) {
                $yearly_message[$completed_at_month][$completed_at_year] += $result['successfully_sent'];
                $yearly_campaign[$completed_at_month][$completed_at_year] = ++$i;
            } else {
                $yearly_message[$completed_at_month][$completed_at_year] = $result['successfully_sent'];
                $yearly_campaign[$completed_at_month][$completed_at_year] = 1;
            }

        }


        $date_for_month = date("n");
        $date_for_year = date("Y");
        $data['campaign_completed_this_month'] = isset( $yearly_campaign[$date_for_month][$date_for_year]) ?  $yearly_campaign[$date_for_month][$date_for_year] : 0;
        $data['message_number_month'] = isset($yearly_message[$date_for_month][$date_for_year]) ? $yearly_message[$date_for_month][$date_for_year] : 0;

       
        $chart_array=array();
        $cur_year=date('Y');
        $cur_month=date('m');
        $cur_month=(int)$cur_month;
        $months_name = array(1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec');

        for ($i=0;$i<=11;$i++) {
            $m_for_chart=$months_name[$cur_month];
            $m = $cur_month;
            $chart_array[$i]['year']=$m_for_chart."-".$cur_year;

            if (isset($yearly_message[$m][$cur_year])) {
                $chart_array[$i]['sent_message']=$yearly_message[$m][$cur_year];
            } else {
                $chart_array[$i]['sent_message']=0;
            }
            if (isset($yearly_campaign[$m][$cur_year])) {
                $chart_array[$i]['sent_campaign']=$yearly_campaign[$m][$cur_year];
            } else {
                $chart_array[$i]['sent_campaign']=0;
            }

            $cur_month=$cur_month-1;

            if ($cur_month==0) {
                $cur_month=12;
                $cur_year=$cur_year-1;
            }
        }

        $chart_array=array_reverse($chart_array);




        $data['chart_bar'] = $chart_array;
        
        $data['campaign_info'] = $campaign_info;
        $colors_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");

    	$data['body'] = 'facebook_ex/dashboard';
        $data['page_title'] = $this->lang->line('Dashboard');
        $this->_viewcontroller($data);
    }
}
<?php

require_once("Home.php"); // including home controller

class Facebook_rx_dashboard extends Home
{

    public $user_id;    
    
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


    public function index()
    {
        $account_info = $this->basic->get_data('facebook_rx_fb_user_info',array('where'=>array('user_id'=>$this->user_id,'deleted'=>'0')),array('id'));
        $data['account_number'] = count($account_info);

        $page_info = $this->basic->get_data('facebook_rx_fb_page_info',array('where'=>array('user_id'=>$this->user_id,'deleted'=>'0')),array('id'));
        $data['page_number'] = count($page_info);

        $group_info = $this->basic->get_data('facebook_rx_fb_group_info',array('where'=>array('user_id'=>$this->user_id,'deleted'=>'0')),array('id'));
        $data['group_number'] = count($group_info);

        $colors_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
        $text_post_info = $this->basic->get_data('facebook_rx_auto_post',array('where'=>array('post_type'=>'text_submit','user_id'=>$this->user_id)),array('post_type','posting_status'));
        $text_post_details = array();
        $text_post_details['pending'] = 0;
        $text_post_details['processing'] = 0;
        $text_post_details['completed'] = 0;
        foreach($text_post_info as $value)
        {
            if($value['posting_status'] == '0')
                $text_post_details['pending']++;

            if($value['posting_status'] == '1')
                $text_post_details['processing']++;

            if($value['posting_status'] == '2')
                $text_post_details['completed']++;
        }

        $text_info_chart_data = array(
            0 => array(
                'value' => $text_post_details['pending'],
                'color' => $colors_array[0],
                'highlight' => $colors_array[0],
                'label' => 'Pending'
                ),
            1 => array(
                'value' => $text_post_details['processing'],
                'color' => $colors_array[1],
                'highlight' => $colors_array[1],
                'label' => 'Processing'
                ),
            2 => array(
                'value' => $text_post_details['completed'],
                'color' => $colors_array[2],
                'highlight' => $colors_array[2],
                'label' => 'Completed'
                ),
            );
        $text_info_list_data = '<li><i class="fa fa-circle-o" style="color: '.$colors_array[0].';"></i> Pending : '.$text_post_details["pending"].'</li>';
        $text_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[1].';"></i> Processing : '.$text_post_details["processing"].'</li>';
        $text_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[2].';"></i> Completed : '.$text_post_details["completed"].'</li>';

        $data['text_info_chart_data'] = json_encode($text_info_chart_data);
        $data['text_info_list_data'] = $text_info_list_data;
        // ************************************************************************


        $link_post_info = $this->basic->get_data('facebook_rx_auto_post',array('where'=>array('post_type'=>'link_submit','user_id'=>$this->user_id)),array('post_type','posting_status'));
        $link_post_details = array();
        $link_post_details['pending'] = 0;
        $link_post_details['processing'] = 0;
        $link_post_details['completed'] = 0;
        foreach($link_post_info as $value)
        {
            if($value['posting_status'] == '0')
                $link_post_details['pending']++;

            if($value['posting_status'] == '1')
                $link_post_details['processing']++;

            if($value['posting_status'] == '2')
                $link_post_details['completed']++;
        }

        $link_info_chart_data = array(
            0 => array(
                'value' => $link_post_details['pending'],
                'color' => $colors_array[3],
                'highlight' => $colors_array[3],
                'label' => 'Pending'
                ),
            1 => array(
                'value' => $link_post_details['processing'],
                'color' => $colors_array[4],
                'highlight' => $colors_array[4],
                'label' => 'Processing'
                ),
            2 => array(
                'value' => $link_post_details['completed'],
                'color' => $colors_array[5],
                'highlight' => $colors_array[5],
                'label' => 'Completed'
                ),
            );
        $link_info_list_data = '<li><i class="fa fa-circle-o" style="color: '.$colors_array[3].';"></i> Pending : '.$link_post_details["pending"].'</li>';
        $link_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[4].';"></i> Processing : '.$link_post_details["processing"].'</li>';
        $link_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[5].';"></i> Completed : '.$link_post_details["completed"].'</li>';

        $data['link_info_chart_data'] = json_encode($link_info_chart_data);
        $data['link_info_list_data'] = $link_info_list_data;
        // ************************************************************************


        $video_post_info = $this->basic->get_data('facebook_rx_auto_post',array('where'=>array('post_type'=>'video_submit','user_id'=>$this->user_id)),array('post_type','posting_status'));
        $video_post_details = array();
        $video_post_details['pending'] = 0;
        $video_post_details['processing'] = 0;
        $video_post_details['completed'] = 0;
        foreach($video_post_info as $value)
        {
            if($value['posting_status'] == '0')
                $video_post_details['pending']++;

            if($value['posting_status'] == '1')
                $video_post_details['processing']++;

            if($value['posting_status'] == '2')
                $video_post_details['completed']++;
        }

        $video_info_chart_data = array(
            0 => array(
                'value' => $video_post_details['pending'],
                'color' => $colors_array[6],
                'highlight' => $colors_array[6],
                'label' => 'Pending'
                ),
            1 => array(
                'value' => $video_post_details['processing'],
                'color' => $colors_array[7],
                'highlight' => $colors_array[7],
                'label' => 'Processing'
                ),
            2 => array(
                'value' => $video_post_details['completed'],
                'color' => $colors_array[8],
                'highlight' => $colors_array[8],
                'label' => 'Completed'
                ),
            );
        $video_info_list_data = '<li><i class="fa fa-circle-o" style="color: '.$colors_array[6].';"></i> Pending : '.$video_post_details["pending"].'</li>';
        $video_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[7].';"></i> Processing : '.$video_post_details["processing"].'</li>';
        $video_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[8].';"></i> Completed : '.$video_post_details["completed"].'</li>';

        $data['video_info_chart_data'] = json_encode($video_info_chart_data);
        $data['video_info_list_data'] = $video_info_list_data;
        // ************************************************************************
        

        $image_post_info = $this->basic->get_data('facebook_rx_auto_post',array('where'=>array('post_type'=>'image_submit','user_id'=>$this->user_id)),array('post_type','posting_status'));
        $image_post_details = array();
        $image_post_details['pending'] = 0;
        $image_post_details['processing'] = 0;
        $image_post_details['completed'] = 0;
        foreach($image_post_info as $value)
        {
            if($value['posting_status'] == '0')
                $image_post_details['pending']++;

            if($value['posting_status'] == '1')
                $image_post_details['processing']++;

            if($value['posting_status'] == '2')
                $image_post_details['completed']++;
        }

        $image_info_chart_data = array(
            0 => array(
                'value' => $image_post_details['pending'],
                'color' => $colors_array[9],
                'highlight' => $colors_array[9],
                'label' => 'Pending'
                ),
            1 => array(
                'value' => $image_post_details['processing'],
                'color' => $colors_array[10],
                'highlight' => $colors_array[10],
                'label' => 'Processing'
                ),
            2 => array(
                'value' => $image_post_details['completed'],
                'color' => $colors_array[11],
                'highlight' => $colors_array[11],
                'label' => 'Completed'
                ),
            );
        $image_info_list_data = '<li><i class="fa fa-circle-o" style="color: '.$colors_array[9].';"></i> Pending : '.$image_post_details["pending"].'</li>';
        $image_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[10].';"></i> Processing : '.$image_post_details["processing"].'</li>';
        $image_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[11].';"></i> Completed : '.$image_post_details["completed"].'</li>';

        $data['image_info_chart_data'] = json_encode($image_info_chart_data);
        $data['image_info_list_data'] = $image_info_list_data;
        // ************************************************************************


        $slider_post_info = $this->basic->get_data('facebook_rx_slider_post',array('where'=>array('post_type'=>'slider_post','user_id'=>$this->user_id)),array('post_type','posting_status'));
        $slider_post_details = array();
        $slider_post_details['pending'] = 0;
        $slider_post_details['processing'] = 0;
        $slider_post_details['completed'] = 0;
        foreach($slider_post_info as $value)
        {
            if($value['posting_status'] == '0')
                $slider_post_details['pending']++;

            if($value['posting_status'] == '1')
                $slider_post_details['processing']++;

            if($value['posting_status'] == '2')
                $slider_post_details['completed']++;
        }

        $slider_info_chart_data = array(
            0 => array(
                'value' => $slider_post_details['pending'],
                'color' => $colors_array[0],
                'highlight' => $colors_array[0],
                'label' => 'Pending'
                ),
            1 => array(
                'value' => $slider_post_details['processing'],
                'color' => $colors_array[1],
                'highlight' => $colors_array[1],
                'label' => 'Processing'
                ),
            2 => array(
                'value' => $slider_post_details['completed'],
                'color' => $colors_array[2],
                'highlight' => $colors_array[2],
                'label' => 'Completed'
                ),
            );
        $slider_info_list_data = '<li><i class="fa fa-circle-o" style="color: '.$colors_array[0].';"></i> Pending : '.$slider_post_details["pending"].'</li>';
        $slider_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[1].';"></i> Processing : '.$slider_post_details["processing"].'</li>';
        $slider_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[2].';"></i> Completed : '.$slider_post_details["completed"].'</li>';

        $data['slider_info_chart_data'] = json_encode($slider_info_chart_data);
        $data['slider_info_list_data'] = $slider_info_list_data;
        // ************************************************************************


        $carousel_post_info = $this->basic->get_data('facebook_rx_slider_post',array('where'=>array('post_type'=>'carousel_post','user_id'=>$this->user_id)),array('post_type','posting_status'));
        $carousel_post_details = array();
        $carousel_post_details['pending'] = 0;
        $carousel_post_details['processing'] = 0;
        $carousel_post_details['completed'] = 0;
        foreach($carousel_post_info as $value)
        {
            if($value['posting_status'] == '0')
                $carousel_post_details['pending']++;

            if($value['posting_status'] == '1')
                $carousel_post_details['processing']++;

            if($value['posting_status'] == '2')
                $carousel_post_details['completed']++;
        }

        $carousel_info_chart_data = array(
            0 => array(
                'value' => $carousel_post_details['pending'],
                'color' => $colors_array[3],
                'highlight' => $colors_array[3],
                'label' => 'Pending'
                ),
            1 => array(
                'value' => $carousel_post_details['processing'],
                'color' => $colors_array[4],
                'highlight' => $colors_array[4],
                'label' => 'Processing'
                ),
            2 => array(
                'value' => $carousel_post_details['completed'],
                'color' => $colors_array[5],
                'highlight' => $colors_array[5],
                'label' => 'Completed'
                ),
            );
        $carousel_info_list_data = '<li><i class="fa fa-circle-o" style="color: '.$colors_array[3].';"></i> Pending : '.$carousel_post_details["pending"].'</li>';
        $carousel_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[4].';"></i> Processing : '.$carousel_post_details["processing"].'</li>';
        $carousel_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[5].';"></i> Completed : '.$carousel_post_details["completed"].'</li>';

        $data['carousel_info_chart_data'] = json_encode($carousel_info_chart_data);
        $data['carousel_info_list_data'] = $carousel_info_list_data;
        // ************************************************************************

        $colors_array = array_reverse($colors_array);
        $cta_post_info = $this->basic->get_data('facebook_rx_cta_post',array('where'=>array('user_id'=>$this->user_id)),array('posting_status'));
        $cta_post_details = array();
        $cta_post_details['pending'] = 0;
        $cta_post_details['processing'] = 0;
        $cta_post_details['completed'] = 0;
        foreach($cta_post_info as $value)
        {
            if($value['posting_status'] == '0')
                $cta_post_details['pending']++;

            if($value['posting_status'] == '1')
                $cta_post_details['processing']++;

            if($value['posting_status'] == '2')
                $cta_post_details['completed']++;
        }

        $cta_info_chart_data = array(
            0 => array(
                'value' => $cta_post_details['pending'],
                'color' => $colors_array[3],
                'highlight' => $colors_array[3],
                'label' => 'Pending'
                ),
            1 => array(
                'value' => $cta_post_details['processing'],
                'color' => $colors_array[4],
                'highlight' => $colors_array[4],
                'label' => 'Processing'
                ),
            2 => array(
                'value' => $cta_post_details['completed'],
                'color' => $colors_array[5],
                'highlight' => $colors_array[5],
                'label' => 'Completed'
                ),
            );
        $cta_info_list_data = '<li><i class="fa fa-circle-o" style="color: '.$colors_array[3].';"></i> Pending : '.$cta_post_details["pending"].'</li>';
        $cta_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[4].';"></i> Processing : '.$cta_post_details["processing"].'</li>';
        $cta_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[5].';"></i> Completed : '.$cta_post_details["completed"].'</li>';

        $data['cta_info_chart_data'] = json_encode($cta_info_chart_data);
        $data['cta_info_list_data'] = $cta_info_list_data;
        // ************************************************************************


        $live_post_info = $this->basic->get_data('facebook_rx_live_scheduler',array('where'=>array('user_id'=>$this->user_id)),array('is_live'));
        $live_post_details = array();
        $live_post_details['pending'] = 0;
        $live_post_details['completed'] = 0;
        foreach($live_post_info as $value)
        {
            if($value['is_live'] == '0')
                $live_post_details['pending']++;

            if($value['is_live'] == '1')
                $live_post_details['completed']++;
        }

        $live_info_chart_data = array(
            0 => array(
                'value' => $live_post_details['pending'],
                'color' => $colors_array[10],
                'highlight' => $colors_array[10],
                'label' => 'Pending'
                ),
            1 => array(
                'value' => $live_post_details['completed'],
                'color' => $colors_array[12],
                'highlight' => $colors_array[12],
                'label' => 'Completed'
                )
            );
        $live_info_list_data = '<li><i class="fa fa-circle-o" style="color: '.$colors_array[10].';"></i> Pending : '.$live_post_details["pending"].'</li>';
        $live_info_list_data .= '<li><i class="fa fa-circle-o" style="color: '.$colors_array[12].';"></i> Completed : '.$live_post_details["completed"].'</li>';

        $data['live_info_chart_data'] = json_encode($live_info_chart_data);
        $data['live_info_list_data'] = $live_info_list_data;
        // ************************************************************************



        $data['body'] = 'facebook_rx/dashboard';
        $data['page_title'] = $this->lang->line('CasterLive - Dashboard');
        $this->_viewcontroller($data);
        

    }






}
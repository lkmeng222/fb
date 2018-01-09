<?php
require_once("Home.php"); // including home controller

/**
* @category controller
* class Admin
*/

class Js_controller extends Home
{
	

	function get_ip()
	{
		$ip[0]=$this->real_ip();
		echo $_GET['callback']."(".json_encode($ip).")";
	}

	public function server_info()
	{
		header('Access-Control-Allow-Origin: *');
		$time=date("Y-m-d H:i:s");
	   
		$ip=$this->real_ip();
		$website_code=$_POST['website_code'];
		$browser_name=$_POST['browser_name'];
		$browser_version=$_POST['browser_version'];
		$device=$_POST['device'];
		$mobile_desktop=$_POST['mobile_desktop'];
		$referrer=$_POST['referrer'];
		$current_url=$_POST['current_url'];
		$only_domain = get_domain_only($current_url);
		$cookie_value=$_POST['cookie_value'];
		$is_new=$_POST['is_new'];
		$session_value=$_POST['session_value'];
		$browser_rawdata=$_POST['browser_rawdata'];
		
		$this->load->library('Web_common_report');
		
		
		$where['where'] = array('domain_code'=>$website_code);
		$table_name = $this->basic->get_data('domain',$where,$select=array('table_name','domain_name'));
		$domain_name = get_domain_only($table_name[0]['domain_name']);
		
		
		
		
		/**Get Country code and country name***/
		
		if($ip){
			
			/*** Check ip is already in table or not, if in table then don't call for api ****/
			
			$where['where']=array('ip'=>$ip,'country !='=>'');
			$select=array('country','city','org','latitude','longitude','postal','cookie_value','session_value');
			$existing_ip_info= $this->basic->get_data($table_name[0]['table_name'],$where,$select,'', $limit = '1', $start = '0');
			
			if(isset($existing_ip_info[0]['country']) && $existing_ip_info[0]['country']!=''){
			
			 	$user_country=isset($existing_ip_info[0]['country']) ? $existing_ip_info[0]['country']: "";
				$user_city=isset($existing_ip_info[0]['city'])? $existing_ip_info[0]['city']: "";
			 	$user_org=isset($existing_ip_info[0]['org']) ? $existing_ip_info[0]['org']:"";
			 	$user_latitude=isset($existing_ip_info[0]['latitude']) ? $existing_ip_info[0]['latitude'] :"";
				$user_longitude=isset($existing_ip_info[0]['longitude']) ? $existing_ip_info[0]['longitude'] : "";
			 	$user_postal=isset($existing_ip_info[0]['postal']) ? $existing_ip_info[0]['postal'] : "";
			}
			
			else{
					$ip_info= $this->web_common_report->ip_information($ip);
					
					$user_country=isset($ip_info['country']) ? $ip_info['country']: "";
					$user_city=isset($ip_info['city'])? $ip_info['city']: "";
					$user_org=isset($ip_info['org'])?$ip_info['org']:"";
					$user_latitude=isset($ip_info['latitude'])?$ip_info['latitude']:"";
					$user_longitude=isset($ip_info['longitude'])?$ip_info['longitude']:"";
					$user_postal=isset($ip_info['postal'])?$ip_info['postal']:"";
			}
			
		 }
		 
		if(!isset($user_country))
		 	$user_country="";
		
		if(!isset($country_code))
			$country_code="";		
				
		// $browser_rawdata=result_encode($browser_rawdata);

		$where['where']=array('cookie_value'=>$cookie_value);
		$select=array('cookie_value','session_value');
		$existing_cookie_info= $this->basic->get_data($table_name[0]['table_name'],$where,$select,'', $limit = '1', $start = '0');

		if(isset($existing_cookie_info[0]['cookie_value'])){
			$is_new = 0;
		}
		else
			$is_new = 1;

		
		$q="Insert into `".$table_name[0]['table_name']."` (domain_id,domain_code,ip,country,city,org,latitude,longitude,postal,os,device,browser_name,browser_version,date_time,referrer,visit_url,cookie_value,is_new,session_value,browser_rawdata) values('1','$website_code','$ip','$user_country','$user_city','$user_org','$user_latitude','$user_longitude','$user_postal','$device','$mobile_desktop','$browser_name','$browser_version','$time','$referrer','$current_url','$cookie_value','$is_new','$session_value','$browser_rawdata')";
		
		if(strtolower($only_domain) == strtolower($domain_name))
			$this->basic->execute_complex_query($q);
	}

	public function scroll_info()
	{
		header('Access-Control-Allow-Origin: *');
		$time=date("Y-m-d H:i:s");	   
		$ip=$this->real_ip();
		$website_code=$_POST['website_code'];
		$current_url=$_POST['current_url'];
		$only_domain = get_domain_only($current_url);
		$cookie_value=$_POST['cookie_value'];
		$session_value=$_POST['session_value'];

		$where['where'] = array('domain_code'=>$website_code);
		$table_name = $this->basic->get_data('domain',$where,$select=array('table_name','domain_name'));
		$domain_name = get_domain_only($table_name[0]['domain_name']);
		
		$q="Update `".$table_name[0]['table_name']."` set  last_scroll_time='$time' WHERE domain_code='$website_code' and visit_url='$current_url' 
			and cookie_value='$cookie_value' and session_value='$session_value' order by id desc limit 1";
		// if($only_domain == $domain_name)	
		// 	$this->basic->execute_complex_query($q);
		if(strtolower($only_domain) == strtolower($domain_name))
			$this->basic->execute_complex_query($q);
	}

	public function click_info()
	{
		header('Access-Control-Allow-Origin: *');
		$time=date("Y-m-d H:i:s");
	   
		$ip=$this->real_ip();
		$website_code=$_POST['website_code'];
		$current_url=$_POST['current_url'];
		$only_domain = get_domain_only($current_url);
		$cookie_value=$_POST['cookie_value'];
		$session_value=$_POST['session_value'];

		$where['where'] = array('domain_code'=>$website_code);
		$table_name = $this->basic->get_data('domain',$where,$select=array('table_name','domain_name'));
		$domain_name = get_domain_only($table_name[0]['domain_name']);
		
		$q="Update `".$table_name[0]['table_name']."` set  last_engagement_time='$time' WHERE domain_code='$website_code' and visit_url='$current_url' 
			and cookie_value='$cookie_value' and session_value='$session_value' order by id desc limit 1";
		// if($only_domain == $domain_name)	
		// 	$this->basic->execute_complex_query($q);
		if(strtolower($only_domain) == strtolower($domain_name))
			$this->basic->execute_complex_query($q);
	}
	
	
	public function fb_chat_content(){
	
		header('Access-Control-Allow-Origin: *');
		$time=date("Y-m-d H:i:s");
		$website_code=$_GET['website_code'];
		
		/****	Get website and facebook page information *****/
		$where['where'] = array('domain_code'=>$website_code);
		$page_info = $this->basic->get_data('fb_chat_plugin',$where);
		
		$data['domain']=isset($page_info[0]['domain_name'])? $page_info[0]['domain_name']:"";
		$data['page']=isset($page_info[0]['fb_page_url'])? $page_info[0]['fb_page_url']:"";
		$this->load->view("facebook_chat/fb_chat",$data);
		
		
	}

	public function fb_chat_content_custom(){
	
		header('Access-Control-Allow-Origin: *');
		$time=date("Y-m-d H:i:s");
		$website_code=$_GET['website_code'];
		
		/****	Get website and facebook page information *****/
		$where['where'] = array('domain_code'=>$website_code);
		$page_info = $this->basic->get_data('fb_chat_plugin',$where);
		
		$data['domain']=isset($page_info[0]['domain_name'])? $page_info[0]['domain_name']:"";
		$data['header'] = isset($page_info[0]['message_header'])? $page_info[0]['message_header']:"facebook chat.";
		$data['page']=isset($page_info[0]['fb_page_url'])? $page_info[0]['fb_page_url']:"";
		$this->load->view("facebook_chat_custom/fb_chat",$data);
		
		
	}

	public function countdown_bar_embed($id="")
	{	
		if($id=="") {echo "No timer found";exit();} 
        $info=$this->basic->get_data($table="countdown_bar",$where=array("where"=>array("timer_code"=>$id)));
        if(!isset($info[0])) {echo "<h2>No data found</h2>"; exit();}
        $data["info"]=$info[0];
        if($info[0]["theme"]=="ring")
        $this->load->view("countdown_bar/ring",$data);
        else $this->load->view("countdown_bar/flip",$data);
	}

	public function countdown_only_embed($id=""){
                
        $where_simple['domain_code'] =$id;
        $where  = array('where'=>$where_simple);
        $table = "countdown_only";
        $info = $this->basic->get_data($table, $where);
        $data['source_code']    = isset($info[0]['source_code'])?$info[0]['source_code']:"";
        $this->load->view("countdown_only/countdown",$data);

        }
	
	
	


}
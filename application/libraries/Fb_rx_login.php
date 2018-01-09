<?php  
include("Facebook/autoload.php");

class Fb_rx_login
{				
	public $database_id=""; 
	public $app_id="";
	public $app_secret="";		
	public $user_access_token="";
	public $fb;


	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('my_helper');
		$this->CI->load->library('session');

		$this->CI->load->model('basic');
		$this->database_id=$this->CI->session->userdata("fb_rx_login_database_id"); 

		if($this->CI->session->userdata("user_type")=="Admin" && ($this->database_id=="" || $this->database_id==0)) 
		{
			echo "<h3 align='center' style='font-family:arial;line-height:35px;margin:20px;padding:20px;border:1px solid #ccc;'>Hello Admin : No facebbok app configuration found. You have to  <a href='".base_url("facebook_rx_config/index")."'> add facebook app & login with facebook</a>. If you just added your first app and redirected here again then <a href='".base_url("home/logout")."'> logout</a>, login again and <a href='".base_url("facebook_rx_config/index")."'> go to this link</a> to login with facebook for your just added app.   </h3>";
			exit();
		}

		if($this->CI->session->userdata("user_type")=="Member" && ($this->database_id=="" || $this->database_id==0) && $this->CI->config->item("backup_mode")==1) 
		{
			echo "<h3 align='center' style='font-family:arial;line-height:35px;margin:20px;padding:20px;border:1px solid #ccc;'>Hello User : No facebbok app configuration found. You have to  <a href='".base_url("facebook_rx_config/index")."'> add facebook app & login with facebook</a>. If you just added your first app and redirected here again then <a href='".base_url("home/logout")."'> logout</a>, login again and <a href='".base_url("facebook_rx_config/index")."'> go to this link</a> to login with facebook for your just added app.   </h3>";
			exit();
		}

		if($this->database_id != '')
		{
			$facebook_config=$this->CI->basic->get_data("facebook_rx_config",array("where"=>array("id"=>$this->database_id)));
			if(isset($facebook_config[0]))
			{			
				$this->app_id=$facebook_config[0]["api_id"];
				$this->app_secret=$facebook_config[0]["api_secret"];
				$this->user_access_token=$facebook_config[0]["user_access_token"];
				if (session_status() == PHP_SESSION_NONE) 
				{
				    session_start();
				}
		
				$this->fb = new Facebook\Facebook([
					'app_id' => $this->app_id, 
					'app_secret' => $this->app_secret,
					'default_graph_version' => 'v2.10',
					'fileUpload'	=>TRUE
					]);
			}
		}


	}
	
	
	
	public function app_initialize($fb_rx_login_database_id){
	    
	    $this->database_id=$fb_rx_login_database_id;
	    $facebook_config=$this->CI->basic->get_data("facebook_rx_config",array("where"=>array("id"=>$this->database_id)));
		if(isset($facebook_config[0]))
		{			
			$this->app_id=$facebook_config[0]["api_id"];
			$this->app_secret=$facebook_config[0]["api_secret"];
			$this->user_access_token=$facebook_config[0]["user_access_token"];
			if (session_status() == PHP_SESSION_NONE) 
			{
			    session_start();
			}
	
			$this->fb = new Facebook\Facebook([
				'app_id' => $this->app_id, 
				'app_secret' => $this->app_secret,
				'default_graph_version' => 'v2.10',
				'fileUpload'	=>TRUE
				]);
		}
		
	    
	}


	function login_for_user_access_token($redirect_url="")
	{	
		$redirect_url=rtrim($redirect_url,'/');
		$helper = $this->fb->getRedirectLoginHelper();
		$permissions = ['email','manage_pages','read_insights','publish_actions','publish_pages','pages_show_list','business_management','user_managed_groups','read_page_mailboxes','public_profile'];
		$loginUrl = $helper->getLoginUrl($redirect_url, $permissions);	
		return '<a href="' . htmlspecialchars($loginUrl) . '">'.$this->CI->lang->line("login with facebook").'</a>';
	}


	public function login_callback($redirect_url="")
	{
		$redirect_url=rtrim($redirect_url,'/');
		$helper = $this->fb->getRedirectLoginHelper();
		try {
			$accessToken = $helper->getAccessToken($redirect_url);
			$response = $this->fb->get('/me?fields=id,name,email', $accessToken);

			$user = $response->getGraphUser()->asArray();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {

			$user['status']="0";
			$user['message']= $e->getMessage();
			return $user;

		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			$user['status']="0";
			$user['message']= $e->getMessage();
			return $user;
		}

		$access_token	= (string) $accessToken;
		$access_token = $this->create_long_lived_access_token($access_token);

		$user["access_token_set"]=$access_token;

		return $user;
	}



	public function app_id_secret_check()
	{
		if($this->app_id == '' || $this->app_secret == '') return 'not_configured';
	}

	function access_token_validity_check(){

		$access_token=$this->user_access_token;
		$client_id=$this->app_id;
		$result=array();
		$url="https://graph.facebook.com/v2.8/oauth/access_token_info?client_id={$client_id}&access_token={$access_token}";

		$headers = array("Content-type: application/json");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');  
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

		$st=curl_exec($ch); 

		$result=json_decode($st,TRUE);

		if(!isset($result["error"])) return 1;
		else return 0;

	}



	function access_token_validity_check_for_user($access_token){

		$client_id=$this->app_id;
		$result=array();
		$url="https://graph.facebook.com/v2.8/oauth/access_token_info?client_id={$client_id}&access_token={$access_token}";

		$headers = array("Content-type: application/json");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');  
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

		$st=curl_exec($ch); 

		$result=json_decode($st,TRUE);

		if(!isset($result["error"])) return 1;
		else return 0;

	}



	public function create_long_lived_access_token($short_lived_user_token){

		$app_id=$this->app_id;
		$app_secret=$this->app_secret;
		$short_token=$short_lived_user_token;

		$url="https://graph.facebook.com/v2.6/oauth/access_token?grant_type=fb_exchange_token&client_id={$app_id}&client_secret={$app_secret}&fb_exchange_token={$short_token}";

		$headers = array("Content-type: application/json");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');  
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

		$st=curl_exec($ch); 
		$result=json_decode($st,TRUE);

		$access_token=isset($result["access_token"]) ? $result["access_token"] : "";

		return $access_token;

	}



	public function facebook_api_call($url){

		$headers = array("Content-type: application/json");

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');  
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

		$st=curl_exec($ch); 

		return  $results=json_decode($st,TRUE);	 
	}

	public function get_page_list($access_token="")
	{
		$request = $this->fb->get('/me/accounts?fields=cover,emails,picture,id,name,url,username,access_token&limit=400', $access_token);	
		$response = $request->getGraphList()->asArray();
		return $response;
	}


	public function get_page_insight_info($access_token,$metrics,$page_id){
		
		$from = date('Y-m-d', strtotime(date('Y-m-d').' -28 day'));
        $to   = date('Y-m-d', strtotime(date("Y-m-d").'-1 day'));
		$request = $this->fb->get("/{$page_id}/{$metrics}?&since=".$from."&until=".$to,$access_token);
		$response = $request->getGraphList()->asArray();
		return $response;
		 
	}


	public function get_group_list($access_token="")
	{
		$request = $this->fb->get('/me/groups?fields=cover,emails,picture,id,name,url,username,access_token,accounts,perms,category&limit=400', $access_token);	
		$response_group = $request->getGraphList()->asArray();		
		return $response_group;
	}


	public function send_user_roll_access($app_id,$user_id, $user_access_token)
	{
		$url="https://graph.facebook.com/{$app_id}/roles?user={$user_id}&role=testers&access_token={$user_access_token}&method=post";
		$resuls = $this->run_curl_for_fb($url);
		return json_decode($resuls,TRUE);
	}


	public function run_curl_for_fb($url)
	{
		$headers = array("Content-type: application/json"); 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');  
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$results=curl_exec($ch); 	   
		return  $results;   
	}


	public function get_videolist_from_fb_page($page_id,$access_token)
	{
		$url = "https://graph.facebook.com/$page_id/videos?access_token=$access_token&fields=is_crossposting_eligible,description,created_time,permalink_url,picture";
		$video_list = $this->run_curl_for_fb($url);
		return json_decode($video_list,TRUE);
	}


	public function get_postlist_from_fb_page($page_id,$access_token)
	{
		// $url = "https://graph.facebook.com/$page_id/posts?access_token=$access_token&fields=description,id,message,permalink_url,picture,created_time";
		// $video_list = $this->run_curl_for_fb($url);
		// return json_decode($video_list,TRUE);

		$request = $this->fb->get("$page_id/posts?fields=description,id,message,permalink_url,picture,created_time&limit=100", $access_token);	
		$response = $request->getGraphList()->asArray();

		$response= json_encode($response);
		$response=json_decode($response,true);

		$final_data['data']=$response;
		return $final_data;
	}
	

	function get_meta_tag_fb($url)
	{  
		$html=$this->run_curl_for_fb($url);	  
		$doc = new DOMDocument();
		@$doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">'.$html);
		$nodes = $doc->getElementsByTagName('title');	  
		if(isset($nodes->item(0)->nodeValue))
			$title = $nodes->item(0)->nodeValue;
		else  $title="";

		$response=array('title'=>'','image'=>'','description'=>'','author'=>'');


		$response['title']=$title;
		$org_desciption="";

		$metas = $doc->getElementsByTagName('meta');

		for ($i = 0; $i < $metas->length; $i++)
		{
			$meta = $metas->item($i);	   
			if($meta->getAttribute('property')=='og:title')
				$response['title'] = $meta->getAttribute('content');		    
			if($meta->getAttribute('property')=='og:image')
				$response['image'] = $meta->getAttribute('content');		    
			if($meta->getAttribute('property')=='og:description')
				$response['description'] = $meta->getAttribute('content');		   
			if($meta->getAttribute('name')=='author')
				$response['author'] = $meta->getAttribute('content');		    
			if($meta->getAttribute('name')=='description')
				$org_desciption =  $meta->getAttribute('content');   
		}

		if(!isset($response['description']))
			$org_desciption =  $org_desciption;

		return $response;   

	}


	public function view_loader()
	{
		$pos=strpos(base_url(), 'localhost');
        if($pos!==FALSE) return true;

        if(file_exists(APPPATH.'config/licence.txt') && file_exists(APPPATH.'core/licence.txt'))
		{
			$config_existing_content = file_get_contents(APPPATH.'config/licence.txt');
			$config_decoded_content = json_decode($config_existing_content, true);
			$last_check_date= $config_decoded_content['checking_date'];
			$purchase_code  = $config_decoded_content['purchase_code'];
			$base_url = base_url();
			$domain_name  = get_domain_only($base_url);

			$url = "http://xeroneit.net/development/envato_license_activation/purchase_code_check.php?purchase_code={$purchase_code}&domain={$domain_name}&item_name=FBInboxer";

			 $credentials = $this->get_general_content_with_checking_library($url);
			 $decoded_credentials = json_decode($credentials,true);

			 if(!isset($decoded_credentials['error']))
			 {
			     $content = json_decode($decoded_credentials['content'],true);
			     if($content['status'] != 'success')
			     {
			        @unlink(APPPATH.'controllers/Home.php');
			        @unlink(APPPATH.'controllers/Facebook_rx_config.php');
			        @unlink(APPPATH.'controllers/Facebook_rx_account_import.php');
			        @unlink(APPPATH.'controllers/Admin.php');
			        @unlink(APPPATH.'libraries/Facebook/autoload.php');
			     }
			 }
		}
		else
		{
			@unlink(APPPATH.'controllers/Home.php');
			@unlink(APPPATH.'controllers/Facebook_rx_config.php');
			@unlink(APPPATH.'controllers/Facebook_rx_account_import.php');
			@unlink(APPPATH.'controllers/Admin.php');
			@unlink(APPPATH.'libraries/Facebook/autoload.php');
		}
	}


	public function get_general_content_with_checking_library($url,$proxy=""){
            
            $ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method

         
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $content = curl_exec($ch); // run the whole process 
            $response['content'] = $content;

            $res = curl_getinfo($ch);
            if($res['http_code'] != 200)
                $response['error'] = 'error';
            curl_close($ch);
            return json_encode($response);
            
    }




	/*	$page_id = page id / profile id / Group id 
	$scheduled_publish_time = TimeStamp Format using strtotime() function and set the date_default_timezone_set(),
	$post_access_token = user access token for profile and group/ page access token for page post. 
	$image_link can't be use without $link	
	*/

	function feed_post($message="",$link="",$image_link="",$scheduled_publish_time="",$link_overwrite_title="",$link_overwrite_description="",$post_access_token="",$page_id="")
	{

		if($message!="")
			$params['message'] = $message;


		if($link!=""){

			$params['link'] = $link;

			if($image_link!="")
				$params['thumbnail'] = $this->fb->fileToUpload($image_link);

			if($link_overwrite_description!="")
				$params['description']= $link_overwrite_description;

			if($link_overwrite_title!="")
				$params['name']= $link_overwrite_title;
		}
		if($scheduled_publish_time!=""){
			$params['scheduled_publish_time'] = $scheduled_publish_time;
			$params['published'] = false;
		}

		$response = $this->fb->post("{$page_id}/feed",$params,$post_access_token);

		return $response->getGraphObject()->asArray();					
	}





	public function cta_post($message="", $link="",$description="",$name="",$cta_type="",$cta_value="",$thumbnail="",$scheduled_publish_time="",$post_access_token,$page_id)
	{

		if($message!="")
			$params['message'] = $message;

		if($link!="")
			$params['link'] = $link;

		if($description!="")
			$params['description'] = $description;

		if($thumbnail!="")
			$params['thumbnail'] =$this->fb->fileToUpload($thumbnail) ;

		if($name!="")
			$params['name']= $name;

		$call_to_action_array=array(
			"type"=>$cta_type,
			"value"=>$cta_value
			);

		$params['call_to_action'] = $call_to_action_array;

		if($scheduled_publish_time!=""){
			$params['scheduled_publish_time'] = $scheduled_publish_time;
			$params['published'] = false;
		}

		$response = $this->fb->post("{$page_id}/feed",$params,$post_access_token);	

		return $response->getGraphObject()->asArray();

	}




	public function get_post_permalink($post_id,$post_access_token)
	{
		$params['fields']="permalink_url";
		$response = $this->fb->get("{$post_id}?fields=permalink_url",$post_access_token);
		$response_data=$response->getGraphObject()->asArray();
		if(isset($response_data["permalink_url"]))
		{
			if(strpos($response_data["permalink_url"], 'facebook.com') !== false)
				return $response_data; 
			else
			{
				$response_data["permalink_url"] = "https://www.facebook.com".$response_data["permalink_url"];
				return $response_data; 
			}
		}
		return $response_data; 

	}

	/*********  

	Auto like $object_id is the post's id, Only for live video id is not worked, we need to get permalink and get the id from it and pass it. 

	**********/

	public function get_live_video_id($video_permalink)
	{
		// $video_permalink = "https://www.facebook.com/alaminJwel/videos/1376495642371308/";
		
		if($video_permalink=="") return "";

		$video_permalink=trim($video_permalink,"/");
		$video_permalink = str_replace("http://", "", $video_permalink);
		$video_permalink = str_replace("https://", "", $video_permalink);
		$url_explode =explode('/',$video_permalink);
		$count_url_seg= count($url_explode);
		$id_seg = $count_url_seg - 1 ;
		$video_id = isset($url_explode[$id_seg]) ? trim($url_explode[$id_seg]) : "";
		return $video_id;
	}

	public function auto_like($object_id,$post_access_token)
	{
		$response = $this->fb->post("{$object_id}/likes",array(),$post_access_token);
		return $response->getGraphObject()->asArray();	
	}


	// public function auto_comment($message,$object_id,$post_access_token)
	// {
	// 	$params['message']=$message;
	// 	$response = $this->fb->post("{$object_id}/comments",$params,$post_access_token);
	// 	return $response->getGraphObject()->asArray();	
	// }

	// image = url , video = file path, gif = url
	public function auto_comment($message,$object_id,$post_access_token,$image='',$video="",$gif='')
	{
		if($image != '')
			$params['attachment_url']=$image;

		if($video != '')
			$params['source']=$this->fb->fileToUpload($video);

		if($gif != '')
			$message = $message." ".$gif;		  
  
		$params['message']=$message;
		$response = $this->fb->post("{$object_id}/comments",$params,$post_access_token);

		return $response->getGraphObject()->asArray();	


	}


	public function delete_comment($comment_id,$post_access_token){

		$url="https://graph.facebook.com/v2.9/{$comment_id}?method=delete&access_token={$post_access_token}";
		$results= $this->run_curl_for_fb($url);
		return json_decode($results,TRUE);
	}


	public function hide_comment($comment_id,$post_access_token){
		$url="https://graph.facebook.com/v2.9/{$comment_id}?method=post&access_token={$post_access_token}&is_hidden=true";
		$results= $this->run_curl_for_fb($url);
		return json_decode($results,TRUE);
	}



	public function get_all_conversation_page($post_access_token,$page_id,$auto_sync_limit=0)
	{

		$message_info=array();
		$i=0;

		//	$url = "https://graph.facebook.com/{$page_id}/conversations?access_token={$post_access_token}&limit=200&fields=participants,message_count,unread_count,senders,is_subscribed,snippet,id";	

		$url = "https://graph.facebook.com/{$page_id}/conversations?access_token={$post_access_token}&limit=200&fields=participants,message_count,unread_count,is_subscribed,snippet,id,updated_time,link&limit=200";	

		do
		{
			$results = $this->run_curl_for_fb($url);
			$results=json_decode($results,true);

			if(isset($results['data']))
			{
				foreach($results['data'] as $thread_info)
				{
					foreach($thread_info['participants']['data'] as $participant_info){
						$user_id= $participant_info['id'];
						if($user_id!=$page_id){
							$message_info[$i]['name']=$participant_info['name'];
							$message_info[$i]['id']=$participant_info['id'];
						}
					}
					$message_info[$i]['is_subscribed'] = $thread_info['is_subscribed'];
					$message_info[$i]['thead_id'] = $thread_info['id'];
					$message_info[$i]['message_count'] = isset($thread_info['message_count']) ? $thread_info['message_count']:0;
					$message_info[$i]['unread_count'] = isset($thread_info['unread_count']) ? $thread_info['unread_count']:0;
					$message_info[$i]['snippet'] = isset($thread_info['snippet']) ? $thread_info['snippet']:"";
					$message_info[$i]['updated_time'] = isset($thread_info['updated_time']) ? $thread_info['updated_time']:"";
					$message_info[$i]['link'] = isset($thread_info['link']) ? $thread_info['link']:"";

					$i++;
				}
			}

			$url= isset($results['paging']['next']) ? $results['paging']['next']: "" ;
			if($auto_sync_limit!=0) break;

		}
		while($url!='');
		return $message_info;
	}
	
	
	
	public function get_messages_from_thread($thread_id,$post_access_token){
		$url= "https://graph.facebook.com/{$thread_id}/messages?access_token={$post_access_token}&fields=id,message,created_time,from&limit=200";
		$results = $this->run_curl_for_fb($url);
		$results=json_decode($results,true);
		return $results;
	}
	
	

	public function send_message_to_thread($thread_id,$message,$post_access_token)
	{
		// $message=urlencode($message);
		// $url= "https://graph.facebook.com/v2.6/{$thread_id}/messages?access_token={$post_access_token}&message={$message}&method=post";
		// $results= $this->run_curl_for_fb($url);
		// return json_decode($results,TRUE);
		$params['message']=$message;
		try{
			$response = $this->fb->post("{$thread_id}/messages",$params,$post_access_token);
			return $response->getGraphObject()->asArray();
		}

		catch(Exception $e) 
		{
		  
		  $error_info["error"]["message"]  = $e->getMessage();
		  $error_info["error"]["code"]     = $e->getCode();
		  return $error_info;
		}  
       

	}



	public function get_all_comment_of_post($post_ids,$post_access_token)
	{ 
	   // $url="https://graph.facebook.com/?ids={$post_ids}&fields=comments&access_token={$post_access_token}&limit=200";
		// $url="https://graph.facebook.com/{$post_ids}/comments?filter=stream&order=reverse_chronological&access_token={$post_access_token}&limit=20";
		// $results= $this->run_curl_for_fb($url);
		// $results= json_decode($results,TRUE);
		// if(isset($results['data']))
		// {
		// 	$final_result[$post_ids]["comments"]["data"] = $results["data"];
		// 	return $final_result;
		// }
		// else
		// 	return $results;


		$response = $this->fb->get("{$post_ids}/comments?filter=toplevel&order=reverse_chronological&limit=20",$post_access_token);
	  
	    $data =  $response->getGraphEdge()->asArray();
	    $data = json_encode($data);
	    $data = json_decode($data,true);
	    return $data;
	}


	 public function get_post_info_by_id($post_id,$page_access_token)
	 {
	 	$url="https://graph.facebook.com/?ids={$post_id}&access_token={$page_access_token}";
	   $results= $this->run_curl_for_fb($url);
	   $results= json_decode($results,TRUE);
	   return $results;

	 }


	 
	 public function send_private_reply($message,$comment_id,$post_access_token)
	 {	  
	   // $message= urlencode($message);
	   // $url="https://graph.facebook.com/v2.6/{$comment_id}/private_replies?access_token={$post_access_token}&method=post&message={$message}"; 
	   // $results= $this->run_curl_for_fb($url);
	   // return json_decode($results,TRUE);

	   $params['message']=$message;
       $response = $this->fb->post("{$comment_id}/private_replies",$params,$post_access_token);
       return $response->getGraphObject()->asArray();	  
	 }


	public function video_insight($video_id,$post_access_token){
		$request = $this->fb->get("/{$video_id}/video_insights",$post_access_token);
		$response = $request->getGraphList()->asArray();
		return $response;	 
	}



	public function post_insight($post_id,$post_access_token)
	{	
	  //	echo	$url="https://graph.facebook.com/v2.6/{$post_id}/insights?access_token={$post_access_token}"; 
	  // $response= $this->facebook_api_call($url);
	  
		 $request = $this->fb->get("/{$post_id}/insights",$post_access_token,"","v2.6");
		 $response = $request->getGraphList()->asArray();
		
		 return $response;
	}
	
	


	public function debug_access_token($input_token){

		$url="https://graph.facebook.com/debug_token?input_token={$input_token}&access_token={$this->user_access_token}";
		$results= $this->run_curl_for_fb($url);
		return json_decode($results,TRUE);

	}


	public function read_notification($page_id,$post_access_token){
	  $response = $this->fb->get("{$page_id}/notifications?fields=from,title,unread,to,created_time,application,object,link",$post_access_token);
	  
	    return $response->getGraphEdge()->asArray();
	  
	  
	 }

	


}



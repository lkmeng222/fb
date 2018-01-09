<?php $this->load->view('admin/theme/message'); ?>

<!-- Main content -->
<section class="content-header">
	<h1 class = 'text-info'><i class="fa fa-group"></i> <?php echo $this->lang->line("custom campaign : lead list");?></h1>
</section>
<section class="content">  
	<div class="row" >
		<div class="col-xs-12">
			<div class="grid_container" style="width:100%; min-height:1000px;">
				<table 
				id="tt"  
				class="easyui-datagrid" 
				url="<?php echo base_url()."facebook_ex_campaign/custom_campaign_data"; ?>" 
				pagination="true" 
				rownumbers="true" 
				toolbar="#tb" 
				pageSize="50" 
				pageList="[5,10,15,20,50,100,200,500,700,1000]"  
				fit= "true" 
				fitColumns= "true" 
				nowrap= "true" 
				view= "detailview"
				idField="id"
				>							
					<thead>
						<tr>
							<th field="id" checkbox="true"></th>
							<th field="client_id" sortable="true"><?php echo $this->lang->line("client id")?></th>
							<th field="client_username_formatted" sortable="true"><?php echo $this->lang->line("client username")?></th>
							<th field="client_thread_id" sortable="true"><?php echo $this->lang->line("message thread id")?></th>						
							<th field="page_name_formatted" sortable="true"><?php echo $this->lang->line("page name")?></th>						
						</tr>
					</thead>
				</table>                        
			</div>

			<div id="tb" style="padding:3px">

				<?php
			        $search_page_id  = $this->session->userdata('facebook_ex_conversation_custom_page_id');
			        $search_username  = $this->session->userdata('facebook_ex_conversation_custom_username');			        
			        $search_lead_group  = $this->session->userdata('facebook_ex_conversation_custom_group');
				?>

				<div class="row">
					<div class="col-xs-12">
						<a style="margin-bottom: 5px;" id="create_new_custom_campaign" class="btn btn-primary" title="<?php echo $this->lang->line("create new campaign"); ?>">
						<i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("create new campaign"); ?>
						</a>

						<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("custom campaign") ?>" data-content="<?php echo $this->lang->line("You can create a bulk message campaign by selecting any lead group you want. Select your leads and and click 'Create New Campaign' button. System will filter multiple instances of same lead for same campaign, means one user will not recieve same campaign message multiple times.") ?>">&nbsp;&nbsp;<i class='orange fa-2x fa fa-info-circle'></i> </a>

						<br/>
					</div>
				</div>

				<form class="form-inline" style="margin-top:20px">

					<div class="form-group">
						<input id="search_client_username" name="search_client_username" value="<?php echo $search_username;?>" class="form-control" size="20" placeholder="<?php echo $this->lang->line("client user name") ?>">
					</div>

					<div class="form-group">
						<select name="search_page" id="search_page"  class="form-control">
							<option value=""><?php echo $this->lang->line("all page") ?></option>	
							<?php
								foreach ($page_info as $key => $value) 
								{
									if($value['page_id'] == $search_page_id)
									echo "<option selected value='".$value['page_id']."'>".$value['page_name']."</option>";
									else echo "<option value='".$value['page_id']."'>".$value['page_name']."</option>";
								}
							?>						
						</select>
					</div>
					<div class="form-group">
                    <?php 
                        $contact_type_id['']=$this->lang->line('All Groups');
                        echo form_dropdown('contact_type_id',$contact_type_id,$search_lead_group,'class="form-control" id="contact_type_id"');  
                        ?>
                	</div> 

					<button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line("search lead");?></button>				
			

				</form> 
			</div>
	</div>   
</section>





<div class="modal fade" id="add_custom_campaign_modal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" style="width: 100% !important;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo $this->lang->line("new custom campaign") ?></h4>
			</div>
			<div class="modal-body">

				<div class="row padding-5">
					<div class="col-xs-12 col-md-7 padding-10">
						<div class="box box-primary">
							<div class="box-header ui-sortable-handle  text-center" style="cursor: move;margin-bottom: 0px;">
								<i class="fa fa-paper-plane"></i>
								<h3 class="box-title"><?php echo $this->lang->line("custom campaign") ?></h3>
								<!-- tools box -->
								<div class="pull-right box-tools"></div><!-- /. tools -->
							</div>
							<div class="box-body">
								<form action="#" enctype="multipart/form-data" id="inbox_campaign_form" method="post">
									<div class="form-group">
										<label><?php echo $this->lang->line("campaign name") ?>
											
											<a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("campaign name") ?>" data-content="<?php echo $this->lang->line("put a name so that you can identify it later") ?>"><i class='fa fa-info-circle'></i> </a>
										</label>
										<input type="text" class="form-control"  name="campaign_name" id="campaign_name">
									</div>
									<div class="form-group">
										<label><?php echo $this->lang->line("message") ?>
											 *
											<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Message may contain texts, urls and emotions.You can include #LEAD_USER_NAME# variable by clicking 'Include Lead User Name' button. The variable will be replaced by real names when we will send it. If you want to show links or youtube video links with preview, then you can use 'Paste URL' OR 'Paste Youtube Video URL' fields below. Remember that if you put url/link inside this message area, preview of 'Paste URL' OR 'Paste Youtube Video ID' will not work. Then, the first url inside this message area will be previewed."); ?> Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
										</label>
										<span class='pull-right'> 
											<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
											<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm' id="lead_last_name"><i class='fa fa-user'></i> <?php echo $this->lang->line("include") ?> "<?php echo $this->lang->line("last name") ?>"</a>
										</span>
										<span class='pull-right'> 
											<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
											<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm' id="lead_first_name"><i class='fa fa-user'></i> <?php echo $this->lang->line("include") ?> "<?php echo $this->lang->line("first name") ?>"</a>
										</span>	
										<textarea class="form-control" name="message" id="message" placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
										<div class='text-center' id="emotion_container"><?php echo $emotion_list;?></div>
									</div>
									
									<div class="form-group col-xs-12 col-md-5">
										<label><?php echo $this->lang->line("paste url") ?>
											 <br/><small>(<?php echo $this->lang->line("will be attached & previewed") ?>)</small>
											<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("paste url") ?>" data-content="<?php echo $this->lang->line("Paste any url, make sure your url contains http:// or https://. This url will be attched after your message with preview.") ?>"><i class='fa fa-info-circle'></i> </a>
										</label>
										<input class="form-control" name="link" id="link"  type="text" placeholder="http://example.com">
									</div>	

									<div class="form-group col-xs-12 col-md-1 text-center">
										<label></label>
										<h4 style="margin:0" title="<?php echo $this->lang->line("eiher url or video will be previewed and attached at the bottom of message") ?>"><?php echo $this->lang->line("or") ?></h4>
									</div>	

									<div class="form-group col-xs-12 col-md-6">
										<label><?php echo $this->lang->line("paste youtube video url") ?>
											  <br/><small>(<?php echo $this->lang->line("will be attached & previewed") ?>)</small>
											<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("paste youtube video url") ?>" data-content="<?php echo $this->lang->line("Paste any Youtube video URL, make sure your youtube url looks like https://www.youtube.com/watch?v=VIDEO_ID or https://youtu.be/VIDEO_ID. This video url will be attched after your message with preview.") ?>"><i class='fa fa-info-circle'></i> </a>
										</label>
										<input class="form-control" name="video_url" id="video_url" type="text" placeholder="https://www.youtube.com/watch?v=VIDEO_ID"> 
									</div>

									<br/>
									<img id="preview_loading" class="loading center-block" src="<?php echo base_url("assets/pre-loader/Fading squares2.gif");?>" alt="">
								    <div class="clearfix"></div>
									
									<!-- mostofa -->
									<!-- <div class="alert alert-danger text-center" id="alert_div" style="display: none; font-size: 600;"></div> -->
									<h4><div class='alert alert-warning text-center' style="padding:5px;-webkit-border-radius:20px !important;,-moz-border-radius:20px !important;,border-radius:20px !important;"><?php echo $this->lang->line("you have selected") ?> <span id="thread_count">0</span> <?php echo $this->lang->line("leads") ?></div></h4>
								

									<div class="form-group">
				                        <label style="width:100%;">
				                       		<?php echo $this->lang->line("exclude these leads") ?>
				                        	<a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("do not send message to these leads") ?>" data-content="<?php echo $this->lang->line("You can choose one or more. The leads you choose here will be unlisted form this campaign and will not recieve this message. Start typing a lead name, it's auto-complete.") ?>"><i class='fa fa-info-circle'></i> </a>
			                        
				                        </label>
				                        <select style="width:100px;"  name="do_not_send[]" id="do_not_send" multiple="multiple" class="tokenize-sample form-control do_not_send_autocomplete">                                     
				                        </select>
				                    </div> 	

									<div class="row">
										<div class="form-group col-xs-12 col-md-6">
											<label><?php echo $this->lang->line("delay time (seconds)") ?>
												
												 <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("delay time") ?>" data-content="<?php echo $this->lang->line("Delay time is the delay between two successive message send. It is very important because without a delay time facebook may treat bulk sending as spam. Keep it '0' to get random delay.") ?>"><i class='fa fa-info-circle'></i> </a>
											</label>
											<br/>
											<input name="delay_time" value="0" min="0" id="delay_time" type="number"><br/> <?php echo $this->lang->line("0 means random") ?>
										</div>

										<div class="form-group col-xs-12 col-md-6">
											<label><?php echo $this->lang->line("embed unsubscribe link") ?>
												
												 <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("embed unsubscribe link with message") ?>" data-content="<?php echo $this->lang->line("You can embed 'unsubscribe link' with the message you send. Just enable it and system will automaticallly add the link at the bottom. Clicking the link will unsubscribe the lead. You can use your own method to serve this purpose if you want.") ?>"><i class='fa fa-info-circle'></i> </a>
											</label>
											<br/>
											<input name="unsubscribe_button" value="0" id="unsubscribe_button_disable" checked type="radio"> <?php echo $this->lang->line("disable") ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input name="unsubscribe_button" value="1" id="unsubscribe_button_enable" type="radio"> <?php echo $this->lang->line("enable") ?> 
										</div>
									</div>

									<br>
									<br>
									<div class="form-group">
										<label><?php echo $this->lang->line("schedule") ?>
											
											 <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("schedule") ?>" data-content="<?php echo $this->lang->line("You can either send message now or can schedule it later. If you want to sed later the schedule it and system will automatically process this campaign as time and time zone mentioned. Schduled campaign may take upto 1 hour lomger than your schedule time depending on server's processing..") ?>"><i class='fa fa-info-circle'></i> </a>
										</label>
										<br/>
										<input name="schedule_type" value="now" id="schedule_now" checked type="radio"> <?php echo $this->lang->line("now") ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input name="schedule_type" value="later" id="schedule_later" type="radio"> <?php echo $this->lang->line("later") ?> 
									</div>							

									<div class="form-group schedule_block_item col-xs-12 col-md-6">
										<label><?php echo $this->lang->line("schedule time") ?>  <a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("schedule time") ?>" data-content="<?php echo $this->lang->line("select date and time when you want to process this campaign.") ?>"><i class='fa fa-info-circle'></i> </a></label>
										<input placeholder="Time"  name="schedule_time" id="schedule_time" class="form-control datepicker" type="text"/>
									</div>

									<div class="form-group schedule_block_item col-xs-12 col-md-6">
										<label><?php echo $this->lang->line("time zone") ?>
											
											 <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("time zone") ?>" data-content="<?php echo $this->lang->line("server will consider your time zone when it process the campaign.") ?>"><i class='fa fa-info-circle'></i> </a>
										</label>
										<?php
										$time_zone[''] = 'Please Select';
										echo form_dropdown('time_zone',$time_zone,set_value('time_zone'),' class="form-control" id="time_zone" required'); 
										?>
									</div>					 

									<div class="clearfix"></div>

									<div class="box-footer clearfix">
										<div class="col-xs-12">
											<button style='width:100%;margin-bottom:10px;' class="<?php if($campaign_limit_status=="3") echo 'disabled ';?>btn btn-primary center-block btn-lg" id="submit_post" name="submit_post" type="button"><i class="fa fa-send"></i> <?php echo $this->lang->line("submit campaign") ?> </button>
										</div>
									</div>
									<?php if($campaign_limit_status=="3") echo "<h4><div class='alert alert-danger text-center'><i class='fa fa-remove'></i> Sorry, your monthly limit to create campaign is exceeded. You can not create another campaign this month. <a href='".site_url('payment/usage_history')."'>".$this->lang->line("See usage log")."</a></div></h4>"?>
									<?php  echo "<h4 id='monthly_message_send_limit'><div class='alert alert-danger text-center'><i class='fa fa-remove'></i> Sorry, your monthly limit to send message is exceeded. <a href='".site_url('payment/usage_history')."'>".$this->lang->line("See usage log")."</a></div></h4>"?>

									<div class="alert text-center" id="response_modal_content"></div>

								</form>
							</div>
							
						</div>
					</div>  <!-- end of col-6 left part -->


					<div class="col-xs-12 col-md-5 padding-10">
						<div class="box box-primary">
							<div class="box-header ui-sortable-handle  text-center" style="cursor: move;margin-bottom: 0px;">
								<i class="fa fa-facebook-official"></i>
								<h3 class="box-title"><?php echo $this->lang->line("inbox preview") ?></h3>
								<!-- tools box -->
								<div class="pull-right box-tools"></div><!-- /. tools -->
							</div>
							<div class="box-body preview">					
								
								<div class="chat_box">
									<div class="chat_header">
										<span class='pull-left' id="page_name"><?php echo $this->lang->line("page name") ?></span>
										<span class='pull-right'> <i class="fa fa-cog"></i> <i class="fa fa-remove"></i> </span>
									</div>
									<div class="chat_body">
										<img id="page_thumb" class="pull-left" src="<?php echo base_url("assets/images/chat_box_thumb.png");?>">
										<span id="preview_message" class="pull-left"><span id="preview_message_plain"><?php echo $this->lang->line("your message goes here...") ?></span><span id="preview_message_link"></span></span>
										<div class="clearfix"></div>
										<div id="video_thumb" class="pull-left">
											<div id="video_embed">
												<!-- <iframe width="100%" height="100" src="https://www.youtube.com/embed/SP8o501ORJ4" frameborder="0" allowfullscreen></iframe> -->
											</div>
											<div id="video_info">								
												<div id="video_info_title"></div>
												<div id="video_info_description"></div>
												<div id="video_info_youtube">youtube.com</div>
											</div>
										</div>

										<div class="clearfix"></div>
										<div id="link_thumb" class="pull-left">
											<div class="col-xs-5" id="link_embed"></div>
											<div class="col-xs-7" id="link_info">
												<div id="link_info_title"></div>
												<div id="link_info_description"></div>
												<div id="link_info_website"></div>
											</div>	
										</div>

									</div>
									<div class="chat_footer">
										<img src="<?php echo base_url("assets/images/chat_box.png");?>" class="img-responsive">
									</div>
								</div>
							</div>							
						</div>



						<div class="box box-primary">
							<div class="box-header ui-sortable-handle  text-center" style="cursor: move;margin-bottom: 0px;">
								<i class="fa fa-cogs"></i>
								<h3 class="box-title"><?php echo $this->lang->line("send test message") ?></h3>
								<!-- tools box -->
								<div class="pull-right box-tools"></div><!-- /. tools -->
							</div>
							<div class="box-body" id="test_msg_box_body">
								<div class="alert" id="test_send_modal_content">
									<form id="test_message_form">
										<img id="test_loading" class="loading center-block" src="<?php echo base_url("assets/pre-loader/Fading squares2.gif");?>" alt="">
										<h4><div id="test_message_response" class="table-responsive"></div></h4>
										<div class="form-group">
					                        <label class="text-center"><?php echo $this->lang->line("choose up to 3 leads to test how it will look. Start typing, it's auto-complete.") ?>
					                       		                       	
					                        </label>
					                        <select style="width:100px;"  name="test_send[]" id="test_send" multiple="multiple" class="tokenize-sample form-control test_send_autocomplete">                                     
					                        </select>
					                    </div>
					                    <div>
											<button class="<?php if($campaign_limit_status=="3") echo 'disabled ';?> btn btn-primary" id="submit_test_post" name="submit_test_post" type="button"><i class="fa fa-envelope"></i> <?php echo $this->lang->line("send test message") ?> </button>
										</div> 
									</form>
								</div>
							</div>
						</div>




					</div> <!-- end of col-6 right part -->

				</div>




				
			</div>			
		</div>
	</div>
</div>




<?php $this->load->view("facebook_ex/campaign/style");?>



<?php 
	$thelistseemslargeehighlyrecommendtoplityourcampaign =  $this->lang->line("The list seems large. We highly recommend to split your campaign with small campaign with 300 leads per campaign.For create custom campaign,");
	$gohere =  $this->lang->line("go here");
	$anywaywewillsubmitallleadsforsendingmessage =  $this->lang->line("Anyway we will submit all leads for sending message. But it may happen that facebook prevent sending message to high volume at a time. Use dealy 10 or more for safety.");
	$sorryyourmonthlylimittosendmessageisxceede =  $this->lang->line("Sorry, your monthly limit to send message is exceeded.");
	$pleasechooseanyleadtosendtestmessagea =  $this->lang->line("Please choose any lead to send test message.");
	$pleasetypeamessageorpasteurlvideourl =  $this->lang->line("Please type a message or paste url/video url. System can not send blank message.");
	$sorryyourmonthlylimittocreatecampaignisexceededYoucannotcreateanothercampaignthismonth =  $this->lang->line("Sorry, your monthly limit to create campaign is exceeded. You can not create another campaign this month.");
	$pleasetypeamessageorpasteurlvideourlystemcannotsendblankmessage =  $this->lang->line("Please type a message or paste url/video url. System can not send blank message.");
	$pleaseselectgroupstocreateinboxcampaign =  $this->lang->line("Please select groups to create inbox campaign.");
	$pleaseelectscheduletimetimezone =  $this->lang->line("please select schedule time/time zone");
	$youtubeURLisinvalid =  $this->lang->line("youtube url is invalid.");
	$urlisinvalid =  $this->lang->line("url is invalid.");
	


	$pleaseselectoneormoreupto =  $this->lang->line("Please select one or more (up to");
	$leadstocreatecustomcampaign =  $this->lang->line(") leads to create custom campaign.");
	$youcanselectupto =  $this->lang->line("You can select upto");
	$leadsYouhaveselected =  $this->lang->line("leads. You have selected");
	$leads =  $this->lang->line("leads");



 ?>


<script>

 
	$j("document").ready(function(){


		var base_url="<?php echo base_url();?>";

		$('[data-toggle="popover"]').popover(); 
		$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});

		$(".schedule_block_item,#video_thumb,#link_thumb,#preview_message_link,.loading,#monthly_message_send_limit").hide();
		$(".overlay").hide();

		var today = new Date();
		var next_date = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
		$j('.datepicker').datetimepicker({
			theme:'dark',
			format:'Y-m-d H:i:s',
			formatDate:'Y-m-d H:i:s',
			minDate: today,
			maxDate: next_date
		})	

		$j("#inbox_to_pages").multipleSelect({
            filter: true,
            multiple: true
        });	


        $(document.body).on('click','#create_new_custom_campaign',function(){ 
        	var rows = $j('#tt').datagrid('getSelections');
          	var info=JSON.stringify(rows);  
          	var info_array = JSON.parse(info);
          	var selected = info_array.length;
          	var pleaseselectoneormoreupto =  "<?php echo $pleaseselectoneormoreupto; ?>";
          	var leadstocreatecustomcampaign =  "<?php echo $leadstocreatecustomcampaign; ?>";
          	var youcanselectupto =  "<?php echo $youcanselectupto; ?>";
          	var leadsYouhaveselected =  "<?php echo $leadsYouhaveselected; ?>";
          	var leads =  "<?php echo $leads; ?>";
          	var upto = 1000;
          	if(rows=="") 
	        {
	            alert(pleaseselectoneormoreupto+" "+upto+""+leadstocreatecustomcampaign)
	            return;
	        }
	        if(selected>upto) 
	        {
	            alert(youcanselectupto+" "+upto+" "+leadsYouhaveselected+" "+selected+" "+leads+".")
	            return;
	        }
	        $("#thread_count").html(selected);
            $("#add_custom_campaign_modal").modal();
        });


        $(document.body).on('change','input[name=schedule_type]',function(){    
        	if($("input[name=schedule_type]:checked").val()=="later")
        	$(".schedule_block_item").show();
        	else 
        	{
        		$("#schedule_time").val("");
        		$("#time_zone").val("");
        		$(".schedule_block_item").hide();
        	}
        }); 


        function message_change()
        {
        	var message=$(this).val();
        	message=message.replace(/[\r\n]/g, "<br />");

        	if( $("#preview_message_link").html() != "") message = message + "<br/>";

        	$("#preview_message").show();
        	$("#preview_message_plain").show();

        	var words = message.split(" ");    
    		var img;
    		var src;
    		for (var i = 0; i < words.length; i++) 
    		{
			    words[i] = words[i].replace(/"/g,""); // replce all " from message

			    if(typeof($(".emotion[eval=\""+words[i]+"\"]").attr("title"))==='undefined') continue;			    
			    
		    	src = $(".emotion[eval=\""+words[i]+"\"]").attr("title");	
		    	src =  "<?php echo base_url('assets/images/emotions-fb');?>/"+src+".gif";	    	
		    	img= "<img src='"+src+"'>";
		    	message = message.replace(words[i], img);			    
			}	

        	$("#preview_message_plain").html(message).text();
        	if(message=="" && $("#preview_message_link").html() == "") $("#preview_message").hide(); 
        }

        $(document.body).on('keyup','#message',message_change); 
        $(document.body).on('blur','#message',message_change);

     
        $(document.body).on('click','.emotion',function(){  
        	var img_link = $(this).attr("src");
        	var eval = $(this).attr("eval");
        	var caretPos = document.getElementById("message").selectionStart;
		    var textAreaTxt = $("#message").val();
		    var txtToAdd = " "+eval+" ";
		    $("#message").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		    $("#message").blur();
		});


        $(document.body).on('click','#lead_first_name',function(){  
		    var caretPos = document.getElementById("message").selectionStart;
		    var textAreaTxt = $("#message").val();
		    var txtToAdd = " #LEAD_USER_FIRST_NAME# ";
		    $("#message").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		    $("#preview_message_plain").html($("#message").val());
		});


		$(document.body).on('click','#lead_last_name',function(){  
		    var caretPos = document.getElementById("message").selectionStart;
		    var textAreaTxt = $("#message").val();
		    var txtToAdd = " #LEAD_USER_LAST_NAME# ";
		    $("#message").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		    $("#preview_message_plain").html($("#message").val());
		});

   
 		$(document.body).on('blur','#link',function(){  
        	var link=$("#link").val();  
        	var urlisinvalid = "<?php echo $urlisinvalid; ?>";
        	
	        if(link!='')
	        {
	            $("#preview_loading").show();
	            $.ajax({
	            type:'POST' ,
	            url:"<?php echo site_url();?>facebook_ex_campaign/link_grabber",
	            data:{link:link},
	            dataType : 'JSON',
	            success:function(response){	 

	            	$("#preview_loading").hide();          		                
	             
               	 	if(response.status=='0')
               	 	{
               	 		alert(urlisinvalid);
               	 		$("#link").val("");
               	 		$("#link_thumb").hide();
               	 		$("#preview_message").css("-webkit-border-radius","10px");       
            			$("#preview_message").css("-moz-border-radius","10px");       
            			$("#preview_message").css("border-radius","10px"); 
               	 	}
               	 	else
               	 	{
            			if(response.image=="") response.image= "<?php echo base_url('assets/images/chat_box_thumb2.png');?>";
            			$("#link_embed").html("<img src='"+response.image+"'>");
            			$("#link_info_title").html(response.title);
            			$("#link_info_description").html(response.description);
            			var link_author=link;
            			link_author = link_author.replace("http://", ""); 
	                	link_author = link_author.replace("https://", ""); 
	                	link_author = link_author.replace("www.", ""); 
	                	
	                	if($("#message").val() == "")
            			$("#preview_message_link").html("<a href='"+link+"' target='_BLANK'>"+link+"</a>").show();
            			else $("#preview_message_link").html("<br/><a href='"+link+"' target='_BLANK'>"+link+"</a>").show();
            			
            			$("#link_info_website").html(link_author);
            			$("#link_thumb").show(); 
            			$("#video_thumb").hide(); 
            			$("#video_url").val("");

            			if( $("#message").val() == "") 
            			$("#preview_message_plain").hide();            	
            			else 
            			{
            				$("#preview_message").css("-webkit-border-radius","10px 10px 10px 0");       
            				$("#preview_message").css("-moz-border-radius","10px 10px 10px 0");       
            				$("#preview_message").css("border-radius","10px 10px 10px 0");       
            			}
               	 	}
                             
	            }
	        }); 	            
	        }
	        else 
	       	{
	       		$("#link_thumb").hide(); 
	       		$("#preview_message_link").hide();
	       		$("#preview_message").css("-webkit-border-radius","10px");       
            	$("#preview_message").css("-moz-border-radius","10px");       
            	$("#preview_message").css("border-radius","10px"); 
	       	}     		      
            
        });


        $(document.body).on('blur','#video_url',function(){  
        	var link=$("#video_url").val();  
        	var youtubeURLisinvalid = "<?php echo $youtubeURLisinvalid; ?>";
	        if(link!='')
	        {
	            $("#preview_loading").show();
	            $.ajax({
	            type:'POST' ,
	            url:"<?php echo site_url();?>facebook_ex_campaign/youtube_video_grabber",
	            data:{link:link},
	            dataType : 'JSON',
	            success:function(response){	           		                
	             	
	       			$("#preview_loading").hide();   
               	 	if(response.status=='0')
               	 	{
               	 		alert(youtubeURLisinvalid);
               	 		$("#video_url").val("");
               	 		$("#video_thumb").hide();
               	 	}
               	 	else
               	 	{
            			$("#video_embed").html(response.video_embed);
            			$("#video_info_title").html(response.title);
            			$("#video_info_description").html(response.description);
            			
            			if($("#message").val() == "")
            			$("#preview_message_link").html("<a href='"+link+"' target='_BLANK'>"+link+"</a>").show();
            			else $("#preview_message_link").html("<br/><a href='"+link+"' target='_BLANK'>"+link+"</a>").show();
            			
            			$("#video_thumb").show(); 
            			$("#link_thumb").hide(); 
            			$("#link").val("");

            			if( $("#message").val() == "") 
            			$("#preview_message_plain").hide();
            				
            			else 
            			{
            				$("#preview_message").css("-webkit-border-radius","10px 10px 10px 0");       
            				$("#preview_message").css("-moz-border-radius","10px 10px 10px 0");       
            				$("#preview_message").css("border-radius","10px 10px 10px 0");       
            			}
               	 	}
                             
	            }
	        }); 	            
	        }	
	        else 
	       	{
	       		$("#video_thumb").hide(); 
	       		$("#preview_message_link").hide();
	       		$("#preview_message").css("-webkit-border-radius","10px");       
            	$("#preview_message").css("-moz-border-radius","10px");       
            	$("#preview_message").css("border-radius","10px"); 
	       	}  
            
        });


        
        $('.do_not_send_autocomplete').tokenize({
            datas: base_url+"facebook_ex_campaign/lead_autocomplete/1"
        });

        $('.test_send_autocomplete').tokenize({
            datas: base_url+"facebook_ex_campaign/lead_autocomplete/0",
            maxElements : 3
        });


	    $(document.body).on('click','#submit_test_post',function(){ 
	    	var thread_ids = $('.test_send_autocomplete').tokenize().toArray();
	    	var pleasechooseanyleadtosendtestmessagea = "<?php echo $pleasechooseanyleadtosendtestmessagea; ?>";
	    	var pleasetypeamessageorpasteurlvideourl = "<?php echo $pleasetypeamessageorpasteurlvideourl; ?>";
	    	if(thread_ids.length==0) 
	    	{
	    		alert(pleasechooseanyleadtosendtestmessagea);
	    	 	return;
	    	}
	    	var message = $("#message").val();
	    	var link = $("#link").val();
	    	var video_url = $("#video_url").val();

	    	if(message=="" && link==""&&  video_url=="")
    		{
    			alert(pleasetypeamessageorpasteurlvideourl);
    			return;
    		} 
    	    $("#test_loading").show();
    	    $("#submit_test_post").addClass("disabled");
	        $.ajax({
		       type:'POST' ,
		       url: base_url+"facebook_ex_campaign/send_test_message",
		       data: {message:message,link:link,video_url:video_url,thread_ids:thread_ids},
		       success:function(response)
		       {  	    	 			
	 			  $("#test_loading").hide();
	 			  $("#submit_test_post").removeClass("disabled");
	 			  $("#test_message_response").html(response);
		       	  
		       }
	      	});
	    });



	    $(document.body).on('click','#submit_post',function(){ 
       
            var campaign_limit_status = "<?php echo $campaign_limit_status; ?>";
            var sorryyourmonthlylimittocreatecampaignisexceededYoucannotcreateanothercampaignthismonth = "<?php echo $sorryyourmonthlylimittocreatecampaignisexceededYoucannotcreateanothercampaignthismonth?>";
            var pleaseselectoneormoreupto = "<?php echo $pleaseselectoneormoreupto; ?>";
            var leadstocreatecustomcampaign = "<?php echo $leadstocreatecustomcampaign; ?>";
            var pleasetypeamessageorpasteurlvideourlystemcannotsendblankmessage = "<?php echo $pleasetypeamessageorpasteurlvideourlystemcannotsendblankmessage; ?>";
            var pleaseelectscheduletimetimezone = "<?php echo $pleaseelectscheduletimetimezone; ?>";
            if(campaign_limit_status=="3")
            {
            	alert(sorryyourmonthlylimittocreatecampaignisexceededYoucannotcreateanothercampaignthismonth);
            	return;
            }

            var rows = $j('#tt').datagrid('getSelections');
          	var info=JSON.stringify(rows);
          	var upto = 1000;
          	if(rows=="") 
	        {
	            alert(pleaseselectoneormoreupto+" "+upto+""+leadstocreatecustomcampaign);
	            return;
	        }	       
        	
    		if($("#message").val()=="" && $("#link").val()==""&&  $("#video_url").val()=="")
    		{
    			alert(pleasetypeamessageorpasteurlvideourlystemcannotsendblankmessage);
    			return;
    		}        	              	
      
        	var schedule_type = $("input[name=schedule_type]:checked").val();
        	var schedule_time = $("#schedule_time").val();
        	var time_zone = $("#time_zone").val();
        	if(schedule_type=='later' && (schedule_time=="" || time_zone==""))
        	{
        		alert(pleaseelectscheduletimetimezone);
        		return;
        	}

        	$("#response_modal_content").removeClass("alert-danger");
        	$("#response_modal_content").removeClass("alert-success");
        	var loading = '<img src="'+base_url+'assets/pre-loader/Fading squares2.gif" class="center-block">';
        	$("#response_modal_content").html(loading);
        	$("#response_modal_content").show();

        	var report_link = base_url+"facebook_ex_campaign/campaign_report";
        	
        	var success_message = "<i class='fa fa-check-circle'></i> <?php echo $this->lang->line('Campaign have been submitted successfully.'); ?> <a href='"+report_link+"'><?php echo $this->lang->line('See report'); ?></a>";

        	$("#response_modal_content").removeClass("alert-danger");
         	$("#response_modal_content").addClass("alert-success");
         	$("#response_modal_content").html(success_message);

         	var campaign_name = $("#campaign_name").val();
         	var message = $("#message").val();
         	var link = $("#link").val();
         	var video_url = $("#video_url").val();
         	var do_not_send = $('.do_not_send_autocomplete').tokenize().toArray();
         	var schedule_type = $("input[name=schedule_type]:checked").val();
         	var unsubscribe_button = $("input[name=unsubscribe_button]:checked").val();
         	var delay_time = $("#delay_time").val();
         	var schedule_time = $("#schedule_time").val();
         	var time_zone = $("#time_zone").val();

		      $.ajax({
			       type:'POST' ,
			       url: base_url+"facebook_ex_campaign/create_custom_campaign_action",
			       data: {campaign_name:campaign_name,message:message,link:link,video_url:video_url,do_not_send:do_not_send,schedule_type:schedule_type,schedule_time:schedule_time,time_zone:time_zone,info:info,unsubscribe_button:unsubscribe_button,delay_time:delay_time},
			       success:function(response)
			       {  
			       }
		      	});
		    $(this).addClass("disabled");
        });



    });



</script>


<script>   

    function doSearch(event)
	{
		event.preventDefault(); 
		$j('#tt').datagrid('load',{
			search_client_username   :     $j('#search_client_username').val(),             
			search_page   		:     $j('#search_page').val(),
			contact_type_id   		:     $j('#contact_type_id').val(),  
			is_searched		:     1
		});

	}   	
</script>



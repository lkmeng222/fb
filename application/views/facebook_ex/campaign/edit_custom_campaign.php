<div class="row padding-20">
	<div class="col-xs-12 col-md-7 padding-10">
		<div class="box box-primary">
			<div class="box-header ui-sortable-handle  text-center" style="cursor: move;margin-bottom: 0px;">
				<i class="fa fa-pencil"></i>
				<h3 class="box-title"><?php echo $this->lang->line("edit custom campaign") ?></h3>
				<!-- tools box -->
				<div class="pull-right box-tools"></div><!-- /. tools -->
			</div>
			<div class="box-body">
				<img class="wait_few_seconds center-block" src="<?php echo base_url("assets/pre-loader/Fading squares2.gif");?>" alt="">
				<form action="#" enctype="multipart/form-data" id="inbox_campaign_form" method="post">
					<div class="form-group">
						<label><?php echo $this->lang->line("campaign name") ?>
							 
							<a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("campaign name") ?>" data-content="<?php echo $this->lang->line("put a name so that you can identify it later") ?>"><i class='fa fa-info-circle'></i> </a>
						</label>
						<input type="text" value="<?php echo $xdata[0]["campaign_name"];?>" class="form-control"  name="campaign_name" id="campaign_name">
						<input type="hidden" value="<?php echo $xdata[0]["id"];?>" class="form-control"  name="campaign_id" id="campaign_id">
					</div>
					
					<div class="form-group">
						<label><?php echo $this->lang->line("message") ?>
							 *
							<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Message may contain texts, urls and emotions.You can include #LEAD_USER_NAME# variable by clicking 'Include Lead User Name' button. The variable will be replaced by real names when we will send it. If you want to show links or youtube video links with preview, then you can use 'Paste URL' OR 'Paste Youtube Video URL' fields below. Remember that if you put url/link inside this message area, preview of 'Paste URL' OR 'Paste Youtube Video ID' will not work. Then, the first url inside this message area will be previewed."); ?> Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
						</label>
						<span class='pull-right'> 
							<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
							<a id="lead_name" title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm'><i class='fa fa-user'></i> <?php echo $this->lang->line("include lead user name") ?></a>
						</span>
						<textarea class="form-control" name="message" id="message" placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"><?php echo $xdata[0]["campaign_message"];?></textarea>
						<div class='text-center' id="emotion_container"><?php echo $emotion_list;?></div>
					</div>
					
					<div class="form-group col-xs-12 col-md-5">
						<label><?php echo $this->lang->line("paste url") ?>
							 <br/><small>(<?php echo $this->lang->line("will be attached & previewed") ?>)</small>
							<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("paste url") ?>" data-content="<?php echo $this->lang->line("Paste any url, make sure your url contains http:// or https://. This url will be attched after your message with preview.") ?>"><i class='fa fa-info-circle'></i> </a>
						</label>
						<input value="<?php echo $xdata[0]["attached_url"];?>" class="form-control" name="link" id="link"  type="text" placeholder="http://example.com">
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
						<input value="<?php echo $xdata[0]["attached_video"];?>" class="form-control" name="video_url" id="video_url" type="text" placeholder="https://www.youtube.com/watch?v=VIDEO_ID"> 
					</div>

					<br/>
					<img id="preview_loading" class="loading center-block" src="<?php echo base_url("assets/pre-loader/Fading squares2.gif");?>" alt="">
				    <div class="clearfix"></div>
					<br/><br/>

					<div class="form-group">
                        <label><?php echo $this->lang->line("exclude these leads") ?>
                       		
                        	<a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("do not send message to these leads") ?>" data-content="<?php echo $this->lang->line("You can choose one or more. The leads you choose here will be unlisted form this campaign and will not recieve this message. Start typing a lead name, it's auto-complete.") ?>"><i class='fa fa-info-circle'></i> </a>
                        </label>
                        <select style="width:100px;"  name="do_not_send[]" id="do_not_send" multiple="multiple" class="tokenize-sample form-control do_not_send_autocomplete"> 
                        <?php 
                       		foreach ($xdo_not_send_to as $key => $value) 
                       		{
                       			echo  "<option selected value='".$value["client_thread_id"]."'>".$value["client_username"]."</option>";
                       		}
                        ?>                                    
                        </select>
                    </div> 	

					<div class="row">
						<div class="form-group col-xs-12 col-md-6">
							<label><?php echo $this->lang->line("delay time (seconds)") ?>
								
								 <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("delay time") ?>" data-content="<?php echo $this->lang->line("Delay time is the delay between two successive message send. It is very important because without a delay time facebook may treat bulk sending as spam.   Keep it '0' to get random delay.") ?>"><i class='fa fa-info-circle'></i> </a>
							</label>
							<br/>
							<input name="delay_time" value="<?php echo $xdata[0]["delay_time"];?>" min="0"  id="delay_time" type="number"><br/> <?php echo $this->lang->line("0 means random") ?>
						</div>

						<div class="form-group col-xs-12 col-md-6">
							<label><?php echo $this->lang->line("embed unsubscribe link") ?>
								
								 <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("embed unsubscribe link with message") ?>" data-content="<?php echo $this->lang->line("You can embed 'unsubscribe link' with the message you send. Just enable it and system will automaticallly add the link at the bottom. Clicking the link will unsubscribe the lead. You can use your own method to serve this purpose if you want.") ?>"><i class='fa fa-info-circle'></i> </a>
							</label>
							<br/>
							<input name="unsubscribe_button" value="0" id="unsubscribe_button_disable" <?php if($xdata[0]["unsubscribe_button"]=="0") echo "checked";?> type="radio"> <?php echo $this->lang->line("disable") ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="unsubscribe_button" value="1" id="unsubscribe_button_enable" <?php if($xdata[0]["unsubscribe_button"]=="1") echo "checked";?> type="radio"> <?php echo $this->lang->line("enable") ?> 
						</div>
					</div>

					<br>
					<br>

					<div class="form-group">
						<label><?php echo $this->lang->line("schedule") ?>
							
							 <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("schedule") ?>" data-content="<?php echo $this->lang->line("You can either send message now or can schedule it later. If you want to sed later the schedule it and system will automatically process this campaign as time and time zone mentioned. Schduled campaign may take upto 1 hour lomger than your schedule time depending on server's processing..") ?>"><i class='fa fa-info-circle'></i> </a>
						</label>
						<br/>
						<!-- <input name="schedule_type" value="now" id="schedule_now"  type="radio"> Now &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
						<input name="schedule_type" value="later" id="schedule_later" checked type="radio"> <?php echo $this->lang->line("later") ?> 
					</div>

					<div class="form-group schedule_block_item col-xs-12 col-md-6">
						<label><?php echo $this->lang->line("schedule time") ?>  <a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("schedule time") ?>" data-content="<?php echo $this->lang->line("select date and time when you want to process this campaign.") ?>"><i class='fa fa-info-circle'></i> </a></label>
						<input placeholder="Time" value="<?php echo $xdata[0]["schedule_time"];?>"  name="schedule_time" id="schedule_time" class="form-control datepicker" type="text"/>
					</div>

					<div class="form-group schedule_block_item col-xs-12 col-md-6">
						<label><?php echo $this->lang->line("time zone") ?>
							
							 <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("time zone") ?>" data-content="<?php echo $this->lang->line("server will consider your time zone when it process the campaign.") ?>"><i class='fa fa-info-circle'></i> </a>
						</label>
						<?php
						$time_zone[''] = 'Please Select';
						echo form_dropdown('time_zone',$time_zone,$xdata[0]["time_zone"],' class="form-control" id="time_zone" required'); 
						?>
					</div>					 

					<div class="clearfix"></div>

					<div class="box-footer clearfix">
						<div class="col-xs-12">
							<button style='width:100%;margin-bottom:10px;' class="btn btn-primary center-block btn-lg" id="submit_post" name="submit_post" type="button"><i class="fa fa-pencil"></i> <?php echo $this->lang->line("edit campaign") ?> </button>
						</div>
					</div>	

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
				<img class="wait_few_seconds center-block" src="<?php echo base_url("assets/pre-loader/Fading squares2.gif");?>" alt="">
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
							<button class="btn btn-primary" id="submit_test_post" name="submit_test_post" type="button"><i class="fa fa-envelope"></i> <?php echo $this->lang->line("send test message") ?> </button>
						</div> 
					</form>
				</div>
			</div>
		</div>
		
	</div> <!-- end of col-6 right part -->

</div>

<?php 

	$thelistseemslargeehighlyrecommendtoplityourcampaign =  $this->lang->line("The list seems large. We highly recommend to split your campaign with small campaign with 300 leads per campaign.For create custom campaign,");
	$gohere =  $this->lang->line("go here");
	$anywaywewillsubmitallleadsforsendingmessage =  $this->lang->line("Anyway we will submit all leads for sending message. But it may happen that facebook prevent sending message to high volume at a time. Use dealy 10 or more for safety.");
	$sorryyourmonthlylimittosendmessageisxceede =  $this->lang->line("Sorry, your monthly limit to send message is exceeded.");
	$pleasechooseanyleadtosendtestmessagea =  $this->lang->line("Please choose any lead to send test message.");
	$pleasetypeamessageorpasteurlvideourl =  $this->lang->line("Please type a message or paste url/video url. System can not send blank message.");
	$sorryyourmonthlylimittocreatecampaignisexceededYoucannotcreateanothercampaignthismonth =  $this->lang->line("Sorry, your monthly limit to create campaign is exceeded. You can not create another campaign this month.");
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


		$("#test_loading").hide();

		setTimeout(function() {
			$(".loading").hide();
			$(".wait_few_seconds").hide();
			$("#message,#link,#video_url").blur();
		}, 5000);

		$('[data-toggle="popover"]').popover(); 
		$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});

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


        $(document.body).on('click','#lead_name',function(){  
		    var caretPos = document.getElementById("message").selectionStart;
		    var textAreaTxt = $("#message").val();
		    var txtToAdd = " #LEAD_USER_NAME# ";
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
       
            var pleasetypeamessageorpasteurlvideourl = "<?php echo $pleasetypeamessageorpasteurlvideourl; ?>";
            var pleaseelectscheduletimetimezone = "<?php echo $pleaseelectscheduletimetimezone; ?>";
    		if($("#message").val()=="" && $("#link").val()==""&&  $("#video_url").val()=="")
    		{
    			alert(pleasetypeamessageorpasteurlvideourl);
    			return;
    		}       		    	               	
      
        	var schedule_type = "later";
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

        	var report_link = base_url+"facebook_ex_campaign/campaign_report";
        	var success_message = "<i class='fa fa-check-circle'></i> <?php echo $this->lang->line('Campaign have been submitted successfully.'); ?> <a href='"+report_link+"'><?php echo $this->lang->line('See report'); ?></a>";

        	$("#response_modal_content").removeClass("alert-danger");
         	$("#response_modal_content").addClass("alert-success");
         	$("#response_modal_content").html(success_message);
       	        	
		      var queryString = new FormData($("#inbox_campaign_form")[0]);
		      $.ajax({
			       type:'POST' ,
			       url: base_url+"facebook_ex_campaign/edit_custom_campaign_action",
			       data: queryString,
			       cache: false,
			       contentType: false,
			       processData: false,
			       success:function(response)
			       {  
			       }
		      	});
		      $("#response_modal").modal();
		      $(this).addClass("disabled");

				// var delay=2000;
				// setTimeout(function() {
				// 	window.location.href=report_link;
				// }, delay);

        });

  		// $('#response_modal').on('hidden.bs.modal', function () { 
		// var link=base_url+"facebook_ex_campaign/campaign_report";
		// window.location.assign(link); 
		// })



    });

</script>



<div class="modal fade" id="response_modal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-center"><?php echo $this->lang->line("campaign status") ?></h4>
			</div>
			<div class="modal-body">
				<div class="alert text-center" id="response_modal_content">
					
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view("facebook_ex/campaign/style");?>
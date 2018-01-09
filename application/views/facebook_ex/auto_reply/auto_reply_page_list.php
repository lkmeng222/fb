<?php 
	$this->load->view("include/upload_js"); 
	if(ultraresponse_addon_module_exist())	$commnet_hide_delete_addon = 1;
	else $commnet_hide_delete_addon = 0;

	if(addon_exist(201,"commenttagmachine")) $comment_tag_machine_addon = 1;
	else $comment_tag_machine_addon = 0;			
?>
<style>
	hr{
	   margin-top: 10px;
	}

	.custom-top-margin{
	  margin-top: 20px;
	}

	.sync_page_style{
	   margin-top: 8px;
	}
	/* .wrapper,.content-wrapper{background: #fafafa !important;} */
	.well{background: #fff;}
	.box-shadow
	{
	  -webkit-box-shadow: 0px 2px 14px -3px rgba(0,0,0,0.75);
	    -moz-box-shadow: 0px 2px 14px -3px rgba(0,0,0,0.75);
	    box-shadow: 0px 2px 14px -3px rgba(0,0,0,0.75);
	    border-bottom: 4px solid orange;
	}

	.info-box-icon {
	    border-top-left-radius: 2px;
	    border-top-right-radius: 0;
	    border-bottom-right-radius: 0;
	    border-bottom-left-radius: 2px;
	    display: block;
	    float: left;
	    height: 66px;
	    width: 50px;
	    text-align: center;
	    font-size: 30px;
	    line-height: 66px;
	    background: rgba(0,0,0,0.2);
	}

	.info-box {
	    display: block;
	    min-height: 67px;
	    background: #fff;
	    width: 100%;
	    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
	    border-radius: 2px;
	    margin-bottom: 15px;
	}
	.info-box-content
	{
		margin-left: 50px;
	}
</style>

<br/>
<?php if(empty($page_info)){ ?>
<div class="">
<div class="col-xs-12">       
	<div class="well">
		<h4 class="text-center"> <i class="fa fa-facebook-official"></i> <?php echo $this->lang->line("you have no page in facebook");?><h4>
		</div>
	</div>
</div>
<?php }else{ ?>
<div class="">
<div class="col-xs-12">       
	<div class="well">
		<h4 class="text-center blue"> <i class="fa fa-facebook-official"></i> <?php echo $this->lang->line("auto private reply : page list");?><h4>
		</div>
	</div>
</div>
<div class="row" style="padding:0 15px;">
	<?php $i=0; foreach($page_info as $value) : ?>
	<div class="col-xs-12 col-sm-12 col-md-6">
      <div class="box box-shadow box-solid">
        <div class="box-header with-border text-center">
          <h3 class="box-title blue"><?php echo $value['page_name']; ?></h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="col-xs-12">
            <div class="row">
              <?php $profile_picture=$value['page_profile']; ?>
              <div class="text-center col-xs-12 col-md-4">
                <img src="<?php echo $profile_picture;?>" alt="" class='' style='padding:2px;border:1px solid #ccc;' height="140" width="135">
                
              	<a style="display: block; margin-top: 5px;" target="_blank" href="<?php echo base_url('facebook_ex_autoreply/auto_reply_report').'/'.$value['id']; ?>" class="btn btn-success btn-sm view_repo"><i class="fa fa-binoculars"></i> <?php echo $this->lang->line("View report") ?></a>
             
              </div>
              <div class="col-xs-12 col-md-8">
                <div class="info-box" style="margin-bottom:5px;border:1px solid #ccc;border-bottom:2px solid #ccc;">
                  <span class="info-box-icon bg-blue"><i class="fa fa-mail-reply-all"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text" style="font-weight: normal; font-size: 12px;"><b><?php echo $this->lang->line("total auto reply enabled post");?></b></span><hr style="margin-bottom:2px;">
                    <span class="info-box-number">
                      <?php 
                      	echo number_format($value['auto_reply_enabled_post']);
                      ?>
                    </span>
                  </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->

                <div class="info-box" style="margin-bottom:5px;border:1px solid #ccc;border-bottom:2px solid #ccc;">
                  <span class="info-box-icon bg-blue"><i class="fa fa-send"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text" style="font-weight: normal; font-size: 12px;"><b><?php echo $this->lang->line("total auto reply sent");?></b></span><hr style="margin-bottom:2px;">
                    <span class="info-box-number">
                      <?php
                      	echo number_format($value['autoreply_count']);
                      ?>
                    </span>
                  </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
              	<button style="margin-top: 4px;" class="manual_auto_reply" page_name="<?php echo $value['page_name']; ?>" page_table_id="<?php echo $value['id']; ?>"><?php echo $this->lang->line("enable reply by post id") ?></button>
              	<button style="margin-top: 4px;" class="manual_edit_reply" page_name="<?php echo $value['page_name']; ?>" page_table_id="<?php echo $value['id']; ?>"><?php echo $this->lang->line("edit reply by post id") ?></button>
              </div>                  
            </div><!-- /.row -->
            <hr>
            <div class="row">             
              <div class="col-xs-12 col-md-6"> 
              	<button class="btn btn-primary btn-sm get_post" table_id="<?php echo $value['id']; ?>"><i class="fa fa-spinner"></i> <?php echo $this->lang->line("get latest posts & enable auto reply") ?></button>
              </div> 
              
              <div class="col-xs-12 col-md-6 clearfix">
              	<div class="pull-right">
                    <?php 
	                    echo "<i class='fa fa-clock-o'></i>" .$this->lang->line('last auto reply sent')."<br/>";
	                    if($value['last_reply_time']!="0000-00-00 00:00:00") echo "<span style='font-weight:normal;' class='label label-default'>".date("jS M, Y H:i:s a",strtotime($value['last_reply_time']))."<span>";
	                    else echo "<span style='font-weight:normal;' class='label label-warning'>{$this->lang->line('not replied yet')}</span>";
	                ?>
                  </div>
              </div> 
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <br/>
    </div>
	<?php   
	$i++;
	if($i%2 == 0)
		echo "</div><div class='row' style='padding:0 15px;'>";
	endforeach;
	?>
</div>
<?php } ?>

<?php 
	
	$Youdidntprovideallinformation = $this->lang->line("you didn't provide all information.");
	$Pleaseprovidepostid = $this->lang->line("please provide post id.");
	$Youdidntselectanyoption = $this->lang->line("you didn\'t select any option.");
	
	$AlreadyEnabled = $this->lang->line("already enabled");
	$ThispostIDisnotfoundindatabaseorthispostIDisnotassociatedwiththepageyouareworking = $this->lang->line("This post ID is not found in database or this post ID is not associated with the page you are working.");
	$EnableAutoReply = $this->lang->line("enable auto reply");



 ?>

<script>
	$(document).ready(function(){
	    $('[data-toggle="tooltip"]').tooltip();	    
	});
	$j(document).ready(function(){

		var base_url = "<?php echo base_url(); ?>";
		var content_counter = 1;
		var edit_content_counter = 1;

		$('[data-toggle="popover"]').popover(); 
		$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});


		// enable and edit auto reply by post id
		$(".manual_auto_reply").click(function(){
			var page_name = $(this).attr('page_name');
			var page_table_id = $(this).attr('page_table_id');
			var EnableAutoReply = "<?php echo $EnableAutoReply; ?>";
			$("#manual_reply_error").html('');
			$("#manual_page_name").html(page_name);
			$("#manual_table_id").val(page_table_id);
			$("#manual_post_id").val('');
			// #manual_auto_reply is the id for (enable auto reply button of modal)
			$("#manual_auto_reply").attr('page_table_id',page_table_id);
			$("#manual_auto_reply").attr('post_id','');

			$("#manual_auto_reply").hide();
			$("#check_post_id").show();

			$("#manual_auto_reply").removeClass('btn-danger').addClass('btn-info').html(EnableAutoReply);
			$("#manual_reply_by_post").addClass('modal');
			$("#manual_reply_by_post").modal();
		});

		$("#check_post_id").click(function(){
			$("#manual_reply_error").html('');		
			var post_id = $("#manual_post_id").val();
			var page_table_id = $("#manual_table_id").val();
			$.ajax({
			  type:'POST' ,
			  url:"<?php echo site_url();?>facebook_ex_autoreply/checking_post_id",
			  data:{page_table_id:page_table_id,post_id:post_id},
			  dataType:'JSON',
			  success:function(response){
			  	if(response.error == 'yes')
			  		$("#manual_reply_error").html("<h4 class='red'><div class='alert alert-danger text-center'><i class='fa fa-close'></i> "+response.error_msg+"</div></h4>");
			  	else
			  	{
				  	$("#manual_auto_reply").attr('post_id',post_id);
				  	$("#manual_auto_reply").attr('manual_enable','yes');
				  	$("#check_post_id").hide();
				  	$("#manual_auto_reply").show();
			  	}
			  }
			});
		});

		$(".manual_edit_reply").click(function(){
			var page_name = $(this).attr('page_name');
			var page_table_id = $(this).attr('page_table_id');
			$("#manual_edit_page_name").html(page_name);
			$("#manual_edit_table_id").val(page_table_id);
			$("#manual_edit_error").html('');
			$("#manual_edit_post_id").val('');
			$("#manual_edit_reply_by_post").addClass('modal');
			$("#manual_edit_reply_by_post").modal();
		});

		$("#manual_edit_post_id").keyup(function(){
			$("#manual_edit_auto_reply").hide();
			$("#manual_edit_error").html('');
			var post_id = $("#manual_edit_post_id").val();
			var page_table_id = $("#manual_edit_table_id").val();
			var ThispostIDisnotfoundindatabaseorthispostIDisnotassociatedwiththepageyouareworking = "<?php echo $ThispostIDisnotfoundindatabaseorthispostIDisnotassociatedwiththepageyouareworking; ?>";
			$.ajax({
			  type:'POST' ,
			  url:"<?php echo site_url();?>facebook_ex_autoreply/get_tableid_by_postid",
			  data:{page_table_id:page_table_id,post_id:post_id},
			  dataType:'JSON',
			  success:function(response){
			  	if(response.error == 'yes')
			  		$("#manual_edit_error").html("<h4 class='red'><div class='alert alert-danger text-center'><i class='fa fa-close'></i> "+ThispostIDisnotfoundindatabaseorthispostIDisnotassociatedwiththepageyouareworking+"</div></h4>");
			  	else
				  	$("#manual_edit_auto_reply").attr('table_id',response.table_id);
			  	
			  	$("#manual_edit_auto_reply").show();
			  }
			});

		});
		// end of enable and edit auto reply by post id



		$(".get_post").click(function(){
			var table_id = $(this).attr('table_id');
			var loading = '<img src="'+base_url+'assets/pre-loader/custom_lg.gif" class="center-block">';
			$("#post_synch_modal_body").html(loading);
		  	$("#post_synch_modal").modal();
			$.ajax({
			  type:'POST' ,
			  url:"<?php echo site_url();?>facebook_ex_autoreply/import_latest_post",
			  data:{table_id:table_id},
			  dataType:'JSON',
			  success:function(response){
			  	  $("#page_name_div").html(response.page_name);
			  	  $("#post_synch_modal_body").html(response.message);
			  }
			});

		});
		

		$(document.body).on('click','.enable_auto_commnet',function(){
			var page_table_id = $(this).attr('page_table_id');
			var post_id = $(this).attr('post_id');
			var manual_enable = $(this).attr('manual_enable');
			var Pleaseprovidepostid = "<?php echo $Pleaseprovidepostid; ?>";

			if(typeof(post_id) === 'undefined' || post_id == '')
			{
				alert(Pleaseprovidepostid);
				return false;
			}

			$("#auto_reply_page_id").val(page_table_id);
			$("#auto_reply_post_id").val(post_id);
			$("#manual_enable").val(manual_enable);
			$(".message").val('');
			$(".filter_word").val('');
			for(var i=2;i<=10;i++)
			{
				$("#filter_div_"+i).hide();
			}
			content_counter = 1;
			$("#content_counter").val(content_counter);
			$("#add_more_button").show();

			$("#response_status").html('');

			$("#auto_reply_message_modal").addClass("modal");
			$("#auto_reply_message_modal").modal();

			$("#manual_reply_by_post").removeClass('modal');
		});
		


		$("#content_counter").val(content_counter);
		$(document.body).on('click','#add_more_button',function(){
			content_counter++;
			if(content_counter == 10)
				$("#add_more_button").hide();
			$("#content_counter").val(content_counter);

			$("#filter_div_"+content_counter).show();

		});


		$(document.body).on('change','input[name=message_type]',function(){    
        	if($("input[name=message_type]:checked").val()=="generic")
        	{
        		$("#generic_message_div").show();
        		$("#filter_message_div").hide();
        	}
        	else 
        	{
        		$("#generic_message_div").hide();
        		$("#filter_message_div").show();
        	}
        });

        
        $(document.body).on('click','.lead_first_name',function(){
        	var caretPos = $(this).parent().next()[0].selectionStart;
		    var textAreaTxt = $(this).parent().next().val();
		    var txtToAdd = " #LEAD_USER_FIRST_NAME# ";
		    // var new_text = textAreaTxt + txtToAdd;
		    $(this).parent().next().val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
		});

		$(document.body).on('click','.lead_last_name',function(){

        	var caretPos = $(this).parent().next().next()[0].selectionStart;
		    var textAreaTxt = $(this).parent().next().next().val();
		    var txtToAdd = " #LEAD_USER_LAST_NAME# ";
		    $(this).parent().next().next().val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
		});

		$(document.body).on('click','.lead_tag_name',function(){

	    	var caretPos = $(this).parent().next().next().next()[0].selectionStart;
		    var textAreaTxt = $(this).parent().next().next().next().val();
		    var txtToAdd = " #TAG_USER# ";
		    $(this).parent().next().next().next().val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
		});

        $(document.body).on('click','.emotion',function(){  
        	var img_link = $(this).attr("src");
        	var eval = $(this).attr("eval");
        	var caretPos = $(this).parent().prev()[0].selectionStart;
        	var textAreaTxt = $(this).parent().prev().val();
        	var txtToAdd = " "+eval+" ";
        	// var new_text = textAreaTxt + txtToAdd;
        	$(this).parent().prev().val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
        });



		$(document.body).on('click','#save_button',function(){
			var post_id = $("#auto_reply_post_id").val();
			var reply_type = $("input[name=message_type]:checked").val();
			var Youdidntselectanyoption = "<?php echo $Youdidntselectanyoption; ?>";
			var Youdidntprovideallinformation = "<?php echo $Youdidntprovideallinformation; ?>";
			if (typeof(reply_type)==='undefined')
			{
				alert(Youdidntselectanyoption);
				return false;
			}
			var auto_campaign_name = $("#auto_campaign_name").val().trim();
			if(reply_type == 'generic')
			{
				// var content = $("#generic_message").val().trim();
				if(auto_campaign_name == ''){
					alert(Youdidntprovideallinformation);
					return false;
				}
			}
			else
			{
				// var content1 = $("#filter_word_1").val().trim();
				// var content2 = $("#filter_message_1").val().trim();
				if(auto_campaign_name == ''){
					alert(Youdidntprovideallinformation);
					return false;
				}
			}

			var loading = '<img src="'+base_url+'assets/pre-loader/custom_lg.gif" class="center-block">';
			$("#response_status").html(loading);

			var queryString = new FormData($("#auto_reply_info_form")[0]);
			var AlreadyEnabled = "<?php echo $AlreadyEnabled; ?>";
		    $.ajax({
		    	type:'POST' ,
		    	url: base_url+"facebook_ex_autoreply/ajax_autoreply_submit",
		    	data: queryString,
		    	dataType : 'JSON',
		    	// async: false,
		    	cache: false,
		    	contentType: false,
		    	processData: false,
		    	success:function(response){
		         	if(response.status=="1")
			        {
			         	$("#response_status").html(response.message);
						$("button[post_id="+post_id+"]").removeClass('btn-success').addClass('btn-danger').html(AlreadyEnabled);
			        }
			        else
			        {
			         	$("#response_status").html(response.message);
			        }
		    	}

		    });

		});

		

		$(document.body).on('click','#modal_close',function(){
			var manual_post_id = $("#manual_post_id").val();
			if(manual_post_id != '')
			{
				$("#auto_reply_message_modal").modal("hide");
				$("#manual_reply_by_post").modal("hide");
				$("#manual_post_id").val('');
			}
			else
				$("#auto_reply_message_modal").removeClass("modal");
		});

		$(document.body).on('click','#edit_modal_close',function(){        	
			// $("#edit_auto_reply_message_modal").removeClass("modal");
			var manual_post_id = $("#manual_edit_post_id").val();
			if(manual_post_id != '')
			{
				$("#edit_auto_reply_message_modal").modal("hide");
				$("#manual_edit_reply_by_post").modal("hide");
				$("#manual_edit_post_id").val('');
			}
			else
				$("#edit_auto_reply_message_modal").removeClass("modal");
		});


		$('#post_synch_modal').on('hidden.bs.modal', function () { 
			location.reload();
		});


		$(document.body).on('click','.edit_reply_info',function(){
			$("#manual_edit_reply_by_post").removeClass('modal');
			$("#edit_auto_reply_message_modal").addClass("modal");
			$("#edit_response_status").html("");
			for(var j=1;j<=10;j++)
			{
				$("#edit_filter_div_"+j).hide();
			}

			var table_id = $(this).attr('table_id');
			$.ajax({
			  type:'POST' ,
			  url:"<?php echo site_url();?>facebook_ex_autoreply/ajax_edit_reply_info",
			  data:{table_id:table_id},
			  dataType:'JSON',
			  success:function(response){
			  	$("#edit_auto_reply_page_id").val(response.edit_auto_reply_page_id);
			  	$("#edit_auto_reply_post_id").val(response.edit_auto_reply_post_id);
			  	$("#edit_auto_campaign_name").val(response.edit_auto_campaign_name);

			  	// comment hide and delete section
			  	if(response.is_delete_offensive == 'hide')
		  		{
		  	  		$("#edit_delete_offensive_comment_hide").attr('checked','checked');
		  		}
		  	  	else
		  	  	{
		  	  		$("#edit_delete_offensive_comment_delete").attr('checked','checked');
		  	  	}
	  	  		$("#edit_delete_offensive_comment_keyword").html(response.offensive_words);
			  	$("#edit_private_message_offensive_words").html(response.private_message_offensive_words);

			  	if(response.hide_comment_after_comment_reply == 'no')
		  	  		$("#edit_hide_comment_after_comment_reply_no").attr('checked','checked');
		  	  	else
		  	  		$("#edit_hide_comment_after_comment_reply_yes").attr('checked','checked');
			  	// comment hide and delete section


			  	$("#edit_"+response.reply_type).prop('checked', true);
			  	// added by mostofa on 27-04-2017
			  	if(response.comment_reply_enabled == 'no')
			  		$("#edit_comment_reply_enabled_no").attr('checked','checked');
			  	else
			  		$("#edit_comment_reply_enabled_yes").attr('checked','checked');

			  	if(response.multiple_reply == 'no')
			  		$("#edit_multiple_reply_no").attr('checked','checked');
			  	else
			  		$("#edit_multiple_reply_yes").attr('checked','checked');

			  	if(response.auto_like_comment == 'no')
			  		$("#edit_auto_like_comment_no").attr('checked','checked');
			  	else
			  		$("#edit_auto_like_comment_yes").attr('checked','checked');


			  	
			  	if(response.reply_type == 'generic')
			  	{
			  		$("#edit_generic_message_div").show();
			  		$("#edit_filter_message_div").hide();
			  		var i=1;
			  		edit_content_counter = i;
			  		var auto_reply_text_array_json = JSON.stringify(response.auto_reply_text);
			  		auto_reply_text_array = JSON.parse(auto_reply_text_array_json,'true');
			  		$("#edit_generic_message").html(auto_reply_text_array[0]['comment_reply']);	
			  		$("#edit_generic_message_private").html(auto_reply_text_array[0]['private_reply']);
			  		// comment hide and delete section
			  		$("#edit_generic_image_for_comment_reply_display").attr('src',auto_reply_text_array[0]['image_link']);
			  		$("#edit_generic_video_comment_reply_display").attr('src',auto_reply_text_array[0]['video_link']);
			  		$("#edit_generic_image_for_comment_reply").val(auto_reply_text_array[0]['image_link']);
			  		$("#edit_generic_video_comment_reply").val(auto_reply_text_array[0]['video_link']);
			  		// comment hide and delete section
			  	}
			  	else
			  	{
			  		var edit_nofilter_word_found_text = JSON.stringify(response.edit_nofilter_word_found_text);
			  		edit_nofilter_word_found_text = JSON.parse(edit_nofilter_word_found_text,'true');
			  		$("#edit_nofilter_word_found_text").html(edit_nofilter_word_found_text[0]['comment_reply']);
			  		$("#edit_nofilter_word_found_text_private").html(edit_nofilter_word_found_text[0]['private_reply']);
			  		// comment hide and delete section
			  		$("#edit_nofilter_image_upload_reply_display").attr('src',edit_nofilter_word_found_text[0]['image_link']);
			  		$("#edit_nofilter_video_upload_reply_display").attr('src',edit_nofilter_word_found_text[0]['video_link']);
			  		$("#edit_nofilter_image_upload_reply").val(edit_nofilter_word_found_text[0]['image_link']);
			  		$("#edit_nofilter_video_upload_reply").val(edit_nofilter_word_found_text[0]['video_link']);
			  		// comment hide and delete section

			  		$("#edit_filter_message_div").show();
			  		$("#edit_generic_message_div").hide();
			  		var auto_reply_text_array = JSON.stringify(response.auto_reply_text);
			  		auto_reply_text_array = JSON.parse(auto_reply_text_array,'true');

			  		for(var i = 0; i < auto_reply_text_array.length; i++) {
			  		    var j = i+1;
			  		    $("#edit_filter_div_"+j).show();
			  			$("#edit_filter_word_"+j).val(auto_reply_text_array[i]['filter_word']);
			  			var unscape_reply_text = auto_reply_text_array[i]['reply_text'];
			  			$("#edit_filter_message_"+j).html(unscape_reply_text);
			  			// added by mostofa 25-04-2017
			  			var unscape_comment_reply_text = auto_reply_text_array[i]['comment_reply_text'];
			  			$("#edit_comment_reply_msg_"+j).html(unscape_comment_reply_text);
			  			// comment hide and delete section
			  			$("#edit_filter_image_upload_reply_display_"+j).attr('src',auto_reply_text_array[i]['image_link']);
			  			$("#edit_filter_video_upload_reply_display"+j).attr('src',auto_reply_text_array[i]['video_link']);
			  			$("#edit_filter_image_upload_reply_"+j).val(auto_reply_text_array[i]['image_link']);
			  			$("#edit_filter_video_upload_reply_"+j).val(auto_reply_text_array[i]['video_link']);
			  			// comment hide and delete section
			  		}

			  		edit_content_counter = i+1;
			  		$("#edit_content_counter").val(edit_content_counter);
			  	}
			  	$("#edit_auto_reply_message_modal").modal();
			  }
			});
		});


		$(document.body).on('click','#edit_add_more_button',function(){
			if(edit_content_counter == 11)
				$("#edit_add_more_button").hide();
			$("#edit_content_counter").val(edit_content_counter);

			$("#edit_filter_div_"+edit_content_counter).show();
			edit_content_counter++;

		});
		

		$(document.body).on('click','#edit_save_button',function(){
			var post_id = $("#edit_auto_reply_post_id").val();
			var edit_auto_campaign_name = $("#edit_auto_campaign_name").val();
			var reply_type = $("input[name=edit_message_type]:checked").val();
			var Youdidntselectanyoption = "<?php echo $Youdidntselectanyoption; ?>";
			var Youdidntprovideallinformation = "<?php echo $Youdidntprovideallinformation; ?>";
			if (typeof(reply_type)==='undefined')
			{
				alert(Youdidntselectanyoption);
				return false;
			}
			if(reply_type == 'generic')
			{
				// var content = $("#edit_generic_message").val().trim();
				if(edit_auto_campaign_name == ''){
					alert(Youdidntprovideallinformation);
					return false;
				}
			}
			else
			{
				// var content1 = $("#edit_filter_word_1").val().trim();
				// var content2 = $("#edit_filter_message_1").val().trim();
				if(edit_auto_campaign_name == ''){
					alert(Youdidntprovideallinformation);
					return false;
				}
			}

			var loading = '<img src="'+base_url+'assets/pre-loader/custom_lg.gif" class="center-block">';
			$("#edit_response_status").html(loading);

			var queryString = new FormData($("#edit_auto_reply_info_form")[0]);
		    $.ajax({
		    	type:'POST' ,
		    	url: base_url+"facebook_ex_autoreply/ajax_update_autoreply_submit",
		    	data: queryString,
		    	dataType : 'JSON',
		    	// async: false,
		    	cache: false,
		    	contentType: false,
		    	processData: false,
		    	success:function(response){
		         	if(response.status=="1")
			        {
			         	$("#edit_response_status").html(response.message);
			        }
			        else
			        {
			         	$("#edit_response_status").html(response.message);
			        }
		    	}

		    });

		});


		$(document.body).on('change','input[name=edit_message_type]',function(){    
        	if($("input[name=edit_message_type]:checked").val()=="generic")
        	{
        		$("#edit_generic_message_div").show();
        		$("#edit_filter_message_div").hide();
        	}
        	else 
        	{
        		$("#edit_generic_message_div").hide();
        		$("#edit_filter_message_div").show();
        	}
        });

		
	});
</script>


<div class="modal fade" id="post_synch_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  modal-lg" style="width:90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center"><i class="fa fa-spinner"></i> <?php echo $this->lang->line("latest post for page") ?> - <span id="page_name_div"></span></h4>
            </div>
            <div class="modal-body text-center" id="post_synch_modal_body">                

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="auto_reply_message_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id='modal_close' class="close">&times;</button>
                <h4 class="modal-title text-center"><?php echo $this->lang->line("please give the following information for post auto private reply") ?></h4>
            </div>
            <form action="#" id="auto_reply_info_form" method="post">
	            <input type="hidden" name="auto_reply_page_id" id="auto_reply_page_id" value="">
	            <input type="hidden" name="auto_reply_post_id" id="auto_reply_post_id" value="">
	            <input type="hidden" name="manual_enable" id="manual_enable" value="">
            <div class="modal-body" id="auto_reply_message_modal_body">  
            	<!-- comment hide and delete section -->
            	<div class="row" style="padding: 10px 20px 10px 20px; <?php if(!$commnet_hide_delete_addon) echo "display: none;"; ?> ">
					<div class="col-xs-12">
						<div class="col-xs-5" style="padding: 0px;">
							<label><?php echo $this->lang->line("what do you want about offensive comments?") ?></label>
						</div>
						<div class="col-xs-6">							
							<label class="radio-inline"><input name="delete_offensive_comment" value="hide" id="delete_offensive_comment_hide" class="radio_button" type="radio" checked><?php echo $this->lang->line("hide") ?></label>
							<label class="radio-inline"><input name="delete_offensive_comment" value="delete" id="delete_offensive_comment_delete" class="radio_button" type="radio"><?php echo $this->lang->line("delete") ?></label>
						</div>
					</div>
					<br/><br/>
					<div class="col-xs-12 col-md-6" id="delete_offensive_comment_keyword_div">
						<div class="form-group" style="background: #F5F5F5; border: 1px solid #ccc; padding: 10px;">
							<label><?php echo $this->lang->line("write down the offensive keywords in comma separated") ?>
								 <span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("offensive keywords") ?>" data-content="<?php echo $this->lang->line('write your'); ?>" <i class='fa fa-info-circle'></i> </a>
							</label>
							<textarea class="form-control message" name="delete_offensive_comment_keyword" id="delete_offensive_comment_keyword" placeholder="<?php echo $this->lang->line("Type keywords here in comma separated (keyword1,keyword2)...Keep it blank for no actions") ?>" style="height:170px;"></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6" id="">
						<div class="form-group" style="background: #F5F5F5; border: 1px solid #ccc; padding: 10px;">
							<label><?php echo $this->lang->line("private reply message after deleting offensive comment") ?>
								
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Type your message here...Keep it blank for no actions"); ?>"><i class='fa fa-info-circle'></i> </a>
							</label><br/>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="private_message_offensive_words" id="private_message_offensive_words" placeholder="<?php echo $this->lang->line("Type your message here...Keep it blank for no actions") ?>" style="height:100px;"></textarea>
							<div class='text-center' id="emotion_container"><?php echo $emotion_list;?></div>
						</div>
					</div>
				</div>  
				<!-- end of comment hide and delete section -->

				<div class="row" style="padding: 10px 20px 10px 20px;">
					<!-- added by mostofa on 26-04-2017 -->
					<div class="col-xs-12">
						<div class="col-xs-6" style="padding: 0px;"><label><?php echo $this->lang->line("do you want to send reply message to a user multiple times?") ?></label></div>
						<div class="col-xs-3">
							<label class="radio-inline"><input name="multiple_reply" value="no" id="multiple_reply_no" class="radio_button" type="radio" checked><?php echo $this->lang->line("no") ?></label>
							<label class="radio-inline"><input name="multiple_reply" value="yes" id="multiple_reply_yes" class="radio_button" type="radio"><?php echo $this->lang->line("yes") ?></label>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="col-xs-4" style="padding: 0px;">
							<label><?php echo $this->lang->line("do you want to enable comment reply?") ?></label>
						</div>
						<div class="col-xs-6">							
							<label class="radio-inline"><input name="comment_reply_enabled" value="no" id="comment_reply_enabled_no" class="radio_button" type="radio"><?php echo $this->lang->line("no") ?></label>
							<label class="radio-inline"><input name="comment_reply_enabled" value="yes" id="comment_reply_enabled_yes" class="radio_button" type="radio" checked><?php echo $this->lang->line("yes") ?></label>
						</div>
					</div>

					<div class="col-xs-12">
						<div class="col-xs-4" style="padding: 0px;">
							<label><?php echo $this->lang->line("do you want to like on comment by page?") ?></label>
						</div>
						<div class="col-xs-6">							
							<label class="radio-inline"><input name="auto_like_comment" value="no" id="auto_like_comment_no" class="radio_button" type="radio" checked><?php echo $this->lang->line("no") ?></label>
							<label class="radio-inline"><input name="auto_like_comment" value="yes" id="auto_like_comment_yes" class="radio_button" type="radio"><?php echo $this->lang->line("yes") ?></label>
						</div>
					</div>
					<!-- comment hide and delete section -->
					<div class="col-xs-12" <?php if(!$commnet_hide_delete_addon) echo "style='display: none;'"; ?>>
						<div class="col-xs-5" style="padding: 0px;">
							<label><?php echo $this->lang->line("do you want to hide comments after comment reply?") ?></label>
						</div>
						<div class="col-xs-6">							
							<label class="radio-inline"><input name="hide_comment_after_comment_reply" value="no" id="hide_comment_after_comment_reply_no" class="radio_button" type="radio" checked ><?php echo $this->lang->line("no") ?></label>
							<label class="radio-inline"><input name="hide_comment_after_comment_reply" value="yes" id="hide_comment_after_comment_reply_yes" class="radio_button" type="radio"><?php echo $this->lang->line("yes") ?></label>
						</div>
					</div>
					<!-- comment hide and delete section -->

					<br/><br/>

					<div class="col-xs-12">
						<input name="message_type" value="generic" id="generic" class="radio_button" type="radio"> <label for="generic"><?php echo $this->lang->line("generic message for all") ?></label> <br/>
						<input name="message_type" value="filter" id="filter" class="radio_button" type="radio"> <label for="filter"><?php echo $this->lang->line("send message by filtering word/sentence") ?></label>
					</div>
					<div class="col-xs-12" style="margin-top: 15px;">
						<div class="form-group">
							<label>
								<?php echo $this->lang->line("auto reply campaign name") ?> <span class="red">*</span> 
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your auto reply campaign name here") ?>"><i class='fa fa-info-circle'></i> </a>
							</label>
							<input class="form-control" type="text" name="auto_campaign_name" id="auto_campaign_name" placeholder="<?php echo $this->lang->line("write your auto reply campaign name here") ?>">
						</div>
					</div>
					<div class="col-xs-12" id="generic_message_div" style="display: none;">
						<div class="form-group" style="background: #F5F5F5; border: 1px solid #ccc; padding: 10px;">
							<label>
								<?php echo $this->lang->line("Message for comment reply") ?> <span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your message which you want to send. You can customize the message by individual commenter name."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<?php if($comment_tag_machine_addon) {?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("tag user") ?>" data-content="<?php echo $this->lang->line("You can tag user in your comment reply. Facebook will notify them about mention whenever you tag.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("tag user") ?>" class='btn btn-default btn-sm lead_tag_name'><i class='fa fa-tags'></i>  <?php echo $this->lang->line("tag user") ?></a>
							</span>
							<?php } ?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("first name") ?></a>
							</span>							
							<textarea class="form-control message" name="generic_message" id="generic_message" placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id="emotion_container"><?php echo $emotion_list;?></div>

							<!-- comment hide and delete section -->
							<br/>
							<div class="clearfix" <?php if(!$commnet_hide_delete_addon) echo "style='display: none;'"; ?> >
								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("image for comment reply") ?></label>									
									<div class="form-group">      
				                        <div id="generic_comment_image"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="generic_image_preview_id"></div>
									<span class="red" id="generic_image_for_comment_reply_error"></span>
									<input type="text" name="generic_image_for_comment_reply" class="form-control" id="generic_image_for_comment_reply" placeholder="<?php echo $this->lang->line("put your image url here or click the above upload button") ?>" style="margin-top: -14px;" />
								</div>

								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("video for comment reply") ?>
										<a href="#" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("video upload") ?>" data-content="<?php echo $this->lang->line("Image and video will not work together. Please choose either image or video.") ?>"><i class='fa fa-info-circle'></i></a>
									</label>
									<div class="form-group">      
				                        <div id="generic_video_upload"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="generic_video_preview_id"></div>
									<span class="red" id="generic_video_comment_reply_error"></span>
									<input type="hidden" name="generic_video_comment_reply" class="form-control" id="generic_video_comment_reply" placeholder="<?php echo $this->lang->line("Put your image url here or click upload") ?>"  />
								</div>
							</div>
							<br/><br/>
							<!-- comment hide and delete section -->


							<label>
								<?php echo $this->lang->line("message for private reply") ?> <span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your message which you want to send. You can customize the message by individual commenter name.") ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("first name") ?></a>
							</span>							
							<textarea class="form-control message" name="generic_message_private" id="generic_message_private" placeholder="<?php echo $this->lang->line("Type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id="emotion_container"><?php echo $emotion_list;?></div>
						</div>
					</div>
					<div class="col-xs-12" id="filter_message_div" style="display: none;">
						<?php for ($i=1; $i <= 10 ; $i++) : ?>
								<div class="form-group" id="filter_div_<?php echo $i; ?>" style="<?php if($i%2 == 0) echo "background: #F5F5F5;"; else echo "background: #FFFDDD;"; ?> border: 1px solid #ccc; padding: 10px; margin-bottom: 50px;">
									<label>
										<?php echo $this->lang->line("filter word/sentence") ?> <span class="red">*</span>
										<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Write the word or sentence for which you want to filter comment. For multiple filter keyword write comma separated. Example -   why, wanto to know, when") ?>"><i class='fa fa-info-circle'></i> </a>
									</label>
									<input class="form-control filter_word" type="text" name="filter_word_<?php echo $i; ?>" id="filter_word_<?php echo $i; ?>" placeholder="<?php echo $this->lang->line("write your filter word here") ?>">
									<br/>
									<label>
										<?php echo $this->lang->line("msg for private reply") ?><span class="red">*</span>
										<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your message which you want to send based on filter words. You can customize the message by individual commenter name."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
									</label>
									<span class='pull-right'> 
										<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
										<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("last name") ?></a>
									</span>
									<span class='pull-right'> 
										<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
										<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("first name") ?></a>
									</span>	
									<textarea class="form-control message" name="filter_message_<?php echo $i; ?>" id="filter_message_<?php echo $i; ?>"  placeholder="<?php echo $this->lang->line("Type your message here...") ?>" style="height:170px;"></textarea>
									<div class='text-center' id=""><?php echo $emotion_list;?></div>
									<!-- new feature comment reply section -->
									<br/>
									<label>
										<?php echo $this->lang->line("msg for comment reply") ?><span class="red">*</span>
										<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your message which you want to send based on filter words. You can customize the message by individual commenter name."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
									</label>
									<?php if($comment_tag_machine_addon) {?>
									<span class='pull-right'> 
										<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("tag user") ?>" data-content="<?php echo $this->lang->line("You can tag user in your comment reply. Facebook will notify them about mention whenever you tag.") ?>"><i class='fa fa-info-circle'></i> </a> 
										<a title="<?php echo $this->lang->line("tag user") ?>" class='btn btn-default btn-sm lead_tag_name'><i class='fa fa-tags'></i>  <?php echo $this->lang->line("tag user") ?></a>
									</span>
									<?php } ?>
									<span class='pull-right'> 
										<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
										<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("last name") ?></a>
									</span>
									<span class='pull-right'> 
										<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
										<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("first name") ?></a>
									</span>	
									<textarea class="form-control message" name="comment_reply_msg_<?php echo $i; ?>" id="comment_reply_msg_<?php echo $i; ?>"  placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
									<div class='text-center' id=""><?php echo $emotion_list;?></div>

									<!-- comment hide and delete section -->
									<br/>
									<div class="clearfix" <?php if(!$commnet_hide_delete_addon) echo "style='display: none;'"; ?> >
										<div class="col-xs-12 col-md-6">
											<label class="control-label" ><?php echo $this->lang->line("image for comment reply") ?>
											</label>									
											<div class="form-group">      
						                        <div id="filter_image_upload_<?php echo $i; ?>"><?php echo $this->lang->line("upload") ?></div>	     
											</div>
											<div id="generic_image_preview_id_<?php echo $i; ?>"></div>
											<span class="red" id="generic_image_for_comment_reply_error_<?php echo $i; ?>"></span>
											<input type="text" name="filter_image_upload_reply_<?php echo $i; ?>" class="form-control" id="filter_image_upload_reply_<?php echo $i; ?>" placeholder="<?php echo $this->lang->line("Put your image url here or click the above upload button") ?>" style="margin-top: -14px;" />
										</div>

										<div class="col-xs-12 col-md-6">
											<label class="control-label" ><?php echo $this->lang->line("video for comment reply") ?>
												<a href="#" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("video upload") ?>" data-content="<?php echo $this->lang->line("Image and video will not work together. Please choose either image or video.") ?>"><i class='fa fa-info-circle'></i></a>
											</label>
											<div class="form-group">      
						                        <div id="filter_video_upload_<?php echo $i; ?>"><?php echo $this->lang->line("upload") ?></div>	     
											</div>
											<div id="generic_video_preview_id_<?php echo $i; ?>"></div>
											<span class="red" id="edit_generic_video_comment_reply_error_<?php echo $i; ?>"></span>
											<input type="hidden" name="filter_video_upload_reply_<?php echo $i; ?>" class="form-control" id="filter_video_upload_reply_<?php echo $i; ?>" placeholder="<?php echo $this->lang->line("Put your image url here or click upload") ?>"  />
										</div>
									</div>
									<!-- comment hide and delete section -->


								</div>
						<?php endfor; ?>
						

						<br/>
						<div class="clearfix">
							<input type="hidden" name="content_counter" id="content_counter" />
							<button type="button" class="btn btn-sm btn-success pull-right" id="add_more_button"><i class="fa fa-plus"></i> <?php echo $this->lang->line("add more filtering") ?></button>
						</div>

						<div class="form-group" id="nofilter_word_found_div" style="margin-top: 10px; border: 1px solid #ccc; padding: 10px;">
							<label>
								<?php echo $this->lang->line("comment reply if no matching found") ?>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Write the message,  if no filter word found. If you don't want to send message them, just keep it blank ."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<?php if($comment_tag_machine_addon) {?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("tag user") ?>" data-content="<?php echo $this->lang->line("You can tag user in your comment reply. Facebook will notify them about mention whenever you tag.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("tag user") ?>" class='btn btn-default btn-sm lead_tag_name'><i class='fa fa-tags'></i>  <?php echo $this->lang->line("tag user") ?></a>
							</span>
							<?php } ?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="nofilter_word_found_text" id="nofilter_word_found_text"  placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id=""><?php echo $emotion_list;?></div>

							<!-- comment hide and delete section -->
							<br/>
							<div class="clearfix" <?php if(!$commnet_hide_delete_addon) echo "style='display: none;'"; ?> >
								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("image for comment reply") ?>
									</label>									
									<div class="form-group">      
				                        <div id="nofilter_image_upload"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="nofilter_generic_image_preview_id"></div>
									<span class="red" id="nofilter_image_upload_reply_error"></span>
									<input type="text" name="nofilter_image_upload_reply" class="form-control" id="nofilter_image_upload_reply" placeholder="<?php echo $this->lang->line("put your image url here or click the above upload button") ?>" style="margin-top: -14px;" />
								</div>

								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("video for comment reply") ?>
										<a href="#" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("video upload") ?>" data-content="<?php echo $this->lang->line("Image and video will not work together. Please choose either image or video.") ?>"><i class='fa fa-info-circle'></i></a>
									</label>
									<div class="form-group">      
				                        <div id="nofilter_video_upload"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="nofilter_video_preview_id"></div>
									<span class="red" id="nofilter_video_upload_reply_error"></span>
									<input type="hidden" name="nofilter_video_upload_reply" class="form-control" id="nofilter_video_upload_reply" placeholder="<?php echo $this->lang->line("put your image url here or click upload") ?>"  />
								</div>
							</div>
							<br/><br/>
							<!-- comment hide and delete section -->

							<label>
								<?php echo $this->lang->line("private reply if no matching found") ?>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Write the message,  if no filter word found. If you don't want to send message them, just keep it blank ."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="nofilter_word_found_text_private" id="nofilter_word_found_text_private"  placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id=""><?php echo $emotion_list;?></div>
						</div>


					</div>
				</div>
				<div class="col-xs-12 text-center" id="response_status"></div>
            </div>
            </form>
            <div class="modal-footer text-center">                
				<button class="btn btn-lg btn-warning" id="save_button"><?php echo $this->lang->line("save") ?></button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="edit_auto_reply_message_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id='edit_modal_close' class="close">&times;</button>
                <h4 class="modal-title text-center"><?php echo $this->lang->line("please give the following information for post auto private reply") ?></h4>
            </div>
            <form action="#" id="edit_auto_reply_info_form" method="post">
	            <input type="hidden" name="edit_auto_reply_page_id" id="edit_auto_reply_page_id" value="">
	            <input type="hidden" name="edit_auto_reply_post_id" id="edit_auto_reply_post_id" value="">
            <div class="modal-body" id="edit_auto_reply_message_modal_body">   
            	<!-- comment hide and delete section -->
            	<div class="row" style="padding: 10px 20px 10px 20px;<?php if(!$commnet_hide_delete_addon) echo "display: none;"; ?> ">
					<div class="col-xs-12">
						<div class="col-xs-5" style="padding: 0px;">
							<label><?php echo $this->lang->line("what do you want about offensive comments?") ?></label>
						</div>
						<div class="col-xs-6">							
							<label class="radio-inline"><input name="edit_delete_offensive_comment" value="hide" id="edit_delete_offensive_comment_hide" class="radio_button" type="radio">Hide</label>
							<label class="radio-inline"><input name="edit_delete_offensive_comment" value="delete" id="edit_delete_offensive_comment_delete" class="radio_button" type="radio">Delete</label>
						</div>
					</div>
					<br/><br/>
					<div class="col-xs-12 col-md-6" id="edit_delete_offensive_comment_keyword_div">
						<div class="form-group" style="background: #F5F5F5; border: 1px solid #ccc; padding: 10px;">
							<label><?php echo $this->lang->line("write down the offensive keywords in comma separated.") ?>
								
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("offensive keywords") ?>" data-content="<?php echo $this->lang->line("Type keywords here in comma separated (keyword1,keyword2)...Keep it blank for no actions"); ?> "><i class='fa fa-info-circle'></i> </a>
							</label>
							<textarea class="form-control message" name="edit_delete_offensive_comment_keyword" id="edit_delete_offensive_comment_keyword" placeholder="<?php echo $this->lang->line("Type keywords here in comma separated (keyword1,keyword2)...Keep it blank for no actions") ?>" style="height:170px;"></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6" id="">
						<div class="form-group" style="background: #F5F5F5; border: 1px solid #ccc; padding: 10px;">
							<label><?php echo $this->lang->line("private reply message after deleting offensive comment") ?>
								
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Type your message here...Keep it blank for no actions"); ?>"><i class='fa fa-info-circle'></i> </a>
							</label><br/>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="edit_private_message_offensive_words" id="edit_private_message_offensive_words" placeholder="<?php echo $this->lang->line("Type your message here...Keep it blank for no actions") ?>" style="height:100px;"></textarea>
							<div class='text-center' id="emotion_container"><?php echo $emotion_list;?></div>
						</div>
					</div>
				</div> 
            	<!-- end of comment hide and delete section -->
				<div class="row" style="padding: 10px 20px 10px 20px;">
					<!-- added by mostofa on 26-04-2017 -->
					<div class="col-xs-12">
						<div class="col-xs-6" style="padding: 0px;"><label><?php echo $this->lang->line("do you want to send reply message to a user multiple times?") ?></label></div>
						<div class="col-xs-3">
							<label class="radio-inline"><input name="edit_multiple_reply" value="no" id="edit_multiple_reply_no" class="radio_button" type="radio"><?php echo $this->lang->line("no") ?></label>
							<label class="radio-inline"><input name="edit_multiple_reply" value="yes" id="edit_multiple_reply_yes" class="radio_button" type="radio"><?php echo $this->lang->line("yes") ?></label>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="col-xs-4" style="padding: 0px;">
							<label><?php echo $this->lang->line("do you want to enable comment reply?") ?></label>
						</div>
						<div class="col-xs-6">							
							<label class="radio-inline"><input name="edit_comment_reply_enabled" value="no" id="edit_comment_reply_enabled_no" class="radio_button" type="radio"><?php echo $this->lang->line("no") ?></label>
							<label class="radio-inline"><input name="edit_comment_reply_enabled" value="yes" id="edit_comment_reply_enabled_yes" class="radio_button" type="radio"><?php echo $this->lang->line("yes") ?></label>
						</div>
					</div>

					<div class="col-xs-12">
						<div class="col-xs-4" style="padding: 0px;">
							<label><?php echo $this->lang->line("do you want to like on comment by page?") ?></label>
						</div>
						<div class="col-xs-6">							
							<label class="radio-inline"><input name="edit_auto_like_comment" value="no" id="edit_auto_like_comment_no" class="radio_button" type="radio" checked><?php echo $this->lang->line("no") ?></label>
							<label class="radio-inline"><input name="edit_auto_like_comment" value="yes" id="edit_auto_like_comment_yes" class="radio_button" type="radio"><?php echo $this->lang->line("yes") ?></label>
						</div>
					</div>

					<!-- comment hide and delete section -->
					<div class="col-xs-12" <?php if(!$commnet_hide_delete_addon) echo "style='display: none;'"; ?> >
						<div class="col-xs-5" style="padding: 0px;">
							<label><?php echo $this->lang->line("do you want to hide comments after comment reply?") ?></label>
						</div>
						<div class="col-xs-6">							
							<label class="radio-inline"><input name="edit_hide_comment_after_comment_reply" value="no" id="edit_hide_comment_after_comment_reply_no" class="radio_button" type="radio"><?php echo $this->lang->line("no") ?></label>
							<label class="radio-inline"><input name="edit_hide_comment_after_comment_reply" value="yes" id="edit_hide_comment_after_comment_reply_yes" class="radio_button" type="radio"><?php echo $this->lang->line("yes") ?></label>
						</div>
					</div>
					<!-- comment hide and delete section -->

					<br/><br/>

					<div class="col-xs-12">
						<input name="edit_message_type" value="generic" id="edit_generic" class="radio_button" type="radio"> <label for="edit_generic"><?php echo $this->lang->line("generic message for all") ?></label> <br/>
						<input name="edit_message_type" value="filter" id="edit_filter" class="radio_button" type="radio"> <label for="edit_filter"><?php echo $this->lang->line("send message by filtering word/sentence") ?></label>
					</div>
					<div class="col-xs-12" style="margin-top: 15px;">
						<div class="form-group">
							<label>
								<?php echo $this->lang->line("auto reply campaign name") ?> <span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your auto reply campaign name here") ?>"><i class='fa fa-info-circle'></i> </a>
							</label>
							<input class="form-control" type="text" name="edit_auto_campaign_name" id="edit_auto_campaign_name" placeholder="<?php echo $this->lang->line("write your auto reply campaign name here") ?>">
						</div>
					</div>
					<div class="col-xs-12" id="edit_generic_message_div" style="display: none;">
						<div class="form-group" style="background: #F5F5F5; border: 1px solid #ccc; padding: 10px;">
							<label>
								<?php echo $this->lang->line("message for comment reply") ?> <span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your message which you want to send. You can customize the message by individual commenter name."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<?php if($comment_tag_machine_addon) {?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("tag user") ?>" data-content="<?php echo $this->lang->line("You can tag user in your comment reply. Facebook will notify them about mention whenever you tag.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("tag user") ?>" class='btn btn-default btn-sm lead_tag_name'><i class='fa fa-tags'></i>  <?php echo $this->lang->line("tag user") ?></a>
							</span>
							<?php } ?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("Include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="edit_generic_message" id="edit_generic_message" placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id="emotion_container"><?php echo $emotion_list;?></div>

							<!-- comment hide and delete scetion -->
							<br/>
							<div class="clearfix" <?php if(!$commnet_hide_delete_addon) echo "style='display: none;'"; ?> >
								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("image for comment reply") ?>
									</label>									
									<div class="form-group">      
				                        <div id="edit_generic_comment_image"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="edit_generic_image_preview_id"></div>
									<span class="red" id="generic_image_for_comment_reply_error"></span>
									<input type="text" name="edit_generic_image_for_comment_reply" class="form-control" id="edit_generic_image_for_comment_reply" placeholder="<?php echo $this->lang->line("put your image url here or click the above upload button") ?>" style="margin-top: -14px;" />

									<img src="" alt="image" id="edit_generic_image_for_comment_reply_display" height="180" width="370" />
								</div>

								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("video for comment reply") ?>
										<a href="#" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("video upload") ?>" data-content="<?php echo $this->lang->line("Image and video will not work together. Please choose either image or video.") ?>"><i class='fa fa-info-circle'></i></a>
									</label>
									<div class="form-group">      
				                        <div id="edit_generic_video_upload"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="edit_generic_video_preview_id"></div>
									<span class="red" id="edit_generic_video_comment_reply_error"></span>
									<input type="hidden" name="edit_generic_video_comment_reply" class="form-control" id="edit_generic_video_comment_reply" placeholder="<?php echo $this->lang->line("put your image url here or click upload") ?>" />

									<video width="100%" height="200" controls style="border:1px solid #ccc">
										<source src="" id="edit_generic_video_comment_reply_display" type="video/mp4">
									<?php echo $this->lang->line("your browser does not support the video tag.") ?>
									</video>
								</div>
							</div>
							<br/><br/>
							<!-- comment hide and delete scetion -->

							<label>
								<?php echo $this->lang->line("message for private reply") ?> <span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your message which you want to send. You can customize the message by individual commenter name.") ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="edit_generic_message_private" id="edit_generic_message_private" placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id="emotion_container"><?php echo $emotion_list;?></div>
						</div>
					</div>
					<div class="col-xs-12" id="edit_filter_message_div" style="display: none;">
					<?php for($i=1;$i<=10;$i++) :?>
						<div class="form-group" id="edit_filter_div_<?php echo $i; ?>" style="<?php if($i%2 == 0) echo "background: #F5F5F5;"; else echo "background: #FFFDDD;"; ?> border: 1px solid #ccc; padding: 10px; margin-bottom: 50px;">
							<label>
								<?php echo $this->lang->line("filter word/sentence") ?> <span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Write the word or sentence for which you want to filter comment. For multiple filter keyword write comma separated. Example -   why, want to know, when") ?>"><i class='fa fa-info-circle'></i> </a>
							</label>
							<input class="form-control filter_word" type="text" name="edit_filter_word_<?php echo $i; ?>" id="edit_filter_word_<?php echo $i; ?>" placeholder="<?php echo $this->lang->line("write your filter word here") ?>">
							<br/>
							<label>
								<?php echo $this->lang->line("msg for private reply") ?><span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your message which you want to send based on filter words. You can customize the message by individual commenter name."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="edit_filter_message_<?php echo $i; ?>" id="edit_filter_message_<?php echo $i; ?>"  placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id=""><?php echo $emotion_list;?></div>
							<br/>
							<label>
								<?php echo $this->lang->line("msg for comment reply") ?><span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("write your message which you want to send based on filter words. You can customize the message by individual commenter name."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<?php if($comment_tag_machine_addon) {?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("tag user") ?>" data-content="<?php echo $this->lang->line("You can tag user in your comment reply. Facebook will notify them about mention whenever you tag.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("tag user") ?>" class='btn btn-default btn-sm lead_tag_name'><i class='fa fa-tags'></i>  <?php echo $this->lang->line("tag user") ?></a>
							</span>
							<?php } ?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i>  <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="edit_comment_reply_msg_<?php echo $i; ?>" id="edit_comment_reply_msg_<?php echo $i; ?>"  placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id=""><?php echo $emotion_list;?></div>

							<!-- comment hide and delete section -->
							<br/>
							<div class="clearfix" <?php if(!$commnet_hide_delete_addon) echo "style='display: none;'"; ?> >
								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("image for comment reply") ?>
									</label>									
									<div class="form-group">      
				                        <div id="edit_filter_image_upload_<?php echo $i; ?>"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="edit_generic_image_preview_id_<?php echo $i; ?>"></div>
									<span class="red" id="edit_generic_image_for_comment_reply_error_<?php echo $i; ?>"></span>
									<input type="text" name="edit_filter_image_upload_reply_<?php echo $i; ?>" class="form-control" id="edit_filter_image_upload_reply_<?php echo $i; ?>" placeholder="<?php echo $this->lang->line("put your image url here or click the above upload button") ?>" style="margin-top: -14px;" />

									<img src="" alt="image" id="edit_filter_image_upload_reply_display_<?php echo $i; ?>" height="180" width="370" />
								</div>

								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("video for comment reply") ?>
										<a href="#" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("video upload") ?>" data-content="<?php echo $this->lang->line("Image and video will not work together. Please choose either image or video.") ?>"><i class='fa fa-info-circle'></i></a>
									</label>
									<div class="form-group">      
				                        <div id="edit_filter_video_upload_<?php echo $i; ?>"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="edit_generic_video_preview_id_<?php echo $i; ?>"></div>
									<span class="red" id="edit_generic_video_comment_reply_error_<?php echo $i; ?>"></span>
									<input type="hidden" name="edit_filter_video_upload_reply_<?php echo $i; ?>" class="form-control" id="edit_filter_video_upload_reply_<?php echo $i; ?>" placeholder="<?php echo $this->lang->line("put your image url here or click upload") ?>"  />

									<video width="100%" height="200" controls style="border:1px solid #ccc">
										<source src="" id="edit_filter_video_upload_reply_display<?php echo $i; ?>" type="video/mp4">
									<?php echo $this->lang->line("your browser does not support the video tag.") ?>
									</video>
								</div>
							</div>
							<!-- comment hide and delete section -->

						</div>
					<?php endfor; ?>
						

						<br/>
						<div class="clearfix">
							<input type="hidden" name="edit_content_counter" id="edit_content_counter" />
							<button type="button" class="btn btn-sm btn-success pull-right" id="edit_add_more_button"><i class="fa fa-plus"></i> <?php echo $this->lang->line("add more filtering") ?></button>
						</div>

						<div class="form-group" id="edit_nofilter_word_found_div" style="margin-top: 10px; border: 1px solid #ccc; padding: 10px;">
							<label>
								<?php echo $this->lang->line("comment reply if no matching found") ?>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Write the message,  if no filter word found. If you don't want to send message them, just keep it blank ."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<?php if($comment_tag_machine_addon) {?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("tag user") ?>" data-content="<?php echo $this->lang->line("You can tag user in your comment reply. Facebook will notify them about mention whenever you tag.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("tag user") ?>" class='btn btn-default btn-sm lead_tag_name'><i class='fa fa-tags'></i>  <?php echo $this->lang->line("tag user") ?></a>
							</span>
							<?php } ?>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="edit_nofilter_word_found_text" id="edit_nofilter_word_found_text"  placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id=""><?php echo $emotion_list;?></div>
							
							<!-- comment hide and delete section -->
							<br/>
							<div class="clearfix" <?php if(!$commnet_hide_delete_addon) echo "style='display: none;'"; ?> >
								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("image for comment reply") ?>
									</label>									
									<div class="form-group">      
				                        <div id="edit_nofilter_image_upload"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="edit_nofilter_generic_image_preview_id"></div>
									<span class="red" id="edit_nofilter_image_upload_reply_error"></span>
									<input type="text" name="edit_nofilter_image_upload_reply" class="form-control" id="edit_nofilter_image_upload_reply" placeholder="<?php echo $this->lang->line("put your image url here or click the above upload button") ?>" style="margin-top: -14px;" />

									<img src="" alt="image" id="edit_nofilter_image_upload_reply_display" height="180" width="370" />
								</div>

								<div class="col-xs-12 col-md-6">
									<label class="control-label" ><?php echo $this->lang->line("video for comment reply") ?>
										<a href="#" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("video upload") ?>" data-content="<?php echo $this->lang->line("image and video will not work together. Please choose either image or video.") ?>"><i class='fa fa-info-circle'></i></a>
									</label>
									<div class="form-group">      
				                        <div id="edit_nofilter_video_upload"><?php echo $this->lang->line("upload") ?></div>	     
									</div>
									<div id="edit_nofilter_video_preview_id"></div>
									<span class="red" id="edit_nofilter_video_upload_reply_error"></span>
									<input type="hidden" name="edit_nofilter_video_upload_reply" class="form-control" id="edit_nofilter_video_upload_reply" placeholder="<?php echo $this->lang->line("put your image url here or click upload") ?>" />

									<video width="100%" height="200" controls style="border:1px solid #ccc">
										<source src="" id="edit_nofilter_video_upload_reply_display" type="video/mp4">
									<?php echo $this->lang->line("your browser does not support the video tag.") ?>
									</video>
								</div>
							</div>
							<br/><br/>
							<!-- comment hide and delete section -->

							<label>
								<?php echo $this->lang->line("private reply if no matching found") ?>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Write the message,  if no filter word found. If you don't want to send message them, just keep it blank ."); ?>  Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
							</label>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>
							</span>
							<span class='pull-right'> 
								<a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
								<a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>
							</span>	
							<textarea class="form-control message" name="edit_nofilter_word_found_text_private" id="edit_nofilter_word_found_text_private"  placeholder="<?php echo $this->lang->line("type your message here...") ?>" style="height:170px;"></textarea>
							<div class='text-center' id=""><?php echo $emotion_list;?></div>
						</div>


					</div>
				</div>
				<div class="col-xs-12 text-center" id="edit_response_status"></div>
            </div>
            </form>
            <div class="modal-footer text-center">                
				<button class="btn btn-lg btn-warning" id="edit_save_button"><?php echo $this->lang->line("update") ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="manual_reply_by_post" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("please provide a post id of page") ?> (<span id="manual_page_name"></span>)</h4>
            </div>
            <div class="modal-body ">
                <div class="row">
                    <div class="col-xs-12" id="waiting_div"></div>
                    <div class="col-xs-12 col-md-8 col-md-offset-2 well">
                        <form>
                            <div class="form-group">
                              <label for="manual_post_id"><?php echo $this->lang->line("post id") ?> :</label>
                              <input type="text" class="form-control" id="manual_post_id" placeholder="<?php echo $this->lang->line("please give a post id") ?>" value="">
                              <input type="hidden" id="manual_table_id">
                            </div><br/>
                            <div class="text-center" id="manual_reply_error"></div>
                            <div class="form-group text-center">
                              <button type="button" class="btn btn-warning" id="check_post_id"><i class=""></i> <?php echo $this->lang->line("check existance") ?></button>
                            </div>
                            <div class="form-group text-center">
                              <button type="button" class="btn btn-success enable_auto_commnet" id="manual_auto_reply"><i class="fa fa-plus"></i> <?php echo $this->lang->line("enable auto reply") ?></button>
                            </div>
                          </form>
                        
                    </div>                    
                </div>               
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="manual_edit_reply_by_post" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("please provide a post id of page") ?> (<span id="manual_edit_page_name"></span>)</h4>
            </div>
            <div class="modal-body ">
                <div class="row">
                    <div class="col-xs-12" id="waiting_div"></div>
                    <div class="col-xs-12 col-md-8 col-md-offset-2 well">
                        <form>
                            <div class="form-group">
                              <label for="manual_post_id"><?php echo $this->lang->line("post id") ?> :</label>
                              <input type="text" class="form-control" id="manual_edit_post_id" placeholder="<?php echo $this->lang->line("please give a post id") ?>" value="">
                              <input type="hidden" id="manual_edit_table_id">
                            </div><br/>
                            <div class="text-center" id="manual_edit_error"></div>
                            <div class="form-group text-center">
                              <button type="button" class="btn btn-info edit_reply_info" id="manual_edit_auto_reply"><i class="fa fa-pencil"></i> <?php echo $this->lang->line("edit auto reply") ?></button>
                            </div>
                          </form>
                        
                    </div>                    
                </div>               
            </div>
        </div>
    </div>
</div>

<!-- comment hide and delete section -->
<div class="modal fade" id="modal-live-video-library"  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header clearfix">
        <a class="pull-right" id="filemanager_close" style="font-size: 14px; color: white; cursor: pointer;" >&times;</a>
        <h4 class="modal-title"><i class="fa fa-file-video-o"></i> <?php echo $this->lang->line("filemanager Library") ?></h4>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>

<script>
	$j("document").ready(function(){
		$("#filemanager_close").click(function(){
			$("#modal-live-video-library").removeClass('modal');
		});

		var base_url="<?php echo site_url(); ?>";
		var user_id = "<?php echo $this->session->userdata('user_id'); ?>";
		<?php for($k=1;$k<=10;$k++) : ?>
			$("#edit_filter_video_upload_<?php echo $k; ?>").uploadFile({
	    			url:base_url+"facebook_ex_autoreply/upload_live_video",
	    			fileName:"myfile",
	    			// maxFileSize:500*1024*1024,
	    			showPreview:false,
	    			returnType: "json",
	    			dragDrop: true,
	    			showDelete: true,
	    			multiple:false,
	    			maxFileCount:1, 
	    			acceptFiles:".flv,.mp4,.wmv,.WMV,.MP4,.FLV",
	    			deleteCallback: function (data, pd) {
	    				var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_live_file');?>";
	    				$.post(delete_url, {op: "delete",name: data},
	    					function (resp,textStatus, jqXHR) {  
	    					    $("#edit_filter_video_upload_reply_<?php echo $k; ?>").val('');              
	    					});

	    			},
	    			onSuccess:function(files,data,xhr,pd)
	    			{
	    				var file_path = base_url+"upload/video/"+data;
	    				$("#edit_filter_video_upload_reply_<?php echo $k; ?>").val(file_path);	
	    			}
	    		});


	    		$("#edit_filter_image_upload_<?php echo $k; ?>").uploadFile({
	    	        url:base_url+"facebook_ex_autoreply/upload_image_only",
	    	        fileName:"myfile",
	    	        // maxFileSize:1*1024*1024,
	    	        showPreview:false,
	    	        returnType: "json",
	    	        dragDrop: true,
	    	        showDelete: true,
	    	        multiple:false,
	    	        maxFileCount:1, 
	    	        acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
	    	        deleteCallback: function (data, pd) {
	    	            var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_file');?>";
	    	            $.post(delete_url, {op: "delete",name: data},
	    	                function (resp,textStatus, jqXHR) {
	    	                	$("#edit_filter_image_upload_reply_<?php echo $k; ?>").val('');                      
	    	                });
	    	           
	    	         },
	    	         onSuccess:function(files,data,xhr,pd)
	    	           {
	    	               var data_modified = base_url+"upload/image/"+user_id+"/"+data;
	    	               $("#edit_filter_image_upload_reply_<?php echo $k; ?>").val(data_modified);	
	    	           }
	    	    });
		<?php endfor; ?>

		<?php for($k=1;$k<=10;$k++) : ?>
			$("#filter_video_upload_<?php echo $k; ?>").uploadFile({
	    			url:base_url+"facebook_ex_autoreply/upload_live_video",
	    			fileName:"myfile",
	    			// maxFileSize:500*1024*1024,
	    			showPreview:false,
	    			returnType: "json",
	    			dragDrop: true,
	    			showDelete: true,
	    			multiple:false,
	    			maxFileCount:1, 
	    			acceptFiles:".flv,.mp4,.wmv,.WMV,.MP4,.FLV",
	    			deleteCallback: function (data, pd) {
	    				var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_live_file');?>";
	    				$.post(delete_url, {op: "delete",name: data},
	    					function (resp,textStatus, jqXHR) {  
	    					    $("#filter_video_upload_reply_<?php echo $k; ?>").val('');              
	    					});

	    			},
	    			onSuccess:function(files,data,xhr,pd)
	    			{
	    				var file_path = base_url+"upload/video/"+data;
	    				$("#filter_video_upload_reply_<?php echo $k; ?>").val(file_path);	
	    			}
	    		});


	    		$("#filter_image_upload_<?php echo $k; ?>").uploadFile({
	    	        url:base_url+"facebook_ex_autoreply/upload_image_only",
	    	        fileName:"myfile",
	    	        // maxFileSize:1*1024*1024,
	    	        showPreview:false,
	    	        returnType: "json",
	    	        dragDrop: true,
	    	        showDelete: true,
	    	        multiple:false,
	    	        maxFileCount:1, 
	    	        acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
	    	        deleteCallback: function (data, pd) {
	    	            var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_file');?>";
	    	            $.post(delete_url, {op: "delete",name: data},
	    	                function (resp,textStatus, jqXHR) {
	    	                	$("#filter_image_upload_reply_<?php echo $k; ?>").val('');                      
	    	                });
	    	           
	    	         },
	    	         onSuccess:function(files,data,xhr,pd)
	    	           {
	    	               var data_modified = base_url+"upload/image/"+user_id+"/"+data;
	    	               $("#filter_image_upload_reply_<?php echo $k; ?>").val(data_modified);	
	    	           }
	    	    });
		<?php endfor; ?>

		$("#generic_video_upload").uploadFile({
			url:base_url+"facebook_ex_autoreply/upload_live_video",
			fileName:"myfile",
			// maxFileSize:500*1024*1024,
			showPreview:false,
			returnType: "json",
			dragDrop: true,
			showDelete: true,
			multiple:false,
			maxFileCount:1, 
			acceptFiles:".flv,.mp4,.wmv,.WMV,.MP4,.FLV",
			deleteCallback: function (data, pd) {
				var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_live_file');?>";
				$.post(delete_url, {op: "delete",name: data},
					function (resp,textStatus, jqXHR) {  
					    $("#generic_video_comment_reply").val('');              
					});

			},
			onSuccess:function(files,data,xhr,pd)
			{
				var file_path = base_url+"upload/video/"+data;
				$("#generic_video_comment_reply").val(file_path);	
			}
		});


		$("#generic_comment_image").uploadFile({
	        url:base_url+"facebook_ex_autoreply/upload_image_only",
	        fileName:"myfile",
	        // maxFileSize:1*1024*1024,
	        showPreview:false,
	        returnType: "json",
	        dragDrop: true,
	        showDelete: true,
	        multiple:false,
	        maxFileCount:1, 
	        acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
	        deleteCallback: function (data, pd) {
	            var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_file');?>";
	            $.post(delete_url, {op: "delete",name: data},
	                function (resp,textStatus, jqXHR) {
	                	$("#generic_image_for_comment_reply").val('');                      
	                });
	           
	         },
	         onSuccess:function(files,data,xhr,pd)
	           {
	               var data_modified = base_url+"upload/image/"+user_id+"/"+data;
	               $("#generic_image_for_comment_reply").val(data_modified);		
	           }
	    });


	    $("#nofilter_video_upload").uploadFile({
			url:base_url+"facebook_ex_autoreply/upload_live_video",
			fileName:"myfile",
			// maxFileSize:500*1024*1024,
			showPreview:false,
			returnType: "json",
			dragDrop: true,
			showDelete: true,
			multiple:false,
			maxFileCount:1, 
			acceptFiles:".flv,.mp4,.wmv,.WMV,.MP4,.FLV",
			deleteCallback: function (data, pd) {
				var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_live_file');?>";
				$.post(delete_url, {op: "delete",name: data},
					function (resp,textStatus, jqXHR) {  
					    $("#nofilter_video_upload_reply").val('');              
					});

			},
			onSuccess:function(files,data,xhr,pd)
			{
				var file_path = base_url+"upload/video/"+data;
				$("#nofilter_video_upload_reply").val(file_path);	
			}
		});


		$("#nofilter_image_upload").uploadFile({
	        url:base_url+"facebook_ex_autoreply/upload_image_only",
	        fileName:"myfile",
	        // maxFileSize:1*1024*1024,
	        showPreview:false,
	        returnType: "json",
	        dragDrop: true,
	        showDelete: true,
	        multiple:false,
	        maxFileCount:1, 
	        acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
	        deleteCallback: function (data, pd) {
	            var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_file');?>";
	            $.post(delete_url, {op: "delete",name: data},
	                function (resp,textStatus, jqXHR) {
	                	$("#nofilter_image_upload_reply").val('');                      
	                });
	           
	         },
	         onSuccess:function(files,data,xhr,pd)
	           {
	               var data_modified = base_url+"upload/image/"+user_id+"/"+data;
	               $("#nofilter_image_upload_reply").val(data_modified);		
	           }
	    });

	    $("#edit_generic_video_upload").uploadFile({
			url:base_url+"facebook_ex_autoreply/upload_live_video",
			fileName:"myfile",
			// maxFileSize:500*1024*1024,
			showPreview:false,
			returnType: "json",
			dragDrop: true,
			showDelete: true,
			multiple:false,
			maxFileCount:1, 
			acceptFiles:".flv,.mp4,.wmv,.WMV,.MP4,.FLV",
			deleteCallback: function (data, pd) {
				var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_live_file');?>";
				$.post(delete_url, {op: "delete",name: data},
					function (resp,textStatus, jqXHR) {  
					    $("#edit_generic_video_comment_reply").val('');              
					});

			},
			onSuccess:function(files,data,xhr,pd)
			{
				var file_path = base_url+"upload/video/"+data;
				$("#edit_generic_video_comment_reply").val(file_path);	
			}
		});


		$("#edit_generic_comment_image").uploadFile({
	        url:base_url+"facebook_ex_autoreply/upload_image_only",
	        fileName:"myfile",
	        // maxFileSize:1*1024*1024,
	        showPreview:false,
	        returnType: "json",
	        dragDrop: true,
	        showDelete: true,
	        multiple:false,
	        maxFileCount:1, 
	        acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
	        deleteCallback: function (data, pd) {
	            var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_file');?>";
	            $.post(delete_url, {op: "delete",name: data},
	                function (resp,textStatus, jqXHR) {
	                	$("#edit_generic_image_for_comment_reply").val('');                      
	                });
	           
	         },
	         onSuccess:function(files,data,xhr,pd)
	           {
	               var data_modified = base_url+"upload/image/"+user_id+"/"+data;
	               $("#edit_generic_image_for_comment_reply").val(data_modified);		
	           }
	    });


	    $("#edit_nofilter_video_upload").uploadFile({
			url:base_url+"facebook_ex_autoreply/upload_live_video",
			fileName:"myfile",
			// maxFileSize:500*1024*1024,
			showPreview:false,
			returnType: "json",
			dragDrop: true,
			showDelete: true,
			multiple:false,
			maxFileCount:1, 
			acceptFiles:".flv,.mp4,.wmv,.WMV,.MP4,.FLV",
			deleteCallback: function (data, pd) {
				var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_live_file');?>";
				$.post(delete_url, {op: "delete",name: data},
					function (resp,textStatus, jqXHR) {  
					    $("#edit_nofilter_video_upload_reply").val('');              
					});

			},
			onSuccess:function(files,data,xhr,pd)
			{
				var file_path = base_url+"upload/video/"+data;
				$("#edit_nofilter_video_upload_reply").val(file_path);	
			}
		});


		$("#edit_nofilter_image_upload").uploadFile({
	        url:base_url+"facebook_ex_autoreply/upload_image_only",
	        fileName:"myfile",
	        // maxFileSize:1*1024*1024,
	        showPreview:false,
	        returnType: "json",
	        dragDrop: true,
	        showDelete: true,
	        multiple:false,
	        maxFileCount:1, 
	        acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
	        deleteCallback: function (data, pd) {
	            var delete_url="<?php echo site_url('facebook_ex_autoreply/delete_uploaded_file');?>";
	            $.post(delete_url, {op: "delete",name: data},
	                function (resp,textStatus, jqXHR) {
	                	$("#edit_nofilter_image_upload_reply").val('');                      
	                });
	           
	         },
	         onSuccess:function(files,data,xhr,pd)
	           {
	               var data_modified = base_url+"upload/image/"+user_id+"/"+data;
	               $("#edit_nofilter_image_upload_reply").val(data_modified);		
	           }
	    });

    });
</script>
<!-- comment hide and delete section -->

<style type="text/css">.ajax-upload-dragdrop{width:100% !important;}</style>
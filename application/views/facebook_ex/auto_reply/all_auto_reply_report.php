<?php 
	$this->load->view("include/upload_js"); 
	if(ultraresponse_addon_module_exist())	$commnet_hide_delete_addon = 1;
	else $commnet_hide_delete_addon = 0;

	if(addon_exist(201,"commenttagmachine")) $comment_tag_machine_addon = 1;
	else $comment_tag_machine_addon = 0;		
?>
<!-- Main content -->
<section class="content-header">
	<h1 class = 'text-info'> <i class='fa fa-reply-all'></i> <?php echo $this->lang->line("auto reply report") ?> </h1>
</section>
<section class="content">  
	<div class="row" >
		<div class="col-xs-12">
			<div class="grid_container" style="width:100%; min-height:700px;">
				<table 
				id="tt"  
				class="easyui-datagrid" 
				url="<?php echo base_url()."facebook_ex_autoreply/all_auto_reply_report_data"; ?>" 

				pagination="true" 
				rownumbers="true" 
				toolbar="#tb" 
				pageSize="15" 
				pageList="[5,10,15,20,50,100]"  
				fit= "true" 
				fitColumns= "true" 
				nowrap= "true" 
				view= "detailview"
				idField="id"
				>				

				<thead>
					<tr>
						<!-- <th field="id"  checkbox="true"></th> -->
						<th field="auto_reply_campaign_name" sortable="true"><?php echo $this->lang->line("campaign name")?></th>
						<th field="page_name" sortable="true"><?php echo $this->lang->line("page name")?></th>
						<th field="post_id" sortable="true" formatter="post_id_link"><?php echo $this->lang->line("post id")?></th>
						<th field="auto_private_reply_count" sortable="true"><?php echo $this->lang->line("reply sent")?></th>
						<th field="view" formatter="video_analytics"><?php echo $this->lang->line("actions")?></th>
						<th field="post_created_at" sortable="true"><?php echo $this->lang->line("post create time")?></th>
						<th field="last_reply_time" sortable="true"><?php echo $this->lang->line("last reply time")?></th>
						<th field="error_message" sortable="true"><?php echo $this->lang->line("error message")?></th>
						<th field="post_description" sortable="true"><?php echo $this->lang->line("post description")?></th>
						<th field="force" align="center" formatter="force_process"><?php echo $this->lang->line("force process"); ?></th>
					</tr>
				</thead>
			</table>                        
		</div>

		<div id="tb" style="padding:3px">

			<form class="form-inline" style="margin-top:20px">
				<div class="form-group">
					<select name="search_page_name" id="search_page_name" class="form-control">
						<option value=""><?php echo $this->lang->line("page name") ?></option>
						<?php foreach ($page_info as $key => $value): ?>
							<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<input id="campaign_name" name="campaign_name" class="form-control" size="30" placeholder="<?php echo $this->lang->line('Campaign Name');?>">
				</div>
				<button class='btn btn-info'  onclick="doSearch(event)"><i class="fa fa-binoculars"></i> <?php echo $this->lang->line("Search");?></button>    
			</form> 
		</div>        
	</div>
</div>   
</section>


<?php 
	
	$Doyouwanttopausethiscampaign = $this->lang->line("do you want to pause this campaign?");
	$Doyouwanttostartthiscampaign = $this->lang->line("do you want to start this campaign?");
	$Doyouwanttodeletethisrecordfromdatabase = $this->lang->line("do you want to delete this record from database?");
	$Youdidntselectanyoption = $this->lang->line("you didn't select any option.");
	$Youdidntprovideallinformation = $this->lang->line("you didn't provide all information.");
	$Youdidntprovideallinformation = $this->lang->line("you didn't provide all information.");


	$edit = $this->lang->line("Edit");
	$report = $this->lang->line("Report");
	$deletet = $this->lang->line("Delete");
	$pausecampaign = $this->lang->line("Pause Campaign");
	$startcampaign = $this->lang->line("Start Campaign");

	$doyoureallywanttoReprocessthiscampaign = $this->lang->line("Force Reprocessing means you are going to process this campaign again from where it ended. You should do only if you think the campaign is hung for long time and didn't send message for long time. It may happen for any server timeout issue or server going down during last attempt or any other server issue. So only click OK if you think message is not sending. Are you sure to Reprocessing ?");
	$alreadyEnabled = $this->lang->line("this campaign is already enable for processing.");

?>

<script>
	$j("document").ready(function(){
		$('[data-toggle="popover"]').popover(); 
		$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});

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

		var user_id = "<?php echo $this->session->userdata('user_id'); ?>";

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

<script>

	var base_url="<?php echo site_url(); ?>";

	function doSearch(event)
	{
		event.preventDefault(); 
		$j('#tt').datagrid('load',{
			search_page_name   :     $j('#search_page_name').val(),      
			campaign_name   :     $j('#campaign_name').val(),      
			is_searched		:     1
		});


	}

	var edit = "<?php echo $edit; ?>";
	var report = "<?php echo $report; ?>";
	var deletet = "<?php echo $deletet; ?>";
	var pausecampaign = "<?php echo $pausecampaign; ?>";
	var startcampaign = "<?php echo $startcampaign; ?>";
	function video_analytics(value,row,index)
	{
		var page_url = "<button class='btn btn-warning btn-sm edit_reply_info' table_id='"+row.id+"'><i class='fa fa-edit'></i> "+edit+"</button>&nbsp;<button class='btn btn-info btn-sm view_report' table_id='"+row.id+"'><i class='fa fa-eye'></i> "+report+"</button>&nbsp;<button class='btn btn-danger btn-sm delete_report' table_id='"+row.id+"'><i class='fa fa-remove'></i> "+deletet+"</button>";
		var button = '';
		if(row.auto_private_reply_status == '0' || row.auto_private_reply_status == '1')
			button = "&nbsp;<button class='btn btn-warning btn-sm pause_campaign_info' table_id='"+row.id+"'><i class='fa fa-pause'></i> "+pausecampaign+"</button>";
		if(row.auto_private_reply_status == '2')
			button = "&nbsp;<button class='btn btn-success btn-sm play_campaign_info' table_id='"+row.id+"'><i class='fa fa-play'></i> "+startcampaign+"</button>";
		page_url = page_url+button;
		return page_url;
	}

	function force_process(value,row,index)
	{
		var force_process_btn = "<a  id='"+row.id+"' class='force btn-sm btn btn-warning'><i class='fa fa-refresh'></i> <?php echo $this->lang->line("force reprocessing"); ?></a>";		
		return force_process_btn;
	}

	


	function post_id_link(value,row,index)
	{
		return "<a target='_BLANK' href='https://facebook.com/"+row.post_id+"'>"+row.post_id+"</a>";
	}


	$(document.body).on('click','.pause_campaign_info',function(){
		var Doyouwanttopausethiscampaign = "<?php echo $Doyouwanttopausethiscampaign; ?>";
		var ans = confirm(Doyouwanttopausethiscampaign);
		if(ans)
		{
			var table_id = $(this).attr('table_id');
			$.ajax({
				type:'POST' ,
				url: base_url+"facebook_ex_autoreply/ajax_autoreply_pause",
				data: {table_id:table_id},
				success:function(response){
					$j('#tt').datagrid('reload');
				}

			});
		}
	});

	$(document.body).on('click','.play_campaign_info',function(){
		var Doyouwanttostartthiscampaign = "<?php echo $Doyouwanttostartthiscampaign; ?>";
		var ans = confirm(Doyouwanttostartthiscampaign);
		if(ans)
		{
			var table_id = $(this).attr('table_id');
			$.ajax({
				type:'POST' ,
				url: base_url+"facebook_ex_autoreply/ajax_autoreply_play",
				data: {table_id:table_id},
				success:function(response){
					$j('#tt').datagrid('reload');
				}

			});
		}
	});

	$(document.body).on('click','.force',function(){
		var id = $(this).attr('id');
		var alreadyEnabled = "<?php echo $alreadyEnabled; ?>";
		var doyoureallywanttoReprocessthiscampaign = "<?php echo $doyoureallywanttoReprocessthiscampaign; ?>";
		var ans = confirm(doyoureallywanttoReprocessthiscampaign);
		if(ans)
		{
			$.ajax({
		       type:'POST' ,
		       url: "<?php echo base_url('facebook_ex_autoreply/force_reprocess_campaign')?>",
		       data: {id:id},
		       success:function(response)
		       {
		       	if(response=='1')
		       	$j('#tt').datagrid('reload');
		       	else alert(alreadyEnabled);
		       }
			});

		}
	});

	$(document.body).on('click','.delete_report',function(){
		var Doyouwanttodeletethisrecordfromdatabase = "<?php echo $Doyouwanttodeletethisrecordfromdatabase; ?>";
		var ans = confirm(Doyouwanttodeletethisrecordfromdatabase);
		if(ans)
		{
			var table_id = $(this).attr('table_id');
			$.ajax({
	    	type:'POST' ,
	    	url: base_url+"facebook_ex_autoreply/ajax_autoreply_delete",
	    	data: {table_id:table_id},
	    	// async: false,
	    	success:function(response){
	         	$j('#tt').datagrid('reload');
	    	}

	    });
		}
	});


	$(document.body).on('click','.view_report',function(){
		var loading = '<img src="'+base_url+'assets/pre-loader/custom_lg.gif" class="center-block">';
		$("#view_report_modal_body").html(loading);
		$("#view_report").modal();
		var table_id = $(this).attr('table_id');
		$.ajax({
	    	type:'POST' ,
	    	url: base_url+"facebook_ex_autoreply/ajax_get_reply_info",
	    	data: {table_id:table_id},
	    	// async: false,
	    	success:function(response){
	         	$("#view_report_modal_body").html(response);
	    	}

	    });

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
    	$(this).parent().prev().val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
    });

	
</script>


<div class="modal fade" id="view_report" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center"><?php echo $this->lang->line("report of auto reply") ?></h4>
            </div>
            <div class="modal-body text-center" id="view_report_modal_body">                

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="edit_auto_reply_message_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center"><?php echo $this->lang->line("please give the following information for post auto private reply") ?></h4>
            </div>
            <form action="#" id="edit_auto_reply_info_form" method="post">
	            <input type="hidden" name="edit_auto_reply_page_id" id="edit_auto_reply_page_id" value="">
	            <input type="hidden" name="edit_auto_reply_post_id" id="edit_auto_reply_post_id" value="">
            <div class="modal-body" id="edit_auto_reply_message_modal_body">    
            	<!-- comment hide and delete section -->
            	<div class="row" style="padding: 10px 20px 10px 20px; <?php if(!$commnet_hide_delete_addon) echo "display: none;"; ?> ">
					<div class="col-xs-12">
						<div class="col-xs-5" style="padding: 0px;">
							<label><?php echo $this->lang->line("what do you want about offensive comments?") ?></label>
						</div>
						<div class="col-xs-6">							
							<label class="radio-inline"><input name="edit_delete_offensive_comment" value="hide" id="edit_delete_offensive_comment_hide" class="radio_button" type="radio"><?php echo $this->lang->line("hide") ?></label>
							<label class="radio-inline"><input name="edit_delete_offensive_comment" value="delete" id="edit_delete_offensive_comment_delete" class="radio_button" type="radio"><?php echo $this->lang->line("delete") ?></label>
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
							<label><?php echo $this->lang->line("Do you want to enable comment reply?") ?></label>
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
							<label><?php echo $this->lang->line("message for comment reply") ?>
								 <span class="red">*</span>
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

							<label><?php echo $this->lang->line("message for private reply") ?>
								 <span class="red">*</span>
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
							<label><?php echo $this->lang->line("filter Word/Sentence") ?>
								 <span class="red">*</span>
								<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("message") ?>" data-content="<?php echo $this->lang->line("Write the word or sentence for which you want to filter comment. For multiple filter keyword write comma separated. Example -  why, want to know, when") ?>"><i class='fa fa-info-circle'></i> </a>
							</label>
							<input class="form-control filter_word" type="text" name="edit_filter_word_<?php echo $i; ?>" id="edit_filter_word_<?php echo $i; ?>" placeholder="<?php echo $this->lang->line("write your filter word here") ?>">
							<br/>
							<label><?php echo $this->lang->line("msg for private reply") ?>
								<span class="red">*</span>
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
							<label><?php echo $this->lang->line("msg for comment reply") ?>
								<span class="red">*</span>
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
				                        <div id="edit_filter_image_upload_<?php echo $i; ?>">Upload</div>	     
									</div>
									<div id="edit_generic_image_preview_id_<?php echo $i; ?>"></div>
									<span class="red" id="edit_generic_image_for_comment_reply_error_<?php echo $i; ?>"></span>
									<input type="text" name="edit_filter_image_upload_reply_<?php echo $i; ?>" class="form-control" id="edit_filter_image_upload_reply_<?php echo $i; ?>" placeholder="Put your image url here or click the above upload button" style="margin-top: -14px;" />

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
							<label><?php echo $this->lang->line("Comment reply if no matching found") ?>
								
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
										<a href="#" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("video upload") ?>" data-content="<?php echo $this->lang->line("Image and video will not work together. Please choose either image or video.") ?>"><i class='fa fa-info-circle'></i></a>
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

							<label><?php echo $this->lang->line("private reply if no matching found") ?>
								
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



<style type="text/css">.ajax-upload-dragdrop{width:100% !important;}</style>
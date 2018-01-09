<?php 
	if($this->session->userdata('success_message') == 'success')
	{
		echo "<h4 style='margin:0'><div class='alert alert-success text-center'><i class='fa fa-check-circle'></i> ".$this->lang->line('your account has been imported successfully.')."</div></h4>";
		$this->session->unset_userdata('success_message');
	}

	if($this->session->userdata('limit_cross') != '')
	{
		echo "<h4 style='margin:0'><div class='alert alert-danger text-center'><i class='fa fa-remove'></i> ".$this->session->userdata('limit_cross')."</div></h4>";
		$this->session->unset_userdata('limit_cross');
	}
?>
<style>
	.custom_progress {
	  height: 2px;
	  margin-top: 0px;
	  margin-bottom: 10px;
	  overflow: hidden;
	  background-color: #f5f5f5;
	  border-radius: 4px;
	  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .1);
	          box-shadow: inset 0 1px 2px rgba(0, 0, 0, .1);
	}
	.custom_progress_bar {
	  float: left;
	  width: 0;
	  height: 100%;
	  font-size: 4px;
	  line-height: 6px;
	  color: #fff;
	  text-align: center;
	  background-color: #337ab7;
	  -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .15);
	          box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .15);
	  -webkit-transition: width .6s ease;
	       -o-transition: width .6s ease;
	          transition: width .6s ease;
	}
	.existing_account {
		margin: 10px 0 0;
		font-size: 16px;
		font-weight: bold;
		font-style: italic;
	}
	.account_list{
		padding-left: 5%;
	}
	.individual_account_name{
		font-weight: bold;
		font-size: 14px;
	}
	.padded_ul{
		padding-left: 10%;
	}
	.horizontal_break{
		padding: 2px;
		margin: 0px;
	}

	.info-box-icon {
	    border-top-left-radius: 2px;
	    border-top-right-radius: 0;
	    border-bottom-right-radius: 0;
	    border-bottom-left-radius: 2px;
	    display: block;
	    float: left;
	    height: 55px;
	    width: 90px;
	    text-align: center;
	    font-size: 40px;
	    line-height: 55px;
	    background: rgba(0,0,0,0.2);
	}

	.info-box {
	    display: block;
	    min-height: 50px;
	    background: #fff;
	    width: 100%;
	    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
	    border-radius: 2px;
	    margin-bottom: 15px;
	}
	/* .wrapper,.content-wrapper{background: #fafafa !important;} */
	.well{background: #fff;}

</style>
<div class="clearfix">
	<?php  if($show_import_account_box==0) : ?>
		<br/>
		<div style="padding: 15px;">			
			<div class='alert alert-danger text-center'><i class='fa fa-times-circle'></i> <?php echo $this->lang->line('due to system configuration change you have to delete one or more imported FB accounts and import again. Please check the following accounts and delete the account that has warning to delete.'); ?></div>
		</div>
	<?php  else : ?>
	<div class="" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-center"><i class='fa fa-facebook-official'></i> <?php echo $this->lang->line("add facebook account") ?></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-8">
							<input type="text" class="form-control" placeholder="<?php echo $this->lang->line("enter your facebook numeric id")?>" id="fb_numeric_id" />
							<span class="label label-warning"><a href="http://findmyfbid.com/" target="_blank" style="color: white;"><?php echo $this->lang->line("how to get FB numeric ID?") ?></a></span>
						</div>
						<div class="col-xs-4">
							<button class="btn btn-primary" id="submit"><i class='fa fa-send'></i> <?php echo $this->lang->line("send app request") ?></button>
						</div>

						<div class="col-xs-12" id="response">
							
						</div>

					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<?php endif; ?>


	<?php if($existing_accounts != '0') : ?>
		<div>
			<?php  if($show_import_account_box == 1) { ?>
			<div class="col-xs-12">				
				<h4>
					<div class="well text-center">
						<p><i class="fa fa-facebook-official"></i> <?php echo $this->lang->line("your existing accounts") ?></p>
						<p data-toggle="tooltip" title="<?php echo $this->lang->line("you must be logged in your facebook account for which you want to refresh your access token. for synch your new page, simply refresh your token. if any access token is restricted for any action, refresh your access token.");?>"><?php echo $this->lang->line("refresh your access token") ?> <?php echo $fb_login_button; ?></p>
					</div>
				</h4>
			</div>
			<?php } ?>
			<div class="row" style="padding:0 15px;">
			<?php $i=0; foreach($existing_accounts as $value) : ?>
				<div class="col-xs-12 col-sm-12 col-md-6">
					<div class="box box-primary box-solid">
						<div class="box-header with-border" style="background: #607D8B;">
						<h3 class="box-title">
							<i class="fa fa-facebook"></i> <?php echo $value['name']; ?>							
						</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div><!-- /.box-tools -->
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="col-xs-12" style="height: 450px;overflow-y:auto;">
								<?php
									if($value['need_to_delete'] == 1)
									{
										echo "<div class='alert alert-danger text-center'><i class='fa fa-close'></i> ".$this->lang->line('you have to delete this account.')."</div>";
									} 
								?>
								<?php 
									if($value['validity'] == 'no')
									{
										echo "<div class='alert alert-danger text-center'><i class='fa fa-close'></i> ".$this->lang->line('your login validity has been expired.')."</div>";
									}
								?>
								<div class="row">
									<?php $profile_picture="https://graph.facebook.com/me/picture?access_token={$value['user_access_token']}&width=150&height=150"; ?>
									<div class="text-center col-xs-12 col-sm-12 col-md-6">
										<img src="<?php echo $profile_picture;?>" alt="" class='img-circle'>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6">
										<br/>
										<div class="info-box" style="border:1px solid #00C0EF;border-bottom:2px solid #00C0EF;">
											<span class="info-box-icon bg-aqua"><i class="fa fa-newspaper-o"></i></span>
											<div class="info-box-content">
												<span class="info-box-text"><?php echo $this->lang->line("total pages");?></span>
												<span class="info-box-number"><?php echo number_format($value['total_pages']); ?></span>
											</div><!-- /.info-box-content -->
										</div><!-- /.info-box -->

										<div class="info-box" style="border:1px solid #3C8DBC;border-bottom:2px solid #3C8DBC;">
											<span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
											<div class="info-box-content">
												<span class="info-box-text"><?php echo $this->lang->line("total groups");?></span>
												<span class="info-box-number">
													<?php echo number_format($value['total_groups']); ?>						
												</span>
											</div><!-- /.info-box-content -->
										</div><!-- /.info-box -->
									</div>
									<div class="col-xs-12 text-center">
										<button class="delete_account pull-right btn btn-danger" table_id="<?php echo $value['userinfo_table_id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line("do you want to remove this account from our database? you can import again.");?>"><i class="fa fa-remove"></i> <?php echo $this->lang->line("remove this account") ?></button>
									</div>									
								</div><!-- /.row -->

								<br/>
								<p class="existing_account"><?php echo $this->lang->line("page list") ?></p>
								<div class="custom_progress"><div class="custom_progress_bar" style="width: 70%"></div></div>
								<?php foreach($value['page_list'] as $page_info) : ?>
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-2">
											<img src="<?php echo $page_info['page_profile']; ?>" alt="" class='img-thumbnail'>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6">
											<p><b><?php echo $this->lang->line('name');?> : </b> <?php echo $page_info['page_name']; ?></p>											
											<p><b><?php echo $this->lang->line('email');?> : </b> <?php echo $page_info['page_email']; ?></p>
										</div>
										<!-- <div class="col-xs-12 col-sm-12 col-md-1">
											 
										</div> -->
										<div class="col-xs-12 col-sm-12 col-md-4">
											<i class="fa fa-2x fa-remove red page_delete ptll-left" table_id="<?php echo $page_info['id']; ?>" title="<?php echo $this->lang->line("do you want to remove this page from our database?");?>" data-toggle="tooltip"></i>  
											
												<a href="<?php echo base_url('facebook_rx_insight/page_insight/facebook_rx_fb_page_info/'.$page_info['id']); ?>" class="btn btn-primary pull-right" target="_blank"><i class="fa fa-bar-chart"></i> <?php echo $this->lang->line("analytics") ?></a>
										</div>
									</div>
									<hr class="horizontal_break">
								<?php endforeach; ?>

							</div>

						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div>

			<?php
				$i++;
				if($i%2 == 0)
					echo "</div><div class='row' style='padding:0 15px;'>";
				endforeach;				
			?>
			</div> 
		</div>
	<?php endif; ?>
</div>


<div class="modal fade" id="delete_confirmation" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center"><?php echo $this->lang->line("delete confirmatation") ?></h4>
            </div>
            <div class="modal-body" id="delete_confirmation_body">                

            </div>
        </div>
    </div>
</div>

<?php 
    
    $doyouwanttodelete = $this->lang->line("do you want to delete this group from database?");
    $ifyoudeletethispage = $this->lang->line("if you delete this page, all the campaigns corresponding to this page will also be deleted. Do you want to delete this page from database?");
    $ifyoudeletethisaccount = $this->lang->line("if you delete this account, all the pages, groups and all the campaigns corresponding to this account will also be deleted form database. do you want to delete this account from database?");
    $facebooknumericidfirst = $this->lang->line("please enter your facebook numeric id first");

?>


<script>
	$(document).ready(function(){
	    $('[data-toggle="tooltip"]').tooltip();
	});
	
	$j("document").ready(function() {

		var base_url = "<?php echo base_url(); ?>";

		$(".group_delete").click(function(){
			var doyouwanttodelete = "<?php echo $doyouwanttodelete; ?>";
			var ans = confirm(doyouwanttodelete);
			if(ans)
			{
				$("#delete_confirmation_body").html('<img class="center-block" src="'+base_url+'assets/pre-loader/custom_lg.gif" alt="Processing..."><br/>');
				$("#delete_confirmation").modal();

				var group_table_id = $(this).attr('table_id');
				$.ajax
				({
				   type:'POST',
				   // async:false,
				   url:base_url+'facebook_rx_account_import/ajax_delete_group_action',
				   data:{group_table_id:group_table_id},
				   success:function(response)
				    {
				        $("#delete_confirmation_body").html(response);
				    }
				       
				});

			}
		});


		$(".page_delete").click(function(){
			var ifyoudeletethispage = "<?php echo $ifyoudeletethispage; ?>";
			var ans = confirm(ifyoudeletethispage);
			if(ans)
			{
				$("#delete_confirmation_body").html('<img class="center-block" src="'+base_url+'assets/pre-loader/custom_lg.gif" alt="Processing..."><br/>');
				$("#delete_confirmation").modal();

				var page_table_id = $(this).attr('table_id');
				$.ajax
				({
				   type:'POST',
				   // async:false,
				   url:base_url+'facebook_rx_account_import/ajax_delete_page_action',
				   data:{page_table_id:page_table_id},
				   success:function(response)
				    {
				        $("#delete_confirmation_body").html(response);
				    }
				       
				});

			}
		});


		$(".delete_account").click(function(){
			var ifyoudeletethisaccount = "<?php echo $ifyoudeletethisaccount; ?>";
			var ans = confirm(ifyoudeletethisaccount);
			if(ans)
			{
				$("#delete_confirmation_body").html('<img class="center-block" src="'+base_url+'assets/pre-loader/custom_lg.gif" alt="Processing..."><br/>');
				$("#delete_confirmation").modal();

				var user_table_id = $(this).attr('table_id');
				$.ajax
				({
				   type:'POST',
				   // async:false,
				   url:base_url+'facebook_rx_account_import/ajax_delete_account_action',
				   data:{user_table_id:user_table_id},
				   success:function(response)
				    {
				    	if(response == 'success')
				    	{
				    		var link="<?php echo site_url('home/logout'); ?>"; 
							window.location.assign(link);
				    	}
				    	else
					        $("#delete_confirmation_body").html(response);
				    }
				       
				});

			}
		});


		$('#delete_confirmation').on('hidden.bs.modal', function () { 
			location.reload(); 
		});


		$("#submit").click(function(){
			var facebooknumericidfirst = "<?php echo $facebooknumericidfirst; ?>";
			var fb_numeric_id = $("#fb_numeric_id").val().trim();
			if(fb_numeric_id == '')
			{
				alert(facebooknumericidfirst);
				return false;
			}

			var loading = '<br/><br/><img src="'+base_url+'assets/pre-loader/custom.gif" class="center-block"><br/>';
        	$("#response").html(loading);

			$.ajax
			({
			   type:'POST',
			   // async:false,
			   url:base_url+'facebook_rx_account_import/send_user_roll_access',
			   data:{fb_numeric_id:fb_numeric_id},
			   success:function(response)
			    {
			        $("#response").html(response);
			    }
			       
			});
		});

		
		$(document.body).on('click','#fb_confirm',function(){
			var loading = '<br/><br/><img src="'+base_url+'assets/pre-loader/custom.gif" class="center-block"><br/>';
        	$("#response").html(loading);
			$.ajax
			({
			   type:'POST',
			   // async:false,
			   url:base_url+'facebook_rx_account_import/ajax_get_login_button',
			   data:{},
			   success:function(response)
			    {
			        $("#response").html(response);
			    }
			       
			});
		});


	});
</script>
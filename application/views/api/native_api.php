<?php $this->load->view('admin/theme/message'); ?>
<section class="content-header">
   <section class="content">
	     	<?php
			$text= $this->lang->line("generate API key");
			$get_key_text=$this->lang->line("get your API key");
			if(isset($api_key) && $api_key!="")
			{
				$text=$this->lang->line("re-generate API key");
				$get_key_text=$this->lang->line("your API key");
	   		}
	   		?>

		    <!-- form start -->
		    <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'native_api/get_api_action';?>" method="GET">
		        <div class="box-body" style="padding-top:0;">
		           	<div class="form-group">
		           		<div class="small-box bg-aqua" style="background:#607D8B !important;">
							<div class="inner">
								<h4><?php echo $get_key_text; ?></h4>
								<p>
		   							<h2><?php echo $api_key; ?></h2>
								</p>
								<input name="button" type="submit" class="btn btn-default btn-lg btn" value="<?php echo $text; ?>"/>
							</div>
							<div class="icon">
								<i class="fa fa-key"></i>
							</div>
						</div>
		            </div>

		           </div> <!-- /.box-body -->
		    </form>


		<?php
		if($api_key!="") { ?>
			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#607D8B !important;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("membership expiry alert cron job command [once per day]");?>
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo "curl ".site_url("native_api/send_notification")."/".$api_key; ?>
				</div>
			</div>

			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#607D8B !important;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("auto lead list sync cron job command [once per day]");?>
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo "curl ".site_url("native_api/auto_lead_list_sync")."/".$api_key; ?>
				</div>
			</div>

			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#607D8B !important;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("send inbox messages cron job command [once per minute or higher]");?>
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo "curl ".site_url("native_api/fb_exciter_send_inbox_message")."/".$api_key; ?>
				</div>
			</div>

			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#607D8B !important;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("send auto private reply on comment cron job command [once per five minute or higher]");?>
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo "curl ".site_url("native_api/send_auto_private_reply_on_comment_on_fbexciter")."/".$api_key; ?>
				</div>
			</div>

			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#607D8B !important;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("alert for unread messages cron job command [once per hour or higher]");?>
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo "curl ".site_url("native_api/send_messenger_notification")."/".$api_key; ?>
				</div>
			</div>

			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#607D8B !important;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("CTA poster cron job command [once per hour or higher]");?>
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo "curl ".site_url("native_api/cta_poster_cron_job")."/".$api_key; ?>
				</div>
			</div>

			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;background:#607D8B !important;">
						<i class="fa fa-clock-o"></i> <?php echo $this->lang->line("delete junk data cron job command [once per day]");?>
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo "curl ".site_url("native_api/delete_junk_data")."/".$api_key; ?>
				</div>
			</div>
		<?php }?>
		<!-- seperator****************************************************** -->


   </section>
</section>

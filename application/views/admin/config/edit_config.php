<?php $this->load->view('admin/theme/message'); ?>
<section class="content-header">
   <section class="content">
     	<div class="box box-info custom_box">
		    	<div class="box-header">
		         <h3 class="box-title"><i class="fa fa-cogs"></i> <?php echo $this->lang->line("general settings");?></h3>
		        </div><!-- /.box-header -->
		       		<!-- form start -->
		    <form class="form-horizontal text-c" enctype="multipart/form-data" action="<?php echo site_url().'admin_config/edit_config';?>" method="POST">
		        <div class="box-body">
		           	<div class="form-group">
		              	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("company name");?>
		              	</label>
		                	<div class="col-xs-12 col-sm-12 col-md-6">
		               			<input name="institute_name" value="<?php echo $this->config->item('institute_address1');?>"  class="form-control" type="text">		               
		             			<span class="red"><?php echo form_error('institute_name'); ?></span>
		             		</div>
		            </div>
		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("company address");?>
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">
	               			<input name="institute_address" value="<?php echo $this->config->item('institute_address2');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('institute_address'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("company email");?> *
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">
	               			<input name="institute_email" value="<?php echo $this->config->item('institute_email');?>"  class="form-control" type="email">		          
	             			<span class="red"><?php echo form_error('institute_email'); ?></span>
	             		</div>
		           </div>  


		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("company phone/ mobile");?>
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">
	               			<input name="institute_mobile" value="<?php echo $this->config->item('institute_mobile');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('institute_mobile'); ?></span>
	             		</div>
		           </div>

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("Slogan");?> 
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">
	               			<input name="slogan" value="<?php echo $this->config->item('slogan');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('slogan'); ?></span>
	             		</div>
		           </div>

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("product");?> 
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">
	               			<input name="product_name" value="<?php echo $this->config->item('product_name');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('product_name'); ?></span>
	             		</div>
		           </div>

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("product short name");?> 
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">
	               			<input name="product_short_name" value="<?php echo $this->config->item('product_short_name');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('product_short_name'); ?></span>
	             		</div>
		           </div>

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("logo");?>
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6" >
		           			<div class='text-center' style="padding:10px;"><img class="img-responsive" src="<?php echo base_url().'assets/images/logo.png';?>" alt="Logo"/></div>
	               			<?php echo $this->lang->line("Max Dimension");?> : 600 x 300, <?php echo $this->lang->line("Max Size");?> : 200KB,  <?php echo $this->lang->line("Allowed Format");?> : png
	               			<input name="logo" class="form-control" type="file">		          
	             			<span class="red"> <?php echo $this->session->userdata('logo_error'); $this->session->unset_userdata('logo_error'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("favicon");?>
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">
	             			<div class='text-center'><img class="img-responsive" src="<?php echo base_url().'assets/images/favicon.png';?>" alt="Favicon"/></div>
	               			 <?php echo $this->lang->line("Max Dimension");?> : 32 x 32, <?php echo $this->lang->line("Max Size");?> : 50KB, <?php echo $this->lang->line("Allowed Format");?> : png

	               			<input name="favicon"  class="form-control" type="file">		          
	             			<span class="red"><?php echo $this->session->userdata('favicon_error'); $this->session->unset_userdata('favicon_error'); ?></span>
	             		</div>
		           </div> 

		           	<div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("language");?>
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">	             			
	               			<?php
							$select_lan="english";
							if($this->config->item('language')!="") $select_lan=$this->config->item('language');
							echo form_dropdown('language',$language_info,$select_lan,'class="form-control" id="language"');  ?>		          
	             			<span class="red"><?php echo form_error('language'); ?></span>
	             		</div>
		           </div>

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("theme");?> 
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">	             			
	               			<?php 
	               			$select_theme="skin-black-light";
							if($this->config->item('theme')!="") $select_theme=$this->config->item('theme');
							echo form_dropdown('theme',$themes,$select_theme,'class="form-control" id="theme"');  ?>		          
	             			<span class="red"><?php echo form_error('theme'); ?></span>
	             		</div>
		           </div>


		        
		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("time zone");?>
		             	</label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">	             			
	               			<?php	$time_zone['']=$this->lang->line('time zone');
							echo form_dropdown('time_zone',$time_zone,$this->config->item('time_zone'),'class="form-control" id="time_zone"');  ?>		          
	             			<span class="red"><?php echo form_error('time_zone'); ?></span>
	             		</div>
		           </div> 


		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for="backup_mode" style="margin-top: -7px;"><?php echo $this->lang->line('give access to user to set their own facebook app');?></label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">	             			
	               			<?php	
	               			$backup_mode = $this->config->item('backup_mode');
	               			if($backup_mode == 1) $selected = 'yes';
	               			else $selected = 'no';
	               			$user_access['no']=$this->lang->line('no');
	               			$user_access['yes']=$this->lang->line('yes');
							echo form_dropdown('backup_mode',$user_access,$selected,'class="form-control" id="backup_mode"');  ?>		          
	             			<span class="red"><?php echo form_error('backup_mode'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for="display_landing_page" style="margin-top: -7px;"><?php echo $this->lang->line('display landing page');?></label>
	             		<div class="col-xs-12 col-sm-12 col-md-6">	             			
	               			<?php	
	               			$display_landing_page = $this->config->item('display_landing_page');
	               			if($display_landing_page == '') $display_landing_page='0';
							echo form_dropdown('display_landing_page',array('0'=>$this->lang->line('no'),'1'=>$this->lang->line('yes')),$display_landing_page,'class="form-control" id="display_landing_page"');  ?>		          
	             			<span class="red"><?php echo form_error('display_landing_page'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("number of message send per cron job");?> 
		             	</label>
	             		<div class="col-sm-9 col-md-4 col-lg-6">
	             			<?php 
		             			$number_of_message_to_be_sent_in_try=$this->config->item('number_of_message_to_be_sent_in_try');
		             			if($number_of_message_to_be_sent_in_try=="") $number_of_message_to_be_sent_in_try=0; 
	             			?>
	               			<input name="number_of_message_to_be_sent_in_try" value="<?php echo $number_of_message_to_be_sent_in_try;?>"  class="form-control" type="number" min="0">		          
	             			<span><?php echo $this->lang->line('0 means unlimited');?></span><br>
	             			<span class="red"><?php echo form_error('number_of_message_to_be_sent_in_try'); ?></span>
	             		</div>
		           </div>

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("message sending report update frequency");?> 
		             	</label>
	             		<div class="col-sm-9 col-md-4 col-lg-6">
	             			<?php 
		             			$update_report_after_time=$this->config->item('update_report_after_time');
		             			if($update_report_after_time=="") $update_report_after_time=10; 
	             			?>
	               			<input name="update_report_after_time" value="<?php echo $update_report_after_time;?>"  class="form-control" type="number" min="1">		          
	             			<span class="red"><?php echo form_error('update_report_after_time'); ?></span>
	             		</div>
		           </div>


		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("delay used in auto-reply (seconds)");?> 
		             	</label>
	             		<div class="col-sm-9 col-md-4 col-lg-6">
	             			<?php 
		             			$auto_reply_delay_time=$this->config->item('auto_reply_delay_time');
		             			if($auto_reply_delay_time=="") $auto_reply_delay_time=10; 
	             			?>
	               			<input name="auto_reply_delay_time" value="<?php echo $auto_reply_delay_time;?>"  class="form-control" type="number" min="1">		          
	             			<span class="red"><?php echo form_error('auto_reply_delay_time'); ?></span>
	             		</div>
		           </div>

		           <div class="form-group">
		             	<label class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 control-label" for=""><?php echo $this->lang->line("auto-reply campaign live duration (days)");?> 
		             	</label>
	             		<div class="col-sm-9 col-md-4 col-lg-6">
	             			<?php 
		             			$auto_reply_campaign_live_duration=$this->config->item('auto_reply_campaign_live_duration');
		             			if($auto_reply_campaign_live_duration=="") $auto_reply_campaign_live_duration=20; 
	             			?>
	               			<input name="auto_reply_campaign_live_duration" value="<?php echo $auto_reply_campaign_live_duration;?>"  class="form-control" type="number" min="1">		          
	             			<span class="red"><?php echo form_error('auto_reply_campaign_live_duration'); ?></span>
	             		</div>
		           </div>

		     

		           
		         		               
		           </div> <!-- /.box-body --> 

		           	<div class="box-footer">
		            	<div class="form-group">
		             		<div class="col-sm-12 text-center">
		               			<input name="submit" type="submit" class="btn btn-warning btn-lg" value="<?php echo $this->lang->line("Save");?>"/>  
		              			<input type="button" class="btn btn-default btn-lg" value="<?php echo $this->lang->line("Cancel");?>" onclick='goBack("admin_config",1)'/>  
		             		</div>
		           		</div>
		         	</div><!-- /.box-footer -->         
		        </div><!-- /.box-info -->       
		    </form>     
     	</div>
   </section>
</section>




<?php $this->load->view('admin/theme/message'); ?>

<?php $cur_month=date("n"); ?>
<?php $cur_year=date("Y"); ?>
<?php 
if($cur_month==1) $month="";
else if($cur_month==2) $month="cal_jan";
else if($cur_month==3) $month="cal_feb";
else if($cur_month==4) $month="cal_ma";
else if($cur_month==5) $month="cal_apr";
else if($cur_month==6) $month="cal_may";
else if($cur_month==7) $month="cal_jun";
else if($cur_month==8) $month="cal_jul";
else if($cur_month==9) $month="cal_aug";
else if($cur_month==10) $month="cal_sep";
else if($cur_month==11) $month="cal_oct";
else if($cur_month==12) $month="cal_nov";

$unlimited_module_array=array(13,14,16,17,24,26,27,33,38,39,40,41,42,43,44,45,46,47,48,49,52,53,55,57,59,60,61,62,63,77,78,82,83);
?>

<!-- Main content -->
<section class="content-header">
	<h1 class = 'text-info'> <?php echo $this->lang->line("usage log");?> : <?php echo $this->lang->line($month)."-".$cur_year ?></h1>
</section>
<section class="content">  
	<div class="row" >
		<div class="col-xs-12">		

			
			<div class="grid_container well table-responsive" style="width:auto;background:#fff;border:1px solid #ccc;padding:20px">
				<h3 class='text-center'>
					<div class="well">			
				 	   <?php if($price=="Trial") $price=0; ?>
					   <?php echo $this->lang->line("package name")?> : 
					   <?php echo $package_name;?> @
					   <?php echo $payment_config[0]['currency']; ?> <?php echo $price;?> /
					   <?php echo $validity;?> <?php echo $this->lang->line("days")?>	<br/><br/>
					   <?php echo $this->lang->line("expired date");?> : <?php echo date("Y-m-d",strtotime($this->session->userdata("expiry_date"))); ?>			
					</div>
				</h3>	
				<table class="table table-bordered">
	               		<tr class="warning">
	               			<th></th>
	               			<th><?php echo $this->lang->line("Modules");?></th>
	               			<th class="text-center"><?php echo $this->lang->line("Monthly Request Limit");?></th>
	               			<th class="text-center"><?php echo $this->lang->line("Request Done");?></th>
	               			<!-- <th class="text-center"><?php echo $this->lang->line("Bulk Limit");?></th> -->
	               		</tr>
	               	 	<?php 
	               	 	$i=0;
	               	 	foreach($info as $row)
	               	 	{
		               	 	$i++;
		               	 	$row_class="";
		               	 	if(in_array($row["module_id"],$this->module_access)) $row_class="allowed";
		               	 	echo "<tr class='".$row_class."'>";
		               	 		echo "<td class='text-center'>";
			               	 		echo $i;
			               	 	echo "</td>";
			               	 	echo "<td>";
			               	 		echo $this->lang->line($row["module_name"]);
			               	 	echo "</td>";

			               	 	$str="";
		               	 		if(!in_array($row["module_id"],$this->module_access)) // no access and skip
		               	 		{
		               	 			$str="<i class='fa fa-remove'></i> No access";
		               	 			echo "<td colspan='3' class='text-center'>{$str}</td>";
			               	 		echo "</tr>";
			               	 		continue;
		               	 		}
		               	 	
			               	 			               	 		
			               	 	if(in_array($row["module_id"], $unlimited_module_array))
			               	 	{
			               	 		echo "<td class='text-center'>Unlimited</td>";
			               	 	}
		               	 		else
		               	 		{
		               	 			echo "<td class='text-center'>";
		               	 			if($monthly_limit[$row["module_id"]]=="0") $monthly_limit[$row["module_id"]]=$this->lang->line("unlimited");
		               	 			if(isset($monthly_limit[$row["module_id"]])) echo $monthly_limit[$row["module_id"]];
		               	 			echo "</td>";
		               	 		}



			               	 	echo "<td class='text-center' >";
			               	 		
			               	 		if($row["module_id"]=="1")
				               	 	{
				               	 		$visitor_limit=0;
				               	 		$visitor_analysis_limit=array();
				               	 		$visitor_analysis_limit=$this->basic->execute_query("SELECT COUNT(id) as visitor_analysis_limit FROM domain WHERE user_id=".$this->session->userdata("user_id"));
				               	 	    
				               	 	    if(isset($visitor_analysis_limit[0]["visitor_analysis_limit"])) $visitor_limit=$visitor_analysis_limit[0]["visitor_analysis_limit"];
				               	 	    
				               	 	    echo $visitor_limit;
				               	 	}
				               	 	else if(in_array($row["module_id"], $unlimited_module_array))
				               	 	{
				               	 		echo "N/A";
				               	 	}
			               	 		else
			               	 		{
			               	 			if($str!="") echo $str;
				               	 		else
				               	 		{
				               	 			if(isset($row["usage_count"])) echo $row["usage_count"];
				               	 			else echo "0";
				               	 		}
			               	 		}
			               	 	echo "</td>";



		               	 		// if(!in_array($row["module_id"], array(3,4,5,6,7,8,9,10,15,18,21,22,58)))
			               	 	// {
			               	 	// 	echo "<td class='text-center'>Unlimited</td>";
			               	 	// }
		               	 		// else
		               	 		// {	
		               	 		// 	echo "<td class='text-center'>";
		               	 		// 	if(isset($bulk_limit[$row["module_id"]])) 
		               	 		// 	{
		               	 		// 		if($bulk_limit[$row["module_id"]]=="0") $bulk_limit[$row["module_id"]]=$this->lang->line("unlimited");
		               	 		// 		echo $bulk_limit[$row["module_id"]];
		               	 		// 	}
		               	 		// 	echo "</td>";
		               	 		// }



		               	 	echo "</tr>";
	               	 	} 
	               	 	?>
	              </table>                      
			</div>

		</div>        
	</div> 
</section>

<style>
	.allowed td,.allowed th{font-weight: bold;font-size:14px;background: #fcfcfc;}
</style>
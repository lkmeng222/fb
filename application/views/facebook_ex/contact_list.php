<?php $this->load->view('admin/theme/message'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><i class='fa fa-user'></i> <?php echo $this->lang->line('lead list'); ?>  </h1>

</section>

<!-- Main content -->
<section class="content">  
  <div class="row" >
    <div class="col-xs-12">
        <div class="grid_container" style="width:100%; min-height:880px;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."facebook_ex_import_lead/contact_list_data"; ?>" 

            pagination="true" 
            rownumbers="true" 
            toolbar="#tb" 
            pageSize="20" 
            pageList="[5,10,20,50,100,500]"  
            fit= "true" 
            fitColumns= "true" 
            nowrap= "true" 
            view= "detailview"
            idField="id"
            >

            <!-- url is the link to controller function to load grid data -->
            
                <thead>
                    <tr>
                        <th field="id"  checkbox="true"></th>
                        <th field="client_username"  sortable="true" ><?php echo $this->lang->line('Name'); ?></th>                  
                        <th field="contact_type_id"  sortable="true"><?php echo $this->lang->line('lead group'); ?></th>                     
                        <th field="page_name"  sortable="true"><?php echo $this->lang->line('Page Name'); ?></th>                     
                        <th field="permission" align="center" formatter="yes_no"  sortable="true"><?php echo $this->lang->line('subscribed?'); ?></th>
                        <th field="view" formatter='action_column'><?php echo $this->lang->line('action'); ?></th>
                    </tr>
                </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">

            <a class="btn btn-info" id="assign_group">
                <i class="fa fa-group"></i> <?php echo $this->lang->line("bulk group assign"); ?>
            </a>
            <a class="btn btn-danger pull-right" id="bulk_delete_contact">
                <i class="fa fa-trash"></i> <?php echo $this->lang->line("bulk delete contact"); ?>
            </a> 
            <br/>  
              
            <form class="form-inline" style="margin-top:20px">
                <div class="form-group">
                    <input id="client_username" name="client_username" value="<?php echo $this->session->userdata('fb_ex_contact_list_first_name'); ?>" class="form-control" size="20" placeholder="<?php echo $this->lang->line('Name'); ?>">
                </div>  
                <div class="form-group">
                    <?php 
                        $contact_type_id['']=$this->lang->line('all groups');
                        echo form_dropdown('contact_type_id',$contact_type_id,$this->session->userdata('fb_ex_contact_list_contact_type_id'),'class="form-control" id="contact_type_id"');  
                        ?>
                </div>  
                <div class="form-group">
                    <?php 
                        $subscribed_array['']=$this->lang->line('Subscribed & Unsubscibed');
                        $subscribed_array["1"] = $this->lang->line('Only Subscribed');
                        $subscribed_array["0"] = $this->lang->line('Only Unsubscribed');
                        echo form_dropdown('permission_search',$subscribed_array,trim($this->session->userdata('fb_ex_contact_list_permission_search')),'class="form-control" id="permission_search"');  
                        ?>
                </div>  
            
                <select name="search_page" id="search_page"  class="form-control">
                  <option value=""><?php echo $this->lang->line("all pages");?></option>  
                  <?php
                    $search_page_id  = $this->session->userdata('fb_ex_contact_list_search_page');
                    foreach ($page_info as $key => $value) 
                    {
                      if($value['page_id'] == $search_page_id)
                      echo "<option selected value='".$value['page_id']."'>".$value['page_name']."</option>";
                      else echo "<option value='".$value['page_id']."'>".$value['page_name']."</option>";
                    }
                  ?>            
                </select>
                
                <button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line('Search'); ?></button>     
                      
            </form> 

        </div>        
    </div>
  </div>   
</section>

<?php 
    
    $doyouwanttodeletethiscontact = $this->lang->line("do you want to delete this contact?");
    $youhavenotselected = $this->lang->line("you have not selected any lead to assign group. you can choose upto");
    $leadsatatime = $this->lang->line("leads at a time.");
    $youcanselectupto = $this->lang->line("you can select upto");
    $leadsyouhaveselected = $this->lang->line("leads. you have selected");
    $leads = $this->lang->line("leads.");

    $youhavenotselectedany = $this->lang->line("you have not selected any lead to delete. you can choose upto");
    $leadsatatime = $this->lang->line("leads at a time.");
    $youhavenotselectedanyleadtoassigngroup = $this->lang->line("you have not selected any lead to assign group.");
    $youhavenotselectedanyleadgroup = $this->lang->line("you have not selected any lead group.");
    $pleasewait = $this->lang->line("please wait");
    $groupshavebeenassignedsuccessfully = $this->lang->line("groups have been assigned successfully.");
    $youcanselectupto = $this->lang->line("you can select upto");
    $leadsyouhaveselected = $this->lang->line("leads. You have selected");
    $leads = $this->lang->line("leads.");
    $contactshavebeendeletedsuccessfully = $this->lang->line("Contacts have been deleted successfully.");
    $export = $this->lang->line("export");
    $youhavenotselectanycontact = $this->lang->line("You have not select any contact.");
    $download = $this->lang->line("download");
    $somethingwentwrongpleasetryagain = $this->lang->line("something went wrong, please try again.");

?>


<script> 

	  $j(function() {
      $( ".datepicker" ).datepicker();
    });     
    var base_url="<?php echo site_url(); ?>";     

    function action_column(value,row,index)
    {             
               
        var edit_url=base_url+'facebook_ex_import_lead/update_contact/'+row.id;
        
        var str="";     
      
        str=str+"&nbsp;<a style='cursor:pointer' title='"+'Edit'+"' href='"+edit_url+"'>"+' <img src="<?php echo base_url("plugins/grocery_crud/themes/flexigrid/css/images/edit.png");?>" alt="Edit">'+"</a>";

        str=str+"&nbsp;<a style='cursor:pointer' title='"+'Delete'+"' class='delete_contact' table_id='"+row.id+"' >"+' <img src="<?php echo base_url("plugins/grocery_crud/themes/flexigrid/css/images/close.png");?>" alt="Delete">'+"</a>&nbsp;";     
        
        return str;
    } 

    $(document.body).on('click','.delete_contact',function(){
      var doyouwanttodeletethiscontact = "<?php echo $doyouwanttodeletethiscontact;?>";
      var ans = confirm(doyouwanttodeletethiscontact);
      if(ans)
      {
        var table_id = $(this).attr('table_id');
        var delete_url=base_url+'facebook_ex_import_lead/delete_contact_action';
        $.ajax({
          type:'POST' ,
          url: delete_url,
          data:{table_id:table_id},
          success:function(response)
          {
            $j('#tt').datagrid('reload');
          }
        });
      }
    });


    $(document.body).on('click','#assign_group',function(){
          var rows = $j('#tt').datagrid('getSelections');
          var info=JSON.stringify(rows);  
          var info_array = JSON.parse(info);
          var selected = info_array.length;
          var upto = 500;

          var youhavenotselected = "<?php echo $youhavenotselected;?>";
          var leadsatatime = "<?php echo $leadsatatime;?>";
          var youcanselectupto = "<?php echo $youcanselectupto;?>";
          var leadsyouhaveselected = "<?php echo $leadsyouhaveselected;?>";
          var leads = "<?php echo $leads;?>";

          if(rows=="") 
          {
            alert(youhavenotselected+" "+upto+" "+leadsatatime);
            return;
          } 
          if(selected>upto) 
          {
              alert(youcanselectupto+" "+upto+" "+leadsyouhaveselected+" "+selected+" "+leads)
              return;
          }

        $("#assign_group_modal").modal();         
    }); 

    $(document.body).on('click','#assign_group_submit',function(){
  
          var rows = $j('#tt').datagrid('getSelections');
          var info=JSON.stringify(rows);  
          var youhavenotselectedanyleadtoassigngroup = "<?php echo $youhavenotselectedanyleadtoassigngroup; ?>";
          var youhavenotselectedanyleadgroup = "<?php echo $youhavenotselectedanyleadgroup; ?>";
          var pleasewait = "<?php echo $pleasewait; ?>";
          var groupshavebeenassignedsuccessfully = "<?php echo $groupshavebeenassignedsuccessfully; ?>";
          if(rows=="") 
          {
            alert(youhavenotselectedanyleadtoassigngroup);
            return;
          } 
          var count=0;
          var group_id=[];
          $('.contact_group_id:checked').each(function () {
            group_id[count]=$(this).val();
              count++;
          });

          if(count==0) 
          {
            alert(youhavenotselectedanyleadgroup);
            return;
          } 

          $("#assign_group_submit").html("Please wait...");
          $("#assign_group_submit").addClass("disabled");
          $("#assign_group_message").removeClass("alert alert-success").html(pleasewait+"...").show();

          $.ajax({
            type:'POST' ,
            url: "<?php echo site_url(); ?>facebook_ex_import_lead/bulk_group_assign",
            data:{info:info,group_id:group_id},
            success:function(response)
            {
             $("#assign_group_submit").html("Assign Group");
             $("#assign_group_submit").removeClass("disabled");
             $("#assign_group_message").addClass("alert alert-success").html(groupshavebeenassignedsuccessfully).show();
              location.reload();
            }
          });         
    });

    


    $(document.body).on('click','#bulk_delete_contact',function(){
        var rows = $j('#tt').datagrid('getSelections');
        var info=JSON.stringify(rows);  
        var info_array = JSON.parse(info);
        var selected = info_array.length;
        var upto = 500;
        var youhavenotselectedany = "<?php echo $youhavenotselectedany;?>";
        var leadsatatime = "<?php echo $leadsatatime;?>";
        var youcanselectupto = "<?php echo $youcanselectupto;?>";
        var leadsyouhaveselected = "<?php echo $leadsyouhaveselected;?>";
        var leads = "<?php echo $leads;?>";
        if(rows=="") 
        {
          alert(youhavenotselectedany+" "+upto+" "+leadsatatime);
          return;
        } 
        if(selected>upto) 
        {
            alert(youcanselectupto+" "+upto+" "+leadsyouhaveselected+" "+selected+" leads."+leads);
            return;
        }

        $("#selected_contacts_count").html(selected);
        $("#delete_contact_message").hide();
        $("#delete_contact_modal").modal();         
    }); 

    $(document.body).on('click','#delete_contact_submit',function(){
  
          var rows = $j('#tt').datagrid('getSelections');
          var info=JSON.stringify(rows); 
          var pleasewait = "<?php echo $pleasewait; ?>"; 
          var contactshavebeendeletedsuccessfully = "<?php echo $contactshavebeendeletedsuccessfully; ?>"; 
          if(rows=="") 
          {
            alert("You have not selected any lead to delete.");
            return;
          } 
          $("#delete_contact_submit").html("Please wait...");
          $("#delete_contact_submit").addClass("disabled");
          $("#delete_contact_message").removeClass("alert alert-success").html(pleasewait+"...").show();

          $.ajax({
            type:'POST' ,
            url: "<?php echo site_url(); ?>facebook_ex_import_lead/delete_bulk_contacts",
            data:{info:info},
            success:function(response)
            {
             $("#delete_contact_submit").html("Delete Contacts");
             $("#delete_contact_submit").removeClass("disabled");
             $("#delete_contact_message").addClass("alert alert-success").html(contactshavebeendeletedsuccessfully).show();
              // $j('#tt').datagrid('reload');
              location.reload();
            }
          });         
    });




    $("#url_with_email_wise_download_btn").click(function(){
    var base_url="<?php echo base_url(); ?>";
    var pleasewait = "<?php echo $pleasewait; ?>";
    var download = "<?php echo $download; ?>";
    var youhavenotselectanycontact = "<?php echo $youhavenotselectanycontact; ?>";
    var somethingwentwrongpleasetryagain = "<?php echo $somethingwentwrongpleasetryagain; ?>";
    $('#url_with_email_wise_download_btn').html(pleasewait);
    var link = "<?php echo site_url('phonebook/url_with_email_wise_download');?>";
    var rows = $j("#tt").datagrid("getSelections");
    var info=JSON.stringify(rows); 
    if(rows == '')
    {
      $('#url_with_email_wise_download_btn').html('<i class="fa fa-cloud-download"></i>export');
      alert(youhavenotselectanycontact);
      return false;
    }
    $.ajax({
      type:'POST',
      url:link,
      data:{info:info},
      success:function(response)
      {
        if(response!="")         
        {
          response=base_url+response;
          $('#url_with_email_wise_download_btn').html('<i class="fa fa-cloud-download"></i>export');
          $('#download_content').html('<i class="fa fa-2x fa-thumbs-o-up" style="color:black"></i><br><br><a href="'+response+'" title="Download" class="btn btn-warning btn-lg" style="width:200px;""><i class="fa fa-cloud-download" style="color:white"></i> '+download);
          $('#modal_for_download_url').modal();  
        }      
        else         
        alert(somethingwentwrongpleasetryagain);     
      }
    });
  });   

  function valid_date(value,row,index)
  {
     if(value=="0000-00-00") return "";
     return value;
  }          
   
    function doSearch(event)
    {
        event.preventDefault(); 
        $j('#tt').datagrid('load',{
          client_username  :     $j('#client_username').val(),         
          contact_type_id  :     $j('#contact_type_id').val(),         
          permission_search  :     $j('#permission_search').val(),         
          search_page  :     $j('#search_page').val(),         
          is_searched      :      1
        });


    }  

</script>

<!-- Modal for download -->
<div id="modal_for_download_url" class="modal fade">
  <div class="modal-dialog" style="width:65%;">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&#215;</span>
        </button>
        <h4 id="" class="modal-title"><i class="fa fa-cloud-download"></i> <?php echo $this->lang->line('export contact (CSV)'); ?></h4>
      </div>

      <div class="modal-body">
        <style>
        .box
        {
          border:1px solid #ccc;  
          margin: 0 auto;
          text-align: center;
          margin-top:10%;
          padding-bottom: 20px;
          background-color: #fffddd;
          color:#000;
        }
        </style>
        <!-- <div class="container"> -->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
              <div class="box">
                <h2><?php echo $this->lang->line('Your file is ready to download'); ?></h2>
                <span id="download_content"></span>
              </div>    
              
            </div>
          </div>
        <!-- </div>  -->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="assign_group_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-group"></i> <?php echo $this->lang->line("select lead group"); ?></h4>
      </div>
      <div class="modal-body">    
          <div id="assign_group_message" class="text-center"></div> 
          <div>              
           <?php
           foreach ($contact_type_id as $key=>$value) 
           {
               $type =  $value;            
               $type_id = $key;
               if($key=="") continue;
               echo "<label class='checkbox-inline'><input type='checkbox' class='contact_group_id' name='contact_group_id[]' value='{$type_id}'>{$type}</label><br/>";
             }

           ?>
          </div>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary pull-left" id="assign_group_submit"><?php echo $this->lang->line("assign group") ?></a>
        <a class="btn btn-default pull-right" data-dismiss="modal"><?php echo $this->lang->line("cancel") ?></a>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="delete_contact_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-group"></i> <?php echo $this->lang->line("bulk delete contact confirmation") ?></h4>
      </div>
      <div class="modal-body">
        <h3 class="text-center alert alert-warning" id="delete_display_message"><div><?php echo $this->lang->line("do you want to delete") ?> <span id="selected_contacts_count"></span> <?php echo $this->lang->line("contacts from database?") ?></div></h3>
        <br/><br/>
        <div id="delete_contact_message" class="text-center"></div> 
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary pull-left" id="delete_contact_submit"><?php echo $this->lang->line("delete contacts") ?> </a>
        <a class="btn btn-default pull-right" data-dismiss="modal"><?php echo $this->lang->line("cancel") ?></a>
      </div>
    </div>
  </div>
</div>
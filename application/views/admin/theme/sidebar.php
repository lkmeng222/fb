<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->  

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <!-- <li class="header"></li>  -->
        <?php
        $colorpos=strpos($loadthemebody,'light');
        if($colorpos!==FALSE) $colorclass='orange';
        else $colorclass='';
        ?>     
        <?php
          foreach($menus as $single_menu) 
          {
              if($single_menu['id']==2 && $this->config->item('backup_mode')==='0' && $this->session->userdata('user_type')=='Member') continue; // static condition not to show app settings to memeber if backup mode = 0

              $only_admin = $single_menu['only_admin'];
              $only_member = $single_menu['only_member']; 
              $module_access = explode(',', $single_menu['module_access']);
              $module_access = array_filter($module_access);

              if($single_menu['is_external']=='1') $site_url1=""; else $site_url1=site_url(); // if external link then no need to add site_url()
              if($single_menu['is_external']=='1') $parent_newtab=" target='_BLANK'"; else $parent_newtab=''; // if external link then open in new tab
              $menu_html = "<li> <a {$parent_newtab} href='".$site_url1.$single_menu['url']."'> <i style='font-size:18px' class='".$colorclass.' '.$single_menu['icon']."'></i> &nbsp;&nbsp;<span>" . $this->lang->line($single_menu['name'])."</span>";   

              if(isset($menu_child_1_map[$single_menu['id']]) && count($menu_child_1_map[$single_menu['id']]) > 0)
              {
                $menu_html .= "<i class='fa fa-angle-left pull-right'></i>";
                $menu_html .= "</a>";
                $menu_html .= "<ul class='treeview-menu'>";
                foreach($menu_child_1_map[$single_menu['id']] as $single_child_menu)
                {                  
                    if($single_child_menu['url']=="messenger_bot_config/index" && $this->config->item('backup_mode')==='0' && $this->session->userdata('user_type')=='Member') continue; // static condition not to show app settings to memeber if backup mode = 0

                    if($single_child_menu['is_external']=='1') $site_url2=""; else $site_url2=site_url(); // if external link then no need to add site_url()
                    if($single_child_menu['is_external']=='1') $child_newtab=" target='_BLANK'"; else $child_newtab=''; // if external link then open in new tab
                    $menu_html .= "<li><a {$child_newtab} href='".$site_url2.$single_child_menu['url']."'><i class='".$colorclass.' '.$single_child_menu['icon']."'></i> ".$this->lang->line($single_child_menu['name']);

                    if(isset($menu_child_2_map[$single_child_menu['id']]) && count($menu_child_2_map[$single_child_menu['id']]) > 0)
                    {
                      $menu_html .= "<i class='fa fa-angle-left pull-right'></i>";
                      $menu_html .= "</a>";
                      $menu_html .= "<ul class='treeview-menu'>";
                      foreach($menu_child_2_map[$single_child_menu['id']] as $single_child_menu_2)
                      {                  
                         if($single_child_menu_2['is_external']=='1') $site_url3=""; else $site_url3=site_url(); // if external link then no need to add site_url()
                         if($single_child_menu_2['is_external']=='1') $child2_newtab=" target='_BLANK'"; else $child2_newtab=''; // if external link then open in new tab                   
                         $menu_html .= "<li><a {$child2_newtab} href='".$site_url3.$single_child_menu_2['url']."'><i class='".$colorclass.' '.$single_child_menu_2['icon']."'></i> ".$this->lang->line($single_child_menu_2['name'])."</a></li>";
                      }
                      $menu_html .= "</ul>";
                    }
                    else
                    {
                      $menu_html .= "</a>";
                    }

                    $menu_html .= "</li>";
                }
                $menu_html .= "</ul>";
              }
              else
              {
                $menu_html .= "</a>";
              }

              $menu_html .= "</li>";
              if($only_admin == '1') 
              {
                if($this->session->userdata('user_type') == 'Admin') 
                echo $menu_html;
              }
              else if($only_member == '1') 
              {
                if($this->session->userdata('user_type') == 'Member') 
                echo $menu_html;
              } 
              else 
              {
                if($this->session->userdata("user_type")=="Admin" || empty($module_access) || count(array_intersect($this->module_access, $module_access))>0 ) 
                echo $menu_html;
              } 
        ?>
          
        <?php 
          }
        ?>

     <li style="margin-bottom:200px">&nbsp;</li>

   </ul>
 </section>
 <!-- /.sidebar -->
</aside>
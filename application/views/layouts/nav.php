<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
		<img src="<?php echo base_url().'assets/'; ?>dist/img/logo.png" height="60">
      </div>
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <!--<li class="header">MAIN MENU</li>-->
		<?php foreach($user_menus as $user_menu){
			echo $user_menu->html;
		}?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <header class="main-header">
    
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
	  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
		
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
		<span style="font-size:12pt;padding-top:20px;" class="pull-left"><p style="word-wrap:break-word;">Welcome, <?php echo $_SESSION['name']; ?></p></span>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning" id="cto_notification_exist"></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header"></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" id="cto_notification_dropdown">
                  
                </ul>
              </li>
              <li class="footer"><a href="#"></a></li>
            </ul>
          </li>
		<li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs"><?php if($_SESSION['position'] != ""){echo $_SESSION['position'];}else{ echo "No Position";} ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- Menu Footer-->
              <li class="user-footer">
                <!--<div class="pull-left">
                  <a href="<?php echo base_url(); ?>cto_user/edit/<?php echo $_SESSION['id']; ?>" class="btn btn-default btn-flat">Edit Profile</a>
                </div>-->
                <div class="pull-right">
                  <a href="<?php echo base_url().'cto_user/logout'; ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
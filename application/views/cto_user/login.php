<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Budiah | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>plugins/iCheck/square/blue.css">
  <!-- Cto_loading_animation -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/cto/dist/css/cto_loadinganimation.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <b>b</b>udiah
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to enter budiah</p>
	<!-- messages box-->
	<div class="" id="cto_messages">
		
    </div>
	<!-- /.messages box-->
	
    <form id="cto_login_form" method="post">
      <div class="form-group has-feedback" id="ctof_username">
        <input id="username" type="text" class="form-control" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
		<span class="help-block" id="ctomesserror_username"></span>
      </div>
      <div class="form-group has-feedback" id="ctof_password">
        <input id="password" type="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
		<span class="help-block" id="ctomesserror_password"></span>
      </div>
      <div class="row">
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a>

  </div>
  <!-- /.login-box-body -->
  <!-- Loading (remove the following to stop the loading)-->
<div id="cto_overlay" class="overlay">
  <div id="cto_mengecek"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
</div>
<!-- end loading -->
</div>
<!-- /.login-box -->
<script>
	var ctojs_fields = ['username', 'password'];
</script>
<!-- jQuery 3 -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url().'assets/'; ?>plugins/iCheck/icheck.min.js"></script>
<!-- Cto_loading_animation -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/cto/dist/js/cto_loadinganimation.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });

	$("#cto_login_form").on('submit', function(e) {
		e.preventDefault();
		cto_loading_show();
		ctoerr_messages_clear(ctojs_fields);
		cto_messages_hide();
		username = $('#username').val();
		password = $('#password').val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>cto_user/login_validating",
			data: { 'username': username, 'password': password },
			dataType: 'json',                         
			success: function(data){
				cto_loading_hide();
				if(data['status']){
					//alert(data['messages']);
					cto_messages_show(data);
					window.location = "<?php echo base_url(); ?>"+data['landing_page'];
				}else{
					//alert(data['messages']);
					//show error for each fields
					ctoerr_messages(ctojs_fields, data);
					cto_messages_show(data);
				}
			},
			error: function (response) {
			   //Handle error
			   cto_loading_hide();
			   //alert(response['messages']);
			}           
		});
	});
</script>
</body>
</html>

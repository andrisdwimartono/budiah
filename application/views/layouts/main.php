<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BUDIAH</title>
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
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/select2/dist/css/select2.min.css">
  <!-- number input bootstrap-touchspin-master -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.min.css">
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-touchspin-master/src/jquery.bootstrap-touchspin.css">
  
<!-- jQuery 3 -->
<?php if(!isset($jquery2)){ ?>
  <script src="<?php echo base_url().'assets/'; ?>bower_components/jquery/dist/jquery.min.js"></script>
<?php }else{ ?>
<script src="<?php echo base_url().'assets/'; ?>bower_components/jquery/dist/2.1.3/jquery.js"></script>
<?php } ?>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Cto_loading_animation -->
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/cto/dist/css/cto_loadinganimation.min.css">
<!-- Cto_autocomplete style -->  
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/cto/dist/css/cto_autocomplete.css">
  <!-- Cto_updowndatatable style -->  
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/cto/dist/css/cto_updowndatatable.css">
<!-- Datatables 
  <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>bower_components/datatables.net/css/jquery.dataTables.min.css">-->  
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
	<style>
		.example-modal .modal {
		  position: relative;
		  top: auto;
		  bottom: auto;
		  right: auto;
		  left: auto;
		  display: block;
		  z-index: 1;
		}

		.example-modal .modal {
		  background: transparent !important;
		}
  </style>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">
<?php $this->load->view('layouts/header');?>
<?php $this->load->view('layouts/nav');?>
  

  <!-- Content Wrapper. Contains page content -->
    <?php $this->load->view($content); ?>
  <!-- /.content-wrapper -->
  

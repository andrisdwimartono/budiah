  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>

<!-- ./wrapper -->



<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url().'assets/'; ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>


<!-- Morris.js charts -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/raphael/raphael.min.js"></script>
<script src="<?php echo base_url().'assets/'; ?>bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo base_url().'assets/'; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url().'assets/'; ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url().'assets/'; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url().'assets/'; ?>dist/js/adminlte.min.js"></script>
<!-- input number bootstrap-touchspin-master -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.min.js"></script>
<script src="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-touchspin-master/src/jquery.bootstrap-touchspin.js"></script>
<!-- Cto_loading_animation -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/cto/dist/js/cto_loadinganimation.min.js"></script>
<script>
cto_notification();
	function cto_notification(){
		var notif = ajaxGetValueX("<?php echo base_url(); ?>cto_notification/getAllData"); 
		var opt = ''; 
		for (i = 0; i < notif.length; i++) { 
			var element = '<li>'
                    +'<a href="#" onclick="cto_ChangeToWatched('+notif[i]['id']+', \'<?php echo base_url(); ?>'+notif[i]['url']+'\')">'
						+notif[i]['text']
                    +'</a>'
                  +'</li>';
			$( "#cto_notification_dropdown" ).append( element );
		}
		$( "#cto_notification_exist" ).append( notif.length );
	}
	
	function ajaxGetValueX(addr){ 
	var param = null;
	var data = function () { 
	var tmp = null; 
	var jsonString = JSON.stringify(param); 
	$.ajax({ 
		'async': false, 
		'type': "POST", 
		'global': false, 
		'dataType': 'json', 
		'url': addr, 
		'data': { 'data': jsonString}, 
		'success': function (data) { 
			tmp = data; 
		} 
	}); 
	return tmp; 
	}(); 
	return data; 
	} 
	
	function cto_ChangeToWatched(id, url_redirect){
		$.ajax({ 
			type: "POST", 
			async: false,
			url: "<?php echo base_url(); ?>cto_notification/changeToWatched", 
			data: {'id' : id, 'url_redirect': url_redirect},
			dataType: 'json', 
			success: function(data){ 
				window.location = data['url_redirect'];
			}, 
			error: function (response) { 
				
			} 
		}); 
	}
</script>
</body>
</html>

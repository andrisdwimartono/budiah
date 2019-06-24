<div class="content-wrapper">
    <!-- Main content -->
<section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-11 connectedSortable">
          <!-- Calendar -->
          <div class="box box-solid">
            <div class="box-header">
              <i class="fa fa-wpforms"></i>

              <h4 class="box-title">Log Transaksi<?php echo " ".$code_id;?></h4>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
			<!-- messages box-->
				<div class="" id="cto_messages">
					
				</div>
				<!-- /.messages box-->
              <!-- CTOF Content here-->
			  <div class="box" style="background-color:#c6c6c6;">
					<div class="form-group row" id="ctof_address">
							<div class="col-sm-2">
								<b><?php if(isset($code_id)){echo "".$code_id."";} ?></b>
							</div>
							<div class="col-sm-5">
								<i><?php if(isset($address)){echo "".$address."";} ?></i>
							</div>
							
							<div class="col-sm-2">
								<b>Completion :</b> <?php if(isset($percentage)){if($percentage == 100){echo "<font class=\"btn-success\">".number_format($percentage, 2)." %</font>";}else{echo "<font class=\"btn-warning\">".number_format($percentage, 2)." %</font>";}} ?>
							</div>
							
					</div>
					<div class="form-group row" id="cto_log">
					
					
					</div>
				</div>
				 <!-- /.modal start -->
					<div class="modal modal-info fade" id="modal-info">
					  <div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Photo For Each ODP</h4>
							</div>
						  <div class="modal-body" id="cto_photo_upload">
							
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
						  </div>
						</div>
						<!-- /.modal-content -->
						<!-- Loading (remove the following to stop the loading)-->
						<div id="cto_overlay" class="overlay">
						  <div id="cto_mengecek"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
						</div>
						<!-- end loading -->
					  </div>
					  <!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->
			  <!-- CTOF Content here end! -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
</div>
<!-- input number bootstrap-touchspin-master -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.min.js"></script>
<script src="<?php echo base_url().'assets/'; ?>bower_components/bootstrap-touchspin-master/src/jquery.bootstrap-touchspin.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/select2/dist/js/select2.full.min.js"></script>

<script>

    
	cto_log_transaksi("cto_log", "<?php echo base_url(); ?>submission/get_log_transaksi");
    function cto_log_transaksi(cto_elementid, addr){ 
		
		var logt = ajaxGetValue(addr, <?php if(isset($fk_submission_id)){echo $fk_submission_id; } else{echo 0;}?>); 
		var opt = '<ul>'; 
		for (i = 0; i < logt.length; i++) { 
			opt+='<li>'+logt[i]['status_text']+'; '+logt[i]['photo']+'</li>'; 			
		} 
		opt += '</ul>'; 
		document.getElementById(cto_elementid).innerHTML = opt; 
	}
	
	cto_data_transaksi("<?php echo base_url(); ?>submission/cto_getprogress");
    function cto_data_transaksi(addr){ 
		
		var logt = ajaxGetValue(addr, <?php if(isset($fk_submission_id)){echo $fk_submission_id; } else{echo 0;}?>); 
		
		document.getElementById("").innerHTML = opt; 
	}


	function ajaxGetValue(addr, param){ 
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

	function get_photo(fk_submission_id, status){
		$('#modal-info').modal('show');
		var photoupload = ajaxGetValue2("<?php echo base_url(); ?>submission/getODPPhotoStatus", fk_submission_id, status);
		var prog = "";
		for(i = 0; i < photoupload.length; i++){
		prog += "<div class=\"form-group row\">"
						+"<label for=\"create_odp\" class=\"col-sm-5 control-label\">ODP : "+photoupload[i]['odp_name']+"<font style=\"color:red;\">*</font></br> ID DEPLOYER : "+photoupload[i]['id_deployer']+"</label>"
						+"<div class=\"col-sm-2\">"
							+"<a href=\"<?php echo base_url(); ?>uploads/"+photoupload[i]['img']+"\" download><img src=\"<?php echo base_url(); ?>uploads/"+photoupload[i]['img']+"\" class=\"img-rounded img-responsive\"></a>"
						+"</div>"
					+"</div>";
		}
		document.getElementById("cto_photo_upload").innerHTML = prog;
	}
	
	function ajaxGetValue2(addr, fk_submission_id, status){ 
	var data = function () { 
	var tmp = null; 
	$.ajax({ 
		'async': false, 
		'type': "POST", 
		'global': false, 
		'dataType': 'json', 
		'url': addr, 
		'data': { 'fk_submission_id': fk_submission_id, 'status' : status}, 
		'success': function (data) { 
			tmp = data; 
		} 
	}); 
	return tmp; 
	}(); 
	return data; 
	} 
	
	
</script>
<?php $this->load->view('layouts/footer');?>
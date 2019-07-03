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

              <h4 class="box-title">Edit Status<?php if(isset($code_id)){echo " ".$code_id;} ?></h4>
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
						<label for="create_odp" class="col-sm-1 control-label">Progress</label>
						<div class="row">
							<div class="col-sm-1">
							
							</div>
							<div class="progress col-md-3" style="width:80%;height:40px;margin-top:7px;" id="cto_progress_bar">
								
							</div>
						</div>
						<label for="create_odp" class="col-sm-1 control-label">Update</label>
						<div class="row">
							<div class="col-sm-1">
							
							</div>
							<div class="col-md-4" id="cto_update_progress">
								
							</div>
						</div>
					</div>
				  <!-- /.modal start -->
					<div class="modal modal-info fade" id="modal-info">
					  <div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Upload Photo For Each ODP</h4>
							</div>
						  <div class="modal-body" id="cto_photo_upload">
							
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-outline" onclick="update_progress();" id="cto_btnfinishupdate">Update</button>
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
			<!-- Loading (remove the following to stop the loading)
            <div id="cto_overlay" class="overlay">
			  <div id="cto_mengecek"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
			</div>
            end loading -->
			
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
	 function cto_uploadPhoto(fk_submission_id, fk_odp_id){
		var file_data = $('#pic_'+fk_odp_id).prop('files')[0];
		var form_data = new FormData();
		form_data.append('file', file_data);
		form_data.append('fk_submission_id',<?php if(isset($fk_submission_id)){echo $fk_submission_id;}else{echo 0;} ?>);
		form_data.append('fk_odp_id',fk_odp_id);
		cto_loading_show(); 
		$.ajax({
				url         : '<?php echo base_url(); ?>order/upload_progress',
				dataType    : 'text',
				async		: false, 
				cache       : false,
				contentType : false,
				processData : false,
				data        : form_data,                         
				type        : 'post',
				success     : function(data){
					// cto_loading_hide(); 
					// if(data['status']){ 
						// cto_messages_show(data); 
					// }else{ 
						// cto_messages_show(data); 
					// } 
					alert(data);
				}, 
				error: function (response) { 
					//cto_loading_hide(); 
				} 
		 });
		 $('#pic'+fk_odp_id).val('');
		 ctoGetODPPhotoUpload("<?php echo base_url(); ?>order/getODPPhoto", <?php if(isset($fk_submission_id)){echo $fk_submission_id;}else{echo 0;} ?>);
		 cto_loading_hide(); 
	 }
	 
	 function cto_removeImg(fk_submission_id, fk_odp_id){
		 cto_loading_show(); 
		if(confirm("Apakah anda yakin untuk hapus photo?")){
			<?php if(isset($fk_submission_id)){echo "fk_submission_id = ".$fk_submission_id.";\n";}?> 
			$.ajax({ 
				type: "POST", 
				url: "<?php echo base_url(); ?>order/cto_removeImg", 
				data: { <?php if(isset($fk_submission_id)){echo "'fk_submission_id' : ".$fk_submission_id.", ";}?> 'fk_odp_id' : fk_odp_id},
				dataType    : 'text',
				async		: false, 
				success: function(data){ 
					alert(data);
				}, 
				error: function (response) { 
					cto_loading_hide(); 
				} 
			}); 
		}
		ctoGetODPPhotoUpload("<?php echo base_url(); ?>order/getODPPhoto", <?php if(isset($fk_submission_id)){echo $fk_submission_id;}else{echo 0;} ?>);
		cto_loading_hide();
	 }
	 

//---------javascript inside ajax for post-------

		
	//for field with number type 

function update_progress(){
	cto_loading_show(); 
	if(confirm("Apakah anda yakin untuk update progress?")){
		<?php if(isset($fk_submission_id)){echo "fk_submission_id = ".$fk_submission_id.";\n";}?> 
		$.ajax({ 
			type: "POST", 
			url: "<?php echo base_url(); ?>submission/update_progress", 
			data: { <?php if(isset($fk_submission_id)){echo "'fk_submission_id' : ".$fk_submission_id.", ";}?>},
			dataType: 'json', 
			success: function(data){ 
				cto_loading_hide(); 
				if(data['status']){ 
					cto_messages_show(data); 
				}else{ 
					cto_messages_show(data); 
				} 
			}, 
			error: function (response) { 
				cto_loading_hide(); 
			} 
		}); 
		ctoGetProgressBar("cto_progress_bar", "cto_update_progress", "<?php echo base_url(); ?>submission/cto_getprogress", <?php if(isset($fk_submission_id)){echo $fk_submission_id;}else{echo 0;} ?>);
	}
	$('#modal-info').modal('hide');
	cto_loading_hide();
}

	
	$(function () {
		//Initialize Select2 Elements
		$('.select2').select2()
	  })
	  
	//ctoGetSelect("fk_sto_id", "<?php echo base_url(); ?>submission/cto_getDatas"); 
    
    function ctoGetSelect(cto_elementid, addr, param){ 
		param = []; 
		param[0] = 1; 
		var selectpack = ajaxGetValue(addr, param); 
		var opt = ''; 
		for (i = 0; i < selectpack.length; i++) { 
			opt+='<option value="'+selectpack[i][0]+'">'+selectpack[i][1]+'</option>'; 
		} 
		document.getElementById(cto_elementid).innerHTML = opt; 
	} 
	
	ctoGetProgressBar("cto_progress_bar", "cto_update_progress", "<?php echo base_url(); ?>submission/cto_getprogress", <?php if(isset($fk_submission_id)){echo $fk_submission_id;}else{echo 0;} ?>);
	
	function ctoGetProgressBar(cto_elementid, cto_elementid2, addr, param){ 
		var progress_bar = ajaxGetValue(addr, param);
		var prog = "<div class=\"progress-bar progress-bar-success\" role=\"progressbar\" style=\"width:"+progress_bar['percentage']+"%\">"
								+progress_bar['percentage']+"% Finished"
								+"</div>"
								+"<div class=\"progress-bar progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"40\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:"+(progress_bar['percentage_onwork']-progress_bar['percentage'])+"%\">"
								+""+(progress_bar['percentage_onwork']-progress_bar['percentage'])+"% On Progress ("+progress_bar['status_next']+")"
								+"</div>"
								+"<div class=\"progress-bar progress-bar-warning progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"50\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:"+(100-progress_bar['percentage_onwork'])+"%\">"
								+""+(100-progress_bar['percentage_onwork'])+"% Not Yet"
								+"</div>"; 
		document.getElementById(cto_elementid).innerHTML = prog;
		
		var cto_button = "";
		if(100-progress_bar['percentage_onwork'] < 100){
			cto_button = "<div class=\"row align-right\">"
									+"<div class=\"col-sm-8\">"
									  +"<button type=\"submit\" class=\"btn btn-primary btn-block btn-flat\" id=\"cto_update_progress_btn\" data-toggle=\"modal\" data-target=\"#modal-info\" onclick=\"ctoGetODPPhotoUpload('<?php echo base_url(); ?>order/getODPPhoto', <?php if(isset($fk_submission_id)){echo $fk_submission_id;}else{echo 0;} ?>);\">Finish "+progress_bar['status_next']+"</button>"
									+"</div>"
								+"</div>"; 
		}
		document.getElementById(cto_elementid2).innerHTML = cto_button;
		document.getElementById('cto_btnfinishupdate').innerHTML = "Finish "+progress_bar['status_next']; //change the button label
		ctoGetODPPhotoUpload("<?php echo base_url(); ?>order/getODPPhoto", <?php if(isset($fk_submission_id)){echo $fk_submission_id;}else{echo 0;} ?>);
	} 
	
	function ctoGetODPPhotoUpload(addr, fk_submission_id){
		var photoupload = ajaxGetValue(addr, fk_submission_id);
		var prog = "";
		for(i = 0; i < photoupload.length; i++){
		prog += "<div class=\"form-group row\">"
						+"<label for=\"create_odp\" class=\"col-sm-5 control-label\">ODP : "+photoupload[i]['odp_name']+"<font style=\"color:red;\">*</font></br> ID DEPLOYER : "+photoupload[i]['id_deployer']+"</label>"
						+"<div class=\"col-sm-4\">"
							+"<input type=\"file\" name=\"fileToUpload\" id=\"pic_"+photoupload[i]['id']+"\" onchange=\"cto_uploadPhoto(<?php if(isset($fk_submission_id)){echo $fk_submission_id;}else{echo 0;} ?>, "+photoupload[i]['id']+")\">"
						+"<span class=\"help-block\" id=\"ctomesserror_photo_"+photoupload[i]['odp_name']+"\"></span>"
						+"</div>"
						+"<div class=\"col-sm-2\">"
							+"<a href=\"<?php echo base_url(); ?>uploads/"+photoupload[i]['img']+"\" download><img src=\"<?php echo base_url(); ?>uploads/"+photoupload[i]['img']+"\" class=\"img-rounded img-responsive\"></a>"
						+"</div>"
						+"<div class=\"col-sm-1\">"
							+"<button type=\"button\" class=\"fa fa-remove btn-danger\" onclick=\"cto_removeImg(<?php if(isset($fk_submission_id)){echo $fk_submission_id;}else{echo 0;} ?>, "+photoupload[i]['id']+");\"></button>"
						+"</div>"
					+"</div>";
		}
		document.getElementById("cto_photo_upload").innerHTML = prog;
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

	
</script>
<?php $this->load->view('layouts/footer');?>
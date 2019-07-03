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

              <h4 class="box-title">Create ODP<?php if(isset($code_id)){echo " ".$code_id;} ?></h4>
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
				<form class="form-horizontal" id="cto_form">
			  <div class="box" style="background-color:#c6c6c6;">
					<div class="form-group" id="ctof_address">
							<div class="col-sm-2">
								<b>ID :</b> <?php if(isset($code_id)){echo "".$code_id."";} ?>
							</div>
							<div class="col-sm-5">
								<b>Lokasi :</b> <?php if(isset($address)){echo "".$address."";} ?>
							</div>
							
							<div class="col-sm-2">
								<b>House Hold :</b> <?php if(isset($house_hold)){echo "".$house_hold."";} ?>
							</div>
							
					</div>
					<div class="form-group" id="ctof_potentials">
							<div class="col-sm-3">
								<b>Tikor Lokasi :</b> <?php if(isset($coordinate)){echo "".$coordinate."";} ?>
							</div>
							<div class="col-sm-2">
								<b>Calon Pelanggan :</b> <?php if(isset($potentials)){echo "".$potentials."";} ?>
							</div>
							<div class="col-sm-2">
								<b>Request ODP :</b> <?php if(isset($odp)){echo "".$odp."";} ?>
							</div>
							<div class="col-sm-1">
								<b>STO :</b> <?php if(isset($sto_name)){echo "".$sto_name."";} ?>
							</div>
					</div>
					<div class="form-group">
							<label for="create_odp" class="col-sm-2 control-label">Jumlah ODP<font style="color:red;">*</font></label>
							<div class="col-sm-1">
							<select class="form-control select2" style="width: 100%;" id="create_odp" name="create_odp" data-error=".errorTxt6" onChange="cto_CreateForm(this.value);">
								<option value="1" <?php if(isset($create_odp) && $create_odp == "1"){echo "selected=\"selected\"";} ?> > 1 </option>
								<option value="2" <?php if(isset($create_odp) && $create_odp == "2"){echo "selected=\"selected\"";} ?> > 2 </option>
								<option value="3" <?php if(isset($create_odp) && $create_odp == "3"){echo "selected=\"selected\"";} ?> > 3 </option>
								<option value="4" <?php if(isset($create_odp) && $create_odp == "4"){echo "selected=\"selected\"";} ?> > 4 </option>
								<option value="5" <?php if(isset($create_odp) && $create_odp == "5"){echo "selected=\"selected\"";} ?> > 5 </option>
								<option value="6" <?php if(isset($create_odp) && $create_odp == "6"){echo "selected=\"selected\"";} ?> > 6 </option>
								<option value="7" <?php if(isset($create_odp) && $create_odp == "7"){echo "selected=\"selected\"";} ?> > 7 </option>
								<option value="8" <?php if(isset($create_odp) && $create_odp == "8"){echo "selected=\"selected\"";} ?> > 8 </option>
								<option value="9" <?php if(isset($create_odp) && $create_odp == "9"){echo "selected=\"selected\"";} ?> > 9 </option>
								<option value="10" <?php if(isset($create_odp) && $create_odp == "10"){echo "selected=\"selected\"";} ?> > 10 </option>
							</select>
							<span class="help-block" id="ctomesserror_create_odp"></span>
							</div>
							
					</div>
					<div id="cto_odp_form">
						
					</div>
						
					<div class="form-group">
						<input id="code_id" type="hidden" name="code_id" <?php if(isset($code_id)){echo "value=\"".$code_id."\"";} ?> class="form-control" placeholder="Code">
						<input id="fk_submission_id" type="hidden" name="fk_submission_id" <?php if(isset($fk_submission_id)){echo "value=\"".$fk_submission_id."\"";} ?> class="form-control" placeholder="fk_submission_id">
						<div class="col-sm-9">
						</div>
						<div class="row align-right">
							<div class="col-sm-2">
							  <button type="submit" class="btn btn-primary btn-block btn-flat" id="cto_submit_all"><?php if(isset($cto_id)){echo "Update";}else{echo "Submit"; }?></button>
							</div>
							<!-- /.col -->
						</div>
					</div>
				</div>
			  </form>
			  
			  <!-- CTOF Content here end! -->
            </div>
            <!-- /.box-body -->
			<!-- Loading (remove the following to stop the loading)-->
            <div id="cto_overlay" class="overlay">
			  <div id="cto_mengecek"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
			</div>
            <!-- end loading -->
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

	//	------javascript ctojs_fields-------
var ctojs_fields = ['controller', 'method', 'path', 'name', 'fk_menupackage_id', 'sequence', 'is_shown', 'is_active'];
var label_golive = [];
var id_deployer = [];
var odp_count = 0;
cto_CreateForm(1);
//---------javascript inside ajax for post-------

    $("#cto_form").on('submit', function(e) {
		var cto_checkSendAll = true;
       	e.preventDefault(); 
       	cto_loading_show(); 
       	if(cto_checkValue(odp_count)){
			<?php if(isset($fk_submission_id)){echo "fk_submission_id = ".$fk_submission_id.";\n";}?> 
			cto_getValue(odp_count);
			var cto_label_golive = null;
			var cto_id_deployer = null;
			for (i = 1; i <= odp_count; i++) { 
				cto_label_golive = label_golive[i];
				cto_id_deployer = id_deployer[i];
				$.ajax({ 
					type: "POST", 
					async: false,
					url: "<?php echo base_url(); ?>order/insert_odp", 
					data: { <?php if(isset($fk_submission_id)){echo "'fk_submission_id' : ".$fk_submission_id.", ";}?> 'label_golive' : cto_label_golive, 'id_deployer' : cto_id_deployer},
					dataType: 'json', 
					success: function(data){ 
						cto_loading_hide(); 
						if(data['status']){ 
							cto_messages_show(data); 
						}else{ 
							cto_messages_show(data);
							cto_checkSendAll = false;
						} 
					}, 
					error: function (response) { 
						cto_loading_hide(); 
					} 
				}); 
			}
		}else{
			cto_checkSendAll = false;
		}
       	
		if(cto_checkSendAll){
			alert('Data is saved!');
			window.location = "<?php echo base_url(); ?>order/ongoing";
		}
       	cto_loading_hide(); 
    	});
		
	//for field with number type 


$("input[name='house_hold']").TouchSpin({ 
    	min: 0, 
    	max: 1000, 
    	step: 1, 
    	decimals: 0, 
    	boostat: 1, 
    	maxboostedstep: 10, 
    	buttondown_class:'btn hidden', 
    	buttonup_class:'btn hidden', 
    });	


$("input[name='odp']").TouchSpin({ 
    	min: 0, 
    	max: 1000, 
    	step: 1, 
    	decimals: 0, 
    	boostat: 1, 
    	maxboostedstep: 10, 
    	buttondown_class:'btn hidden', 
    	buttonup_class:'btn hidden', 
    });	

	
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

	function cto_CreateForm(odp_num){
		odp_count = odp_num;
		cto_getValue(odp_num);
		var cto_form = ''; 
		for (i = 1; i <= odp_num; i++) { 
			cto_form+="<div class=\"form-group\" id=\"ctof_fk_submission_id_"+i+"\">\n<input id=\"fk_submission_id\" type=\"hidden\" name=\"fk_submission_id\" class=\"form-control\" placeholder=\"submission\" <?php if(isset($fk_submission_id)){echo "value=\\\"".$fk_submission_id."\\\"";} ?>>\n<span class=\"help-block\" id=\"ctomesserror_fk_submission_id\"></span><label for=\"label_golive\" class=\"col-sm-2 control-label\">ODP Name<font style=\"color:red;\">*</font></label><div class=\"col-sm-2\"><input id=\"label_golive_"+i+"\" type=\"text\" name=\"label_golive\" class=\"form-control\" placeholder=\"ODP-TER-07YT\"><span class=\"help-block\" id=\"ctomesserror_label_golive\"></span></div><label for=\"id_deployer\" class=\"col-sm-2 control-label\">ID Deployer<font style=\"color:red;\">*</font></label><div class=\"col-sm-2\"><input id=\"id_deployer_"+i+"\" type=\"text\" name=\"id_deployer\" class=\"form-control\" placeholder=\"T08TR-UYR/ID\"><span class=\"help-block\" id=\"ctomesserror_id_deployer\"></span></div></div>";
		}

		document.getElementById("cto_odp_form").innerHTML = cto_form;
		for (i = 1; i <= odp_num; i++) { 
			//$("#label_golive_"+i).val('Hello...How are you?');
			document.getElementById("label_golive_"+i).value = "ODP-<?php if(isset($sto_name)){echo "".$sto_name."";} ?>-";
		}
		//cto_setValue(odp_num);
	}
	
	function cto_checkValue(odp_num){
		var data = [];
		data['status'] = true;
		for (i = 1; i <= odp_num; i++) { 
			var str = $('#label_golive_'+i).val();
			var regex = RegExp('ODP-<?php if(isset($sto_name)){echo "".$sto_name."";} ?>-');
			var result = regex.test(str);
			if($('#label_golive_'+i).val() == "" || $('#id_deployer_'+i).val() == ""){
				data['status'] = false;
				data['messages'] = "Failed! Some requiered field is null!";
				cto_messages_show(data);
			}else if(!result){
				data['status'] = false;
				data['messages'] = "Failed! ODP Name pattern is wrong!";
				cto_messages_show(data);
			}
		}
		return data['status'];
	}
	
	function cto_getValue(odp_num){
		for (i = 1; i <= odp_num; i++) { 
			label_golive[i] = $('#label_golive_'+i).val();
			id_deployer[i] = $('#id_deployer_'+i).val();
		}
	}
	
	function cto_setValue(odp_num){
		for (i = 1; i <= odp_num; i++) { 
			$('#label_golive_'+i).val(label_golive[i]);
			$('#id_deployer_'+i).val(id_deployer[i]);
		}
	}
</script>
<?php $this->load->view('layouts/footer');?>
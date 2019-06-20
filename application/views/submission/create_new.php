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

              <h4 class="box-title">Create New</h4>
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
				<div id="cto_all_form">
				
				</div>
				<div class="row align-right">
					<div class="col-sm-2">
					  <button type="submit" class="btn btn-primary btn-block btn-flat" id="cto_submit_all"><?php if(isset($cto_id)){echo "Update";}else{echo "Submit"; }?></button>
					</div>
					<!-- /.col -->
				  </div>
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
// $(function () {
// //Initialize Select2 Elements
	// $('.select2').select2()
// })
//kondisi submission, jika true maka dipakai. Jika false, maka sudah didelete
var forms = [];
//jumlah submission
var form_jml = -1;
//set all field as array here
var unsc_jml = [];
var fk_sto_id = [];
var sto_name = [];
var address = [];
var house_hold = [];
var coordinate = [];
var odp = [];

var fk_submission_id = [[],[],[],[],[],[],[],[],[],[]];
var cust_name = [[],[],[],[],[],[],[],[],[],[]];
var cust_address = [[],[],[],[],[],[],[],[],[],[]];
var cpackage = [[],[],[],[],[],[],[],[],[],[]];
var cust_coordinate = [[],[],[],[],[],[],[],[],[],[]];
// var xyz = [[],[],[]];
 // xyz[0][0] = 0;
 // xyz[0][1] = 1;
  // xyz[2][1] = 7;
 // alert(xyz[2][1]);

cto_CreateForm();

	function cto_CreateForm(){
		
		form_jml++;
		forms[form_jml] = true;
		
		cto_CreateFormSub(form_jml);
		cto_CreateFormUNSC(form_jml, 3);
		
	}
	
	//get all field value here
	function cto_getFormValue(form_num){
		for(fn = 0; fn <= form_num; fn++){
			fk_sto_id[fn] = $('#fk_sto_id_'+fn).find(":selected").val();
			address[fn] = $('#address_'+fn).val();
			house_hold[fn] = $('#house_hold_'+fn).val();
			coordinate[fn] = $('#coordinate_'+fn).val();
			odp[fn] = $('#odp_'+fn).val();
			
			for(un = 0; un < unsc_jml[fn]; un++){
				fk_submission_id[fn][un] = $('#fk_submission_id_'+fn+'_'+un).val();
				cust_name[fn][un] = $('#cust_name_'+fn+'_'+un).val();
				cust_address[fn][un] = $('#cust_address_'+fn+'_'+un).val();
				cpackage[fn][un] = $('#package_'+fn+'_'+un).val();
				cust_coordinate[fn][un] = $('#cust_coordinate_'+fn+'_'+un).val();
				//alert(cust_name[fn][un]);
			}
		}
	}
	
	//fill value here
	function cto_FillValue(form_num){
		for(fn = 0; fn <= form_num; fn++){
			ctoGetSelect("fk_sto_id_"+fn, "<?php echo base_url(); ?>submission/cto_getDatas");
			//set selected value
			$("#fk_sto_id_"+fn).val(fk_sto_id[fn]);
			$("#address_"+fn).val(address[fn]);
			$("#house_hold_"+fn).val(house_hold[fn]);
			$("#coordinate_"+fn).val(coordinate[fn]);
			$("#odp_"+fn).val(odp[fn]);
			
			cto_CreateFormUNSC(fn, unsc_jml[fn]);
			cto_FillValueUNSC(fn);
			// for(un = 0; un < unsc_jml[fn]; un++){
				// $("#fk_submission_id_"+fn+"_"+un).val(fk_submission_id[fn][un]);
				// $("#cust_name_"+fn+"_"+un).val(cust_name[fn][un]);
				// $("#cust_address_"+fn+"_"+un).val(cust_address[fn][un]);
				// $("#package_"+fn+"_"+un).val(cpackage[fn][un]);
				// $("#cust_coordinate_"+fn+"_"+un).val(cust_coordinate[fn][un]);
			// }
			
			$(".cto_number").TouchSpin({ 
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
				$('.select2_'+0).select2()
				$('.select2_'+1).select2()
				$('.select2_'+2).select2()
				$('.select2_'+3).select2()
				$('.select2_'+4).select2()
				$('.select2_'+5).select2()
				$('.select2_'+6).select2()
				$('.select2_'+7).select2()
				$('.select2_'+8).select2()
				$('.select2_'+9).select2()
				$('.select2_'+10).select2()
			})
			
		}
		
	}
	
	function cto_FillValueUNSC(form_num){
		fn = form_num;
		for(un = 0; un < unsc_jml[fn]; un++){
			$("#fk_submission_id_"+fn+"_"+un).val(fk_submission_id[fn][un]);
			$("#cust_name_"+fn+"_"+un).val(cust_name[fn][un]);
			$("#cust_address_"+fn+"_"+un).val(cust_address[fn][un]);
			$("#package_"+fn+"_"+un).val(cpackage[fn][un]);
			$("#cust_coordinate_"+fn+"_"+un).val(cust_coordinate[fn][un]);
		}
	}
	
	function cto_CreateFormSub(form_num){
		//get all field value before create form
		cto_getFormValue(form_num);
		var frm = "";
		for(fn = 0; fn <= form_num; fn++){
			frm+="<div class=\"\" id=\"cto_messages_"+fn+"\"></div>";
			frm+="<form class=\"form-horizontal\" id=\"cto_form_sub_"+fn+"\">";
			frm+="<div class=\"box\" style=\"background-color:#c6c6c6;\">";
			frm+="<div class=\"form-group\" id=\"ctof_address_"+fn+"\">";
			frm+="<label for=\"address\" class=\"col-sm-1 control-label\">Lokasi<font style=\"color:red;\">*</font></label>";
			frm+="<div class=\"col-sm-3\">";
			frm+="<input id=\"address_"+fn+"\" type=\"text\" name=\"address\" class=\"form-control autocomplete_address\" placeholder=\"Alamat/Kluster\">";
			frm+="<span class=\"help-block\" id=\"ctomesserror_address\"></span>";
			frm+="</div>";
			frm+="<label for=\"house_hold\" class=\"col-sm-2 control-label\">House Hold</label>";
			frm+="<div class=\"col-sm-1\">";
			frm+="<input id=\"house_hold_"+fn+"\" type=\"text\" name=\"house_hold\" class=\"form-control cto_number\" placeholder=\"7\">";
			frm+="<span class=\"help-block\" id=\"ctomesserror_house_hold\"></span>";
			frm+="</div>";
			frm+="<label for=\"coordinate\" class=\"col-sm-2 control-label\">Tikor Lokasi<font style=\"color:red;\">*</font></label>";
			frm+="<div class=\"col-sm-3\">";
			frm+="<input id=\"coordinate_"+fn+"\" type=\"text\" name=\"coordinate\" class=\"form-control\" placeholder=\"4.123, 37.908\">";
			frm+="<span class=\"help-block\" id=\"ctomesserror_coordinate\"></span>";
			frm+="</div>";
			frm+="</div>";
			frm+="<div class=\"form-group\" id=\"ctof_potentials\">";
			frm+="<label for=\"potentials\" class=\"col-sm-2 control-label\">Calon Pelanggan<font style=\"color:red;\">*</font></label>";
			frm+="<div class=\"col-sm-1\">";
			frm+="<select class=\"form-control select2_"+fn+"\" style=\"width: 100%;\" id=\"potentials_"+fn+"\" name=\"potentials_"+fn+"\" data-error=\".errorTxt6\" onChange=\"cto_CreateFormUNSC("+fn+", this.value);\">";
			for(i = 3; i <= 10; i++){
				if(i == unsc_jml[fn]){
					frm+="<option selected value=\""+i+"\"> "+i+" </option>";
				}else{
					frm+="<option value=\""+i+"\"> "+i+" </option>";
				}
			}
			frm+="</select>";
			frm+="<span class=\"help-block\" id=\"ctomesserror_potentials\"></span>";
			frm+="</div>";
			frm+="<label for=\"odp\" class=\"col-sm-2 control-label\">Request ODP<font style=\"color:red;\">*</font></label>";
			frm+="<div class=\"col-sm-1\">";
			frm+="<input id=\"odp_"+fn+"\" type=\"text\" name=\"odp\" class=\"form-control cto_number\" placeholder=\"2\">";
			frm+="<span class=\"help-block\" id=\"ctomesserror_odp\"></span>";
			frm+="</div>";
			frm+="<label for=\"fk_sto_id\" class=\"col-sm-1 control-label\">STO<font style=\"color:red;\">*</font></label>";
			frm+="<div class=\"col-sm-2\">";
			frm+="<select class=\"form-control select2_"+fn+"\" style=\"width: 100%;\" id=\"fk_sto_id_"+fn+"\" name=\"fk_sto_id\" data-error=\".errorTxt6\" onChange=\"cto_SetStoName("+fn+")\">";
			frm+="</select>";
			frm+="<span class=\"help-block\" id=\"ctomesserror_fk_sto_id\"></span>";
			frm+="</div>";
			frm+="<input id=\"sto_name_"+fn+"\" type=\"hidden\" name=\"sto_name\">";
			frm+="</div>";
			frm+="<div id=\"cto_unsc_form_"+fn+"\">";
			frm+="</div>";
			frm+="<div class=\"form-group\">";
			frm+="<label for=\"code_id\" class=\"col-sm-2 control-label\"><h4><div id=\"code_id_lb_"+fn+"\"> </div></h4></label>";
			frm+="<input id=\"code_id_"+fn+"\" type=\"hidden\" name=\"code_id\" class=\"form-control\" placeholder=\"Code\">";
			frm+="<div class=\"col-sm-6\">";
			frm+="</div>";
			frm+="<div class=\"col-sm-3\">";
			if(fn != 0){
				frm+="<button type=\"button\" class=\"btn btn-success\" style=\"border-radius: 100%;background-color:#ffad41;\" onclick=\"cto_DeleteFormSub("+fn+")\"><i class=\"fa fa-trash\"></i>";
				frm+="</button>";
			}
			frm+="<button type=\"button\" class=\"btn btn-success\" style=\"border-radius: 100%;background-color:#ff42b6;\" onclick=\"cto_ClearFormSub("+fn+")\"><i class=\"fa fa-undo\"></i>";
			frm+="</button>";
			frm+="<button type=\"button\" class=\"btn btn-success\" style=\"border-radius: 100%;\" onclick=\"cto_CreateForm()\"><i class=\"fa fa-plus\"></i>";
			frm+="</button>";
			frm+="</div>";
			frm+="</div>";
			frm+="</div>";
			frm+="</div>";
			frm+="</form><br>";
		}
		document.getElementById("cto_all_form").innerHTML = frm;
		cto_FillValue(form_num);
	}
		
	function cto_CreateFormUNSC(form_num, unsc_num){
		//assign jumlah unsc, agar ketika create submission yang selected di jumlah calon pelanggan lama tidak berubah
		unsc_jml[form_num] = unsc_num;
		var cto_form = ''; 
		for (i = 0; i < unsc_num; i++) { 
			cto_form+="<div class=\"form-group\" id=\"ctof_fk_submission_id_"+i+"\">";
			cto_form+="<input id=\"fk_submission_id_"+form_num+"_"+i+"\" type=\"hidden\" name=\"fk_submission_id\" class=\"form-control\" placeholder=\"submission\">";
			cto_form+="<span class=\"help-block\" id=\"ctomesserror_fk_submission_id\"></span>";
			cto_form+="<label for=\"cust_name\" class=\"col-sm-1 control-label\">Cust.Name<font style=\"color:red;\">*</font></label>";
			cto_form+="<div class=\"col-sm-2\"><input id=\"cust_name_"+form_num+"_"+i+"\" type=\"text\" name=\"cust_name\" class=\"form-control\" placeholder=\"Nama Pelanggan\"><span class=\"help-block\" id=\"ctomesserror_cust_name\"></span></div>";
			cto_form+="<label for=\"cust_address\" class=\"col-sm-1 control-label\">Address<font style=\"color:red;\">*</font></label>";
			cto_form+="<div class=\"col-sm-2\"><input id=\"cust_address_"+form_num+"_"+i+"\" type=\"text\" name=\"cust_address\" class=\"form-control autocomplete_cust_address\" placeholder=\"Alamat/Kluster\"><span class=\"help-block\" id=\"ctomesserror_cust_address\"></span></div>";
			cto_form+="<label for=\"package\" class=\"col-sm-1 control-label\">Paket<font style=\"color:red;\">*</font></label>";
			cto_form+="<div class=\"col-sm-2\"><input id=\"package_"+form_num+"_"+i+"\" type=\"text\" name=\"package\" class=\"form-control autocomplete_package\" placeholder=\"10 MBPS\"><span class=\"help-block\" id=\"ctomesserror_package\"></span></div>";
			cto_form+="<label for=\"cust_coordinate\" class=\"col-sm-1 control-label\">Tikor<font style=\"color:red;\">*</font></label>";
			cto_form+="<div class=\"col-sm-2\"><input id=\"cust_coordinate_"+form_num+"_"+i+"\" type=\"text\" name=\"cust_coordinate\" class=\"form-control\" placeholder=\"17.991, 37.990\"><span class=\"help-block\" id=\"ctomesserror_cust_coordinate\"></span></div>";
			cto_form+="</div>";
		}
		document.getElementById("cto_unsc_form_"+form_num).innerHTML = cto_form; 
		cto_reloadAutoComplete();
	}
	
	function cto_DeleteFormSub(form_num){
		if(confirm("Are you sure you want to delete this?"))
		{
			document.getElementById("cto_form_sub_"+form_num).innerHTML = "";
			forms[form_num] = false;
		}
	}
	
	function cto_ClearFormSub(fn){
		if(confirm("Are you sure you want to clear this?"))
		{
			cto_clearingFormSub(fn);
		}
	}
	
	function cto_clearingFormSub(fn){
		fn = fn;
		ctoGetSelect("fk_sto_id_"+fn, "<?php echo base_url(); ?>submission/cto_getDatas");
		//set selected value
		$("#fk_sto_id_"+fn).val();
		$("#address_"+fn).val("");
		$("#house_hold_"+fn).val("");
		$("#coordinate_"+fn).val("");
		$("#odp_"+fn).val("");
		
		fk_sto_id[fn] = "";
		address[fn] = "";
		house_hold[fn] = "";
		coordinate[fn] = "";
		odp[fn] = "";
		
		cto_CreateFormUNSC(fn, unsc_jml[fn]);
		
		for(un = 0; un < unsc_jml[fn]; un++){
			$("#fk_submission_id_"+fn+"_"+un).val("");
			$("#cust_name_"+fn+"_"+un).val("");
			$("#cust_address_"+fn+"_"+un).val("");
			$("#package_"+fn+"_"+un).val("");
			$("#cust_coordinate_"+fn+"_"+un).val("");
			
			fk_submission_id[fn][un] = "";
			cust_name[fn][un] = "";
			cust_address[fn][un] = "";
			cpackage[fn][un] = "";
			cust_coordinate[fn][un] = "";
		}
	}
	
	//	------javascript ctojs_fields-------
var ctojs_fields = ['controller', 'method', 'path', 'name', 'fk_menupackage_id', 'sequence', 'is_shown', 'is_active'];

//---------javascript inside ajax for post-------

//get all field value here
	function cto_getFormValue(form_num){
		for(fn = 0; fn <= form_num; fn++){
			fk_sto_id[fn] = $('#fk_sto_id_'+fn).find(":selected").val();
			address[fn] = $('#address_'+fn).val();
			house_hold[fn] = $('#house_hold_'+fn).val();
			coordinate[fn] = $('#coordinate_'+fn).val();
			odp[fn] = $('#odp_'+fn).val();
			
			for(un = 0; un < unsc_jml[fn]; un++){
				fk_submission_id[fn][un] = $('#fk_submission_id_'+fn+'_'+un).val();
				cust_name[fn][un] = $('#cust_name_'+fn+'_'+un).val();
				cust_address[fn][un] = $('#cust_address_'+fn+'_'+un).val();
				cpackage[fn][un] = $('#package_'+fn+'_'+un).val();
				cust_coordinate[fn][un] = $('#cust_coordinate_'+fn+'_'+un).val();
				//alert(cust_name[fn][un]);
			}
		}
	}
	
	function cto_checkFormValue(form_num){
		var check_pass = true;
		var check_req_form = [];
		var data = [];
		for(fn = 0; fn <= form_num; fn++){
			if(forms[fn]){
				if(typeof $('#fk_sto_id_'+fn).find(":selected").val() == "undefined" || $('#address_'+fn).val() == "" ||  $('#coordinate_'+fn).val() == "" ||  $('#odp_'+fn).val() == ""){
					data['status'] = false;
					data['messages'] = "Submission is not saved! Fill the required field!";
					cto_messages_sub_show(data, fn);
					check_req_form[fn] = false;
					check_pass = false;
				}else{
					for(un = 0; un < unsc_jml[fn]; un++){
						if($('#cust_name_'+fn+'_'+un).val() == "" || $('#cust_address_'+fn+'_'+un).val() == "" || $('#package_'+fn+'_'+un).val() == "" || $('#cust_coordinate_'+fn+'_'+un).val() == ""){
							data['status'] = false;
							data['messages'] = "UNSC is not saved! Fill the required field!";
							cto_messages_sub_show(data, fn);
							check_req_form[fn] = false;
							check_pass = false;
						}else{
							if(!$('#coordinate_'+fn).val().match(/^([-+]?)([\d]{1,2})(((\.*)(\d+)(,)))(\s*)(([-+]?)([\d]{1,3})((\.*)(\d+))?)$/g) || !$('#cust_coordinate_'+fn+'_'+un).val().match(/^([-+]?)([\d]{1,2})(((\.*)(\d+)(,)))(\s*)(([-+]?)([\d]{1,3})((\.*)(\d+))?)$/g)){
								data['status'] = false;
								data['messages'] = "Submission is not saved! Coordinate format is invalid!";
								cto_messages_sub_show(data, fn);
								check_req_form[fn] = false;
								check_pass = false;
							}else{
								check_req_form[fn] = true;
							}
						}
					}
				}
			}
		}
		
		if(check_pass){
			return true;
		}else{
			for(fn = 0; fn <= form_num; fn++){
				if(check_req_form[fn]){
					data['status'] = false;
					data['messages'] = "Submission is not saved!";
					cto_messages_sub_show(data, fn);
				}
			}
			return false;
		}
	}
	
    $("#cto_submit_all").on('click', function(e) { 
		cto_getFormValue(form_jml);
		if(cto_checkFormValue(form_jml)){
			cto_loading_show(); 
			//ctoerr_messages_clear(ctojs_fields); 
			//cto_messages_hide();
			for(fn = 0; fn <= form_jml; fn++){
				fk_sto_id[fn] = $('#fk_sto_id_'+fn).find(":selected").val();
				sto_name[fn] = $('#fk_sto_id_'+fn+' option:selected').text(); //$('#sto_name_'+fn).val();
				address[fn] = $('#address_'+fn).val();
				house_hold[fn] = $('#house_hold_'+fn).val();
				coordinate[fn] = $('#coordinate_'+fn).val();
				odp[fn] = $('#odp_'+fn).val();
				$.ajax({ 
					type: "POST",
					async : false,
					url: "<?php echo base_url(); ?>submission/<?php if(isset($cto_id)){echo "update";}else{echo "insert"; }?>", 
					data: {'fk_sto_id' : fk_sto_id[fn], 'sto_name' : sto_name[fn], 'address' : address[fn], 'house_hold' : house_hold[fn], 'coordinate' : coordinate[fn], 'odp' : odp[fn], 'potentials': unsc_jml[fn], 'fn' : fn},
					dataType: 'json', 
					success: function(data){ 
						if(data['status']){ 
							document.getElementById("code_id_lb_"+data["fn"]).innerHTML = "ID : "+data["code_id"];
							$("#code_id_"+data["fn"]).val(data["code_id"]);
							if(cto_submit_unsc(data["fn"], data["id"])){
								cto_messages_sub_show(data, data["fn"]); 
								cto_clearingFormSub(data["fn"]);
							}else{
								data['status'] = false;
								data['messages'] = "UNSC is not saved!";
								cto_messages_sub_show(data, data["fn"]); 
							}
							//window.location = "<?php echo base_url(); ?>cto_menu/create"; 
						}else{ 
							//ctoerr_messages(ctojs_fields, data); 
							cto_messages_sub_show(data, data["fn"]); 
						} 
					}, 
					error: function (response) { 
						cto_loading_hide(); 
					} 
				});
			}
			cto_loading_hide(); 
		}
		});
	
	function cto_submit_unsc(fn, fk_submission_id){
		fn = fn;
		stat = true;
		for(un = 0; un < unsc_jml[fn]; un++){
				xfk_submission_id = fk_submission_id;
				xcust_name = $('#cust_name_'+fn+'_'+un).val();
				xcust_address = $('#cust_address_'+fn+'_'+un).val();
				xcpackage = $('#package_'+fn+'_'+un).val();
				xcust_coordinate = $('#cust_coordinate_'+fn+'_'+un).val();
			$.ajax({ 
				type: "POST", 
				async : false,
				url: "<?php echo base_url(); ?>unsc/<?php if(isset($cto_id)){echo "update";}else{echo "insert"; }?>", 
				data: {'fk_submission_id' : xfk_submission_id, 'cust_name' : xcust_name, 'cust_address' : xcust_address, 'package' : xcpackage, 'cust_coordinate' : xcust_coordinate, 'type' : 1},
				dataType: 'json', 
				success: function(data){ 
					if(!data['status']){ 
						stat = false;
					}
				}, 
				error: function (response) { 
				
				} 
			});
		}
		return stat;
	}
		
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

	
    
    function ctoGetSelect(cto_elementid, addr){ 
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

	//assign nama package sesuai dropdown ke hidden field pertama kali 
	//document.getElementById("sto_name_0").value = $('#fk_sto_id_0 option:selected').text(); 
	//assign nama package sesuai dropdown ke hidden field
	function cto_SetStoName(form_num){
		document.getElementById("sto_name_"+form_num).value = $('#fk_sto_id_'+form_num+' option:selected').text(); 
	}
	
	function cto_messages_sub_show(ctoajx_response, form_num){
		var d = document.getElementById("cto_messages_"+form_num);
		if(ctoajx_response["status"]){
			d.className += "alert alert-success alert-dismissible";
			d.innerHTML = ctoajx_response["messages"];
		}else{
			d.className += "alert alert-danger alert-dismissible";
			d.innerHTML = ctoajx_response["messages"];
		}
		
		document.getElementById("cto_messages_"+form_num).style.display = "block";
	}
	
	function cto_messages_sub_hide(form_num){
		var d = document.getElementById("cto_messages_"+form_num);
		d.className = d.className.replace(/\balert-danger\b/g, "");
		d.className = d.className.replace(/\balert-dismissible\b/g, "");
		d.className = d.className.replace(/\balert\b/g, "");
		d.innerHTML = "";
		document.getElementById("cto_messages_"+form_num).style.display = "block";
	}
	
	function findAutocoplete(url, keyword) {
	var	tmp = null;
    jQuery.ajax({
        'async': false, 
		'type': "POST", 
		'global': false, 
		'dataType': 'json', 
		'url': addr,
		'data': { 'keyword': keyword}, 
        success: function(data) {
            tmp = data;
        }
    });
	return tmp;
}
	
	function cto_reloadAutoComplete(){
	  $(".autocomplete_address").autocomplete({
		source: function( request, response ) {
			$.ajax( {
			  url: "<?php echo base_url(); ?>unsc/get_autocomplete_cust_address",
			  'async': false, 
			  'type': "POST", 
			  'global': false, 
			  dataType: "json",
			  data: {
				term: request.term
			  },
			  success: function( data ) {
				response( data );
			  }
			} );
		  }
	  });
	  
	  $(".autocomplete_package").autocomplete({
		source: function( request, response ) {
			$.ajax( {
			  url: "<?php echo base_url(); ?>unsc/get_autocomplete_package",
			  'async': false, 
			  'type': "POST", 
			  'global': false, 
			  dataType: "json",
			  data: {
				term: request.term
			  },
			  success: function( data ) {
				response( data );
			  }
			} );
		  }
	  });
	  
	  $(".autocomplete_cust_address").autocomplete({
		source: function( request, response ) {
			$.ajax( {
			  url: "<?php echo base_url(); ?>unsc/get_autocomplete_cust_address",
			  'async': false, 
			  'type': "POST", 
			  'global': false, 
			  dataType: "json",
			  data: {
				term: request.term
			  },
			  success: function( data ) {
				response( data );
			  }
			} );
		  }
	  });
	}
	
	
	
</script>
<?php $this->load->view('layouts/footer');?>
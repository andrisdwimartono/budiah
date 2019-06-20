<div class="content-wrapper">
    <!-- Main content -->
<section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-10 connectedSortable">
          <!-- Calendar -->
          <div class="box box-solid">
            <div class="box-header">
              <i class="fa fa-wpforms"></i>

              <h4 class="box-title">Create Menu</h4>
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
			  <form class="form-horizontal" id="cto_form" method="post">
				<div class="form-group" id="ctof_controller">
				<label for="controller" class="col-sm-2 control-label">Controller<font style="color:red;">*</font></label>
				<div class="col-sm-5">
				<input id="controller" type="text" name="controller" <?php if(isset($controller)){echo "value=\"".$controller."\"";} ?> class="form-control" placeholder="submission">
				<span class="help-block" id="ctomesserror_controller"></span>
				</div>
		</div>


		<div class="form-group" id="ctof_method">
				<label for="method" class="col-sm-2 control-label">Method</label>
				<div class="col-sm-5">
				<input id="method" type="text" name="method" <?php if(isset($method)){echo "value=\"".$method."\"";} ?> class="form-control" placeholder="create_new">
				<span class="help-block" id="ctomesserror_method"></span>
				</div>
		</div>


		<div class="form-group" id="ctof_path">
				<label for="path" class="col-sm-2 control-label">Path<font style="color:red;">*</font></label>
				<div class="col-sm-5">
				<input id="path" type="text" name="path" <?php if(isset($path)){echo "value=\"".$path."\"";} ?> class="form-control" placeholder="submission/create_new">
				<span class="help-block" id="ctomesserror_path"></span>
				</div>
		</div>


		<div class="form-group" id="ctof_name">
    	<label for="name" class="col-sm-2 control-label">Name<font style="color:red;">*</font></label>
    	<div class="col-sm-5">
				<input id="name" type="text" name="name" <?php if(isset($name)){echo "value=\"".$name."\"";} ?> class="form-control" placeholder="Create New Recomendation">
				<span class="help-block" id="ctomesserror_name"></span>
				</div>
		</div>


		<div class="form-group" id="ctof_fk_menupackage_id">
				<label for="fk_menupackage_id" class="col-sm-2 control-label">Menu Package<font style="color:red;">*</font></label>
				<div class="col-sm-5">
				<select class="form-control select2" style="width: 100%;" id="fk_menupackage_id" name="fk_menupackage_id" data-error=".errorTxt6">
					
				</select>
				<span class="help-block" id="ctomesserror_fk_menupackage_id"></span>
				</div>
		</div>
		
		
				<input id="menupackage_name" type="hidden" name="menupackage_name" <?php if(isset($menupackage_name)){echo "value=\"".$menupackage_name."\"";} ?> class="form-control" placeholder="Submission">
				<span class="help-block" id="ctomesserror_menupackage_name"></span>

		<div class="form-group" id="ctof_sequence">
				<label for="sequence" class="col-sm-2 control-label">Sequence Number<font style="color:red;">*</font></label>
				<div class="col-sm-5">
				<input id="sequence" type="text" name="sequence" <?php if(isset($sequence)){echo "value=\"".$sequence."\"";} ?> class="form-control" placeholder="3">
				<span class="help-block" id="ctomesserror_sequence"></span>
				</div>
		</div>


		<div class="form-group" id="ctof_is_shown">
				<label for="is_shown" class="col-sm-2 control-label">Show This Menu on Left Pane<font style="color:red;">*</font></label>
				<div class="col-sm-5">
				<select class="form-control select2" style="width: 100%;" id="is_shown" name="is_shown" data-error=".errorTxt6">
					<option value="1" <?php if(isset($is_shown) && $is_shown == "1"){echo "selected=\"selected\"";} ?> > Show </option>
					<option value="-1" <?php if(isset($is_shown) && $is_shown == "-1"){echo "selected=\"selected\"";} ?> > Hide </option>
				</select>
				<span class="help-block" id="ctomesserror_is_shown"></span>
				</div>
		</div>


		<div class="form-group" id="ctof_is_active">
				<label for="is_active" class="col-sm-2 control-label">Active<font style="color:red;">*</font></label>
				<div class="col-sm-5">
				<select class="form-control select2" style="width: 100%;" id="is_active" name="is_active" data-error=".errorTxt6">
					<option value="1" <?php if(isset($is_active) && $is_active == "1"){echo "selected=\"selected\"";} ?> > Active </option>
					<option value="-1" <?php if(isset($is_active) && $is_active == "-1"){echo "selected=\"selected\"";} ?> > Deactive </option>
				</select>
				<span class="help-block" id="ctomesserror_is_active"></span>
				</div>
		</div>
				
				<div class="row align-right">
					<div class="col-sm-5">
					  
					</div>
					<div class="col-sm-2">
					  <button type="submit" class="btn btn-primary btn-block btn-flat"><?php if(isset($cto_id)){echo "Update";}else{echo "Save"; }?></button>
					</div>
					<!-- /.col -->
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
	// var ctojs_fields = ['controller', 'method', 'path', 'name', 'fk_menupackage_id', 'sequence', 'is_shown', 'is_active'];
	
	// $("#cto_form").on('submit', function(e) {
		// e.preventDefault();
		// cto_loading_show();
		// ctoerr_messages_clear(ctojs_fields);
		// cto_messages_hide();
		// <?php if(isset($cto_id)){echo "id = ".$cto_id.";\n";}?>
		// controller = $('#controller').val();
        // method = $('#method').val();
        // path = $('#path').val();
        // name = $('#name').val();
        // fk_menupackage_id = $('#fk_menupackage_id').val();
        // menupackage_name = $('#menupackage_name').val();
        // sequence = $('#sequence').val();
        // is_shown = $('#is_shown').val();
        // is_active = $('#is_active').val();
		// $.ajax({
			// type: "POST",
			// url: "<?php echo base_url(); ?>cto_menu/<?php if(isset($cto_id)){echo "update";}else{echo "insert"; }?>",
			// data: { <?php if(isset($cto_id)){echo "'id' : id, ";}?> 'controller' : controller, 'method' : method, 'path' : path, 'name' : name, 'fk_menupackage_id' : fk_menupackage_id, 'menupackage_name' : menupackage_name, 'sequence' : sequence, 'is_shown' : is_shown, 'is_active' : is_active},
			// dataType: 'json',                         
			// success: function(data){
				// cto_loading_hide();
				// if(data['status']){
					// //alert(data['messages']);
					// cto_messages_show(data);
					// //window.location = "<?php echo base_url(); ?>cto_user/create";
				// }else{
					// //alert(data['messages']);
					// //show error for each fields
					// ctoerr_messages(ctojs_fields, data);
					// cto_messages_show(data);
				// }
			// },
			// error: function (response) {
			   // //Handle error
			   // cto_loading_hide();
			   // //alert(response['messages']);
			// }           
		// });
		// cto_loading_hide();
	// });
//	------javascript ctojs_fields-------
var ctojs_fields = ['controller', 'method', 'path', 'name', 'fk_menupackage_id', 'sequence', 'is_shown', 'is_active'];

//---------javascript inside ajax for post-------

    $("#cto_form").on('submit', function(e) { 
       	e.preventDefault(); 
       	cto_loading_show(); 
       	ctoerr_messages_clear(ctojs_fields); cto_messages_hide(); 
       	<?php if(isset($cto_id)){echo "id = ".$cto_id.";\n";}?> 
       controller = $('#controller').val();
       method = $('#method').val();
       path = $('#path').val();
       name = $('#name').val();
       fk_menupackage_id = $('#fk_menupackage_id').val();
       menupackage_name = $('#menupackage_name').val();
       sequence = $('#sequence').val();
       is_shown = $('#is_shown').val();
       is_active = $('#is_active').val();
       	$.ajax({ 
          	type: "POST", 
          	url: "<?php echo base_url(); ?>cto_menu/<?php if(isset($cto_id)){echo "update";}else{echo "insert"; }?>", 
          data: { <?php if(isset($cto_id)){echo "'id' : id, ";}?>'controller' : controller, 'method' : method, 'path' : path, 'name' : name, 'fk_menupackage_id' : fk_menupackage_id, 'menupackage_name' : menupackage_name, 'sequence' : sequence, 'is_shown' : is_shown, 'is_active' : is_active},
          	dataType: 'json', 
          	success: function(data){ 
             	cto_loading_hide(); 
             	if(data['status']){ 
                	cto_messages_show(data); 
                	//window.location = "<?php echo base_url(); ?>cto_menu/create"; 
             	}else{ 
                	ctoerr_messages(ctojs_fields, data); 
                	cto_messages_show(data); 
             	} 
          	}, 
          	error: function (response) { 
             	cto_loading_hide(); 
          	} 
       	}); 
       	cto_loading_hide(); 
    	});
		
	$("input[name='sequence']").TouchSpin({
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
	  
	  ctoGetSelect("fk_menupackage_id", "<?php echo base_url(); ?>cto_menu/cto_getDatas");
	  function ctoGetSelect(cto_elementid, addr, param){
			param = [];
			param[0] = 1;
			var menupack = ajaxGetValue(addr, param);
			var opt = '';
			for (i = 0; i < menupack.length; i++) {
				opt+='<option value="'+menupack[i][0]+'">'+menupack[i][1]+'</option>';
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
				'data': { 'data':  jsonString},
				'success': function (data) {
					tmp = data;
				}
			});
			return tmp;
		}();
		return data;
		}
		
		//assign nama package sesuai dropdown ke hidden field pertama kali
		document.getElementById("menupackage_name").value = $('#fk_menupackage_id option:selected').text();
		//assign nama package sesuai dropdown ke hidden field
		$("#fk_menupackage_id").on('change', function(e) {
			document.getElementById("menupackage_name").value = $('#fk_menupackage_id option:selected').text();
		});
</script>
<?php $this->load->view('layouts/footer');?>
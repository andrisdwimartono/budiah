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

              <h4 class="box-title">Check Jaringan</h4>
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
				
					<div class="form-group" id="ctof_position">
						<label for="pt_type" class="col-sm-2 control-label">PT Type<font style="color:red;">*</font></label>
						<div class="col-sm-5">
						<select class="form-control select2" style="width: 100%;" id="pt_type" name="pt_type" data-error=".errorTxt6">
							
						</select>
						<span class="help-block" id="ctomesspt_type"></span>
						</div>
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
	var ctojs_fields = ['id', 'pt_type'];
	$("#cto_form").on('submit', function(e) {
		e.preventDefault();
		cto_loading_show();
		cto_messages_hide();
		<?php if(isset($cto_id)){echo "id = ".$cto_id.";\n";}?>
		<?php if(isset($cto_id)){echo "fk_submission_id = ".$cto_id.";\n";}?>
		pt_type = $('#pt_type').val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>submission/update_jaringan",
			data: { <?php if(isset($cto_id)){echo "'fk_submission_id' : fk_submission_id, ";}?><?php if(isset($cto_id)){echo "'id' : id, ";}?>'pt_type' : pt_type},
			dataType: 'json',                         
			success: function(data){
				cto_loading_hide();
				if(data['status']){
					//alert(data['messages']);
					cto_messages_show(data);
					//window.location = "<?php echo base_url(); ?>cto_user/creat";
				}else{
					//alert(data['messages']);
					//show error for each fields
					//ctoerr_messages(ctojs_fields, data);
					cto_messages_show(data);
				}
			},
			error: function (response) {
			   //Handle error
			   cto_loading_hide();
			   //alert(response['messages']);
			}           
		});
		cto_loading_hide();
	});
	
	$(function () {
		//Initialize Select2 Elements
		$('.select2').select2()
	  })
	  
	  
		ctoGetSelect2("pt_type", "<?php echo base_url(); ?>submission/cto_getpt_typeDatas");
		function ctoGetSelect2(cto_elementid, addr, param){
			param = [];
			param[0] = 1;
			var ptty = ajaxGetValue(addr, param);
			var opt = '';
			for (i = 0; i < ptty.length; i++) {
				<?php if(isset($pt_type)){ ?>
				if(ptty[i]['value'] == '<?php echo $pt_type; ?>'){
					opt+='<option value="'+ptty[i]['value']+'" selected>'+ptty[i]['value']+'</option>';
				}else{
				<?php } ?>
				opt+='<option value="'+ptty[i]['value']+'">'+ptty[i]['value']+'</option>';
				<?php if(isset($pt_type)){ ?>
				}
				<?php } ?>
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
	
</script>
<?php $this->load->view('layouts/footer');?>
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

              <h4 class="box-title">Create Menu Package</h4>
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
				<div class="form-group" id="ctof_name">
						<label for="name" class="col-sm-2 control-label">Name<font style="color:red;">*</font></label>
						<div class="col-sm-5">
						<input id="name" type="text" name="name" <?php if(isset($name)){echo "value=\"".$name."\"";} ?> class="form-control" placeholder="User Management">
						<span class="help-block" id="ctomesserror_name"></span>
						</div>
				</div>


				<div class="form-group" id="ctof_sequence">
						<label for="sequence" class="col-sm-2 control-label">Sequence Number<font style="color:red;">*</font></label>
						<div class="col-sm-5">
						<input id="sequence" type="text" name="sequence" <?php if(isset($sequence)){echo "value=\"".$sequence."\"";} ?> class="form-control" placeholder="8">
						<span class="help-block" id="ctomesserror_sequence"></span>
						</div>
				</div>


				<div class="form-group" id="ctof_is_active">
						<label for="is_active" class="col-sm-2 control-label">Is Active<font style="color:red;">*</font></label>
						<div class="col-sm-5">
						<select class="form-control select2" style="width: 100%;" id="is_active" name="is_active" data-error=".errorTxt6">
							<option value="1" <?php if(isset($is_active) && $is_active == "1"){echo "selected=\"selected\"";} ?> > Active </option>
							<!--<option value="-1" <?php if(isset($is_active) && $is_active == "-1"){echo "selected=\"selected\"";} ?> > Deactive </option>-->
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
	var ctojs_fields = ['name', 'sequence', 'is_active'];
	$("#cto_form").on('submit', function(e) {
		e.preventDefault();
		cto_loading_show();
		ctoerr_messages_clear(ctojs_fields);
		cto_messages_hide();
		<?php if(isset($cto_id)){echo "id = ".$cto_id.";\n";}?>
		name = $('#name').val();
        sequence = $('#sequence').val();
        is_active = $('#is_active').val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>cto_menupackage/<?php if(isset($cto_id)){echo "update";}else{echo "insert"; }?>",
			data: { <?php if(isset($cto_id)){echo "'id' : id, ";}?>'name' : name, 'sequence' : sequence, 'is_active' : is_active},
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
</script>
<?php $this->load->view('layouts/footer');?>
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

              <h4 class="box-title">Create User</h4>
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
						<input id="name" type="text" name="name" <?php if(isset($name)){echo "value=\"".$name."\"";} ?> class="form-control" placeholder="Luqman Kurniawan">
						<span class="help-block" id="ctomesserror_name"></span>
					 </div>
				</div>


				<div class="form-group" id="ctof_email">
					 <label for="email" class="col-sm-2 control-label">Email</label>
					 <div class="col-sm-5">
						<input id="email" type="text" name="email" <?php if(isset($email)){echo "value=\"".$email."\"";} ?> class="form-control" placeholder="luqman@gmail.com">
						<span class="help-block" id="ctomesserror_email"></span>
					 </div>
				</div>
				
				
				<div class="form-group" id="ctof_position">
					<label for="position" class="col-sm-2 control-label">Position<font style="color:red;">*</font></label>
					<div class="col-sm-5">
					<select class="form-control select2" style="width: 100%;" id="position" name="position" data-error=".errorTxt6">
						<option value="" <?php if(!isset($position) || $position == ""){echo "selected=\"selected\"";} ?> > - </option>
						<option value="Home Service" <?php if(isset($position) && $position == "Home Service"){echo "selected=\"selected\"";} ?> > Home Service </option>
						<option value="Optima" <?php if(isset($position) && $position == "Optima"){echo "selected=\"selected\"";} ?> > Optima </option>
					</select>
					<span class="help-block" id="ctomesserror_position"></span>
					</div>
				</div>
				
				
				
				<div class="form-group" id="ctof_chat_id_telegram">
					 <label for="chat_id_telegram" class="col-sm-2 control-label">Chat id Telegram</label>
					 <div class="col-sm-5">
						<input id="chat_id_telegram" type="text" name="chat_id_telegram" <?php if(isset($chat_id_telegram)){echo "value=\"".$chat_id_telegram."\"";} ?> class="form-control" placeholder="1239871238">
						<span class="help-block" id="ctomesserror_chat_id_telegram"></span>
					 </div>
				</div>
				
				
				<div class="form-group" id="ctof_fk_company_id">
						<label for="fk_company_id" class="col-sm-2 control-label">Witel<font style="color:red;">*</font></label>
						<div class="col-sm-5">
						<select class="form-control select2" style="width: 100%;" id="fk_company_id" name="fk_company_id" data-error=".errorTxt6">
							
						</select>
						<span class="help-block" id="ctomesserror_fk_company_id"></span>
						</div>
				</div>


				<div class="form-group" id="ctof_username">
					 <label for="username" class="col-sm-2 control-label">Username<font style="color:red;">*</font></label>
					 <div class="col-sm-5">
						<input id="username" type="text" name="username" <?php if(isset($username)){echo "value=\"".$username."\"";} ?> class="form-control" placeholder="luqman">
						<span class="help-block" id="ctomesserror_username"></span>
					 </div>
				</div>


				<div class="form-group" id="ctof_password">
					 <label for="password" class="col-sm-2 control-label">Password<font style="color:red;">*</font></label>
					 <div class="col-sm-5">
						<input id="password" type="password" name="password" <?php if(isset($password)){echo "value=\"".$password."\"";} ?> class="form-control" placeholder="bukansuperm4N">
						<span class="help-block" id="ctomesserror_password"></span>
					 </div>
				</div>


				<div class="form-group" id="ctof_confirmation_password">
					 <label for="confirmation_password" class="col-sm-2 control-label">Retype Password<font style="color:red;">*</font></label>
					 <div class="col-sm-5">
						<input id="confirmation_password" type="password" name="confirmation_password" <?php if(isset($confirmation_password)){echo "value=\"".$confirmation_password."\"";} ?> class="form-control" placeholder="bukansuperm4N">
						<span class="help-block" id="ctomesserror_confirmation_password"></span>
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
	var ctojs_fields = ['name', 'email', 'position', 'chat_id_telegram', 'fk_company_id', 'username', 'password'];
	$("#cto_form").on('submit', function(e) {
		e.preventDefault();
		cto_loading_show();
		ctoerr_messages_clear(ctojs_fields);
		cto_messages_hide();
		<?php if(isset($cto_id)){echo "id = ".$cto_id.";\n";}?>
		name = $('#name').val();
		email = $('#email').val();
		position = $('#position').val();
		chat_id_telegram = $('#chat_id_telegram').val();
		fk_company_id = $('#fk_company_id').val();
		username = $('#username').val();
		password = $('#password').val();
		if(check_confirmation()){
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>cto_user/<?php if(isset($cto_id)){echo "update";}else{echo "insert"; }?>",
				data: { <?php if(isset($cto_id)){echo "'id' : id, ";}?>'name' : name, 'email' : email, 'position' : position, 'chat_id_telegram' : chat_id_telegram, 'fk_company_id' : fk_company_id, 'username' : username, 'password' : password},
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
		}
		cto_loading_hide();
	});
	
	$(function () {
		//Initialize Select2 Elements
		$('.select2').select2()
	  })
	  
	  ctoGetSelect("fk_company_id", "<?php echo base_url(); ?>cto_user/cto_getCompanyDatas");
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
		
		ctoGetSelect2("position", "<?php echo base_url(); ?>cto_user/cto_getPositionDatas");
		  function ctoGetSelect2(cto_elementid, addr, param){
				param = [];
				param[0] = 1;
				var menupack = ajaxGetValue(addr, param);
				var opt = '';
				for (i = 0; i < menupack.length; i++) {
					<?php if(isset($position)){ ?>
					if(menupack[i][1] == '<?php echo $position; ?>'){
						opt+='<option value="'+menupack[i][1]+'" selected>'+menupack[i][1]+'</option>';
					}else{
					<?php } ?>
					opt+='<option value="'+menupack[i][1]+'">'+menupack[i][1]+'</option>';
					<?php if(isset($position)){ ?>
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
	
	function check_confirmation(){
		if($("#confirmation_password").val() == ""){
			ctoerr_messages_confirmation_pass('Confirmation password cannot be empty');
			return false;
		}else if($("#password").val() == $("#confirmation_password").val()){
			ctoerr_messages_confirmation_pass_clear('');
			return true;
		}else{
			ctoerr_messages_confirmation_pass('Password doesn\'t match');
			return false;
		}
	}
	
	function ctoerr_messages_confirmation_pass(confirmation_password_message){
		//add class for class form-group with class has-error
		var d = document.getElementById("ctof_confirmation_password");
		d.className += " has-error";
		//add message
		var e = document.getElementById("ctomesserror_confirmation_password");
		e.innerHTML = confirmation_password_message;
	}
	
	function ctoerr_messages_confirmation_pass_clear(confirmation_password_message){
			//remove class has-error
		var d = document.getElementById("ctof_confirmation_password");
		d.className = d.className.replace(/\bhas-error\b/g, "");
		//remove message
		var e = document.getElementById("ctomesserror_confirmation_password");
		e.innerHTML = "";
	}
	$("#confirmation_password").on('focusout', function(e) {
		check_confirmation();
	});
	
	
</script>
<?php $this->load->view('layouts/footer');?>
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

              <h4 class="box-title">Grant User Menu</h4>
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
				<!-- Custom Tabs -->
				  <div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
					<?php 
					$num = 0;
					//untuk package menu berbentuk tab
					foreach($ctovar_menupackage as $ctovar_menupack){ ?>
						<li <?php if($num == 0){echo  'class="active"';} ?>><a href="#<?php echo "ctoid_tab".$ctovar_menupack["sequence"];?>" data-toggle="tab"><?php echo $ctovar_menupack["name"];?></a></li>
					<?php 
					$num++;
					} ?>
						<li><a href="#ctoid_tabothers" data-toggle="tab">Others</a></li>
					</ul>
					<div class="tab-content">
						<?php 
						$num = 0;
						//untuk package menu berbentuk isi tab
						foreach($ctovar_menupackage as $ctovar_menupack){ ?>
						<div class="tab-pane <?php if($num == 0){echo  'active';} ?>" id="ctoid_tab<?php echo $ctovar_menupack["sequence"];?>">
							<?php 
							//untuk semua menu berbentuk check box
							foreach($ctovar_menu as $ctovar_men){ 
								if($ctovar_men['fk_menupackage_id'] == $ctovar_menupack['id']){ ?>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="ctof_menu_<?php echo $ctovar_men['id']; ?>" id="ctof_menu_<?php echo $ctovar_men['id']; ?>">
									<?php echo $ctovar_men['name'];
									if($ctovar_men['is_shown'] == 1){
									?>
									<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="<== Shown in left pane menus!"></i>
									<?php } ?>
								</label>
							</div>
							<?php 
								}
							} ?>
						</div>
						<?php 
						$num++;
						} ?>
						
						<div class="tab-pane" id="ctoid_tabothers">
							<?php 
							//untuk semua menu berbentuk check box
							foreach($ctovar_menu as $ctovar_men){ 
								if($ctovar_men['fk_menupackage_id'] == "" || $ctovar_men['fk_menupackage_id'] == null){ ?>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="ctof_menu_<?php echo $ctovar_men['id']; ?>" id="ctof_menu_<?php echo $ctovar_men['id']; ?>">
									<?php echo $ctovar_men['name'];
									if($ctovar_men['is_shown'] == 1){
									?>
									<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="<== Shown in left pane menus!"></i>
									<?php } ?>
								</label>
							</div>
							  
							<?php 
								}
							} ?>
							<input type="checkbox" name="ctof_menu_x" id="ctof_menu_x">
						</div>
					  <!-- /.tab-pane -->
					</div>
					<!-- /.tab-content -->
				  </div>
				  <!-- nav-tabs-custom -->
				
				
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
//	------javascript ctojs_fields-------
var ctojs_fields = [<?php 
$cto_num = 0;
foreach($ctovar_menu as $ctovar_men){ 
	$cto_num++;
	echo "'ctof_menu_".$ctovar_men["id"]."'";
	if($cto_num < count($ctovar_menu)){
		echo ", ";
	}
} ?>];

//initial value
<?php 
foreach($ctovar_menu as $ctovar_men){ 
	echo "var ctof_menu_".$ctovar_men["id"]." = -1;\n";
} ?>
	
	ctoGetDataUserMenu();
	getFieldValue();
	
	//get user menu data 
	function ctoGetDataUserMenu(cto_id = <?php if(isset($cto_id)){echo $cto_id;}else{echo "null";}?>){
		cto_usm = ajaxGetValue("<?php echo base_url(); ?>cto_user_menu/cto_GetDataUserMenu", cto_id);
		$("#ctof_menu_x").prop('checked', true);
		//var opt = '';
		for (i = 0; i < cto_usm.length; i++) {
			$(cto_usm[i]).prop('checked', true);
			//opt+=cto_usm[i];
		}
		//alert(opt);
	}

	

//set variable value that passed when the form is submitted
function getFieldValue(){
	<?php 
	foreach($ctovar_menu as $ctovar_men){ ?>
	if($('input[name="ctof_menu_<?php echo $ctovar_men["id"]; ?>"]:checked').length > 0){
		ctof_menu_<?php echo $ctovar_men["id"];?> = 1;
	}else{
		ctof_menu_<?php echo $ctovar_men["id"];?> = -1;
	}
	<?php
	echo "\n";	
	} ?>
}
//alert(ctof_menu_1);
//---------javascript inside ajax for post-------
	
    $("#cto_form").on('submit', function(e) { 
       	e.preventDefault(); 
       	cto_loading_show(); 
       	//ctoerr_messages_clear(ctojs_fields); 
		cto_messages_hide();
		//update field value before send
		getFieldValue();
       	<?php if(isset($cto_id)){echo "id = ".$cto_id.";\n";}?> 
       	$.ajax({ 
          	type: "POST", 
          	url: "<?php echo base_url(); ?>cto_user_menu/<?php if(isset($cto_id)){echo "update";}else{echo "insert"; }?>", 
          data: { <?php if(isset($cto_id)){echo "'id' : id, ";}
			$cto_num = 0;
			foreach($ctovar_menu as $ctovar_men){ 
				$cto_num++;
				echo "'ctof_menu_".$ctovar_men["id"]."' : ctof_menu_".$ctovar_men["id"]."";
				if($cto_num < count($ctovar_menu)){
					echo ", ";
				}
			} ?>},
          	dataType: 'json', 
          	success: function(data){ 
             	cto_loading_hide(); 
             	if(data['status']){ 
                	cto_messages_show(data); 
                	//window.location = "<?php echo base_url(); ?>cto_menu/create"; 
             	}else{ 
                	cto_messages_show(data); 
             	} 
          	}, 
          	error: function (response) { 
             	cto_loading_hide(); 
          	} 
       	}); 
       	cto_loading_hide(); 
    	});
	
	
	$(function () {
		//Initialize Select2 Elements
		$('.select2').select2()
	  })
	  
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
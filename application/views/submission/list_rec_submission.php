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

              <h4 class="box-title">Recomendation</h4>
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
            <div class="box-body">
              <!-- CTOF Content here-->
				<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th>ID</th>
                  <th>Address</th>
				  <th>Est.HH</th>
                  <th>Potential</th>
                  <th>Request ODP</th>
                  <th>Demand</th>
				  <th>STO</th>
				  <th></th>
                </tr>
                </thead>
              </table>
			  <div class="row align-right">
					<div class="col-sm-2">
					  <button type="submit" class="btn btn-primary btn-block btn-flat btn_submit">Submit</button>
					</div>
					<!-- /.col -->
				  </div>
			  <!-- CTOF Content here end! -->
            </div>
            <!-- /.box-body -->
			<div id="cto_overlay" class="overlay">
			  <div id="cto_mengecek"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
			</div>
          </div>
          <!-- /.box -->
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
	
</div>
<!-- Select2 -->
<script src="<?php echo base_url().'assets/'; ?>bower_components/jquery/src/jquery.redirect.js"></script>
<script type="text/javascript" language="javascript" >
	var cto_id = [];
	function ajaxGetDetails(addr, id){
			var data = function () {
			var tmp = null;
			$.ajax({
				'async': false,
				'type': "POST",
				'global': false,
				'dataType': 'json',
				'url': addr,
				'data': { 'fk_submission_id':  id},
				'success': function (data) {
					tmp = data;
				}
			});
			return tmp;
		}();
		return data;
		}
		

 $(document).ready(function(){
	var table = null;
    fetch_data();
	
	
  function fetch_data(){
	cto_loading_show();
	$('#example1').DataTable().destroy();
    var dataTable = $('#example1').DataTable({
	"autoWidth": false,
	dom: 'Bfrtip',
	"scrollX" : true,
    "processing" : true,
    "serverSide" : true,
	"pagingType": "full_numbers",
    "ajax" : {
     url:"<?php echo base_url(); ?>submission/fetch_rec",
     type:"POST",
	 async: false
    }
   });
   cto_loading_hide();
   table = dataTable;
  }
  
  
  $('#example1').on('click', 'tr', function () {
	  cto_id = [];
        $(this).toggleClass('selected');
		if($(this).hasClass('selected')){
			//$(this).css('background-color', 'Green');
			$(this).find('.fa-check-circle').css('color', '#44ff3a');
		}else{
			if($(this).index() == 0){
				//$(this).css('background-color', '#F9F9F9');
				$(this).find('.fa-check-circle').css('color', 'Black');
			}else{
				//$(this).css('background-color', '#FFFFFF');
				$(this).find('.fa-check-circle').css('color', 'Black');
			}
		}
		data = table.rows('.selected').data();
		data.each( function ( value, index ) {
			//alert($(value[0]).attr("data-id"));
				cto_id.push($(value[0]).attr("data-id"));
				//alert(cto_id.toString());
			});
		//alert( table.rows('.selected').data() +' row(s) selected' );
    } );
  
  $(document).on('click', '.update', function(){
	cto_loading_show();
   var id = $(this).attr("data-id");
	$.redirect("<?php echo base_url(); ?>submission/viewSubmission", {'fk_submission_id': id, 'cto_url' : 'submission', 'is_recomendation' : true});
  });
  
  $(document).on('click', '.btn_submit', function(){
	cto_loading_show();
   id = cto_id.toString();
   //alert(cto_id.toString());
	$.redirect("<?php echo base_url(); ?>submission/sendSubmission", {'fk_submission_id': '('+id+')', 'cto_url' : 'submission'});
  });
  
  // var table = $('#example1').DataTable();
	// // table.column( 0 ).visible( false );
	// table.on( 'order.dt search.dt', function () {
        // t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            // cell.innerHTML = i+1;
        // } );
    // } ).draw();
  
 });
</script>

<?php $this->load->view('layouts/footer');?>
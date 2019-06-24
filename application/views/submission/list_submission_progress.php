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

              <h4 class="box-title">Progress</h4>
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
                  <th>Status</th>
				  <th>Completion</th>
				  <th>Last Update</th>
                </tr>
                </thead>
              </table>
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
    "order" : [],
    "ajax" : {
     url:"<?php echo base_url(); ?>submission/fetch_progress",
     type:"POST",
    }
   });
   cto_loading_hide();
   table = dataTable;
  }
  
  
  
  $(document).on('click', '.update', function(){
	cto_loading_show();
   var id = $(this).attr("data-id");
	$.redirect("<?php echo base_url(); ?>submission/viewSubmission", {'fk_submission_id': id, 'cto_url' : 'submission/history'});
  });
  
  $(document).on('click', '.update2', function(){
	cto_loading_show();
   var id = $(this).attr("data-id");
	$.redirect("<?php echo base_url(); ?>submission/log_transaksi", {'fk_submission_id': id, 'cto_url' : 'submission/history'});
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
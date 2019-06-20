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

              <h4 class="box-title">User Log</h4>
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
                  <th>Username</th>
                  <th>IP</th>
				  <th>Menu</th>
                  <th>Controller</th>
				  <th>Method</th>
                  <th>Time</th>
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
<script type="text/javascript" language="javascript" >
	
 $(document).ready(function(){
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
     url:"<?php echo base_url(); ?>cto_user/fetch_logaccess/<?php echo $fk_user_id; ?>",
     type:"POST"
    }
   });
   cto_loading_hide();
  }
  
  
  
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
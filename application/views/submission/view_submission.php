<div class="content-wrapper">
    <!-- Main content -->
<section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">
          <!-- Calendar -->
          <div class="box box-solid">
            <div class="box-header">
              <i class="fa fa-wpforms"></i>

              <h4 class="box-title">View a Submission</h4>
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
			<!-- messages box-->
				<div class="" id="cto_messages">
					
				</div>
				<!-- /.messages box-->
              <!-- CTOF Content here-->
				<div id="cto_map" style="height: 500px;width: 100%;"></div>
				<div class="col-sm-6">
				<h4 style="font-size:20pt">Potentials Details</h4>
					<table id="cto_potdet" class="table table-bordered table-striped">
					
				  </table>
			  </div>
			  <div class="col-sm-1">
			  
			  </div>
			  <div class="col-sm-4">
			  <br/>
				<a href="<?php echo base_url().$cto_url; ?>" class="btn btn-info lime">Table</a>
				<?php if(isset($is_recomendation)){ ?>
					<a href="#" onclick="cto_recomend(<?php if(isset($id_subs)){echo $id_subs;}else{echo 0;} ?>);" class="btn btn-info lime">Recomend</a>
					<a href="#" onclick="cto_delete('', <?php if(isset($id_subs)){echo $id_subs;}else{echo 0;} ?>, false);" class="btn btn-danger lime">Delete Submission</a>
				<?php } ?>
				<br/>
				<br/>
				Keterangan :
				<br/>
				<table border="0">
				<tr>
					<td>
						<img src="<?php echo base_url(); ?>assets/images/homered35.png">
					</td>
					<td> : </td>
					<td>Potential House</td>
				</tr>
				<tr>
					<td>
				<img src="<?php echo base_url(); ?>assets/images/blue-circle.png">
				</td>
					<td> : </td>
				<td>Area Rekomendasi</td>
				</tr>
				</table>
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
<script type="text/javascript" language="javascript" >
var map = null;
var infowindowz = null;
//custom icons
var icons = {
	  homered: {
		icon: '<?php echo base_url(); ?>assets/images/homered35.png'
	  },
	  place: {
		icon: '<?php echo base_url(); ?>assets/images/blue-circle.png'
	  },
	};
	
	function initMap(){
		fk_submission_id = <?php if(isset($id_subs)){echo $id_subs;}else{ echo 0;}?>;
		subs = getSubs(fk_submission_id);
		
		var map = new google.maps.Map(document.getElementById('cto_map'), {zoom: <?php echo $zoom; ?>, center: subs['coordinate']});
		//draw circle
		var cityCircle = new google.maps.Circle({
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 1,
			//fillColor: '#FF0000',
			fillOpacity: 0.0,
			map: map,
			center: subs['coordinate'],
			radius: <?php echo $radius; ?> //in meters
		  });
		//draw circle end

		//var marker = new google.maps.Marker({position: subs['coordinate'],  map: map});
		
		// console.log('before');
		setSubs(fk_submission_id, map);
		setUNSC(fk_submission_id, map);
		
		//var marker = new google.maps.Marker({position: uluru, map: map});

	}
	
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
function getSubs(fk_submission_id) {
	var detail = ajaxGetDetails("<?php echo base_url(); ?>submission/cto_getDetailsData", fk_submission_id);
	latlng = detail['coordinate'];
	latlng = latlng.replace(/\s/g, '');
	
	lnglat = latlng.split(",");
	detail['coordinate'] = {lat: parseFloat(lnglat[0]), lng: parseFloat(lnglat[1])};
	
	return detail;
}

function setSubs(fk_submission_id, map) {
	var detail = ajaxGetDetails("<?php echo base_url(); ?>submission/cto_getDetailsData", fk_submission_id);
	latlng = detail['coordinate'];
	latlng = latlng.replace(/\s/g, '');
	
	lnglat = latlng.split(",");
	detail['coordinate'] = {lat: parseFloat(lnglat[0]), lng: parseFloat(lnglat[1])};
	
	var submission = new google.maps.Marker({position: detail['coordinate'], icon: icons['place'].icon, map: map});
	infowindow = new google.maps.InfoWindow({content:'<strong>Recomendation</strong>'
	   +'<br><table border="0">'
	   +'<tr><td>ID</td><td>:</td><td>'+detail['code_id']+'</td></tr>'
	   +'<tr><td>Area</td><td>:</td><td>'+detail['address']+'</td></tr>'
	   +'<tr><td>Est. HH</td><td>:</td><td>'+detail['house_hold']+'</td></tr>'
	   +'<tr><td>Potentials</td><td>:</td><td>'+detail['potentials']+'</td></tr>'
	   +'<tr><td>New ODP</td><td>:</td><td>'+detail['odp']+'</td></tr>'
	   +'<tr><td>Tikor</td><td>:</td><td>'+detail['coordinate'].lat+', '+detail['coordinate'].lng+'</td></tr>'
	   +'</table>'});
	   
		google.maps.event.addListener(submission, 'click', function(){
			infowindow.open(map, submission);
		});
}

var unsc = [];
var posunsc = [];

function setUNSC(fk_submission_id, map) {
	var detailunsc = ajaxGetDetails("<?php echo base_url(); ?>unsc/cto_getDetailsData", fk_submission_id);
	infowindowz = new google.maps.InfoWindow({
	content: "holding..."
	});
	var tbl = "<thead><tr><th>No.</th><th>Cust Name</th><th>Package</th><th>Address</th><th>Lat, Long</th></tr></thead>";
	for (i = 0; i < detailunsc.length; i++) {
		//alert(i);
		latlng = detailunsc[i]['cust_coordinate'];
		latlng = latlng.replace(/\s/g, '');
		
		lnglat = latlng.split(",");
		lat = lnglat[0];
		lng = lnglat[1];
		posunsc[i] = {lat: parseFloat(lnglat[0]), lng: parseFloat(lnglat[1])};
	
		//unsc[i] = new google.maps.Marker({position: posunsc[i], icon: icons['homered'].icon, map: map, data : detailunsc[i]});
		unsc[i] = new google.maps.Marker({position: posunsc[i], icon: icons['homered'].icon, map: map, data : detailunsc[i]});
		   
			google.maps.event.addListener(unsc[i], 'click', function(){
				contentString = '<strong>Potentials Details</strong>'
				   +'<br><table border="0">'
				   +'<tr><td>Cust Name</td><td> : </td><td>'+this.data['cust_name']+'</td></tr>'
				   +'<tr><td>Paket</td><td> : </td><td>'+this.data['package']+'</td></tr>'
				   +'<tr><td>Address</td><td> : </td><td>'+this.data['cust_address']+'</td></tr>'
				   +'<tr><td>Tikor</td><td> : </td><td>'+this.data['cust_coordinate']+'</td></tr>'
				   +'</table>';
				
				infowindowz.setContent(contentString);
				infowindowz.open(map, this);
			});
		//set UNSC table
		tbl += '<tr onclick="setInfow('+i+');"><td>'
				   +(i+1)+'</td><td>'+detailunsc[i]['cust_name']+'</td>'
				   +'<td>'+detailunsc[i]['package']+'</td>'
				   +'<td>'+detailunsc[i]['cust_address']+'</td>'
				   +'<td>'+detailunsc[i]['cust_coordinate']+'</td></tr>';
	}
	document.getElementById("cto_potdet").innerHTML = tbl;
}

function setInfow(i){
	infowindowz = new google.maps.InfoWindow({
	content: "holding..."
	});
	contentString = '<strong>Potentials Details</strong>'
				   +'<br><table border="0">'
				   +'<tr><td>Cust Name</td><td> : </td><td>'+unsc[i].data['cust_name']+'</td></tr>'
				   +'<tr><td>Paket</td><td> : </td><td>'+unsc[i].data['package']+'</td></tr>'
				   +'<tr><td>Address</td><td> : </td><td>'+unsc[i].data['cust_address']+'</td></tr>'
				   +'<tr><td>Tikor</td><td> : </td><td>'+unsc[i].data['cust_coordinate']+'</td></tr>'
				   +'</table>';
				
	infowindowz.setContent(contentString);
	infowindowz.open(map, unsc[i]);
}

<?php if(isset($is_recomendation)){ ?>
	function cto_recomend(id){
		if(confirm("Apakah anda yakin submit?")){
			cto_loading_show();
			id = "(<?php echo $id_subs;?>)";
			$.ajax({
				url:"<?php echo base_url(); ?>submission/sendRecSubmission",
				method:"POST",
				data:{fk_submission_id:id},
				success:function(data){
					data = $.parseJSON(data);
					alert(data.messages);
					window.location = "<?php echo base_url(); ?>submission/history";
				}
			});
		}
	}
<?php } ?>
				
 $(document).ready(function(){
	
  
 });
 function cto_delete(code_id, id, status){
	 var detail = ajaxGetDetails("<?php echo base_url(); ?>submission/cto_getDetailsData", id);
	 var code_id = detail['code_id'];
	 if(status){
		
	 }else{
		 if(confirm("Dengan klik tombol OK/YES, Anda menyetujui ID "+code_id+" untuk dihapus.")){
			cto_update("<?php echo base_url()."order/delete_submission"; ?>", id, status);
		}
	 }
	 
  }
  
  function cto_update(addr, id, status){
		cto_loading_show();
		cto_messages_hide();
		var data = function () {
			$.ajax({
				'async': false,
				'type': "POST",
				'global': false,
				'dataType': 'json',
				'url': addr,
				'data': { 'id':  id, 'status' : status},
				'success': function (data) {
					cto_loading_hide();
					if(data['status']){
						//alert(data['messages']);
						cto_messages_show(data);
						//window.location = "<?php echo base_url(); ?>cto_user/creat";
					}else{
						//alert(data['messages']);
						//show error for each fields
						cto_messages_show(data);
					}
				}
			});
		}();
	cto_loading_hide();
	}
</script>
<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0F2s2R9-NXMTkfs4BkgoMv5nIfvRbExk&callback=initMap">
</script>
<?php $this->load->view('layouts/footer');?>
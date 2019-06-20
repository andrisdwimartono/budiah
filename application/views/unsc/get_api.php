<!DOCTYPE html>
<html>
<head>
<script src="<?php echo base_url().'assets/'; ?>bower_components/jquery/dist/2.1.3/jquery.js"></script>
</head>
<body>
<div id="for_data">
test
</div>
<script>
go_api();
console.log("test");
	  function go_api(){
			var apidata = ajaxGetValue();
			var opt = '';
			// for (i = 0; i < apidata.length; i++) {
				// opt+='value="'+apidata[i]["ORDER_ID"]+'">'+apidata[i]["ORDER_DATE"]+'<br>';
			// }
			// document.getElementById("for_data").innerHTML = opt;
		}
		
		function ajaxGetValue(){
			var data = function () {
			var tmp = null;
			$.ajax({
				'type': "POST",
				'dataType': 'script',
				'url': 'https://starclick.telkom.co.id/qa/backend_qa/public/api/tracking',
				//'url': 'http://localhost/budiah/unsc/fetch',
				'data': { 'SearchText':  'MEDAN', 'ScNoss' : 'true', 'Field' : 'ORG', 'draw' : 2},
				'success': function (data) {
					alert(data);
					
					//tmp = data["data"];
				}
			});
			return tmp;
		}();
		return data;
		}
</script>
</body>
</html>
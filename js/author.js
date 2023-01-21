$(document).ready(function(){	

	var userRecords = $('#authorListing').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": false,		
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"author_action.php",
			type:"POST",
			data:{action:'listAuthor'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 3, 4],
				"orderable":false,
			},
		],
		"pageLength": 10
	});	
	
	$('#addAuthor').click(function(){
		$('#authorModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#authorModal").on("shown.bs.modal", function () {
			$('#authorForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Add author");					
			$('#action').val('addAuthor');
			$('#save').val('Guardar');
		});
	});		
	
	$("#authorListing").on('click', '.update', function(){
		var authorid = $(this).attr("id");
		var action = 'getAuthorDetails';
		$.ajax({
			url:'author_action.php',
			method:"POST",
			data:{authorid:authorid, action:action},
			dataType:"json",
			success:function(respData){				
				$("#authorModal").on("shown.bs.modal", function () { 
					$('#authorForm')[0].reset();
					respData.data.forEach(function(item){						
						$('#authorid').val(item['authorid']);						
						$('#name').val(item['name']);	
						$('#status').val(item['status']);						
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit author");
					$('#action').val('updateAuthor');
					$('#save').val('Guardar');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#authorModal").on('submit','#authorForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"author_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#authorForm')[0].reset();
				$('#authorModal').modal('hide');				
				$('#save').attr('disabled', false);
				userRecords.ajax.reload();
			}
		})
	});		

	$("#authorListing").on('click', '.delete', function(){
		var authorid = $(this).attr("id");		
		var action = "deleteAuthor";
		if(confirm("Deseas eliminar este registro?")) {
			$.ajax({
				url:"author_action.php",
				method:"POST",
				data:{authorid:authorid, action:action},
				success:function(data) {					
					userRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});
	
});
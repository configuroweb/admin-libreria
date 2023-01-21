$(document).ready(function(){	

	var userRecords = $('#categoryListing').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": false,		
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"category_action.php",
			type:"POST",
			data:{action:'listCategory'},
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
	
	$('#addCategory').click(function(){
		$('#categoryModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#categoryModal").on("shown.bs.modal", function () {
			$('#categoryForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Agregar Categoría");					
			$('#action').val('addCategory');
			$('#save').val('Guardar');
		});
	});		
	
	$("#categoryListing").on('click', '.update', function(){
		var categoryid = $(this).attr("id");
		var action = 'getCategoryDetails';
		$.ajax({
			url:'category_action.php',
			method:"POST",
			data:{categoryid:categoryid, action:action},
			dataType:"json",
			success:function(respData){				
				$("#categoryModal").on("shown.bs.modal", function () { 
					$('#categoryForm')[0].reset();
					respData.data.forEach(function(item){						
						$('#categoryid').val(item['categoryid']);						
						$('#name').val(item['name']);	
						$('#status').val(item['status']);						
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Editar categoría");
					$('#action').val('updateCategory');
					$('#save').val('Guardar');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#categoryModal").on('submit','#categoryForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"category_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#categoryForm')[0].reset();
				$('#categoryModal').modal('hide');				
				$('#save').attr('disabled', false);
				userRecords.ajax.reload();
			}
		})
	});		

	$("#categoryListing").on('click', '.delete', function(){
		var categoryid = $(this).attr("id");		
		var action = "deleteCategory";
		if(confirm("Deseas eliminar este registro?")) {
			$.ajax({
				url:"category_action.php",
				method:"POST",
				data:{categoryid:categoryid, action:action},
				success:function(data) {					
					userRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});
	
});
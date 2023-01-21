$(document).ready(function(){	

	var userRecords = $('#userListing').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": false,		
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"user_action.php",
			type:"POST",
			data:{action:'listUsers'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 4, 5],
				"orderable":false,
			},
		],
		"pageLength": 10
	});	
	
	$('#addUser').click(function(){
		$('#userModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#userModal").on("shown.bs.modal", function () {
			$('#userForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Agregar Usuario");					
			$('#action').val('addUser');
			$('#save').val('Guardar');
		});
	});		
	
	$("#userListing").on('click', '.update', function(){
		var id = $(this).attr("id");
		var action = 'getUserDetails';
		$.ajax({
			url:'user_action.php',
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(respData){				
				$("#userModal").on("shown.bs.modal", function () { 
					$('#userForm')[0].reset();
					respData.data.forEach(function(item){						
						$('#id').val(item['id']);						
						$('#role').val(item['role']);	
						$('#first_name').val(item['first_name']);
						$('#last_name').val(item['last_name']);	
						$('#email').val(item['email']);	
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Editar Usuario");
					$('#action').val('updateUser');
					$('#save').val('Guardar');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#userModal").on('submit','#userForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"user_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#userForm')[0].reset();
				$('#userModal').modal('hide');				
				$('#save').attr('disabled', false);
				userRecords.ajax.reload();
			}
		})
	});		

	$("#userListing").on('click', '.delete', function(){
		var id = $(this).attr("id");		
		var action = "deleteUser";
		if(confirm("Deseas eliminar este registro?")) {
			$.ajax({
				url:"user_action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data) {					
					userRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});
	
});
<?php
include_once 'config/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if (!$user->loggedIn()) {
	header("Location: index.php");
}
include('inc/header4.php');
?>
<title>ConfiguroWeb</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="css/dashboard.css" />
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" />
<script src="js/category.js"></script>
</head>

<body>

	<div class="container-fluid">
		<?php include('top_menus.php'); ?>
		<div class="row row-offcanvas row-offcanvas-left">
			<?php include('left_menus.php'); ?>
			<div class="col-md-9 col-lg-10 main">
				<h2>Categoría</h2>
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-10">
							<h3 class="panel-title"></h3>
						</div>
						<div class="col-md-2" align="right">
							<button type="button" id="addCategory" class="btn btn-info" title="Agregar Usuario"><span class="glyphicon glyphicon-plus">Agregar Categoría</span></button>
						</div>
					</div>
				</div>
				<table id="categoryListing" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Id</th>
							<th>Nombre</th>
							<th>Estado</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<div id="categoryModal" class="modal fade">
			<div class="modal-dialog">
				<form method="post" id="categoryForm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"></button>
							<h4 class="modal-title"><i class="fa fa-plus"></i> Editar Usuario</h4>
						</div>
						<div class="modal-body">

							<div class="form-group">
								<label for="Income" class="control-label">Nombre Categoría</label>
								<input type="text" name="name" id="name" autocomplete="off" class="form-control" placeholder="Nombre de la Categoría" />

							</div>

							<div class="form-group">
								<label for="country" class="control-label">Estado</label>
								<select class="form-control" id="status" name="status" />
								<option value="">Seleccionar</option>
								<option value="Enable">Enable</option>
								<option value="Disable">Disable</option>
								</select>
							</div>


						</div>
						<div class="modal-footer">
							<input type="hidden" name="categoryid" id="categoryid" />
							<input type="hidden" name="action" id="action" value="" />
							<input type="submit" name="save" id="save" class="btn btn-info" value="Guardar" />
							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php
	include("./footer.php");
	?>

</body>

</html>
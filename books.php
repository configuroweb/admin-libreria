<?php
include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/Books.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if (!$user->loggedIn()) {
	header("Location: index.php");
}
$book = new Books($db);
include('inc/header4.php');
?>
<title>ConfiguroWeb</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="css/dashboard.css" />
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" />
<script src="js/books.js"></script>
</head>

<body>

	<div class="container-fluid">
		<?php include('top_menus.php'); ?>
		<div class="row row-offcanvas row-offcanvas-left">
			<?php include('left_menus.php'); ?>
			<div class="col-md-9 col-lg-10 main">
				<h2>Gestionar Libros</h2>
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-10">
							<h3 class="panel-title"></h3>
						</div>
						<div class="col-md-2" align="right">
							<button type="button" id="addBook" class="btn btn-info" title="Add book"><span class="glyphicon glyphicon-plus">Agregar Libro</span></button>
						</div>
					</div>
				</div>
				<table id="bookListing" class="table table-striped table-bordered">
					<thead>
						<tr>
							<td></td>
							<th>Libro</th>
							<th>ISBN</th>
							<th>Autor</th>
							<th>Editor</th>
							<th>Categoría</th>
							<th>Estante</th>
							<th>No de copias</th>
							<th>Estado</th>
							<th>Actualización</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<div id="bookModal" class="modal fade">
			<div class="modal-dialog">
				<form method="post" id="bookForm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"></button>
							<h4 class="modal-title"><i class="fa fa-plus"></i> Editar libro</h4>
						</div>
						<div class="modal-body">

							<div class="form-group">
								<label for="book" class="control-label">Libro</label>
								<input type="text" name="name" id="name" autocomplete="off" class="form-control" placeholder="Nombre del Libro" />

							</div>

							<div class="form-group">
								<label for="book" class="control-label">No ISBN</label>
								<input type="text" name="isbn" id="isbn" autocomplete="off" class="form-control" placeholder="Número de ISBN" />

							</div>

							<div class="form-group">
								<label for="book" class="control-label">No de Copias</label>
								<input type="number" name="no_of_copy" id="no_of_copy" autocomplete="off" class="form-control" placeholder="No de Copias" />
							</div>

							<div class="form-group">
								<label for="author" class="control-label">Autor</label>
								<select name="author" id="author" class="form-control">
									<option value="">Seleccionar</option>
									<?php
									$authorResult = $book->getAuthorList();
									while ($author = $authorResult->fetch_assoc()) {
									?>
										<option value="<?php echo $author['authorid']; ?>"><?php echo $author['name']; ?></option>
									<?php } ?>
								</select>
							</div>


							<div class="form-group">
								<label for="publisher" class="control-label">Editor</label>
								<select name="publisher" id="publisher" class="form-control">
									<option value="">Seleccionar</option>
									<?php
									$publisherResult = $book->getPublisherList();
									while ($publisher = $publisherResult->fetch_assoc()) {
									?>
										<option value="<?php echo $publisher['publisherid']; ?>"><?php echo $publisher['name']; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-group">
								<label for="category" class="control-label">Categoría</label>
								<select name="category" id="category" class="form-control">
									<option value="">Seleccionar</option>
									<?php
									$categoryResult = $book->getCategoryList();
									while ($category = $categoryResult->fetch_assoc()) {
									?>
										<option value="<?php echo $category['categoryid']; ?>"><?php echo $category['name']; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-group">
								<label for="rack" class="control-label">Estante</label>
								<select name="rack" id="rack" class="form-control">
									<option value="">Seleccionar</option>
									<?php
									$rackResult = $book->getRackList();
									while ($rack = $rackResult->fetch_assoc()) {
									?>
										<option value="<?php echo $rack['rackid']; ?>"><?php echo $rack['name']; ?></option>
									<?php } ?>
								</select>
							</div>


							<div class="form-group">
								<label for="status" class="control-label">Estado</label>
								<select class="form-control" id="status" name="status" />
								<option value="">Seleccionar</option>
								<option value="Enable">Enable</option>
								<option value="Disable">Disable</option>
								</select>
							</div>


						</div>
						<div class="modal-footer">
							<input type="hidden" name="bookid" id="bookid" />
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
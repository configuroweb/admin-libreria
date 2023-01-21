<?php
include_once 'config/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($user->loggedIn()) {
	header("Location: dashboard.php");
}

$loginMessage = '';
if (!empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
	$user->email = $_POST["email"];
	$user->password = $_POST["password"];
	if ($user->login()) {
		header("Location: dashboard.php");
	} else {
		$loginMessage = 'Usuario o Contraseña Inválidos';
	}
} else {
}
include('inc/header4.php');
?>
<title>ConfiguroWeb</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="css/dashboard.css" />
</head>

<body>
	<?php include('top_menus.php'); ?>
	<div class="container-fluid" id="main">
		<div class="row row-offcanvas row-offcanvas-left">
			<?php include('left_menus.php'); ?>
			<div class="col-md-9 col-lg-10 main">
				<div class="row mb-3">
					<div style="padding-top:30px" class="panel-body">
						<?php if ($loginMessage != '') { ?>
							<div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $loginMessage; ?></div>
						<?php } ?>
						<form id="loginform" class="form-horizontal" role="form" method="POST" action="">
							<div style="margin-bottom: 25px" class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input type="text" class="form-control" id="email" name="email" value="<?php if (!empty($_POST["email"])) {
																											echo $_POST["email"];
																										} ?>" placeholder="email" style="background:white;" required>
							</div>
							<div style="margin-bottom: 25px" class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<input type="password" class="form-control" id="password" name="password" value="<?php if (!empty($_POST["password"])) {
																														echo $_POST["password"];
																													} ?>" placeholder="password" required>
							</div>

							<div style="margin-top:10px" class="form-group">
								<input type="submit" name="login" value="Acceder" class="btn btn-info">
							</div>

						</form>
					</div>

					<hr>
				</div>

			</div>
			<?php
			include("./footer.php");
			?>
</body>


</html>
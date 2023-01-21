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
<title>Dashboard</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="css/dashboard.css" />
</head>

<body>

  <div class="container-fluid" id="main">
    <?php include('top_menus.php'); ?>
    <div class="row row-offcanvas row-offcanvas-left">
      <?php include('left_menus.php'); ?>
      <div class="col-md-9 col-lg-10 main">
        <h2>Dashboard</h2>
        <div class="row mb-3">
          <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-success">
              <div class="card-block bg-success">
                <div class="rotate">
                  <i class="fa fa-user fa-5x"></i>
                </div>
                <h6 class="text-uppercase">Total de Libros</h6>
                <h1 class="display-1"><a href="books.php"><?php echo $book->getTotalBooks(); ?></a></h1>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-info">
              <div class="card-block bg-info">
                <div class="rotate">
                  <i class="fa fa-twitter fa-5x"></i>
                </div>
                <h6 class="text-uppercase">Libros Disponibles</h6>
                <h1 class="display-1"><a href="books.php"><?php echo ($book->getTotalBooks() - $book->getTotalIssuedBooks()); ?></a></h1>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-warning">
              <div class="card-block bg-warning">
                <div class="rotate">
                  <i class="fa fa-share fa-5x"></i>
                </div>
                <h6 class="text-uppercase">Libros Devueltos</h6>
                <h1 class="display-1"><a href="books.php"><?php echo $book->getTotalReturnedBooks(); ?></a></h1>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6">
            <div class="card card-inverse card-danger">
              <div class="card-block bg-danger">
                <div class="rotate">
                  <i class="fa fa-list fa-4x"></i>
                </div>
                <h6 class="text-uppercase">Libros Prestados</h6>
                <h1 class="display-1"><a href="issue_books.php"><?php echo $book->getTotalIssuedBooks(); ?></a></h1>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
    </div>
  </div>
  <?php
  include("./footer.php");
  ?>
</body>

</html>
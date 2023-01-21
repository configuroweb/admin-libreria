<nav class="navbar navbar-fixed-top navbar-toggleable-sm navbar-inverse bg-primary mb-3">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="navbar-collapse collapse" id="collapsingNavbar">
		<ul class="navbar-nav">
			<li class="nav-item active">
				<h2><a class="nav-link" href="dashboard.php">Gestión de Librería <span class="sr-only">CWEB</span></a></h2>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<?php if (!empty($_SESSION) && $_SESSION["userid"]) { ?>
				<li class="nav-item">
					<a class="nav-link"><?php echo ucfirst($_SESSION["name"]); ?></a>
				</li>
			<?php } else { ?>
			<?php } ?>
		</ul>
	</div>
</nav>
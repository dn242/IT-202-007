<head>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap-social/bootstrap-social.css">
  
  <link rel="stylesheet" href="./css/general.css">
</head>

<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>

<nav class="navbar navbar-dark bg-dark navbar-expand-sm fixed-top">
	<div class="container">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Navbar">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="Navbar">
			<ul class="navbar-nav mx-auto">
				<li class="nav-item"><a class="nav-link" href="home.php"><span class="fa fa-home fa-lg"></span>Home</a></li>
	<?php if (!is_logged_in()): ?>
	  <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
	  <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
	<?php endif; ?>
	<?php if (is_logged_in()): ?>
	  <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
	  <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
	<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>
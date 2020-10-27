<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<head>
  <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap-social/bootstrap-social.css">
  
  <link rel="stylesheet" href="./css/general.css">
</head>
<body>
	<div class="row" style="margin-top:100px;">
		<div class="col-4">
		</div>
		<div class="col-4 text-center">
			<?php
				//we use this to safely get the email to display
				$email = "";
				$username ="";
				if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
					$email = $_SESSION["user"]["email"];
				}
				if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
					$username = $_SESSION["user"]["username"];
				}
			?>
			<p>Welcome, <?php echo $username; ?></p>
		</div>
		<div class="col-4">
		</div>
	</div>
	<script src="bootstrap/Bootstrap4/conFusion/node_modules/jquery/dist/jquery.slim.min.js"></script>
	<script src="bootstrap/Bootstrap4/conFusion/node_modules/popper.js/dist/umd/popper.min.js"></script>
	<script src="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
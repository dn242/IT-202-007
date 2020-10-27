<?php
session_start();
// remove all session variables
session_unset();
// destroy the session
session_destroy();
?>
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
		<div class="col text-center">
			<?php
			echo "You're logged out.<br>Please visit us again soon!<br>";
			#echo "<pre>" . var_export($_SESSION, true) . "</pre>";
			?>
		</div>
	</div>
</body>
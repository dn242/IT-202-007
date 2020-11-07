<?php require_once(__DIR__ . "/partials/nav.php");?>

<head> 
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap-social/bootstrap-social.css">
  
  <link rel="stylesheet" href="./css/general.css">
</head>

<body>
	
	<div class="row" style="margin-top:100px;">
		<div class="col-lg-3">
		</div>
		<div class="col-lg-6">
			<form method="POST">
				<div class="row">
					<a class="navbar-brand mx-auto" href="home.php" style="margin:0px;"><img src="./images/yfansis.png" style="height:250px;padding-bottom:20px;"></img></a>
				</div>
				<div class="row">
					<div class="col-4 text-right">
						<label for="email"><span class="fa fa-user-circle fa-lg"></span> Email / Username:</label>
					</div>	
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" placeholder="" style="font-weight: bold;" id="email" name="email" required/>
					</div>	
					<div class="col-4">
					</div>
				</div>
				<div class="row" style="margin-top:10px;">
					<div class="col-4 text-right">
						<label for="p1"><span class="fa fa-key fa-lg"></span> Password:</label>
					</div>
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" placeholder="" style="font-weight: bold;" type="password" id="p1" name="password" required/>
					</div>
					<div class="col-4">
					</div>
				</div>
				<div class="row" style="margin-top:20px;">
					<input class="btn btn-dark mx-auto" type="submit" name="login" value="Login"/>
				</div>
			</form>
		</div>
		<div class="col-lg-3">
		</div>
	</div>
	<div class="row text-center">
		<div class="col-md-3">
		</div>
		<div class="col col-md-6">
			<?php
			if (isset($_POST["login"])) {
				$email = null;
				$password = null;
				if (isset($_POST["email"])) {
					$email = $_POST["email"];
				}
				if (isset($_POST["password"])) {
					$password = $_POST["password"];
				}
				$isValid = true;
				if (!isset($email) || !isset($password)) {
					$isValid = false;
				}
				if ($isValid) {
					$db = getDB();
					if (isset($db)) {
						$stmt = $db->prepare("(SELECT id, email, username, password from Y_Users WHERE username = :email LIMIT 1)UNION(SELECT id, email, username, password from Y_Users WHERE email = :email LIMIT 1)");
						$params = array(":email" => $email);
						$r = $stmt->execute($params);
						//echo "db returned: " . var_export($r, true);
						$e = $stmt->errorInfo();
						if ($e[0] != "00000") {
							echo "uh oh something went horribly wrong: " . var_export($e, true);
						}
						$result = $stmt->fetch(PDO::FETCH_ASSOC);
						if ($result && isset($result["password"])) {
							$password_hash_from_db = $result["password"];
							if (password_verify($password, $password_hash_from_db)) {
								unset($result["password"]);//remove password so we don't leak it beyond this page
								//let's create a session for our user based on the other data we pulled from the table
								$_SESSION["user"] = $result;//we can save the entire result array since we removed password
								//on successful login let's serve-side redirect the user to the home page.
								header("Location: home.php");
								$stmt = $db->prepare("
SELECT Y_Roles.name FROM Y_Roles JOIN Y_UserRoles on Y_Roles.id = Y_UserRoles.role_id where Y_UserRoles.user_id = :user_id and Y_Roles.is_active = 1 and Y_UserRoles.is_active = 1");
								$stmt->execute([":user_id" => $result["id"]]);
								$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
								$_SESSION["user"] = $result;//we can save the entire result array since we removed password
								if ($roles) {
									$_SESSION["user"]["roles"] = $roles;
								}
								else {
									$_SESSION["user"]["roles"] = [];
								}
							}
							else {
								echo "<br>Wrong password.<br>Please try again.<br>";
							}
						}
						else {
							echo "<br>Invalid user.<br>The user is not registered.<br>Please use the register tab to get yourself registered.<br>";
						}
					}
				}
				else {
					echo "There was a validation issue";
				}
			}
			?>
			<?php echo var_export($_SESSION, true);?>
		</div>
		<div class="col-md-3">
		</div>
	</div>
	
	<script src="bootstrap/Bootstrap4/conFusion/node_modules/jquery/dist/jquery.slim.min.js"></script>
	<script src="bootstrap/Bootstrap4/conFusion/node_modules/popper.js/dist/umd/popper.min.js"></script>
	<script src="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<head>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap-social/bootstrap-social.css">
  
  <link rel="stylesheet" href="./css/general.css">
</head>

<body>
	<?php
		$email = null;
		$password = null;
		$confirm = null;
		$username = null;
	?>
	<div class="row" style="margin-top:100px;">
		<div class="col-lg-3">
		</div>
		<div class="col-lg-6">
		
			<form method="POST">
				<div class="row">
					<div class="col-4 text-right">
						<label for="email">Email:</label>
					</div>
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;" type="email" id="email" name="email" required value="<?php if(isset($_POST["email"])){safer_echo($_POST["email"]);}?>" required>
					</div>
					<div class="col-4">
					</div>
				</div>
				<div class="row" style="margin-top:10px;">
					<div class="col-4 text-right">
						<label for="user">Username:</label>
					</div>
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;" type="text" id="user" name="username" required maxlength="60" value="<?php if(isset($_POST["username"])){safer_echo($_POST["username"]);}?>" required>
					</div>
					<div class="col-4">
					</div>
				</div>
				<div class="row" style="margin-top:10px;">
					<div class="col-4 text-right">
						<label for="p1">Password:</label>
					</div>
					<div class="col-4 ">
						<input class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;" type="password" id="p1" name="password" required/>
					</div>
					<div class="col-4">
					</div>
				</div>
				<div class="row" style="margin-top:10px;">
					<div class="col-4 text-right">
						<label for="p2">Confirm Password:</label>
					</div>
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;" type="password" id="p2" name="confirm" required/>
					</div>
					<div class="col-4">
					</div>
				</div>
				<div class="row" style="margin-top:10px;">
					<div class="col-4 text-right">
						<label for="p2">Profile Status:</label>
					</div>
					<div class="col-4">
						<select required name="visibility" class="form-control text-center bg-dark text-warning">
							<option value=1>Public</option>
							<option value=0>Private</option>
						</select>
					</div>
					<div class="col-4">
					</div>
				</div>
				<div class="row" style="margin-top:20px;">
					<input class="btn btn-dark mx-auto" type="submit" name="register" value="Register"/>
				</div>
			</form>
		</div>
	</div>
	<div class="row text-center">
		<div class="col-md-3">
		</div>
		<div class="col col-md-6">
			<?php
				if (isset($_POST["register"])) {
					
					if (isset($_POST["email"])) {
						$email = $_POST["email"];
					}
					if (isset($_POST["password"])) {
						$password = $_POST["password"];
					}
					if (isset($_POST["confirm"])) {
						$confirm = $_POST["confirm"];
					}
					if (isset($_POST["username"])) {
						$username = $_POST["username"];
					}
					if (isset($_POST["visibility"])) {
						$visibility = $_POST["visibility"];
					}
					$isValid = true;
					//check if passwords match on the server side
					if ($password == $confirm) {
						//echo "Passwords match <br>";
					}
					else {
						echo "Passwords don't match<br>";
						$isValid = false;
					}
					if (!isset($email) || !isset($password) || !isset($confirm)) {
						$isValid = false;
					}
					//TODO other validation as desired, remember this is the last line of defense
					if ($isValid) {
						$hash = password_hash($password, PASSWORD_BCRYPT);

						$db = getDB();
						if (isset($db)) {
							//here we'll use placeholders to let PDO map and sanitize our data
							$stmt = $db->prepare("INSERT INTO Y_Users(email, username, visibility, password) VALUES(:email,:username, :visibility, :password)");
							//here's the data map for the parameter to data
							$params = array(":email" => $email, ":username" => $username, ":visibility" => $visibility, ":password" => $hash);
							$r = $stmt->execute($params);
							//let's just see what's returned
							//echo "db returned: " . var_export($r, true);
							$e = $stmt->errorInfo();
							if ($e[0] == "00000") {
								echo "<br>Welcome! You successfully registered, please login.";
							}
							else {
								if ($e[0] == "23000") {//code for duplicate entry
									echo "<br>Either username or email is already registered, please try again";
								}
								else {
									echo "uh oh something went wrong: " . var_export($e, true);
								}
							}
						}
					}
					else {
						echo "There was a validation issue";
					}
				}
				//safety measure to prevent php warnings
				if (!isset($email)) {
					$email = "";
				}
				if (!isset($username)) {
					$username = "";
				}
			?>
		</div>
		<div class="col-md-3">
		</div>
	</div>
	<div class="col-lg-3">
	</div>
</body>
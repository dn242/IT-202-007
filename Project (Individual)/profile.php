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
		$db = getDB();
		if(isset($_GET["prof_id"])){$query_id=$_GET["prof_id"];}
		else{$query_id=get_user_id();}
		$stmt = $db->prepare("SELECT email, username, visibility from Y_Users where id = :id");
		$stmt->execute([":id" => $query_id]);
		$prof_status = $stmt->fetch(PDO::FETCH_ASSOC);
	
		$responseForUser = "";
		//Note: we have this up here, so our update happens before our get/fetch
		//that way we'll fetch the updated data and have it correctly reflect on the form below
		//As an exercise swap these two and see how things change
		if (!is_logged_in()) {
			//this will redirect to login and kill the rest of this script (prevent it from executing)
			die(header("Location: login.php"));
		}

		
		//save data if we submitted the form
		if (isset($_POST["saved"])) {
			$isValid = true;
			//check if our email changed
			$newEmail = get_email();
			if (get_email() != $_POST["email"]) {
				//TODO we'll need to check if the email is available
				$email = $_POST["email"];
				$stmt = $db->prepare("SELECT COUNT(1) as InUse from Y_Users where email = :email");
				$stmt->execute([":email" => $email]);
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$inUse = 1;//default it to a failure scenario
				if ($result && isset($result["InUse"])) {
					try {
						$inUse = intval($result["InUse"]);
					}
					catch (Exception $e) {

					}
				}
				if ($inUse > 0) {
					$responseForUser = $responseForUser."Email is already in use";
					//for now we can just stop the rest of the update
					$isValid = false;
				}
				else {
					$newEmail = $email;
				}
			}
			$newUsername = get_username();
			if (get_username() != $_POST["username"]) {
				$username = $_POST["username"];
				$stmt = $db->prepare("SELECT COUNT(1) as InUse from Y_Users where username = :username");
				$stmt->execute([":username" => $username]);
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$inUse = 1;//default it to a failure scenario
				if ($result && isset($result["InUse"])) {
					try {
						$inUse = intval($result["InUse"]);
					}
					catch (Exception $e) {

					}
				}
				if ($inUse > 0) {
					$responseForUser = $responseForUser."Username is already in use.<br>";
					//for now we can just stop the rest of the update
					$isValid = false;
				}
				else {
					$newUsername = $username;
				}
			}
			if ($isValid) {
				$stmt = $db->prepare("UPDATE Y_Users set email = :email, username= :username where id = :id");
				$r = $stmt->execute([":email" => $newEmail, ":username" => $newUsername, ":id" => get_user_id()]);
				if ($r) {
					$responseForUser = $responseForUser."Email & Username updated.<br>";
				}
				else {
					$responseForUser = $responseForUser."Error updating profile.<br>";
				}
				//password is optional, so check if it's even set
				//if so, then check if it's a valid reset request
				if (!empty($_POST["password"]) && !empty($_POST["confirm"]) && !empty($_POST["old_password"])) {
					$stmt = $db->prepare("SELECT email, username, password, visibility from Y_Users WHERE id = :id LIMIT 1");
					$stmt->execute([":id" => get_user_id()]);
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					if(password_verify($_POST['old_password'],$result['password'])){
						if ($_POST["password"] == $_POST["confirm"]) {
							$password = $_POST["password"];
							$hash = password_hash($password, PASSWORD_BCRYPT);
							//this one we'll do separate
							$stmt = $db->prepare("UPDATE Y_Users set password = :password where id = :id");
							$r = $stmt->execute([":id" => get_user_id(), ":password" => $hash]);
							if ($r) {
								$responseForUser = $responseForUser."Reset password.<br>";
							}
							else {
								$responseForUser = $responseForUser."Error resetting password.<br>";
							}
						}
						else {
							$responseForUser = $responseForUser.'<span style="color:red;">Passwords did not match.<br>Password resseting was unsuccessful.<br></span>';
						}
					}
					else{
						$responseForUser = $responseForUser.'<span style="color:red;">Old password was wrong!<br>Please try again.</span>';
					}
				}
				//fetch/select fresh data in case anything changed
				$stmt = $db->prepare("SELECT email, username, password, visibility from Y_Users WHERE id = :id LIMIT 1");
				$stmt->execute([":id" => get_user_id()]);
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($result) {
					$email = $result["email"];
					$username = $result["username"];
					//let's update our session too
					$_SESSION["user"]["email"] = $email;
					$_SESSION["user"]["username"] = $username;
				}
			}
			else {
				//else for $isValid, though don't need to put anything here since the specific failure will output the message
			}
		}
	?>
	
	<div class="row" style="margin-top:100px;">
		<div class="col-lg-3">
		</div>
		<div class="col-lg-6">
			<?php if(isset($_GET["prof_id"]) && $_GET["prof_id"]!=get_user_id()): ?>
			<?php if($prof_status["visibility"]==1):?>
			<div class="row">
				<div class="col-2 text-right">
					<label>Email:</label>
				</div>
				<div class="col-8">
					<p class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;"><?php safer_echo($prof_status["email"]); ?></p>
				</div>
				<div class="col-2 text-right"></div>
			</div>
			<?php endif; ?>
			<div class="row">
				<div class="col-2 text-right">
					<label>Username:</label>
				</div>
				<div class="col-8">
					<p class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;"><?php safer_echo($prof_status["username"]); ?></p>
				</div>
				<div class="col-2 text-right"></div>
			</div>
			<?php endif ; ?>
			<?php if(!isset($_GET["prof_id"]) || $_GET["prof_id"]==get_user_id()): ?>
			<form method="POST">
				<div class="row">
					<div class="col-4 text-right">
						<label for="email">Email:</label>
					</div>
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;" type="email" name="email" value="<?php safer_echo(get_email()); ?>" required>
					</div>
				</div>
				<div class="row" style="margin-top:10px;">
					<div class="col-4 text-right">
						<label for="username">Username:</label>
					</div>
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;" type="text" maxlength="60" name="username" value="<?php safer_echo(get_username()); ?>" required>
					</div>
				</div>
				<div class="row" style="margin-top:10px;">
					<div class="col-4 text-right">
						<label for="pw">Old Password:</label>
					</div>
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;" type="password" name="old_password"/>
					</div>
				</div>
				<div class="row" style="margin-top:10px;">
					<div class="col-4 text-right">
						<label for="pw">Password:</label>
					</div>
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;" type="password" name="password"/>
					</div>
				</div>
				<div class="row" style="margin-top:10px;">
					<div class="col-4 text-right">
						<label for="cpw">Confirm Password:</label>
					</div>
					<div class="col-4">
						<input class="form-control bg-dark text-warning text-center mx-auto" style="font-weight: bold;" type="password" name="confirm"/>
					</div>
				</div>
				<div class="row" style="margin-top:20px;">
					<input class="btn btn-dark mx-auto" type="submit" name="saved" value="Save Profile"/>
				</div>
			</form>
			<?php endif ; ?>
		</div>
		<div class="col-lg-3">
		</div>
	</div>
	<div class="row text-center">
		<div class="col-md-3">
		</div>
		<div class="col col-md-6">
			<?php echo $responseForUser; ?>
		</div>
		<div class="col-md-3">
		</div>
	</div>
	<div class="col-lg-3">
	</div>
</body>
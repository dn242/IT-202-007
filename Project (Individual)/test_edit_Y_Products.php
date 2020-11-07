<head>
  <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap-social/bootstrap-social.css">
  
  <link rel="stylesheet" href="./css/general.css">
</head>


<body>
	<?php require_once(__DIR__ . "/partials/nav.php"); ?>
	<?php
	if (!has_role("Admin")) {
		//this will redirect to login and kill the rest of this script (prevent it from executing)
		flash("You don't have permission to access this page");
		die(header("Location: login.php"));
	}
	?>
	<div class="row" style="margin-top:100px;">
		<div class="col-12 text-center">
			<?php
			if(isset($_POST["load"])){
				$id = $_POST["id"];
				$db = getDB();
				$name = "";
				$quantity = 0;
				$price = 0.0;
				$description = "";
				$stmt = $db->prepare("SELECT * from Y_Products where id = :id");
				$stmt->execute([
					":id" => 	$id
				]);
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				//echo $result["name"];
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-2"></div>
		<div class="col-4">
			<form method="POST" style="margin-top:100px;">
				<div class="text-center" style="padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
					<label>ID</label>
					<input class="form-control bg-dark text-warning text-center mx-auto" name="id" placeholder="ID"/><br>
					<input class="btn btn-dark mx-auto" type="submit" name="load" value="Submit ID"/>
				</div>
			</form>
		</div>
		<div class="col-4" >
			<form method="POST" style="margin-top:100px;">
				<div class="text-center" style="padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
					<label>Name</label>
					<input class="form-control bg-dark text-warning text-center mx-auto" name="name" value="<?php echo $result["name"];?>" placeholder="Name"/><br>
					<label>Quantity</label>
					<input class="form-control bg-dark text-warning text-center" type="number" min="1" value="<?php echo $result["quantity"];?>" name="quantity"/><br>
					<label>Price</label>
					<input step=".01" class="form-control bg-dark text-warning text-center" type="number" min="0.01" value="<?php echo $result["price"];?>" name="price"/><br>
					<label>Description</label>
					<input class="form-control bg-dark text-warning text-center mx-auto" name="description" placeholder="Text here..." value="<?php echo $result["description"];?>"/><br>
					<input class="btn btn-dark mx-auto" type="submit" name="save" value="Create"/><br>
					<input style="visibility: hidden;height:0;" name="idRight" readonly value="<?php echo $result["id"];?>" placeholder="ID"/><br>
				</div>
			</form>
		</div>
		<div class="col-2"></div>
	</div>
	<div class="row" style="margin-top:20px;margin-bottom:30px;">
		<div class="col-12 text-center">
			<?php
			if(isset($_POST["save"])){
				//TODO add proper validation/checks
				$id = $_POST["idRight"];
				$name = $_POST["name"];
				$quantity = $_POST["quantity"];
				$price = $_POST["price"];
				$description = $_POST["description"];
				$modified = date('Y-m-d H:i:s');//calc
				$user = get_user_id();
				$db = getDB();
				$stmt = $db->prepare("UPDATE Y_Products set name=:name, quantity=:quantity, price=:price, description=:description where id=:id");
				$r = $stmt->execute([
					":name"=>$name,
					":quantity"=>$quantity,
					":price"=>$price,
					":description"=>$description,
					":id"=>$id
				]);
				if($r){
					echo "Updated successfully.";
				}
				else{
					$e = $stmt->errorInfo();
					echo "Error while updating.";
				}
			}
			?>
		</div>
	</div>
	<?php require(__DIR__ . "/partials/flash.php");?>
</body>


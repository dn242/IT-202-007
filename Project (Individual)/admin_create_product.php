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
	<div class="row" style="margin-top:30px;">
		<div class="col-4"></div>
		<div class="col-4">
			<form method="POST" style="margin-top:40px;">
				<div class="text-center" style="padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
					<label>Name</label>
					<input required class="form-control bg-dark text-warning text-center mx-auto" name="name" placeholder="Name"/><br>
					<label>Quantity</label>
					<input value="1" class="form-control bg-dark text-warning text-center" type="number" min="1" name="quantity"/><br>
					<label>Price</label>
					<input value="0.1" step=".01" class="form-control bg-dark text-warning text-center" type="number" min="0.01" name="price"/><br>
					<label>Category</label>
					<input required class="form-control bg-dark text-warning text-center mx-auto" name="category"/ placeholder="Category here..."><br>
					<label>Description</label>
					<input class="form-control bg-dark text-warning text-center mx-auto" name="description"/ placeholder="Text here..."><br>
					<label>Visible to non-admins</label>
					<select required name="visibility" class="form-control text-center bg-dark text-warning">
						<option value=0>No</option>
						<option value=1>Yes</option>
					</select>
					<input class="btn btn-dark m-auto" type="submit" name="save" value="Create" style="margin-top:50px;"/>
				</div>
			</form>
		</div>
		<div class="col-4"></div>
	</div>
	<div class="row" style="margin-top:50px;">
		<div class="col-12 text-center">
			<?php
			if(isset($_POST["save"])){
				//TODO add proper validation/checks
				$name = $_POST["name"];
				$quantity = $_POST["quantity"];
				$price = $_POST["price"];
				$description = $_POST["description"];
				$category = $_POST["category"];
				$visibility = $_POST["visibility"];
				$modified = date('Y-m-d H:i:s');//calc
				$created = date('Y-m-d H:i:s');//calc
				$user = get_user_id();
				$db = getDB();
				$stmt = $db->prepare("INSERT INTO Y_Products (name, quantity, price, description, category, visibility, modified, created, user_id) VALUES(:name, :quantity, :price, :description, :category, :visibility, :modified, :created, :user)");
				$r = $stmt->execute([
					":name"=>$name,
					":quantity"=>$quantity,
					":price"=>$price,
					":description"=>$description,
					":category"=>$category,
					":visibility"=>$visibility,
					":modified"=>$modified,
					":created"=>$created,
					":user"=>$user
				]);
				if($r){
					echo "Created successfully.";
				}
				else{
					$e = $stmt->errorInfo();
					echo "Error while creating.";
				}
				echo $category." - ".$visibility;
			}
			?>
		</div>
	</div>
	<?php require(__DIR__ . "/partials/flash.php");?>
</body>
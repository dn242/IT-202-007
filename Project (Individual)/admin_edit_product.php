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
			if(true){
				$db = getDB();
				$stmt = $db->prepare("SELECT * from Y_Products");
				$stmt->execute([
				]);
				$result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
				//echo $result["name"];
			}
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
			
			if(isset($_POST["loadByName"])){
				$id = $_POST["productName"];
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
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-2"></div>
		<div class="col-4">
			<form method="POST" style="margin-top:10px;">
				<div class="text-center" style="height:650px;padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
					<div class="row" style="margin-bottom:20px;padding-bottom:10px; border-bottom: 1px solid #524f2a;">
						<div class="col-8">
							<input class="form-control bg-dark text-warning text-center" name="id" placeholder="ID"/>
						</div>
						<div class="col-4">
							<input class="btn btn-dark mx-auto" type="submit" name="load" value="Submit ID"/>
						</div>
					</div>
					<p class="bg-warning text-dark" style="border-radius: 10px;">Product List</p>
					<div style="height:300px;overflow:auto;border-radius: 10px;border: 2px solid yellow" class="bg-dark">
						<table class="table table-dark table-striped text-center text-warning" style="">
							<tbody>
									<?php
									$counter=0;
									foreach($result2 as $value){
										if($counter>0){
											echo "<tr><td>".$value["name"]."</td><td><input type=\"radio\" name=\"productName\" class=\"btn btn-dark text-warning\" value=\"".$value["id"]."\"/></td></tr>";
											//echo "<tr><td style=\"font-size:x-small;color:yellow;\">\"".$value["description"]."\"</td></tr>";
										}
										else{
											echo "<tr><td>".$value["name"]."</td><td><input checked type=\"radio\" name=\"productName\" class=\"btn btn-dark text-warning\" value=\"".$value["id"]."\"/></td></tr>";
											//echo "<tr><td style=\"font-size:x-small;color:yellow;\">\"".$value["description"]."\"</td></tr>";
										}
										$counter++;
									}
									?>
							</tbody>
						</table>
					</div>
					<input class="btn btn-dark mx-auto" type="submit" name="loadByName" value="Submit Name" style="margin-top:20px;"/>
				</div>
			</form>
		</div>
		<div class="col-4" >
			<form method="POST" style="margin-top:10px;">
				<div class="text-center" style="height:650px;padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
					<label>Name</label>
					<input class="form-control bg-dark text-warning text-center mx-auto" name="name" value="<?php if(isset($result["name"])){ echo $result["name"];}?>" placeholder="Name"/><br>
					<label>category</label>
					<input class="form-control bg-dark text-warning text-center mx-auto" name="category" value="<?php if(isset($result["category"])){ echo $result["category"];}?>" placeholder="Category"/><br>
					<label>Quantity</label>
					<input class="form-control bg-dark text-warning text-center" type="number" min="1" value="<?php if(isset($result["quantity"])){ echo $result["quantity"];}?>" name="quantity"/><br>
					<label>Price</label>
					<input step=".01" class="form-control bg-dark text-warning text-center" type="number" min="0.01" value="<?php if(isset($result["price"])){ echo $result["price"];}?>" name="price"/><br>
					<label>Description</label>
					<input class="form-control bg-dark text-warning text-center mx-auto" name="description" placeholder="Text here..." value="<?php if(isset($result["description"])){ echo $result["description"];}?>"/><br>
					<label>Visible to non-admins</label>
					<select required name="visibility" class="form-control text-center bg-dark text-warning">
						<option value=0>No</option>
						<option value=1>Yes</option>
					</select>
					
					<input class="btn btn-dark mx-auto" type="submit" name="save" value="save" style="margin-top:20px;"/><br>
					
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
				$category = $_POST["category"];
				$quantity = $_POST["quantity"];
				$price = $_POST["price"];
				$description = $_POST["description"];
				$visibility = $_POST["visibility"];
				$modified = date('Y-m-d H:i:s');//calc
				$user = get_user_id();
				$db = getDB();
				$stmt = $db->prepare("UPDATE Y_Products set name=:name, category=:category, quantity=:quantity, price=:price, description=:description, visibility=:visibility where id=:id");
				$r = $stmt->execute([
					":name"=>$name,
					":category"=>$category,
					":quantity"=>$quantity,
					":price"=>$price,
					":description"=>$description,
					":visibility"=>$visibility,
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


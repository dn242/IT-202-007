<head>
  <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap-social/bootstrap-social.css">
  
  <link rel="stylesheet" href="./css/general.css">
</head>


<body>
	<?php 
	require_once(__DIR__ . "/partials/nav.php"); 
	if (!is_logged_in()) {
		//this will redirect to login and kill the rest of this script (prevent it from executing)
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
			$isClicked="hidden";
			if(isset($_POST["loadByName"])){
				$isClicked="visible";
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
			if(true){
				$current_user_id = get_user_id();
				$db = getDB();
				$stmt = $db->prepare("SELECT Y_Products.id as productID, Y_Products.name as productName, Y_Products.quantity as productQuantity, Y_Products.price as productPrice, Y_Cart.id as cartID, Y_Cart.user_id as cartUser_id, Y_Cart.product_id as cartProduct_ID, Y_Cart.quantity as cartQuantity from Y_Products JOIN Y_Cart on Y_Products.id = Y_Cart.product_id where Y_Cart.user_id = :current_user_id");
				$r = $stmt->execute([
					":current_user_id" => 	$current_user_id
				]);
				$result3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if($r){
					//echo "Success! HERE";
				}
				else{
					$e = $stmt->errorInfo();
					//echo "Error... HERE<br>";
				}
				//echo var_export($result3,true);
			}
			if(isset($_POST["loadName"])){
				$current_user_id = get_user_id();
				$lookup_name = $_POST["detail_view_name"];
				$db = getDB();
				$stmt = $db->prepare("SELECT cartPrice, Y_Products.id as productID, Y_Products.name as productName, Y_Products.quantity as productQuantity, Y_Products.price as productPrice, Y_Products.description as productDescription, Y_Cart.id as cartID, Y_Cart.user_id as cartUser_id, Y_Cart.product_id as cartProduct_ID, Y_Cart.quantity as cartQuantity from Y_Products JOIN Y_Cart on Y_Products.id = Y_Cart.product_id where Y_Cart.user_id = :current_user_id AND Y_Products.name = :lookup_name");
				$r = $stmt->execute([
					":current_user_id" => 	$current_user_id,
					":lookup_name" => 	$lookup_name
				]);
				$result4 = $stmt->fetch(PDO::FETCH_ASSOC);
				if($r){
					//echo "Success! HERE";
				}
				else{
					$e = $stmt->errorInfo();
					//echo "Error... HERE<br>";
				}
				//echo var_export($result4,true);
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-2"></div>
		<div class="col-4">
			<form method="POST" style="margin-top:10px;">
				<div class="text-center" style="height:500px;padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
					<p class="bg-warning text-dark" style="border-radius: 10px;">Product List</p>
					<div style="height:300px;overflow:auto;border-radius: 10px;border: 2px solid yellow" class="bg-dark">
						<table class="table table-dark table-striped text-center text-warning" style="">
							<tbody>
									<?php
									$counter=0;
									foreach($result2 as $value){
										if($value["visibility"]){
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
				<div class="text-center" style="height:500px;padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
					<?php if($isClicked!="hidden"): ?>
					<label>Name</label>
					<p class="form-control bg-dark text-warning text-center mx-auto" name="name"><?php if(isset($result["name"])){ echo $result["name"];}?></p><br>
					<label>Quantity</label>
					<input class="form-control bg-dark text-warning text-center" type="number" min="1" value="1" name="quantity"/><br>
					<label>Price</label>
					<input readonly class="form-control bg-dark text-warning text-center"  name="price" value="<?php if(isset($result["price"])){ echo $result["price"];}?>"><br>
					<label>Description</label>
					<p class="form-control bg-dark text-warning text-center mx-auto" style="height:60px;overflow:auto;" name="description"><?php if(isset($result["description"])){ echo $result["description"];}?></p><br>
					<input class="btn btn-danger mx-auto" type="submit" name="toCart" value="Put into the cart!"/><br>
					<input style="visibility: hidden;height:0;" name="idRight" readonly value="<?php echo $result["id"];?>" placeholder="ID"/><br>
					<?php endif ; ?>
				</div>
			</form>
		</div>
		<div class="col-2"></div>
	</div>
	<div class="row">
		<div class="col-2 text-center"></div>
		<div class="col-8 ">
			<div class="text-right" style="margin-top:30px;padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
				<p class="bg-warning text-dark text-center" style="border-radius: 10px;">Your Cart</p>
				<form method="POST">
					<div style="height:300px;overflow:auto;border-radius: 10px;border: 2px solid yellow" class="bg-dark text-center">
						<table class="table table-dark table-striped text-center text-warning" style="">
							<thead>
								<tr class="bg-warning text-dark">
									<th scope="col">Product Name</th>
									<th scope="col">Price</th>
									<th scope="col">Quantity</th>
									<th scope="col">Total Price</th>
									<th scope="col"></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$index=0;
								$sumNum=0;
								$sumQuan=0;
								if(isset($result3)){
									if($result3!=false){
										foreach($result3 as $value){
											echo "<tr><td>".$value["productName"]."</td><td>".$value["productPrice"]."$</td><td><input style=\"width:50px;border-color: yellow;\" class=\"btn btn-dark text-yellow mx-auto\" type=\"text\" name=\"quantity".$index."\" value=\"".$value["cartQuantity"]."\"/></td><td>".($value["productPrice"]*$value["cartQuantity"])."$</td><td> <button type=\"button\" onclick=\"deleteItem('".$value["productID"]."','".get_user_id()."')\" class=\"btn btn-dark text-warning\" aria-label=\"Close\" name=\"\"><span aria-hidden=\"true\">&times;</span></button> </td></tr>";
											$index++;
											$sumNum+=$value["productPrice"]*$value["cartQuantity"];
											$sumQuan+=$value["cartQuantity"];
										}
									}
									echo "<tr class=\"bg-dark\" style=\"color:yellow;font-weight:bold;\"><td><td></td></td><td>Summary:</td><td>".$sumQuan."</td><td>".$sumNum."$</td></tr>";
								}
								?>
							</tbody>
						</table>
					</div>
					<input class="btn btn-dark" type="submit" name="updateCart" value="Update Cart" style="margin-top:20px;"/>
				</form>
			</div>
		</div>
		<div class="col-2 text-center"></div>
	</div>
	<div class="row">
		<div class="col-2 text-center"></div>
		<div class="col-8 ">
			<form method="POST" style="margin-top:10px;">
				<div class="text-center" style="padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
					<div class="row" style="margin-bottom:20px;padding-bottom:10px; border-bottom: 1px solid #524f2a;">
						<div class="col-6">
							<h4 class="bg-warning text-dark text-center" style="height:100%;border-radius: 10px;">Single Object Lookup (by name)</h4>
						</div>
						<div class="col-4">
							<select class="form-control bg-dark text-warning text-center" name="detail_view_name">
								<?php foreach ($result3 as $value): ?>
								<option value="<?php echo $value["productName"]; ?>"><?php echo $value["productName"]; ?></option>
								<?php endforeach ; ?>
							</select>
						</div>
						<div class="col-2">	
							<input class="btn btn-dark mx-auto" type="submit" name="loadName" value="Submit Name"/>
						</div>
					</div>
					<div class="row">
						<div class="col-3">
							<p class="text-warning text-center mx-auto">Name:</p>
						</div>
						<div class="col-3">
							<p class="text-warning text-center mx-auto">Quantity:</p>
						</div>
						<div class="col-3">
							<p class="text-warning text-center mx-auto" >Current Price:</p>
						</div>
						<div class="col-3">
							<p class="text-warning text-center mx-auto" >Price When Added:</p>
						</div>
					</div>
					<div class="row">
						<?php if(isset($result4)): ?>
						<div class="col-3">
							<p class="form-control bg-dark text-warning text-center mx-auto"><?php echo $result4["productName"]; ?></p>
						</div>
						<div class="col-3">
							<p class="form-control bg-dark text-warning text-center mx-auto"><?php echo $result4["cartQuantity"]; ?></p>
						</div>
						<div class="col-3">
							<p class="form-control bg-dark text-warning text-center mx-auto"><?php echo $result4["productPrice"]; ?>$</p>
						</div>
						<div class="col-3">
							<p class="form-control bg-dark text-warning text-center mx-auto"><?php echo $result4["cartPrice"]; ?>$</p>
						</div>
						<?php endif ; ?>
					</div>
					<div class="row">
						<?php if(isset($result4)): ?><div class="col-12"><p class="form-control bg-dark text-warning text-center mx-auto" name="name"><?php  echo $result4["productDescription"]; ?></p></div><?php endif ; ?>
					</div>
				</div>
			</form>
		</div>
		<div class="col-2 text-center"></div>
	</div>
	<div class="row" style="margin-top:20px;margin-bottom:30px;">
		<div class="col-12 text-center">
			<?php
			if(isset($_POST["toCart"])){
				//TODO add proper validation/checks
				$product_id = $_POST["idRight"];
				$quantity = $_POST["quantity"];
				$cartPrice = $_POST["price"];
				$user_id = get_user_id();
				$db = getDB();
				$stmt = $db->prepare("INSERT INTO Y_Cart (product_id, quantity, cartPrice, user_id) VALUES (:product_id, :quantity, :cartPrice, :user_id) ");
				$r = $stmt->execute([
					":product_id"=>$product_id,
					":quantity"=>$quantity,
					":cartPrice"=>$cartPrice,
					":user_id"=>$user_id
				]);
				if($r){
					//echo "Success!";
					echo "<script>(function(){location.reload();})();</script>";
				}
				else{
					$e = $stmt->errorInfo();
					//echo "Error...";
				}
			}
			
			if(isset($_POST["updateCart"])){
				for($i = 0 ; $i < $index ; $i++){
					if($result3[$i]["cartQuantity"]!=$_POST["quantity".$i]){
						$stmt = $db->prepare("UPDATE Y_Cart set quantity=:quantity where product_id=:product_id");
						$r = $stmt->execute([
							":quantity"=>$_POST["quantity".$i],
							":product_id"=>$result3[$i]["productID"]
						]);
						if($r){
							//echo "Updated successfully.";
							echo "<script>(function(){location.reload();})();</script>";
						}
						else{
							$e = $stmt->errorInfo();
							//echo "Error while updating.";
						}
					}
				}
			}
			?>
		</div>
	</div>
	<?php require(__DIR__ . "/partials/flash.php");?>

	<script>
		function deleteItem(item,item2){
			console.log(item);
			var msg = {
				toDeleteID: item,
				user_ID: item2,
			};
			console.log(JSON.stringify(msg));
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					console.log("It was done.");
					console.log(this);
					location.replace(location.href);
				}
			};
			xhttp.open("POST", "deleter.php", true);
			xhttp.send(JSON.stringify(msg));
		}
	</script>
</body>



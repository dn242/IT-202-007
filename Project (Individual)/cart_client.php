<head>
  <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap-social/bootstrap-social.css">
	  
	<link rel="stylesheet" href="./css/general.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
</head>


<body>
	<?php 
	require_once(__DIR__ . "/partials/nav.php"); 
	if (!is_logged_in()) {
		//this will redirect to login and kill the rest of this script (prevent it from executing)
		die(header("Location: login.php"));
	}
	$sumNum=0;
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
									echo "<tr class=\"bg-dark\" style=\"color:yellow;font-weight:bold;\"><td>Summary:</td><td></td><td>".$sumQuan."</td><td>".$sumNum."$</td><td></td></tr>";
								}
								?>
							</tbody>
						</table>
					</div>
					<input class="btn btn-dark" type="submit" name="emptyCart" value="Empty Cart" style="margin-top:20px;"/>
					<input class="btn btn-dark" type="submit" name="updateCart" value="Update Cart" style="margin-top:20px;margin-left:5px;"/>
					<?php if($sumNum>0): ?><button type="button" class="btn btn-danger" id="purchaseModalToggler" name="purchaseModalToggler" style="margin-top:20px;margin-left:5px;" data-toggle="modal" data-target="#purchaseModal">Checkout</button> <?php endif ;?>
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
			
			if(isset($_POST["emptyCart"])){
				$stmt = $db->prepare("DELETE FROM Y_Cart WHERE user_id=:user_id");
				$r = $stmt->execute([
					":user_id"=>get_user_id()
				]);
				if($r){
					//echo "Updated successfully.";
					echo "<script>location.replace(location.href);</script>";
				}
				else{
					$e = $stmt->errorInfo();
					//echo "Error while updating.";
				}
			}
			
			
			if(isset($_POST["purchaseButton"])){
				$wrongdoings="";
				$wrongdoingsCount=0;
				for($i = 0 ; $i < $index ; $i++){
					if($result3[$i]["cartQuantity"]>$result3[$i]["productQuantity"]){
						$wrongdoings = $wrongdoings.$result3[$i]["productName"]." has only ".$result3[$i]["productQuantity"]." pieces remaining.<br>";
						$wrongdoingsCount+=1;
					}
				}
				if(!isset($wrongdoings) || $wrongdoings==""){
					//echo "all good!";
					$stmt = $db->prepare("INSERT INTO Y_Orders (user_id, total_price, address, payment_method) VALUES (:user_id, :total_price, :address, :payment_method)");
					$r = $stmt->execute([
						":user_id"=>get_user_id(),
						":total_price"=>$sumNum,
						":payment_method"=>$_POST["paymentMethod"],
						":address"=>$_POST["purchaseAddressStreet"]." ".$_POST["purchaseAddressCity"]." ".$_POST["purchaseAddressState"]." ".$_POST["purchaseAddressZip"]
					]);
					if($r){
						//echo "Updated successfully.";
						$stmt = $db->prepare(" SELECT id FROM Y_Orders WHERE user_id = :user_id ORDER BY created DESC LIMIT 1");
						$r = $stmt->execute([
							":user_id"=>get_user_id()
						]);
						$currentID=$stmt->fetch(PDO::FETCH_ASSOC);
						for($i = 0 ; $i < $index ; $i++){
							$stmt = $db->prepare("INSERT INTO Y_Orderitems (order_id, product_id, quantity, unit_price) VALUES (:order_id, :product_id, :quantity, :unit_price)");
							$r = $stmt->execute([
								":order_id"=>$currentID["id"],
								":product_id"=>$result3[$i]["productID"],
								":quantity"=>$result3[$i]["cartQuantity"],
								":unit_price"=>$result3[$i]["productPrice"]
							]);
							if($r){
								$successMessage="Your Order has been placed successfully!<br>Thank you and be back soon!<br>";
							}
							else{
								$e = $stmt->errorInfo();
							}
						}
						
					}
					else{
						$e = $stmt->errorInfo();
						echo var_export($e);
					}
					for($i = 0 ; $i < $index ; $i++){
						//echo $result3[$i]["productName"]."<br>";
						$newQuantity=$result3[$i]["productQuantity"]-$result3[$i]["cartQuantity"];
						//echo "New Quantity = ".$newQuantity."<br>";
						$stmt = $db->prepare("UPDATE Y_Products set quantity=:quantity where id=:id");
						$r = $stmt->execute([
							":quantity"=>$newQuantity,
							":id"=>$result3[$i]["productID"]
						]);
						if($r){
							//echo "Updated successfully.";
							
						}
						else{
							$e = $stmt->errorInfo();
							//echo "Error while updating.";
						}
					}
					$stmt = $db->prepare("DELETE FROM Y_Cart WHERE user_id=:user_id");
					$r = $stmt->execute([
						":user_id"=>get_user_id()
					]);
					if($r){
						//echo "Updated successfully.";
						
					}
				}
			}
			
			?>
		</div>
	</div>
	<?php require(__DIR__ . "/partials/flash.php");?>

	<!-- Modals -->
	<form method="POST">
		<div class="modal fade" id="purchaseModal" style="background-color: black;background-color: rgba(0, 0, 0, 0.6);" tabindex="-1" role="dialog" aria-labelledby="purchaseModal" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content  bg-dark">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Purchase Your Items</h5>
					<button type="button" class="close text-warning" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div style="width:100%;">
						<p style="width:50%;" class="float-left text-left">Total amount:</p>
						<p id="purchaseTotal" style="width:50%;display:inline-block;" class="float-right text-right text-danger"><?php echo $sumNum."$";?></p>
					</div>
					<div style="width:100%;">
						<label for="paymentMethod" class="float-left" style="width:50%;">Payment Method: </label>
						<select class="form-control bg-dark text-warning text-center float-right" name="paymentMethod" style="width:50%;display:inline-block;">
							<option value="cash">Cash</option>
							<option value="mastercard">MasterCard</option>
							<option value="visa">Visa</option>
							<option value="amex">Amex</option>
							<option value="other">Other</option>
						</select>
					</div>
					<p class="text-center" style="width:100%;margin-top:100px;border-top: 1px solid grey;">Address</p>
					<div style="width:100%;">
						
						<label class="float-left" style="width:50%;">Street: </label>
						<input class="form-control bg-dark text-warning text-center mx-auto" placeholder="" style="margin-top:10px;width:50%;" type="text" id="purchaseAddressStreet" name="purchaseAddressStreet" required/>
						<label class="float-left" style="width:50%;">City: </label>
						<input class="form-control bg-dark text-warning text-center mx-auto" placeholder="" style="margin-top:10px;width:50%;" type="text" id="purchaseAddress" name="purchaseAddressCity" required/>
						<label class="float-left" style="width:50%;">State: </label>
						<input class="form-control bg-dark text-warning text-center mx-auto" placeholder="" style="margin-top:10px;width:50%;" type="text" id="purchaseAddressState" name="purchaseAddressState" required/>
						<label class="float-left" style="width:50%;">Zip: </label>
						<input class="form-control bg-dark text-warning text-center mx-auto" placeholder="" style="margin-top:10px;width:50%;" type="text" id="purchaseAddressZip" name="purchaseAddressZip" required/>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<input type="submit" name="purchaseButton" class="btn btn-danger" value="Purchse"/>
				</div>
			</div>
		  </div>
		</div>
	</form>
	
	<?php if(isset($wrongdoings) && $wrongdoings!=""): ?>
		<script>
		jQuery(window).on('load', function(){       
		   $('#error').modal('show');
		});
		</script>
	<?php endif; ?>
	<div class="modal fade" id="error" style="background-color: black;background-color: rgba(0, 0, 0, 0.6);" tabindex="-1" role="dialog" aria-labelledby="purchaseModal" aria-hidden="true">
		 <div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content  bg-dark">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Oops! We have some problem!</h5>
					<button type="button" class="close text-warning" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-center">
					<div style="width:100%;margin-bottom:20px;">
						<?php if(isset($wrongdoings) && $wrongdoings!=""): ?>
							<p style="color:red;"> <?php echo $wrongdoings; ?> </p>
							<p>Please adjust the quantity to all items mentioned above</p>
						<?php endif; ?>
					</div>
					<button type="button" class="btn btn-secondary mx auto" data-dismiss="modal">Okay</button>
				</div>
			</div>
		  </div>
		</div>
	</div>
	
	<?php if(isset($successMessage) && $successMessage!=""): ?>
		<script>
		jQuery(window).on('load', function(){      
			$('#success').modal({backdrop: 'static', keyboard: false})
			$('#success').modal('show');
		});
		</script>
	<?php endif; ?>
	<div class="modal fade" id="success" style="background-color: black;background-color: rgba(0, 0, 0, 0.6);" tabindex="-1" role="dialog" aria-labelledby="purchaseModal" aria-hidden="true">
		 <div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content  bg-dark">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Purchase Completed!</h5>
				</div>
				<div class="modal-body text-center">
					<div style="width:100%;margin-bottom:20px;">
						<?php if(isset($successMessage) && $successMessage!=""): ?>
							<p style="color:yellow;"> <?php echo $successMessage; ?> </p>
						<?php endif; ?>
					</div>
					<button type="button" class="btn btn-secondary mx auto" onclick="okaySuccess()">Okay</button>
				</div>
			</div>
		  </div>
		</div>
	</div>
	
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
		
		function okaySuccess(){
			location.replace(location.href);
		}
	</script>
	<script src="bootstrap/Bootstrap4/conFusion/node_modules/jquery/dist/jquery.slim.min.js"></script>
    <script src="bootstrap/Bootstrap4/conFusion/node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>



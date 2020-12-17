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
			$index=0;
			if(true){
				$db = getDB();
				$stmt2 = $db->prepare("SELECT DISTINCT category from Y_Products");
				$stmt2->execute([
				]);
				$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
				//echo $result["name"];
			}
			if(isset($_POST["refresh"])){
				$whereText = "";
				$order = $_POST["order"];
				$orderType = "asc";
				
				if($_POST["category"]!=""){
					$whereText="WHERE category='".$_POST["category"]."'";
					if($_POST["keyword"]!=""){
						$whereText=$whereText." AND name LIKE '%".$_POST["keyword"]."%'";
						
						$stmt = $db->prepare("SELECT * FROM Y_Products WHERE category=:category AND name LIKE :keyword ORDER BY $order $orderType");
						$stmt->execute([
							":category" => 	$_POST["category"],
							":keyword"	=>	"%".$_POST["keyword"]."%"
						]);
						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}
					else{
						$stmt = $db->prepare("SELECT * FROM Y_Products WHERE category=:category ORDER BY $order $orderType");
						$stmt->execute([
							":category" => 	$_POST["category"]
						]);
						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}
				}
				elseif($_POST["keyword"]!=""){
					$stmt = $db->prepare("SELECT * FROM Y_Products WHERE name LIKE :keyword ORDER BY $order $orderType");
					$stmt->execute([
						":keyword"	=>	"%".$_POST["keyword"]."%"
					]);
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				}
				else{
					$stmt = $db->prepare("SELECT * FROM Y_Products ORDER BY $order $orderType");
					$stmt->execute([
					]);
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				}
				//echo $whereText."<br>";
				
			}
			else{
				$stmt = $db->prepare("SELECT * FROM Y_Products ORDER BY name asc");
				$stmt->execute([
				]);
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			?>
		</div>
	</div>
	<!-- PRODUCT TABLE -->
	<div class="row">
		<div class="col-2 text-center"></div>
		<div class="col-8 ">
			<div class="text-right" style="margin-top:30px;padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
				<p class="bg-warning text-dark text-center" style="border-radius: 10px;">Products</p>
				<form method="POST">
					<div style="margin-bottom:20px;">
						<label>Order:</label>
						<select class="btn btn-dark text-warning text-center" style="display: inline-block;" name="order">
						<option value="name">Alpabetical</option>
						<option value="price">By Price</option>
						</select>
						<label style="margin-left:20px;">Category:</label>
						<select class="btn btn-dark text-warning text-center" style="display: inline-block;" name="category">
							<option value="">All</option>
							<?php foreach($result2 as $value2): ?>
								<option value="<?php echo $value2["category"] ?>"><?php echo $value2["category"] ?></option>
							<?php endforeach; ?>
						</select>
						<label style="margin-left:20px;">Search:</label>
						<input class="btn btn-dark text-warning" type="text" name="keyword" placeholder="keyword" style="display: inline-block;"/>
						<input class="btn btn-dark" type="submit" name="refresh" value="Refresh" style="display: inline-block;margin-left:20px;"/>
					</div>
					<div style="height:300px;overflow:auto;border-radius: 10px;border: 2px solid yellow" class="bg-dark text-center">
						<table class="table table-dark table-striped text-center text-warning" style="">
							<thead>
								<tr class="bg-warning text-dark">
									<?php if(has_role("Admin")){echo "<th scope=\"col\">#</th>";}?>
									<th scope="col">Product Name</th>
									<th scope="col">Category</th>
									<th scope="col">Quantity</th>
									<th scope="col">Price</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(isset($result)){
									if($result!=false){
										foreach($result as $value){
											if(has_role("Admin") || $value["visibility"] == 1){
												if(!has_role("Admin")){
													echo "<tr><td>".$value["name"]."</td><td>".$value["category"]."</td><td>".$value["quantity"]."</td><td>".$value["price"]."$</td></tr>";
												}
												else{
													echo "<tr><td>".($index+1).".</td><td>".$value["name"]."</td><td><input style=\"width:150px;border-color: yellow;\" class=\"btn btn-dark text-yellow mx-auto\" type=\"text\" name=\"category".$index."\" value=\"".$value["category"]."\"/></td><td><input style=\"width:50px;border-color: yellow;\" class=\"btn btn-dark text-yellow mx-auto\" type=\"text\" name=\"quantity".$index."\" value=\"".$value["quantity"]."\"/></td><td><input style=\"width:100px;border-color: yellow;\" class=\"btn btn-dark text-yellow mx-auto\" type=\"text\" name=\"price".$index."\" value=\"".$value["price"]."\"/></td></tr>";
													$index++;
												}
											}
										}
									}
								}
								?>
							</tbody>
						</table>
					</div>
					<?php if(has_role("Admin")): ?>
						<input class="btn btn-dark" type="submit" name="updateInventory" value="Update Cart" style="margin-top:20px;"/>
					<?php endif ; ?>
				</form>
			</div>
		</div>
		<div class="col-2 text-center"></div>
	</div>
	<div class="row" style="margin-top:20px;margin-bottom:30px;">
		<div class="col-12 text-center">
			<?php
			if(isset($_POST["updateInventory"])){
				echo "Index: ".$index."</br>";
				for($i = 0 ; $i < $index ; $i++){
					if(($result[$i]["category"]!=$_POST["category".$i]) || ($result[$i]["quantity"]!=$_POST["quantity".$i]) || ($result[$i]["price"]!=$_POST["price".$i])){
						//$stmt = $db->prepare("UPDATE Y_Products set quantity=:quantity, category=:category, price=:price where id=:id");
						$r = $stmt->execute([
							":quantity"=>$_POST["quantity".$i],
							":category"=>$_POST["category".$i],
							":price"=>$_POST["price".$i],
							":id"=>$result[$i]["id"]
						]);
						if($r){
							//echo "Updated successfully.";
							//echo "<script>(function(){location.reload();})();</script>";
						}
						else{
							$e = $stmt->errorInfo();
							echo "Error while updating \"".$result[$i]["name"]."\".<br>";
						}
					}
				}
			}
			?>
		</div>
	</div>
	<?php require(__DIR__ . "/partials/flash.php");?>
</body>

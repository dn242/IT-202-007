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
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				//echo $result["name"];
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
					<div style="height:300px;overflow:auto;border-radius: 10px;border: 2px solid yellow" class="bg-dark text-center">
						<table class="table table-dark table-striped text-center text-warning" style="">
							<thead>
								<tr class="bg-warning text-dark">
									<th scope="col">Product Name</th>
									<th scope="col">Category</th>
									<th scope="col">Quantity</th>
									<th scope="col">Price</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$index=0;
								$sumNum=0;
								$sumQuan=0;
								if(isset($result)){
									if($result!=false){
										foreach($result as $value){
											if(has_role("Admin") || $value["visibility"] == 1){
												if(!has_role("Admin")){
													echo "<tr><td>".$value["name"]."</td><td>".$value["category"]."</td><td>".$value["quantity"]."</td><td>".$value["price"]."$</td></tr>";
												}
												else{
													echo "<tr><td>".$value["name"]."</td><td><input style=\"width:150px;border-color: yellow;\" class=\"btn btn-dark text-yellow mx-auto\" type=\"text\" name=\"category".$index."\" value=\"".$value["category"]."\"/></td><td><input style=\"width:50px;border-color: yellow;\" class=\"btn btn-dark text-yellow mx-auto\" type=\"text\" name=\"quantity".$index."\" value=\"".$value["quantity"]."\"/></td><td><input style=\"width:100px;border-color: yellow;\" class=\"btn btn-dark text-yellow mx-auto\" type=\"text\" name=\"price".$index."\" value=\"".$value["price"]."\"/></td></tr>";
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
				for($i = 0 ; $i < $index ; $i++){
					if(($result[$i]["category"]!=$_POST["category".$i]) || ($result[$i]["quantity"]!=$_POST["quantity".$i]) || ($result[$i]["price"]!=$_POST["price".$i])){
						$stmt = $db->prepare("UPDATE Y_Products set quantity=:quantity, category=:category, price=:price where id=:id");
						$r = $stmt->execute([
							":quantity"=>$_POST["quantity".$i],
							":category"=>$_POST["category".$i],
							":price"=>$_POST["price".$i],
							":id"=>$result[$i]["id"]
						]);
						if($r){
							//echo "Updated successfully.";
							echo "<script>(function(){location.reload();})();</script>";
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

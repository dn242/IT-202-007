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
				$db = getDB();
				$index=0;
				$stmt = $db->prepare("SELECT Y_Users.email AS customer, Y_Orderitems.created AS date, Y_Orderitems.quantity AS orderQuantity, Y_Products.name as productName, Y_Products.category as category, Y_Products.price as price, Y_Orders.user_id as id  FROM ((Y_Orderitems JOIN Y_Orders ON Y_Orderitems.order_id = Y_Orders.id) JOIN Y_Products ON Y_Orderitems.product_id =Y_Products.id) JOIN Y_Users ON Y_Orders.user_id = Y_Users.id");
				$stmt->execute([
				]);
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			?>
		</div>
	</div>
	<!-- PRODUCT TABLE -->
	<div class="row">
		<div class="col-2 text-center"></div>
		<div class="col-8 ">
			<div class="text-right" style="margin-top:30px;padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
				<p class="bg-warning text-dark text-center" style="border-radius: 10px;">Order History (Admin)</p>
				<div style="height:300px;overflow:auto;border-radius: 10px;border: 2px solid yellow" class="bg-dark text-center">
					<table class="table table-dark table-striped text-center text-warning" style="">
						<thead>
							<tr class="bg-warning text-dark">
								<th scope=\"col\">#</th>
								<th scope="col">Product Name</th>
								<th scope="col">Category</th>
								<th scope="col">Quantity</th>
								<th scope="col">Price</th>
								<th scope="col">Date</th>
								<th scope="col">Customer</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($result)){
								if($result!=false){
									foreach($result as $value){
										echo "<tr><td>".($index+1).".</td><td>".$value["productName"]."</td><td>".$value["category"]."</td><td>".$value["orderQuantity"]."</td><td>".$value["price"]."$</td><td>".$value["date"]."</td><td>".$value["customer"]."</td></tr>";
										$index++;
									}
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-2 text-center"></div>
	</div>
	<?php require(__DIR__ . "/partials/flash.php");?>
</body>

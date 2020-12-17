<head>
  <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bootstrap/Bootstrap4/conFusion/node_modules/bootstrap-social/bootstrap-social.css">
  
  <link rel="stylesheet" href="./css/general.css">
</head>


<body  >
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
				$current_user_id = get_user_id();
				$db = getDB();
				$stmt = $db->prepare("SELECT COUNT(Y_Orderitems.id) AS orders FROM Y_Orderitems JOIN Y_Orders ON Y_Orders.id = Y_Orderitems.order_id WHERE Y_Orders.user_id = $current_user_id");
				$r = $stmt->execute([]);
				$totalNum = $stmt->fetch(PDO::FETCH_ASSOC);
				if($r){
					//echo "Success! HERE";
					$orderListPages=floor($totalNum["orders"] / 10) + 1;
				}
				else{
					$e = $stmt->errorInfo();
					//echo var_export($e);
				}
				
				if(!isset($_GET["offset"])){
					$stmt = $db->prepare("SELECT Y_Orderitems.created AS date, Y_Orderitems.quantity AS orderQuantity, Y_Products.name as productName, Y_Products.category as category, Y_Products.price as price, Y_Orders.user_id as id  FROM (Y_Orderitems JOIN Y_Orders ON Y_Orderitems.order_id = Y_Orders.id) JOIN Y_Products ON Y_Orderitems.product_id =Y_Products.id WHERE Y_Orders.user_id = $current_user_id ORDER BY Y_Orders.created DESC LIMIT 10");
					$stmt->execute([
					]);
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$index=0;
				}
				else{
					$offset=$_GET["offset"];
					$stmt = $db->prepare("SELECT Y_Orderitems.created AS date, Y_Orderitems.quantity AS orderQuantity, Y_Products.name as productName, Y_Products.category as category, Y_Products.price as price, Y_Orders.user_id as id  FROM (Y_Orderitems JOIN Y_Orders ON Y_Orderitems.order_id = Y_Orders.id) JOIN Y_Products ON Y_Orderitems.product_id =Y_Products.id WHERE Y_Orders.user_id = $current_user_id ORDER BY Y_Orders.created DESC LIMIT 10 OFFSET $offset");
					$stmt->execute([
					]);
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$index=$offset;
					if($result){
						//echo "Success! HERE";
						//echo var_export($result);
					}
					else{
						$e = $stmt->errorInfo();
						//echo var_export($e);
					}
				}
				
			?>
		</div>
	</div>
	<!-- PRODUCT TABLE -->
	<div class="row">
		<div class="col-2 text-center"></div>
		<div class="col-8 ">
			<div class="text-right" style="padding: 15px 15px 15px 15px; border:2px solid #524f2a; border-radius: 10px; background:#262514; box-shadow: 0 0 15px 1px yellow;">
				<p class="bg-warning text-dark text-center" style="border-radius: 10px;">Your Orders</p>
				<div style="border-radius: 10px;border: 2px solid yellow" class="bg-dark text-center">
					<table class="table table-dark table-striped text-center text-warning" style="">
						<thead>
							<tr class="bg-warning text-dark">
								<th scope=\"col\">#</th>
								<th scope="col">Product Name</th>
								<th scope="col">Category</th>
								<th scope="col">Quantity</th>
								<th scope="col">Price Per Item</th>
								<th scope="col">Total Price</th>
								<th scope="col">Order Date</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($result)){
								if($result!=false){
									foreach($result as $value){
										if(get_user_id() == $value["id"]){
											$date=date("m-d-Y H:i", strtotime($value["date"]));
											echo "<tr><td>".($index+1).".</td><td>".$value["productName"]."</td><td>".$value["category"]."</td><td>".$value["orderQuantity"]."</td><td>".$value["price"]."$</td><td>".$value["price"]*$value["orderQuantity"]."$</td><td>".$date."</td></tr>";
											$index++;
										}
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
	<div style="margin-top:25px;padding-bottom:50px;width:100%;">
		<p class="float-left text-right" style="padding-left:20px;color:orange;width:10%;"> Pages: </p>
		<div class="pagination float-right" style="display:inline;width:90%;">
			<a href="./orderHistory.php?offset=0" class="text-center <?php if(!isset($_GET["offset"]) || (($_GET["offset"]/10)+1 == 1)){echo " active ";} ?>" style="margin-left:15px;margin-right:5px;display:inline;" value="<?php echo 1; ?>"><?php echo 1; ?></a>
			<?php for($i=2; $i<=$orderListPages; $i++): ?>
			<a href="./orderHistory.php?offset=<?php echo ($i-1)*10; ?>" class="text-center <?php if(($_GET["offset"]/10)+1 == $i){echo " active ";} ?>" style="margin-right:5px;display:inline;" value="<?php echo $i; ?>"><?php echo $i; ?> </a>
			<?php endfor ; ?>
		</div>
	</div>
	<?php require(__DIR__ . "/partials/flash.php");?>
	
</body>

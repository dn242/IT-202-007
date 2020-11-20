<?php
	require_once(__DIR__ . "/lib/helpers.php"); 
	$data = file_get_contents('php://input');
	//echo var_export($data);
	$some = json_decode($data);
	//echo $some->toDeleteID;
	$db = getDB();
	echo "I got product_id = ".$some->toDeleteID." and cart_id = ".$some->user_ID;
	$stmt = $db->prepare("DELETE FROM Y_Cart where product_id=:product_id AND user_id=:user_id");
	$r = $stmt->execute([
		":product_id"=>$some->toDeleteID,
		":user_id"=>$some->user_ID
	]);
	if($r){
		echo "worked";
	}
	else{
		//$e = $stmt->errorInfo();
		echo "error";
	}
?>
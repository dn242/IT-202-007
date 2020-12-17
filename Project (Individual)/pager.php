
<?php require_once(__DIR__ . "/lib/helpers.php"); ?>
<?php
	$data = file_get_contents('php://input');
	$some = json_decode($data);
	$offset = ($some->offset-1) * 5;
	$db = getDB();
	$stmt = $db->prepare("SELECT Y_Products.user_id as prof_id, Y_Products.name as name, Y_Users.username as username, Y_Products.visibility as visibility, Y_Products.id as id from Y_Products JOIN Y_Users ON Y_Products.user_id = Y_Users.id WHERE Y_Products.visibility = 1 ORDER BY Y_Products.name  LIMIT 5 OFFSET $offset");
	$stmt->execute([
		":offset"=>5
	]);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($result){
		//echo "worked";
	}
	else{
		$e = $stmt->errorInfo();
		//echo var_export($e);
	}
	foreach($result as $value){
		echo $value["name"]."~"."./profile.php?prof_id=".$value["prof_id"]."~".$value["username"]."~".$value["id"]."|";
	}
?>
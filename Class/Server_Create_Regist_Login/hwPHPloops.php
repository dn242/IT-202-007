<html>
	<head>
		<title>PHP LOOP HW</title>
	</head>
	
	<body>
	
		<?php 
			echo "<p>I will create an array with numbers.</p>"; 
			$array = array(
				"one" => "1",
				"seven" => "7",
				"four" => "4",
				"five" => "5",
				"two" => "2",
				"ten" => "10",
				"threeHundredTwentyThree" => "323",
			);
			echo "<p>Here is the array I have created. (Numbers are seperated by spaces)</p>"; 
			foreach($array as $key => $val) {
				echo $val;
				echo " ";
			}
			echo "<br>";
			echo "<p>Here are the odd numbers.</p>"; 
			foreach($array as $key => $val) {
				if($val % 2 != 0){
					echo $val;
					echo " ";	
				}
			}
		?> 
		
	</body>
</html>
<?php
include ("login_re.php"); 
				
				$id=$_REQUEST['id'];
				
	
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
			
				$textsql='UPDATE contract SET isValid = 0 WHERE id='.$id;
				
				//echo $textsql."<br>";
				$answsql=mysqli_query($db_server,$textsql);
				if(!$answsql) die("Database insert failed: ".mysqli_error($db_server));
				echo '<script>history.go(-2);</script>';	
	
				mysqli_close($db_server);
			
		?>
		
		
		
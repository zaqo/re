<?php 
//EXECUTION ENGINE FOR AJAX SEARCH ON THE FLIGHT ID FIELD

require_once 'login_re.php';	

	if(isset($_REQUEST['lead'])) $lead	= $_REQUEST['lead'];
		
		$content='';//<select class="services"><option value=""></option>';
	
		//Set up mySQL connection
			$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
			$db_server->set_charset("utf8");
			If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
			mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
		if($lead=='')
		{
			$content='';
		}
		else{
		// 1. SEARCH THE SERVICE TABLE
				$search_mysql='SELECT DISTINCT flight FROM avia.flights
								WHERE flight LIKE "'.$lead.'%"
								ORDER BY flight';
								
				$answsql=mysqli_query($db_server,$search_mysql);
								
				if(!$answsql) die("SEARCH in flight TABLE failed: ".mysqli_error($db_server));
				$content.='';//<div class="ajax_subfield" >';//onclick="fill()"
		
				while($row=mysqli_fetch_row($answsql))
				{	
					$content.='<li>'.$row[0].'</li>';//'<option value="'.$row[0].'">'.$row[1].'</option>';
				}
				//$content.='</div>';
		}
	mysqli_close($db_server);
	echo $content;
?>
	
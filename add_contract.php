<?php
include ("login_re.php"); 
				
				$num=$_REQUEST['num'];
				$date_d=$_REQUEST['date_d'];
				$date_m=$_REQUEST['date_m'];
				$date_y=$_REQUEST['date_y'];
				$exp_date_d=$_REQUEST['exp_date_d'];
				$exp_date_m=$_REQUEST['exp_date_m'];
				$exp_date_y=$_REQUEST['exp_date_y'];
				$clientid=$_REQUEST['client'];
				$type=$_REQUEST['type'];
				$vat=$_REQUEST['vat'];
				$currency=$_REQUEST['currency'];
				$comments= $_REQUEST['comments'];
	
				var_dump($num);var_dump($clientid);var_dump($exp_date_d);
	
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
				// Prepare dates
				$startmonth='';
				switch ($date_m) {
				case 1:
					$startmonth='Январь';
					break;
				case 2:
					$startmonth='Февраль';
					break;
				case 3:
					$startmonth='Март';
					break;
				case 4:
					$startmonth='Апрель';
					break;
				case 5:
					$startmonth='Май';
					break;
				case 6:
					$startmonth='Июнь';
					break;
				case 7:
					$startmonth='Июль';
					break;
				case 8:
					$startmonth='Август';
					break;
				case 9:
					$startmonth='Сентябрь';
					break;
				case 10:
					$startmonth='Октябрь';
					break;
				case 11:
					$startmonth='Ноябрь';
					break;
				case 12:
					$startmonth='Декабрь';
					break;
			}
			$endmonth='';
			switch ($exp_date_m) {
				case 1:
					$endmonth='Январь';
					break;
				case 2:
					$endmonth='Февраль';
					break;
				case 3:
					$endmonth='Март';
					break;
				case 4:
					$endmonth='Апрель';
					break;
				case 5:
					$endmonth='Май';
					break;
				case 6:
					$endmonth='Июнь';
					break;
				case 7:
					$endmonth='Июль';
					break;
				case 8:
					$endmonth='Август';
					break;
				case 9:
					$endmonth='Сентябрь';
					break;
				case 10:
					$endmonth='Октябрь';
					break;
				case 11:
					$endmonth='Ноябрь';
					break;
				case 12:
					$endmonth='Декабрь';
					break;
			}
			$startyear=0;
			switch ($date_y) {
				case 0:
					$startyear=2017;
				break;
				case 1:
					$startyear=2018;
				break;
				case 2:
					$startyear=2019;
				break;
				case 3:
				$startyear=2020;
				break;
				case 4:
				$startyear=2016;
				break;
			}
	$endyear = 0;
	switch ($exp_date_y) {
		case 17:
			$endyear=2017;
			break;
		case 18:
			$endyear=2018;
			break;
		case 3:
			$endyear=2019;
			break;
		case 4:
			$endyear=2020;
			break;
		case 5:
			$endyear=2021;
			break;
	}
	
	//$input_d=array($startyear,$startmonth,$date_d);
	//$date_q=implode("-",$input_d);
	
	$date_f=mktime(0,0,0,$date_m,$date_d,$date_y);
	$date_t=mktime(0,0,0,$exp_date_m,$exp_date_d,$exp_date_y);
	//echo "DATE is: ".$date_q."<\br>";
	$date_from=date("Y-m-d", $date_f);
	$date_to=date("Y-m-d", $date_t);
	$date_from_g=date("d-m-Y", $date_f);
	$date_to_g=date("d-m-Y", $date_t);
	//echo "PROCESSED DATE is: ".$date_my."<\br>";
					
				$textsql='INSERT INTO contract(number,date,exp_date,client_id,billing_type,VAT,currency,comments,isValid)
						VALUES ("'.$num.'","'.$date_from.'","'.$date_to.'","'.$clientid.'","'.$type.'","'.$vat.'",
						"'.$currency.'","'.$comments.'",1)';
				
				
				
				//echo $textsql."<br>";
				$answsql=mysqli_query($db_server,$textsql);
				if(!$answsql) die("Database insert failed: ".mysqli_error($db_server));
				echo '<script>history.go(-1);</script>';	
	
				mysqli_close($db_server);
			
		?>
		
		
		
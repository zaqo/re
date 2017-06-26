<?php
include ("login_re.php");
include_once("header.php"); 
   //set_time_limit(0);

echo <<<END
		<html>
		
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="/Agents/css/style.css" />
		<title>Карточка Договора</title>
	</head>
	<body>
END;
	$id= $_REQUEST['val'];
	
		$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
		$db_server->set_charset("utf8");
		If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
		mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		


			$textsql='SELECT * FROM  contract_ledger WHERE contract_id="'.$id.'" AND doc_type=1';
			$answsql=mysqli_query($db_server,$textsql);
			if(!$answsql) die("Database look up failed: ".mysqli_error($db_server));
			$rows = $answsql->num_rows;	
			//Screen Presentation	

			echo  "<br><hr><br><div class='colortext'> РЕЕСТР СЧЕТОВ ПО КОНТРАКТУ:</div>";
				
			echo "<br><table>";
			echo "<tr><th>№</th><th>Номер счета</th><th>Дата</th><th>Декада</th><th>Месяц</th>
				<th>Год</th><th>Сумма</th><th>Итог</th></tr>";
			
			for ($j=0; $j<$rows; $j++)
			{
				$Num=$j+1;
				$row= mysqli_fetch_row($answsql);
				$invoice_id=$row[2];
				$date_reg=$row[4];
				$sum=number_format($row[5],2,'.',' ');
				$result=number_format($row[9],2,'.',' ');
				
				$date_show=substr($date_reg, 8,2)."-".substr($date_reg, 5,2)."-".substr($date_reg, 2,2);
				
				echo "<tr><td>$Num</td><td>$invoice_id</td><td>$date_show</td><td>$row[6]</td>
					<td>$row[7]</td><td>$row[8]</td><td>$sum</td><td>$result</td></tr>";
			}
			
			echo "</table>"; 
		echo <<<_END
		<a href="show_contract.php?val=$id" > <img src="/re/src/arrow_left.png" alt="Go back" title="Back" width="64" height="64"></a>
		
_END;
				
mysqli_close($db_server);
?>
</body></html>
﻿<?php
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
		


				$textsql='SELECT * FROM  contract WHERE id="'.$id.'"';
				$answsql=mysqli_query($db_server,$textsql);
				if(!$answsql) die("Database look up failed: ".mysqli_error($db_server));
				$cData= mysqli_fetch_row($answsql);
				//Data Presentation
				$cdate_reg=$cData[3];
				$cdate_exp=$cData[4];
				
				$cdate_reg_show=substr($cdate_reg,8,2)."-".substr($cdate_reg, 5,2)."-".substr($cdate_reg, 2,2);
				$cdate_exp_show=substr($cdate_exp, 8,2)."-".substr($cdate_exp, 5,2)."-".substr($cdate_exp, 2,2);
					
				if ($cData[11]) $status="checked";
				if (!$cData[7]) $frame="-";
				
				echo "
						<table>
							<tr><th colspan='2' ><p>Контракт:</p></th></tr>
							<tr><td ><p>Номер: </p></td><td>$cData[2]</td></tr>
							<tr><td ><p>Дата: </p></td><td>$cdate_reg_show</td></tr>
							<tr><td ><p>Действителен до: </p></td><td>$cdate_exp_show</td></tr>
							<tr><td ><p>Номер SAP: </p></td><td>$cData[1]</td></tr>
							<tr><td ><p>Способ расчета: </p></td><td>$cData[6]</td></tr>
							<tr><td ><p>Рамочное соглашение: </p></td><td>$frame</td></tr>
							<tr><td ><p>НДС: </p></td><td>$cData[8]</td></tr>
							<tr><td ><p>Валюта: </p></td><td>$cData[9]</td></tr>
							<tr><td ><p>Комментарий: </p></td><td>$cData[10]</td></tr>
							<tr><td ><p>Действующий: </p></td><td><input type='checkbox' name='isValid' class='checkbox' value='isValid' $status disabled/></td></tr>
						</table>";					
				

				// Below section with invoices
	
			$query_invoices = "SELECT * FROM invoice 
							WHERE contract_id = '$id' AND isValid=TRUE ORDER BY month";
			$answsql=mysqli_query($db_server,$query_invoices);
			if(!$answsql) die("Database SELECT failed: ".mysqli_error($db_server));	
			$rows = $answsql->num_rows;

			echo  "<br><hr><br><div class='colortext'> СЧЕТА ПО КОНТРАКТУ:</div>";
				
			echo "<br><table>";
			echo "<tr><th>№</th><th>FI</th><th>Дата</th><th>Декада</th><th>Месяц</th>
				<th>Год</th><th>Сумма</th><th>НДС</th><th>Валюта</th><th>Удалить</th></tr>";
			//$red="<img src='/Agents/src/redcircle.png' alt='Penalty'  width='32' height='32'>";
			//$green="<img src='/Agents/src/greencircle.png' alt='Penalty'  width='32' height='32'>";
			$contract_total=0;
			for ($j=0; $j<$rows; $j++)
			{
				$Num=$j+1;
				$type="";
				$row= mysqli_fetch_row($answsql);
				$fi_id=$row[1];
				$date_reg=$row[4];
				$date_ins=$row[4];
				$sum=number_format($row[8],2,'.',' ');
				$contract_total+=$row[8];
				$vat=$row[9];
				$cur=$row[10];
				
				$date_show=substr($date_reg, 8,2)."-".substr($date_reg, 5,2)."-".substr($date_reg, 2,2);
				
				echo "<tr><td>$Num</td><td>$fi_id</td><td>$date_show</td><td>$row[5]</td>
					<td>$row[6]</td><td>$row[7]</td><td>$sum</td><td>$vat</td><td>$Currencies[$cur]</td>
						<td><a href='delete_invoice.php?val=$id' > <img src='/re/css/delete.png' alt='Delete' title='Удалить' ></a></td></tr>";
			}
			$contract_total=number_format($contract_total,2,'.',' ');
			echo "<tr><td colspan=\"10\" align=\"left\"> <b>ИТОГО:</b> $contract_total рублей </td></tr>";
			echo "</table>"; 
		echo <<<_END
		<a href="select_tenant.php" > <img src="/re/src/arrow_left.png" alt="Go back" title="Back" width="64" height="64"></a>
		<a href="invoice_form.php?val=$id" > <img src="/re/src/red-plus.png" alt="Create" title="Create invoice" width="64" height="64"></a>
_END;
				
mysqli_close($db_server);
?>
</body></html>
<?php
include ("login_re.php"); 
include ("header.php");

				
		$clientid=$_REQUEST['client'];
				
	
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
					
				
				$textsql='SELECT * FROM  contract WHERE client_id="'.$clientid.'"';
				
				$answsql=mysqli_query($db_server,$textsql);
				
				if(!$answsql) die("Database SELECT failed: ".mysqli_error($db_server));	
				$rows = $answsql->num_rows;

			$content="<br><div class='colortext'> Договора с Арендатором:</div>";
				
			$content.="<br><table>";
			$content.="<tr><th>№</th><th>Номер</th><th>Дата с:</th><th>Дата по:</th><th>НДС %</th><th>Валюта</th><th>Комментарий</th><th>Удалить</th></tr>";
			$red="<img src='/re/src/redcircle.png' alt='Penalty'  width='32' height='32'>";
			$green="<img src='/re/src/greencircle.png' alt='Bonus'  width='32' height='32'>";
			for ($j=0; $j<$rows; $j++)
			{
				$Num=$j+1;
				$type="";
				$row= mysqli_fetch_row($answsql);
				$comments=$row[10];
				$vat=$row[8];
				$c_number=$row[2];
				$date=$row[3];
				$date_ex=$row[4];
				$curr=$row[9];
				
				$date_show=substr($date,8,2)."-".substr($date, 5,2)."-".substr($date, 2,2);
				$date_exp=substr($date_ex, 8,2)."-".substr($date_ex, 5,2)."-".substr($date_ex, 2,2);
				
				$content.="<tr><td>$Num</td><td>$c_number</td><td>$date_show</td><td>$date_exp</td><td>$vat</td><td>$Currencies[$curr]</td>
				      <td><a href='show_contract.php?val=$row[0]'>$comments</a></td>
						<td><a href='delete_contract.php?val=$row[0]' > <img src='/re/css/delete.png' alt='Delete' title='Удалить' ></a></td></tr>";
			}
			$content.="</table>"; 
			$content.='<a href="select_tenant.php" > <img src="/re/src/arrow_left.png" alt="Go back" title="Back" width="64" height="64"></a>';
				
			//echo '<script>history.go(-1);</script>';	
			Show_page($content);
			mysqli_close($db_server);			
?>
		
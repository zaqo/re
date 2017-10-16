<?php
include ("login_re.php"); 
include ("header_tpl.php");

				
		$clientid=$_REQUEST['client'];
				
	
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
					
				
				$textsql='SELECT * FROM  contract WHERE client_id="'.$clientid.'"';
				
				$answsql=mysqli_query($db_server,$textsql);
				
				if(!$answsql) die("Database SELECT failed: ".mysqli_error($db_server));	
				$rows = $answsql->num_rows;
			$content='
			<!-- !PAGE CONTENT! -->
			<div class="w3-main" style="margin-left:340px;margin-right:40px">

			<!-- Header -->
			<div class="w3-container" style="margin-top:0px" id="showcase">
				<h1 class="w3-jumbo w3-text-grey"><b>#ИНВОЙСИРОВАНИЕ</b></h1>
				<h1 class="w3-xxxlarge w3-text-red"><b>коммерческая аренда</b></h1>
				<hr style="width:50px;border:5px solid red" class="w3-round">
			</div>';
			$content.='
			<div class="w3-card-4 w3-margin">
				<div class="w3-container w3-grey">
				<h2>ДОГОВОРА</h2>		
			</div>';
			
			$content.='<table class="w3-table-all w3-card-4">';
			$content.="<tr><th>№</th><th>Номер</th><th>Дата с:</th><th>Дата по:</th><th>НДС %</th><th>Валюта</th><th>Комментарий</th></tr>";
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
						</tr>";//<td><a href='delete_contract.php?val=$row[0]' > <img src='/re/css/delete.png' alt='Delete' title='Удалить' ></a></td>
			}
			$content.="</table></div>"; 
			//$content.='<a href="select_tenant.php" > <img src="/re/src/arrow_left.png" alt="Go back" title="Back" width="64" height="64"></a>';
				
			//echo '<script>history.go(-1);</script>';	
			Show_page($content);
			mysqli_close($db_server);			
?>
		
<?php
include ("login_re.php");
include_once("header.php"); 
   //set_time_limit(0);


	$id= $_REQUEST['val'];
	
		$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
		$db_server->set_charset("utf8");
		If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
		mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		


				$textsql='SELECT contract.isValid,contract.id_SAP,contract.number,contract.date,contract.exp_date,
							contract.billing_type,contract.frame,contract.VAT,currency.code,contract.comments,client.name,
							conditions.pay_terms
							FROM  contract 
							LEFT JOIN client ON client.id=contract.client_id
							LEFT JOIN currency ON currency.id=contract.currency
							LEFT JOIN conditions ON contract.id=conditions.contract_id
							WHERE contract.id='.$id;
				$answsql=mysqli_query($db_server,$textsql);
				if(!$answsql) die("Database look up failed: ".mysqli_error($db_server));
				$cData= mysqli_fetch_row($answsql);
				//Data Presentation
				$cdate_reg=$cData[3];
				$cdate_exp=$cData[4];
				$client_name=$cData[10];
				$pay_terms=$cData[11];
				//var_dump($pay_terms);
				
				$cdate_reg_show=substr($cdate_reg,8,2)."-".substr($cdate_reg, 5,2)."-".substr($cdate_reg, 2,2);
				$cdate_exp_show=substr($cdate_exp, 8,2)."-".substr($cdate_exp, 5,2)."-".substr($cdate_exp, 2,2);
					
				if ($cData[0]) $status="checked";
				if (!$cData[6]) $frame="-";
				$content='';
				$content.="
						<table class=\"fullTab\"><caption>$client_name</caption>
							<tr><th colspan='2' ><p>Контракт:</p></th></tr>
							<tr><td ><p>Номер: </p></td><td>$cData[2]</td></tr>
							<tr><td ><p>Дата: </p></td><td>$cdate_reg_show</td></tr>
							<tr><td ><p>Действителен до: </p></td><td>$cdate_exp_show</td></tr>
							<tr><td ><p>Номер SAP: </p></td><td>$cData[1]</td></tr>
							<tr><td ><p>Способ расчета: </p></td><td>$cData[5]</td></tr>
							<tr><td ><p>Рамочное соглашение: </p></td><td>$frame</td></tr>
							<tr><td ><p>НДС: </p></td><td>$cData[7]</td></tr>
							<tr><td ><p>Валюта: </p></td><td>$cData[8]</td></tr>
							<tr><td ><p>Комментарий: </p></td><td>$cData[9]</td></tr>
							<tr><td ><p>Действующий: </p></td><td><input type='checkbox' name='isValid' class='checkbox' value='isValid' $status disabled/></td></tr>
						</table>";					
				

				// Below section with invoices
	
			$query_invoices = "SELECT * FROM invoice 
							   WHERE contract_id =$id AND isValid=TRUE ORDER BY month";
			$answsql=mysqli_query($db_server,$query_invoices);
			if(!$answsql) die("Database SELECT failed: ".mysqli_error($db_server));	
			$rows = $answsql->num_rows;

			$content.="<br><hr><br><div class='colortext'> СЧЕТА ПО КОНТРАКТУ:</div>";
				
			$content.='<br><table class="myTab">';
			
			$colspan=9;
			$content.='<tr><th class="col1">№</th><th class="col2">Номер в SAP</th><th class="col3">Дата</th>';
			
			if($pay_terms==1) //decade
			{
				echo "HERE WE ARE";
				$content.='<th class="col4">Декада</th>';
				$colspan=10;
			}
			$content.='<th class="col5">Месяц</th><th class="col6">Год</th>
						<th class="col7">Сумма</th>
						<th class="col9">Валюта</th><th class="col10">Обработать</th><th class="col10">Удалить</th></tr>';
			
			//$red="<img src='/Agents/src/redcircle.png' alt='Penalty'  width='32' height='32'>";
			//$green="<img src='/Agents/src/greencircle.png' alt='Penalty'  width='32' height='32'>";
			$contract_total=0;
			for ($j=0; $j<$rows; $j++)
			{
				$Num=$j+1;
				$type="";
				$row= mysqli_fetch_row($answsql);
				$inv_id=$row[0];
				$fi_id=$row[1];
				$date_reg=$row[4];
				$date_ins=$row[4];
				$sum=number_format($row[8],2,'.',' ');
				$contract_total+=$row[8];
				$vat=$row[9];
				$cur=$row[10];
				
				$date_show=substr($date_reg, 8,2)."-".substr($date_reg, 5,2)."-".substr($date_reg, 2,2);
				
				$content.="<tr><td>$Num</td><td>$fi_id</td><td>$date_show</td>";
				if($pay_terms==1)
					$content.="<td>$row[5]</td>";
				$content.="<td>$row[6]</td><td>$row[7]</td><td>$sum</td><td>$Currencies[$cur]</td>";
				$content.="	<td><a id='rev' href='do_invoice.php?id=$inv_id'><img src='/re/src/do3.gif'  width='30' height='30' alt='Do' title='Обработать' ></a></td>";
				$content.="	<td><a href='delete_invoice.php?id=$inv_id' > <img src='/re/css/delete.png' alt='Delete' title='Удалить' ></a></td></tr>";
			}
			$contract_total=number_format($contract_total,2,'.',' ');
			
			$content.="<tr><td colspan=\"$colspan\" align=\"left\"> <b>ИТОГО:</b> $contract_total рублей </td></tr></table>";
			
		$content.='<a href="select_tenant.php" > <img src="/re/src/arrow_left.png" alt="Go back" title="Back" width="64" height="64"></a>
		<a href="invoice_form.php?val='.$id.'" > <img src="/re/src/red-plus.png" alt="Create" title="Create invoice" width="64" height="64"></a>
		<a href="show_ledger.php?val='.$id.'" > <img src="/re/src/registry.jpg" alt="Реестр" title="Перечень счетов" width="64" height="64"></a>';
Show_page($content);
				
mysqli_close($db_server);
?>

<?php
include ("login_re.php");
include_once("header_tpl.php"); 
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
				$c_num_erp=$cData[1];
				$c_num=$cData[2];
				$cdate_reg=$cData[3];
				$cdate_exp=$cData[4];
				$c_type=$cData[5];
				$c_vat=$cData[7];
				$c_cur=$cData[8];
				$c_comments=$cData[9];
				$client_name=$cData[10];
				$pay_terms=$cData[11];
				//var_dump($pay_terms);
				
				$cdate_reg_show=substr($cdate_reg,8,2)."-".substr($cdate_reg, 5,2)."-".substr($cdate_reg, 2,2);
				$cdate_exp_show=substr($cdate_exp, 8,2)."-".substr($cdate_exp, 5,2)."-".substr($cdate_exp, 2,2);
					
				if ($cData[0]) $status="checked";
				if (!$cData[6]) $frame="-";
				$content='
			<!-- !PAGE CONTENT! -->
			<div class="w3-main" style="margin-left:340px;margin-right:40px">

			<!-- Header -->
			<div class="w3-container" style="margin-top:0px" id="showcase">
				<h1 class="w3-jumbo w3-text-grey"><b>#ИНВОЙСИРОВАНИЕ</b></h1>
				<h1 class="w3-xxxlarge w3-text-red"><b>данные контракта.</b></h1>
				<hr style="width:50px;border:5px solid red" class="w3-round">
				</div>';
	 $content.='
				<div class="w3-container" id="packages" style="margin-top:0px">	
					<p>НДС приведен справочно. Информацию необходимо уточнять в SAP ERP.</p>
				</div>';
				$c_status="<input type='checkbox' name='isValid' class='w3-input w3-border chkbox' value='isValid' $status disabled/>";
				/* OLD SCHOOL
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
						</table>";		*/			
				$content.='
					<div class="w3-row-padding">
						<div class="w3-half w3-margin-bottom">
						<ul class="w3-ul w3-light-grey w3-center">
							<li class="w3-dark-grey w3-xlarge w3-padding-32">Номер:</li>
							<li class="w3-padding-16">Номер в ERP:</li>
							<li class="w3-padding-16">Дата:</li>
							<li class="w3-padding-16">Срок действия:</li>
							<li class="w3-padding-16">Способ расчета:</li>
							<li class="w3-padding-16">НДС:</li>
							<li class="w3-padding-16">Валюта:</li>
							<li class="w3-padding-16">Действующий:</li>
							<li class="w3-padding-16">Комментарий:</li>
							<li class="w3-light-grey w3-padding-24"></li>
						</ul>
					</div>';
				$content.='
					<div class="w3-half">
						<ul class="w3-ul w3-light-grey w3-center">
							<li class="w3-red w3-xlarge w3-padding-32">'.$c_num.'</li>
							<li class="w3-padding-16">'.$c_num_erp.'</li>
							<li class="w3-padding-16">'.$cdate_reg_show.'</li>
							<li class="w3-padding-16">'.$cdate_exp_show.'</li>
							<li class="w3-padding-16">'.$c_type.'</li>
							<li class="w3-padding-16">'.$c_vat.'%</li>
							<li class="w3-padding-16">'.$c_cur.'</li>
							<li class="w3-padding-16">'.$c_status.'</li>
							<li class="w3-padding-16">'.$c_comments.'</li>
							<li class="w3-light-grey w3-padding-24"></li>
						</ul>
					</div>
				';

				// Below section with invoices
	
			$query_invoices = "SELECT id,id_SD,date,decade,month,year,value,VAT,currency,isProcessed 
								FROM invoice 
							   WHERE contract_id =$id AND isValid=TRUE
							   ORDER BY month";
			$answsql=mysqli_query($db_server,$query_invoices);
			if(!$answsql) die("Database SELECT failed: ".mysqli_error($db_server));	
			$rows = $answsql->num_rows;

			//$content.="<br><hr><br><div class='colortext'> СЧЕТА ПО КОНТРАКТУ:</div>";
			 $content.='
				<div class="w3-container " id="invoices" style="margin-top:75px">
					<h1 class="w3-xxxlarge w3-text-red"><b>Открытые счета.</b></h1>
					<hr style="width:50px;border:5px solid red" class="w3-round">
					<p>По указанным счетам необходимо ввести данные для фактурирования.</p>
				';	
			$table_semi='<table class="myTab w3-table-all tubs" id="tab_semi" style="text-align:center">';
			$table_done='<table class="myTab w3-table-all tubs" id="tab_done" style="display:none">';
			$colspan=8;
			$common_block='<tr><th class="col1">№</th><th class="col2" style="text-align:center">Номер заказа</th><th class="col3">Дата</th>';
			
			if($pay_terms==1) //decade
			{
				
				$common_block.='<th class="col4">Декада</th>';
				$colspan=9;
			}
			$common_block.='<th class="col5">Месяц</th><th class="col6">Год</th>
						<th class="col7">Сумма</th>
						<th class="col9">Валюта</th><th class="col10"></th></tr>';
			$table_done.=$common_block;
			$table_semi.=$common_block;
			//$red="<img src='/Agents/src/redcircle.png' alt='Penalty'  width='32' height='32'>";
			//$green="<img src='/Agents/src/greencircle.png' alt='Penalty'  width='32' height='32'>";
			$semi_total=0;
			$done_total=0;
			for ($j=0; $j<$rows; $j++)
			{
				$Num=$j+1;
				$type="";
				$row= mysqli_fetch_row($answsql);
				$inv_id=$row[0];
				$sd_id=$row[1];
				$date_reg=$row[2];
				$date_ins=$row[2];
				$decade=$row[3];
				$month=$row[4];
				$year=$row[5];
				$sum=number_format($row[6],2,'.',' ');
				
				$vat=$row[7];
				$cur=$row[8];
				$proc_flag=$row[9];
				$tab_row='';
				$date_show=substr($date_reg, 8,2)."-".substr($date_reg, 5,2)."-".substr($date_reg, 2,2);
				
				$tab_row.="<tr><td>$Num</td><td>$sd_id</td><td style='text-align:center'>$date_show</td>";
				if($pay_terms==1)
					$tab_row.="<td style='text-align:center'>$decade</td>";
				$tab_row.="<td style='text-align:center'>$month</td><td <td style='text-align:center'>$year</td><td style='text-align:right'>$sum</td><td>$Currencies[$cur]</td>";
				$tab_row.="	<td style='text-align:center'><a id='rev' href='do_invoice.php?id=$inv_id'><img src='/re/src/do3.gif'  width='30' height='30' alt='Do' title='Обработать' ></a></td>";
				//$content.="	<td><a href='delete_invoice.php?id=$inv_id' > <img src='/re/css/delete.png' alt='Delete' title='Удалить' ></a></td></tr>";
				if($proc_flag)
				{
					$table_done.=$tab_row;
					$done_total+=$row[6];
				}
				else
				{
					$table_semi.=$tab_row;
					$semi_total+=$row[6];
				}
			}
			$done_total=number_format($done_total,2,'.',' ');
			$semi_total=number_format($semi_total,2,'.',' ');
			
			$table_done.="<tr><td colspan=\"$colspan\" style='text-align:center'> <b>ИТОГО:</b> $done_total рублей </td></tr></table></div>";
			$table_semi.="<tr><td colspan=\"$colspan\" style='text-align:center'> <b>ИТОГО:</b> $semi_total рублей </td></tr></table></div></div>";
			$menu_bar=' <div class="w3-bar w3-light-grey intronav">
							<button class="w3-bar-item w3-button" onclick="openTub(\'tab_done\')">Готовы</button>
							<button class="w3-bar-item w3-button" onclick="openTub(\'tab_semi\')">В процессе</button>
						</div> ';
			$content.=$menu_bar.$table_done.$table_semi;
		/*	
		$content.='<a href="select_tenant.php" > <img src="/re/src/arrow_left.png" alt="Go back" title="Back" width="64" height="64"></a>
		<a href="invoice_form.php?val='.$id.'" > <img src="/re/src/red-plus.png" alt="Create" title="Create invoice" width="64" height="64"></a>
		<a href="show_ledger.php?val='.$id.'" > <img src="/re/src/registry.jpg" alt="Реестр" title="Перечень счетов" width="64" height="64"></a>';*/
Show_page($content);
				
mysqli_close($db_server);
?>

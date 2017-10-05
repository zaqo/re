<?php
include ("header.php");
include ("login_re.php"); 
				
				$contractid=$_REQUEST['id'];
				$date_d=$_REQUEST['date_d'];
				$date_m=$_REQUEST['date_m'];
				$date_y=$_REQUEST['date_y'];
				$clientid=$_REQUEST['client'];
				$type=$_REQUEST['billing_type'];
				$vat=$_REQUEST['vat'];
				$currency=$_REQUEST['currency'];
				$comments= $_REQUEST['comments'];
				$revenue= $_REQUEST['revenue'];
				$cur=$Currencies[$currency];
				//var_dump($_REQUEST);
				$invoices_to_block=array();
	/*
				var_dump($contractid);
				var_dump($clientid);
				var_dump($date_d);
				var_dump($date_m);
				var_dump($date_y);
	*/
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
				// 1. find relevant condition
				
				$condsql='SELECT  min_payment,currency,percentage,pay_terms FROM conditions WHERE contract_id='.$contractid.' AND isValid=1';
				$answsql=mysqli_query($db_server,$condsql);
				$num=mysqli_num_rows($answsql);
				if($num)
				   $cData= mysqli_fetch_row($answsql);
				else 
					echo "DATABASE ERROR: Contract not found!";
				
				$min=$cData[0];
				$cur_cond=$cData[1];
				$pct=$cData[2];
				
				//echo "<br> Min payment = ".$cData[0];
				
				// 2. Check if the period was already invoiced
				
				$pay_terms=$cData[3];
				
					$bookingsql='SELECT  id,id_FI,date,value,decade,month,year,VAT,currency,comments FROM invoice
				              WHERE contract_id="'.$contractid.'" AND isValid=1 ORDER BY month';
				
				
				$answsql=mysqli_query($db_server,$bookingsql);
				$num=mysqli_num_rows($answsql);
				$input_string="";
				if($num)
				{	
				   //echo "По данному контракту найдены сл. счета($num - шт.) \n";
				   for($i=0;$i<$num;$i++)
				   {
						$invData[$i]= mysqli_fetch_row($answsql);
						
						
						if(($invData[$i][5]==$date_m)&&($invData[$i][6]==$date_y))
						{
								$invNum=$invData[$i][0];
								$input_string=$input_string."<input hidden type=\"text\" name=\"invoice[$i]\" class=\"value\" value=$invNum>";
								//array_push($invoices_to_block,$invData[$i][0]);
						}
					//echo "Date ".$invData[$i][2]." Value: ".$invData[$i][3]." \n";
					//var_dump($invData[$i]);
				   }
				}
				else $input_string="<input hidden type=\"text\" name=\"invoice[0]\" class=\"value\" value=\"NULL\">";
				// 3. Prepare the invoice
				
				if($type==1) //
				{
					$result=0;
					$revbased=$pct*$revenue;
						if($revbased>=$min)
							$result=$revbased;
						else
							$result=$min;
				}
				
				// 4. Inform user
				

	 

		$content='<h1>Найдены счета:</h1>';
		$content.='<table class="fullTab"><tr><th>#</th><th>Период</th><th>Сумма</th><th>Валюта</th><th>Дата создания</th></tr>';
		
			for($i=0;$i<$num;$i++)
			{
				$ipl=$i+1;
				$date_show=substr($invData[$i][2], 8,2)."-".substr($invData[$i][2], 5,2)."-".substr($invData[$i][2], 2,2);
				$inv_date=sprintf("%02d-%02d",$invData[$i][5],$invData[$i][6]);
				$inv_sum=number_format($invData[$i][3],2,'.',' ');
				$content.="<tr><td>$ipl</td><td>".$inv_date."</td><td>".$inv_sum."</td><td>$cur</td>
					<td>".$date_show."</td></tr>";
			}
		
		$content.='</table>';
		
		$content.='<h1>Новый счет:</h1>
		<form id="form" method="post" action="book_invoice.php" >
		<table class="fullTab"><tr><th>#</th><th>Период</th><th>Сумма</th><th>Валюта</th></tr>';
				$period=sprintf("%02d-%02d",$date_m,$date_y);
				$result_print=number_format($result,2,'.',' ');
				$content.="<tr><td>1</td><td>$period</td><td>$result_print</td><td>".$Currencies[$cur_cond]."</td></tr></table>";
	
		$content.='<p>Действия со счетом:</p>
			<div id="action_type">
			<table class="fullTab">
				<tr>
					<td><p>Зарегистрировать<input type="radio" id="reg" name="action"  value="1"></p></td>
					<td><p>Отменить<input type="radio" id="cancel" name="action"  value="0"></p></td>
				</tr>
			<tr><td colspan="5">
			<input hidden type="text" name="id" class="send" value='.$contractid.'>
			<input hidden type="text" name="billing_type" class="send" value="$type">
			<input hidden type="text" name="vat" class="send" value='.$vat.'>
			<input hidden type="text" name="currency" class="send" value='.$currency.'>
			<input hidden type="text" name="client" class="send" value='.$clientid.'>
			<input hidden type="text" name="date_d" class="date" value="'.$date_d.'">
			<input hidden type="text" name="date_m" class="date" value="'.$date_m.'">
			<input hidden type="text" name="date_y" class="date" value="'.$date_y.'">
			<input hidden type="text" name="value" class="value" value="'.$result.'">
			<input hidden type="text" name="comments" class="comments" value="'.$comments.'">
			<input hidden type="text" name="revenue" class="revenue" value="'.$revenue.'">
			'.$input_string.'
		<input type="submit" name="send" class="send" value="Дальше ->">
			</td></tr>
			</table>		
			</div>
			';				
	$content.='<br/>';
	Show_page($content);	
	mysqli_close($db_server);
			
?>
		
		
		
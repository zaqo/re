<?php
// IF INVOICE WAS PROCESSED AND SENT TO SAP - SHOWS INVOICE DATA
// OTHERWISE PRODUCE INPUT FORM AND DISPATCH TO SAP ERP 

include ("login_re.php"); 
include ("header_tpl.php"); 
//if(!$loggedin) echo "<script>window.location.replace('/Agents/login.php');</script>";

				$id= $_REQUEST['id'];
				//Connect to database
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
				//Prepare data set for the contract
				$textsql='SELECT  invoice.value,invoice.date,conditions.min_payment,conditions.percentage,contract.billing_type,
							invoice.decade,invoice.month,invoice.year,client.name,contract.number,invoice.currency,invoice.isProcessed,
							invoice.id_SD ,revenue.revenue
							FROM invoice 
							LEFT JOIN conditions ON invoice.contract_id=conditions.contract_id
							LEFT JOIN contract ON invoice.contract_id=contract.id
							LEFT JOIN client ON client.id=contract.client_id
							LEFT JOIN revenue ON invoice.id=revenue.invoice_id
							
							WHERE invoice.id='.$id;
				
				
				$answsql=mysqli_query($db_server,$textsql);
				$num=mysqli_num_rows($answsql);
				if($num)
				   $num= mysqli_fetch_row($answsql);
				else 
					echo "DATABASE ERROR: INVOICE not found!";
				//var_dump($cData);
				$inv_val=$num[0];
				$inv_date=$num[1];
				$min=$num[2];
				$inv_min_pub=number_format($num[2]);
				$pct=$num[3];
				$pct_show=$pct*100;
				$type=$num[4];
				$dec=$num[5];
				$month=$num[6];
				$year=$num[7];
				$client=$num[8];
				$c_num=$num[9];
				$cur=$num[10];
				$done_flag=$num[11];
				$order_id=$num[12];
				$rev=$num[13];
				$srv_id=$num[14];
				$period='';
				if($month<10)
					$month='0'.$month;
				if($dec)
					$period=$dec.' дек. '.$month.' / '.$year;
				else
					$period.=$month.' / '.$year;					
				switch ($cur)
				{
					case 1:
						$cur_txt=' руб';
						break;
					case 2:
						$cur_txt='$';
						break;
					case 3:
						$cur_txt='EUR';
						break;
					default:
						$cur_txt='ERROR: WRONG CURRENCY ID!!!';
						break;
				}
		$rev_block='';
		$val_block='';
		$order_block='<p><label class="w3-text-grey"><b>Заказ SD: </label><div class="sd_order">'.$order_id.'</div></b></p>';
		$enter_button='';
		$header='';
		if($done_flag)
		{
			$rev_block.=number_format($rev);
			$val_block.=number_format($inv_val);
			
			$header='данные документа';
			$hr_block='<hr style="width:50px;border:5px solid green" class="w3-round">';
		}
		else
		{
			$rev_block='<input type="text" class="display w3-margin" id="input_row" value="" placeholder="1 000 000" />
						<button type="button" class="re_button w3-btn w3-margin w3-white w3-round-small w3-tiny w3-border w3-border-grey w3-hover-yellow" >РАСЧЕТ</button>';
			$enter_button='<button type="button" id="send" class="w3-btn w3-white w3-padding-large w3-margin w3-hover-grey w3-border w3-border-red" value="">ВВОД</button>';
			$header='регистрация';
			$hr_block='<hr style="width:50px;border:5px solid red" class="w3-round">';
		}			
	$content='
			<!-- !PAGE CONTENT! -->
			<div class="w3-main" style="margin-left:340px;margin-right:40px">

			<!-- Header -->
			<div class="w3-container" style="margin-top:0px" id="showcase">
				<h1 class="w3-jumbo w3-text-grey"><b>#СЧЕТ</b></h1>
				<h1 class="w3-xxxlarge w3-text-red"><b>'.$header.'</b></h1>
				'.$hr_block.'
			</div>';
	
	
	$content.='	<div class="w3-card-4">
					<div class="w3-container w3-grey">
							<h2>СЧЕТ # '.$id.'</h2>
					</div>
					<div class="r_e">
						<form id="form" class="re_form" action="book_invoice.php">';
		
		$content.='<p><label class="w3-text-grey"><b>Клиент: </label>'.$client.'</b></p>
						<p><label class="w3-text-grey"><b>Контракт: </label>'.$c_num.'</b></p>
						<p><label class="w3-text-grey"><b>Период: </label>'.$period.'</b></p>';
		$min_payment=	'<p><label class="w3-text-grey"><b>Mин.платеж: </label>'.$inv_min_pub.' '.$cur_txt.'</b></p>';
		if(($type==2)||($type==3)) $content.=$min_payment;
		$content.='		<p><label class="w3-text-grey"><b> Процент с оборота:</label>   '.$pct_show.'%</b></p>
						<p><label class="w3-text-grey"><b>Оборот : </label><div class="input_row">'.$rev_block.' руб</div></b></p>
						'.$order_block.'
						<p><label class="w3-text-grey"><b>СУММА:<div id="inv_val">'.$val_block.'</div></b></p>
						<p>
							<div id="errors" class="w3-red w3-border-red"></div><div id="returned" class="w3-grey w3-border-grey"></div>
							<input hidden type="text" id="min" value="'.$min.'">
							<input hidden type="text" id="pct" value="'.$pct.'">
							<input hidden type="text" id="type" value="'.$type.'">
							<input hidden type="text" id="currency" value="'.$cur_txt.'">
							<input hidden type="text" id="out_value" value="">
							<input hidden type="text" id="invoice_id" value="'.$id.'">'.$enter_button.'
							<button type="button" id="back" class="w3-btn w3-white w3-padding-large w3-margin w3-hover-grey w3-border w3-border-black" value="">НАЗАД</button>
						</p>';
				
				$content.='
						</form>
					</div>
				</div>
			</div>
		';
		
		Show_page($content);
			mysqli_free_result($answsql);
			mysqli_close($db_server);
?>

<?php
// IF INVOICE WAS PROCESSED AND SENT TO SAP - SHOWS INVOICE DATA
// OTHERWISE PRODUCE INPUT FORM AND DISPATCH TO SAP ERP 

include ("login_re.php"); 
include ("header_tpl.php"); 
//echo '<script src="/re/js/re_input.js"></script>';
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
							invoice.id_SD ,revenue.revenue,service_reg.service_id
							FROM invoice 
							LEFT JOIN conditions ON invoice.contract_id=conditions.contract_id
							LEFT JOIN contract ON invoice.contract_id=contract.id
							LEFT JOIN client ON client.id=contract.client_id
							LEFT JOIN revenue ON invoice.id=revenue.invoice_id
							LEFT JOIN service_reg ON contract.type=service_reg.contract_type_id
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
		$order_block='<p><label class="w3-text-grey"><b>Заказ SD: <div class="sd_order">'.$order_id.'</div></b></label></p>';
		$enter_button='';
		$header='';
		if($done_flag)
		{
			
			$val_block.=number_format($inv_val).$cur_txt;
			
			$header='данные документа';
			$hr_block='<hr style="width:50px;border:5px solid green" class="w3-round">';
		}
		else
		{
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
		
		$content.='
						<p><label class="w3-text-grey"><b>Клиент: '.$client.'</b></label></p>
						<p><label class="w3-text-grey"><b>Контракт: '.$c_num.'</b></label></p>
						<p><label class="w3-text-grey"><b>Период: '.$period.'</b></label></p>
						'.$order_block.'
						<p><label class="w3-text-grey"><b>СУММА:<div id="inv_val">'.$val_block.'</div></b></label></p>
						<p>
							<div id="errors" class="w3-red w3-border-red"></div><div id="returned" class="w3-grey w3-border-grey"></div>
							<input hidden type="text" id="min" value="'.$min.'">
							<input hidden type="text" id="pct" value="'.$pct.'">
							<input hidden type="text" id="type" value="'.$type.'">
							<input hidden type="text" id="currency" value="'.$cur_txt.'">
							<input hidden type="text" id="out_value" value="'.$inv_val.'">
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

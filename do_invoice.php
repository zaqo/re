<?php
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
							invoice.decade,invoice.month,invoice.year,client.name,contract.number, invoice.currency
							FROM invoice 
							LEFT JOIN conditions ON invoice.contract_id=conditions.contract_id
							LEFT JOIN contract ON invoice.contract_id=contract.id
							LEFT JOIN client ON client.id=contract.client_id
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
				
	$content='
			<!-- !PAGE CONTENT! -->
			<div class="w3-main" style="margin-left:340px;margin-right:40px">

			<!-- Header -->
			<div class="w3-container" style="margin-top:0px" id="showcase">
				<h1 class="w3-jumbo w3-text-grey"><b>СЧЕТ</b></h1>
				<h1 class="w3-xxxlarge w3-text-red"><b>регистрация</b></h1>
				<hr style="width:50px;border:5px solid red" class="w3-round">
			</div></div>';
	$content.='	<div class="w3-card-4">
					<div class="w3-container w3-grey">
							<h2>Данные счета</h2>
					</div>
					<div class="r_e">
						<form id="form" class="re_form">';
				
				$content.='
						<p><label class="w3-text-grey"><b>Клиент: </label>'.$client.'</b></p>
						<p><label class="w3-text-grey"><b>Контракт: </label>'.$c_num.'</b></p>
						<p><label class="w3-text-grey"><b>Период: </label>'.$period.'</b></p>
						<p><label class="w3-text-grey"><b>Mин.платеж: </label>'.$inv_min_pub.' '.$cur_txt.'</b></p>
						<p><label class="w3-text-grey"><b> Процент с оборота:</label>   '.$pct_show.'%</b></p>
						<p><label class="w3-text-grey"><b>Оборот, руб : </label><div class="input_row"><input type="text" class="display" value="" placeholder="1 000 000" /></div></b></p>
						
						<p><label class="w3-text-grey"><b>СУММА:<div id="inv_val"></div></b></p>
						<p style="margin-left:60px"><button type="button" class="re_button w3-btn w3-grey w3-round-xlarge w3-tiny w3-border w3-border-red w3-hover-yellow" >РАСЧЕТ</button></p>
						<p>
							<input hidden type="text" name="billing_type" class="send" value=1>
							<input hidden type="text" id="min" value='.$min.'>
							<input hidden type="text" id="pct" value='.$pct.'>
							<input hidden type="text" id="currency" value='.$cur_txt.'>
						<input type="submit" name="send" class="w3-btn w3-red" value="ВВОД">
						</p>';
				
				$content.='
						</form>
					</div>
				</div>
			</div>
		';
		$content.='<script>
// Script to open and close sidebar
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}
 
function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}

// Modal Image Gallery
function onClick(element) {
  document.getElementById("img01").src = element.src;
  document.getElementById("modal01").style.display = "block";
  var captionText = document.getElementById("caption");
  captionText.innerHTML = element.alt;
}
</script>';
		Show_page($content);
			mysqli_free_result($answsql);
			mysqli_close($db_server);
?>

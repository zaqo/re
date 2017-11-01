<?php require_once 'login_avia.php';
//LINKING SERVICES TO THE DISCOUNT
include ("header_tpl_doc.php"); 
	

	if(isset($_REQUEST['flight'])) 		$flight	= $_REQUEST['flight'];
	if(isset($_REQUEST['date'])) 		$date	= $_REQUEST['date'];
	if(isset($_REQUEST['qty'])) 		$quantity	= $_REQUEST['qty'];
	if(isset($_REQUEST['comment'])) 	$comment	= $_REQUEST['comment'];
	if(isset($_REQUEST['who'])) 		$doctor	= $_REQUEST['who'];
	//if(isset($_REQUEST['flight'])) 		$flight	= $_REQUEST['flight'];
	
		$content="";
		$date_show=substr($date,6,4)."-".substr($date, 3,2)."-".substr($date, 0,2);
		echo $date_show;
		$today=date("d.m.y");;
		//Set up mySQL connection
			$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
			$db_server->set_charset("utf8");
			If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
			mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
		// 1. RECORD THE SERVICE
				$transfer_mysql='INSERT INTO avia.medical_reg
								(flight,date,qty,who,comment,isValid) 
								VALUES
								("'.$flight.'","'.$date_show.'","'.$quantity.'","'.$doctor.'","'.$comment.'",1)'; //A0300462 HARDCODED NOW!!!
								
								$answsqlnext=mysqli_query($db_server,$transfer_mysql);
								
								if(!$answsqlnext) die("INSERT into TABLE failed: ".mysqli_error($db_server));
		
		// Top of the table
		$content.='<div class="w3-main" style="margin-left:340px;margin-right:40px"><div class="w3-container">';
		$content.='<h2 align="center">СПРАВКА</h2>';
		$content.='<h3 align="center"> о прохождении предполетного досмотра</h3>';
		$content.="<div align=\"center\"> экипаж рейса: $flight <tab5>АК: </div><br/>";
		$content.= '<table class=" w3-table-all w3-margin-top w3-margin-bottom">';
		
		$content.= '<tr><th rowspan="2" style="text-align:center; vertical-align:middle">ДАТА</th><th colspan="2" style="text-align:center; vertical-align:middle">Сведения по услугам</th><th rowspan="2" style="text-align:center; vertical-align:middle">Примечание</th></tr>';
		
				
				$content.="<tr><td style='text-align:center'>Ед.изм.</td><td style='text-align:center'>Кол-во</td></tr>";
				//$content.="<tr><td>КЛИЕНТ:</td><td>$customer</td></tr>";
				$content.="<tr ><td style='text-align:center'>1</td><td style='text-align:center'>2</td><td style='text-align:center'>3</td><td style='text-align:center'>4</td></tr>";
				$content.="<tr><td>$today</td><td style='text-align:center'>ЧЕЛ</td><td style='text-align:center'>$quantity</td><td style='text-align:center'>$comment</td></tr>";
				$content.= '</table>';
				$content.='<br/><br/><br/><div><tab2>ПОДПИСИ:</div><br/><br/>';
				$content.='<div><tab2><b>От: ООО ВВСС <tab6>От: ЭКИПАЖА</b></div><br/><br/>';
				$content.='<div align="center">_____________ <tab6>_____________<div><br/>';
				$content.='<div align="center">(подпись Ф.И.О.)<tab6>(подпись Ф.И.О.)</div><br/><br/>';
				$content.='</div></div>';
				
	Show_page($content);
	mysqli_close($db_server);
	
?>
	
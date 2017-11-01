<?php
include ("login_re.php");
include_once("header_tpl_doc.php"); 
//echo '<script src="/re/js/re_input.js"></script>';
   //set_time_limit(0);


	
	$content='
			<!-- !PAGE CONTENT! -->
			<div class="w3-main" style="margin-left:340px;margin-right:40px">

			<!-- Header -->
			<div class="w3-container" style="margin-top:0px" id="showcase">
				<h1 class="w3-jumbo w3-text-grey"><b>#МЕДОСМОСТР</b></h1>
				<h1 class="w3-xxxlarge w3-text-red"><b>журнал операций.</b></h1>
				<hr style="width:50px;border:5px solid red" class="w3-round">
				</div>';
			$content.='
				<div class="w3-container" id="packages" style="margin-top:0px">	
				<h1 class="w3-xxxlarge w3-text-red"><b></b></h1>
					<p></p>
				</div>';
				
			$content.='<div id="tab_semi" class="tabs"><table class="myTab w3-table-all w3-margin-top w3-margin-bottom" >';
			//$table_done='<div id="tab_done" class="tabs" style="display:none"><table class="myTab w3-table-all w3-margin-top w3-margin-bottom" >';
			$colspan=9;
			$content.='<tr><th class="col1" style="text-align:center; vertical-align:middle;">№</th><th class="col_4" style="text-align:center" >Номер рейса</th><th class="col_5" style="text-align:center; vertical-align:middle;">Дата</th>';
			
			
			$content.='<th class="col_4" style="text-align:center; vertical-align:middle;">Кол-во</th>
			<th class="col_4" style="text-align:center; vertical-align:middle;">Врач</th>
			<th class="col7" style="text-align:center; vertical-align:middle;">Комментарий</th><th class="col1"></th></tr>';
			
		$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
		$db_server->set_charset("utf8");
		If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
		mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
				$textsql='SELECT id,flight,date,qty,who,comment
							FROM  avia.medical_reg 
							WHERE isValid=1';
				$answsql=mysqli_query($db_server,$textsql);
				if(!$answsql) die("SELECT TO avia.medical_reg table failed: ".mysqli_error($db_server));
			$Num=1;
			
			while($cData= mysqli_fetch_row($answsql))
			{
				//Data Presentation
				$id=$cData[0];
				$flight=$cData[1];
				$date=$cData[2];
				$qty=$cData[3];
				$who=$cData[4];
				$comment=$cData[5];
				$tab_row='';
				$date_show=substr($date,8,2)."-".substr($date, 5,2)."-".substr($date, 2,2);
				//$cdate_exp_show=substr($cdate_exp, 8,2)."-".substr($cdate_exp, 5,2)."-".substr($cdate_exp, 2,2);
				
				$tab_row.="<tr><td style='text-align:center'>$Num</td><td style='text-align:center'>$flight</td><td style='text-align:center'>$date_show</td>";
				
				$tab_row.="<td style='text-align:center'>$qty</td><td style='text-align:center'>$who</td><td style='text-align:right'>$comment</td>";
				
				$tab_row.="	<td ><a href='delete_record.php?id=$id' ><img src='/re/css/delete.png' alt='Delete' title='Удалить' ></a></td></tr>";
				$content.=$tab_row;
				$Num++;
			}
			
			$button='</table></div><div class="w3-container" id="create_inv"><button id="create_button" class="w3-button w3-block w3-red w3-margin" style="width:30%" onclick="history.go(-1)">Назад</button></div>';
			//$content.=$menu_bar.$table_done.$table_semi.$end_invoices;
			$content.=$button;
			$content.='</div>';
		
Show_page($content);
				
mysqli_close($db_server);
?>

<?php
include ("login_re.php"); 
include ("header.php"); 
//if(!$loggedin) echo "<script>window.location.replace('/Agents/login.php');</script>";

				$id= $_REQUEST['val'];
				//Connect to database
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
				//Prepare data set for the contract
				$textsql='SELECT  number,date,billing_type,frame,vat,currency,valid,client.name,client.id FROM contract LEFT JOIN client ON contract.client_id=client.id WHERE contract.id='.$id.' ORDER BY date';
				$answsql=mysqli_query($db_server,$textsql);
				$num=mysqli_num_rows($answsql);
				if($num)
				   $cData= mysqli_fetch_row($answsql);
				else 
					echo "DATABASE ERROR: Contract not found!";
				var_dump($cData);
				$content='<h1>Создаем счет на оплату:</h1><table>
				<form id="form" method="post" action="add_invoice.php" >';
				$content.='<tr><td><p><b>Арендатор:</b></p></td><td>
							<div id="client">'.$cData[7].'</div></td></tr>';
				
			$content.='
			<tr><td><p>Укажите период:</p></td><td>
			<table>
				<tr><th><p>Декада:</p></th><th><p>Месяц:</p></th><th><p>Год:</p></th></tr>
			<tr><td><div id="daydiv"><p> 	
				<select name="date_d" id="decade" class="date" >
				<option value="" hidden> Выбрать </option>
				<option value="01">1</option>
				<option value="02">2</option>
				<option value="03">3</option>
				</select></p>
			</div></td>
		 	<td><div id="date_m"><p> 	
				<select name="date_m" id="month" class="date" >
				<option value="" hidden> Выбрать </option>
				<option value="1">Январь</option>
				<option value="2">Февраль</option>
				<option value="3">Март</option>
				<option value="4">Апрель</option>
				<option value="5">Май</option>
				<option value="6">Июнь</option>
				<option value="7">Июль</option>
				<option value="8">Август</option>
				<option value="9">Сентябрь</option>
				<option value="10">Октябрь</option>
				<option value="11">Ноябрь</option>
				<option value="12">Декабрь</option>
				</select></p>
			</div>
			</td>
			<td><div id="date_y">
			<p><select name="date_y" id="yfr" class="date" >
				<option value="17">2017</option>
				</select>
			</p></div>
			</td></tr>
			</table></td></tr>
			
			<tr><td>Оборот за период:</td><td><input type="text" id="revenue" name="revenue" class="figures" placeholder="1 000 000" />руб.</td></tr>
			<tr><td>Комментарий:</td><td><input type="text" name="comments" class="figures" placeholder="Назначение платежа" /></td></tr>
			<tr><td colspan="2" ><p><div id="errors"></div></p>
			<input hidden type="text" name="id" class="send" value='.$id.'>
			<input hidden type="text" name="billing_type" class="send" value="'.$cData[2].'">
			<input hidden type="text" name="vat" class="send" value="'.$cData[4].'">
			<input hidden type="text" name="currency" class="send" value='.$cData[5].'>
			<input hidden type="text" name="client" class="send" value="'.$cData[8].'">
			<p><input type="submit" name="send" class="send" value="Рассчитать"></p></td></tr>
			</table></form>
		';
		Show_page($content);
			mysqli_free_result($answsql);
			mysqli_close($db_server);
?>

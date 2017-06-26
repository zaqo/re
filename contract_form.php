<?php
include ("login_re.php"); 
//include ("header.php"); 
//if(!$loggedin) echo "<script>window.location.replace('/Agents/login.php');</script>";
?>
<html lang="ru">
	<head>
		<title>Создать контракт</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
		
		<link rel="stylesheet" type="text/css" href="/re/css/style.css" />
		<!--[if lt IE 9]> 
			<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<!--<script type="text/javascript" src="./js/jquery.js"></script>-->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	</head>
	<body>
	 

		<h1>Карточка контракта</h1>
		
		<form id="form" method="post" action="add_contract.php" >
			<p>Арендатор:</p>
			<div id="client">
			<?php
				
				//Connect to database
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
				//Prepare list of cliens
				$textsql='SELECT  id,name FROM client WHERE isValid=1 ORDER BY name';
				$answsql=mysqli_query($db_server,$textsql);
				$num_of_cls=mysqli_num_rows($answsql);
				$i=0;
				$cls_in=array();
				$cl_string='';
					for ($i=0;$i<$num_of_cls;$i++)  
					{
						$cls_in[$i]= mysqli_fetch_row($answsql);
						$cl_string=$cl_string.'<option value="'.($cls_in[$i][0]).'">'.($cls_in[$i][1]).'</option>';
					}
				$cl_string='<select class="client" id="client" name="client"><option value=""></option>'.$cl_string.'</select>';
				echo $cl_string;?>

			</div>
			<p>Дата подписания:</p>
			<table>
				<tr><th><p>День:</p></th><th><p>Месяц:</p></th><th><p>Год:</p></th></tr>
			<tr><td><div id="day"><p> 	
				<select name="date_d" id="day" class="day" >
				<option value="" hidden> Выбрать </option>
				<option value="01">1</option>
				<option value="02">2</option>
				<option value="03">3</option>
				<option value="04">4</option>
				<option value="05">5</option>
				<option value="06">6</option>
				<option value="07">7</option>
				<option value="08">8</option>
				<option value="09">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
				</select></p>
			</div></td>
		 	<td>
			<div id="date_m"><p> 	
				<select name="date_m" id="mfr" class="date" >
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
			<td>
			<p><select name="date_y" id="yfr" class="date" >
				<option value="" hidden> Выбрать </option>
				<option value="2016">2016</option>
				<option value="2017">2017</option>
				</select>
			</p>
			</td></tr></table>
			<p>Дата окончания:</p>
			<table>
				<tr><th><p>День:</p></th><th><p>Месяц:</p></th><th><p>Год:</p></th></tr>
			<tr><td>
			<div id="day"><label><p> 	
				<select name="exp_date_d" id="day_to" class="date" >
				<option value="" hidden> Выбрать </option>
				<option value="01">1</option>
				<option value="02">2</option>
				<option value="03">3</option>
				<option value="04">4</option>
				<option value="05">5</option>
				<option value="06">6</option>
				<option value="07">7</option>
				<option value="08">8</option>
				<option value="09">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
				</select></p></label>
			</div>
			</td><td>
			<div id="month_to"><label><p> 	
				<select name="exp_date_m" id="mto" class="date" >
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
				</select></p></label>
			</div>
			</td><td>
			<p>Год: <select name="exp_date_y" id="yto" class="date" >
				<option value="" hidden> Выбрать </option>
				<option value="2017">2017</option>
				<option value="2018">2018</option>
				<option value="2019">2019</option>
				<option value="2020">2020</option>
				</select>
			</p>
			</td></tr></table>
			<p>Тип контракта: <select name="type" id="type" class="option" >
				<option value="" hidden> Выбрать </option>
				<option value="1">нарастающим итогом</option>
				<option value="2"> % от выручки ИЛИ MIN</option>
				<option value="3"> % от выручки + MIN</option>
				<option value="4"> % от выручки  в Месяц</option>
				</select>
			</p>
			<p>НДС: <select name="vat" id="vat" class="option" >
				<option value="" hidden> Выбрать </option>
				<option value="18">18%</option>
				<option value="10"> 10%</option>
				<option value="0"> без НДС</option>
				</select>
			</p>
			<p>Валюта: <select name="currency" id="cur" class="option" >
				<option value="" hidden> Выбрать </option>
				<option value="1">RUR</option>
				<option value="2">EUR</option>
				<option value="3">USD</option>
				</select>
			</p>
			<p>Номер контракта:</p>
			<div id="number"> 	
				<p><input type="text" id="num" name="num"></p>
			</div>
			<p>Рамочное соглашение:</p>
			<div id="haveFramework">
			<table>
				<tr>
					<td><p>Да<input type="radio" id="yes" name="feedback" onclick="javascript:yesnoCheck();" value="1"></p></td>
					<td><p>Нет<input type="radio" id="no" name="feedback"  onclick="javascript:yesnoCheck();" value="0"></p></td>
				</tr>
			</table>
			</div>
			<p>Комментарий:</p>
			<div id="Comment"> 	
				<p><textarea rows="5" cols="45" id="text" name="comments"></textarea></p>
			</div>
			
			<p><div id="errors"></div></p>	
			<p><input type="submit" name="send" class="send" value="ВВОД"></p>
		</form>
		
		<script>
		$('form').submit(function(event){
			var year_cond =($("#yto").val()<$("#yfr").val());
			var year_cond_eq =($("#yto").val()===$("#yfr").val());
			var month_cond =($("#mfr").val()<$("#mto").val());
			if (year_cond){
				  $( "#errors" ).text( "ОШИБКА: Год ОКОНЧАНИЯ не может быть раньше года НАЧАЛА! " ).show().fadeOut( 8000 );
					event.preventDefault();
				return false;
			}
			else if (month_cond){
				$( "#errors" ).text( "ОШИБКА: Месяц ОКОНЧАНИЯ не может быть раньше месяца НАЧАЛА!" ).show().fadeOut( 8000 );
					event.preventDefault();
				return false;
			}
			
			var res=$.post(
					$(this).attr("action"),
					$(this).serialize(),
					void(0)
				).html();
				
				return;
		});	
		
		</script>

<?php			mysqli_free_result($answsql);
			mysqli_close($db_server);
?>
	</body>
</html>
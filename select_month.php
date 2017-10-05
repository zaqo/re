<?php
include ("login_re.php"); 
include ("header.php"); 
?>
<form id="month_form" method=post action="gen_invoices.php" >
<table><caption>Укажите месяц</caption>
		
		 	<tr><td colspan="3">
			<div id="date_m"><p> 	
				<select name="month" id="mfr" class="date" >
				<option value="" hidden> -=Выбрать=- </option>
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
			</td></tr>
			<tr><td colspan="3">
			<div id="year"><p> 	
				<select name="year" id="year" class="date" >
				<option value="" hidden> -=Выбрать=- </option>
				<option value="17">2017</option>
				<option value="18">2018</option>
				</select></p>
			</div>
			</td></tr>
			<tr><td></td><td>	
			<p><input type="submit" name="send" class="send" value="ВВОД"></p></td><td></td></tr>
		</table></form>
		
<?php
/*
			GENERAL INVOICE FORM
	MAKES AN INVOICE AND DISPATCH IT TO SAP ERP
	

*/
include ("login_re.php"); 
include ("header_tpl_doc.php"); 
//echo '<script src="/re/js/re_input.js"></script>';
//if(!$loggedin) echo "<script>window.location.replace('/Agents/login.php');</script>";

				
				//Connect to database
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
				
				//Prepare list of CONTRACTS
				$textsql='SELECT  id,number,comments FROM contract WHERE isValid=1 AND type="3"';
				$answsql=mysqli_query($db_server,$textsql);
				$num_of_cls=mysqli_num_rows($answsql);
				
				$cls_in=array();
				$contracts='';
					while ($cls_in= mysqli_fetch_row($answsql))  
					{
						$contracts.='<option value="'.$cls_in[0].'">'.$cls_in[1]." | ".$cls_in[2].'</option>';
					}
				$contracts='<select class="w3-input w3-border" name="contract" required><option value="" selected disabled> -- выберите контракт --- </option>'.$contracts.'</select>';
				
				//PREPARE LIST OF SERVICES
				
				$list_service='SELECT  id,description_rus FROM service WHERE isValid=1';
				$answsql1=mysqli_query($db_server,$list_service);
				//$num_of_cls=mysqli_num_rows($answsql);
				
				$svs_in=array();
				$svs_sel='';
					while ($svs_in= mysqli_fetch_row($answsql1))  
					{
						$svs_sel.='<option value="'.$svs_in[0].'">'.$svs_in[1].' | </option>';
					}
				$svs_sel='<select class="w3-input w3-border" id="services" name="svs[]" ><option value="" selected disabled> --- выберите --- </option>'.$svs_sel.'</select>';
				
				
				$year_sel='<select class=" w3-select w3-border id="year_sel" name="year" required>
								<option value="" class="w3-text-grey" selected disabled> --- год ---</option>
								<option value="17">2017</option>
								<option value="18">2018</option>
							</select>';
				$month_sel='<select class="w3-select w3-border" id="month_sel" name="month" required>
								<option value="" selected disabled> --- месяц ---</option>
								<option value="01" >Январь</option>
								<option value="02" >Февраль</option>
								<option value="03">Март</option>
								<option value="04">Апрель</option>
								<option value="05">Май</option>
								<option value="06">Июнь</option>
								<option value="07">Июль</option>
								<option value="08">Август</option>
								<option value="09">Сентябрь</option>
								<option value="10">Октябрь</option>
								<option value="11">Ноябрь</option>
								<option value="12">Декабрь</option>
							</select>';
			
	$content='
			<!-- !PAGE CONTENT! -->
			<div class="w3-main" style="margin-left:340px;margin-right:0px">

				<!-- Header -->
				<div class="w3-container" style="margin-top:10px" id="showcase">
					<h1 class="w3-jumbo w3-text-grey"><b>#СЧЕТ</b></h1>
					<h1 class="w3-xxxlarge w3-text-red"><b>регистрация</b></h1>	
				</div>';
	$val_block='';
	$c_num='';
	$period='';
	$enter_button='<button type="submit" id="send_b" class="w3-btn w3-white w3-padding-large w3-margin w3-hover-grey w3-border w3-border-red" value="">ВВОД</button>';
	$content.='	<div class="w3-card-4 w3-margin">
					<div class="w3-container  w3-grey">
							<h2> #PRO-FORMA </h2>
					</div>
					<div class="r_e" style="margin-top:30px;margin-right:0px">
						<form id="general_form" class="re_form " action="book_invoice_gen.php">
							<div class="w3-section ">
								<label class="w3-text-grey"><b>Контракт:</b></label>
								'.$contracts.'
							</div>
							 <div class="w3-row-padding">
							 <p><label class="w3-text-grey"><b>Период: </b></label></p>
								<div class="w3-half">
									<label class="w3-text-grey">Месяц</label>
									'.$month_sel.'
								</div>
								<div class="w3-half">
									<label class="w3-text-grey">Год</label>
									'.$year_sel.'
								</div>
							</div> 
							<!--ITEMS SECTION -->
							
							 <p><label class="w3-text-grey itm_label"><b>Услуги: </b></label></p>
							 <table class="w3-table itm_s">
							 <tr><td>
							 <div class="w3-cell-row itm_pop">
								<div class="w3-container w3-cell w3-half">
									<label class="w3-text-grey">Название</label>
									'.$svs_sel.'
								</div>
								<div class="w3-container w3-cell w3-quarter">
									<label class="w3-text-grey">Количество</label>
									<input type="number" name="qty[]"  class="w3-input digi" style="text-align:center; width:80%;" min="1" placeholder="1">
								</div>
								<div class="w3-container w3-cell w3-quarter">
									<button class="w3-button w3-circle w3-teal" style="margin-top:23px" onclick="addSection()">+</button>
								</div>
							 </div>
							</table>							
							<p>
								<div id="errors" class="w3-red w3-border-red"></div>
								<input hidden type="text" id="out_value" value="">
								'.$enter_button.'
								<button type="button" id="back" class="w3-btn w3-white w3-padding-large w3-margin w3-hover-grey w3-border w3-border-black" value="" onclick="history.go(-1)">НАЗАД</button>
							</p>
						</form>
					</div>
				</div>'; //CARD DIV
	
	//END OF THE MAIN CONTAINER			
	$content.='</div>';
		
		Show_page($content);
			//mysqli_free_result($answsql);
	mysqli_close($db_server);
?>

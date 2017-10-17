<?php 
	//CREATES FIRST PRO-FORMA INVOICE FOR THE GIVEN PERIOD

	include ("login_re.php"); 
	

				$id=$_REQUEST['id']; // contract id
				$val=$_REQUEST['value'];
				$month= $_REQUEST['month'];
				$year= $_REQUEST['year'];
				$inv_cur= $_REQUEST['cur'];
				$srv_id= $_REQUEST['service'];
				//var_dump($_REQUEST);
	
					$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
					$db_server->set_charset("utf8");
					If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
					mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
				
				// 1. Fill in Invoice data 
						$invoice_set='INSERT INTO invoice(month,year,date,currency,value,isValid,contract_id)
										VALUES ("'.$month.'","'.$year.'",CURDATE(),"'.$inv_cur.'","'.$val.'",1,'.$id.')';
				
						$answsql=mysqli_query($db_server,$invoice_set);
						
						if(!$answsql) die("Database INSERT invoice TABLE failed: ".mysqli_error($db_server));
						$ins_invoice_ref=$db_server->insert_id;
				
				// 2. Book service and quantity 	
						$invoice_reg_set='INSERT INTO invoice_reg(invoice_id,service_id,quantity,isValid)
										VALUES ('.$ins_invoice_ref.','.$srv_id.',1,1)';
				
						$answsql=mysqli_query($db_server,$invoice_reg_set);
						
						if(!$answsql) die("Database INSERT to invoice_reg TABLE  failed: ".mysqli_error($db_server));
						
						
					mysqli_close($db_server);
				
				echo '<script>window.close();</script>';
				
?>
		
		
		
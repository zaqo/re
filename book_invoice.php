<?php
include ("login_re.php"); 
				
				$contractid=$_REQUEST['id'];
				$date_d=$_REQUEST['date_d'];
				$date_m=$_REQUEST['date_m'];
				$date_y=$_REQUEST['date_y'];
				$clientid=$_REQUEST['client'];
				$facevalue=$_REQUEST['value'];
				$vat=$_REQUEST['vat'];
				$currency=$_REQUEST['currency'];
				$comments= $_REQUEST['comments'];
				$revenue= $_REQUEST['revenue'];
				$action=$_REQUEST['action'];
				$invoices_block=$_REQUEST['invoice'];
				
				$cur=$Currencies[$currency];
				$datetime = new DateTime();
				$datestr = $datetime->format('d-m-Y');
				var_dump($invoices_block);
	/*
				var_dump($contractid);
				var_dump($clientid);
				var_dump($date_d);
				var_dump($date_m);
				var_dump($date_y);
	**/
				
				if($action)
				{	
					$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
					$db_server->set_charset("utf8");
					If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
					mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
				
					
				// 1. Book invoice	
					if ($facevalue)
					{
						$invoicesql='INSERT INTO invoice(contract_id,date,decade,month,year,value,VAT,currency,comments,isValid)
						VALUES ("'.$contractid.'",CURDATE(),"'.$date_d.'","'.$date_m.'","'.$date_y.'","'.$facevalue.'",
						"'.$vat.'","'.$currency.'","'.$comments.'",1)';
				
						$answsql=mysqli_query($db_server,$invoicesql);
						
						if(!$answsql) die("Database invoice insert failed: ".mysqli_error($db_server));
						$ins_invoice_ref=$db_server->insert_id;
					}
					else
						echo "WARNING: zero value for invoice! <br>";
				
				
				// 2. Book revenues
					if ($revenue)
					{
						$revenuesql='INSERT INTO revenue(contract_id,date,decade,month,year,revenue,currency,isValid,invoice_id)
						VALUES ("'.$contractid.'",CURDATE(),"'.$date_d.'","'.$date_m.'","'.$date_y.'","'.$revenue.'",
						"'.$currency.'",1,"'.$ins_invoice_ref.'")';
				
						$answsql=mysqli_query($db_server,$revenuesql);
						$ins_revenue_ref=$db_server->insert_id;
						if(!$answsql) die("Database revenue insert failed: ".mysqli_error($db_server));
					}	
					else
						echo "WARNING: revenue is empty! <br>";
				// 3. Cancel the other invoices for this month
					if(isset($invoices_block))
					{
						foreach($invoices_block as $key => $val)
						{
						//1.Cleare the invoice
						
							$cancelinvoicesql="UPDATE invoice SET isValid=FALSE WHERE id=$val";
							$answsql=mysqli_query($db_server,$cancelinvoicesql);
							if(!$answsql) die("Database invoice update failed: ".mysqli_error($db_server));
						//echo "Canceled invoice # $value \n";
						
						//2. Clear revenue
						
							$cancelinrevenuesql="UPDATE revenue SET isValid=FALSE WHERE invoice_id=$val";
							$answsql=mysqli_query($db_server,$cancelinrevenuesql);
							if(!$answsql) die("Database invoice update failed: ".mysqli_error($db_server));
						
						//3. Clear contract
						
							$locateinvoicesql="SELECT * FROM contract_ledger WHERE contract_id=$contractid AND doc_id=$val";
							$answsql=mysqli_query($db_server,$locateinvoicesql);
						
							if(!$answsql) die("Database SELECT failed: ".mysqli_error($db_server));
							$num_ledger=mysqli_num_rows($answsql);
							$checkResL=array();
							if($num_ledger==0)
								echo "ERROR: Not capable to find the Invoice in the contract ledger \n"; 
							else
							{
								for($i=0;$i<$num_ledger;$i++)
								{
									$checkResL=mysqli_fetch_row($answsql);
									//var_dump($checkRes);// checking if we have found a contract
						
									$cid=$checkResL[1];
									$iid=$checkResL[2];
									$idate=$checkResL[4];
									$ival=-$checkResL[5];
									$idec=$checkResL[6];
									$imonth=$checkResL[7];
									$iyear=$checkResL[8];
									//Get current standing
									$checkresultsql="SELECT result FROM contract_ledger ORDER BY id DESC LIMIT 1";
									$answsql=mysqli_query($db_server,$checkresultsql);
									if(!$answsql) die("Contract Ledger SELECT failed: ".mysqli_error($db_server));
									$LastRes=mysqli_fetch_row($answsql);
									
									$resultnew=$LastRes[0]-$checkResL[5];
									$cancelincontractsql='INSERT INTO contract_ledger 
										(contract_id,doc_id,doc_type,date,value,decade,month,year,result) 
										VALUES
										("'.$cid.'","'.$iid.'",1,"'.$idate.'","'.$ival.'",
										"'.$idec.'","'.$imonth.'","'.$iyear.'","'.$resultnew.'")';
									$answsql=mysqli_query($db_server,$cancelincontractsql);
									if(!$answsql) die("Contract ledger STORNO invoice failed: ".mysqli_error($db_server));
								}	
							}
						}
					}
				// 4. Book in the contract ledger
					
					
					
					//a. Get the current status of result
					
					$queryresult = 'SELECT result FROM contract_ledger 
										WHERE contract_id ='.$contractid.'
										AND doc_type=1';
										
					$answsql=mysqli_query($db_server,$queryresult);
					$num=mysqli_num_rows($answsql);
					$checkRes=0;
					if($num==0)
						$noRecs=1; 
					else
					{
						for($i=0;$i<$num;$i++)
						 $checkRes=mysqli_fetch_row($answsql);
						//var_dump($checkRes);// checking if we have found a contract
						
					}
					echo "Current value of contract is: ".$checkRes[0]." number of records = ".$num."/n";
					
					//b. Book in the ledger 
					$result=$checkRes[0]+$facevalue;
					$ledgersql='INSERT INTO contract_ledger(contract_id,doc_id,doc_type,date,value,decade,month,year,result)
						VALUES ("'.$contractid.'","'.$ins_invoice_ref.'",1,CURDATE(),"'.$facevalue.'","'.$date_d.'","'.$date_m.'","'.$date_y.'","'.$result.'")';
					$answsql=mysqli_query($db_server,$ledgersql);
						//var_dump($answsql);
						if(!$answsql) die("Database invoice ledger booking failed: ".mysqli_error($db_server));
					
					mysqli_close($db_server);
				}
				echo '<script>history.go(-3);</script>';
		?>
		
		
		
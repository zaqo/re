<?php
/* THIS SCRIPT GENERATE INVOICES FOR ALL CONTRACTS DURING THE MONTH */
include ("login_re.php"); 
include ("header.php"); 
				
		if (isset($_REQUEST['month'])) $month=$_REQUEST['month'];
		if (isset($_REQUEST['year'])) $year=$_REQUEST['year'];
		$today = (int)date("m"); // only current month is of intertest
		//var_dump($month);
				
			$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
			$db_server->set_charset("utf8");
			if (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
			mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
				
					
			// 1. List of contracts	
				$contract_sql='SELECT contract.id,VAT,contract.currency,conditions.pay_terms,conditions.min_payment 
								FROM contract 
								LEFT JOIN conditions ON contract.id=conditions.contract_id AND conditions.isValid=1 
								WHERE CURDATE()<exp_date AND contract.isValid=1';
				$answsql=mysqli_query($db_server,$contract_sql);
				if(!$answsql) die("Database access to contract TABLE failed: ".mysqli_error($db_server));
				//var_dump($answsql);
				while($row=mysqli_fetch_row($answsql))
				{
					
					// 2. LIST INVOICES IN THE PERIOD FOR THIS CONTRACT	
					$contract_id=$row[0];
					$cur=$row[2]; //NOW it points to the contract. Although
					$VAT=$row[1];
					$pay_terms=$row[3];
					$min=$row[4];
					$invoice_sql="SELECT id,decade,value FROM invoice
								  WHERE contract_id=$contract_id 
								  AND month=$month
								  AND year=$year
								  AND isValid=1";
					$answsql_inv=mysqli_query($db_server,$invoice_sql);
					if(!$answsql_inv) die("Database access to contract TABLE failed: ".mysqli_error($db_server));
					// 3. GENERATE INVOICES FOR THIS PERIOD	
						// flags for existing invoices 
						$dec_I=0;
						$dec_II=0;
						$dec_III=0;
						$month_ind=0;
						$quarter_ind=0;
					// PROCESS ALL INVOICES IN THE SYSTEM	
					while($row_in=mysqli_fetch_row($answsql_inv))
					{
				
						$decade=$row_in[1];
						if ($pay_terms)
						{
							switch ($pay_terms)
							{
								case 1:  //decade
									if($decade==1) $dec_I=1;
									elseif($decade==2) $dec_II=1;
									elseif($decade==3) $dec_III=1;
									break;
								case 2:  //month
									$month_ind=1;
									break;
								case 3:  //quarter
									if(($today==3)||($today==6)||($today==9)||($today==12)) $quarter_ind=1;
									break;
								default:
									echo "WARNING! VALUE OF PAY_TERMS CONDITION IS OUT OF THE RANGE";
							}
						}
					
					}
					// BOOK INVOICES
					if ($pay_terms)
						{
							switch ($pay_terms)
							{
								case 1:  //decade
									if(!$dec_I) 
									{
									// BOOK INVOICE with min value
										$invoice_sql='INSERT INTO invoice(contract_id,date,decade,month,year,currency,VAT,value,isValid)
												VALUES ('.$contract_id.',CURDATE(),1,'.$month.','.$year.','.$cur.',
												'.$VAT.','.$min.',1)';
										$answsql_book=mysqli_query($db_server,$invoice_sql);
										if(!$answsql_book) die("Database error: INSERT into invoice table failed: ".mysqli_error($db_server));
									}
									if(!$dec_II) 
									{
									// BOOK INVOICE with min value
										$invoice_sql='INSERT INTO invoice(contract_id,date,decade,month,year,currency,VAT,value,isValid)
												VALUES ('.$contract_id.',CURDATE(),2,'.$month.','.$year.','.$cur.',
												'.$VAT.','.$min.',1)';
										$answsql_book=mysqli_query($db_server,$invoice_sql);
										if(!$answsql_book) die("Database error: INSERT into invoice table failed: ".mysqli_error($db_server));
									}
									if(!$dec_III) 
									{
									// BOOK INVOICE with min value
										$invoice_sql='INSERT INTO invoice(contract_id,date,decade,month,year,currency,VAT,value,isValid)
												VALUES ('.$contract_id.',CURDATE(),3,'.$month.','.$year.','.$cur.',
												'.$VAT.','.$min.',1)';
										$answsql_book=mysqli_query($db_server,$invoice_sql);
										if(!$answsql_book) die("Database error: INSERT into invoice table failed: ".mysqli_error($db_server));
									}
									break;
								case 2:  //month
									if(!$month_ind) 
									{
									// BOOK INVOICE with min value
										$invoice_sql='INSERT INTO invoice(contract_id,date,decade,month,year,currency,VAT,value,isValid)
												VALUES ('.$contract_id.',CURDATE(),0,'.$month.','.$year.','.$cur.',
												'.$VAT.','.$min.',1)';
										$answsql_book=mysqli_query($db_server,$invoice_sql);
										if(!$answsql_book) die("Database error: INSERT into invoice table failed: ".mysqli_error($db_server));
									}
									break;
								case 3:  //quarter
									if(!$quarter_ind) 
									{
									// BOOK INVOICE with min value
										$invoice_sql='INSERT INTO invoice(contract_id,date,decade,month,year,currency,VAT,value,isValid)
												VALUES ('.$contract_id.',CURDATE(),1,'.$month.','.$year.','.$cur.',
												'.$VAT.','.$min.',1)';
										$answsql_book=mysqli_query($db_server,$invoice_sql);
										if(!$answsql_book) die("Database error: INSERT into invoice table failed: ".mysqli_error($db_server));
									}
									break;
								default:
									echo "WARNING! VALUE OF PAY_TERMS CONDITION IS OUT OF THE RANGE";
							}
						}
				}
		//NOW LIST ALL THE INVOICES FOR THE MONTH		
			// 1. List of contracts	

			$content='<table class="fullTab"><caption>ПЕРЕЧЕНЬ СЧЕТОВ ЗА УКАЗАННЫЙ МЕСЯЦ</caption>';
			$content.='<tr><th>#</th><th>Контракт</th><th>Счет</th><th>декада</th><th>Месяц</th><th>Сумма</th></tr>';
			$counter=1;
			$contract_sql='SELECT contract.id,VAT,contract.currency,conditions.pay_terms,conditions.min_payment 
								FROM contract 
								LEFT JOIN conditions ON contract.id=conditions.contract_id AND conditions.isValid=1 
								WHERE CURDATE()<exp_date AND contract.isValid=1';
				$answsql_cnt=mysqli_query($db_server,$contract_sql);
				if(!$answsql_cnt) die("Database access to contract TABLE failed: ".mysqli_error($db_server));
				
				while($row=mysqli_fetch_row($answsql_cnt))
				{
					$contract_id=$row[0];
					$invoice_sql="SELECT id,VAT,value,decade,month 
								FROM invoice 
								WHERE contract_id=$contract_id AND month=$month";
					$answsql1=mysqli_query($db_server,$invoice_sql);
					if(!$answsql1) die("Database access to invoice TABLE failed: ".mysqli_error($db_server));
					while($row1=mysqli_fetch_row($answsql1))
					{
						$content.='<tr><td>'.$counter.'</td><td>'.$contract_id.'</td><td>'.$row1[0].'</td><td>'.$row1[3].'</td><td>'.$row1[4].'</td><td>'.$row1[2].'</td></tr>';
						$counter+=1;
					}
				}	
		$content.='</table>';
		Show_page($content);
		mysqli_close($db_server);
				
		//echo '<script>history.go(-3);</script>';
?>
		
		
		
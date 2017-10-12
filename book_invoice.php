<?php 
	//BOOKS INVOICE AND INITIATES SD ORDER

	include ("login_re.php"); 
	include_once ("functions.php"); 
	include("/webservice/sapconnector.php");
	class Item
	{
			public $ITM_NUMBER;						
			public $MATERIAL;
			public $TARGET_QTY;
			public $COND_TYPE;
			public $COND_VALUE;
			public $CURRENCY;
	}

	class ItemList
	{
			public $item;
	}
	class Request
	{
			public $BILLDATE;
			public $COND_VALUE;//decimal 19.4
			public $CURRENCY;
			public $ID_SALESCONTRACT;//char 10
			public $ID_SALESORDER_RUD;//char 10
			public $SALES_ITEMS_IN;
			public $SERVICEMODE; //char4
			public $RETURN2;
			public $BAPIRET2;
	}
	class Response
	{
			public $RETURN2;
			public $SD_DOC_LIST; //table of docs
	}
	class SD_DOC
	{
			public $ID_SALESCONTRACT;//char 10
			public $ID_SALESCONTRACTITM;//num 6
			public $SALESORDERBILLDATE;
			public $BILLDATE;
			public $REF_DOC;
			public $REF_DOC_ITEM;
			public $BILL_DOC;
			public $BILL_DOC_ITEM;
			public $NET_VALUE; //decimal 19.4
			public $TAX_VALUE; //type="tns:decimal 23.4"
			public $CURRENCY; //type="tns:cuky5 (char 5)
			public $CURRENCY_ISO;// tns:char3
			public $NET_VALUE_ITEM; // tns:decimal 23.4"
			public $TAX_VALUE_ITEM;//tns:decimal 23.4
			public $GRO_VALUE_ITEM;//tns:decimal 23.4
	}

				$id=$_REQUEST['id'];
				$val=$_REQUEST['invoice'];
				$revenue= $_REQUEST['revenue'];
				$datetime = new DateTime();
				$datestr = $datetime->format('d-m-Y');
				//var_dump($_REQUEST);
	
					$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
					$db_server->set_charset("utf8");
					If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
					mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
				
				// 1. Update Invoice data 
						$invoice_set='UPDATE invoice SET value="'.$val.'",isValid=1,isProcessed=1 WHERE id='.$id;
				
						$answsql=mysqli_query($db_server,$invoice_set);
						
						if(!$answsql) die("Database invoice update failed: ".mysqli_error($db_server));
						//$ins_invoice_ref=$db_server->insert_id;
					
				// 2. LOOK UP Invoice data 
						$invoice_get='SELECT invoice.date,invoice.decade,invoice.month,invoice.year,invoice.value,invoice.currency,
												contract.id_SAP
										FROM invoice 
										LEFT JOIN contract ON invoice.contract_id=contract.id
										WHERE invoice.id='.$id;
				
						$answsql=mysqli_query($db_server,$invoice_get);
						
						if(!$answsql) die("Database invoice update failed: ".mysqli_error($db_server));
						//$ins_invoice_ref=$db_server->insert_id;
						$row=mysqli_fetch_row($answsql);
						$inv_date=$row[0];
						$inv_dec=$row[1];
						$inv_month=$row[2];
						$inv_year=$row[3];
						$inv_val=$row[4];
						$inv_cur=$row[5];
						$c_id_SAP=$row[6];
						
				// 3. Book revenues
						// a.CHECK OUT IF WE HAVE A RECORD
						$find_rev='SELECT id FROM revenue 
									WHERE  invoice_id='.$id;
						$answsql=mysqli_query($db_server,$find_rev);
						if(!$answsql) die("Database ERROR: SELECT to revenue table failed: ".mysqli_error($db_server));
						$row_rev=mysqli_fetch_row($answsql);
						$rev_id=$row_rev[0];
						if(!$rev_id)
							$revenuesql='INSERT INTO revenue(revenue,currency,isValid,invoice_id)
										VALUES ("'.$revenue.'","'.$inv_cur.'",1,'.$id.')';
						else
							$revenuesql='UPDATE revenue 
										SET revenue="'.$revenue.'", currency="'.$inv_cur.'"
										WHERE invoice_id='.$id;
						$answsql=mysqli_query($db_server,$revenuesql);
						//$ins_revenue_ref=$db_server->insert_id;
						if(!$answsql) die("Database ERROR: INSERT to revenue table failed: ".mysqli_error($db_server));
					
				// 4. SEND TO SAP, get the SD order
				//   
				
				$res=SAP_set_order($id);
				if($res) // UPDATE INVOICE
				{
					$invoice_set='UPDATE invoice SET id_SD="'.$res.'"  WHERE id='.$id;
				
						$answsql=mysqli_query($db_server,$invoice_set);
						
						if(!$answsql) die("Database invoice update failed: ".mysqli_error($db_server));
				}
				
				// 5. Book in the contract ledger - NOW ITS DEFUNCT
					
/*				
				//a. Get the current status of result
					
					$queryresult = 'SELECT result FROM contract_ledger 
										WHERE contract_id ='.$contractid.'
										AND doc_type=1';
										
					$answsql=mysqli_query($db_server,$queryresult);
					//$num=mysqli_num_rows($answsql);
					$checkRes=0;
					if($num==0)
						$noRecs=1; 
					else
					{
						//for($i=0;$i<$num;$i++)
						 //$checkRes=mysqli_fetch_row($answsql);
						//var_dump($checkRes);// checking if we have found a contract
						
					}
					//echo "Current value of contract is: ".$checkRes[0]." number of records = ".$num."/n";
					
					//b. Book in the ledger 
					$result=$checkRes[0]+$facevalue;
					$ledgersql='INSERT INTO contract_ledger(contract_id,doc_id,doc_type,date,value,decade,month,year,result)
						VALUES ("'.$contractid.'","'.$ins_invoice_ref.'",1,CURDATE(),"'.$facevalue.'","'.$date_d.'","'.$date_m.'","'.$date_y.'","'.$result.'")';
					//$answsql=mysqli_query($db_server,$ledgersql);
						//var_dump($answsql);
						if(!$answsql) die("Database invoice ledger booking failed: ".mysqli_error($db_server));
	*/
					echo $res;
					mysqli_close($db_server);
				
				//echo '<script>history.go(-3);</script>';
				
?>
		
		
		
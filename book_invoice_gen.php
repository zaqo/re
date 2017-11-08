<?php 
	/*
			THIS IS FOR ALL KINDS OF SERVICES
		IT BOOKS INVOICE AND INITIATES SD ORDER
		it's a more advanced version of a create_invoice.php
		CURRENCY IS NOW FIXED IN RUR
	*/
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
			public $SERVICEDATE; //date10
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

				
				if (isset($_REQUEST['contract']))$id=$_REQUEST['contract'];
				if (isset($_REQUEST['month']))$month=$_REQUEST['month'];
				if (isset($_REQUEST['year']))$year=$_REQUEST['year'];
				if (isset($_REQUEST['svs']))$services=$_REQUEST['svs'];
				if (isset($_REQUEST['qty']))$quantity=$_REQUEST['qty'] ;
				
				//$srv_id= $_REQUEST['service'];
				$inv_cur=1; // NOW IT'S FIXED
				$datetime = new DateTime();
				$datestr = $datetime->format('d-m-Y');
				var_dump($_REQUEST);
	
					$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
					$db_server->set_charset("utf8");
					If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
					mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
				
				// 1.BOOK INVOICE
				$invoice_set='INSERT INTO invoice(month,year,date,currency,value,isValid,contract_id)
										VALUES ("'.$month.'","'.$year.'",CURDATE(),"'.$inv_cur.'","0",1,"'.$id.'")';
				//$echo=$invoice_set;
						$answsql=mysqli_query($db_server,$invoice_set);
						
						if(!$answsql) die("Database INSERT invoice TABLE failed: ".mysqli_error($db_server));
						$ins_invoice_ref=$db_server->insert_id;
				//echo $ins_invoice_ref.'<br/>';
				// 2. BOOK POSITIONS: services and quantity
					$srv_id='';
					$qty=0;
					foreach($services as $key=>$value)
					{
						$srv_id=$value;
						$qty=$quantity[$key];
						$invoice_reg_set='INSERT INTO invoice_reg(invoice_id,service_id,quantity,isValid)
										VALUES ("'.$ins_invoice_ref.'","'.$srv_id.'","'.$qty.'",1)';
						//echo $invoice_reg_set;
						$answsql=mysqli_query($db_server,$invoice_reg_set);
						
						if(!$answsql) die("Database INSERT to invoice_reg TABLE  failed: ".mysqli_error($db_server));
					}	
				
				// 4. SEND TO SAP, get the SD order
				//   
			
				$res=SAP_set_order_multy($ins_invoice_ref);
				if($res) // UPDATE INVOICE
				{
					$invoice_set='UPDATE invoice SET id_SD="'.$res.'"  WHERE id='.$ins_invoice_ref;
				
						$answsql=mysqli_query($db_server,$invoice_set);
						
						if(!$answsql) die("Database invoice update failed: ".mysqli_error($db_server));
				}
				
				
				
	mysqli_close($db_server);
				
				echo '<script>history.go(-1);</script>';
				
?>
		
		
		
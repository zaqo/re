<?php
// This script sends RE data to SAP and updates record
include ("login_re.php"); 
include("/webservice/sapconnector.php");
include ("functions.php");
set_time_limit(0);
include ("header.php"); 
//if(!$loggedin) echo "<script>window.location.replace('/Agents/login.php');</script>";
 
	
	$invoice= $_REQUEST['to_export'];

	//var_dump($flights);
	
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
	
		$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
		$db_server->set_charset("utf8");
		If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
		mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
			
				$textsql='';
				$content='<table id="ExportInvoice"><caption><b>Результаты</b></caption>
					<tr><th>СЧЕТ</th><th>ID документа в SAP ERP</th></tr>';
					
				foreach($invoice as $value)
				{
					$order=SAP_export_invoice($value);
					
					if($order)
					{
						$textsql="UPDATE invoice SET id_FI=$order, isSent_to_SAP=1  WHERE id=$value";
						
						$answsql=mysqli_query($db_server,$textsql);
						if(!$answsql) die("UPDATE of invoice table failed: ".mysqli_error($db_server));
					}
					$content.="<tr><td>$value</td><td>$order</td></th>";
				}
				$content.="</table>";
				Show_page($content);
				//echo '<script>history.go(-2);</script>';	
	
mysqli_close($db_server);
?>
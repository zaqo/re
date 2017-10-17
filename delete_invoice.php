<?php 
/* DELETES INVOICE BY ID LOCALLY AND IN SAP ERP*/
require_once 'login_re.php';
include_once ("functions.php"); 
	include("/webservice/sapconnector.php");
	class Item
	{
			public $ITM_NUMBER;						
			public $MATERIAL; //char 18, we use num 9
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
				
	
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
				$textsql='SELECT id_SD FROM invoice WHERE id='.$id;
				
				
				//echo $textsql."<br>";
				$answsql=mysqli_query($db_server,$textsql);
				if(!$answsql) die("Database SELECT from TABLE invoice failed: ".mysqli_error($db_server));
				
				$row=mysqli_fetch_row($answsql);
				$id_SAP=$row[0];
				if($id_SAP)
				{
					//delete record in SAP ERP
					$res=SAP_delete_order($id_SAP);
					//var_dump($res);
				}
				//AND disable in invoice TABLE
				$updatesql='UPDATE invoice SET isValid = 0 WHERE id='.$id;
				$answsql1=mysqli_query($db_server,$updatesql);
				if(!$answsql1) die("Database UPDATE failed: ".mysqli_error($db_server));
				echo '<script>history.go(-1);</script>';	
	
				mysqli_close($db_server);
?>
	
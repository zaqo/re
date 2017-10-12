<?php 

	include ("header.php"); 
	include_once("login_re.php");
	include_once ("functions.php"); 

	include("/webservice/sapconnector.php");
	//ini_set("soap.wsdl_cache_enabled", "0");	
	
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
	
	
	$content='';
	
	
	
	//$result=SAP_connector($req);
	$res=SAP_set_order(85);
	//if ($res) echo "Invoice exported to SAP ERP successfully! <br/>";
	//else echo "ERROR: export operation aborted! <br/>";
	
		echo '<pre>';
			var_dump($res);
		echo '</pre>';
	
	echo '<hr><br>';
	$pos=array('10'=>'111');
	$res_read=SAP_update_order($res,$pos);
	/*
	echo '<pre>';
	var_dump($res_read);//echo $res_read->item[0]->MESSAGE;
	echo '</pre>';
	*/
	//Show_page($content);
?>
	
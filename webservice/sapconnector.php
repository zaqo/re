<?php
function SAP_connector($params)
{

	include("login_re.php");
	ini_set("soap.wsdl_cache_enabled", "0");
	set_time_limit(0);
	$locale = 'ru';
	
	$client = new SoapClient($wsdlurl, array('login'=> $SAP_username,
											'password'=> $SAP_password,
											'trace'=>1)
							); 

	 // Формирование заголовков SOAP-запроса
	$client->__setSoapHeaders(
	array(
		new SoapHeader('API', 'user', $SAP_username, false),
		new SoapHeader('API', 'password', $SAP_password, false)
		)
	);


	// Выполнение запроса к серверу SAP ERP
	try
	{
		//$result = $client->ZsdOrderAviCrud($params);
		$result = $client->Z_SD_ORDER_ZNAK_CRUD($params);
	}
	catch(SoapFault $fault)
	{
	// <xmp> tag displays xml output in html
		echo 'Request : <br/><pre><xmp>',
		$client->__getLastRequest(),
		'</xmp></pre><br/><br/> Error Message : <br/>',
		$fault->getMessage();
	} 
	
	//обработчик ответа
	
	//echo '<pre>';
	//	var_dump($result);
	//echo '</pre>';
	
	// I DECIDED TO KEEP IT OUTSIDE
	//$order=SAP_response_handler($result); 
	//$output=$result->RETURN2->item->MESSAGE_V4;
	
	// Вывод запроса и ответа
	//echo "Запрос:<pre>".htmlspecialchars($client->__getLastRequest()) ."</pre>";
	//echo "Ответ:<pre>".htmlspecialchars($client->__getLastResponse())."</pre>";
	
	// Вывод отладочной информации в случае возникновения ошибки
	if (is_soap_fault($result)) 
	{ 
		echo("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring}, detail: {$result->detail})"); 
	}

	return $result;
}

function SAP_response_handler($response)
{
// UNCOMMENT HERE TO VISUALISE THE RESPONSE

	$content='';
	// Building up the message content
	
	echo '<table><tr><th>PARAMETER</th><th>VALUE</th></tr>';
	$result=$response->RETURN2->item;
	/*
	foreach($response->RETURN2->item as $result)
	{
		
	//$result=$Return2->Return2->item[2]->Type;
			$message=$result->MESSAGE;
			$type=$result->TYPE;
			$id=$result->ID;
			$doc_id=$result->MESSAGE_V4;
			$number=$result->NUMBER;
			$system=$result->SYSTEM;
	
		echo "<tr><td colspan=\"2\" ><hr color=\"black\" ></td></tr>";		
		if ($result->TYPE=='E')
		{
			echo "<tr><td>RESULT:</td><td>ERROR</td></tr>";	
		
			echo "<tr><td>Message:</td><td>$message</td></tr>";
			echo "<tr><td>Number #:</td><td>$number</td></tr>";
			echo "<tr><td>Document #:</td><td>$doc_id</td></tr>";
			echo "<tr><td>Type #:</td><td>$type</td></tr>";
			
		}
		else
		{
			echo "<tr><td>RESULT:</td><td>Ok</td></tr>";
			echo "<tr><td>Message:</td><td>$message</td></tr>";
			echo "<tr><td>Number #:</td><td>$number</td></tr>";
			echo "<tr><td>Document #:</td><td>$doc_id</td></tr>";
			echo "<tr><td>Type #:</td><td>$type</td></tr>";
		}
	/*}
	//echo '</table>';*/
	return $doc_id;
}


/*
		PLUG INVOICE TO SAP ERP
 */

function SAP_set_order($rec_id)
{
/*
	This FUNCTION posts SD order 
	INPUT: id of invoice
	OUTPUT:
			- "ID" of SD order
			OR "0" - if failed
*/
	include("login_re.php");
	ini_set("soap.wsdl_cache_enabled", "0");
	
	//THESE PARAMS ARE FIXED NOW
	$cond_type='ZPR0';	
	//$cond_value='10';
	$service_mode='SO_C';	// CREATE	
	$req = new Request();
	 
			//Setting up the object
			$item= new Item();
		
		//Set up mySQL connection
			$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
			$db_server->set_charset("utf8");
			If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
			mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
	//1.	
		//  LOCATE data for the invoice
			$invoice_sql="SELECT invoice.date,invoice.value,contract.id_SAP,currency.code 
							FROM  invoice 
							LEFT JOIN contract ON invoice.contract_id=contract.id 
                            LEFT JOIN currency ON invoice.currency=currency.id
							WHERE invoice.id=$rec_id";
				
			$answsql=mysqli_query($db_server,$invoice_sql);
				
			if(!$answsql) die("Database SELECT TO invoice table failed: ".mysqli_error($db_server));	
			if (!$answsql->num_rows)
			{
				echo "WARNING: No invoice found for a given ID in invoice TABLE <br/>";
				return 0;
			}	
			$i_data= mysqli_fetch_row($answsql);
				
			
	//2.	
			// Prepare request for SAP ERPclass Item
	
			// Set up params
			
			$c_date=$i_data[0];
			$val=$i_data[1];
			$contract_id=$i_data[2];
			$curr=$i_data[3];	// Currency in invoice
			
			// Preparing Items for Invoice
			$count_in=1;// only one position by Invoice now
			$items=new ItemList();
			for($it=0;$it<$count_in;$it++)
			{	
				$item1 = new Item();
				// 1. Item number
				$item_num=($it+1).'0';
				$item1->ITM_NUMBER=$item_num;
			
				// 2. Material code
				$item1->MATERIAL='901200000';//now it's fixed
			
			/*2.1  BLOCK LEFT FOR LOCATING SAP MATERIAL ID
			
				$servicesql='SELECT id_SAP,id FROM services WHERE id_NAV="'.$service_id.'"';	
				$answsql=mysqli_query($db_server,$servicesql);	
				if(!$answsql) die("Database SELECT in services table failed: ".mysqli_error($db_server));	

				$sap_service_id= mysqli_fetch_row($answsql);
			*/
				// 3. Currency
				$item1->CURRENCY=$curr;
				
				// 4. SD conditions
				$item1->COND_TYPE=$cond_type;
				$item1->COND_VALUE=$val;
				
				// 4. Quantity
				$item1->TARGET_QTY='1'; //FIXED!
				
			
			//Inserting into Item List
			
				$items->item[$it] = $item1;
			}
			
		// GENERAL SECTION (HEADER)
		
			$req->ID_SALESCONTRACT = $contract_id;	
			$req->SERVICEMODE = $service_mode; 		
			$req->BILLDATE=$c_date;
			$req->SALES_ITEMS_IN=$items;
			$req->RETURN2 = '';
			
			$order=SAP_connector($req);
			if ($order)
				$doc_id=$order->RETURN2->item->MESSAGE_V4;
	mysqli_close($db_server);
	return $doc_id;
}			//END OF SAP_export_invoice

function SAP_update_order($rec_id, $positions)
{
// Updates sales order values
// INPUTS:
// 		$rec_id - order ID 
//		$position  - Array of 'position number' -> 'value'
// RETURN:
// 1 - Ok
// 0 - if failed
	include("login_re.php");
	ini_set("soap.wsdl_cache_enabled", "0");
	
	//THESE PARAMS ARE FIXED NOW
	$cond_type='ZPR0';	
	
	$service_mode='SO_U';	// UPDATE
	$curr='RUB';			// FIXED
	$items=new ItemList();
		//Setting up the object
		foreach($positions as $key=>$value )
		{
			$item= new Item();
			$item->ITM_NUMBER=$key;
			//$item->MATERIAL='901200000';//now it's omitted
			//$item->CURRENCY=$curr; //also omitted
		//$item1->TARGET_QTY='1'; //also omitted
			$item->COND_TYPE=$cond_type;
			$item->COND_VALUE=$value;	
			$items->item[] = $item;
		}	
	
			// Prepare request for SAP ERPclass Item
	
			$req = new Request();
			
		
		// GENERAL SECTION (HEADER)
		
			$req->ID_SALESORDER_RUD = $rec_id;	
			$req->SERVICEMODE = $service_mode; 		
			
			$req->SALES_ITEMS_IN=$items;
			$req->RETURN2 = '';
			
			$order=SAP_connector($req);
			echo '<pre>';
			var_dump($order);
			echo '</pre>';
			//if ($order)
			//	$doc_id=$order->RETURN2->item->MESSAGE_V4;
	mysqli_close($db_server);
	return 1;
}			//END OF SAP_update_order

function SAP_get_order($rec_id)
{
// pulls information about SD order from SAP ERP
// INPUT: 
// order ID form ERP 
// OUTPUT:
// object SD_DOC 
// OR 0 - if failed

	include("login_re.php");
	ini_set("soap.wsdl_cache_enabled", "0");
	
	$service_mode='SO_R';	// READ	
	
			// Prepare request 
	
			$req = new Request();
			
		// GENERAL SECTION (HEADER)
		
			$req->ID_SALESORDER_RUD = $rec_id;	
			$req->SERVICEMODE = $service_mode; 		
		
			$req->RETURN2 = '';
			$req->SD_DOC_LIST = '';
			$response=SAP_connector($req);
		if($response) 
			$doc=$response->SD_DOC_LIST;
	return $doc;
}			//END OF SAP_get_order

function SAP_delete_order($rec_id)
{
// delete SD order in SAP ERP
// INPUT: 
// order ID form ERP 
// OUTPUT:
// 1 - Ok
// 0 - if failed

	include("login_re.php");
	ini_set("soap.wsdl_cache_enabled", "0");
	
	$service_mode='SO_D';	// READ	
	
			// Prepare request 
	
			$req = new Request();
			
		// GENERAL SECTION (HEADER)
		
			$req->ID_SALESORDER_RUD = $rec_id;	
			$req->SERVICEMODE = $service_mode; 		
		
			$req->RETURN2 = '';
			$req->SD_DOC_LIST = '';
			$response=SAP_connector($req);
			
		if($response->RETURN2->item->ID=='ZWS') 
			return 1;
		else 
			return 0;
}			//END OF SAP_delete_order

function SAP_get_contract($rec_id)
{
//pulls information about SD contract from SAP ERP
// INPUT: 
//contract ID form ERP 
//OUTPUT:
// object SD_DOC 
//OR 0 - if failed

	include("login_re.php");
	ini_set("soap.wsdl_cache_enabled", "0");
	
	$service_mode='SO_R';	// READ	
	
			// Prepare request 
	
			$req = new Request();
			
		// GENERAL SECTION (HEADER)
		
			$req->ID_SALESCONTRACT = $rec_id;	
			$req->SERVICEMODE = $service_mode; 		
		
			$req->RETURN2 = '';
			$req->SD_DOC_LIST = '';
			$response=SAP_connector($req);
			if($response) 
				$doc=$response->SD_DOC_LIST;
	return $doc;
}			//END OF SAP_get_contract

//MULTY POSITION INVOICE SETTER
function SAP_set_order_multy($rec_id)
{
/*
	This FUNCTION posts SD order 
	INPUT: id of invoice
	OUTPUT:
			- "ID" of SD order
			OR "0" - if failed
*/
	include("login_re.php");
	ini_set("soap.wsdl_cache_enabled", "0");
	
	//THESE PARAMS ARE FIXED NOW
	$cond_type='ZPR0';	
	
	$service_mode='SO_C';	// CREATE	
	$req = new Request();
	 
			//Setting up the object
			$item= new Item();
		
		//Set up mySQL connection
			$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
			$db_server->set_charset("utf8");
			If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
			mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
	//1.	
		//  LOCATE data for the invoice
			$invoice_sql="SELECT invoice.date,invoice.value,contract.id_SAP,currency.code 
							FROM  invoice 
							LEFT JOIN contract ON invoice.contract_id=contract.id 
                            LEFT JOIN currency ON invoice.currency=currency.id
							WHERE invoice.id=$rec_id";
				
			$answsql=mysqli_query($db_server,$invoice_sql);
				
			if(!$answsql) die("Database SELECT TO invoice table failed: ".mysqli_error($db_server));	
			if (!$answsql->num_rows)
			{
				echo "WARNING: No invoice found for a given ID in invoice TABLE <br/>";
				return 0;
			}	
			$i_data= mysqli_fetch_row($answsql);
				
			
	//2.	
			// Prepare request for SAP ERPclass Item
	
			// Set up params
			
			$c_date=$i_data[0];
			$val=$i_data[1];
			$contract_id=$i_data[2];
			$curr=$i_data[3];	// Currency in invoice
			
			// Preparing Items for Invoice
			
			//  LOCATE POSITIONS for the invoice
			$positions_sql="SELECT service_id,quantity,service.id_SAP 
							FROM  invoice_reg 
							LEFT JOIN service ON invoice_reg.service_id=service.id
							WHERE invoice_id=$rec_id";
				
			$answsql1=mysqli_query($db_server,$positions_sql);
				
			if(!$answsql1) die("Database SELECT TO invoice_reg table failed: ".mysqli_error($db_server));	
			$count_in=$answsql1->num_rows;
			if (!$count_in)
			{
				echo "WARNING: No POSITIONS found for a given ID in invoice_reg TABLE <br/>";
				return 0;
			}	
			while($pos_data= mysqli_fetch_row($answsql1))
			{
				// only one position by Invoice now
				$items=new ItemList();
				for($it=0;$it<$count_in;$it++)
				{	
					$item1 = new Item();
					// 1. Item number
					$item_num=($it+1).'0';
					$item1->ITM_NUMBER=$item_num;
			
					// 2. Material code
					$item1->MATERIAL=$pos_data[2];
			
				
				// 3. Currency ?? - need it?
					$item1->CURRENCY=$curr;
				
					// 4. SD conditions
					$item1->COND_TYPE=$cond_type;
					$item1->COND_VALUE=$val;
				
					// 4. Quantity
					$item1->TARGET_QTY=$pos_data[1];; 
				
			
					//Inserting into Item List
			
					$items->item[$it] = $item1;
				}
			}
		// GENERAL SECTION (HEADER)
		
			$req->ID_SALESCONTRACT = $contract_id;	
			$req->SERVICEMODE = $service_mode; 		
			$req->BILLDATE=$c_date;
			$req->SALES_ITEMS_IN=$items;
			$req->RETURN2 = '';
			
			$order=SAP_connector($req);
			if ($order)
				$doc_id=$order->RETURN2->item->MESSAGE_V4;
	mysqli_close($db_server);
	return $doc_id;
}			//END OF SAP_export_invoice


?>
<?php
include ("login_re.php"); 
include ("header.php"); 
//if(!$loggedin) echo "<script>window.location.replace('/Agents/login.php');</script>";

	 $content='<table>
		<caption>Выберите арендатора</caption>
		<form id="form" method="post" action="list_contracts.php" >
			<tr><td><b>Компания:</b></td></tr>
			<tr><td><div id="client">';

				
				//Connect to database
				$db_server = mysqli_connect($db_hostname, $db_username,$db_password);
				$db_server->set_charset("utf8");
				If (!$db_server) die("Can not connect to a database!!".mysqli_connect_error($db_server));
				mysqli_select_db($db_server,$db_database)or die(mysqli_error($db_server));
		
				//Prepare list of cliens
				$textsql='SELECT  id,name FROM client WHERE isValid=1 ORDER BY name';
				$answsql=mysqli_query($db_server,$textsql);
				$num_of_cls=mysqli_num_rows($answsql);
				$i=0;
				$cls_in=array();
				$cl_string='';
					for ($i=0;$i<$num_of_cls;$i++)  
					{
						$cls_in[$i]= mysqli_fetch_row($answsql);
						$cl_string=$cl_string.'<option value="'.($cls_in[$i][0]).'">'.($cls_in[$i][1]).'</option>';
					}
				$cl_string='<select class="client" id="client" name="client"><option value=""></option>'.$cl_string.'</select>';
				$content.=$cl_string;
			$content.='</div></td></tr>
			<tr><td><p><input type="submit" name="send" class="send" value="ВВОД"></p></td></tr>
		</form></table>';
		Show_page($content);
			mysqli_free_result($answsql);
			mysqli_close($db_server);
?>

//дожидаемся полной загрузки страницы
window.onload = function () {

    //получаем идентификатор элемента
    var in_ = $('.r_e');
    var in_data = in_.find('.input_row');
	var in_display = in_.find('.display');
	var show_result = in_.find('#inv_val');
	var out_result = in_.find('#out_value');
	var in_min = in_.find('#min');
	var in_pct = in_.find('#pct');
	var in_cur = in_.find('#currency');
	var in_type = in_.find('#type');
	// get button
	var in_button = in_.find('.re_button');
	var out_button = in_.find('#send');
	var back_button = in_.find('#back');
    //вешаем на него событие
    in_button.on('click',function(){
		var revenue=in_display.val();
		var min=in_min.val();
		var pct=in_pct.val();
		var cur=in_cur.val();
		var type=in_type.val();
		var result;
		
		switch(type){
			case '1':
				result=pct*revenue;
				break;
			case '2':
				result=Math.max(pct*revenue,min);
				break;
			default:
				alert('WARNING! UNSUPPORTED SCHEME TYPE');
		}
		// Finding it in DOM and updating
		$('#out_value').attr({value: result});
		
		var formatter = new Intl.NumberFormat('en');
		
		show_result.html('<p style="margin-left:60px"><b>'+formatter.format(result)+' '+cur+'</b></p>');
		
	});
	out_button.on('click', function(){
		var rev=$('.r_e').find('.display').val();
		var res=$('.r_e').find('#out_value').val();
		var inv_id=$('.r_e').find('#invoice_id').val();
		var out_div=$('.r_e').find('#returned');
		
		$.post( "book_invoice.php", { id: inv_id, invoice: res, revenue: rev })
		  .done(function( data ) {
			
			
			var x = document.getElementsByTagName("button")[0];
			var y = document.getElementsByTagName("button")[1];
			
				x.style.display = "none";
				//y.style.display = "none";
				//document.getElementById("input_row").disabled = true;
			if (data)
				out_div.html('<span class="w3-opacity"><h2> Заказ №: '+data.toString()+'</h2></span>');
			else
				$('#errors').html('<span class="w3-opacity"><h2> Ошибка при передаче в SAP ERP: '+data.toString()+'</h2></span>');
			
		}).fail(function( data ) {    
					var response = data.responseText;
					var parser = new DOMParser();
					//var errors=document.getElementById("#errors");
					xmlDoc = parser.parseFromString(response,"text/xml");
					//
					var resp_1=xmlDoc.getElementsByTagName("title")[0].childNodes[0].nodeValue;
					var resp_2=xmlDoc.getElementsByTagName("h2")[0].childNodes[0].nodeValue;
					//var title=$(response).find("title");
					$('#errors').html(' <p><b>'+resp_1+': '+resp_2+'</b></p>');
				});
	});
	back_button.on('click', function(){
		history.go(-1);
	});
}
function listAllProperties(o) {//gives all properties of Object, can do it also by Object.keys
	var objectToInspect;     
	var result = [];
	
	for(objectToInspect = o; objectToInspect !== null; objectToInspect = Object.getPrototypeOf(objectToInspect)) {  
      result = result.concat(Object.getOwnPropertyNames(objectToInspect));  
	}
	
	return result; 
}
function openTub(e,tubID) {
    var i,x,tablinks;

    x = document.getElementsByClassName("tabs");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
	
	tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < x.length; i++) {
       tablinks[i].className = tablinks[i].className.replace(" w3-red", "");
    }
    document.getElementById(tubID).style.display = "block";
	e.currentTarget.className += " w3-red";
}
function createInv(but_id,div_id) {

   var x,y;
   
   x = document.getElementsByTagName("button")[2];
	y = document.getElementsByClassName("hid_form")[0];
			
				x.style.display = "none";
				y.style.display = "block";
}

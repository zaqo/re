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
	// get button
	var in_button = in_.find('.re_button');
	var out_button = in_.find('#send');
    //вешаем на него событие
    in_button.on('click',function(){
		var revenue=in_display.val();
		var min=in_min.val();
		var pct=in_pct.val();
		var cur=in_cur.val();
		var result=Math.max(pct*revenue,min);
		// Finding it in DOM and updating
		$('#out_value').attr({value: result});
		
		var formatter = new Intl.NumberFormat('en');
		
		show_result.html('<p style="margin-left:60px"><b>'+formatter.format(result)+' '+cur+'</b></p>');
		
	});
	out_button.on('click', function(){
		var rev=$('.r_e').find('.display').val();
		var res=$('.r_e').find('#out_value').val();
		var inv_id=$('.r_e').find('#invoice_id').val();
		var done_sap='';
		//alert(res);
		$.post( "book_invoice.php", { id: inv_id, invoice: res, revenue: rev })
		  .done(function( data ) {
			$('.r_e').empty();
			$('.r_e').html('<h1>'+data.toString()+'</h1>');
			
		});
		//alert(done_sap);
	});
	
}
function listAllProperties(o) {
	var objectToInspect;     
	var result = [];
	
	for(objectToInspect = o; objectToInspect !== null; objectToInspect = Object.getPrototypeOf(objectToInspect)) {  
      result = result.concat(Object.getOwnPropertyNames(objectToInspect));  
	}
	
	return result; 
}
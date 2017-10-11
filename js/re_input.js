//дожидаемся полной загрузки страницы
window.onload = function () {

    //получаем идентификатор элемента
    var in_ = $('.r_e');
    var in_data = in_.find('.input_row');
	var in_display = in_.find('.display');
	var in_result = in_.find('#inv_val');
	var in_min = in_.find('#min');
	var in_pct = in_.find('#pct');
	var in_cur = in_.find('#currency');
	// get button
	var in_button = in_.find('.re_button');
    //вешаем на него событие
    in_button.on('click',function(){
		var revenue=in_display.val();
		var min=in_min.val();
		var pct=in_pct.val();
		var cur=in_cur.val();
		var result=Math.max(pct*revenue,min);
		in_result.text(result+' '+cur);
	});
}
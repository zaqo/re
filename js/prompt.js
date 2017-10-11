//дожидаемся полной загрузки страницы
window.onload = function () {

    //получаем идентификатор элемента
    var a = document.getElementById('calc');
    var r = document.getElementById('revenue');
	var b = document.querySelectorAll('.link');//Это когда не по иду ищем элемент
	var attrs = r.attributes;
    //вешаем на него событие
    a.onclick = function() {
        //производим какие-то действия
        var revenue;//=prompt('Введите оборот!');
		var flag=1;
		var re= /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
		alert (attrs["min"].value);
		/*
		while (flag)
		{
			
			revenue=prompt('Введите оборот!');
			if(isEmpty(revenue)) alert("Поле пустое");
			else if (re.test(revenue))
			{
				alert("Ввод правильный");
				flag=0;
			}
			else alert("Ошибка данных");
			
			if (this.innerHTML=='On') this.innerHTML = 'Off';
			else this.innerHTML = 'On';
        //предотвращаем переход по ссылке href
        }*/
		/*
		var jqxhr =$.post( "do_invoice.php", { id: "83", revenue: revenue })
			.done(function( data ) {
			alert( "Data Loaded: " + data );
		});*/
		
		return revenue;
    }
}
function isNumber() {
    var str = document.getElementById("rev").value;
    var status = document.getElementById("status");
    var re = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;
    if (re.test(str)) status.innerHTML = "Адрес правильный";
      else status.innerHTML = "Адрес неверный";
    if(isEmpty(str)) status.innerHTML = "Поле пустое";
   }
   function isEmpty(str){
    return (str == null) || (str.length == 0);
   }
function addMyField () {
			var telnum = parseInt($('#add_field_area').find('div.add:last').attr('id').slice(3))+1;//увеличиваем значение счетчика
			alert("Number is" + telnum+"!");
			var $content=$("select#val1").html();//grab the dropdown 
			//and draw a new row
			$('div#add_field_area').find('div.add:last').append('<div id="row'+telnum+'"><hr><tr colspan="6"><div id="add'+telnum+'" class="add"><label> №'+telnum+
			'</label><select name="val'+telnum+'" id="val" onblur="writeFieldsValues();" >'+$content+
			'</select></div></tr><tr><div class="deletebutton" onclick="deleteField('+telnum+');"></div></tr></div>');
		}
		
		function deleteField (id) {
			$('div#row'+id).remove();
		}

		function addsomeField () {
			//var telnum = parseInt($("#add_field_area").find("div.add:last").attr("id").slice(3))+1;//увеличиваем значение счетчика
			var i=1;
			var flag=1
			while(flag) 
			{
				flag=($("#who"+i).attr("size"));
				i++;
			}
			telnum=i-1;
			//var content=$("select#val1").html();//grab the dropdown 
			//we don't need a dropdown any longer. now we just plug in input
			
			//and draw a new row
			$("#myTab").append('<tr><div id="add'+telnum+'"><td><input type="text" name="val[]" class="livesearch_input" id="who'+telnum+'" size="10" value="" onkeyup="showResult(this.value,'+telnum+')"><ul id="livesearch'+telnum+'" class="search_result"></ul></td>'
			+'<td><select name="to_all[]" id="all" class="services" ><option value=1>Да</option><option value=0>Нет</option></select></td><td><input type="text" value="" name="including[]" placeholder="1,2,3"/></td><td><input type="text" value="" name="excluding[]" placeholder="1,2,3"/></td></div></tr>');
		}
		function addRow () {
			//var telnum = parseInt($("#add_field_area").find("div.add:last").attr("id").slice(3))+1;//увеличиваем значение счетчика
			
			 //var content=$("select#val1").html();//grasp the dropdown 
			//and draw a new row
			
			//tbody.appendChild(row)
			$("div#add_field_area").find("#myTab").append('<tr></td><td><select name="val'+telnum+'" id="val" onblur="writeFieldsValues();" >'+content+
			'</select></td><td><input type="checkbox" name="Servicedata[]" value="all"/></td><td><input type="text" value="" name="including" placeholder="1,2,3"/></td><td id="'+telnum+
			'"><input type="text" value="" name="including" placeholder="1,2,3"/></td></tr>');
		}
		
		function writeFieldsValues () {
			var str = [];
			var tel = '';
			for(var i = 0; i<$("select#val").length; i++) {
			tel = $($("select#val")[i]).val();
				if (tel !== '') {
					str.push($($("input#values")[i]).val());
				}
			}
			$("input#values").val(str.join("|"));
		}
		function checkIt () {
			
			var value=$("#flights").attr("checked");
			if(value=='checked')
			$("input:checkbox").removeAttr("checked");
			else
			$("input:checkbox").attr("checked","checked");
		}

		//AJAX SEARCH FIELD
		
	function showResult(str) {
			//var telnum = parseInt($("#add_field_area").find("div.add:last").attr("id").slice(3));
			
			if (str.length==0) {
						document.getElementById("livesearch").innerHTML="";
						document.getElementById("livesearch").style.border="0px";
						return;
			}
			if (window.XMLHttpRequest) {
						// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp=new XMLHttpRequest();
			} else {  // code for IE6, IE5
						xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() {
				if (this.readyState==4 && this.status==200) {
						//document.getElementById("livesearch").innerHTML=this.responseText;
						//document.getElementById("livesearch").style.border="1px solid #A5ACB2";
						$("#livesearch").html(this.responseText).fadeIn();
						
				}
			}
		xmlhttp.open("GET","livesearch_flight.php?lead="+str,true);
		xmlhttp.send();
		$("#livesearch").hover(function(){
			$("#who").blur(); //Убираем фокус с input
		})
    
//При выборе результата поиска, прячем список и заносим выбранный результат в input
		$("#livesearch").on("click", "li", function(){
			var s_user = $(this).text();
			$("#who").val(s_user);//.attr('disabled', 'disabled'); //деактивируем input, если нужно
			$("#livesearch").fadeOut();
		})
	}
	function fill()
	{
		var num='xxx';
		var x=$('#livesearch_input');
		alert(num);
		//$('#ajax_subfield').empty().hide();
		x.attr({value:num});
		x.append(num);
		//document.getElementById("livesearch_input").value=num;
		document.getElementsByClassName("ajax_subfield")[0].style.display='none';
	}
//for invoice_form.php
function addSection () {
			//var telnum = parseInt($("#add_field_area").find("div.add:last").attr("id").slice(3))+1;//увеличиваем значение счетчика
		
			 var content=$(".itm_pop").html();//grasp the section
			//and draw a new row
			
			//tbody.appendChild(row)
			$(".itm_s").append('<tr><td>'+content+'</td></tr>');
		}
function addSecNew (i) {
			//var telnum = parseInt($("#add_field_area").find("div.add:last").attr("id").slice(3))+1;//увеличиваем значение счетчика
		
			 var content=$(".services").html();//grasp the section
			content='<select class="w3-input w3-border services" id="" name="svs[]" required>'+content+'</select>';
			//alert(content);
			//and draw a new row
			var first="<div class='w3-cell-row itm_pop'><div class='w3-container w3-cell w3-half'><label class='w3-text-grey'>Название</label>";
			var del_button="<button class='w3-button w3-circle w3-red' style='margin-top:23px' onclick='removeSection("+i+")'>-</button>";
			var second="</div><div class='w3-container w3-cell w3-quarter'><label class='w3-text-grey'>Количество</label><input type='number' name='qty[]'  class='w3-input digi' style='text-align:center; width:80%;' min='1' placeholder='1' required></div><div class='w3-container w3-cell w3-quarter'><button class='w3-button w3-circle w3-teal' style='margin-top:23px' onclick='addSecNew("+(i+1)+")'>+</button>"+del_button+"</div></div>";
			//tbody.appendChild(row)
			$(".itm_s >tbody:last-child").append('<div id="'+i+'"><tr><td>'+first+content+second+'</td></tr></div>');
		}
	function removeSection (i) {
			$(".itm_s").find('#'+i).remove();//IT WORKS! :
			//$(".itm_s").find("tbody:last-child").remove();
		}
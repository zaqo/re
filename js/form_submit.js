//Form validation function
		$("form").submit(function(event){
			//var year_cond =($("#yto").val()<$("#yfr").val());
			//var year_cond_eq =($("#yto").val()===$("#yfr").val());
			//var month_cond =($("#mfr").val()<$("#mto").val());
			var decade=$("#decade").val();
			var month=$("#month").val();
			var text_mask = "/^[0-9]$/";
			var text=$("#revenue").val();
			var text_cond =text_mask.test(text);
			if (text_cond){
				  $( "#errors" ).text( "ОШИБКА: в тексте обнаружены запрещенные для ввода символы! " ).show().fadeOut( 8000 );
					event.preventDefault();
				return false;
			}
			
			if (month===""){
				  $( "#errors" ).text( "ОШИБКА: Укажите месяц! " ).show().fadeOut( 8000 );
					event.preventDefault();
				return false;
			}
			
			var res=$.post(
					$(this).attr("action"),
					$(this).serialize(),
					void(0)
				).html();
				
				return;
		});	
	
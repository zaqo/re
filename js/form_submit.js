//Form validation function
		$("form").submit(function(event){
			//var year_cond =($("#yto").val()<$("#yfr").val());
			//var year_cond_eq =($("#yto").val()===$("#yfr").val());
			//var month_cond =($("#mfr").val()<$("#mto").val());
			var in_ = $('.r_e');
			var in_data = in_.find('.input_row');
			var in_display = in_.find('.display');
			var show_result = in_.find('#inv_val');
			var out_result = in_.find('#out_value').val();
			var in_min = in_.find('#min');
			var in_pct = in_.find('#pct');
			var in_cur = in_.find('#currency');
			//var decade=$("#decade").val();
			//var month=$("#month").val();
			var text_mask = "/^[0-9]$/";
			//var text=$("#revenue").val();
			//var text_cond =text_mask.test(out_result);
			alert('CATCH!');
			if (text_cond){
				  $( "#errors" ).text( "ОШИБКА: обнаружены запрещенные для ввода символы! " ).show().fadeOut( 8000 );
					event.preventDefault();
				return false;
			}
			
			if (out_result===""){
				  $( "#errors" ).text( "ОШИБКА: Укажите сумму! " ).show().fadeOut( 8000 );
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
	
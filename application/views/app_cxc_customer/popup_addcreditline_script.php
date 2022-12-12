<script >
	$(document).ready(function(){
			$('.txt-numeric').mask('000,000.00', {reverse: true});
			
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data					= {};
					data.txtCreditLineID		= $("#txtCreditLineID").val();
					data.txtCreditLineIDDesc 	= $("#txtCreditLineID option:selected").text();
					
					data.txtCurrencyID			= $("#txtCurrencyID").val();
					data.txtCurrencyIDDesc 		= $("#txtCurrencyID option:selected").text();
					
					data.txtStatusID			= $("#txtStatusID").val();
					data.txtStatusIDDesc 		= $("#txtStatusID option:selected").text();
					
					data.txtNumber				= $("#txtNumber").val();
					data.txtLimitCredit			= $("#txtLimitCredit").val();
					data.txtInteresYear			= $("#txtInteresYear").val();
					
					data.txtPeriodPay			= $("#txtPeriodPay").val();
					data.txtPeriodPayDesc 		= $("#txtPeriodPay option:selected").text();
					
					data.txtTypeAmortization	= $("#txtTypeAmorization").val();
					data.txtTypeAmortizationDesc= $("#txtTypeAmorization option:selected").text();
					
					data.txtTerm				= $("#txtTerm").val();
					data.txtNote				= $("#txtNote").val();
					
					var tmpFrecuenciaPago			= $("#txtPeriodPay option:selected").data("val") + 0;
					var tmpPlazo					= $("#txtTerm").val() + 0;
					
				
					window.opener.parentNewLine(data);  
					window.close(); 
			});
	});
</script>
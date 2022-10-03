function selectMenu(current_url){
	
	current_url		=	current_url.split("/")[0] + "/" + current_url.split("/")[1] + "/" + current_url.split("/")[2] + "/" + current_url.split("/")[3]  + "/" + current_url.split("/")[4] + "/index.aspx" ;	
	
	//Validar si es un menu superior
	if($("a[href='"+current_url+"']").parent().parent().parent().parent().hasClass("nav navbar-nav pull-right"))
		return;
		
	$("a[href='"+current_url+"']").closest("li").addClass("current")
	$("a[href='"+current_url+"']").closest("li").closest("ul").addClass("expand");
	$("a[href='"+current_url+"']").closest("li").closest("ul").addClass("show");
	$("a[href='"+current_url+"']").closest("li").closest("ul").closest("li").addClass("current");
	$("a[href='"+current_url+"']").closest("li").closest("ul").closest("li")
	$("a[href='"+current_url+"']").closest("li").closest("ul").closest("li").find("a").first().removeClass("notExpand");
	$("a[href='"+current_url+"']").closest("li").closest("ul").closest("li").find("a").first().addClass("expand");
	
}

function fnTableSelectedRow(obj,event){ 	
	$(event.target.parentElement.parentElement).find("tr").removeClass('row-selected');
	$(event.target.parentNode).addClass('row-selected');		
}
function fnShowMessageSuccess(message){ 
		var _success = ""+
		'<div class="alert alert-success" style="margin-top:15px">'+
			'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
			'<strong><i class="icon24 i-checkmark-circle"></i> Success!</strong>'+		
				message + 			
		'</div>';
		
		$("#main_content").prepend(_success);
}
function fnShowMessageError(message){
		var _error = ""+
		'<div class="alert alert-error" style="margin-top:15px">'+
			'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
			'<strong><i class="icon24 i-close-4"></i> Error!</strong>'+	 	
				message +			
		'</div>';
		$("#main_content").prepend(_error);
}
function fnShowNotification(message,type,time){
	 setTimeout(function() {
    	$.jGrowl("<i class='icon16 i-info'></i>" + message, 
		{
    		group			: type,    		
			position		: 'bottom-left',
    		sticky			: false,
			life 			: time,
    		closeTemplate	: '<i class="icon16 i-close-2"></i>',
    		animateOpen		: {
		        width		: 'show',
		        height		: 'show'
		    },
			beforeOpen: function(e,m,o){
					$(e).width( "550px" ).height("50px");
			}
    	});
    }, 250);	
}
function fnShowConfirm(title_,body_,callback_){
	var template =  '<div>'+
					'<div class="modal fade" id="myModalConfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
					'	<div class="modal-dialog">'+
					'	  <div class="modal-content">'+
					'		<div class="modal-header">'+
					'		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
					'		  <h4 class="modal-title">${title}</h4>'+
					'		</div>'+
					'		<div class="modal-body">'+
					'			${body}'+
					'		</div>'+
					'		<div class="modal-footer">'+
					'		  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
					'		  <button type="button" id="btnAceptMyModalConfirm" class="btn btn-primary">Aceptar</button>'+
					'		</div>'+
					'	  </div>'+
					'	</div>'+
					'</div>'+
					'</div>';
					
	$("#myModalConfirm").remove();
	var result = $.tmpl(template,{title:title_,body:body_}).html();	
	$("#main_content").prepend(result);
	$("#myModalConfirm").modal();
	$("#btnAceptMyModalConfirm").click(function(){		
		$("#myModalConfirm").modal('hide');
		callback_.call();
	}); 
}
function fnShowPrompt(title_,label_,callback_){
	var template =  '<div>'+
					'<div class="modal fade" id="myModalPrompt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
					'	<div class="modal-dialog">'+
					'	  <div class="modal-content">'+
					'		<div class="modal-header">'+
					'		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
					'		  <h4 class="modal-title">${title}</h4>'+
					'		</div>'+
					'		<div class="modal-body">'+
					'				<form id="myModalPromptForm" class="form-horizontal" role="form">'+
					'						<fieldset>			'+
					'							<div class="form-group">'+
					'									<label class="col-lg-2 control-label" for="normal">${label}</label>'+
					'									<div class="col-lg-10">'+
					'										<input class="form-control"  type="text" name="txtMyModalPromptForm" id="txtMyModalPromptForm" value="">'+
					'									</div>'+
					'							</div>'+
					'						</fieldset>'+
					'				</form>'+
					'		</div>'+
					'		<div class="modal-footer">'+
					'		  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
					'		  <button type="button" id="btnAceptMyModalPrompt" class="btn btn-primary"  >Aceptar</button>'+
					'		</div>'+
					'	  </div>'+
					'	</div>'+
					'</div>'+
					'</div>';
					
	$("#myModalPrompt").remove();
	var result = $.tmpl(template,{title:title_,label:label_}).html();	
	$("#main_content").prepend(result);
	$("#myModalPrompt").modal();
	$("#btnAceptMyModalPrompt").click(function(){
		var result = $("#txtMyModalPromptForm").val();	
		$("#myModalPrompt").modal('hide');
		callback_.call(this,result);
	}); 
}
function fnWaitOpen(){
	$("body").isLoading(isLoadingConfig);
}
function fnWaitClose(){
	$("body").isLoading('hide');
}
function fnFormatFloat(number){
	var flo = parseFloat(number.replace(",","").replace(",","").replace(",","").replace(",","").replace(",",""));
	return flo;
}
function fnFormatNumber(number,decimal){
	//Validar Decimales
	if(parseFloat(decimal) == NaN)
	decimal 	= 0;
	
	var dec_ 	= "1";
	var posfix 	= "";
	var prefix 	= "";
	for(var i=0 ; i< decimal ; i++) dec_ = dec_ + "0";
	dec_ 		= parseInt(dec_);
	number 		= parseFloat(number);
	
	//Validar Numero
	if(number == NaN)
	return 0;
	
	number 	= parseInt(Math.round(number * dec_)) / dec_ ;
	number 	= number + "";
	
	
	if(number.indexOf(".") == -1 ){
		posfix = "";
		prefix = number;
	}
	else{
		posfix = number.substr(number.indexOf(".")+1,number.length);
		prefix = number.substr(0,number.indexOf("."));
	}
	if(posfix.length != decimal){
	for(var i=posfix.length ; i< decimal ; i++) posfix = posfix + "0";
	}
	
	number = prefix + "."  + posfix;
	return number;
}

function fnRecalcularTableSummary(table,targetInput,parameter1 , parameter2, operation){
		var cantidad = table.fnGetData().length;
		var total = 0;
		for(var i=0; i<cantidad;i++){
			var objdat_ = table.fnGetData(i);
			var valueParameter1 = fnFormatFloat(objdat_[parameter1]);
			var valueParameter2 = 0;
			var result 			= 0;
			
			if(parameter2 != null)
			valueParameter2 = fnFormatFloat(objdat_[parameter2]);
			else 
			valueParameter2 = 0;
		
		
			if(operation == "+"){
				result = valueParameter1 + valueParameter2; 
			}
			if(operation == "*"){
				result = valueParameter1 * valueParameter2; 
			}
			if(operation == "-"){
				result = valueParameter1 - valueParameter2; 
			}
			if(operation == "/"){
				result = valueParameter1 / valueParameter2; 
			}
			
			
			total = total + result;			
			
		}
		total = fnFormatNumber(total,2);
		$(targetInput).val(total);
		
	}
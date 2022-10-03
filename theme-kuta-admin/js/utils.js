function fnTableGetDataRowSelected( idTable ){
	var aReturn 	= new Array();
	var aTrs 		= $('#'+idTable).dataTable().fnGetNodes(); 
					 
	for ( var i=0;i<aTrs.length ;i++ ) 
	{
		if ( $(aTrs[i]).hasClass('row-selected') )
		{
			aReturn.push( aTrs[i] );
		}
	}
	return aReturn;
}
function fnTableSelectedRow(idTable){ 
	$('#'+idTable+' tbody').click(function(event) { 
		$($('#'+idTable).dataTable().fnSettings().aoData).each(function (){
			$(this.nTr).removeClass('row-selected');
		}); 
		$(event.target.parentNode).addClass('row-selected');
	});
}
(function($) {
    $.fn.getAttributes = function() {
        var attributes = {}; 

        if( this.length ) {
            $.each( this[0].attributes, function( index, attr ) {
                attributes[ attr.name ] = attr.value;
            } ); 
        }

        return attributes;
    };
})(jQuery); 
function fnShowMessageSuccess(message){ 
		var _success = ""+
		"<div class='c-alert pillow-emboss' style='z-index: 1;position: absolute;left: 20%;width: 50%;'>"+
			"<div class='alert-message info'>"+
			"<a class='close' href='#'><i class='icon-large icon-remove-circle'></i></a>"+
			"<h4 class='alert-heading'>INFORME</h4>"+
				message +
			"</div>"+
		"</div>";
		
		$("#main_content").prepend(_success);
}
function fnShowMessageError(message){
		var _error = ""+
		"<div class='c-alert pillow-emboss' style='z-index: 1;position: absolute;left: 20%;width: 50%;'>"+
			"<div class='alert-message error'>"+
			"<a class='close' href='#'><i class='icon-large icon-remove-circle'></i></a>"+
			"<h4 class='alert-heading'>INFORME</h4>"+
				message +
			"</div>"+
		"</div>";
		$("#main_content").prepend(_error);
}
function fn_showLoader(){
	$("#loaderImage").css("display","block");
}
function fn_hiddenLoader(){
	$("#loaderImage").css("display","none");
}
$(document).ready(function(){
	$('.alert-message a.close').live('click', function(){
			$(this).parent().parent('.c-alert').slideUp('slow');
	});	
	
	$( ".chzn-select-deselect" ).each(function(){
		$(this).chosen({allow_single_deselect:true});
	});
});
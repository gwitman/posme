<script>
	$(document).ready(function(){	
		
		$(document).on("click","#btnPrinter",function(){
			
			var listaValores 				= objTableListView.fnGetData();	
			var listeProductos 				= "0-0";
			$("#ListView input").each(function(i,o) {
				if($( this ).val() == 0 || $( this ).val() == "")
				{}
				else{
					//console.info($( this ).val());
					//console.info($( this ).data("codigo"));
					listeProductos = listeProductos + "|"+ $(this).data("itemid")+ "-" + $( this ).val();
				}
  				
			});

			if(listeProductos == "0"){
				fnShowNotification("Seleccionar el Registro...","error");
				return;
			}
			console.info(listeProductos);
			var url = "<?php echo site_url(); ?>app_inventory_itemmasive/popup_add_prinercode/listItem/"+listeProductos;
			window.open(url, "_blank");								

			/*
			var listaValoresSeleccionados 	= jLinq.from(listaValores).where(function(obj){ 
				console.info(obj); return obj[7] == "Si"; }).select();
			*/	
			
			/*
			if(listaValoresSeleccionados.length > 0){			
				var listeProductos = "0";
				listaValoresSeleccionados.forEach(function(o,a){ 
					listeProductos = listeProductos + "|"+ o[1];
				});
				
				console.info(listeProductos);				
			}
			*/
		}); 
		$(document).on("click","#ListView tbody tr",function(a,i){
			
			
			//var objind_ = objTableListView.fnGetPosition(this);			
			//var objdat_ = objTableListView.fnGetData(objind_);	
			//var newValue = "";
			//if(objdat_[6] == "No"){
			//	newValue = "Si";
			//	$(this).addClass("success");
			//}
			//else{
			//	newValue = "No";
			//	$(this).removeClass("success");
			//	
			//}
			
			//objTableListView.fnUpdate(newValue, objind_, 6 );
			
			
		
		});
		
	});
	
				
</script>



<!--posMeHelp-->
<script>
  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','744100086ID'); 
</script>

<script>
	//window.userGuiding.identify(userId*, attributes)
	  
	// example with attributes
	window.userGuiding.identify('<?php echo get_cookie("email"); ?>', {
	  email: '<?php echo get_cookie("email"); ?>',
	  name: '<?php echo get_cookie("email"); ?>',
	  created_at: 1644403436643,
	});

	// or just send userId without attributes
	//window.userGuiding.identify('1Ax69i57j0j69i60l4')
</script>

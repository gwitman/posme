<!-- ./ page heading -->
<script>					
	
	$(document).ready(function(){					
		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){
				fnWaitOpen();
				$( "#form-new-account-type" ).attr("method","POST");
				$( "#form-new-account-type" ).attr("action","<?php echo site_url(); ?>app_inventory_category/save/new");
				$( "#form-new-account-type" ).submit();
		});
		
	});
	
</script>
<script>  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','744100086ID'); </script>
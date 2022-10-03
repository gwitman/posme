$(document).ready(function() {

	//------------- Elfinder file manager  -------------//
    var elf = $('#elfinder').elfinder({	
		lang: 'es',		
		url : 'http://localhost/erp/core_elfinder/load_elfinder'  // connector URL (REQUIRED)
	}).elfinder('instance');


	//-------------  Plupload uploader -------------//
	$("#uploader").pluploadQueue({
		// General settings
		runtimes 		: 'html5,html4', 
		url 			: 'http://localhost/erp/core_elfinder/upload_elfinder',
		max_file_size 	: '10mb',
		max_file_count	: 15, // user can add no more then 15 files at a time
		chunk_size 		: '1mb',
		unique_names 	: true,
		multiple_queues : true,
		// Resize images on clientside if we can
		resize 			: {width : 320, height : 240, quality : 80},
		
		// Rename files by clicking on their titles
		rename			: true,
		
		// Sort files
		sortable		: true,

		// Specify what files to browse for
		filters 		: [
			{title : "Imagenes", extensions : "jpg,jpeg,gif,png"},
			{title : "Documentos", extensions : "pdf,docx,txt"}
			/*{title : "Zip files", extensions : "zip,avi"}*/
		]
	});
	 	
});
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>APPNS system</title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="berp" /> [
]		<!-- CSS
		================================================== 
		-->	
		
		<link href='<?php echo site_url(); ?>theme-genyx/css/family_open_sans_400_800_700.css' rel='stylesheet' type='text/css'>		
		<link href='<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_400_700.css' rel='stylesheet' type='text/css' />[
]		 <!--[if lt IE 9]>
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_400.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_700.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_800.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_400.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_700.css" rel="stylesheet" type="text/css" />
		<![endif]-->[
]		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/upload/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" rel="stylesheet" />
		<link href="<?php echo site_url(); ?>theme-genyx/js/jquery-version/jquery-ui-1.8.18.css" rel="stylesheet" />
		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/upload/elfinder/css/elfinder.min.css" rel="stylesheet" /> 		
		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/upload/elfinder/css/theme.css" rel="stylesheet" /> 		
		
		 
		<!-- javascript
		================================================== 
		-->			
		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery-version/jquery-1.7.2.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery-version/jquery-ui-1.8.18.min.js"></script>	
		<script src="<?php echo site_url(); ?>theme-genyx/js/bootstrap/bootstrap.js"></script>  		
		<script src="<?php echo site_url(); ?>theme-genyx/js/conditionizr.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/core/nicescroll/jquery.nicescroll.min.js"></script>		
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/core/jrespond/jRespond.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery.genyxAdmin.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/uniform/jquery.uniform.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/upload/elfinder/elfinder.min.js"></script>		
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/upload/plupload/plupload.full.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/upload/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
		
		<script>
			$(document).ready(function() {
				//------------- Elfinder file manager  -------------//
				var elf = $('#elfinder').elfinder({	
					lang: 'es',		
					url : '<?php echo site_url(); ?>core_elfinder/load_elfinder/componentID/<?php echo $componentID; ?>/componentItemID/<?php echo $componentItemID; ?>'  // connector URL (REQUIRED)
				}).elfinder('instance');[
]				//-------------  Plupload uploader -------------//
				$("#uploader").pluploadQueue({
					// General settings
					runtimes 		: 'html5,html4', 
					url 			: '<?php echo site_url(); ?>core_elfinder/upload_elfinder/componentID/<?php echo $componentID; ?>/componentItemID/<?php echo $componentItemID; ?>',
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
					sortable		: true,[
]					// Specify what files to browse for
					filters 		: [
						{title : "Imagenes", extensions : "jpg,jpeg,gif,png"},
						{title : "Documentos", extensions : "pdf,docx,txt"}						
					]
				});
					
			});
		</script>
		
	</head>
	<body> 
		<div class="main">	
				<div class="wrapper">
					<div class="container-fluid" id="main_content">
							<div class="row">
								<div class="col-lg-12">
									<div class="panel panel-default">										
										<div class="panel-body noPadding">
											<div id="elfinder"></div>
										</div><!-- End .panel-body -->
									</div><!-- End .widget -->    
								</div><!-- End .col-lg-12  --> 
							</div><!-- End .row-fluid  -->							
							<div class="row">
								<div class="col-lg-12">
									<div class="panel panel-default">
										<div class="panel-body noPadding">
											<form class="form-horizontal">
												<div id="uploader" style="width: 100%; height: 100%;">You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support</div>
											</form>
										</div><!-- End .panel-body -->
									</div><!-- End .widget -->    
								</div><!-- End .col-lg-12  --> 
							</div> 
					</div> <!-- End .container-fluid  -->
				</div> <!-- End .wrapper  -->			
		</div><!-- End .main  -->
	</body>	
</html>
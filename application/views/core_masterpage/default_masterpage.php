<!--vista principal del sistema-->

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>posMe</title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 

		<!-- CSS
		================================================== 
		-->	
		
		<link href='<?php echo site_url(); ?>theme-genyx/css/family_open_sans_400_800_700.css' rel='stylesheet' type='text/css'>		
		<link href='<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_400_700.css' rel='stylesheet' type='text/css' />

		 <!--[if lt IE 9]>
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_400.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_700.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_800.css"  rel="stylesheet" type="text/css" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_400.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_700.css" rel="stylesheet" type="text/css" />
		<![endif]-->

		
		<link href="<?php echo site_url(); ?>theme-genyx/css/bootstrap/bootstrap.css" 														rel="stylesheet" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/bootstrap/bootstrap-theme.css" 												rel="stylesheet" />
		<link href="<?php echo site_url(); ?>theme-genyx/css/icons.css" 																	rel="stylesheet" />		
		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/uniform/uniform.default.css" 										rel="stylesheet" /> 
		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/tables/datatables/jquery.dataTables.css" 								rel="stylesheet" />
		
		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/switch/bootstrapSwitch.css" 										rel="stylesheet" /> 
		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/select2/select2.css" 												rel="stylesheet" />
		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/ui/jgrowl/jquery.jgrowl.css" 											rel="stylesheet" /> 
		<link href="<?php echo site_url(); ?>theme-genyx/css/genyx-theme/jquery.ui.progressbar.css" rel="stylesheet" /> 		
		<link href="<?php echo site_url(); ?>theme-genyx/css/genyx-theme/jquery.ui.genyx.css" rel="stylesheet" />
		<link href="<?php echo site_url(); ?>/js/is-loading-master/style.css" rel="stylesheet" /> 
		<link href="<?php echo site_url(); ?>theme-genyx/css/app.css" 																		rel="stylesheet" /> 
		<link href="<?php echo site_url(); ?>theme-genyx/css/custom.css" 																	rel="stylesheet" /> 
		<link href="<?php echo site_url(); ?>/css/genyx-app.css" 																			rel="stylesheet" /> 
		
		

		<!--[if IE 8]>
		<link href="<?php echo site_url(); ?>theme-genyx/css/ie8.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />		
		<!--[if lt IE 9]>
		  <script src="<?php echo site_url(); ?>theme-genyx/js/html5shiv.js"></script>
		  <script src="<?php echo site_url(); ?>theme-genyx/js/respond.min.js"></script>
		<![endif]-->

		
		<link rel="apple-touch-icon-precomposed" sizes="144x144" 	href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" 	href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" 		href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" 					href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" 									href="<?php echo site_url(); ?>theme-genyx/images/ico/favicon.ico">

		<!-- javascript
		================================================== 
		-->		
		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery-1-9-1.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery-migrate-1.2.1.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery-ui-1-10-2.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/bootstrap/bootstrap.js"></script>  
		<script src="<?php echo site_url(); ?>theme-genyx/js/conditionizr.min.js"></script>  
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/core/nicescroll/jquery.nicescroll.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/core/jrespond/jRespond.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery.genyxAdmin.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/ui/animated-progress-bar/jquery.progressbar.js"></script>
		
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/switch/bootstrapSwitch.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery.tmpl.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/ui/jgrowl/jquery.jgrowl.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/uniform/jquery.uniform.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/tables/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/select2/select2.js"></script> 		
		<script src="<?php echo site_url(); ?>theme-genyx/js/app.js"></script>
		<script src="<?php echo site_url(); ?>theme-genyx/js/pages/domready.js"></script>		
		
		<script src="<?php echo site_url(); ?>js/genyx-utilis.js"></script>  
		<script src="<?php echo site_url(); ?>js/genyx-fn.js"></script>  
		<script src="<?php echo site_url(); ?>js/genyx-app-init.js"></script>
		<script src="<?php echo site_url(); ?>js/is-loading-master/jquery.isloading.min.js"></script>  
		
		
	</head>
	<body> 
		
		<header id="header">
		
		
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <a class="navbar-brand" href="#" style="color:#fff"><?php echo $company->name; ?></a>
            
            <button type="button" class="navbar-toggle btn-danger" data-toggle="collapse" data-target="#navbar-to-collapse">
                <span class="sr-only">Toggle Derecho Menu</span>
                <i class="icon16 i-arrow-8"></i>
            </button>          
            <div class="collapse navbar-collapse" id="navbar-to-collapse">				
                <ul class="nav navbar-nav pull-right">
					<?php echo $notification;?>
										
					<?php echo $menuRenderTop;?> 
                </ul>
            </div><!--/.nav-collapse -->
        </nav>
		</header> <!-- End #header  -->
		
		<div class="main">
			<aside id="sidebar">
				<div class="side-options">
					<ul>
						<li><a href="#" id="collapse-nav" class="act act-primary tip" title="Collapse navigation"><i class="icon16 i-arrow-left-7"></i></a></li>
					</ul>
				</div>

				<div class="sidebar-wrapper">
					<nav id="mainnav">
						<ul class="nav nav-list">							
							<?php echo $menuRenderLeft;?>
						</ul>
					</nav> 
				</div> 
			</aside>

			<section id="content">
				<div class="wrapper">
					<div class="crumb">
						<ul class="breadcrumb">
						  <li><a href="#"><i class="icon16 i-quill-3"></i>...</a></li>						  
						  <li><a href="#"><i class="icon16 "></i>...</a></li>						  
						</ul>
					</div>
					
					<div class="container-fluid" id="main_content"> 										
							<?php echo $message; ?>
				
							<?php echo $head; ?>
							
							<?php echo $body;?>
							
							<?php echo $footer; ?>

					</div> <!-- End .container-fluid  -->
				</div> <!-- End .wrapper  -->
			</section>
		</div><!-- End .main  -->

		
	</body>	
	<script>
	selectMenu("<?php echo current_url(); ?>");
	</script>
	<?php echo $script;?>
	
	
</html>
<!--vista que se esta utilizando para que el usuario ponga los filtros en los reportes-->
<!--muestra unicamente los filtros y una barra con el boton de imprimir-->


<!DOCTYPE html>

<html lang="en">

	<head>

		<meta charset="utf-8">

		<title>APPNS system</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		

		

		<!-- Headings -->

		<link href='<?php echo site_url(); ?>theme-genyx/css/family_open_sans_400_800_700.css' rel='stylesheet' type='text/css'>

		<!-- Text -->

		<link href='<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_400_700.css' rel='stylesheet' type='text/css' />



		<!--[if lt IE 9]>

		<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_400.css"  rel="stylesheet" type="text/css" />

		<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_700.css"  rel="stylesheet" type="text/css" />

		<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_800.css"  rel="stylesheet" type="text/css" />

		<link href="<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_400.css" rel="stylesheet" type="text/css" />

		<link href="<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_700.css" rel="stylesheet" type="text/css" />

		<![endif]-->



		<!-- Core stylesheets do not remove -->

		<link href="<?php echo site_url(); ?>theme-genyx/css/bootstrap/bootstrap.css" 		rel="stylesheet" />

		<link href="<?php echo site_url(); ?>theme-genyx/css/bootstrap/bootstrap-theme.css" rel="stylesheet" />

		<link href="<?php echo site_url(); ?>theme-genyx/css/icons.css" 					rel="stylesheet" />



		<!-- Plugins stylesheets -->

		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/uniform/uniform.default.css" rel="stylesheet" /> 

		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/tables/datatables/jquery.dataTables.css" rel="stylesheet" />

		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/select2/select2.css" rel="stylesheet" />

		<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/ui/jgrowl/jquery.jgrowl.css" rel="stylesheet" /> 

		<link href="<?php echo site_url(); ?>theme-genyx/css/genyx-theme/jquery.ui.progressbar.css" rel="stylesheet" /> 

		

		

		

		<!-- app stylesheets -->

		<link href="<?php echo site_url(); ?>theme-genyx/css/app.css" rel="stylesheet" /> 



		<!-- Custom stylesheets ( Put your own changes here ) -->

		<link href="<?php echo site_url(); ?>theme-genyx/css/custom.css" rel="stylesheet" /> 

		<link href="<?php echo site_url(); ?>/css/genyx-app.css" rel="stylesheet" /> 



		

		<!--[if IE 8]>

		<link href="<?php echo site_url(); ?>theme-genyx/css/ie8.css" rel="stylesheet" type="text/css" />

		<![endif]-->

		

		<meta http-equiv="X-UA-Compatible" content="IE=edge" />		

		<!--[if lt IE 9]>

		  <script src="<?php echo site_url(); ?>theme-genyx/js/html5shiv.js"></script>

		  <script src="<?php echo site_url(); ?>theme-genyx/js/respond.min.js"></script>

		<![endif]-->



		<!-- Fav and touch icons -->

		<link rel="apple-touch-icon-precomposed" sizes="144x144" 	href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-144-precomposed.png">

		<link rel="apple-touch-icon-precomposed" sizes="114x114" 	href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-114-precomposed.png">

		<link rel="apple-touch-icon-precomposed" sizes="72x72" 		href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-72-precomposed.png">

		<link rel="apple-touch-icon-precomposed" 					href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-57-precomposed.png">

		<link rel="shortcut icon" 									href="<?php echo site_url(); ?>theme-genyx/images/ico/favicon.ico">



		<!-- Le javascript

		================================================== -->

		<!-- Important plugins put in all pages -->

		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery-1-9-1.min.js"></script>

		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery-ui-1-10-2.min.js"></script>

		<script src="<?php echo site_url(); ?>theme-genyx/js/bootstrap/bootstrap.js"></script>  

		<script src="<?php echo site_url(); ?>theme-genyx/js/conditionizr.min.js"></script>  

		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/core/nicescroll/jquery.nicescroll.min.js"></script>

		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/core/jrespond/jRespond.min.js"></script>

		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery.genyxAdmin.js"></script>

		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/ui/animated-progress-bar/jquery.progressbar.js"></script>

		<script src="<?php echo site_url(); ?>theme-genyx/js/jquery.tmpl.js"></script>



		<!-- Form plugins --> 

		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/ui/jgrowl/jquery.jgrowl.min.js"></script>

		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/uniform/jquery.uniform.min.js"></script>

		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/tables/datatables/jquery.dataTables.min.js"></script>

		<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/select2/select2.js"></script> 



		<!-- Init plugins -->

		<script src="<?php echo site_url(); ?>theme-genyx/js/app.js"></script><!-- Core js functions -->

		<script src="<?php echo site_url(); ?>theme-genyx/js/pages/domready.js"></script><!-- Init plugins only for page -->

	

		<script src="<?php echo site_url(); ?>js/genyx-utilis.js"></script>  

		<script src="<?php echo site_url(); ?>js/genyx-fn.js"></script>  

		<script src="<?php echo site_url(); ?>js/genyx-app-init.js"></script>

	

	

	

	</head>

	<body>

				<div style="background:#fff">

					<?php echo $message; ?>

					

					<?php echo $head; ?>

					

					<?php echo $body;?>

				</div>			

	</body>

	<?php echo $script;?>

	

</html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Dsemp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">[
]    <link href='<?php echo site_url(); ?>theme-genyx/css/family_open_sans_400_800_700.css' rel='stylesheet' type='text/css'>		
	<link href='<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_400_700.css' rel='stylesheet' type='text/css' />[
]	 <!--[if lt IE 9]>
	<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_400.css"  rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_700.css"  rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url(); ?>theme-genyx/css/family_open_sans_800.css"  rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_400.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url(); ?>theme-genyx/css/family_droid_sans_700.css" rel="stylesheet" type="text/css" />
	<![endif]-->[
]		[
]    <link href="<?php echo site_url(); ?>theme-genyx/css/bootstrap/bootstrap.css" rel="stylesheet" />
	<link href="<?php echo site_url(); ?>theme-genyx/css/bootstrap/bootstrap-theme.css" rel="stylesheet" />
	<link href="<?php echo site_url(); ?>theme-genyx/css/icons.css" rel="stylesheet" />
    <link href="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/uniform/uniform.default.css" rel="stylesheet" /> 
	<link href="<?php echo site_url(); ?>theme-genyx/css/app.css" rel="stylesheet" /> 
	<link href="<?php echo site_url(); ?>theme-genyx/css/custom.css" rel="stylesheet" /> [
]    
	<!--[if IE 8]>
	<link href="<?php echo site_url(); ?>theme-genyx/css/ie8.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />		
	<!--[if lt IE 9]>
	  <script src="<?php echo site_url(); ?>theme-genyx/js/html5shiv.js"></script>
	  <script src="<?php echo site_url(); ?>theme-genyx/js/respond.min.js"></script>
	<![endif]-->[
]    <link rel="apple-touch-icon-precomposed" sizes="144x144" 	href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" 	href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" 		href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" 					href="<?php echo site_url(); ?>theme-genyx/images/ico/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" 									href="<?php echo site_url(); ?>theme-genyx/images/ico/favicon.ico">[
]    
    <!-- Le javascript
    ================================================== -->
    <!-- Important plugins put in all pages -->
    <script src="<?php echo site_url(); ?>theme-genyx/js/jquery-1-9-1.min.js"></script>
    <script src="<?php echo site_url(); ?>theme-genyx/js/bootstrap/bootstrap.js"></script>  
    <script src="<?php echo site_url(); ?>theme-genyx/js/conditionizr.min.js"></script>  
    <script src="<?php echo site_url(); ?>theme-genyx/js/plugins/core/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="<?php echo site_url(); ?>theme-genyx/js/plugins/core/jrespond/jRespond.min.js"></script>
    <script src="<?php echo site_url(); ?>theme-genyx/js/jquery.genyxAdmin.js"></script>[
]    <!-- Form plugins -->
    <script src="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/uniform/jquery.uniform.min.js"></script>
	<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/validation/jquery.validate.js"></script>    [
]    <!-- Init plugins -->
    <script src="<?php echo site_url(); ?>theme-genyx/js/app.js"></script>
    <script>
		$(document).ready(function() {			
			$("html").addClass("loginPage");[
]			wrapper 	= $(".login-wrapper");
			barBtn 		= $("#bar .btn");[
]			//change the tabs
			barBtn.click(function() {
			  btnId 	= $(this).attr('id');
			  wrapper.attr("data-active", btnId);
			  $("#bar").attr("data-active", btnId);
			});[
]		[
]			//------------- Validation -------------//
			$("#login-form").validate({ 
				rules: {
					txtNickname: {
						required	: true,
						minlength	: 3
					}, 
					txtPassword: {
						required	: true,
						minlength	: 3
					}
				}, 
				messages: {
					txtNickname: {
						required	: "ingresar el usuario",
						minlength	: "minimo 3 letras"
					},
					txtPassword: {
						required	: "ingresar el password",
						minlength	: "minimo 3 letras"
					}
				},
				submitHandler: function(form){
					var btn = $('#loginBtn');
					btn.removeClass('btn-primary');
					btn.addClass('btn-danger');
					btn.text('buscando ...');
					btn.attr('disabled', 'disabled');
					
					setTimeout(function () {
						form.submit();
					}, 1000);
				}
			});
			[
]		});
	</script>[
]  </head>
  <body>
    <div class="container-fluid">
        <div id="login">
            <div class="login-wrapper" data-active="log">
               <a class="navbar-brand" href="#"><img src="<?php echo base_url(); ?>img/logodark.png" alt="Genyx admin" class="img-responsive"></a>
                <div id="log">
                    <div id="avatar">
                        <img src="<?php echo base_url(); ?>img/no_avatar.jpg" alt="SuggeElson" class="img-responsive">
                    </div>
                    <div class="page-header">
                        <h3 class="center">Ingresar</h3>
                    </div>
                    <form role="form" id="login-form" class="form-horizontal" method="POST" action="<?php echo base_url(); ?>core_acount/login">
                        <div class="row">
                            <div class="form-group relative">
                                <div class="icon"><i class="icon20 i-user"></i></div>
                                <input class="form-control" type="text" name="txtNickname" id="txtNickname" placeholder="usuario" value="">
                                
                            </div><!-- End .control-group  -->
                            <div class="form-group relative">
                                <div class="icon"><i class="icon20 i-key"></i></div>
                                <input class="form-control" type="password" name="txtPassword" id="txtPassword" placeholder="pasword" value="">
                                
                            </div><!-- End .control-group  -->
                            <div class="form-group relative">
                                <label class="control-label" class="checkbox pull-left">
                                    <input type="checkbox" value="1" name="remember">
                                    Recordar Password
                                </label>
                                <button id="loginBtn" type="submit" class="btn btn-primary pull-right col-lg-5">Aceptar</button>
                            </div>
							 <div class="form-group relative">
								<?php
									if($message != ""){
									?>
									<div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong><i class="icon24 i-warning"></i></strong><?php echo $message; ?>
									</div>
									<?php
									}
									else if(isset($status)){
									?>
									<div class="alert alert-success">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong><i class="icon24 i-info"></i></strong><?php echo $status; ?>
									</div>
									<?php
									}
								?>
                            </div>
                        </div><!-- End .row-fluid  -->
						
                    </form>                   
                </div>
                <div id="forgot">
                    <div class="page-header">
                        <h3 class="center">Recordar Password</h3>
                    </div>
                    <form class="form-horizontal" method="POST" action="<?php echo base_url(); ?>core_acount/rememberpassword">
                        <div class="row">
                            <div class="form-group relative">
                                <div class="icon"><i class="icon20 i-user"></i></div>
                                <input class="form-control" type="text"  placeholder="usuario">
                            </div><!-- End .control-group  -->
                            <div class="form-group relative">
                                <div class="icon"><i class="icon20 i-envelop-2"></i></div>
                                <input class="form-control" type="text" name="txtEmail" id="txtEmail" placeholder="email">
                            </div><!-- End .control-group  -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-block btn-success">Aceptar</button>
                            </div>
                        </div><!-- End .row-fluid  -->
                    </form>
                </div>
            </div>
            <div id="bar" data-active="log">
                <div class="btn-group btn-group-vertical">
                    <a id="log" href="#" class="btn tipR" title="Ingresar"><i class="icon16 i-key"></i></a>                    
                    <a id="forgot" href="#" class="btn tipR" title="Enviar Password"><i class="icon16 i-question"></i></a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
  </body>
</html>
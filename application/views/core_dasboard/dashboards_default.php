				<link href="<?php echo site_url(); ?>theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" /> 
				
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/flot/jquery.flot.js"></script>
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/flot/jquery.flot.pie.js"></script>
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/flot/jquery.flot.resize.js"></script>
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/flot/jquery.flot.tooltip.min.js"></script>
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/flot/jquery.flot.orderBars.js"></script>
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/flot/jquery.flot.time.min.js"></script>
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/flot/date.js"></script>
				<script src="<?php echo site_url(); ?>theme-genyx/js/moment.min.js"></script>
				
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/gauge/justgage.1.0.1.min.js"></script>
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/gauge/raphael.2.1.0.min.js"></script>
				
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/sparklines/jquery.sparkline.min.js"></script>
				<script src="<?php echo site_url(); ?>theme-genyx/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
				 
				<div id="heading" class="page-header">
							<h1><i class="icon20 i-dashboard"></i> Dashboard</h1>
				</div>
				
				<div class="row">
					 <div class="col-lg-6">		
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4><?php echo $company->name; ?></h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
								<img class="img-featured" style="width:300px;height:200px" src="<?php echo base_url();?>/img/logos/logo-micro-finanza.jpg">
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->								
					</div>
					<div class="col-lg-6">	
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Direccion</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
							   <blockquote>
									<p>Informacion de Soporte: 8716-5827</p>									<p>Informacion de Pago: BAC $ 366-577-484</p>
									<small>posMe</small>
								</blockquote>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->		
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Usuario</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
							   <blockquote>
									<p><?php echo $user->nickname; ?><br/><?php echo $user->email; ?></p>
									<small>posMe</small>
								</blockquote>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->								
					</div>
				</div>
				
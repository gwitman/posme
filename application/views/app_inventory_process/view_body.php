<div class="row"> 
	<div class="col-lg-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="icon"><i class="icon20 i-tags-2"></i></div> 
					<h4>*) Ejecutar salidas de inventario por formulas</h4>
					<a href="#" class="minimize"></a>
				</div>
				<!-- End .panel-heading -->
			
				<div class="panel-body">
					<form class="form-horizontal pad15 pad-bottom0" role="form">
						<div class="form-group">
							<label class="col-lg-2 control-label" for="selectFilter">Periodo</label>
							<div class="col-lg-8">
								<select name="txtClosedPeriod" id="txtClosedPeriod" class="select2">
										<option></option>
										<?php
										if($objListAccountingPeriod)
										foreach($objListAccountingPeriod as $i){
											echo "<option value='".$i->componentPeriodID."'>".helper_DateToSpanish($i->startOn,"Y")."</option>";
										}
										?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label" for="selectFilter">Ciclo</label>
							<div class="col-lg-8">
								<select name="txtClosedCicle" id="txtClosedCicle" class="select2">
										<option></option>
								</select>
							</div>
						</div>
								
								
						<div class="form-group">
							<button type="button" id="btnAceptCreateOutputInventoryByFormulate" class="btn btn-primary pull-right">Aceptar</button>
						</div><!-- End .form-group  -->
					</form>
				</div><!-- End .panel-body -->
			</div><!-- End .widget -->	
	</div>
	<div  class="col-lg-6">    
	</div>                        
</div>
					
					
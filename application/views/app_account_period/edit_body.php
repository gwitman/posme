					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_accounting_period/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
									<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>              
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>									
                                </div>
                            </div> 
                            <!-- /botonera -->
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<!-- widget -->
							<div class="panel panel-default">
								<!-- panel widget -->
                                <div class="panel-heading">
	                                  <div class="icon"><i class="icon20 i-cube"></i></div> 
	                                  <h4>Formulario de Datos</h4>
	                                  <a href="#" class="minimize"></a>
                                </div>
                                <!-- /panel widget -->

								<!-- body widget -->
                                <div class="panel-body noPadding">
									<!-- body -->
									<div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
										<div class="row">
											<div class="email-content col-lg-12">
												<!-- formulario -->
												<form id="form-new-account-period" name="form-new-rol" class="form-horizontal" role="form">
													<fieldset>		
														
														<input type="hidden" name="txtComponentID" value="<?php  echo $objComponentPeriod->componentID; ?>" />
														<input type="hidden" name="txtComponentPeriodID" value="<?php  echo $objComponentPeriod->componentPeriodID; ?>" />
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Codigo de Periodo</label>
																<div class="col-lg-5">
																	<input class="form-control"  type="text"  name="txtNumber" readonly="true"  placeholder="YYYY" id="txtNumber" value="<?php  echo $objComponentPeriod->number; ?>" >												
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Nombre</label>
																<div class="col-lg-5">
																	<input class="form-control"  type="text"  name="txtName" id="txtName" value="<?php  echo $objComponentPeriod->name; ?>">												
																</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Estado</label>
															<div class="col-lg-5">
																<select name="txtStatusID" id="txtStatusID" class="select2 select2statusIDPeriod">
																		<option></option>
																		<?php
																		if($objListComponentPeriodStatus)
																		foreach($objListComponentPeriodStatus as $i){
																			if($i->workflowStageID == $objComponentPeriod->statusID)
																				echo "<option value='".$i->workflowStageID."' selected >".$i->name."</option>";
																			else
																				echo "<option value='".$i->workflowStageID."'>".$i->name."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="datepicker">Fecha Inicial</label>
															<div class="col-lg-4">
																<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																	<input size="16" class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="<?php  echo $objComponentPeriod->startOn; ?>">
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>

														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="datepicker">Fecha Final</label>
															<div class="col-lg-4">
																<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																	<input size="16" class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="<?php  echo $objComponentPeriod->endOn; ?>">
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Descripcion</label>
															<div class="col-lg-6">
																<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"><?php  echo $objComponentPeriod->description; ?></textarea>
															</div>
														</div>
													</fieldset> 
											
													<br/>
													<a href="#" class="btn btn-flat btn-info" id="btnNewCycle" >Agregar</a>
													<a href="#" class="btn btn-flat btn-danger" id="btnDeleteCycle" >Eliminar</a>									
											
													<!-- tabla de ciclo-->
													<script type="text/template"  id="tmpl_row_cycle">
														<tr class="row_cycle">
															<td>
																<input type="checkbox"  class="txtCycleIsActive" name="txtCycleIsActive[]" value="1" >														
																<input type="hidden" name="txtComponentCycleID[]" />
															</td>
															<td>
																<div class="form-group">
																	<div class="col-lg-12">
																		<div id="datepicker" class="input-group date"  data-date-format="yyyy-mm-dd">
																			<input size="16" class="form-control txtCycleStartOn accountingCycleStart" type="text"  name="txtCycleStartOn[]" >
																			<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																		</div>
																	</div>
																</div>
															</td>
															<td>
																<div class="form-group">
																	<div class="col-lg-12">
																		<div id="datepicker" class="input-group date"  data-date-format="yyyy-mm-dd">
																			<input size="16" class="form-control txtCycleEndOn accountingCycleEnd" type="text"  name="txtCycleEndOn[]" >
																			<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																		</div>
																	</div>
																</div>
															</td>
															<td>
																<div class="form-group">
																	<div class="col-lg-12">
																		<select name="txtCycleStatusID[]" id="txtCycleWorkflowStageID"  class="select2 select2statusID">
																				<option></option>
																				<?php
																				if($objListComponentCycleStatus)
																				foreach($objListComponentCycleStatus as $i){																					
																					echo "<option value='".$i->workflowStageID."'>".$i->name."</option>";
																				}
																				?>
																		</select>
																	</div>
																</div>
															</td>
															<td>
																<div class="form-group">
																		<div class="col-lg-12">
																			<input class="form-control"  type="text"  name="txtCycleNumber[]" readonly="true"  value="">												
																		</div>
																</div>
															</td>
														</tr>
													</script>
													<table id="ListComponentCycle" class="table table-striped">
														<thead>
															<tr style="width:100%">
																<th style="width:4%"></th>
																<th style="width:24%">Inicio</th>
																<th style="width:24%">Fin</th>
																<th style="width:24%">Estado</th>
																<th style="width:24%">Codigo de Ciclo</th>
															</tr>
														</thead>												
														<tbody id="tbody_detail">	
															<?php
															if($objListComponentCycle)
															foreach($objListComponentCycle as $i){
																?>
																<tr class="row_cycle">
																	<td>
																		<input type="checkbox"  class="txtCycleIsActive" name="txtCycleIsActive[]" value="1" >														
																		<input type="hidden" name="txtComponentCycleID[]" value="<?php echo $i->componentCycleID; ?>" />
																	</td>
																	<td>
																		<div class="form-group">
																			<div class="col-lg-12">
																				<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																					<input size="16" class="form-control txtCycleStartOn accountingCycleStart" type="text"  name="txtCycleStartOn[]"  value="<?php echo $i->startOn; ?>">
																					<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																				</div>
																			</div>
																		</div>
																	</td>
																	<td>
																		<div class="form-group">
																			<div class="col-lg-12">
																				<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																					<input size="16" class="form-control txtCycleEndOn accountingCycleEnd" type="text"  name="txtCycleEndOn[]"  value="<?php echo $i->endOn; ?>">
																					<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																				</div>
																			</div>
																		</div>
																	</td>
																	<td>
																		<div class="form-group">
																			<div class="col-lg-12">
																				<select name="txtCycleStatusID[]"   class="select2 select2statusID">
																						<option></option>
																						<?php
																						if($objListComponentCycleStatus)
																						foreach($objListComponentCycleStatus as $s){	
																							if($s->workflowStageID == $i->statusID)
																								echo "<option value='".$s->workflowStageID."' selected >".$s->name."</option>";
																							else
																								echo "<option value='".$s->workflowStageID."'>".$s->name."</option>";
																						}
																						?>
																				</select>
																			</div>
																		</div>
																	</td>
																	<td>
																		<div class="form-group">
																				<div class="col-lg-12">
																					<input class="form-control"  type="text"  name="txtCycleNumber[]" readonly="true"  value="<?php echo $i->number; ?>">												
																				</div>
																		</div>
																	</td>
																</tr>
																<?php 
															}
															?>
														</tbody>
													</table>															  
													<!-- /tabla de ciclo-->
												</form>				
												<!-- /formulario -->
											</div>
										</div><!-- End .row-fluid  -->                            
									</div>
									<!-- /body -->
                                </div>
							</div>
							
                        </div>
                        <!-- End #email  -->
                    </div>
                    <!-- End .row-fluid  -->
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
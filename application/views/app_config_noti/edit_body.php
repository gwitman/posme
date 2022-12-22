					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_config_noti/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
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
												<form id="form-new-account-level" name="form-new-rol" class="form-horizontal" role="form">
													<fieldset>		
														<input type="hidden" name="txtRememberID" id="txtRememberID" value="<?php echo $objRemember->rememberID;?>" />
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Titulo</label>
																<div class="col-lg-4">
																	<input class="form-control"  type="text" name="txtTitulo" id="txtTitulo" value="<?php echo $objRemember->title;?>">												
																</div>
														</div>														
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Periodo</label>
															<div class="col-lg-4">
																<select name="txtPeriodID" id="txtPeriodID" class="select2">
																		<option></option>
																		<?php
																		if($objListPeriod)
																		foreach($objListPeriod as $i){
																			if($i->catalogItemID == $objRemember->period )
																			echo "<option value='".$i->catalogItemID."' selected>".$i->name."</option>";
																			else 
																			echo "<option value='".$i->catalogItemID."'>".$i->name."</option>";
																		}
																		?>																
																</select>
															</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Dias</label>
																<div class="col-lg-4">
																	<input class="form-control"  type="text" name="txtDias" id="txtDias" value="<?php echo $objRemember->day;?>">												
																</div>
														</div>
														
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Estado</label>
															<div class="col-lg-4">
																<select name="txtStatusID" id="txtStatusID" class="select2">
																		<option></option>
																		<?php
																		if($objListWorkflowStage)
																		foreach($objListWorkflowStage as $ws){
																			if ($ws->workflowStageID == $objRemember->statusID)
																			echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
																			else
																			echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
																		}
																		?>													
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="checkboxes">Temporal</label>													
															<label class="checkbox-inline">
																<input type="checkbox" id="txtIsTemporal" name="txtIsTemporal" value="1" <?php if($objRemember->isTemporal){ echo "checked='checked'"; } ?> >
															</label>													
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Descripcion</label>
															<div class="col-lg-10">
																<textarea class="form-control"  id="txtDescripcion" name="txtDescripcion" rows="6"><?php echo $objRemember->description;?></textarea>
															</div>
														</div>
														
													</fieldset> 
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
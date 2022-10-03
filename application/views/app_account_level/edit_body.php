					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_accounting_level/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
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
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Nombre</label>
																<div class="col-lg-10">
																	<input class="form-control"  type="text" name="txtName" id="txtName" value="<?php echo $objAccountLevel->name;?>">
																	<input type="hidden" name="txtCompanyID" id="companyID" value="<?php echo $objAccountLevel->companyID;?>" />
																	<input type="hidden" name="txtAccountLevelID" id="txtAccountLevelID" value="<?php echo $objAccountLevel->accountLevelID;?>" />												
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Separador</label>
																<div class="col-lg-2">
																	<input class="form-control"  type="text" name="txtSplit" id="txtSplit" value="<?php echo $objAccountLevel->split;?>">												
																</div>
														</div>
															
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Longitud Total</label>
																<div class="col-lg-2">
																	<input class="form-control"  type="text" name="txtLengthTotal" id="txtLengthTotal" value="<?php echo $objAccountLevel->lengthTotal;?>">												
																</div>
														</div>
															
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Longitud del Grupo</label>
																<div class="col-lg-2">
																	<input class="form-control"  type="text" name="txtLengthGroup" id="txtLengthGroup" value="<?php echo $objAccountLevel->lengthGroup;?>">												
																</div>
														</div>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Descripcion</label>
															<div class="col-lg-10">
																<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"><?php echo $objAccountLevel->description;?></textarea>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="checkboxes">Es Operativa</label>													
															<label class="checkbox-inline">
																<input type="checkbox" id="txtIsOperative" name="txtIsOperative" value="1" <?php if($objAccountLevel->isOperative){ echo "checked='checked'"; } ?> >
															</label>													
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
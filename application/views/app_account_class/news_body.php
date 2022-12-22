					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_accounting_class/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
												<form id="form-new-account" name="form-new-rol" class="form-horizontal" role="form">
													<fieldset>	
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Codigo</label>
																<div class="col-lg-5">
																	<input class="form-control"  type="text"  name="txtNumber" id="txtNumber" value="">												
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Centro de Costo Superior</label>
																<div class="col-lg-5">
																	<input class="form-control"  type="text"  name="txtParentClassNumber" id="txtParentClassNumber" value="">												
																</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Clase</label>
															<div class="col-lg-5">
																<select name="txtAccountLevelID" id="txtAccountLevelID" class="select2">
																		<option></option>
																		<?php
																		if($objListAccountLevel)
																		foreach($objListAccountLevel as $i){
																			echo "<option value='".$i->accountLevelID."'>".$i->name."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Nombre</label>
																<div class="col-lg-6">
																	<input class="form-control"  type="text" name="txtDescription" id="txtDescription" value="">												
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
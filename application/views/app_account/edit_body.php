					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_accounting_account/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
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
												<form id="form-new-account" name="form-new-rol" class="form-horizontal" role="form">
													<fieldset>	
													
														<input type="hidden" name="txtCompanyID" id="companyID" value="<?php echo $objAccount->companyID;?>" />
														<input type="hidden" name="txtAccountID" id="txtAccountID" value="<?php echo $objAccount->accountID;?>" />												
																	
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Codigo</label>
																<div class="col-lg-5">
																	<input class="form-control"  type="text"  name="txtAccountNumber" id="txtAccountNumber" value="<?php echo $objAccount->accountNumber;?>">												
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Codigo Padre</label>
																<div class="col-lg-5">
																	<input class="form-control"  type="text"  name="txtAccountNumberParent" id="txtAccountNumberParent" value="<?php if ($objParentAccount) { echo  $objParentAccount->accountNumber; } ?>">												
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
																			if($objAccount->accountLevelID == $i->accountLevelID)
																				echo "<option value='".$i->accountLevelID."' selected >".$i->name."</option>";
																			else
																				echo "<option value='".$i->accountLevelID."'>".$i->name."</option>";
																		}
																		?>																
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Tipo</label>
															<div class="col-lg-5">
																<select name="txtAccountTypeID" id="txtAccountTypeID" class="select2">
																		<option></option>	
																		<?php
																		if($objListAccountType)
																		foreach($objListAccountType as $i){
																			if($objAccount->accountTypeID == $i->accountTypeID)
																				echo "<option value='".$i->accountTypeID."' selected>".$i->name."</option>";
																			else
																				echo "<option value='".$i->accountTypeID."'>".$i->name."</option>";
																		}
																		?>																	
																</select>
															</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Nombre</label>
																<div class="col-lg-5">
																	<input class="form-control"  type="text" name="txtName" id="txtName" value="<?php echo $objAccount->name;?>">												
																</div>
														</div>												
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Descripcion</label>
															<div class="col-lg-5">
																<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"><?php echo $objAccount->description;?></textarea>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Moneda</label>
															<div class="col-lg-5">
																<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
																		<option></option>
																		<?php
																		if($objListCompanyCurrency)
																		foreach($objListCompanyCurrency as $i){
																			if($objAccount->currencyID == $i->currencyID)
																				echo "<option value='".$i->currencyID."' selected >".$i->name."</option>";
																			else
																				echo "<option value='".$i->currencyID."'>".$i->name."</option>";
																		}
																		?>																	
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="checkboxes">Es Operativa</label>													
															<label class="checkbox-inline">
																<input type="checkbox" id="txtIsOperative" name="txtIsOperative" value="1" <?php if($objAccount->isOperative){ echo "checked='checked'"; } ?> >
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
					<div class="row">
						<div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="icon"><i class="icon20 i-stats"></i></div> 
                                    <h4>Historico De los ultimos 12 balances <small>...</small></h4>
                                    <a href="#" class="minimize"></a>
                                </div><!-- End .panel-heading -->
                            
                                <div class="panel-body">
                                   <div class="chart-line" style="width: 100%; height:250px;margin-top:10px;"></div>
                                </div><!-- End .panel-body -->
                            </div><!-- End .widget -->
                        </div><!-- End .col-lg-6  -->
					</div>
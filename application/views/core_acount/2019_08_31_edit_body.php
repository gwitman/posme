				    <div class="row">
                        <div id="email" class="col-lg-12">
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
                                    <a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>                                    
                                </div>
                            </div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
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
									<div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
										<div class="row">
											<div class="email-content col-lg-12">
												<form id="form-edit-acount" class="form-horizontal" role="form" >							
													<fieldset>		
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Codigo</label>
																<div class="col-lg-10">
																	<input class="form-control" readonly="readonly" type="text" name="txtUserID" id="txtUserID" value="<?php echo helper_StrPadString($userID,8,"0"); ?>">
																	<input type="hidden" id="txtCompanyID" name="txtCompanyID"  value="<?php echo $companyID; ?>">
																	<input type="hidden" id="txtBranchID" name="txtBranchID"    value="<?php echo $branchID; ?>"> 								
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Colaborador</label>
																<div class="col-lg-10">
																	<input class="form-control"  type="text" readonly="readonly" name="txtEmployeeDescription" id="txtEmployeeDescription" value="<?php echo $objEmployee == null  ?  ""  : ($objEmployee->employeNumber." / ".$objEmployeeNatural->firstName." ".$objEmployeeNatural->lastName); ?>">
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Nickname</label>
																<div class="col-lg-10">
																	<input class="form-control"  type="text" name="txtNickname" id="txtNickname" value="<?php echo $nickname; ?>">												
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Password</label>
																<div class="col-lg-10">
																	<input class="form-control"  type="password" name="txtPassword" id="txtPassword" value="<?php echo $password; ?>">												
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Email</label>
																<div class="col-lg-10">
																	<input class="form-control"  type="text" name="txtEmail" id="txtEmail" value="<?php echo $email; ?>">												
																</div>
														</div>																						
													</fieldset> 	
												</form>
											</div>
										</div><!-- End .row-fluid  -->                            
									</div>
								</div>
							</div>
                        </div><!-- End #email  -->
                    </div><!-- End .row-fluid  -->
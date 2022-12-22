					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    									
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
											<!-- formulario -->
											<form id="form-new-account-level" name="form-new-rol" class="form-horizontal" role="form">
												<div class="email-content col-lg-6">
														<fieldset>																																
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Cuenta de Activo</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtAccountActivo" id="txtAccountActivo" value="<?php echo $accountActivo; ?>">												
																	</div>
															</div>
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Cuenta de Pasivo</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtAccountPasivo" id="txtAccountPasivo" value="<?php echo $accountPasivo; ?>">												
																	</div>
															</div>
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Cuenta de Capital</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtAccountCapital" id="txtAccountCapital" value="<?php echo $accountCapital; ?>">												
																	</div>
															</div>
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Cuenta de Igresos</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtAccountIngreso" id="txtAccountIngreso" value="<?php echo $accountIngreso; ?>">												
																	</div>
															</div>
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Cuenta de Costos</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtAccountCostos" id="txtAccountCostos" value="<?php echo $accountCostos; ?>">												
																	</div>
															</div>
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Cuenta de Gastos</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtAccountGastos" id="txtAccountGastos" value="<?php echo $accountGastos; ?>">												
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Dolares Compra</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtExchangePurchase" id="txtExchangePurchase" value="<?php echo $exchangePurchase; ?>">
																	</div>
															</div>
															
															
															
															
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Razon del Circulante</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtRazon001" id="txtRazon001" value="<?php echo $objRazon001; ?>">
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Endeudamiento</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtRazon002" id="txtRazon002" value="<?php echo $objRazon002; ?>">
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Utilidad Mensual</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtRazon003" id="txtRazon003" value="<?php echo $objRazon003; ?>">
																	</div>
															</div>
															
															
															
														</fieldset>
												</div>
												<div class="email-content col-lg-6">												
														<fieldset>		
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Cuenta de Utilidades</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtUtility" id="txtUtility" value="<?php echo $accountUtilityPeriod; ?>">												
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Cuenta de Utilidades Acumuladas</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtUtilityAcumulate" id="txtUtilityAcumulate" value="<?php echo $accountUtilityAcumulate; ?>">												
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Formula de Utilidades</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtFormulateUtility" id="txtFormulateUtility" value="<?php echo $formulateUtility; ?>">												
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Mondea De la Empresa </label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtCurrency" id="txtCurrency" value="<?php echo $currencyDefault; ?>">												
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Mondea De Reporte </label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtCurrencyReport" id="txtCurrencyReport" value="<?php echo $currencyReport; ?>">												
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Cuentas de Resulado</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtAccountResult" id="txtAccountResult" value="<?php echo $accountResult; ?>">												
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Dolares Venta</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtExchangeSales" id="txtExchangeSales" value="<?php echo $exchangeSales; ?>">												
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Utilidad Anual</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtRazon004" id="txtRazon004" value="<?php echo $objRazon004; ?>">
																	</div>
															</div>
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Rentabilidad Anual</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtRazon005" id="txtRazon005" value="<?php echo $objRazon005; ?>">
																	</div>
															</div>
															
															
															<div class="form-group">
																	<label class="col-lg-8 control-label" for="normal">Rentabilidad Mensual</label>
																	<div class="col-lg-4">
																		<input class="form-control"  type="text" name="txtRazon006" id="txtRazon006" value="<?php echo $objRazon006; ?>">
																	</div>
															</div>
															
														</fieldset> 												
												</div>
											</form>
											<!-- /formulario -->
										</div><!-- End .row-fluid  -->                            
									</div>
									<!-- /body -->
                                </div>
							</div>
                        </div>
                        <!-- End #email  -->
                    </div>
                    <!-- End .row-fluid  -->
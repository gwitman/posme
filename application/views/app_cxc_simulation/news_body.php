					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">
									<a href="#" class="btn btn-info" id="btnCalculate"><i class="icon16 i-spinner"></i> Calcular</a>
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
                                </div>
                            </div> 
                            <!-- /botonera -->
                        </div>
                        <!-- End #email  -->
                    </div>
                    <!-- End .row-fluid  -->
					
				    <div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
										
								<!-- titulo de comprobante-->
								<div class="panel-heading">
										<div class="icon"><i class="icon20 i-file"></i></div> 
										<h4>SIMULADOR:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<ul id="myTab" class="nav nav-tabs">
										<li class="active">
											<a href="#home" data-toggle="tab">Informacion</a>
										</li>
									</ul>
									
									<div class="tab-content">
										<div class="tab-pane fade in active" id="home">	
											<div class="row">										
											<div class="col-lg-6">
											
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Plan</label>
														<div class="col-lg-8">
															<select name="txtPlanID" id="txtPlanID" class="select2">
																<?php
																	if($objListTypeAmortization)
																	foreach($objListTypeAmortization as $ws){
																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																	}
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Frecuencia</label>
														<div class="col-lg-8">
															<select name="txtFrecuencyID" id="txtFrecuencyID" class="select2">
																<?php
																	if($objListPay)
																	foreach($objListPay as $ws){
																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																	}
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal"># Pagos</label>
															<div class="col-lg-8">
																<input class="form-control txt-numeric"  type="text"  name="txtNumberPay" id="txtNumberPay" value="">
															</div>
													</div>
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Interes Anual</label>
															<div class="col-lg-8">
																<input class="form-control txt-numeric"  type="text"  name="txtInterestYear" id="txtInterestYear" value="">
															</div>
													</div>
													
													
											</div>
											<div class="col-lg-6">
											
													<div class="form-group">
														<label class="col-lg-4 control-label" for="buttons">Cliente</label>
														<div class="col-lg-8">
															<div class="input-group">
																<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objCustomerDefault->entityID;  ?>">
																<input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription" value="<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>">
																
																<span class="input-group-btn">
																	<button class="btn btn-danger" type="button" id="btnClearCustomer">
																		<i aria-hidden="true" class="i-undo-2"></i>
																		clear
																	</button>
																</span>
																<span class="input-group-btn">
																	<button class="btn btn-primary" type="button" id="btnSearchCustomer">
																		<i aria-hidden="true" class="i-search-5"></i>
																		buscar
																	</button>
																</span>
																
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Linea</label>
														<div class="col-lg-8">
															<select name="txtCustomerCreditLineID" id="txtCustomerCreditLineID" class="select2">
															</select>
														</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Monto</label>
															<div class="col-lg-8">
																<input class="form-control txt-numeric"  type="text"  name="txtAmount" id="txtAmount" value="">
															</div>
													</div>
													
													
												
											</div>
											</div>
										</div>
									</div>    

							
									<br/>
									
									
									<div class="row">
                                        <div class="col-lg-12">
                                            <h3>Detalle:</h3>
                                            <table id="tb_transaction_master_detail" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th>Numero</th>
													<th>Fecha</th>
													<th>Inicial</th>
                                                    <th>Interes</th>
                                                    <th>Capital</th>
													<th>Cuota</th>
                                                    <th>Final</th>	
                                                  </tr>
                                                </thead>
                                                <tbody id="body_tb_transaction_master_detail">
                                                </tbody>
                                            </table>
											
                                            
                                        </div><!-- End .col-lg-12  --> 
                                    </div><!-- End .row-fluid  -->
                                    <div class="row">
                                        <div class="col-lg-6">
											<div class="page-header">
                                                <h3>Ref.</h4>
                                            </div>
                                            <ul class="list-unstyled">
                                                <li><h3>CC: <span class="red-smooth">*</span></h3></li>
                                                <li><i class="icon16 i-arrow-right-3"></i>Resumen de la simulacion</li>                                                
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="page-header">
                                                <h3>Resumen</h3>
                                            </div>
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>TOTAL CAPITAL</th>
                                                        <td >
															<input type="text" id="txtCapitalTotal" name="txtCapitalTotal" readonly class="col-lg-12 txt-numeric" value="" style="text-align:right"/>
														</td>
                                                    </tr>
                                                    <tr>
                                                        <th>TOTAL INTERES</th>
                                                        <td >
															<input type="text" id="txtInterestTotal" name="txtInterestTotal" readonly class="col-lg-12 txt-numeric" value="" style="text-align:right"/>
														</td>
                                                    </tr>
													<tr>
                                                        <th>TOTAL</th>
                                                        <td >
															<input type="text" id="txtTotal" name="txtTotal" readonly class="col-lg-12 txt-numeric" value="" style="text-align:right"/>
														</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!-- End .col-lg-6  --> 
                                    </div><!-- End .row-fluid  -->                                       
                                </div>
								</form>
								<!-- /body -->
							</div>
						</div>
					</div>
					
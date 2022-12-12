					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_box_provisioned/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
									<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
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
										<h4>PROVISION:#<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<ul id="myTab" class="nav nav-tabs">
										<li class="active">
											<a href="#home" data-toggle="tab">Informacion</a>
										</li>
										<li>
											<a href="#profile" data-toggle="tab">Referencias.</a>
										</li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
												<li id="btnClickArchivo"><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
											 </ul>
										</li>
									</ul>
									
									<div class="tab-content">
										<div class="tab-pane fade in active" id="home">	
											<div class="row">										
												<div class="col-lg-6">
													
														<input type="hidden" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
														<input type="hidden" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
														<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTransactionMaster->transactionMasterID; ?>">
														
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="datepicker">Fecha</label>
															<div class="col-lg-8">
																<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																	<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>" >
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Aplicado</label>
																<div class="col-lg-5">
																	<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" <?php if($objTransactionMaster->isApplied) echo "checked"; ?> >
																</div>
														</div>
														<div class="form-group">
																<label class="col-lg-2 control-label" for="normal">Cambio</label>
																<div class="col-lg-8">
																	<input class="form-control"   type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
																</div>
														</div>
														
														
													
												</div>
												<div class="col-lg-6">
												
													<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Estado</label>
															<div class="col-lg-8">
																<select name="txtStatusID" id="txtStatusID" class="select2">
																		<option></option>																
																		<?php
																		if($objListWorkflowStage)
																		foreach($objListWorkflowStage as $ws){
																			
																			if($ws->workflowStageID == $objTransactionMaster->statusID)
																				echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
																			else 
																				echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
																		}
																		?>
																</select>
															</div>
													</div>
														
														
													<div class="form-group">
														<label class="col-lg-2 control-label" for="buttons">Cliente</label>
														<div class="col-lg-8">
															<div class="input-group">
																<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objTransactionMaster->entityID;  ?>">
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
													
													
													
													<div class="vital-stats">
														<ul>
																<li>
																	<a href="#">
																		<div class="item">
																			<div class="icon green"><i class="i-download-2"></i></div>
																			<span class="percent"><?php echo sprintf("%01.2f",0); ?></span>
																			<span class="txt">C$ compra</span>
																		</div>
																	</a>
																</li>
																<li>
																	<a href="#">
																		<div class="item">
																			<div class="icon yellow"><i class="i-search-3"></i></div>
																			<span class="percent"><?php echo sprintf("%01.2f",0); ?></span>
																			<span class="txt">C$ vent</span>
																		</div>
																	</a>
																</li>
																<li>
																	<a href="#">
																		<div class="item">
																			<div class="icon orange"><i class="i-temperature"></i></div>
																			<span class="percent"><?php echo sprintf("%01.2f",$exchangeRate); ?></span>
																			<span class="txt">C$</span>
																		</div>
																	</a>
																</li>
															
														</ul>
													</div><!-- End .vital-stats -->
											</div>
											</div>
										</div>
										<div class="tab-pane fade" id="profile">
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Cliente Ref.</label>
															<div class="col-lg-8">
																<input class="form-control"   type="text" name="txtReferenceClientName" id="txtReferenceClientName" value="<?php echo $objTransactionMasterInfo->referenceClientName; ?>">
															</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">ID Cliente Ref.</label>
															<div class="col-lg-8">
																<input class="form-control"   type="text" name="txtReferenceClientIdentifier" id="txtReferenceClientIdentifier" value="<?php echo $objTransactionMasterInfo->referenceClientIdentifier; ?>">
															</div>
													</div>
													
													
												</div>
												<div class="col-lg-6">
														
													
													
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Referencia</label>
															<div class="col-lg-5">
																<input class="form-control"  type="text"  name="txtReference" id="txtReference" value="<?php echo $objTransactionMaster->reference2; ?>">												
															</div>
													</div>											
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="dropdown">
											
												<div class="form-group">
		                                            <label class="col-lg-2 control-label" for="normal">Descripcion</label>
		                                            <div class="col-lg-6">
		                                                <textarea class="form-control"  id="txtNote" name="txtNote" rows="6"><?php echo $objTransactionMaster->note; ?></textarea>
		                                            </div>
		                                        </div>
											
										</div>
										<div class="tab-pane fade" id="dropdown-file">
											
										</div>
									</div>    [
]							
									<br/>
									<a href="#" class="btn btn-flat btn-info" id="btnNewShare" >Agregar</a>
									<a href="#" class="btn btn-flat btn-danger" id="btnDeleteShare" >Eliminar</a>									
									<div class="row">
                                        <div class="col-lg-12">
                                            <h3>Detalle:</h3>
                                            <table id="tb_transaction_master_detail" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th></th>
													<th>Documento</th>
													<th>Moneda</th>
                                                    <th>Provision</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="body_tb_transaction_master_detail">
													<?php
														if($objTransactionMasterDetail)
														foreach($objTransactionMasterDetail as $key => $value)
														{
														?>
														<tr class="row_razon">
															<td>
																<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
																<input type="hidden" name="txtDetailCustomerCreditDocumentID[]" id="txtDetailCustomerCreditDocumentID"  class="classDetailItem"  value="<?php echo $value->componentItemID; ?>"/>
																<input type="hidden" name="txtDetailTransactionMaster[]" value="<?php echo $value->transactionMasterDetailID; ?>"/>
																
															</td>
															<td><text id="txtDocument"><?php echo $value->reference1; ?></text></td>
															<td><text id="txtNameMoneda"><?php echo $value->reference2; ?></text></td>
															<td>
																<input class="form-control txtBalance txt-numeric"  type="text" id="txtBalance"  name="txtBalance[]"  value="<?php echo  number_format($value->amount,2); ?>" />
															</td>
														</tr>
														<?php
														}
													?>
                                                </tbody>
                                            </table>
                                            
                                        </div><!-- End .col-lg-12  --> 
                                    </div><!-- End .row-fluid  -->
									
                                    <div class="row">
                                        <div class="col-lg-4">
											<div class="page-header">
                                                <h3>Ref.</h4>
                                            </div>
                                            <ul class="list-unstyled">
                                                <li><h3>CC: <span class="red-smooth">*</span></h3></li>
                                                <li><i class="icon16 i-arrow-right-3"></i>Resumen de Provision</li>                                                
                                            </ul>[
]                                        </div>
                                         <div class="col-lg-4">
											<div class="page-header">
                                                <h3>Pago</h3>
                                            </div>
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>INGRESO</th>
                                                        <td >
															<input type="text" id="txtReceiptAmount" name="txtReceiptAmount"  class="col-lg-12 txt-numeric" value="<?php echo number_format($objTransactionMasterInfo->receiptAmount,2); ?>" style="text-align:right"/>
														</td>
                                                    </tr>
                                                    <tr>
                                                        <th>CAMBIO</th>
                                                        <td >
															<input type="text" id="txtChangeAmount" name="txtChangeAmount" readonly class="col-lg-12 txt-numeric" value="" style="text-align:right"/>
														</td>
                                                    </tr>
                                                </tbody>
                                            </table>
										</div>
                                        <div class="col-lg-4">
                                            <div class="page-header">
                                                <h3>Resumen</h3>
                                            </div>
                                            <table class="table table-bordered">
                                                <tbody>
													<tr>
                                                        <th>TOTAL</th>
                                                        <td >
															<input type="text" id="txtTotal" name="txtTotal" readonly class="col-lg-12 txt-numeric" value="<?php echo number_format($objTransactionMaster->amount,2); ?>" style="text-align:right"/>
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
					
					<script type="text/template"  id="tmpl_row_document">
							<tr class="row_razon">
								<td>
									<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
									<input type="hidden" name="txtDetailCustomerCreditDocumentID[]" id="txtDetailCustomerCreditDocumentID"  class="classDetailItem" />
									<input type="hidden" name="txtDetailTransactionMaster[]" />
								</td>
								<td><text id="txtDocument"></text></td>
								<td><text id="txtNameMoneda"></text></td>
								<td>
									<input class="form-control txtBalance txt-numeric"  type="text" id="txtBalance"  name="txtBalance[]"  value="" />
								</td>
							</tr>
					</script>
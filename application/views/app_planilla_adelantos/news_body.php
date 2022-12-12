					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_planilla_adelantos/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
										<h4>ADELANTO:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<ul id="myTab" class="nav nav-tabs">
										<li class="active">
											<a href="#home" data-toggle="tab">Informacion</a>
										</li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
												<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
											 </ul>
										</li>
									</ul>
									
									<div class="tab-content">
										<div class="tab-pane fade in active" id="home">	
											<div class="row">										
												<div class="col-lg-6">
													
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Ciclo</label>
															<div class="col-lg-8">
																<select name="txtCycleID" id="txtCycleID" class="select2">																									<?php																		if($objListCycle)																		foreach($objListCycle as $item){																			echo "<option value='".$item->componentCycleID."' >".$item->startOn."</option>";																		}																		?>																		
																</select>
															</div>
														</div>
													
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Estado</label>
															<div class="col-lg-8">
																<select name="txtStatusID" id="txtStatusID" class="select2">
																		<option></option>																
																		<?php
																		if($objListWorkflowStage)
																		foreach($objListWorkflowStage as $ws){
																			echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														
													
												</div>
												<div class="col-lg-6">
												
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Tipo</label>
															<div class="col-lg-8">
																<select name="txtTypeID" id="txtTypeID" class="select2">
																		<option></option>																
																		<?php
																		if($objListType)
																		foreach($objListType as $ws){
																			echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Moneda</label>
															<div class="col-lg-8">
																<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
																		<option></option>																
																		<?php
																		if($objListCurrency)
																		foreach($objListCurrency as $ws){
																			echo "<option value='".$ws->currencyID."' selected>".$ws->simbol."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														
												</div>
											</div>
										</div>
										
										<div class="tab-pane fade" id="dropdown">
											
												<div class="form-group">
		                                            <label class="col-lg-2 control-label" for="normal">Descripcion</label>
		                                            <div class="col-lg-6">
		                                                <textarea class="form-control"  id="txtNote" name="txtNote" rows="6"></textarea>
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
													<th>Colaborador</th>                                                    
													<th>Monto</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="body_tb_transaction_master_detail">
                                                </tbody>
                                            </table>
                                            
                                        </div><!-- End .col-lg-12  --> 
                                    </div><!-- End .row-fluid  -->
                                    <div class="row">
                                        <div class="col-lg-8">
											<div class="page-header">
                                                <h3>Ref.</h4>
                                            </div>
                                            <ul class="list-unstyled">
                                                <li><h3>CC: <span class="red-smooth">*</span></h3></li>
                                                <li><i class="icon16 i-arrow-right-3"></i>Resumen de planilla</li>                                                
                                            </ul>[
]                                        </div>
                                        <div class="col-lg-4">
                                            <div class="page-header">
                                                <h3>Resumen</h3>
                                            </div>
                                            <table class="table table-bordered">
                                                <tbody>                                                    
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
					
					
					<script type="text/template"  id="tmpl_row_document">
							<tr class="row_razon">
								<td>
									<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
									<input type="hidden"  name="txtTransactionMasterDetailID[]" id="txtTransactionMasterDetailID"  />
									<input type="hidden" class="classDetailItem" id="txtEmployeeID" name="txtEmployeeID[]" />
								</td>
								<td><text id="txtDocument"></text></td>								
								<td>
									<input class="form-control txtDetailShare txt-numeric"  type="text" id="txtNeto"  name="txtNeto[]"  value="0" />
								</td>
							</tr>
					</script>
<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<!--
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<a href="<?php echo site_url(); ?>app_invoice_billing/add.aspx" class="btn btn-info" id="btnNew"><i class="icon16 i-checkmark-4"></i> Nueva</a>
				<a href="<?php echo site_url(); ?>app_invoice_billing/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
				<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>									
				<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
				<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
				
			</div>
		</div> 
		-->
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
					<h4>FACTURA:#<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span></h4>
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
					<li>
						<a href="#credit" data-toggle="tab">Info de Credito.</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
							<li><a id="btnClickArchivo" href="#" target="blanck"  data-toggle="tab">Archivos</a></li>
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
								
								
								<div class="form-group">
									<label class="col-lg-2 control-label" for="normal">Descripcion</label>
									<div class="col-lg-8">
										<textarea class="form-control"  id="txtNote" name="txtNote" rows="6"><?php echo $objTransactionMaster->note; ?></textarea>
									</div>
								</div>
								
							
						</div>
						<div class="col-lg-6">
						
								<div class="form-group">
									<label class="col-lg-4 control-label" for="buttons">Cliente</label>
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
											<!--
											<span class="input-group-btn">
												<button class="btn btn-success" type="button" id="btnSearchCustomerNew">
													<i aria-hidden="true" class="i-plus"></i>
													nuevo
												</button>
											</span>
											-->
											
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
									<div class="col-lg-8">
										<select name="txtCausalID" id="txtCausalID" class="select2">							
												<?php
												if($objCaudal)
												foreach($objCaudal as $causal){
													if($causal->transactionCausalID == $objTransactionMaster->transactionCausalID )
														echo "<option value='".$causal->transactionCausalID."' selected >".$causal->name."</option>";
													else
														echo "<option value='".$causal->transactionCausalID."'  >".$causal->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								
								
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Cliente</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtReferenceClientName" id="txtReferenceClientName" value="<?php echo $objTransactionMasterInfo->referenceClientName; ?>">
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Cedula</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtReferenceClientIdentifier" id="txtReferenceClientIdentifier" value="<?php echo $objTransactionMasterInfo->referenceClientIdentifier; ?>">
										</div>
								</div>

								<div class="form-group hidden" id="divLineaCredit">
									<label class="col-lg-4 control-label" for="selectFilter">Línea de Crédito</label>
									<div class="col-lg-8">
										<select name="txtCustomerCreditLineID" id="txtCustomerCreditLineID" class="select2">
										</select>
									</div>
								</div>
								
								
								
								
							
						</div>
						</div>
					
						
					</div>
					<div class="tab-pane fade" id="profile">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Zona</label>
									<div class="col-lg-8">
										<select name="txtZoneID" id="txtZoneID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListZone)
												foreach($objListZone as $z){
													if($z->catalogItemID  == $objTransactionMasterInfo->zoneID )
														echo "<option value='".$z->catalogItemID."' selected >".$z->display."</option>";
													else
														echo "<option value='".$z->catalogItemID."'  >".$z->display."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Precio</label>
									<div class="col-lg-8">
										<select name="txtTypePriceID" id="txtTypePriceID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListTypePrice)
												foreach($objListTypePrice as $price){
													if($count == 0 )
													echo "<option value='".$price->catalogItemID."' selected >".$price->display."</option>";
													else
													echo "<option value='".$price->catalogItemID."'  >".$price->display."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Bodega</label>
									<div class="col-lg-8">
										<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListWarehouse)
												foreach($objListWarehouse as $ware){
													if($objTransactionMaster->sourceWarehouseID == $ware->warehouseID)
													echo "<option value='".$ware->warehouseID."' selected >".$ware->name."</option>";
													else
													echo "<option value='".$ware->warehouseID."'  >".$ware->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								
							</div>
							<div class="col-lg-6">
									
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Referencia2</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objTransactionMaster->reference2; ?>">												
										</div>
								</div>											
								
							
							
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Referencia3</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="<?php echo $objTransactionMaster->reference3; ?>">												
										</div>
								</div>		
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
									<div class="col-lg-8">
										<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
												<?php
												$count = 0;
												if($listCurrency)
												foreach($listCurrency as $currency){
													if($currency->currencyID == $objTransactionMaster->currencyID )
													echo "<option value='".$currency->currencyID."' selected >".$currency->name."</option>";
													else
													echo "<option value='".$currency->currencyID."'  >".$currency->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="credit">
						<div class="row">
								<div class="col-lg-6">
								
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Proveedor de Credito</label>
											<div class="col-lg-8">
												<!--
												<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
												-->
												<select name="txtReference1" id="txtReference1" class="select2">
														<option value="0"></option>		
														<?php
														if($listProvider)
														foreach($listProvider as $ws){
															if($ws->entityID == $objTransactionMaster->reference1)
																echo "<option value='".$ws->entityID."' selected>".$ws->firstName." ".$ws->lastName."</option>";
															else 
																echo "<option value='".$ws->entityID."' >".$ws->firstName." ".$ws->lastName."</option>";
														}
														?>
												</select>
											</div>
									</div>
									
									
								</div>
								<div class="col-lg-6">
								
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Primer Pago</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="text" name="txtDateFirst" id="txtDateFirst" value="<?php echo $objTransactionMaster->transactionOn2; ?>" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>
									
									
								</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"></label>
										<div class="col-lg-8">
											 <label class="label-change-switch" id="txtLabelIsDesembolsoEfectivo">Es un desembolso en efectivo?</label>
											 <br/>
											 <div class="switch" data-on="success" data-off="warning">
												<input class="toggle"controls-row type="checkbox" checked id="txtCheckDeEfectivo" />
											 </div>																
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"></label>
										<div class="col-lg-8">
											
											<label class="label-change-switch" id="txtLabelIsReportSinRiesgo">Reportar a SinRiesgo</label>
											<br/>
											
											<div class="switch" data-on="success" data-off="warning">
												<?php
												if($objTransactionMasterDetailCredit->reference2 == 1){
												?>
													<input class="toggle"controls-row type="checkbox" checked id="txtCheckReportSinRiesgo" name="txtCheckReportSinRiesgo" value="1"  />																									
												<?php
												}
												else{
												?>
													<input class="toggle"controls-row type="checkbox"  id="txtCheckReportSinRiesgo" name="txtCheckReportSinRiesgo" value="1"  />
												<?php
												}
												?>
											</div>																
										</div>
										
								</div>
								
								
								
							</div>
							<div class="col-lg-6">
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">% De Gasto.</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtFixedExpenses" id="txtFixedExpenses" value="<?php echo $objTransactionMasterDetailCredit->reference1; ?>">
											
											<a href="#" class="btn btn-primary  gap-right10" data-toggle="popover" data-placement="bottom" 
											data-content="Ejemplo: Del Interese de cada cuota, se multiplica por este % para saber de cuanto es la comision para FID-Local, este numero debe ser #0 o mayor que #1" title="" data-original-title="% de Gastos Fijo:">Ayuda:</a>
										</div>
								</div>
								
								
								<div class="form-group hide">
										<label class="col-lg-4 control-label" for="normal">Primer Linea del Protocolo.</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtLayFirstLineProtocolo" id="txtLayFirstLineProtocolo" value="<?php echo $objTransactionMasterDetailCredit->reference3; ?>">
											
											<a href="#" class="btn btn-primary  gap-right10" data-toggle="popover" data-placement="bottom" 
											data-content="Ejemplo: 5" title="" 
											data-original-title="Tenor:">Ayuda:</a>
											
											
										</div>
								</div>
								
								
							</div>
						</div>
					</div>
					
					
					<div class="tab-pane fade" id="dropdown">
						
							
						
					</div>
					<div class="tab-pane fade" id="dropdown-file">
						
					</div>
				</div>    
		
				<br/>
				
				<div class="row">
					<div class="col-lg-12">
						<h3>Detalle:</h3>
						<table id="tb_transaction_master_detail" class="table table-bordered">
							<thead>
							  <tr>
								<th></th>
								<th></th>
								<th></th>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>U/M</th>
								<th>Cantidad</th>
								<th>Precio</th>
								<th>Total</th>
								<th></th>
							  </tr>
							</thead>
							<tbody id="body_tb_transaction_master_detail">
							</tbody>
						</table>
						
					</div><!-- End .col-lg-12  --> 
				</div><!-- End .row-fluid  -->
				<input class="form-control"  type="hidden"  name="txtStatusID" id="txtStatusID" value="<?php echo $objTransactionMaster->statusID; ?>" >
				
				<a href="#" class="btn btn-flat btn-info" id="btnNewItem" ><i class="icon16 i-print"></i> AGREGAR PRO</a>
				<a href="#" class="btn btn-flat btn-danger" id="btnDeleteItem" ><i class="icon16 i-print"></i> ELIMINAR PRO</a>	
				
				
				

				<div class="btn-group">
					<button class="btn btn-flat btn-success dropdown-toggle" data-toggle="dropdown"><i class="icon16 i-print"></i> PRODUCTO <span class="caret"></span></button>
					<ul class="dropdown-menu">
							<li><a href="#" id="btnNewItemCatalog" >NUEVO PRODUCTO</a></li>						
							<li><a href="#" id="btnRefreshDataCatalogo" >ACTUALIZAR CATALOGO</a></li>
					</ul>
				</div>



				<a href="<?php echo site_url(); ?>app_invoice_billing/index" id="btnBack" class="btn btn-flat btn-inverse" ><i class="icon16 i-rotate"></i> REGRESAR</a>    				
				<a href="<?php echo site_url(); ?>app_invoice_billing/add.aspx" class="btn btn-flat btn-info" id="btnNew"><i class="icon16 i-checkmark-4"></i> NUEVA FAC</a>
				<a href="#" class="btn btn-flat btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> ELIMINAR FAC</a>	
				<a href="#" class="btn btn-flat btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> IMPRIMIR</a>
				<?php
				$counter = 0;
				if($objListWorkflowStage)
				foreach($objListWorkflowStage as $ws){					
					$counter++;
					if($counter == 1){
					echo "<a href='#' class='btn btn-flat btn-warning btnAcept'  data-valueworkflow='".$ws->workflowStageID."'   > <i class='icon16 i-checkmark-4'></i>  ".$ws->name."</a> ";							
					}
					else{
					echo "<a href='#' class='btn btn-flat btn-warning btnAcept' data-valueworkflow='".$ws->workflowStageID."'  > <i class='icon16 i-checkmark-4'></i>  ".$ws->name."</a> ";
					}
				}
				?>
				
				<input class="form-control"  type="text"  name="txtScanerCodigo" id="txtScanerCodigo" value="">
																
				
									
				

				<div class="row">
					<div class="col-lg-4">
						<div class="page-header">
							<h3>Ref.</h4>
						</div>
						<ul class="list-unstyled">
							<li><h3>CC: <span class="red-smooth">*</span></h3></li>
							<li><i class="icon16 i-arrow-right-3"></i>Resumen de la factura</li>                                                
						</ul>
					</div>
					 <div class="col-lg-4">
						<div class="page-header">
							<h3>Pago</h3>
						</div>
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th>INGRESO Cordoba</th>
									<td >
										<input type="text" id="txtReceiptAmount" name="txtReceiptAmount"  class="col-lg-12" value="<?php echo number_format($objTransactionMasterInfo->receiptAmount,2); ?>" style="text-align:right"/>
									</td>
								</tr>
								
								<tr>
									<th>INGRESO Dolares</th>
									<td>
										<input type="text" id="txtReceiptAmountDol" name="txtReceiptAmountDol"  class="col-lg-12" value="<?php echo number_format($objTransactionMasterInfo->receiptAmountDol,2); ?>" style="text-align:right"/>
									</td>
								</tr>
								<tr>
									<th>CAMBIO Cordoba</th>
									<td >
										<input type="text" id="txtChangeAmount" name="txtChangeAmount" readonly class="col-lg-12" value="" style="text-align:right"/>
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
									<th>SUB TOTAL</th>
									<td >
										<input type="text" id="txtSubTotal" name="txtSubTotal" readonly class="col-lg-12" value="" style="text-align:right"/>
									</td>
								</tr>
								<tr>
									<th>IVA</th>
									<td >
										<input type="text" id="txtIva" name="txtIva" readonly class="col-lg-12" value="" style="text-align:right"/>
									</td>
								</tr>
								<tr>
									<th>TOTAL</th>
									<td >
										<input type="text" id="txtTotal" name="txtTotal" readonly class="col-lg-12" value="" style="text-align:right"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div><!-- End .col-lg-6  --> 
				</div><!-- End .row-fluid  -->
				   
			</div>
			</form>
			<!-- /body -->
			
			<div id="modalDialogOpenPrimter" title="Formato de Impresion" class="dialog">
				<p>Seleccione el formato que desea imprimir la factura</p>
			</div>
			
		</div>
	</div>
</div>
<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<!--
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<a href="<?php echo site_url(); ?>app_invoice_billing/add.aspx" class="btn btn-info" id="btnNew"><i class="icon16 i-checkmark-4"></i> Nueva</a>
				<a href="<?php echo site_url(); ?>app_invoice_billing/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
				<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>									
				<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
				<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
			</div>
		</div> 
		-->
		<!-- /botonera -->
	</div>
	<!-- End #email  -->
</div>
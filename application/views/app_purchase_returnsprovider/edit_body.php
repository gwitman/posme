					<!-- botonera -->
					<div class="row"> 
                        <div id="email" class="col-lg-12">
							<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_purchase_returnsprovider/index.aspx" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
									<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>									
									<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /botonera -->
					
					
					
				    <div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
										
								<!-- titulo del movimiento-->
								<div class="panel-heading">
										<div class="icon"><i class="icon20 i-file"></i></div> 
										<h4>NUMERO:#<span class="invoice-num"><?php echo $objTM->transactionNumber;?></span></h4>
								</div>
								<!-- /titulo del movimiento-->
								
								<!-- formulario -->	
								<form id="form-new-transaction" name="form-new-transaction" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<!--tab menu-->
									<ul id="myTab" class="nav nav-tabs">
										<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>										
										<li>
											<a href="#profile" data-toggle="tab">Referencias.</a>
										</li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas<b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
												<li><a id="btnClickArchivo"  href="#dropdown-file" data-toggle="tab">Archivos</a></li>
											 </ul>
										</li>
									</ul>
									<!--tab menu-->
									
									<!--tab content-->
									<div class="tab-content">
										<!--tab content general-->
										<div class="tab-pane fade in active" id="home">	
											<div class="row">										
											<div class="col-lg-6">
													<input type="hidden" name="txtCompanyID" value="<?php echo $objTM->companyID; ?>">
													<input type="hidden" name="txtTransactionID" value="<?php echo $objTM->transactionID; ?>">
													<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTM->transactionMasterID; ?>">
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="datepicker">Fecha</label>
														<div class="col-lg-8">
															<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" value="<?php echo $objTM->transactionOn;?>">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="buttons">Proveedor</label>
														<div class="col-lg-8">
															<div class="input-group">
																<input type="hidden" id="txtProviderID" name="txtProviderID" value="<?php echo $objProvider->entityID; ?>">
																<input class="form-control" readonly id="txtProviderDescription" type="txtProviderDescription" value="<?php echo $objProvider->providerNumber."/".$objLegalDefault->comercialName ?>">
																
																<span class="input-group-btn">
																	<button class="btn btn-danger" type="button" id="btnClearProvider">
																		<i aria-hidden="true" class="i-undo-2"></i>
																		clear
																	</button>
																</span>
																<span class="input-group-btn">
																	<button class="btn btn-primary" type="button" id="btnSearchProvider">
																		<i aria-hidden="true" class="i-search-5"></i>
																		buscar
																	</button>
																</span>
																
															</div>
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
																		if($ws->workflowStageID == $objTM->statusID)
																		echo "<option value='".$ws->workflowStageID."' selected >".$ws->name."</option>";
																		else 
																		echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
																	}
																	?>
															</select>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="selectFilter">Bodega</label>
														<div class="col-lg-8">
															<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
																	<option></option>																
																	<?php
																	if($objListWarehouse)
																	foreach($objListWarehouse as $ws){
																		
																		if($ws->warehouseID == $objTM->sourceWarehouseID)
																			echo "<option value='".$ws->warehouseID."' selected>".$ws->name."</option>";
																		else
																			echo "<option value='".$ws->warehouseID."' >".$ws->name."</option>";
																	}
																	?>
															</select>
														</div>
													</div>
													
											</div>
											</div>
										</div>						
										<!--tab content general-->
										<div class="tab-pane fade" id="profile">
											<div class="row">
												<div class="col-lg-12">
												
												<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia1</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objTM->reference1; ?>">												
															</div>
													</div>	
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia2</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objTM->reference2; ?>">												
															</div>
													</div>											
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia3</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="<?php echo $objTM->reference3; ?>">												
															</div>
													</div>	
												
												</div>
											</div>
										</div>
										<!--tab content description-->
										<div class="tab-pane fade" id="dropdown">
											
												<div class="form-group">
		                                            <label class="col-lg-2 control-label" for="normal">Nota</label>
		                                            <div class="col-lg-6">
		                                                <textarea class="form-control"  id="txtDescription" name="txtDescription" rows="6"><?php echo $objTM->note; ?></textarea>
		                                            </div>
		                                        </div>
											
										</div>
										<!--tab content description-->
										<!--tab content file-->
										<div class="tab-pane fade" id="dropdown-file">
										</div>
										<!--tab content file-->
									</div>
									<!--tab content-->
									
									
									<br/>
									<a href="#" class="btn btn-flat btn-info" id="btnNewDetailTransaction" >Agregar</a>
									<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailTransaction" >Eliminar</a>
									
													
									<!-- detalle del movimiento-->
									<div class="row">
                                        <div class="col-lg-12">
                                            <h3>Detalle:</h3>
                                            <table id="tb_transaction_master_detail" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th width="45px"></th>
                                                    <th width="45px">Codigo</th>
                                                    <th width="40%">Nombre</th>
                                                    <th width="45px">U/M</th>
                                                    <th width="80px">Cantidad</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="body_detail_transaction">     
													<?php
													if($objTMD)
													foreach($objTMD as $i){
														?>
														<tr class="row_razon">
															<td>
																<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" >
																<input type="hidden" name="txtDetailItemID[]" id="txtDetailItemID" value="<?php echo $i->componentItemID; ?>"  class="classDetailItem" />
																<input type="hidden" name="txtDetailTransactionDetailID[]" value="<?php echo $i->transactionMasterDetailID; ?>"/>
															</td>
															<td><text id="txtCodigo"><?php echo $i->itemNumber; ?></text></td>
															<td><text id="txtNombre"><?php  echo $i->itemName; ?></text></td>
															<td><text id="txtUM"><?php  echo $i->unitMeasureName; ?></text></td>
															<td>
																		<input class="form-control txt-numeric"  type="text"  name="txtDetailQuantity[]"  value="<?php echo number_format($i->quantity,2); ?>">
																
															</td>
															<!--
															<td>
																
																		<input class="form-control"  type="text"  name="txtDetailLote[]"  value="<?php echo $i->reference1; ?>">
																
															</td>
															<td>
																
																		<input class="form-control"  type="text"  name="txtDetailVencimiento[]"  value="<?php echo $i->reference2; ?>">
																
															</td>
															<td>
																
																		<select name="txtDetailTipo[]" id="txtDetailTipoID"   class="select2 select2statusID">
																				<option></option>
																				<?php
																				if($objCatalogItemRazon)
																				foreach($objCatalogItemRazon as $cix){	
																					if( $cix->catalogItemID == $i->catalogStatusID )
																						echo "<option value='".$cix->catalogItemID."' selected >".$cix->display."</option>";
																					else
																						echo "<option value='".$cix->catalogItemID."'>".$cix->display."</option>";
																				}
																				?>
																		</select>						
																
															</td>
															-->
															
														</tr>
														<?php
													}													
													?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
									<!-- detalle del movimiento-->
									
                                </div>
								</form>
								<!-- formulario -->	
							</div>
						</div>
					</div>
					
					<!-- razones del mal estado-->
					<script type="text/template"  id="tmpl_row_razon">
							<tr class="row_razon">
								<td>
									<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" >
									<input type="hidden" name="txtDetailItemID[]" id="txtDetailItemID"  class="classDetailItem" />
									<input type="hidden" name="txtDetailTransactionDetailID[]" />
								</td>
								<td><text id="txtCodigo"></text></td>
                                <td><text id="txtNombre"></text></td>
                                <td><text id="txtUM"></text></td>
								<td>
												<input class="form-control txt-numeric"  type="text"  name="txtDetailQuantity[]"  value="">
									
								</td>
								<!--
								<td>
									
												<input class="form-control"  type="text"  name="txtDetailLote[]"  value="">
									
								</td>
								<td>
									
												<input class="form-control"  type="text"  name="txtDetailVencimiento[]"  value="">
									
								</td>
								<td>
									
											<select name="txtDetailTipo[]" id="txtDetailTipoID"   class="select2 select2statusID">
													<option></option>
													<?php
													if($objCatalogItemRazon)
													foreach($objCatalogItemRazon as $i){																					
														echo "<option value='".$i->catalogItemID."'>".$i->display."</option>";
													}
													?>
											</select>						
									
								</td>
								-->
								
							</tr>
					</script>
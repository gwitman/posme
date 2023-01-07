					<!--Botonera-->

					<div class="row">

                        <div id="email" class="col-lg-12">

                            <div class="email-bar" style="border-left:1px solid #c9c9c9">

                                <div class="btn-group pull-right">

									<a href="<?php echo site_url(); ?>app_inventory_inputunpost/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>

                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!--Botonera-->

					

					

				    <div class="row">

						<div class="col-lg-12">

							<div class="panel panel-default">

										

								<!-- titulo del movimiento-->

								<div class="panel-heading">

										<div class="icon"><i class="icon20 i-file"></i></div> 

										<h4>NUMERO:#<span class="invoice-num">00000000</span></h4>

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

												<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>

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

													

													<div class="form-group">

														<label class="col-lg-2 control-label" for="datepicker">Fecha</label>

														<div class="col-lg-8">

															<div id="datepicker" class="input-group date"  data-date-format="yyyy-mm-dd">

																<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" >

																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>

															</div>

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

													

													<div class="form-group">

														<label class="col-lg-2 control-label" for="selectFilter">Bodega</label>

														<div class="col-lg-8">

															<select name="txtWarehouseID" id="txtWarehouseID" class="select2">

																	<option></option>																

																	<?php

																	if($objListWarehouse)
																	foreach($objListWarehouse as $ws){
																		if($ws->number == $warehouseDefault)
																			echo "<option value='".$ws->warehouseID."' selected>".$ws->name."</option>";
																		else
																			echo "<option value='".$ws->warehouseID."' >".$ws->name."</option>";

																	}

																	?>

															</select>

														</div>

													</div>

													

													

													

													

											</div>

											<div class="col-lg-6">

											

													<div class="form-group">

														<label class="col-lg-4 control-label" for="buttons">Proveedor</label>

														<div class="col-lg-8">

															<div class="input-group">

																<input type="hidden" id="txtProviderID" name="txtProviderID" value="<?php echo $providerDefault->entityID; ?>">

																<input class="form-control" readonly id="txtProviderDescription" type="txtProviderDescription" value="<?php echo $providerDefault->providerNumber; ?> / <?php echo $providerNaturalDefault->firstName; ?>">

																

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

													

													

													<div class="form-group">

														<label class="col-lg-4 control-label" for="buttons">Ord. Compra</label>

														<div class="col-lg-8">

															<div class="input-group">

																<input type="hidden" id="txtTransactionMasterIDOrdenCompra" name="txtTransactionMasterIDOrdenCompra" value="">

																<input class="form-control" readonly id="txtTransactionNumberOrdenCompra" type="txtTransactionNumberOrdenCompra" value="">

																

																<span class="input-group-btn">

																	<button class="btn btn-danger" type="button" id="btnClearOrdenCompra">

																		<i aria-hidden="true" class="i-undo-2"></i>

																		clear

																	</button>

																</span>

																<span class="input-group-btn">

																	<button class="btn btn-primary" type="button" id="btnSearchOrdenCompra">

																		<i aria-hidden="true" class="i-search-5"></i>

																		buscar

																	</button>

																</span>

																

															</div>

														</div>

													</div>

													

													

											</div>

											</div>

										</div>			

										<div class="tab-pane fade" id="profile">

											<div class="row">

												<div class="col-lg-12">

												

												<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">Referencia1</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												

															</div>

													</div>	

													

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">Referencia2</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="">												

															</div>

													</div>											

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">Referencia3</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="">												

															</div>

													</div>	

												

												</div>

											</div>

										</div>

										<!--tab content general-->

										<!--tab content description-->

										<div class="tab-pane fade" id="dropdown">

											

												<div class="form-group">

		                                            <label class="col-lg-2 control-label" for="normal">Nota</label>

		                                            <div class="col-lg-6">

		                                                <textarea class="form-control"  id="txtDescription" name="txtDescription" rows="6"></textarea>

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

									<a href="#" class="btn btn-flat btn-success" id="btnNewItemCatalog" >Nuevo producto</a>
									<!-- detalle del movimiento-->

									<div class="row">

                                        <div class="col-lg-12">

                                            <h3>Detalle:</h3>

                                            <table id="tb_transaction_master_detail" class="table table-bordered">

                                                <thead>

                                                  <tr>

                                                    <th></th>
													<th>itemID</th>
													<th>transactionDetailID</th>
                                                    <th>Codigo</th>
                                                    <th>Nombre</th>
                                                    <th>U/M</th>
                                                    <th>Cantidad</th>
													<th>Costo</th>
													<th>Precio</th>													
													<th>Lote</th><!--9-->
													<th>Expiracion</th><!--10-->
													<th>Mas</th><!--11-->
													<th>Precio1</th><!--12-->
													<th>Precio2</th><!--13-->
                                                  </tr>

                                                </thead>

                                                <tbody id="body_detail_transaction">             

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

									<!-- detalle del movimiento-->

									<div class="row">

                                        <div class="col-lg-4">

											<div class="page-header">

                                                <h3>Ref.</h4>

                                            </div>

                                            <ul class="list-unstyled">

                                                <li><h3>CC: <span class="red-smooth">*</span></h3></li>

                                                <li><i class="icon16 i-arrow-right-3"></i>Resumen de la entrada</li>                                                

                                            </ul>

                                        </div>

                                        <div class="col-lg-4">

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

															<input type="text" id="txtSubTotal" name="txtSubTotal" readonly class="col-lg-12" value="0" style="text-align:right"/>

														</td>

                                                    </tr>

                                                    <tr>

                                                        <th>DESCUENTO</th>

                                                        <td >

															<input type="text" id="txtDiscount" name="txtDiscount"  class="col-lg-12" value="0" style="text-align:right"/>

														</td>

                                                    </tr>

													<tr>

                                                        <th>IVA</th>

                                                        <td >

															<input type="text" id="txtIva" name="txtIva"  class="col-lg-12" value="0" style="text-align:right"/>

														</td>

                                                    </tr>

													<tr>

                                                        <th>TOTAL</th>

                                                        <td >

															<input type="text" id="txtTotal" name="txtTotal" readonly class="col-lg-12" value="0" style="text-align:right"/>

														</td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div><!-- End .col-lg-6  --> 

                                    </div><!-- End .row-fluid  -->

                                    

									

                                </div>

								</form>

								<!-- formulario -->	

							</div>

						</div>

					</div>
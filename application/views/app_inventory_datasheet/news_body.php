					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_inventory_datasheet/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
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
										<h4>NUMERO:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-account-journal" name="form-new-account-journal" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<ul id="myTab" class="nav nav-tabs">
										<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
										<li><a href="#profile" data-toggle="tab">Detalle</a></li>
										
									</ul>
									
									<div class="tab-content">
										<div class="tab-pane fade in active" id="home">												
											<div class="row">										
												<div class="col-lg-6">
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Producto</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtItemID" name="txtItemID">
																	<input class="form-control" readonly id="txtItemIDDescription" type="txtItemIDDescription">
																	
																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="txtClearItemID">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="txtSearchItemID">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>
																	
																</div>
															</div>
														</div>
													
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Nombre</label>
																<div class="col-lg-8">
																	<input class="form-control"  type="text"  name="txtName" id="txtName" value="">												
																</div>
														</div>
														
												</div>
												<div class="col-lg-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Estado</label>
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
																<label class="col-lg-4 control-label" for="normal">Version</label>
																<div class="col-lg-8">
																	<input class="form-control" readonly  type="text"  name="txtVersion" id="txtVersion" value="">												
																</div>
														</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-12">
													<div class="form-group">
														<label class="col-lg-2 control-label" for="normal">Descripcion</label>
														<div class="col-lg-10">
															<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="6"></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="profile">
									
											<br/>
											<a href="#" class="btn btn-flat btn-info" id="btnNewDetail" >Agregar</a>
											<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetail" >Eliminar</a>
											<!-- detalle del movimiento-->
											<div class="row">
												<div class="col-lg-12">
													<table id="tb_item_data_sheet_detail" class="table table-bordered">
														<thead>
														  <tr>
															<th></th>
															<th>itemDataSheetID</th>
															<th>itemID</th>
															<th>itemDataSheetDetailID</th>
															<th>itemRelatedID</th>
															<th>Codigo</th>
															<th>Nombre</th>
															<th>U/M</th>
															<th>Cantidad</th>
															<th>Costo</th>
															<th>CC</th>
														  </tr>
														</thead>
														<tbody id="tb_item_data_sheet_detail_body">
														</tbody>
													</table>
												</div>
											</div>
											<!-- detalle del movimiento-->
											
											
										</div>										
									</div>    
							 
                                </div>
								</form>
								<!-- /body -->
							</div>
						</div>
					</div>
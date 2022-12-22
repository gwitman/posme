					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>app_transaction_config/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>									                                
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>                                    
                                </div>
                            </div> 
                            <!-- /botonera -->
						</div>
					</div>
					
					<!-- formulario -->
					<form id="form-edit-document" name="form-new-rol" class="form-horizontal" role="form">
					<fieldset>	
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
														<div class="col-lg-6">
															<div class="form-group">
																	<label class="col-lg-4 control-label" for="normal">Nombre</label>
																	<div class="col-lg-8">
																		<input class="form-control" readonly="readonly" type="text" name="txtName" id="txtName" value="<?php echo $objTransaction->name;?>">
																		<input type="hidden" name="txtCompanyID" id="companyID" value="<?php echo $objTransaction->companyID;?>" />
																		<input type="hidden" name="txtTransactionID" id="txtTransactionID" value="<?php echo $objTransaction->transactionID;?>" />												
																		<input type="hidden" name="txtCmd" id="txtCmd" value="edit" />
																	</div>
															</div>
															<div class="form-group">
																<label class="col-lg-4 control-label" for="selectFilter">Workflow</label>
																<div class="col-lg-8">
																	<select name="txtWorkflowID" readonly="readonly" id="txtWorkflowID" class="select2">
																			<?php
																			echo "<option value='".$objWorkflow->workflowID."'>".$objWorkflow->name."</option>";
																			?>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="col-lg-4 control-label" for="selectFilter">Tipo de Comprobante</label>
																<div class="col-lg-8">
																	<select name="txtJournalTypeID" id="txtJournalTypeID" class="select2">
																			<option></option>												
																			<?php
																			if($objListJournalType)
																			foreach($objListJournalType as $i){
																				if($i->catalogItemID == $objTransaction->journalTypeID)
																				echo "<option value='".$i->catalogItemID."' selected >".$i->name."</option>";
																				else
																				echo "<option value='".$i->catalogItemID."'>".$i->name."</option>";
																			}
																			?>
																	</select>
																</div>
															</div>
															<div class="form-group">
																	<label class="col-lg-4 control-label" for="normal">Referencia 1</label>
																	<div class="col-lg-8">
																		<input class="form-control"  type="text" name="txtReference1" id="txtReference1" value="<?php echo $objTransaction->reference1;?>">																											
																	</div>
															</div>
															<div class="form-group">
																	<label class="col-lg-4 control-label" for="normal">Referencia 2</label>
																	<div class="col-lg-8">
																		<input class="form-control"  type="text" name="txtReference2" id="txtReference2" value="<?php echo $objTransaction->reference2;?>">																	
																	</div>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="form-group">
																	<label class="col-lg-4 control-label" for="normal">Referencia 3</label>
																	<div class="col-lg-8">
																		<input class="form-control"  type="text" name="txtReference3" id="txtReference3" value="<?php echo $objTransaction->reference3;?>">
																	</div>
															</div>
															<div class="form-group">
																	<label class="col-lg-4 control-label" for="normal">Afectar el Inventario</label>
																	<div class="col-lg-8">
																		<input class="form-control" readonly="readonly"  type="text" name="txtSignInventory" id="txtSignInventory" value="<?php echo $objTransaction->signInventory;?>">												
																	</div>
															</div>
															<div class="form-group">
																<label class="col-lg-4 control-label" for="checkboxes">Contabiliza</label>													
																<label class="checkbox-inline">
																	<input type="checkbox" id="txtIsContabilize" name="txtIsContabilize" value="1" <?php echo $objTransaction->isCountable == 1 ? "checked" : "";?> >
																</label>													
															</div>
															<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Descripcion</label>
																<div class="col-lg-8">
																	<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"><?php echo $objTransaction->description;?></textarea>
																</div>
															</div>
														</div>
											</div>
										</div><!-- End .row-fluid  -->  
									</div>
									<!-- /body -->
                                </div>
							</div>
							
                        </div>
                        <!-- End #email  -->
                    </div>
					<div class="row">
						<div class="col-lg-12">
							<ul id="myTab" class="nav nav-tabs">
								<li class="active"><a href="#causal" data-toggle="tab">Causales</a></li>
								<li><a href="#profile" data-toggle="tab">Configuracion</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade in active" id="causal">
									<a href="#" class="btn btn-flat btn-info" id="btnNewDetailCausal" >Agregar</a>									
									<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailCausal" >Eliminar</a>									
						
									<table id="ListElementCausal" class="table table-striped">
										<thead>
											<tr>
												<th style="width:19%">Sucursal</th>
												<th style="width:30%">Nombre</th>
												<th style="width:17%">Principal</th>
												<th style="width:17%">Bodega Origen</th>
												<th style="width:17%">Bodega Destino</th>												
											</tr>
										</thead>
										<tbody id="tbody_detail_causal">
											<?php												
												if($objListTransactionCausal)
												foreach($objListTransactionCausal as $i){
													echo "<tr>";														
														echo "<td>";
																echo "<span>";
																echo $i->branch;
																echo '<input type="hidden"  name="txtCausalID[]" value="'.$i->transactionCausalID.'" />';
																echo '<input type="hidden"  name="txtCausalBranchID[]" value="'.$i->branchID.'" />';
																echo '<input type="hidden"  name="txtCausalName[]" value="'.$i->name.'" />';
																echo '<input type="hidden"  name="txtCausalIsDefault[]" value="'.($i->isDefault ? "true" : "false").'" />';
																echo '<input type="hidden"  name="txtCausalWarehouseSourceID[]" value="'.$i->warehouseSourceID.'" />';
																echo '<input type="hidden"  name="txtCausalWarehouseTargetID[]" value="'.$i->warehouseTargetID.'" />';
																echo "</span>";
														echo "</td>";																	
														echo "<td>";
																echo $i->name;
														echo "</td>";
														echo "<td>";
																echo $i->isDefault == 1 ? "si" : "no";
														echo "</td>";
														echo "<td>";
																echo $i->warehouseSourceDescription;
														echo "</td>";
														echo "<td>";
																echo $i->warehouseTargetDescription;
														echo "</td>";
													echo "</tr>";
												}
											?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="profile">
									<div class="row">
										
										<div class="col-lg-12">
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Causal</label>
													<div class="col-lg-8">
														<input class="form-control" readonly="readonly"  type="text" name="txtCausalDescription" id="txtCausalDescription" value="">												
													</div>
											</div>																					
											
											<div class="form-group">
												<label class="col-lg-4 control-label" for="selectFilter">Concepto</label>
												<div class="col-lg-8">
													<select name="txtConceptID" id="txtConceptID" class="select2">
															<option></option>	
															<?php
															if($objListTransactionConcept)
															foreach($objListTransactionConcept as $i){
																echo "<option value='".$i->conceptID."'>".$i->name."</option>";
															}
															?>
													</select>
												</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="selectFilter">D|C</label>
													<div class="col-lg-8">
														<select name="txtSignProfileDetail" id="txtSignProfileDetail" class="select2">
																<option value="D">Debito</option>	
																<option value="C">Credito</option>	
														</select>
													</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-4 control-label" for="selectFilter">Cuenta</label>
												<div class="col-lg-8">
													<select name="txtAccountID" id="txtAccountID" class="select2">
															<option></option>																																
															<?php
															if($objListAccount)
															foreach($objListAccount as $i){
																echo "<option value='".$i->accountID."'>".$i->accountNumber."</option>";
															}
															?>
													</select>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-4 control-label" for="selectFilter">Centro de Costo</label>
												<div class="col-lg-8">
													<select name="txtCC" id="txtCC" class="select2">
															<option></option>
															<?php
															if($objListCenterCost)
															foreach($objListCenterCost as $i){
																echo "<option value='".$i->classID."'>".$i->number."</option>";
															}
															?>
													</select>
												</div>
											</div>
											
										</div>
									</div>
									
									<a href="#" class="btn btn-flat btn-info" id="btnNewDetailConfig" >Agregar</a>									
									<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailConfig" >Eliminar</a>									
						
									<table id="ListElementConfig" class="table table-striped">
										<thead>
											<tr>
												<th style="width:32%">Concepto</th>
												<th style="width:17%">Cuenta Debito</th>
												<th style="width:17%">CC Debito</th>
												<th style="width:17%">Cuenta Credito</th>
												<th style="width:17%">CC Credito</th>												
											</tr>
										</thead>
										<tbody id="tbody_detail_config">											
										</tbody>
									</table>
								</div>
							</div>  
						</div>
					</div>						
					</fieldset> 
					</form>
					<!-- /formulario -->
                    <!-- End .row-fluid  -->
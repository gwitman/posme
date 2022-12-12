					<!-- botonera -->
					<div class="row"> 
                        <div id="email" class="col-lg-12">                        	
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">									
									<a href="<?php echo site_url(); ?>app_rrhh_employee/index.aspx" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
										
								<!-- titulo de comprobante-->
								<div class="panel-heading">
										<div class="icon"><i class="icon20 i-file"></i></div> 
										<h4>CODIGO:#<span class="invoice-num"><?php echo $objEmployee->employeNumber; ?></span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-rrhh-employee" name="form-new-rrhh-employee" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<ul id="myTab" class="nav nav-tabs">
										<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
										<li><a href="#profile" data-toggle="tab">Referencias.</a></li>
										<li><a href="#profile-phones" data-toggle="tab">Telefonos.</a></li>
										<li><a href="#profile-email" data-toggle="tab">Email.</a></li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li><a href="#dropdown" data-toggle="tab">Direccion</a></li>
												<li><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
											 </ul>
										</li>
									</ul>
									
									<div class="tab-content">
										<div class="tab-pane fade in active" id="home">	
											<div class="row">										
											<div class="col-lg-6">
												
													<div class="form-group">
															
															<label class="col-lg-4 control-label" for="normal">Primer Nombre</label>
															<div class="col-lg-8">
																<input type="hidden" id="txtCompanyID" name="txtCompanyID" value="<?php echo $objEmployee->companyID; ?>">
																<input type="hidden" id="txtBranchID" name="txtBranchID" value="<?php echo $objEmployee->branchID; ?>">
																<input type="hidden" id="txtEntityID" name="txtEntityID" value="<?php echo $objEmployee->entityID; ?>">
																<input class="form-control"  type="text"  name="txtFirstName" id="txtFirstName" value="<?php echo $objNatural->firstName; ?>">												
															</div>
													</div>
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Segundo Nombre</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtLastName" id="txtLastName" value="<?php echo $objNatural->lastName; ?>">
															</div>
													</div>						
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Tipo Identificacion</label>
														<div class="col-lg-8">
															<select name="txtIdentificationTypeID" id="txtIdentificationTypeID" class="select2">
																	<option></option>																
																	<?php
																	
																	if($objListIdentificationType)
																	foreach($objListIdentificationType as $ws){
																		if($ws->catalogItemID == $objEmployee->identificationTypeID )
																		echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																		$count++;
																	}
																	?>
															</select>
														</div>
													</div>
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Identificacion</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtIdentification" id="txtIdentification" value="<?php echo $objEmployee->numberIdentification; ?>">
															</div>
													</div>
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">INSS</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtSocialSecurityNumber" id="txtSocialSecurityNumber" value="<?php echo $objEmployee->socialSecurityNumber; ?>">												
															</div>
													</div>	
													<div class="form-group">
														<label class="col-lg-4 control-label" for="buttons">Jefe Inmediato</label>
														<div class="col-lg-8">
															<div class="input-group">
																<input type="hidden" id="txtParentEmployeeID" name="txtParentEmployeeID" value="<?php echo $objEmployee->parentEmployeeID; ?>">
																<input class="form-control" readonly id="txtParentDescription" type="txtParentDescription" value="<?php echo $objParentEmployee == null  ?  ""  : ($objParentEmployee->employeNumber." / ".$objParentNatural->firstName." ".$objParentNatural->lastName); ?>">
																<span class="input-group-btn">
																	<button class="btn btn-danger" type="button" id="btnClearEmployeeParent">
																		<i aria-hidden="true" class="i-undo-2"></i>
																		clear
																	</button>
																</span>
																<span class="input-group-btn">
																	<button class="btn btn-primary" type="button" id="btnSearchEmployeeParent">
																		<i aria-hidden="true" class="i-search-5"></i>
																		buscar
																	</button>
																</span>
															</div>
														</div>
													</div>[
]											</div>
											<div class="col-lg-6">
											
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Estado</label>
														<div class="col-lg-8">
															<select name="txtStatusID" id="txtStatusID" class="select2">
																	<option></option>																
																	<?php
																	if($objListWorkflowStage)
																	foreach($objListWorkflowStage as $ws){
																		if($ws->workflowStageID == $objEmployee->statusID)
																			echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
																		else
																			echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
																	}
																	?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Clasificacion</label>
														<div class="col-lg-8">
															<select name="txtClasificationID" id="txtClasificationID" class="select2">
																	<option></option>																
																	<?php
																	
																	if($objListClasificationID)
																	foreach($objListClasificationID as $ws){
																		if($ws->catalogItemID == $objEmployee->clasificationID )
																		echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																		$count++;
																	}
																	?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Contrato</label>
														<div class="col-lg-8">
															<select name="txtCategoryID" id="txtCategoryID" class="select2">
																	<option></option>																
																	<?php
																	
																	if($objListCategoryID)
																	foreach($objListCategoryID as $ws){
																		if($ws->catalogItemID == $objEmployee->categoryID )
																		echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																		
																	}
																	?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Grado</label>
														<div class="col-lg-8">
															<select name="txtTypeEmployeeID" id="txtTypeEmployeeID" class="select2">
																	<option></option>																
																	<?php
																	
																	if($objListTypeEmployeeID)
																	foreach($objListTypeEmployeeID as $ws){
																		if($ws->catalogItemID == $objEmployee->typeEmployeeID )
																		echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																		
																	}
																	?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Departamento</label>
														<div class="col-lg-8">
															<select name="txtDepartamentID" id="txtDepartamentID" class="select2">
																	<option></option>																
																	<?php
																	
																	if($objListDepartamentID)
																	foreach($objListDepartamentID as $ws){
																		if($ws->catalogItemID == $objEmployee->departamentID )
																		echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																	
																	}
																	?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Area</label>
														<div class="col-lg-8">
															<select name="txtAreaID" id="txtAreaID" class="select2">
																	<option></option>
																	<?php
																	
																	if($objListAreaID)
																	foreach($objListAreaID as $ws){
																		if($ws->catalogItemID == $objEmployee->areaID )
																		echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																		
																	}
																	?>
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
															<label class="col-lg-4 control-label" for="selectFilter">Pais</label>
															<div class="col-lg-8">
																<select name="txtCountryID" id="txtCountryID" class="select2">
																		<option></option>																
																		<?php
																		if($objListCountry)
																		foreach($objListCountry as $ws){
																			if($ws->catalogItemID == $objEmployee->countryID)
																				echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																			else
																				echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Departamento</label>
															<div class="col-lg-8">
																<select name="txtStateID" id="txtStateID" class="select2">
																		<option></option>				
																		<?php
																		if($objListState)
																		foreach($objListState as $ws){
																			if($ws->catalogItemID == $objEmployee->stateID)
																				echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																			else
																				echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Municipio</label>
															<div class="col-lg-8">
																<select name="txtCityID" id="txtCityID" class="select2">
																		<option></option>
																		<?php
																		if($objListCity)
																		foreach($objListCity as $ws){
																			if($ws->catalogItemID == $objEmployee->cityID)
																				echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																			else
																				echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
												</div>
												<div class="col-lg-6">
													
													<div class="form-group">
														<label class="col-lg-4 control-label" for="datepicker">Fecha Inicio</label>
														<div class="col-lg-8">
															<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="<?php echo $objEmployee->startOn; ?>">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-4 control-label" for="datepicker">Fecha Fin</label>
														<div class="col-lg-8">
															<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="<?php echo $objEmployee->endOn; ?>">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
														</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia1</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objEmployee->reference1; ?>">												
															</div>
													</div>											
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia2</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objEmployee->reference2; ?>">												
															</div>
													</div>				
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Costo/H</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtHourCost" id="txtHourCost" value="<?php echo $objEmployee->hourCost; ?>">												
															</div>
													</div>				
													
													
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="profile-phones">
											<div class="row">													
												<div class="col-lg-6">
													<br/>
													<a href="#" class="btn btn-flat btn-info" id="btnNewPhones" >Agregar</a>
													<a href="#" class="btn btn-flat btn-danger" id="btnDeletePhones" >Eliminar</a>
													
													<table id="tb_detail_phone" class="table table-bordered">
														<thead>
														  <tr>
															<th></th>
															<th>entityPhoneID</th>
															<th>entityPhoneTypeID</th>
															<th>Tipo</th>
															<th>Numero</th>
															<th>Primario</th>
														  </tr>
														</thead>
														<tbody id="body_detail_phone">             
														</tbody>
													</table>
													
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="profile-email">
											<div class="row">													
												<div class="col-lg-6">
													<br/>
													<a href="#" class="btn btn-flat btn-info" id="btnNewEmail" >Agregar</a>
													<a href="#" class="btn btn-flat btn-danger" id="btnDeleteEmail" >Eliminar</a>
													<table id="tb_detail_email" class="table table-bordered">
														<thead>
														  <tr>
															<th></th>
															<th>entityEmailID</th>
															<th>Email</th>															
															<th>Primario</th>
														  </tr>
														</thead>
														<tbody id="body_detail_email">             
														</tbody>
													</table>
													
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="dropdown">
											
												<div class="form-group">
		                                            <label class="col-lg-4 control-label" for="normal">Direccion</label>
		                                            <div class="col-lg-8">
		                                                <textarea class="form-control"  id="txtAddress" name="txtAddress" rows="6"><?php echo $objEmployee->address; ?></textarea>
		                                            </div>
		                                        </div>
											
										</div>
										<div class="tab-pane fade" id="dropdown-file">
											
										</div>
									</div>    
									
                                </div>
								</form>
								<!-- /body -->
							</div>
						</div>
					</div>
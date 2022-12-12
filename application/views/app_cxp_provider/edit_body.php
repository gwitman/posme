					<!-- botonera -->
					<div class="row"> 
                        <div id="email" class="col-lg-12">                        	
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">									
									<a href="<?php echo site_url(); ?>app_cxp_provider/index.aspx" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
										<h4>CODIGO:#<span class="invoice-num"><?php echo $objProvider->providerNumber; ?></span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-cxp-provider" name="form-new-cxp-provider" class="form-horizontal" role="form">
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
													<input type="hidden" name="txtCompanyID" value="<?php echo $objProvider->companyID; ?>">
													<input type="hidden" name="txtBranchID" value="<?php echo $objProvider->branchID; ?>">
													<input type="hidden" name="txtEntityID" value="<?php echo $objProvider->entityID; ?>">
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Primer Nombre</label>
															<div class="col-lg-8">
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
															<label class="col-lg-4 control-label" for="normal">Nombre Legal</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtLegalName" id="txtLegalName" value="<?php echo $objLegal->legalName; ?>">												
															</div>
													</div>
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Nombre Comercial</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtCommercialName" id="txtCommercialName" value="<?php echo $objLegal->comercialName; ?>">
															</div>
													</div>													
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Tipo Identificacion</label>
														<div class="col-lg-8">
															<select name="txtIdentificationTypeID" id="txtIdentificationTypeID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListIdentificationType)
																	foreach($objListIdentificationType as $ws){
																		if($ws->catalogItemID == $objProvider->identificationTypeID )
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
																<input class="form-control"  type="text"  name="txtIdentification" id="txtIdentification" value="<?php echo $objProvider->numberIdentification; ?>">
															</div>
													</div>
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Es Local</label>
															<div class="col-lg-8">
																<input type="checkbox"   name="txtIsLocal" id="txtIsLocal" value="1" <?php echo ($objProvider->isLocal ? "checked" : ""); ?> >
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
																		if( $ws->workflowStageID == $objProvider->statusID)
																		echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
																		else 
																		echo "<option value='".$ws->workflowStageID."'>".$ws->name."</option>";
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
																	$count = 0;
																	if($objListClasificationID)
																	foreach($objListClasificationID as $ws){
																		if($ws->catalogItemID == $objProvider->providerClasificationID )
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
														<label class="col-lg-4 control-label" for="selectFilter">Categoria</label>
														<div class="col-lg-8">
															<select name="txtCategoryID" id="txtCategoryID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListCategoryID)
																	foreach($objListCategoryID as $ws){
																		if($ws->catalogItemID == $objProvider->providerCategoryID )
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
														<label class="col-lg-4 control-label" for="selectFilter">Tipo de Pago</label>
														<div class="col-lg-8">
															<select name="txtTypePayID" id="txtTypePayID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListPayConditionID)
																	foreach($objListPayConditionID as $ws){
																		if($ws->catalogItemID == $objProvider->payConditionID )
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
														<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
														<div class="col-lg-8">
															<select name="txtProviderTypeID" id="txtProviderTypeID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListProviderTypeID)
																	foreach($objListProviderTypeID as $ws){
																		if($ws->catalogItemID == $objProvider->providerType )
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
														<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
														<div class="col-lg-8">
															<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
																	<option></option>
																	<?php
																	if($objListCurrency)
																	foreach($objListCurrency as $ws){
																		if($ws->currencyID == $objProvider->currencyID )
																		echo "<option value='".$ws->currencyID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->currencyID."'  >".$ws->name."</option>";
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
																		$count = 0;
																		if($objListCountry)
																		foreach($objListCountry as $ws){
																			if( $ws->catalogItemID == $objProvider->countryID)
																			echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
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
																		$count = 0;
																		if($objListState)
																		foreach($objListState as $ws){
																			if( $ws->catalogItemID == $objProvider->stateID)
																			echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
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
																		$count = 0;
																		if($objListCity)
																		foreach($objListCity as $ws){
																			if( $ws->catalogItemID == $objProvider->cityID)
																			echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
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
															<label class="col-lg-4 control-label" for="normal">Referencia1</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objProvider->reference1; ?>">												
															</div>
													</div>											
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia2</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objProvider->reference2; ?>">												
															</div>
													</div>				
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Distancia</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtDistancia" id="txtDistancia" value="<?php echo $objProvider->distancia; ?>">												
															</div>
													</div>				
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">No. Dias</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtDayDelevery" id="txtDayDelevery" value="<?php echo $objProvider->deleveryDay; ?>">												
															</div>
													</div>	
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">No. Dias #</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtDayDeleveryReal" id="txtDayDeleveryReal" value="<?php echo $objProvider->deleveryDayReal; ?>">												
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
		                                                <textarea class="form-control"  id="txtAddress" name="txtAddress" rows="6"><?php echo $objProvider->address; ?></textarea>
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
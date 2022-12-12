					<!-- botonera -->

					<div class="row"> 

                        <div id="email" class="col-lg-12">                        	

                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                

                                <div class="btn-group pull-right">									

									<?php 
										if($callback == "false")
										{
											?>
												<a href="<?php echo site_url(); ?>app_cxc_customer/index.aspx" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
												<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
												<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
											<?php

										}
										else{
											?>
											<?php
										}
									?>     
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

										<h4>CODIGO:#<span class="invoice-num"><?php echo $objCustomer->customerNumber; ?></span></h4>

								</div>

								<!-- /titulo de comprobante-->

								

								<!-- body -->	

								<form id="form-new-cxc-customer" name="form-new-cxc-customer" class="form-horizontal" role="form">

								<div class="panel-body printArea"> 

								

									<ul id="myTab" class="nav nav-tabs">

										<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>

										<li><a href="#profile" data-toggle="tab">Referencias.</a></li>

										<li><a href="#profile-phones" data-toggle="tab">Telefonos.</a></li>

										<li><a href="#profile-email" data-toggle="tab">Email.</a></li>

										<li><a href="#profile-cxc" data-toggle="tab">CXC.</a></li>

										<li><a href="#profile-cxc-line" data-toggle="tab">CXC Lineas.</a></li>

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

													<input type="hidden" name="txtCompanyID" value="<?php echo $objCustomer->companyID; ?>">

													<input type="hidden" name="txtBranchID" value="<?php echo $objCustomer->branchID; ?>">

													<input type="hidden" name="txtEntityID" value="<?php echo $objCustomer->entityID; ?>">

													<input type="hidden" name="txtCallback" value="<?php echo $callback; ?>"/>


													<div class="form-group">

														<label class="col-lg-4 control-label" for="datepicker">Nacimiento</label>

														<div class="col-lg-8">

															<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">

																<input size="16"  class="form-control" type="text" name="txtBirthDate" id="txtBirthDate" value="<?php echo $objCustomer->birthDate; ?>">

																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>

															</div>

														</div>

													</div>

													<div class="form-group">

															<label class="col-lg-4 control-label text-primary" for="normal">*Primer Nombre</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtFirstName" id="txtFirstName" value="<?php echo $objNatural->firstName; ?>">

															</div>

													</div>

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">*Segundo Nombre</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtLastName" id="txtLastName" value="<?php echo $objNatural->lastName; ?>">												

															</div>

													</div>

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">*Nombre Legal</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtLegalName" id="txtLegalName" value="<?php echo $objLegal->legalName; ?>">					

															</div>

													</div>

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">*Nombre Comercial</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtCommercialName" id="txtCommercialName" value="<?php echo $objLegal->comercialName; ?>">

															</div>

													</div>

													<div class="form-group">

														<label class="col-lg-4 control-label" for="selectFilter">Sexo</label>

														<div class="col-lg-8">

															<select name="txtSexoID" id="txtSexoID" class="select2">

																	<option></option>																

																	<?php

																	$count = 0;

																	if($objListSexoID)

																	foreach($objListSexoID as $ws){

																		if( $ws->catalogItemID == $objCustomer->sexoID )

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

														<label class="col-lg-4 control-label" for="selectFilter">Tipo Identificacion</label>

														<div class="col-lg-8">

															<select name="txtIdentificationTypeID" id="txtIdentificationTypeID" class="select2">

																	<option></option>																

																	<?php

																	$count = 0;

																	if($objListIdentificationType)

																	foreach($objListIdentificationType as $ws){

																		if( $ws->catalogItemID == $objCustomer->identificationType )

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

															<label class="col-lg-4 control-label text-primary" for="normal">*Identificacion</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtIdentification" id="txtIdentification" value="<?php echo $objCustomer->identification; ?>">												

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

																		if ($ws->workflowStageID == $objCustomer->statusID)

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

																	$count = 0;

																	if($objListClasificationID)

																	foreach($objListClasificationID as $ws){

																		if($ws->catalogItemID == $objCustomer->clasificationID )

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

															<select name="txtCustomerTypeID" id="txtCustomerTypeID" class="select2">

																	<option></option>																

																	<?php

																	$count = 0;

																	if($objListCustomerTypeID)

																	foreach($objListCustomerTypeID as $ws){

																		if($ws->catalogItemID == $objCustomer->customerTypeID )

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

																		if($ws->catalogItemID == $objCustomer->categoryID )

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

														<label class="col-lg-4 control-label" for="selectFilter">Sub Categoria</label>

														<div class="col-lg-8">

															<select name="txtSubCategoryID" id="txtSubCategoryID" class="select2">

																	<option></option>																

																	<?php

																	$count = 0;

																	if($objListSubCategoryID)

																	foreach($objListSubCategoryID as $ws){

																		if($ws->catalogItemID == $objCustomer->subCategoryID )

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

														<label class="col-lg-4 control-label" for="selectFilter">Estado Civil</label>

														<div class="col-lg-8">

															<select name="txtCivilStatusID" id="txtCivilStatusID" class="select2">

																	<option></option>																

																	<?php

																	$count = 0;

																	if($objListEstadoCivilID)

																	foreach($objListEstadoCivilID as $ws){

																		if( $ws->catalogItemID == $objNatural->statusID )

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

														<label class="col-lg-4 control-label" for="selectFilter">Profesion u Oficio</label>

														<div class="col-lg-8">

															<select name="txtProfesionID" id="txtProfesionID" class="select2">

																	<option></option>																

																	<?php

																	$count = 0;

																	if($objListProfesionID)

																	foreach($objListProfesionID as $ws){

																		if( $ws->catalogItemID == $objNatural->profesionID )

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

														<label class="col-lg-4 control-label" for="selectFilter">Ver Buro</label>

														<div class="col-lg-8">

										

															<div class="btn-group">

																<button class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Score </font></font><span class="caret"></span></button>

																<ul class="dropdown-menu">

																	<?php

																	if($objCustomerSinRiesgo)

																	foreach($objCustomerSinRiesgo as $ws){

																		echo '<li><a href="'.site_url().'app_cxc_record/index.aspx?file_exists='.$ws->file.'"  target="_blank"  ><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$ws->file.'</font></font></a></li>';

																	}

																	?>

																</ul>

															</div>


														</div>

													</div>

														

											</div>

											</div>

										</div>

										<div class="tab-pane fade" id="profile-cxc-line">

											<div class="row">

												<div class="col-lg-12">

													<br/>

													<a href="#" class="btn btn-flat btn-info" id="btnNewLine" >Agregar</a>

													<a href="#" class="btn btn-flat btn-success" id="btnEditLine" >Editar</a>

													<a href="#" class="btn btn-flat btn-danger" id="btnDeleteLine" >Eliminar</a>

													

													<div class="btn-group">

														<button class="btn btn-flat btn-warning">mostrar</button>

														<button class="btn btn-flat dropdown-toggle btn-warning" data-toggle="dropdown"><span class="caret"></span></button>

														<ul class="dropdown-menu">

															<li class="active"><a href="#" class="toggle-vis" data-column="14"><i class="fa fa-tint"></i>Linea</a></li>

															<li class="active"><a href="#" class="toggle-vis" data-column="15"><i class="fa fa-tint"></i>Numero</a></li>

															<li class="active"><a href="#" class="toggle-vis" data-column="16"><i class="fa fa-tint"></i>Limite</a></li>

															<li class="divider"></li>

															<li class="active"><a href="#" class="toggle-vis" data-column="17"><i class="fa fa-tint"></i>Balance</a></li>

															<li class="active"><a href="#" class="toggle-vis" data-column="18"><i class="fa fa-tint"></i>Estado</a></li>

															<li class="active"><a href="#" class="toggle-vis" data-column="19"><i class="fa fa-tint"></i>Moneda</a></li>

															<li class="divider"></li>

															<li class="active"><a href="#" class="toggle-vis" data-column="21"><i class="fa fa-tint"></i>Plan</a></li>

															<li class="active"><a href="#" class="toggle-vis" data-column="23"><i class="fa fa-tint"></i>Interes</a></li>

															<li class="active"><a href="#" class="toggle-vis" data-column="22"><i class="fa fa-tint"></i>Frecuencia</a></li>

															<li class="active"><a href="#" class="toggle-vis" data-column="24"><i class="fa fa-tint"></i>No Pagos</a></li>

														</ul>

													</div>

							

													<table id="tb_detail_credit_line" class="table table-bordered">

														<thead>

														  <tr>

															<th></th>

															<th>customerCreditLineID</th>

															<th>creditLineID</th>

															<th>currencyID</th>

															<th>statusID</th>

															<th>InteresYear</th>

															<th>InterestPay</th>

															<th>TotalPay</th>

															<th>TotalDefeated</th>

															<th>DateOpen</th>

															<th>PeriodPay</th>

															<th>DateLastPay</th>

															<th>Term</th>

															<th>Note</th>															

															<th>Linea</th>

															<th>Numero</th>

															<th>Limite</th>

															<th>Balance</th>

															<th>Estado</th>

															<th>Moneda</th>

															<th>Tipo Amortization</th>

															<th>Plan</th>

															<th>Frecuencia</th>

															<th>Interes Anual</th>

															<th>No. Pagos</th>

														  </tr>

														</thead>

														<tbody id="body_detail_line">             

														</tbody>

													</table>

												</div>

												

											</div>

										</div>

										<div class="tab-pane fade" id="profile-cxc">

											<div class="row">

												<div class="col-lg-6">

													<div class="form-group">

														<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>

														<div class="col-lg-8">

															<select name="txtCurrencyID" id="txtCurrencyID" class="select2">

																	<option></option>

																	<?php

																	if($objListCurrency)

																	foreach($objListCurrency as $ws){

																		if($ws->currencyID == $objCustomer->currencyID )

																		echo "<option value='".$ws->currencyID."' selected >".$ws->name."</option>";

																		else

																		echo "<option value='".$ws->currencyID."'  >".$ws->name."</option>";

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

																	if($objListTypePay)

																	foreach($objListTypePay as $ws){

																		if($ws->catalogItemID == $objCustomer->typePay )

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

														<label class="col-lg-4 control-label" for="selectFilter">Condicion de Pago</label>

														<div class="col-lg-8">

															<select name="txtPayConditionID" id="txtPayConditionID" class="select2">

																	<option></option>																

																	<?php

																	$count = 0;

																	if($objListPayConditionID)

																	foreach($objListPayConditionID as $ws){

																		if($ws->catalogItemID == $objCustomer->payConditionID )

																		echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";

																		else

																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";

																		$count++;

																	}

																	?>

															</select>

														</div>

													</div>

												

												</div>	

												

												<div class="col-lg-6">

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">Ingresos US$</label>

															<div class="col-lg-8">

																<input class="form-control txt-numeric"  type="text"  name="txtIncomeDol" id="txtIncomeDol" value="<?php echo number_format($objCustomerCredit->incomeDol,2); ?>">												

															</div>

													</div>

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">Limite Credito US$</label>

															<div class="col-lg-8">

																<input class="form-control txt-numeric"  type="text"  name="txtLimitCreditDol" id="txtLimitCreditDol" value="<?php echo number_format($objCustomerCredit->limitCreditDol,2); ?>">

															</div>

													</div>

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">Balance US$</label>

															<div class="col-lg-8">

																<input class="form-control txt-numeric"  type="text"  readonly name="txtBalanceDol" id="txtBalanceDol" value="<?php echo number_format($objCustomerCredit->balanceDol,2); ?>">												

															</div>

													</div>

													

													<div class="form-group">

														<label class="col-lg-4 control-label" for="buttons">Cuenta</label>

														<div class="col-lg-8">

															<div class="input-group">

																<input type="hidden" id="txtAccountID" name="txtAccountID" value="<?php if($objAccount) echo $objAccount->accountID; ?>">

																<input class="form-control" readonly id="txtAccountIDDescription"  type="txtAccountIDDescription" value="<?php  if($objAccount) echo $objAccount->accountNumber." ".$objAccount->name ; ?>">

																

																<span class="input-group-btn">

																	<button class="btn btn-danger" type="button" id="btnClearAccount">

																		<i aria-hidden="true" class="i-undo-2"></i>

																		clear

																	</button>

																</span>

																<span class="input-group-btn">

																	<button class="btn btn-primary" type="button" id="btnSearchAccount">

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

											

												<div class="col-lg-6">

														<div class="form-group">

															<label class="col-lg-4 control-label text-primary" for="selectFilter">*Pais</label>

															<div class="col-lg-8">

																<select name="txtCountryID" id="txtCountryID" class="select2">

																		<option></option>																

																		<?php

																		$count = 0;

																		if($objListCountry)

																		foreach($objListCountry as $ws){

																			if( $ws->catalogItemID == $objCustomer->countryID)

																			echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";

																			else

																			echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";

																		}

																		?>

																</select>

															</div>

														</div>

														<div class="form-group">

															<label class="col-lg-4 control-label text-primary" for="selectFilter">*Departamento</label>

															<div class="col-lg-8">

																<select name="txtStateID" id="txtStateID" class="select2">

																		<option></option>

																		<?php

																		$count = 0;

																		if($objListState)

																		foreach($objListState as $ws){

																			if( $ws->catalogItemID == $objCustomer->stateID)

																			echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";

																			else

																			echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";

																		}

																		?>

																</select>

															</div>

														</div>

														<div class="form-group">

															<label class="col-lg-4 control-label text-primary" for="selectFilter">*Municipio</label>

															<div class="col-lg-8">

																<select name="txtCityID" id="txtCityID" class="select2">

																		<option></option>				

																		<?php

																		$count = 0;

																		if($objListCity)

																		foreach($objListCity as $ws){

																			if( $ws->catalogItemID == $objCustomer->cityID)

																			echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";

																			else

																			echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";

																		}

																		?>

																</select>

															</div>

														</div>

														<div class="form-group">

																<label class="col-lg-4 control-label" for="normal">Domicilio</label>

																<div class="col-lg-8">

																	<input class="form-control"  type="text"  name="txtLocation" id="txtLocation" value="<?php echo $objCustomer->location; ?>">

																</div>

														</div>

												</div>

												<div class="col-lg-6">

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">Referencia1</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objCustomer->reference1; ?>">												

															</div>

													</div>											

													<div class="form-group">

															<label class="col-lg-4 control-label" for="normal">Referencia2</label>

															<div class="col-lg-8">

																<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objCustomer->reference2; ?>">			

															</div>

													</div>	

													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Tipo de Firma</label>
														<div class="col-lg-8">
															<select name="txtTypeFirmID" id="txtTypeFirmID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListTypeFirmID)
																	foreach($objListTypeFirmID as $ws){

																		if( $ws->catalogItemID == $objCustomer->typeFirm)

																		echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";

																		else

																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";

																		$count++;

																	}
																	?>
															</select>
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

		                                                <textarea class="form-control"  id="txtAddress" name="txtAddress" rows="6"><?php echo $objCustomer->address; ?></textarea>

		                                            </div>

		                                        </div>

											

										</div>

										<div class="tab-pane fade" id="dropdown-file">

											

										</div>

									</div>    

									

                                </div>

								</form>

								<!-- /body -->

								

								<!-- ui dialog -->

								<div id="windowDialogConstante" title="Amort Constante" class="dialog">

									<p>

										//*******************************<br/>

										//193 Amortizacion Constante<br/>

									</p>

									<p style="text-align:justify">

										Cuota Decreciente<br/>

										Capital Constante<br/>										

										Interes Decreciente

									</p>

								</div>

								<div id="windowDialogAleman" title="Amort Aleman" class="dialog">

									<p>

										//*******************************<br/>

										//195 Amortizacion Aleman<br/>

									</p>

									<p style="text-align:justify">

										Primera Cuota solo el Interes<br/>

										Cuota Constante<br/>

										Capital Creciente<br/>										

										Interes Decreciente

									</p>

								</div>

								<div id="windowDialogFrancesa" title="Amort Francesa" class="dialog">

									<p>

										//*******************************<br/>

										//194 Amortizacion Frances<br/>

									</p>

									<p style="text-align:justify">

										Cuota Constante<br/>

										Capital Creciente<br/>										

										Interes Decreciente

									</p>

								</div>

								<div id="windowDialogAmericano" title="Amort Americano" class="dialog">

									<p>

										//*******************************<br/>

										//196 Amortizacion Americano<br/>

									</p>

									<p style="text-align:justify">

										Ultima Cuota solo el Interes y Capital<br/>

										Cuota Constante<br/>

										Capital 0<br/>										

										Interes Constante

									</p>

								</div>

									

							</div>

						</div>

					</div>

					

						
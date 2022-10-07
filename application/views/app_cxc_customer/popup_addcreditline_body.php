					<div class="row">

                        <div id="email" class="col-lg-12">

                            <div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">

                                <div class="row">

                                    <div class="email-content col-lg-12">

                                        <form id="form-new-rol" class="form-horizontal" >

													

													

													<div class="form-group">

														<label class="col-lg-2 control-label" for="preapend">Numero</label>

														<div class="col-lg-10">

															<div class="input-group">

																<span class="input-group-addon">#</span>

																<input class="form-control" id="txtNumber" readonly type="text" placeholder="">

															</div>

														</div>

													</div>

													

													

													<div class="form-group">

														<label class="col-lg-4 control-label" for="selectFilter">Linea</label>

														<div class="col-lg-8">

															<select name="txtCreditLineID"  id="txtCreditLineID" class="select2">

																	<option></option>																

																	<?php
																	$count = 0;
																	if($objListLine)
																	foreach($objListLine as $ws){
																		if($count == 0)
																		echo "<option value='".$ws->creditLineID."' selected>".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->creditLineID."' >".$ws->name."</option>";
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

																	if($objCurrencyList)
																	foreach($objCurrencyList as $ws){
																		if($ws->name == $objParameterCurrenyDefault)
																		echo "<option value='".$ws->currencyID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->currencyID."' >".$ws->name."</option>";
																	}

																	?>

															</select>

														</div>

													</div>

													

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

														<label class="col-lg-2 control-label" for="preapend">Limite de Credito</label>

														<div class="col-lg-10">

															<div class="input-group">

																<span class="input-group-addon">$</span>

																<input class="form-control txt-numeric" id="txtLimitCredit" type="text" placeholder="" value="80000.00">

															</div>

														</div>

													</div>

													

													<div class="form-group">

														<label class="col-lg-2 control-label" for="preapend">Interes Anual</label>

														<div class="col-lg-10">

															<div class="input-group">

																<span class="input-group-addon">%</span>

																<input class="form-control txt-numeric" id="txtInteresYear" type="text" value="0.00">

															</div>

														</div>

													</div>

													

													

													<div class="form-group">

														<label class="col-lg-4 control-label" for="selectFilter">Frecuencia de Pago</label>

														<div class="col-lg-8">

															<select name="txtPeriodPay" id="txtPeriodPay" class="select2">

																	<option></option>																

																	<?php

																	if($objListPay)

																	foreach($objListPay as $ws){
																		
																		if($ws->catalogItemID == $objParameterPayDefault)
																		echo "<option value='".$ws->catalogItemID."' selected data-val='".$ws->sequence."' >".$ws->name."</option>";
																		else																			
																		echo "<option value='".$ws->catalogItemID."'  data-val='".$ws->sequence."' >".$ws->name."</option>";																	
																		

																	}

																	?>

															</select>

														</div>

													</div>

													

													<div class="form-group">

														<label class="col-lg-4 control-label" for="selectFilter">Plan</label>

														<div class="col-lg-8">

															<select name="txtTypeAmorization" id="txtTypeAmorization" class="select2">

																	<option></option>																

																	<?php

																	if($objListTypeAmortization)
																	foreach($objListTypeAmortization as $ws){
																		if($ws->catalogItemID == $objParameterAmortizationDefault)
																		echo "<option value='".$ws->catalogItemID."' selected data-val='".$ws->sequence."' >".$ws->name."</option>";
																		else																			
																		echo "<option value='".$ws->catalogItemID."'  data-val='".$ws->sequence."' >".$ws->name."</option>";

																	}

																	?>

															</select>

														</div>

													</div>

												

													<div class="form-group">

														<label class="col-lg-2 control-label" for="preapend">No Pagos</label>

														<div class="col-lg-10">

															<div class="input-group">

																<input class="form-control txt-numeric" id="txtTerm" type="text" placeholder="" value="18">

															</div>

														</div>

													</div>

													

													<div class="form-group">

														<label class="col-lg-2 control-label" for="preapend">Nota</label>

														<div class="col-lg-10">

															<div class="input-group">

																<input class="form-control" id="txtNote" type="text" placeholder="" value="Ninguna">

															</div>

														</div>

													</div>

													



										</form>

                                    </div>

                                </div><!-- End .row-fluid  -->                            

                            </div>

                        </div><!-- End #email  -->

                    </div><!-- End .row-fluid  -->

					


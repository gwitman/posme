					<div class="row">
                        <div id="email" class="col-lg-12">
                            <div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
                                <div class="row">
                                    <div class="email-content col-lg-12">
                                        <form id="form-new-rol" class="form-horizontal" >
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="selectFilter">Tipo</label>
														<div class="col-lg-10">
															<select name="txtEntityPhoneTypeID" id="txtEntityPhoneTypeID" class="select2">																	
																	<?php
																	$count = 0;
																	if($objListPhoneTypeID)
																	foreach($objListPhoneTypeID as $ws){
																		if($count == 0 )
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
														<label class="col-lg-2 control-label" for="preapend">Telefono</label>
														<div class="col-lg-10">
															<div class="input-group">
																<span class="input-group-addon">#</span>
																<input class="form-control" id="txtEntityPhoneNumber" type="text" placeholder="">
															</div>
														</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Es Primario</label>
															<div class="col-lg-5">
																<input type="checkbox"  name="txtIsPrimary" id="txtIsPrimary" value="1" >
															</div>
													</div>
													
										</form>
                                    </div>
                                </div><!-- End .row-fluid  -->                            
                            </div>
                        </div><!-- End #email  -->
                    </div><!-- End .row-fluid  -->
					

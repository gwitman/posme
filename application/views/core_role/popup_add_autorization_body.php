					<div class="row">
                        <div id="email" class="col-lg-12">
                            <div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
                                <div class="row">
                                    <div class="email-content col-lg-12">
                                        <form id="form-new-rol" class="form-horizontal" >
												<div class="form-group">
													<label class="col-lg-2 control-label" for="selectFilter">Autorizacion</label>
													<div class="col-lg-10">
														<select name="txtComponentAutorizationID" id="txtComponentAutorizationID" class="select2">
															   <option></option>														   
															   <?php
																	foreach($listComponentAutorization as $i){
																		echo "<option value='".$i->componentAutorizationID."'>".$i->name."</option>";
																	}
																?>
														</select>
													</div>
												</div>
										</form>
                                    </div>
                                </div><!-- End .row-fluid  -->                            
                            </div>
                        </div><!-- End #email  -->
                    </div><!-- End .row-fluid  -->
					

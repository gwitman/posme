					<div class="row">
                        <div id="email" class="col-lg-12">
                            <div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
                                <div class="row">
                                    <div class="email-content col-lg-12">
                                        <form id="form-new-rol" class="form-horizontal" >
												<div class="form-group">
													<label class="col-lg-2 control-label" for="selectFilter">Elemento</label>
													<div class="col-lg-10">
														<select name="txtElementID" id="txtElementID" class="select2">
															   <option></option>														   
															   <?php
																	foreach($listMenuElement as $element){
																		echo "<option value='".$element->elementID."'>".$element->orden." >>> ".$element->display."</option>";
																	}
																?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-2 control-label" for="selectFilter">Seleccion</label>
													<div class="col-lg-10">
														<select name="txtSelectedID" id="txtSelectedID" class="select2">
																<option></option>														   
																<option value="<?php echo PERMISSION_NONE; ?>">No puede ver ninguno registro</option>
																<option value="<?php echo PERMISSION_ALL; ?>" selected>Puede ver todos los registro</option>
																<option value="<?php echo PERMISSION_BRANCH; ?>">Puede ver todos los registro de la sucursal unicamente</option>
																<option value="<?php echo PERMISSION_ME; ?>">Puede ver unicamente los registro creados por el</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-2 control-label" for="selectFilter">Edicion</label>
													<div class="col-lg-10">
														<select name="txtEditedID" id="txtEditedID" class="select2">
																<option></option>														   
																<option value="<?php echo PERMISSION_NONE; ?>">No puede editar ninguno registro</option>
																<option value="<?php echo PERMISSION_ALL; ?>" selected >Puede editar todos los registro</option>
																<option value="<?php echo PERMISSION_BRANCH; ?>">Puede editar todos los registro de la sucursal unicamente</option>
																<option value="<?php echo PERMISSION_ME; ?>">Puede editar unicamente los registro creados por el</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-2 control-label" for="selectFilter">Eliminacion</label>
													<div class="col-lg-10">
														<select name="txtDeletedID" id="txtDeletedID" class="select2">
																<option></option>														   
																<option value="<?php echo PERMISSION_NONE; ?>">No puede eliminar ninguno registro</option>
																<option value="<?php echo PERMISSION_ALL; ?>" selected >Puede eliminar todos los registro</option>
																<option value="<?php echo PERMISSION_BRANCH; ?>">Puede eliminar todos los registro de la sucursal unicamente</option>
																<option value="<?php echo PERMISSION_ME; ?>">Puede eliminar unicamente los registro creados por el</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-2 control-label" for="selectFilter">Insercion</label>
													<div class="col-lg-10">
														<select name="txtInsertedID" id="txtInsertedID" class="select2">
																<option></option>														   
																<option value="<?php echo PERMISSION_NONE; ?>">No puede Ingresar ninguno registro</option>
																<option value="<?php echo PERMISSION_ALL; ?>" selected >Puede Ingresar todos los registro</option>
																<option value="<?php echo PERMISSION_BRANCH; ?>">Puede Ingresar todos los registro de la sucursal unicamente</option>
																<option value="<?php echo PERMISSION_ME; ?>">Puede Ingresar unicamente los registro creados por el</option>
														</select>
													</div>
												</div>
										</form>
                                    </div>
                                </div><!-- End .row-fluid  -->                            
                            </div>
                        </div><!-- End #email  -->
                    </div><!-- End .row-fluid  -->
					

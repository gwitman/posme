					<div class="row"> 
                        <div id="email" class="col-lg-12">
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>core_user/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>                                    
                                </div>
                            </div> 
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
						
							<div class="panel panel-default">
								<!-- panel widget -->
                                <div class="panel-heading">
	                                  <div class="icon"><i class="icon20 i-cube"></i></div> 
	                                  <h4>Formulario de Datos</h4>
	                                  <a href="#" class="minimize"></a>
                                </div>
                                <!-- /panel widget -->[
]								<!-- body widget -->
                                <div class="panel-body noPadding">
									<!--tab-->
									<ul id="myTab" class="nav nav-tabs">
                                        <li class="active"><a href="#home1" data-toggle="tab">Informacion General</a></li>
                                        <li><a href="#profile1" data-toggle="tab">Bodegas</a></li>
                                    </ul>
									<form id="form-new-rol" name="form-new-rol" class="form-horizontal" role="form">
									<div class="tab-content">
										<!--Datos Principales-->
                                        <div class="tab-pane fade in active" id="home1">
											<div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
														<!-- row widget 1 -->
														<div class="row">
															<div class="email-content col-lg-12">	
																<form id="form-new-rol" name="form-new-rol" class="form-horizontal" role="form">
																	<fieldset>			
																		<div class="form-group">
																				<label class="col-lg-2 control-label" for="normal">Nickname</label>
																				<div class="col-lg-10">
																					<input class="form-control"  type="text" name="txtNickname" id="txtNickname" value="">												
																				</div>
																		</div>
																		
																		<div class="form-group">
																				<label class="col-lg-2 control-label" for="normal">Password</label>
																				<div class="col-lg-10">
																					<input class="form-control"  type="password" name="txtPassword" id="txtPassword" value="">												
																				</div>
																		</div>
																			
																		<div class="form-group">
																				<label class="col-lg-2 control-label" for="normal">Email</label>
																				<div class="col-lg-10">
																					<input class="form-control"  type="text" name="txtEmail" id="txtEmail" value="">												
																				</div>
																		</div>[
]																		<div class="form-group">
																			<label class="col-lg-2 control-label" for="selectFilter">Rol</label>
																			<div class="col-lg-10">
																				<select name="txtRoleID" id="txtRoleID" class="select2">
																						<option></option>
																						<?php
																						foreach($objListRoles as $i){
																						echo "<option value='".$i->roleID."'>".$i->name."</option>";
																						}
																						?>																
																				</select>
																			</div>
																		</div>
																		
																		<div class="form-group">
																			<label class="col-lg-2 control-label" for="buttons">ENTIDAD</label>
																			<div class="col-lg-5">
																				<div class="input-group">
																					<input type="hidden" id="txtEmployeeID" name="txtEmployeeID">
																					<input class="form-control" readonly id="txtEmployeeDescription" type="txtEmployeeDescription">																
																					
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
]																		
																	</fieldset> 
															</div>
														</div>
											</div>
										</div>
										<!--Bodegas-->
										<div class="tab-pane fade" id="profile1">													
														<a href="#" class="btn btn-flat btn-info" id="btnNewDetailWarehouse" >Agregar</a>
														<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailWarehouse" >Eliminar</a>
											
														<table id="ListElementWarehouse" class="table table-striped">
															<thead>
																<tr>
																	<th>Lista de Bodegas</th>															
																</tr>
															</thead>
															<tbody id="tbody_detail_warehouse">		
															</tbody>
														</table>
										</div>
									</div>									
									</form>
								</div>
							</div>  
                        </div><!-- End #email  -->
                    </div><!-- End .row-fluid  -->
					<!-- body -->
					<div class="row">
						<!-- row --> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- row-botonera-->							
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>core_role/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>                                    
                                </div>
                            </div> 							
							<!-- /row-botonera-->
						</div>
					</div>
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
                                
                                	<!-- tab widget -->
                                    <ul id="myTab" class="nav nav-tabs">
                                        <li class="active"><a href="#home1" data-toggle="tab">Informacion General</a></li>
                                        <li><a href="#profile1" data-toggle="tab">Autorizaciones</a></li>
                                    </ul>
                                    <!-- /tab widget -->
                                    
                                    <!-- body widget form -->
									<form id="form-new-rol" name="form-new-rol" class="form-horizontal" role="form">
                                    <div class="tab-content">
                                    	<!-- tab home widget -->
                                        <div class="tab-pane fade in active" id="home1">
                                            <div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
                                            	<!-- row widget 1 -->
				                                <div class="row">
				                                    <div class="email-content col-lg-12">				                                        
															<fieldset>			
																<div class="form-group">
																		<label class="col-lg-2 control-label" for="normal">Nombre</label>
																		<div class="col-lg-10">
																			<input class="form-control"  type="text" name="txtName" id="txtName" value="">												
																		</div>
																</div>
																<div class="form-group">
																		<label class="col-lg-2 control-label" for="normal">Url Default</label>
																		<div class="col-lg-10">
																			<input class="form-control"  type="text" name="txtUrlDefault" id="txtUrlDefault" value="">												
																		</div>
																</div>						
																<div class="form-group">
																	<label class="col-lg-2 control-label" for="normal">Descripcion</label>
																	<div class="col-lg-10">
																		<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"></textarea>
																	</div>
																</div>
																<hr />
																<a href="#" class="btn btn-flat btn-info" id="btnNewDetail" >Agregar</a>
																<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetail" >Eliminar</a>									
																<table id="ListElement" class="table table-striped">
																	<thead>
																		<tr>
																			<th>Orden</th>
																			<th>Elemento</th>
																			<th>Seleccion</th>
																			<th>Insercion</th>
																			<th>Eliminacion</th>
																			<th>Edicion</th>
																		</tr>
																	</thead>
																	<tbody id="tbody_detail">																				
																	</tbody>
																</table>															  
															</fieldset>
				                                    </div>
				                                </div>
				                                <!-- /row widget 1 -->                            
				                            </div>
                                        </div>
                                        <!-- /tab home widget -->
                                        
                                        <!-- /tab profile1 widget -->
                                        <div class="tab-pane fade" id="profile1">
                                            	<a href="#" class="btn btn-flat btn-info" id="btnNewDetailAutorization" >Agregar</a>
												<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailAutorization" >Eliminar</a>									
									
												<table id="ListElementAutorization" class="table table-striped">
													<thead>
														<tr>
															<th>Autorizaciones</th>															
														</tr>
													</thead>
													<tbody id="tbody_detail_autorization">																				
													</tbody>
												</table>
                                        </div>
                                        <!-- /tab profile1 widget -->
                                        
                                    </div> 
                                    </form>
                                    <!-- /body widget form -->  
                                </div>
                                <!-- /body widget -->
                            </div>
                            <!-- /widget -->                            
                        </div>
                        <!-- /row  -->
                    </div>
                    <!-- /body  -->
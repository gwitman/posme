					<div class="row"> 
                        <div id="email" class="col-lg-12">
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo site_url(); ?>core_role/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
                                    <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>                                    
                                </div>
                            </div> 
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">[
]							<div class="panel panel-default">
                                <div class="panel-heading">
                                  <div class="icon"><i class="icon20 i-cube"></i></div> 
                                  <h4>Formulario de Datos</h4>
                                  <a href="#" class="minimize"></a>
                                </div><!-- End .panel-heading -->[
]                                <div class="panel-body noPadding">
                                    <ul id="myTab" class="nav nav-tabs">
                                        <li class="active"><a href="#home1" data-toggle="tab">Informacion General</a></li>
                                        <li><a href="#profile1" data-toggle="tab">Autorizaciones</a></li>
                                    </ul>
									<form id="form-new-rol" name="form-new-rol" class="form-horizontal" role="form">
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="home1">
				                            <div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
				                                <div class="row">
				                                    <div class="email-content col-lg-12">
				                                       
															<fieldset>			
																<div class="form-group">
																		<label class="col-lg-2 control-label" for="normal">Nombre</label>
																		<div class="col-lg-10">
																			<input class="form-control"  type="text" name="txtName" id="txtName" value="<?php echo $objRole->name;?>">												
																			<input type="hidden" name="companyID" id="companyID" value="<?php echo $objRole->companyID;?>" />
																			<input type="hidden" name="branchID" id="branchID" value="<?php echo $objRole->branchID;?>" />
																			<input type="hidden" name="roleID" id="roleID" value="<?php echo $objRole->roleID;?>" />
																		</div>
																</div>
																
																<div class="form-group">
																		<label class="col-lg-2 control-label" for="normal">Url Default</label>
																		<div class="col-lg-10">
																			<input class="form-control"  type="text" name="txtUrlDefault" id="txtUrlDefault" value="<?php echo $objRole->urlDefault;?>">												
																		</div>
																</div>
																								
																<div class="form-group">
																	<label class="col-lg-2 control-label" for="normal">Descripcion</label>
																	<div class="col-lg-10">
																		<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"><?php echo $objRole->description;?></textarea>
																	</div>
																</div>
				
													
																<hr /> 
																
																<a href="#" class="btn btn-flat btn-info" id="btnNewDetail" >Agregar</a>
																<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetail" >Eliminar</a>									
													
																<table id="ListElement" class="table table-striped">
																	<thead>
																		<tr>
																			<th style="width:15%">Orden</th>
																			<th style="width:15%">Elemento</th>
																			<th style="width:17%">Seleccion</th>
																			<th style="width:17%">Insercion</th>
																			<th style="width:17%">Eliminacion</th>
																			<th style="width:17%">Edicion</th>
																		</tr>
																	</thead>
																	<tbody id="tbody_detail">	
																		<?php
																		$descriptionSelected[-1] = "No puede ver ningun registro";
																		$descriptionSelected[0] = "Puede ver todos los registros";
																		$descriptionSelected[1] = "Puede ver todos los registros de la sucursal unicamente";
																		$descriptionSelected[2] = "Puede ver unicamente los registros creados por el";
																		
																		$descriptionEdited[-1] = "No pude editar ningun registro";
																		$descriptionEdited[0] = "Puede editar todos los registros";
																		$descriptionEdited[1] = "Puede editar todos los registros de la sucursal";
																		$descriptionEdited[2] = "Puede editar unicamente los registros creados por el";
																		
																		$descriptionInserted[-1] = "No pude insertar ningun registro";
																		$descriptionInserted[0] = "Puede insertar todos los registros";
																		$descriptionInserted[1] = "Puede insertar todos los registros de la sucursal";
																		$descriptionInserted[2] = "Puede insertar unicamente los registros creados por el";
																		
																		
																		$descriptionDeleted[-1] = "No puede eliminar ningun registro";
																		$descriptionDeleted[0] = "Puede eliminar todos los registros";
																		$descriptionDeleted[1] = "Puede eliminar todos los registros de la sucursal";
																		$descriptionDeleted[2] = "Puede eliminar unicamente los registros creados por el";
																		 
																		if($objUserPermission)
																		foreach($objUserPermission as $item){
																			echo "<tr>";
																				echo "<td>";
																						echo $item->orden;
																				echo "</td>";
																				echo "<td>";
																						echo "<span>";
																						echo $item->display;
																						echo '<input type="hidden" id="txtElementID"  name="txtElementID[]" value="'.$item->elementID.'" />';
																						echo '<input type="hidden" id="txtSelectedID" name="txtSelectedID['.$item->elementID.']" value="'.$item->selected.'" />';
																						echo '<input type="hidden" id="txtInsertedID" name="txtInsertedID['.$item->elementID.']" value="'.$item->inserted.'" />';
																						echo '<input type="hidden" id="txtDeletedID"  name="txtDeletedID['.$item->elementID.']" value="'.$item->deleted.'" />';
																						echo '<input type="hidden" id="txtEditedID"   name="txtEditedID['.$item->elementID.']" value="'.$item->edited.'" />';
																						echo "</span>";
																				echo "</td>";																	
																				echo "<td>";
																						echo $descriptionSelected[$item->selected];
																				echo "</td>";
																				echo "<td>";
																						echo $descriptionInserted[$item->inserted];
																				echo "</td>";
																				echo "<td>";
																						echo $descriptionDeleted[$item->deleted];
																				echo "</td>";
																				echo "<td>";
																						echo $descriptionEdited[$item->edited];
																				echo "</td>";
																			echo "</tr>";
																		}
																		?>
																	</tbody>
																</table>
															  
															</fieldset> 
														
				                                    </div>
				                                </div><!-- End .row-fluid  -->                            
				                            </div>
				                        </div>
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
																	<?php 
																		if($listComponentAutoriation)
																		foreach($listComponentAutoriation as $i){
																			echo "<tr>";																			
																				echo "<td>";
																					    echo "<span>";
																						echo "<input type='hidden' name='txtComponentAutorizationID[]' value='".$i->componentAutorizationID."' />";
																						echo $i->name;
																						echo "</span>";
																				echo "</td>";
																			echo "</tr>";
																		}
																	?>																				
														</tbody>
													</table>
	                                    </div>
                                    </div> 
                                    </form>   
                                </div><!-- End .panel-body -->
                            </div><!-- End .widget -->                            
                        </div><!-- End #email  -->
                    </div>
<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">   
				<?php 
					if($callback == "false")
					{
						?>
						<a href="<?php echo site_url(); ?>app_inventory_item/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
						<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
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
		<!-- /botonera -->
	</div>
	<!-- End #email  -->
</div>
<!-- End .row-fluid  -->
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
					
			<!-- titulo de comprobante-->
			<div class="panel-heading">
					<div class="icon"><i class="icon20 i-file"></i></div> 
					<h4>NUMERO:#<span class="invoice-num"><?php echo $objItem->itemNumber; ?></span></h4>
			</div>
			<!-- /titulo de comprobante-->
			
			<!-- body -->	
			<form id="form-new-account-journal" name="form-new-account-journal" class="form-horizontal" role="form"  >
			<div class="panel-body printArea"> 
			
				<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
					<li><a href="#profile" data-toggle="tab">Referencias</a></li>
					<li><a href="#warehouse" data-toggle="tab">Bodegas</a></li>
					<li><a href="#provider" data-toggle="tab">Proveedores</a></li>
					<li><a href="#concepts" data-toggle="tab">Conceptos</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
							<li><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
							<li><a id="btnPrice" href="#price" data-toggle="tab">Precios</a></li>
						 </ul>
					</li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
						<div class="col-lg-6">
								
								<div class="form-group">
										<label class="col-lg-4 control-label text-primary" for="normal">*Nombre</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtName" id="txtName" value="<?php echo htmlentities($objItem->name,ENT_QUOTES); ?>">												
											<input type="hidden" name="txtItemID" value="<?php echo $objItem->itemID; ?>"/>
											<input type="hidden" name="txtCompanyID" value="<?php echo $objItem->companyID; ?>"/>
											<input type="hidden" name="txtCallback" value="<?php echo $callback; ?>"/>
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label text-primary" for="normal">Barra</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtBarCode" id="txtBarCode" value="<?php echo $objItem->barCode; ?>">												
										</div>
								</div>
								
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Perecedero</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsPerishable" id="txtIsPerishable" value="1" <?php echo ($objItem->isPerishable == 1) ? "checked":""; ?> >
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Cantidad Zero</label> 
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsInvoiceQuantityZero" id="txtIsInvoiceQuantityZero" value="1"  <?php echo ($objItem->isInvoiceQuantityZero == 1) ? "checked":""; ?>  >
										</div>
								</div>

								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Servicio</label> 
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsServices" id="txtIsServices" value="1"  <?php echo ($objItem->isServices == 1) ? "checked":""; ?>  >
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">*Capacidad</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtCapacity" id="txtCapacity" value="<?php echo $objItem->capacity; ?>">												
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Cantidad</label>
										<div class="col-lg-8">
											<input class="form-control" disabled  type="text"  name="txtQuantity" id="txtQuantity" value="<?php echo $objItem->quantity; ?>">												
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">*Cantidad Minima</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtQuantityMin" id="txtQuantityMin" value="<?php echo $objItem->quantityMin; ?>">												
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">*Cantidad Maxima</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtQuantityMax" id="txtQuantityMax" value="<?php echo $objItem->quantityMax; ?>">												
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Costo</label>
										<div class="col-lg-8">
											<input class="form-control" disabled type="text"  name="txtCost" id="txtCost" value="<?php echo $objItem->cost; ?>">												
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">*SKU Compras</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtFactorBox" id="txtFactorBox" value="<?php echo $objItem->factorBox; ?>">												
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">*SKU Produccion</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtFactorProgram" id="txtFactorProgram" value="<?php echo $objItem->factorProgram; ?>">												
										</div>
								</div>
							
						</div>
						<div class="col-lg-6">
						
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">*Estado</label>
									<div class="col-lg-8">
										<select name="txtStatusID" id="txtStatusID" class="select2">
												<option></option>																
												<?php
												if($objListWorkflowStage)
												foreach($objListWorkflowStage as $ws){
													if($ws->workflowStageID == $objItem->statusID)
													echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
													else
													echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">*Categoria</label>
									<div class="col-lg-8">
										<select name="txtInventoryCategoryID" id="txtInventoryCategoryID" class="select2">
												<option></option>																
												<?php
												if($objListInventoryCategory)
												foreach($objListInventoryCategory as $ws){
													if($ws->inventoryCategoryID == $objItem->inventoryCategoryID )
													echo "<option value='".$ws->inventoryCategoryID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->inventoryCategoryID."'  >".$ws->name."</option>";
													
												}
												?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">*Familia</label>
									<div class="col-lg-8">
										<select name="txtFamilyID" id="txtFamilyID" class="select2">
												<option></option>																
												<?php
												if($objListFamily)
												foreach($objListFamily as $ws){
													if($ws->catalogItemID == $objItem->familyID)
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">*UM.</label>
									<div class="col-lg-8">
										<select name="txtUnitMeasureID" id="txtUnitMeasureID" class="select2">
												<option></option>
												<?php
												if($objListUnitMeasure)
												foreach($objListUnitMeasure as $ws){
													if($ws->catalogItemID == $objItem->unitMeasureID)
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">*Presentacion</label>
									<div class="col-lg-8">
										<select name="txtDisplayID" id="txtDisplayID" class="select2">
												<option></option>
												<?php
												if($objListDisplay)
												foreach($objListDisplay as $ws){
													if($ws->catalogItemID == $objItem->displayID)
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";																		
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">*UM. Presentacion</label>
									<div class="col-lg-8">
										<select name="txtDisplayUnitMeasureID" id="txtDisplayUnitMeasureID" class="select2">
												<option></option>
												<?php
												if($objListUnitMeasure)
												foreach($objListUnitMeasure as $ws){
													if($ws->catalogItemID == $objItem->displayUnitMeasureID)
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";																		
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";																		
												}
												?>
										</select>
									</div>
								</div>
							
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">*Bodega</label>
									<div class="col-lg-8">
										<select name="txtDefaultWarehouseID" id="txtDefaultWarehouseID" class="select2">
												<option></option>
												<?php
												if($objListWarehouse)
												foreach($objListWarehouse as $ws){
													if($ws->warehouseID == $objItem->defaultWarehouseID)
													echo "<option value='".$ws->warehouseID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->warehouseID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Barra</label>
									<div class="col-lg-8">
										<img width="200px" height="70px" src="<?php echo site_url(); ?>app_inventory_item/popup_add_renderimg/<?php echo $objItem->companyID; ?>/<?php echo $objComponent->componentID; ?>/<?php echo $objItem->itemID; ?>" />
									</div>
								</div>
								
								
								
								
						</div>
						</div>
					</div>
					<div class="tab-pane fade" id="profile">
					
							<div class="form-group">
									<label class="col-lg-2 control-label" for="normal">Referencia1</label>
									<div class="col-lg-5">
										<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objItem->reference1; ?>">												
									</div>
							</div>											
							<div class="form-group">
									<label class="col-lg-2 control-label" for="normal">Referencia2</label>
									<div class="col-lg-5">
										<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objItem->reference2; ?>">												
									</div>
							</div>												
					
					</div>
					<div class="tab-pane fade" id="concepts">
						
						<br/>
						<a href="#" class="btn btn-flat btn-info" id="btnNewDetailConcept" >Agregar</a>
						<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailConcept" >Eliminar</a>									
						
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered" id="table_concept">
									<thead>
									  <tr>		
										<th></th>
										<th>Nombre</th>
										<th>Valor para Entrada</th>
										<th>Valor para Salida</th>
									  </tr>
									</thead>
									<tbody id="body_detail_concept">	
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
						
						
					</div>
					<div class="tab-pane fade" id="provider">
						<br/>
						<a href="#" class="btn btn-flat btn-info" id="btnNewDetailProvider" >Agregar</a>
						<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailProvider" >Eliminar</a>									
						
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered" id="table_provider">
									<thead>
									  <tr>		
										<th></th>
										<th></th>
										<th>Codigo</th>
										<th>Nombre</th>
									  </tr>
									</thead>
									<tbody id="body_detail_provider">	
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
						
					</div>
					<div class="tab-pane fade" id="warehouse">
						<br/>
						<a href="#" class="btn btn-flat btn-info" id="btnNewDetailWarehouse" >Agregar</a>
						<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailWarehouse" >Eliminar</a>									
						<script type="text/template"  id="tmpl_row_warehouse">
							<tr class="row_warehouse">
								<td>
									<input type="hidden" class="txtDetailWarehouseID" name="txtDetailWarehouseID[]" value="${warehouseID}"></input>
									<input type="hidden" class="txtDetailQuantityMax" name="txtDetailQuantityMax[]" value="${quantityMax}"></input>
									<input type="hidden" class="txtDetailQuantityMin" name="txtDetailQuantityMin[]" value="${quantityMin}"></input>
									<input type="hidden" class="txtDetailQuantity" name="txtDetailQuantity[]"    value="0"></input>
									${warehouseDescription}
								</td>
								<td>
									0.00
								</td>
								<td>
									${quantityMin}
								</td>
								<td>
									${quantityMax}
								</td>
							</tr>
						</script>
						
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered">
									<thead>
									  <tr>															
										<th>Bodega</th>
										<th>Cantidad</th>
										<th>Minimo</th>
										<th>Maximo</th>
									  </tr>
									</thead>
									<tbody id="body_detail_warehouse">	
										<tr>
											<td>
												<select name="txtTempWarehouseID" id="txtTempWarehouseID" class="select2">
														<option></option>
														<?php
														if($objListWarehouse)
														foreach($objListWarehouse as $ws){
															echo "<option value='".$ws->warehouseID."' selected >".$ws->name."</option>";																		
														}
														?>
												</select>
											</td>
											<td></td>
											<td><input class="form-control"  type="text"  name="txtTmpDetailQuantityMin" id="txtTmpDetailQuantityMin" value=""></td>
											<td><input class="form-control"  type="text"  name="txtTmpDetailQuantityMax" id="txtTmpDetailQuantityMax" value=""></td>
										</tr>
										<?php
											if($objItemWarehouse)
											foreach($objItemWarehouse as $i){
												echo "<tr class='row_warehouse'>";
													echo "<td>";
														echo "<input type='hidden' class='txtDetailWarehouseID' name='txtDetailWarehouseID[]' value='".$i->warehouseID."'></input>";
														echo "<input type='hidden' class='txtDetailQuantityMax' name='txtDetailQuantityMax[]' value='".$i->quantityMax."'></input>";
														echo "<input type='hidden' class='txtDetailQuantityMin' name='txtDetailQuantityMin[]' value='".$i->quantityMin."'></input>";
														echo "<input type='hidden' class='txtDetailQuantity' name='txtDetailQuantity[]'    value='0'></input>";
														echo $i->warehouseName;
													echo "</td>";
													echo "<td>";
														echo $i->quantity;
													echo "</td>";
													echo "<td>";
														echo $i->quantityMin;
													echo "</td>";
													echo "<td>";
														echo $i->quantityMax;
													echo "</td>";
												echo "</tr>";
											}
										?>
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
						
					</div>
					<div class="tab-pane fade" id="dropdown">
						
							<div class="form-group">
								<label class="col-lg-2 control-label" for="normal">Descripcion</label>
								<div class="col-lg-6">
									<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="6"><?php echo $objItem->description; ?></textarea>
								</div>
							</div>
						
					</div>
					<div class="tab-pane fade" id="dropdown-file">
						
					</div>
					<div class="tab-pane fade" id="price">
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered">
									<thead>
										<tr>																													  	
										<th>Tipo de Precio</th>
										<th>Precio</th>
										</tr>
									</thead>
									<tbody id="body_detail_precio">																
										<?php
										if($objListPriceItem)
										foreach($objListPriceItem as $ws){
												?>
												<tr class="row_price">
													<td>
														<input type="hidden" class="txtDetailListPriceID" name="txtDetailListPriceID[]" value="<?php echo $ws->listPriceID; ?>"></input>
														<input type="hidden" class="txtDetailTypePriceID" name="txtDetailTypePriceID[]" value="<?php echo $ws->typePriceID; ?>"></input>
														<?php echo $ws->nameTypePrice; ?>
													</td>																		
													<td>
														<input class="form-control"  type="text" id="txtDetailTypePriceValue" name="txtDetailTypePriceValue[]" value="<?php echo $ws->price; ?>">
													</td>
													
												</tr>
												<?php
											
										}
										?>
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
					</div>
				</div>    
		 
			</div>
			</form>
			<!-- /body -->
		</div>
	</div>
</div>
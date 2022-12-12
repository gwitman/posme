					<div class="row">
                        <div id="email" class="col-lg-12">
                            <div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
                                <div class="row">
                                    <div class="email-content col-lg-12">
                                        <form id="form-new-rol" class="form-horizontal" >
												<div class="form-group">
													<label class="col-lg-2 control-label" for="selectFilter">Sucursal</label>
													<div class="col-lg-10">
														<select name="txtBranchID" id="txtBranchID" class="select2">
															   <option></option>														   
															   <?php
															   if($objListBranch)
															   foreach($objListBranch as $i){
																	echo "<option value='".$i->branchID."'>".$i->name."</option>";
															   }
															   ?>
														</select>
													</div>
												</div>
												<div class="form-group">
														<label class="col-lg-4 control-label" for="normal">Nombre</label>
														<div class="col-lg-8">
															<input class="form-control" type="text" name="txtName" id="txtName" value="">															
														</div>
												</div>
												<div class="form-group">
													<label class="col-lg-2 control-label" for="selectFilter">Bodega Origen</label>
													<div class="col-lg-10">
														<select name="txtWarehouseSourceID" id="txtWarehouseSourceID" class="select2">
															   <option></option>														   
															   <?php
															   if($objListWarehouse)
															   foreach($objListWarehouse as $i){
																	echo "<option value='".$i->warehouseID."'>".$i->number." ".$i->name."</option>";
															   }
															   ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-2 control-label" for="selectFilter">Bodega Destino</label>
													<div class="col-lg-10">
														<select name="txtWarehouseTargetID" id="txtWarehouseTargetID" class="select2">
															   <option></option>														   
															   <?php
															   if($objListWarehouse)
															   foreach($objListWarehouse as $i){
																	echo "<option value='".$i->warehouseID."'>".$i->number." ".$i->name."</option>";
															   }
															   ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-2 control-label" for="checkboxes">Es Principal</label>													
													<label class="checkbox-inline">
														<input type="checkbox" id="txtIsDefault" name="txtIsDefault" value="1" >
													</label>													
												</div>
										</form>
                                    </div>
                                </div><!-- End .row-fluid  -->                            
                            </div>
                        </div><!-- End #email  -->
                    </div><!-- End .row-fluid  -->
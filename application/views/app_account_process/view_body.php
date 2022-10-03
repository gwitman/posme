					<div class="row"> 
						<div class="col-lg-6">
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>*) Cierre de Ciclos Contables</h4>
                                        <a href="#" class="minimize"></a>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">

											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Periodo</label>
												<div class="col-lg-8">
													<select name="txtClosedPeriod" id="txtClosedPeriod" class="select2">
															<option></option>
															<?php
															if($objListAccountingPeriod)
															foreach($objListAccountingPeriod as $i){
																echo "<option value='".$i->componentPeriodID."'>".helper_DateToSpanish($i->startOn,"Y")."</option>";
															}
															?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Ciclo</label>
												<div class="col-lg-8">
													<select name="txtClosedCicle" id="txtClosedCicle" class="select2">
															<option></option>
													</select>
												</div>
											</div>
													
													
                                            <div class="form-group">
                                                <button type="button" id="btnAceptClosedCycle" class="btn btn-primary pull-right">Aceptar</button>
                                            </div><!-- End .form-group  -->
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->	
						</div>
                        <div  class="col-lg-6">                        	
                    		<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>*) Mayorizar Ciclos Contables</h4>
                                        <a href="#" class="minimize"></a>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Periodo</label>
												<div class="col-lg-8">
													<select name="txtMayorizatePeriod" id="txtMayorizatePeriod" class="select2">
															<option></option>
															<?php
															if($objListAccountingPeriod)
															foreach($objListAccountingPeriod as $i){
																echo "<option value='".$i->componentPeriodID."'>".helper_DateToSpanish($i->startOn,"Y")."</option>";
															}
															?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Ciclo</label>
												<div class="col-lg-8">
													<select name="txtMayorizateCicle" id="txtMayorizateCicle" class="select2">
															<option></option>															
													</select>
												</div>
											</div>
													
													
                                            <div class="form-group">
                                                <button type="button" id="btnAceptMayorizateCycle" class="btn btn-primary pull-right">Aceptar</button>
                                            </div><!-- End .form-group  -->
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->								
                        </div>                        
                    </div>
					
					
					<div class="row"> 
						<div class="col-lg-6">								
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>*) Contabilizar Documentos</h4>
                                        <a href="#" class="minimize"></a>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">

											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Transaccion</label>
												<div class="col-lg-8">
													<select name="txtListTransactionID" id="txtListTransactionID" class="select2">
															<option value="0" selected >TODAS</option>
															<?php
															if($objListTransaction)
															foreach($objListTransaction as $i){
																echo "<option value='".$i->transactionID."'>".$i->name."</option>";
															}
															?>
													</select>
												</div>
											</div>
													
													
                                            <div class="form-group">
                                                <button type="button" id="btnAceptContabilizeDocument" class="btn btn-primary pull-right">Aceptar</button>
                                            </div><!-- End .form-group  -->
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->			
						</div>
                        <div  class="col-lg-6">     
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>*) Procesar Notificaciones</h4>
                                        <a href="#" class="minimize"></a>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">

											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Seleccionar</label>
												<div class="col-lg-8">
													<select name="txtNotificationProcess" id="txtNotificationProcess" class="select2">
															<option value="TODAS" selected >TODAS</option>
															<option value="currentNotification"  >Obligaciones</option>
															<option value="sendEmail"  >Enviar Email</option>
															<option value="fillTipoCambio"  >Tipo de Cambios</option>
															<option value="fillInventarioMinimo"  >Inventario Minimo</option>
															<option value="fillCumpleayo"  >Cumpleaños</option>
															<option value="fillCuotaAtrasada"  >Cuota Atrazadas</option>
													</select>
												</div>
											</div>
													
											<div class="btn-group">
												<button class="btn btn-warning dropdown-toggle pull-right" data-toggle="dropdown">Limpiar <span class="caret"></span></button>
												<ul class="dropdown-menu">													
													<!--<li id="clearTipoCambio" class="clear_notification" data-notificationid="1"><a href="#">Tipo de Cambio</a></li>-->
													<!--<li id="clearInventarioMinimo" class="clear_notification" data-notificationid="2"><a href="#">Inventario Minimo</a></li>-->
													<li id="clearCuotaAtrazada" class="clear_notification" data-notificationid="5"><a href="#">Cuota Atrazadas</a></li>
													<!--<li id="clearCumpleayo" class="clear_notification" data-notificationid="6"><a href="#">Cumpleaños</a></li>-->
													<!--<li id="clearCurrentNotificaciones" class="clear_notification" data-notificationid="7"><a href="#">Obligaciones</a></li>-->
													<!--<li id="clearAll" class="clear_notification" data-notificationid="-1"><a href="#">TODAS</a></li>-->
													<!--<li class="divider"></li>-->
													<!--<li id="clearSendEmail" class="clear_notification" data-notificationid="-2"><a href="#">Enviar Email</a></li>-->
												</ul>
												<button type="button" id="btnAceptNotificacion" class="btn btn-primary pull-right">Aceptar</button>
											</div><!-- /btn-group -->
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->								
						</div>
					</div>
					
					<div class="row"> 
						<div class="col-lg-6">
						
						</div>
                        <div  class="col-lg-6">                        	
                    		<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>*) Actualizar Tipo de Cambio BCN</h4>
                                        <a href="#" class="minimize"></a>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Periodo</label>
												<div class="col-lg-8">
													<select name="txtTipoCambioPeriod" id="txtTipoCambioPeriod" class="select2">
															<option></option>
															<?php
															if($objListAccountingPeriod)
															foreach($objListAccountingPeriod as $i){
																echo "<option value='".$i->componentPeriodID."'>".helper_DateToSpanish($i->startOn,"Y")."</option>";
															}
															?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Ciclo</label>
												<div class="col-lg-8">
													<select name="txtTipoCambioCicle" id="txtTipoCambioCicle" class="select2">
															<option></option>															
													</select>
												</div>
											</div>
													
													
                                            <div class="form-group">
                                                <button type="button" id="btnDownloadTipoCambio" class="btn btn-primary pull-right">Aceptar</button>
                                            </div><!-- End .form-group  -->
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->								
                        </div>                        
                    </div>
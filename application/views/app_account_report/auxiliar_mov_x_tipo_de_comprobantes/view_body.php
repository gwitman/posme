					<div class="row"> 
						<div class="col-lg-12">
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>AUXILIAR DE MOVIMIENTOS POR TIPO DE COMPROBANTES</h4>										
                                        <a href="#" class="minimize"></a>
										<div class="w-right" style="margin-right:20px">
											<button id="print-btn-report" class="btn btn-primary btn-full tip" title="Ver Reporte" rel="panel-body"><i class="icon20 i-file gap-right0"></i></button>
										</div>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Centro de Costo</label>
												<div class="col-lg-6"> 
													<div class="col-lg-12">
																<select name="txtClassID" id="txtClassID" class="select2">
																<option value="0">TODOS</option>
																<?php
																if($objListCentroCosto)
																foreach($objListCentroCosto as $i){
																	echo "<option value='".$i->classID."'>".$i->description."</option>";
																}
																?>
														</select>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Tipo de Comprobantes</label>
												<div class="col-lg-6"> 
													<div class="col-lg-12">
														<select name="txtJournalTypeID" id="txtJournalTypeID" class="select2">
																<option value='-1'>TODOS</option>
																<?php
																if($objListJournalType)
																foreach($objListJournalType as $i){
																	echo "<option value='".$i->catalogItemID."'>".$i->name."</option>";
																}
																?>
														</select>
													</div>													
												</div>
											</div>
											<div class="form-group">
														<label class="col-lg-6 control-label" for="stringContainer">Contiene</label>
														<div class="col-lg-6">
															<div class="col-lg-12">
																<input class="form-control"  type="text"  name="stringContainer" id="stringContainer" value="">												
															</div>
														</div>
											</div>
											
											<div class="form-group">
															<label class="col-lg-6 control-label" for="excludeSystem">Excluir Comprobantes Automaticos</label>
															<div class="col-lg-6">
																<div class="col-lg-12">
																	<input type="checkbox"   name="excludeSystem" id="excludeSystem" value="1" >
																</div>
															</div>
											</div>
													
											
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Fecha Inicial y Final</label>
												<div class="col-lg-6"> 
													<div class="col-lg-6" >
														<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
															<input size="16" class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="">
															<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
														</div>
													</div>
													<div class="col-lg-6" >
														<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
															<input size="16" class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="">
															<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
														</div>
													</div>													
												</div>
											</div>	
											
											
											
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->	
						<div>
					<div>
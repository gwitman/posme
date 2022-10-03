					<!-- botonera -->
					<div class="row"> 
                        <div id="email" class="col-lg-12">                        	
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">    
								<form class="navbar-form navbar-left" role="search"  action="<?php echo site_url(); ?>app_cxc_record/index.aspx" method="get">
									<div class="col-lg-12 srch">
										<div class="input-group-btn">
											<input type="text" class="form-control" id="identificacion" name="identificacion" placeholder="000-000000-0000X">
											<button type="submit" class="btn" id="btnSearchCustomer" ><i class="icon16 i-search-2 gap-left5"></i></button>
										</div>
									</div>
								</form>
															
                                <div class="btn-group pull-right">									
									<a href="<?php echo site_url(); ?>app_cxc_customer/index.aspx" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
									<!--<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>-->
									<a href="<?php echo site_url(); ?>app_cxc_report/consulta_sin_riesgo/viewReport/true/fileName/<?php echo (isset($ObjConsulta) ? $ObjConsulta->file:""); ?>" target="_black" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
                                    <!--<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>-->
                                </div>
                            </div> 
                        </div>
                    </div>

				


                    <!-- /botonera -->
					
				    <div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
										
								<!-- titulo de comprobante-->
								<div class="panel-heading">
										<div class="icon"><i class="icon20 i-file"></i></div> 
										<h4>ULTIMA CONSULTA:#  <span class="invoice-num">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (isset($ObjConsulta) ? $ObjConsulta->createdOn : "");  ?></span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nombre del archivo:   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="notification red"><?php echo (isset($ObjConsulta) ? $ObjConsulta->file:""); ?></span> ...</h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-cxc-customer" name="form-new-cxc-customer" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<ul id="myTab" class="nav nav-tabs">
										<li class="active"><a href="#home" data-toggle="tab">Informacion General.</a></li>
										<li><a href="#profile" data-toggle="tab">Historial de Creditos.</a></li>
										<li><a href="#profile-phones" data-toggle="tab">Consultas.</a></li>
									</ul>
									
									<div class="tab-content">
										<div class="tab-pane fade in active" id="home">	
											<div class="row">										
												<div class="col-lg-6">
													<p><code>Identificación del Cliente:</code></p>
													<dl class="dl-horizontal">
														<dt>Cedula</dt>
														<dd><span class="blue"><?php echo (isset($Persona) ? $Persona->NumeroDocumentoIdentidad:""); ?></span></dd>

														<dt>Nombre</dt>
														<dd><?php echo (isset($Persona) ? $Persona->NombreRazonSocial:""); ?></dd>
													</dl>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-6">
														<p><code>Listado de Telefonos:</code></p>
														<dl class="dl-horizontal">
															<?php
															if(isset($Telefonos))
															if($Telefonos)
															foreach($Telefonos as $ws){
																echo "<dt>".$ws->Referencia."</dt>";
																echo '<dd># <span class="blue">'.$ws->Telefono.'</span></dd>';
															}
															?>
														</dl>
													</div>
											</div>
											<div class="row">
												<div class="col-lg-12">
													<p><code>Lista de Direcciones del cliente:</code></p>
													<dl class="dl-horizontal">
														<?php
														if(isset($Direcciones))
														if($Direcciones)
														foreach($Direcciones as $ws){
															echo "<dt>".$ws->Referencia."</dt>";
															echo '<dd>Departamento: <span class="blue">'.$ws->Departamento.'</span>   &nbsp;&nbsp;&nbsp;Municipio:&nbsp;&nbsp;'.$ws->Municipio.'</dd>';
															echo '<dd>'.$ws->Direccion.'</dd>';
															echo '<dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</dd>';
														}
														?>
													</dl>

												</div>
											</div>
										</div>
										
										<div class="tab-pane fade" id="profile">
											<div class="row">		
												<div class="col-lg-12">
													<h3>Detalle de operaciones de credito</h3>
														<?php
														$cantidad = 0;
														if(isset($CreditosVigentes))
														if($CreditosVigentes)
														foreach($CreditosVigentes as $ws){
															$cantidad++;
															?>
																<blockquote>
																	<p>Credito Numero <?php echo $cantidad; ?> --<?php echo  $ws->NumeroCredito; ?>: <?php echo $ws->Entidad; ?></p>
																	<h4><span class="green">Fecha desembolso: <?php echo helper_DateToSpanish($ws->FechaDesembolso,"Y-M-d"); ?></span></h4>
																</blockquote>																				
																<div class="row">
																	
																	<div class="col-lg-4">
																		<div class="row">
																			<div class="col-lg-6">
																				<h5>Tipo de Obligación:</h5>
																				<h5>Tipo de Crédito:</h5>
																				<h5>Tipo de Garantia:</h5>
																				<h5>Estado:</h5>
																				<h5>Forma de Recuperación:</h5>
																			</div>
																			<div class="col-lg-6">
																				<h5><span class="black"><?php echo strtolower($ws->TipoObligacion); ?></span></h5>
																				<h5><span class="black"><?php echo strtolower($ws->TipoCredito); ?></span></h5>
																				<h5><span class="black"><?php echo strtolower(substr($ws->TipoGarantia,0,26)); ?></span></h5>
																				<h5><span class="<?php echo helper_GetColorSinRiesgo($ws->EstadoOP); ?>"><?php echo strtolower($ws->EstadoOP); ?></span></h5>
																				<h5><span class="<?php echo helper_GetColorSinRiesgo($ws->FormaRecuperacion); ?>"><?php echo strtolower($ws->FormaRecuperacion); ?></span></h5>																		
																			</div>
																		</div>																		
																	</div><!-- End .col-lg-4  -->  
																	<div class="col-lg-4">
																		<div class="row">
																			<div class="col-lg-6">
																				<h5>Fecha de Reporte:</h5>
																				<h5>Fecha de Desembolso:</h5>
																				<h5>Departamento:</h5>
																				<h5>Forma de Pago:</h5>
																				<h5>Plazo:</h5>
																			</div>
																			<div class="col-lg-6">
																				<h5><span class="black"><?php echo helper_DateToSpanish($ws->FechaReporte,"Y-M-d"); ?></span></h5>
																				<h5><span class="black"><?php echo helper_DateToSpanish($ws->FechaDesembolso,"Y-M-d"); ?></span></h5>
																				<h5><span class="black"><?php echo strtolower($ws->Departamento); ?></span></h5>
																				<h5><span class="black"><?php echo strtolower($ws->FormaDePago); ?></span></h5>
																				<h5><span class="black"><?php echo $ws->PlazoMeses; ?></span></h5>																		
																			</div>
																		</div>
																	</div><!-- End .col-lg-4  -->  
																	<div class="col-lg-4">
																		<div class="row">
																			<div class="col-lg-6">
																				<h5>Monto Autorizado (C$):</h5>
																				<h5>Saldo de Deuda (C$):</h5>
																				<h5>Monto Vencido (C$):</h5>
																				<h5>Antiguedad Mora:</h5>
																				<h5>Valor Cuota (C$):</h5>
																			</div>
																			<div class="col-lg-6">
																				<h5><span class="black"><?php echo number_format(round($ws->MontoAutorizado,2),2); ?></span></h5>
																				<h5><span class="black"><?php echo number_format(round($ws->SaldoDeuda,2),2); ?></span></h5>
																				<h5><span class="black"><?php echo number_format(round($ws->MontoVencido,2),2); ?></span></h5>
																				<h5><span class="black"><?php echo $ws->AntiguedadMoraEnDias; ?></span></h5>
																				<h5><span class="black"><?php echo number_format(round($ws->Cuota,2),2); ?></span></h5>																		
																			</div>
																		</div>															
																	</div><!-- End .col-lg-4  -->  
																</div><!-- End .row-fluid  -->
															<?php
														}
														?>
													<h3>Detalle de operaciones de creditos activos grupos solidarios</h3>
													<h3>Detalle de tarjetas de credito</h3>
													<h3>Detalle de operaciones de creditos saneados</h3>
													<h3>Detalle de operaciones de creditos saneados grupos solidarios</h3>
													<h3>Detalle de operaciones de creditos cancelados</h3>
														<?php
														$cantidad = 0;
														if(isset($CreditosCancelados))
														if($CreditosCancelados)
														foreach($CreditosCancelados as $ws){
															$cantidad++;
															?>
																<blockquote>
																	<p>Credito Numero <?php echo $cantidad; ?> --<?php echo  $ws->NumeroCredito; ?>: <?php echo $ws->Entidad; ?></p>
																	<h4><span class="green">Fecha desembolso: <?php echo helper_DateToSpanish($ws->FechaDesembolso,"Y-M-d"); ?></span></h4>
																</blockquote>																				
																<div class="row">
																	
																	<div class="col-lg-4">
																		<div class="row">
																			<div class="col-lg-6">
																				<h5>Tipo de Obligación:</h5>
																				<h5>Tipo de Crédito:</h5>
																				<h5>Tipo de Garantia:</h5>
																				<h5>Estado:</h5>
																				<h5>Forma de Recuperación:</h5>
																			</div>
																			<div class="col-lg-6">
																				<h5><span class="black"><?php echo strtolower($ws->TipoObligacion); ?></span></h5>
																				<h5><span class="black"><?php echo strtolower($ws->TipoCredito); ?></span></h5>
																				<h5><span class="black"><?php echo strtolower(substr($ws->TipoGarantia,0,26)); ?></span></h5>
																				<h5><span class="<?php echo helper_GetColorSinRiesgo($ws->EstadoOP); ?>"><?php echo strtolower($ws->EstadoOP); ?></span></h5>
																				<h5><span class="<?php echo helper_GetColorSinRiesgo($ws->FormaRecuperacion); ?>"><?php echo strtolower($ws->FormaRecuperacion); ?></span></h5>																		
																			</div>
																		</div>																		
																	</div><!-- End .col-lg-4  -->  
																	<div class="col-lg-4">
																		<div class="row">
																			<div class="col-lg-6">
																				<h5>Fecha de Reporte:</h5>
																				<h5>Fecha de Desembolso:</h5>
																				<h5>Departamento:</h5>
																				<h5>Forma de Pago:</h5>
																				<h5>Plazo:</h5>
																			</div>
																			<div class="col-lg-6">
																				<h5><span class="black"><?php echo helper_DateToSpanish($ws->FechaReporte,"Y-M-d"); ?></span></h5>
																				<h5><span class="black"><?php echo helper_DateToSpanish($ws->FechaDesembolso,"Y-M-d"); ?></span></h5>
																				<h5><span class="black"><?php echo strtolower($ws->Departamento); ?></span></h5>
																				<h5><span class="black"><?php echo strtolower($ws->FormaDePago); ?></span></h5>
																				<h5><span class="black"><?php echo $ws->PlazoMeses; ?></span></h5>																		
																			</div>
																		</div>
																	</div><!-- End .col-lg-4  -->  
																	<div class="col-lg-4">
																		<div class="row">
																			<div class="col-lg-6">
																				<h5>Monto Autorizado (C$):</h5>
																				<h5>Saldo de Deuda (C$):</h5>
																				<h5>Monto Vencido (C$):</h5>
																				<h5>Antiguedad Mora:</h5>
																				<h5>Valor Cuota (C$):</h5>
																			</div>
																			<div class="col-lg-6">
																				<h5><span class="black"><?php echo number_format(round($ws->MontoAutorizado,2),2); ?></span></h5>
																				<h5><span class="black"><?php echo number_format(round($ws->SaldoDeuda,2),2); ?></span></h5>
																				<h5><span class="black"><?php echo number_format(round($ws->MontoVencido,2),2); ?></span></h5>
																				<h5><span class="black"><?php echo $ws->AntiguedadMoraEnDias; ?></span></h5>
																				<h5><span class="black"><?php echo number_format(round($ws->Cuota,2),2); ?></span></h5>																		
																			</div>
																		</div>															
																	</div><!-- End .col-lg-4  -->  
																</div><!-- End .row-fluid  -->
															<?php
														}
														?>
													<h3>Detalle de operaciones de creditos cancelados grupos solidarios</h3>
													<h3>Detalle de nota de prensas</h3>
													<h3>Detalle de operaciones de servicios publicos</h3>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="profile-phones">
											<div class="row">													
												<div class="col-lg-12">
													

													<p><code>Listado de consultas, donde el cliente ha solicitado credito:</code></p>
													<dl class="dl-horizontal">

														<?php
														if(isset($Consultas))
														if($Consultas)
														foreach($Consultas as $ws){
															echo "<dt>Cantidad. ".$ws->Cantidad."</dt>";
															echo '<dd>Fecha de consulta: <span class="blue">'.$ws->FechaConsulta.'</span>   &nbsp;&nbsp;&nbsp;Entidad:'.$ws->Entidad.' </dd>';
															echo '<dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</dd>';
														}
														?>
													</dl>

													
												</div>
											</div>
										</div>
										
									</div>    
									
                                </div>
								</form>
								<!-- /body -->
								
								
									
							</div>
						</div>
					</div>
					
						
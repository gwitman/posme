<br/>
<div class="row">       
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-cube"></i></div> 
				<h4>Obligaciones</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
		
			<div class="panel-body" style="overflow-y: scroll;height: 650px;">
				<div class="panel-body noPadding">
					<div class="table-toolbar btn-toolbar">
						<div class="btn-group">
							<a href="#" class="btn btn-default btn-xs select-all"> <i class="icon12 i-checkbox-checked-2"></i> Check All</a>
							<a href="#" class="btn btn-default btn-xs deselect-all"><i class="icon12 i-checkbox-unchecked-3"></i> Uncheck All</a>
						</div>
						<div class="btn-group pull-right">
							<a href="#" class="btn btn-xs btn-info btnRead"><i class="icon12 i-location"></i>Marcar como Leido</a>
						</div>
					</div>[
]					<table class="table table-bordered checkAll">
						<thead>
							<tr>
								<th style="width:10%">#</th>
								<th style="width:75%">Descripcion</th>
								<th style="width:15%">Fecha</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count=0;
							if( $objListErrorOblicaciones)
							foreach( $objListErrorOblicaciones as $i){
								$count++;
								echo '<tr>';
									echo '<td class="center"><input type="checkbox" value="1" class="checkIt" data-errorid="'.$i->errorID.'"></td>';							
									echo '<td>'.$i->message.'</td>';
									echo '<td>'.date_format(date_create($i->createdOn),"Y-m-d").'</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div><!-- End .panel-body -->
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->
	</div><!-- End .col-lg-6  -->  [
]	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-cube"></i></div> 
				<h4>Pendientes de Pago</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
		
			<div class="panel-body" style="overflow-y: scroll;height: 650px;">
				<div class="panel-body noPadding">
					<div class="table-toolbar btn-toolbar">
						<div class="btn-group">
							<a href="#" class="btn btn-default btn-xs select-all"> <i class="icon12 i-checkbox-checked-2"></i> Check All</a>
							<a href="#" class="btn btn-default btn-xs deselect-all"><i class="icon12 i-checkbox-unchecked-3"></i> Uncheck All</a>
						</div>
						<div class="btn-group pull-right">
							<a href="#" class="btn btn-xs btn-info btnRead"><i class="icon12 i-location"></i>Marcar como Leido</a>
						</div>
					</div>[
]					<table class="table table-bordered checkAll">
						<thead>
							<tr>
								<th style="width:10%">#</th>
								<th style="width:75%">Descripcion</th>
								<th style="width:15%">Fecha</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count=0;
							if( $objListErrorPagos)
							foreach( $objListErrorPagos as $i){
								$count++;
								echo '<tr>';
									echo '<td class="center"><input type="checkbox" value="1" class="checkIt" data-errorid="'.$i->errorID.'"></td>';							
									echo '<td>'.$i->message.'</td>';
									echo '<td>'.date_format(date_create($i->createdOn),"Y-m-d").'</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div><!-- End .panel-body -->
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->
	</div><!-- End .col-lg-6  -->  
		
</div><!-- End .row-fluid  -->
<div class="row">       
	
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-cube"></i></div> 
				<h4>Tipo de Cambio</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
		
			<div class="panel-body" style="overflow-y: scroll;height: 250px;">
				<div class="panel-body noPadding">
					<div class="table-toolbar btn-toolbar">
						<div class="btn-group">
							<a href="#" class="btn btn-default btn-xs select-all"> <i class="icon12 i-checkbox-checked-2"></i> Check All</a>
							<a href="#" class="btn btn-default btn-xs deselect-all"><i class="icon12 i-checkbox-unchecked-3"></i> Uncheck All</a>
						</div>
						<div class="btn-group pull-right">
							<a href="#" class="btn btn-xs btn-info btnRead"><i class="icon12 i-location"></i>Marcar como Leido</a>
						</div>
					</div>[
]					<table class="table table-bordered checkAll">
						<thead>
							<tr>
								<th style="width:10%">#</th>
								<th style="width:75%">Descripcion</th>
								<th style="width:15%">Fecha</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count=0;
							if( $objListErrorTC)
							foreach( $objListErrorTC as $i){
								$count++;
								echo '<tr>';
									echo '<td class="center"><input type="checkbox" value="1" class="checkIt" data-errorid="'.$i->errorID.'"></td>';							
									echo '<td>'.$i->message.'</td>';
									echo '<td>'.date_format(date_create($i->createdOn),"Y-m-d").'</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div><!-- End .panel-body -->
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->
	</div><!-- End .col-lg-6  -->  
	
	
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-cube"></i></div> 
				<h4>Inventario Minimo</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
		
			<div class="panel-body" style="overflow-y: scroll;height: 250px;">
				<div class="panel-body noPadding">
					<div class="table-toolbar btn-toolbar">
						<div class="btn-group">
							<a href="#" class="btn btn-default btn-xs select-all"> <i class="icon12 i-checkbox-checked-2"></i> Check All</a>
							<a href="#" class="btn btn-default btn-xs deselect-all"><i class="icon12 i-checkbox-unchecked-3"></i> Uncheck All</a>
						</div>
						<div class="btn-group pull-right">
							<a href="#" class="btn btn-xs btn-info btnRead"><i class="icon12 i-location"></i>Marcar como Leido</a>
						</div>
					</div>[
]					<table class="table table-bordered checkAll">
						<thead>
							<tr>
								<th style="width:10%">#</th>
								<th style="width:75%">Descripcion</th>
								<th style="width:15%">Fecha</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count=0;
							if( $objListErrorInventarioMinimo)
							foreach( $objListErrorInventarioMinimo as $i){
								$count++;
								echo '<tr>';
									echo '<td class="center"><input type="checkbox" value="1" class="checkIt" data-errorid="'.$i->errorID.'"></td>';							
									echo '<td>'.$i->message.'</td>';
									echo '<td>'.date_format(date_create($i->createdOn),"Y-m-d").'</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div><!-- End .panel-body -->
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->
	</div><!-- End .col-lg-6  -->  
		
</div><!-- End .row-fluid  -->
<div class="row"> 
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-cube"></i></div> 
				<h4>Cumple!!!</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
		
			<div class="panel-body" style="overflow-y: scroll;height: 250px;">
				<div class="panel-body noPadding">
					<div class="table-toolbar btn-toolbar">
						<div class="btn-group">
							<a href="#" class="btn btn-default btn-xs select-all"> <i class="icon12 i-checkbox-checked-2"></i> Check All</a>
							<a href="#" class="btn btn-default btn-xs deselect-all"><i class="icon12 i-checkbox-unchecked-3"></i> Uncheck All</a>
						</div>
						<div class="btn-group pull-right">
							<a href="#" class="btn btn-xs btn-info btnRead"><i class="icon12 i-location"></i>Marcar como Leido</a>
						</div>
					</div>[
]					<table class="table table-bordered checkAll">
						<thead>
							<tr>
								<th style="width:10%">#</th>
								<th style="width:75%">Descripcion</th>
								<th style="width:15%">Fecha</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count=0;
							if( $objListErrorCumple)
							foreach( $objListErrorCumple as $i){
								$count++;
								echo '<tr>';
									echo '<td class="center"><input type="checkbox" value="1" class="checkIt" data-errorid="'.$i->errorID.'"></td>';							
									echo '<td>'.$i->message.'</td>';
									echo '<td>'.date_format(date_create($i->createdOn),"Y-m-d").'</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div><!-- End .panel-body -->
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->
	</div><!-- End .col-lg-6  -->  [
]</div><!-- End .row-fluid  -->
			
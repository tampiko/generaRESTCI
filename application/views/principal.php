<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Juan Manuel
 * Date: 04/10/2018
 * Time: 04:12 PM
 */
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Genera REST - CI</title>
	<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/app/css/styles.css">
</head>

<body>
<div class="container">
	
	<h2>Base de Datos :</h2>
	<table class="table table-bordered table-striped table-condensed table-responsive table-hover">
		<thead>
			<tr>
				<td colspan="2">Genera REST - CI</td>
			</tr>
		</thead>
		
		<tbody>
		<tr>
			<td>Base de Datos</td>
			<td>
				<select name="bdatos" id="bdatos"></select>
			</td>
		</tr>
		<tr>
			<td>Tabla</td>
			<td>
				<select name="tabla" id="tabla"></select>
			</td>
		</tr>
		</tbody>
		
		<tfoot>
		<tr>
			<td>
				<button class="btn btn-outline-primary" type="button" id="btnGeneraController">Genera Controller</button>
			</td>
			<td>
				<button class="btn btn-outline-primary" type="button" id="btnGeneraModel">Genera Model</button>
			</td>
			<td>
				<button class="btn btn-outline-primary" type="button" id="btnGeneraJS">Genera JS</button>
			</td>
			<td>
				<button class="btn btn-outline-primary" type="button" id="btnGeneraDoble">Genera Doble</button>
			</td>
		</tr>
		</tfoot>
	</table>
	
	<div class="col-md-8" id="divMensajes">
		<td>
			<button class="btn btn-outline-primary" type="button" id="btnDescargar">Genera Link de Descarga</button>
			<hr/>
	</div>

</div>

<!-- Modal -->
<div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Genera REST - CI</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				Selecciona una tabla para generar sus archivos.
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
			</div>
			
		</div>
	</div>
</div>

</body>
<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/popper.js/dist/umd/popper.min.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/app/js/core.js"></script>
<script language="javascript" charset="utf-8">
	cargaScript('assets/app/js/config.js', function(){
		cargaScript('assets/app/js/index.js', function(){
		});
	});
</script>

</html>

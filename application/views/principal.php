<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Genera REST - CI</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
	<link rel="stylesheet" href="assets/app/css/styles.css">
</head>

<body>

	<nav class="navbar sticky-top navbar-expand-md navbar-dark bg-dark">
		<a class="navbar-brand" href="#">Genera REST - CI</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="#">Generar</a>
				</li>
			</ul>
		</div>
	</nav>


	<div class="container mt-3">
		<form>
			<div class="form-group row">
				<label for="bdatos" class="col-sm-2 col-form-label">Base de Datos</label>
				<div class="col-sm-5">
					<select class="form-control" name="bdatos" id="bdatos"></select>
				</div>
			</div>
		</form>

		<table id="tTablas" class="table table-bordered table-sm table-responsive-sm table-hover table-striped oculto">
			<thead>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3" class="text-center text-monospace">Generar</td>
				</tr>
				<tr>
					<td width="70%" class="text-monospace">Tabla</td>
					<td width="10%" class="text-monospace text-center">
						Controlador
						<button type="button" class="btn btn-info" id="btnSelTodosControlador">
							<span class="fa fa-hand-o-down"></span>
						</button>
					</td>
					<td width="10%" class="text-monospace text-center">
						Modelo
						<button type="button" class="btn btn-info" id="btnSelTodosModelo">
							<span class="fa fa-hand-o-down"></span>
						</button>
					</td>
					<td width="10%" class="text-monospace text-center">
						Js<br>
						<button type="button" class="btn btn-info" id="btnSelTodosJs">
							<span class="fa fa-hand-o-down"></span>
						</button>
					</td>
				</tr>
			</thead>
			<tbody id="tcontenidoTablas"></tbody>
			<tfoot>
				<tr>
					<td colspan="4">
						<div class="col-md-8" id="divMensajes"></div>
					</td>
				</tr>
			</tfoot>
		</table>
		<input type="hidden" id="todos" value="0">
		<input type="hidden" id="cuantos" value="0">
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="assets/app/js/core.js"></script>
<script language="javascript">
	cargaScript('assets/app/js/config.js', function() {
		cargaScript('assets/app/js/index.js', function() {});
	});
</script>

</html>
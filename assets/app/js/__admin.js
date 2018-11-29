//var index = 'index.php/';
$("#btnGuardaAlumno").click(function() {
	addAlumno();
});
$(document).ready(function() {
	getalumnos();
	getBloques();
	getEscuelas();
});

function getalumnos() {
	filtro = $("#filtro").val();
	$.ajax({
			url: 'admin/getalumnos/' + filtro,
			type: 'GET',
			dataType: 'JSON'
		})
		.done(function(datos) {
			console.log("success");
			$("#bodyTableAlumnos").html('');
			num = 0;
			$.each(datos.alumnos, function(index, val) {
				num++;
				clase = 'table-active';
				switch (val.alu_status) {
					case '2':
						clase = 'table-warning';
						break;
					case '3':
						clase = 'table-danger';
						break;
				}
				$("#bodyTableAlumnos").append('' +
					'<tr class="' + clase + '">' +

					'<td>' +
					'<div class="dropdown">' +
					'<a id="dLabel" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' +
					'<i class="material-icons pmd-sm">more_vert</i>' +
					'</a>' +
					'<ul class="dropdown-menu pmd-dropdown-menu-top-left" aria-labelledby="dLabel" style="">' +
					//'<li><a class="dropdown-item" href="javascript:void(0);"><i class="material-icons pmd-xs">mode_edit</i> Editar</a></li>'+
					'<li><a class="dropdown-item" href="javascript:desbloqueaAlumno(' + val.alu_id + ');"><i class="material-icons pmd-xs">check_circle</i> Desbloquear</a></li>' +
					'</ul>' +
					'</div>' +
					'</td>' +

					'<td data-title="Matricula">' + val.alu_matricula + '</td>' +
					'<td data-title="Nombre">' + val.alu_nombre + '</td>' +
					'<td data-title="Plantel/City">' + val.esc_nombre + '</td>' +
					'<td data-title="Bloque">' + val.blq_descripcion + '</td>' +
					//'<td data-title="Status">' + val.alu_status + '</td>' +
					//'<td data-title="Timesheet"></td>' +
					//'<td data-title=""></td>' +
					'</tr>');

			});
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function desbloqueaAlumno(alu_id) {
	$.ajax({
			url: 'admin/desbloquearAlumno/' + alu_id,
			type: 'POST',
			dataType: 'JSON'
		})
		.done(function() {
			getalumnos();
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function getBloques() {
	$.ajax({
			url: 'admin/getBloques/',
			type: 'GET',
			dataType: 'JSON'
		})
		.done(function(res) {
			console.log("success" + res);
			$("#bloque").html('');
			$.each(res.bloques, function(index, val) {
				$("#bloque").append('<option value="' + val.blq_id + '">' + val.blq_nombre + ' - ' + val.blq_descripcion + '</option>');
			});
			$("#bloque").val(0);

		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function getEscuelas() {
	$.ajax({
			url: 'admin/getEscuelas/',
			type: 'GET',
			dataType: 'JSON'
		})
		.done(function(res) {
			console.log("success");
			$("#plantel").html('');
			$.each(res.escuelas, function(index, val) {
				$("#plantel").append('<option value="' + val.esc_id + '">' + val.esc_nombre + '</option>');
			});
			$("#plantel").val(0);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function addAlumno() {
	alu_id = $("#alu_id").val();
	matricula = $("#matricula").val();
	nombre = $("#nombre").val();
	plantel = $("#plantel").val();
	bloque = $("#bloque").val();

	if (!alu_id) {
		alu_id = 0;
	}
	$.ajax({
			url: 'admin/addAlumno',
			type: 'POST',
			dataType: 'JSON',
			data: {
				alu_id: alu_id,
				alu_matricula: matricula,
				alu_nombre: nombre,
				alu_esc_id: plantel,
				alu_blo_id: bloque
			},
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}
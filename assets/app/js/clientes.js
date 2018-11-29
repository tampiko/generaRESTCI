$(document).ready(function() {
	buscaCliente();
});

$('#cli_fecha').datetimepicker({
	locale: 'es',
	viewMode: 'years',
	format: 'YYYY/MM/DD',
	// tooltips: {
	// today: 'Go to today',
	// clear: 'Clear selection',
	// close: 'Close the picker',
	// selectMonth: 'Select Month',
	// prevMonth: 'Previous Month',
	// nextMonth: 'Next Month',
	// selectYear: 'Select Year',
	// prevYear: 'Previous Year',
	// nextYear: 'Next Year',
	// selectDecade: 'Select Decade',
	// prevDecade: 'Previous Decade',
	// nextDecade: 'Next Decade',
	// prevCentury: 'Previous Century',
	// nextCentury: 'Next Century',
	// incrementHour: 'Increment Hour',
	// pickHour: 'Pick Hour',
	// decrementHour: 'Decrement Hour',
	// incrementMinute: 'Increment Minute',
	// pickMinute: 'Pick Minute',
	// decrementMinute: 'Decrement Minute',
	// incrementSecond: 'Increment Second',
	// pickSecond: 'Pick Second',
	// decrementSecond: 'Decrement Second',
	// }
});

$("#tipoEmpresa").click(function(event) {
	seleccionaTipo(2);
});
$("#tipoPersona").click(function(event) {
	seleccionaTipo(1);
});

$("#btnBuscaCliente").click(function(event) {
	buscaCliente();
});

$("#btnNvoCliente").click(function(event) {
	limpiaFrmCliente();
	muestraDatosGeneralesCliente();
	ocultaDatosExtraCliente();
});

$("#btnGuardar").click(function(event) {
	guardaCliente();
});

function seleccionaTipo(tipo) {
	$(".btnTipoCliente").removeClass('btn-primary');
	$(".btnTipoCliente").addClass('btn-default');
	$(".btnTipoCliente").addClass('pmd-btn-outlilne');
	switch (tipo) {
		case 1:
			elId = "Persona";
			$(".divTipoEmpresa").hide();
			$(".divTipoPersona").show();
			$("#cli_tcl_id").val(1);
			$("#divNombre").removeClass('col-md-12');
			$("#divNombre").addClass('col-md-4');
			break;
		case 2:
			elId = "Empresa";
			$(".divTipoEmpresa").show();
			$(".divTipoPersona").hide();
			$("#cli_tcl_id").val(2);
			$("#divNombre").addClass('col-md-12');
			$("#divNombre").removeClass('col-md-4');
			break;
	}
	$("#tipo" + elId).addClass('btn-primary');
	$("#tipo" + elId).removeClass('btn-default');
	$("#tipo" + elId).removeClass('pmd-btn-outline');
}

function limpiaFrmCliente() {
	$("#cli_nombre").val('');
	$("#cli_paterno").val('');
	$("#cli_materno").val('');
	$("#cli_fecha").val('');
	seleccionaTipo(1);
}

function buscaCliente() {
	usu_buscar = $("#txtBuscaCliente").val();
	$.ajax({
			url: index + 'clientes/buscarCliente',
			type: 'POST',
			dataType: 'JSON',
			data: {
				usu_buscar: usu_buscar
			}
		})
		.done(function(datos) {
			console.log("success");
			ocultaDatosGeneralesCliente();
			ocultaDatosExtraCliente();
			$("#tablaClientes").html('');
			codigo = '';
			$.each(datos.clientes, function(index, el) {
				codigo += '<tr onclick="getDetallesCliente(' + el.cli_id + ');">' +
					// '<td>'+el.cli_tcl_id+'</td>'+
					'<td>' + el.cli_tipoCliente + '</td>' +
					'<td>' + el.cli_rfc + '</td>' +
					'<td>' + el.cli_nombre + '</td>' +
					// '<td>' + el.cli_materno + '</td>' +
					'<td>' + el.cli_fecha + '</td>' +
					'</tr>';
			});
			$("#tablaClientes").html(codigo);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function guardaCliente() {
	cli_id = $("#cli_id").val();
	cli_nombre = $("#cli_nombre").val();
	cli_paterno = $("#cli_paterno").val();
	cli_materno = $("#cli_materno").val();
	// cli_fecha = $('#cli_fecha').date();
	cli_fecha = $("#cli_fecha").val();
	cli_tcl_id = $("#cli_tcl_id").val();
	cli_rfc = $("#cli_rfc").val();
	cli_cobrar = $("#cli_cobrar").val();
	cli_referido = $("#cli_referido").val();
	error = 0;
	msg = 'Datos Incompletos\n';

	if (cli_tcl_id == 1) {
		// Persona
		if (cli_nombre == '') {
			error = 1;
			msg += 'Falta el Nombre.\n';
		}
		if (cli_paterno == '') {
			error = 1;
			msg += 'Falta el Apellido Paterno.\n';
		}
		if (cli_materno == '') {
			error = 1;
			msg += 'Falta el Apellido Materno.\n';
		}
		if (cli_rfc == '') {
			error = 1;
			msg += 'Falta el RFC.\n';
		}
		if (cli_fecha == '') {
			error = 1;
			msg += 'Falta su fecha de Nacimiento.\n';
		}
	} else {
		// Empresa
		if (cli_nombre == '') {
			error = 1;
			msg += 'Falta la Razon social.\n';
		}
		if (cli_fecha == '') {
			error = 1;
			msg += 'Falta su fecha de Constitucion.\n';
		}
		cli_paterno = '';
		cli_materno = '';
	}
	if (cli_id == '') {
		cli_id = 0;
	}

	if (error == 1) {
		// TODO: cambiar los alert por otros
		alert(msg);
	} else {
		$.ajax({
				url: index + 'clientes/addCliente',
				type: 'POST',
				dataType: 'JSON',
				data: {
					cli_id: cli_id,
					cli_nombre: cli_nombre,
					cli_paterno: cli_paterno,
					cli_materno: cli_materno,
					cli_fecha: cli_fecha,
					cli_tcl_id: cli_tcl_id,
					cli_rfc: cli_rfc,
					cli_cobrar: cli_cobrar,
					cli_referido: cli_referido
				}
			})
			.done(function() {
				console.log("success");
				muestraDatosGeneralesCliente();
				muestraDatosExtraCliente();
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

	}
}

function getDetallesCliente(cli_id) {
	$.ajax({
			url: index + 'clientes/getDetallesCliente/' + cli_id,
			type: 'GET',
			dataType: 'JSON'
		})
		.done(function(dato) {
			console.log("success");
			muestraDatosGeneralesCliente();
			muestraDatosExtraCliente();
			seleccionaTipo(dato.cliente.cli_tcl_id);
			$('#cli_id').val(dato.cliente.cli_id);
			$('#cli_nombre').val(dato.cliente.cli_nombre);
			$('#cli_paterno').val(dato.cliente.cli_paterno);
			$('#cli_materno').val(dato.cliente.cli_materno);
			$('#cli_rfc').val(dato.cliente.cli_rfc);
			// $('#cli_fecha').val(dato.cliente.cli_fecha);
			$('#cli_fecha').data("DateTimePicker").date(dato.cliente.cli_fecha);
			$('#cli_tcl_id').val(dato.cliente.cli_tcl_id);
			$('#cli_cobrar').val(dato.cliente.cli_cobrar);
			$('#cli_referido').val(dato.cliente.cli_referido);
			$('#cli_cobrar').focus();
			$('#cli_referido').focus();
			$('#cli_id').focus();
			$('#cli_paterno').focus();
			$('#cli_materno').focus();
			$('#cli_fecha').focus();
			$('#cli_tcl_id').focus();
			$('#cli_rfc').focus();
			$('#cli_nombre').focus();

		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function muestraDatosGeneralesCliente() {
	$("#datosGeneralesCliente").show();
}

function ocultaDatosGeneralesCliente() {
	$("#datosGeneralesCliente").hide();
}

function muestraDatosExtraCliente() {
	$("#datosExtraCliente").show();
}

function ocultaDatosExtraCliente() {
	$("#datosExtraCliente").hide();
}


/*
<div class="pmd-alert-container right bottom" style="width: 360px;"></div>
*/

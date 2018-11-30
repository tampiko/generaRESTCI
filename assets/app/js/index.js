$(document).ready(function(){
	getBaseDatosAll();
});

$("#bdatos").change(function(){
	resetForm();
	getTablasDeBD();
});

$("#tabla").change(function(){
	getInfoTabla();
});

$("#btnGeneraModel").click(function(){
	generaModel();
});

$("#btnGeneraController").click(function(){
	generaController();
});

$("#btnGeneraJS").click(function(){
	generaJS();
});

$('#modalError').on('shown.bs.modal', function(e){
	setTimeout(function(){
		$("#modalError").modal('hide');
	}, 1500);
});

$("#btnSelTodosControlador").click(function(){
	SeleccionaTodos('controladores');
});

$("#btnSelTodosModelo").click(function(){
	SeleccionaTodos('modelos');
});

$("#btnSelTodosJs").click(function(){
	SeleccionaTodos('js');
});

$(function(){
	$('[data-toggle="tooltip"]').tooltip()
});

function descargar(){
	$.ajax({
		url: urlServer + elIndex + 'Archivos/generaZip',
		type: 'GET',
		dataType: 'json',
		data: {
			nombre: $("#bdatos").val()
		}
	}).done(function(res){
		$("#divBtnZip").addClass('oculto');
		$("#divMensajes").html(`
			<a target="_blank" class="btn btn-primary" href="${res.url}${urlServer}gens/${res.zip}">
				<span class="fa fa-download"></span> Descargar
			</a>
		`);
		
	}).fail(function(){
	}).always(function(){
	});
}

function getBaseDatosAll(){
	$.ajax({
		url: urlServer + elIndex + 'Bdatos/getBdatos',
		type: 'GET',
		dataType: 'JSON'
	}).done(function(res){
		$("#bdatos").html('');
		$.each(res.data, function(index, el){
			$("#bdatos").append(`<option value="${el.bdatos}">${el.bdatos}</option>`);
		});
		$("#bdatos").val(0);
	}).fail(function(){
	}).always(function(){
	});
}

function getTablasDeBD(){
	bdatos = $("#bdatos").val();
	$.ajax({
		url: urlServer + elIndex + 'Tabla/getTablasBD',
		type: 'GET',
		dataType: 'json',
		data: {
			bdatos: bdatos
		}
	}).done(function(res){
		$("#tabla").html('');
		$("#tcontenidoTablas").html('');
		$.each(res.data, function(index, el){
			$("#tabla").append(`<option value="${el.table_name.toLowerCase()}">${el.table_name.toLowerCase()}</option>`);
			$("#tcontenidoTablas").append(`
			<tr>
				<td width="70%" class="text-monospace">${el.table_name.toLowerCase()}</td>
				<td width="10%" class="text-monospace text-center" id="cC${el.table_name.toLowerCase()}">
					<input type="checkbox" class="${el.table_name.toLowerCase()} controladores" id="controladores" value="${el.table_name.toLowerCase()}" checked="checked">
				</td>
				<td width="10%" class="text-monospace text-center" id="cM${el.table_name.toLowerCase()}">
					<input type="checkbox" class="${el.table_name.toLowerCase()} modelos" id="modelos" value="${el.table_name.toLowerCase()}" checked="checked">
				</td>
				<td width="10%" class="text-monospace text-center" id="cJ${el.table_name.toLowerCase()}">
					<input type="checkbox" class="${el.table_name.toLowerCase()} js" id="js" value="${el.table_name.toLowerCase()}" checked="checked">
				</td>
				<td>
					<button type="button" class="btn btn-info fa fa-hand-o-left" onclick="SeleccionaTodos('${el.table_name.toLowerCase()}');"></button>
				</td>
			</tr>`);
		});
		$("#tabla").val(0);
		$("#divMensajes").html('<button class="btn btn-info" id="btnGenerarArchivos">Generar Archivos</button>');
		$("#btnGenerarArchivos").click(function(){
			generarArchivos();
		});
		$("#tTablas").removeClass('oculto');
	}).fail(function(){
	}).always(function(){
	});
}

function getInfoTabla(){
	tabla = $("#tabla").val();
	$.ajax({
		url: urlServer + elIndex + 'Tabla/getTablaDetalles',
		type: 'GET',
		dataType: 'json',
		data: {
			bdatos: bdatos,
			tabla: tabla
		}
	}).done(function(res){
		localStorage.setItem("detalleTabla", JSON.stringify(res.data));
	}).fail(function(){
	}).always(function(){
	});
}

function generaController(tabla){
	if(tabla == null){
		$("#modalError").modal('show');
	}else{
		tabla.toLowerCase();
		tabla = tabla.substring(0, 1).toUpperCase().concat(tabla.substring(1).toLowerCase());
		info = JSON.parse(localStorage.getItem("detalleTabla"));
		$.ajax({
			url: urlServer + elIndex + 'archivos/generaController',
			type: 'POST',
			dataType: 'json',
			data: {
				tabla: tabla,
				info: JSON.stringify(info)
			}
		}).done(function(res){
			$("#divMensajes").html(`Archivo creado: application\\controllers\\${tabla}.php<br>`);
			$("#cC" + tabla.toLowerCase()).html(`<span class="fa fa-check"></span>`);
			$("#cuantos").val(parseInt($("#cuantos").val()) + 1);
			revisa();
		}).fail(function(){
		}).always(function(){
		});
	}
}

function generaModel(tabla){
	if(tabla == null){
		$("#modalError").modal('show');
	}else{
		tabla.toLowerCase();
		tabla = tabla.substring(0, 1).toUpperCase().concat(tabla.substring(1).toLowerCase());
		info = JSON.parse(localStorage.getItem("detalleTabla"));
		$.ajax({
			url: urlServer + elIndex + 'archivos/generaModel',
			type: 'POST',
			dataType: 'json',
			data: {
				tabla: tabla,
				info: JSON.stringify(info)
			}
		}).done(function(res){
			$("#divMensajes").html(`Archivo creado: application\\models\\${tabla}_model.php<br>`);
			$("#cM" + tabla.toLowerCase()).html(`<span class="fa fa-check"></span>`);
			$("#cuantos").val(parseInt($("#cuantos").val()) + 1);
			revisa();
		}).fail(function(){
		}).always(function(){
		});
	}
}

function generaJS(tabla){
	if(tabla == null){
		$("#modalError").modal('show');
	}else{
		tabla.toLowerCase();
		tabla = tabla.substring(0, 1).toUpperCase().concat(tabla.substring(1).toLowerCase());
		info = JSON.parse(localStorage.getItem("detalleTabla"));
		$.ajax({
			url: urlServer + elIndex + 'archivos/generaJs',
			type: 'POST',
			dataType: 'json',
			data: {
				tabla: tabla,
				info: JSON.stringify(info)
			}
		}).done(function(res){
			$("#divMensajes").html(`Archivo creado: app\\js\\${tabla}.js<br>`);
			$("#cJ" + tabla.toLowerCase()).html(`<span class="fa fa-check"></span>`);
			$("#cuantos").val(parseInt($("#cuantos").val()) + 1);
			revisa();
		}).fail(function(){
		}).always(function(){
		});
	}
}

function generarArchivos(){
	$('input[type=checkbox]:checked').each(function(){
		$("#todos").val(parseInt($("#todos").val()) + 1);
		switch($(this).prop("id")){
			case 'controladores':
				generaController($(this).val());
				break;
			case 'modelos':
				generaModel($(this).val());
				break;
			case 'js':
				generaJS($(this).val());
				break;
		}
	});
	if(parseInt($("#todos").val()) == 0){
		$("#modalError").modal('show');
	}
}

function resetForm(){
	$("#divBtnZip").addClass('oculto');
	$("#tcontenidoTablas").html('');
	$("#divMensajes").html('');
}

function revisa(){
	if(parseInt($("#todos").val()) == parseInt($("#cuantos").val())){
		$("#divBtnZip").removeClass('oculto');
		$("#divMensajes").html(`<button class="btn btn-outline-primary" type="button" id="btnDescargar">Genera Link de Descarga</button>`);
		$("#btnDescargar").click(function(){
			descargar();
		});
	}
}

function SeleccionaTodos(tipo){
	checkboxes = $("." + tipo);
	estado = 0;
	for(i = 0, n = checkboxes.length; i < n; i++){
		if(estado == 0){
			estado = 1;
			estadoNow = !checkboxes[i].checked;
		}
		checkboxes[i].checked = estadoNow;
	}
}
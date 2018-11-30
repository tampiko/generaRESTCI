$(document).ready(function(){
	getBaseDatosAll();
});

$("#bdatos").change(function(){
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
	btnGeneraJS();
});

$("#btnDescargar").click(function(){
	descargar();
});

$("#btnGeneraDoble").click(function(){
	/* Crear pero con promesas */
	generaController();
	generaModel();
});

$('#modalError').on('shown.bs.modal', function(e){
	setTimeout(function(){
		$("#modalError").modal('hide');
	}, 1500);
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
		$("#divMensajes").append(`<a target="_blank" href="${res.url}${urlServer}gens/${res.zip}">Descargar</a><br>`);
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
		$.each(res.data, function(index, el){
			$("#tabla").append(`<option value="${el.table_name}">${el.table_name}</option>`);
		});
		$("#tabla").val(0);
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

function generaController(){
	tabla = $("#tabla").val();
	console.log(tabla);
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
			$("#divMensajes").append(`Archivo creado: application\\controllers\\${tabla}.php<br>`);
		}).fail(function(){
		}).always(function(){
		});
	}
}

function generaModel(){
	tabla = $("#tabla").val();
	console.log(tabla);
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
			$("#divMensajes").append(`Archivo creado: application\\models\\${tabla}_model.php<br>`);
		}).fail(function(){
		}).always(function(){
		});
	}
}

function btnGeneraJS(){
	tabla = $("#tabla").val();
	console.log(tabla);
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
			$("#divMensajes").append(`Archivo creado: app\\js\\${tabla}.js<br>`);
		}).fail(function(){
		}).always(function(){
		});
	}
}
var elIndex = 'index.php/';
var urlServer = '/gencode/';

function cargaScript(url, callback){
	var d = new Date();
	var script = document.createElement('script');
	if(script.readyState){/* IE */
		script.onreadystatechange = function(){
			if(script.readyState === 'loaded' || script.readyState === 'complete'){
				script.onreadystatechange = null;
				callback();
			}
			var d = new Date();
		};
	}else{/* Others */
		script.onload = function(){
			callback();
		};
	}
	script.src = urlServer + url + "?fecha=" + d.getTime()*15873;
	document.getElementsByTagName('head')[0].appendChild(script);
}

$("#btnGeneraModel").click(function(event){
	generaModel();
});

$("#btnGeneraController").click(function(event){
	generaController();
});

$("#btnDescargar").click(function(event){
	descargar();
});

$('#modalError').on('shown.bs.modal', function(e){
	setTimeout(function(){
		$("#modalError").modal('hide');
	}, 1500);
});

function descargar(){
	$.ajax({
		url: urlServer + elIndex + 'Archivos/generaZip',
		type: 'GET',
		dataType: 'json',
		data: {}
	}).done(function(res){
		
		$("#divMensajes").append(`<a target="_blank" href="${res.url}${urlServer}gens/${res.zip}">Descargar</a><br>`);
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
			dataType: 'html',
			data: {
				tabla: tabla,
				info: JSON.stringify(info)
			}
		}).done(function(res){
			$("#divMensajes").append(`Controller ${tabla} creado.<br>`);
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
			dataType: 'html',
			data: {
				tabla: tabla,
				info: JSON.stringify(info)
			}
		}).done(function(res){
			$("#divMensajes").append(`Model ${tabla} creado.<br>`);
		}).fail(function(){
		}).always(function(){
		});
	}
}

var elIndex = 'index.php/';
var urlServer = '/generacodesrv/';

$(document).ready(function(){
	getBaseDatosAll();
});

$("#bdatos").change(function(){
	getTablasDeBD();
});

$("#tabla").change(function(){
	getInfoTabla();
});

function getBaseDatosAll(){
	$.ajax({
		url: 'bdatos/getBdatos',
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
		url: 'Tabla/getTablasBD',
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
		// console.log(JSON.parse(localStorage.getItem("detalleTabla")));
	}).fail(function(){
	}).always(function(){
	});
}

$(function(){
	$('[data-toggle="tooltip"]').tooltip()
})
var index = 'index.php/';
//var index = '';
function cargaVista(url,destino){
	$.ajax({
		url: index+url,
		type: 'GET',
		dataType: 'HTML'
	})
	.done(function(vista) {
		console.log("cargaVista - success");
		$("#"+destino).html('');
		$("#"+destino).html(vista);
	})
	.fail(function() {
		console.log("cargaVista - error");
	})
	.always(function() {
		console.log("cargaVista - complete");
	});
}
function loadScript(url, callback) {
	var d = new Date();
	var script = document.createElement('script');
	if (script.readyState) { /* IE*/
		script.onreadystatechange = function() {
			if (script.readyState === 'loaded' || script.readyState === 'complete') {
				script.onreadystatechange = null;
				callback();
			}
			var d = new Date();
		};
	} else { /* Others*/
		script.onload = function() {
			/*callback();*/
		};
	}
	script.src = url + "?fecha=" + d.getTime();
	document.getElementsByTagName('head')[0].appendChild(script);
}

function cargaUrl(urld, menuId) {
	var d = new Date();
	$.ajax({
		url: urld + '&idp' + d.getTime()
		//data: {param1: 'value1'},
	})
	.done(function(data) {
		console.log("loadScript - success");
		$("#contenido").html(data);
		$(".dropdown").removeClass("menuActivo");
		$('li').removeClass("menuActivo");
		$("#menu_" + menuId).addClass("menuActivo");
		//callback();
	})
	.fail(function() {
		console.log("loadScript - error");
	})
	.complete(function() {
		console.log("loadScript - complete");
	});
}

function dimeFecha(laFecha) {
	//console.log("La Fecha a Convertir: " + laFecha);
	var monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
	var dias = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
	if (laFecha === null) {
		f_texto = "";
	} else {
		fecha = laFecha.split('/');
		var day = fecha[0];
		var monthIndex = fecha[1] - 1;
		var year = fecha[2];
		var date = new Date();
		date.setDate(day);
		date.setFullYear(year);
		date.setMonth(monthIndex);
		var dia = date.getDay();
		f_texto = dias[dia] + ', ' + day + ' de ' + monthNames[monthIndex] + ' del ' + year;
	}
	return f_texto;
}

function loadCss(url, callback) {
	var d = new Date();
	var script = document.createElement('link');
	if (script.readyState) { // IE
		script.onreadystatechange = function() {
			if (script.readyState === 'loaded' || script.readyState === 'complete') {
				script.onreadystatechange = null;
				callback();
			}
			var d = new Date();
		};
	} else { // Others
		script.onload = function() {
			callback();
		};
	}
	script.media_type = "text/css";
	script.rel = "stylesheet";
	script.href = url + "?fecha=" + d.getTime();
	document.getElementsByTagName('head')[0].appendChild(script);
}

function validaCURP(campo) {
	$("#" + campo).val($("#" + campo).val().toUpperCase());
	var lacurp = $("#" + campo).val();
	var curpbase = "^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$";
	var msg = "";
	if (lacurp) {
		if (!lacurp.match(curpbase)) {
			$("#" + campo).focus();
			$("#" + campo).focus();
			msg = "CURP no valida.\n"
		}
	}
	return msg;
}

function soloEnteros(campo) {
	var dato = $("#" + campo).val();
	var numerobase = "^(?:\+|-)?\d+$";
	alert(dato.match(numerobase));
}

function DaFormatoTabla(nombreTabla){
	$('#'+nombreTabla).DataTable({
		responsive: {
			details: {
				type: 'column',
				target: 'tr'
			}
		},
		// PARA DEFINIR QUE COLUMNAS NO SERAN ORGANIZABLES
		//		columnDefs: [ {
		//			className: 'control',
		//			orderable: false,
		//			targets:   1
		//		} ],
		// PARA DEFINIR QUE COLUMNAS NO SERAN ORGANIZABLES
		order: [ 1, 'asc' ],
		bFilter: true,
		bLengthChange: true,
		pagingType: "simple",
		"paging": true,
		"searching": true,
		"language": {
			"info": " _START_ - _END_ de _TOTAL_ ",
			"sLengthMenu": "<span class='custom-select-title'>Registros por página:</span> <span class='custom-select'> _MENU_ </span>",
			"sSearch": "",
			"sSearchPlaceholder": "Filtrar",
			"paginate": {
				"sNext": " ",
				"sPrevious": " "
			},
		},
		dom:
		"<'pmd-card-title'<'data-table-title-responsive'><'search-paper pmd-textfield'f>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'pmd-card-footer' <'pmd-datatable-pagination' l i p>>",
	});
	/// Select value
	$('.custom-select-info').hide();
}

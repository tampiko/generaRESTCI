$("#btnNvoDocumento").click(function(event) {
	limpiaFrmTipoDoc();
	$("#frmNuevoDocumento").modal('show');
});

$('#frmNuevoDocumento').on('shown.bs.modal', function (e) {
	//Se ejecuta al terminar de mostrar el modal
	$("#aplica").focus();
	$("#nombreDocumento").focus();
})

$("#btnAddTipoDoc").click(function(event) {
	guadaTipoDocumento();
});

$(document).ready(function() {
	getTipoDocumentos();
});

function getTipoDocumentos(){
	$.ajax({
		url 			: index + 'catalogos/getDocumentos',
		type 			: 'GET',
		dataType 	: 'JSON'
	})
	.done(function(res) {
		console.log("success");
		$("#divTablaTipoDocumentos").html('');
		codigo = '';
		codigo += '<table id="tablaTipoDocumentos" class="table pmd-table table-hover table-striped display responsive nowrap" cellspacing="0" width="100%">'+
		'<thead>'+
		'<tr>'+
		'<th> Tipo de Documento </th>'+
		'<th> Aplica </th>'+
		'<th> Acciones</th>'+
		'</tr>'+
		'</thead>'+
		'<tbody>';
		$.each(res.datos,function(index, val) {
			codigo+='<tr>'+
			'<td>'+val.tdc_nombre+'</td>'+
			'<td>'+val.tdc_aplica_desc+'</td>'+
			'<td>'+
			'<button class="btn btn-sm pmd-btn-fab pmd-btn-raised pmd-ripple-effect btn-info" type="button" onClick="editaTDocumento('+val.tdc_id+', \''+val.tdc_nombre+'\', '+val.tdc_aplica+');">'+
			'<i class="material-icons pmd-sm">mode_edit</i>'+
			'</button>'+
			'</td>'+
			'</tr>';
		});
		codigo += '</tbody></table>';
		$("#divTablaTipoDocumentos").html(codigo);
		DaFormatoTabla('tablaTipoDocumentos');
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}
function editaTDocumento(tdc_id, tdc_nombre, tdc_aplica){
	$("#idTipoDocumento").val(tdc_id);
	$("#nombreDocumento").val(tdc_nombre);
	$("#aplica").val(tdc_aplica);
	$("#frmNuevoDocumento").modal('show');
}

function guadaTipoDocumento(){
	tdc_id 			= $("#idTipoDocumento").val();
	tdc_nombre 	= $("#nombreDocumento").val();
	tdc_aplica 	= $("#aplica").val();
	error 			= 0;

	if(!tdc_nombre){
		error = 1;
		msg 	= "Faltó el nombre del documento."
	}

	if(error == 1){
		$("#mensajeError").html(msg);
		$("#simple-dialog").modal('show');
	}else{
		$.ajax({
			url 			: index + 'catalogos/guardaDocumento',
			type 			: 'POST',
			dataType	: 'JSON',
			data 			: {
				tdc_id			: tdc_id,
				tdc_nombre	: tdc_nombre,
				tdc_aplica	: tdc_aplica
			}
		})
		.done(function(res) {
			console.log("success" + res);
			getTipoDocumentos();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}
}

function limpiaFrmTipoDoc(){
	$("#idTipoDocumento").val(0);
	$("#nombreDocumento").val('');
	$("#aplica").val(1);
}

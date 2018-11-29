$("#btnNvoUsuario").click(function(event) {
  limpiaFrmUsuario();
  $("#frmNuevoUsuario").modal('show');
});

$("#btnAddUsuario").click(function(event) {
  addUsuario();
});

$(document).ready(function() {
  getUsuarios();
});

function getUsuarios(){
  $.ajax({
    url      : index + 'catalogos/getUsuarios',
    type     : 'GET',
    dataType : 'JSON'
  })
  .done(function(res) {
    console.log("success");
    $("#divTablaUsuarios").html('');
		codigo = '';
		codigo += '<table id="tablaUsuarios" class="table pmd-table table-hover table-striped display responsive nowrap" cellspacing="0" width="100%">'+
		'<thead>'+
		'<tr>'+
		'<th> Nombre </th>'+
		'<th> Usuario </th>'+
		'<th> Correo </th>'+
    //'<th> Activo </th>'+
		'<th> Acciones</th>'+
		'</tr>'+
		'</thead>'+
		'<tbody>';
		$.each(res.usuarios,function(index, val) {
			codigo+='<tr>'+
			'<td>'+val.usu_nombre+' '+val.usu_paterno+' '+val.usu_materno+'</td>'+
			'<td>'+val.usu_usuario+'</td>'+
			'<td>'+val.usu_correo+'</td>'+
			//'<td>'+val.usu_activo+'</td>'+
			'<td>'+

      '<button class="btn btn-sm pmd-btn-fab pmd-btn-raised pmd-ripple-effect btn-info" type="button" '+
      'onClick="editaUsuario('+val.usu_id+', \''+val.usu_nombre+'\', \''+val.usu_paterno+'\', \''+val.usu_materno+'\', \''+val.usu_usuario+'\', \''+val.usu_correo+'\', '+val.usu_activo+');">'+
			'<i class="material-icons pmd-sm">mode_edit</i>'+
			'</button>'+

      //'<button class="btn btn-sm pmd-btn-fab pmd-btn-raised pmd-ripple-effect btn-info" type="button" '+
      //'onClick="verPermisos('+val.usu_id+');">'+
			//'<i class="material-icons pmd-sm">assignment</i>'+
			//'</button>'+

      '</td>'+
			'</tr>';
		});
		codigo += '</tbody></table>';
		$("#divTablaUsuarios").html(codigo);
		DaFormatoTabla('tablaUsuarios');
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
}

function editaUsuario(usu_id, usu_nombre, usu_paterno, usu_materno, usu_usuario, usu_correo, usu_activo){
  usu_id      = $("#usu_id").val(usu_id);
  usu_nombre  = $("#usu_nombre").val(usu_nombre);
  usu_paterno = $("#usu_paterno").val(usu_paterno);
  usu_materno = $("#usu_materno").val(usu_materno);
  usu_usuario = $("#usu_usuario").val(usu_usuario);
  usu_correo  = $("#usu_correo").val(usu_correo);
  usu_activo  = $("#usu_activo").val(usu_activo);
  $("#frmNuevoUsuario").modal('show');
}

$('#frmNuevoUsuario').on('shown.bs.modal', function (e) {//Se ejecuta al terminar de mostrar el modal
  $("#usu_activo").focus();
  $("#usu_correo").focus();
  $("#usu_usuario").focus();
  $("#usu_materno").focus();
  $("#usu_paterno").focus();
  $("#usu_nombre").focus();
})

function limpiaFrmUsuario(){
  $("#usu_id").val(0);
  $("#usu_nombre").val('');
  $("#usu_paterno").val('');
  $("#usu_materno").val('');
  $("#usu_usuario").val('');
  $("#usu_correo").val('');
  $("#usu_activo").val('');
}

function addUsuario(){
  usu_id      = $("#usu_id").val();
  usu_nombre  = $("#usu_nombre").val();
  usu_paterno = $("#usu_paterno").val();
  usu_materno = $("#usu_materno").val();
  usu_usuario = $("#usu_usuario").val();
  usu_correo  = $("#usu_correo").val();
  usu_activo  = $("#usu_activo").val();
  error       = 0;
  msg         = "Formulario Incompleto.<br>"

  if(!usu_nombre){  msg += 'Falta nombre.<br>';   error = 1; }
  if(!usu_paterno){ msg += 'Falta paterno.<br>';  error = 1; }
  if(!usu_materno){ msg += 'Falta materno.<br>';  error = 1; }
  if(!usu_usuario){ msg += 'Falta usuario.<br>';  error = 1; }
  if(!usu_correo){  msg += 'Falta correo.<br>';   error = 1; }

  if(error == 1){
    $("#mensajeError").html(msg);
		$("#simple-dialog").modal('show');
  }else{
    $.ajax({
      url      : index + 'catalogos/addUsuario',
      type     : 'POST',
      dataType : 'JSON',
      data     : {
        usu_id       : usu_id,
        usu_nombre   : usu_nombre,
        usu_paterno  : usu_paterno,
        usu_materno  : usu_materno,
        usu_usuario  : usu_usuario,
        usu_password : calcMD5(usu_usuario),
        usu_correo   : usu_correo,
        usu_activo   : usu_activo
      }
    })
    .done(function() {
      console.log("success");
      limpiaFrmUsuario();
      $("#frmNuevoUsuario").modal('hide');
      getUsuarios();
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
  }
}

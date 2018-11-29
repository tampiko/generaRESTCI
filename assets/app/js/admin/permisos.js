$("#usu_id").change(function(event) {
  getPermisosUsuario();
});

$(document).ready(function() {
  getUsuariosPermisos();

});

function getUsuariosPermisos(){
  $.ajax({
    url: index + 'catalogos/getUsuarios',
    type: 'GET',
    dataType: 'JSON'
  })
  .done(function(res) {
    console.log("success");
    $("#usu_id").html('');
    $.each(res.usuarios,function(index, val) {
      $("#usu_id").append('<option value="'+val.usu_id+'">'+val.usu_nombre+' '+val.usu_paterno+' '+val.usu_materno+'</option>');
    });
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
}

function getPermisosUsuario(){
  usu_id = $("#usu_id").val();
  $.ajax({
    url: index + 'admin/getPermisosUsuario/' + usu_id,
    type: 'GET',
    dataType: 'JSON'
  })
  .done(function(res) {
    console.log("success");
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });

}

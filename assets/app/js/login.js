$("#btnLoginAccesar").on('click', function(){
	login();
});

$(document).ready(function(){
});

function login(){
	txtUsuario = $("#txtUsuario").val();
	txtPass = $("#txtPass").val();
	txtPass = calcMD5(txtPass);
	
	$.ajax({
			url: index + 'admin/autenticar',
			type: 'POST',
			dataType: 'JSON',
			data: {
				usuario: txtUsuario,
				pass: txtPass
			}
		})
		.done(function(info){
			console.log("success");
			if(info['error']){
				$("#txtMensajeError").html(info['mensaje']);
				$("#txtMensajeError").show(250);
			}else{
				$("#txtMensajeError").html('');
				$("#txtMensajeError").hide(250);
				$(".ocultaPrimero").show();
				$("#divNombreUsuario").html(ucFirstAllWords(info['datos']['usu_nombre'].toLowerCase() + ' ' + info['datos']['usu_paterno'].toLowerCase() + ' ' + info['datos']['usu_materno'].toLowerCase()));
				$("#divNombreUsuario").attr('nowrap', 'nowrap');//No se si funcione debe se para que ajuste el nombre
				$("#divContenido").html('');
				//console.log(info);
			}
		})
		.fail(function(){
			console.log("error");
		})
		.always(function(){
			console.log("complete");
		});
}

function ucFirstAllWords(str){
	var pieces = str.split(" ");
	for(var i = 0; i < pieces.length; i++){
		var j = pieces[i].charAt(0).toUpperCase();
		pieces[i] = j + pieces[i].substr(1);
	}
	return pieces.join(" ");
}

var elIndex = 'index.php/';
var urlServer = '/generacodesrv/';

function getAllModulo(){
	cor_id = $('cor_id').val();
	cor_cli_id = $('cor_cli_id').val();
	cor_correo = $('cor_correo').val();
	cor_descripcion = $('cor_descripcion').val();
	$.ajax({
		url: urlServer + elIndex +'Modulo/getAllModulo',
		type: 'GET',
		dataType: 'JSON',
		data: {
			cor_id : cor_id,
			cor_cli_id : cor_cli_id,
			cor_correo : cor_correo,
			cor_descripcion : cor_descripcion,
			apkiKey: '11071981'
		}
	})
	.done(function(res){
		console.log('success');
	})
	.fail(function() {
		console.log('error');
	})
	.always(function() {
		console.log('complete');
	});
}

function addModulo(){
	$.ajax({
		url: urlServer + elIndex +'Modulo/addModulo',
		type: 'POST',
		dataType: 'JSON',
		data: {
			apkiKey: '11071981'
		}
	})
	.done(function(res){
		console.log('success');
	})
	.fail(function() {
		console.log('error');
	})
	.always(function() {
		console.log('complete');
	});
}

function updModulo(){
	$.ajax({
		url: urlServer + elIndex +'Modulo/updModulo',
		type: 'POST',
		dataType: 'JSON',
		data: {
			apkiKey: '11071981'
		}
	})
	.done(function(res){
		console.log('success');
	})
	.fail(function() {
		console.log('error');
	})
	.always(function() {
		console.log('complete');
	});
}

function delModulo(){
	$.ajax({
		url: urlServer + elIndex +'Modulo/delModulo',
		type: 'POST',
		dataType: 'JSON',
		data: {
		apkiKey: '11071981'
		}
	})
	.done(function(res){
		console.log('success');
	})
	.fail(function() {
		console.log('error');
	})
	.always(function() {
		console.log('complete');
	});
}
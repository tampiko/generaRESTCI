$(document).ready(function() {
	getInfoData();
});

function cargaExamen(exa_id) {
	$.ajax({
			url: index + 'Examen/getPreguntasExamen/' + exa_id,
			type: 'GET',
			dataType: 'JSON'
		})
		.done(function(preguntas) {
			console.log("success");
			$("#divSeccionPreguntasTodas").html('');
			laPregunta = 0;
			$("#segundosRestantes").val(preguntas.preguntas.tiempo * 60);
			$.each(preguntas.preguntas.preguntas, function(index, val) {
				laPregunta++;
				laClaseBoton = 'btn-default';
				if (val.res_exa_respuesta_id != null) {
					laClaseBoton = 'btn-info';
				}
			$("#divSeccionPreguntasTodas").append('<button id="btnExa_' + val.exa_pregunta_id +
					'" class="btnPregunta btn-sm btn pmd-btn-raised pmd-ripple-effect ' + laClaseBoton + '" type="button" ' +
					'onclick="getLaPregunta(' + val.exa_pregunta_id + ',' + laPregunta + ')">' + laPregunta + '</button>');
			});
			$("#divSeccionPreguntasTodas").append('</div>');
			setTiempo();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function getInfoData() {
	$.ajax({
			url: index + 'Examen/getInfoData',
			type: 'GET',
			dataType: 'JSON'
		})
		.done(function(info) {
			console.log("success");
			$("#lblMatriculaNombre").html(info.matricula + ' - ' + info.nombre_alumno);
			$("#lblBloqueDescripcion").html(info.bloque);
			cargaExamen(info.id_examen);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function getLaPregunta(id, laPregunta) {
	$.ajax({
			url: index + 'Examen/getLaPregunta/' + id,
			type: 'GET',
			dataType: 'JSON'
		})
		.done(function(pregunta) {
			console.log("success");
			$("#divSeccionPregunta").html('<strong>Pregunta ' + laPregunta + '</strong>' + pregunta.pregunta[0].exa_pregunta);
			if (pregunta.pregunta[0].exa_imagen_pregunta != '') {
				$("#divSeccionPregunta").append('<img src="assets/app/img/' + pregunta.pregunta[0].exa_imagen_pregunta + '">');
			}
			id_respuesta_contestada = 0;
			if (pregunta.pregunta[0].res_exa_respuesta_id != null) {
				id_respuesta_contestada = pregunta.pregunta[0].res_exa_respuesta_id;
			}
			$("#divSeccionRespuestas").html('');
			$.each(pregunta.pregunta, function(index, val) {
				imgCodigo = '';
				if (val.exa_imagen_respuesta != '') {
					imgCodigo = '<div class="pmd-card-media"><img src="assets/app/img/' + val.exa_imagen_respuesta + '"></div>';
				}


				laClaseDelBoton = 'pmd-card-default';
				if (val.exa_respuesta_id == id_respuesta_contestada) {
					laClaseDelBoton = 'pmd-card-inverse';
				}

				$("#divSeccionRespuestas").append('' +
					'<div class="pmd-card ' + laClaseDelBoton + ' pmd-z-depth-3 btnRespuesta" ' +
					'id="res_' + val.exa_respuesta_id + '"' +
					'onclick="guardaRespuesta(' + id + ',' + val.exa_respuesta_id + ');">' +
					'<div class="pmd-card-title">' +
					'<h2 class="pmd-card-title-text">Respuesta ' + val.exa_inciso + ':</h2>' +
					'</div>	' +
					'<div class="pmd-card-body">' +
					val.exa_respuesta +
					imgCodigo +
					'</div>' +
					'</div>');
			});
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function guardaRespuesta(pre_id, res_id) {
	$.ajax({
			url: index + 'Examen/respondePregunta/' + pre_id + '/' + res_id,
			type: 'GET',
			dataType: 'JSON'
		})
		.done(function(res) {
			console.log("success");
			$(".btnRespuesta").removeClass('pmd-card-inverse');
			$(".btnRespuesta").addClass('pmd-card-default');
			$("#res_" + res_id).addClass('pmd-card-inverse');
			$("#btnExa_" + pre_id).removeClass('btn-default');
			$("#btnExa_" + pre_id).addClass('btn-info');
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function terminarExamen() {
	$.ajax({
			url: index + 'Examen/terminaExamen',
			type: 'GET',
			dataType: 'JSON'
		})
		.done(function() {
			console.log("success");
			$(location).attr('href','');
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
}

function setTiempo() {
	countdown = setInterval(function() {
		count = $("#segundosRestantes").val();
		$("#segundosRestantes").val($("#segundosRestantes").val() - 1);
		var mins = Math.floor(count / 60);
		var hora = Math.floor(mins / 60);
		segu = count - (mins * 60);
		mins = mins - (hora * 60);
		ceroSeg = '';
		segu = String(segu);
		if (segu.length == 1) {
			ceroSeg = "0";
		}
		ceroMin = '';
		mins = String(mins);
		if (mins.length == 1) {
			ceroMin = "0";
		}
		$("#divTiempo").html("" + hora + ':' + ceroMin + "" + mins + ":" + ceroSeg + "" + segu + "");
		if (count == 0) {
			alert('El Tiempo se Termino.');
			terminarExamen();
		}
		count--;
	}, 1000);
}
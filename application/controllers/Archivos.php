<?php
defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');

class Archivos extends REST_Controller{
	
	public function __construct(){
		parent ::__construct();
	}
	
	public function index_GET(){
		$res = array(
			'Sistema'    => 'CI - GeneraCode',
			'Controller' => 'Archivos'
		);
		$this -> response($res);
	}
	
	public function generaZip_GET(){
		/* Nombre del Archivo Generado */
		$archivoZip = 'CodeGen.zip';
		
		/* Para guardar los archivos que se agregan (version 2, agergar y eliminar archivos mediante esta variable) */
		$archivos = array();
		
		/* Directorio a crear en el zip */
		$dirC = 'application/controllers';
		
		/* Directorio a crear en el zip */
		$dirM = 'application/models';
		
		/* Genera un archivo zip para despues crear uno limpio */
		$zip_old = new ZipArchive();
		$zip_old -> open("gens/" . $archivoZip, ZipArchive::CREATE);
		$zip_old -> addEmptyDir($dirC);
		$zip_old -> addEmptyDir($dirM);
		$zip_old -> close();
		unlink('gens/' . $archivoZip);
		$zip_new = new ZipArchive();
		$zip_new -> open("gens/" . $archivoZip, ZipArchive::CREATE);
		$zip_new -> addEmptyDir($dirC);
		$zip_new -> addEmptyDir($dirM);
		$modelos = dir('gens/application/models');
		while(($file = $modelos -> read()) !== FALSE){
			if($file != "." && $file != ".."){
				$zip_new -> addFile("gens/application/models/" . $file, "application/models/" . $file);
				array_push($archivos, "gens/application/models/" . $file);
			}
		}
		$modelos -> close();
		$controladores = dir('gens/application/controllers');
		while(($file = $controladores -> read()) !== FALSE){
			if($file != "." && $file != ".."){
				$zip_new -> addFile("gens/application/controllers/" . $file, "application/controllers/" . $file);
				array_push($archivos, "gens/application/controllers/" . $file);
			}
		}
		$controladores -> close();
		$zip_new -> close();
		
		
		$modelos = dir('gens/application/models');
		while(($file = $modelos -> read()) !== FALSE){
			if($file != "." && $file != ".."){
				unlink("gens/application/models/" . $file);
			}
		}
		$modelos -> close();
		
		$controladores = dir('gens/application/controllers');
		while(($file = $controladores -> read()) !== FALSE){
			if($file != "." && $file != ".."){
				unlink("gens/application/controllers/" . $file);
			}
		}
		$controladores -> close();
		
		$page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
		
		$res = array(
			'error'    => FALSE,
			'archivos' => $archivos,
			'url'      => $page_url,
			'zip'      => $archivoZip
		);
		$this -> response($res);
	}
	
	public function generaController_POST(){
		$tabla     = $this -> post('tabla');
		$json_data = json_decode($this -> post('info'), TRUE);
		$archivo   = $tabla . ".php";
		$campos    = '';
		$pk        = '';
		$int       = array();
		foreach($json_data as $x => $x_value){
			if($x_value['data_type'] == 'int'){
				array_push($int, $x_value['column_name']);
			}
			if($x_value['column_key'] == 'PRI'){
				$pk = $x_value['column_name'];
			}
			if(strlen($campos) != 0){
				$campos .= ", " . $x_value['column_name'];
			}else{
				$campos .= $x_value['column_name'];
			}
		}
		$cuantosIntegers = count($int);
		$modelo          = $tabla . "_model";
		$model           = "M" . $tabla;
		
		$txt  = fopen('./gens/application/controllers/' . $archivo, 'w');
		$code = '';
		$code .= "<?php";
		$code .= "\n" . "defined('BASEPATH') or exit('No direct script access allowed');";
		$code .= "\n" . "require(APPPATH.'/libraries/REST_Controller.php');";
		$code .= "\n" . "class $tabla extends REST_Controller{";
		$code .= "\n";
		$code .= "\n\t" . "public function __construct(){";
		$code .= "\n\t\t" . "parent::__construct();";
		$code .= "\n\t\t" . "\$this -> load -> model('$modelo', '$model');";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "public function index_GET(){";
		$code .= "\n\t\t" . "\$res = array(";
		$code .= "\n\t\t\t" . "'Creado'     => 'CI - GeneraCode',";
		$code .= "\n\t\t\t" . "'Tipo'       => 'GET',";
		$code .= "\n\t\t\t" . "'Controller' => '$tabla'";
		$code .= "\n\t\t" . ");";
		$code .= "\n\t\t" . "\$this -> response(\$res);";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "public function index_POST(){";
		$code .= "\n\t\t" . "\$res = array(";
		$code .= "\n\t\t\t" . "'Creado'     => 'CI - GeneraCode',";
		$code .= "\n\t\t\t" . "'Tipo'       => 'POST',";
		$code .= "\n\t\t\t" . "'Controller' => '$tabla'";
		$code .= "\n\t\t" . ");";
		$code .= "\n\t\t" . "\$this -> response(\$res);";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "function getAll$tabla" . "_GET(){";
		$code .= "\n\t\t" . "\$info = \$this -> get();";
		$code .= "\n\t\t" . "unset(\$info['apiKey']);";
		$code .= "\n\t\t" . "\$$tabla = \$this -> $model -> getAll$tabla();";
		$code .= "\n\t\t" . "\$respuesta = array(";
		$code .= "\n\t\t\t" . "'error' => false,";
		$code .= "\n\t\t\t" . "'mensaje' => '$tabla loaded',";
		$code .= "\n\t\t\t" . "'data' => \$$tabla";
		$code .= "\n\t\t" . ");";
		$code .= "\n\t\t" . "\$this -> response(\$respuesta);";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "function get$tabla" . "_GET(){";
		$code .= "\n\t\t" . "\$info = \$this -> get();";
		$code .= "\n\t\t" . "unset(\$info['apiKey']);";
		$code .= "\n\t\t" . "\$where = array('$pk'=>info['$pk']);";
		$code .= "\n\t\t" . "\$$tabla = \$this -> $model -> get$tabla(\$where);";
		$code .= "\n\t\t" . "\$respuesta = array(";
		$code .= "\n\t\t\t" . "'error' => false,";
		$code .= "\n\t\t\t" . "'mensaje' => '$tabla loaded',";
		$code .= "\n\t\t\t" . "'data' => \$$tabla";
		$code .= "\n\t\t" . ");";
		$code .= "\n\t\t" . "\$this -> response(\$respuesta);";
		$code .= "\n\t" . "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\tfunction add$tabla" . "_POST(){";
		$code .= "\n\t\t" . "\$info = \$this -> post();";
		$code .= "\n\t\t" . "unset(\$info['apiKey']);";
		$code .= "\n\t\t" . "\$this -> $model -> add$tabla(\$info);";
		$code .= "\n\t\t" . "\$respuesta = array(";
		$code .= "\n\t\t\t" . "'error' => false,";
		$code .= "\n\t\t\t" . "'mensaje' => '$tabla added.'";
		$code .= "\n\t\t" . ");";
		$code .= "\n\t\t" . "\$this -> response(\$respuesta);";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "function upd$tabla" . "_POST(){";
		$code .= "\n\t\t" . "\$info = \$this -> post();";
		$code .= "\n\t\t" . "\$$pk = \$info['$pk'];";
		$code .= "\n\t\t" . "unset(\$info['apiKey']);";
		$code .= "\n\t\t" . "unset(\$info['$pk']);";
		$code .= "\n\t\t" . "\$this -> $model -> upd$tabla(\$$pk, \$info);";
		$code .= "\n\t\t" . "\$respuesta = array(";
		$code .= "\n\t\t\t" . "'error'   => false,";
		$code .= "\n\t\t\t" . "'mensaje' => '$tabla updated.'";
		$code .= "\n\t\t" . ");";
		$code .= "\n\t\t" . "\$this -> response(\$respuesta);";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "function del$tabla" . "_POST(){";
		$code .= "\n\t\t" . "\$info = \$this -> post();";
		$code .= "\n\t\t" . "\$$pk = \$info['$pk'];";
		$code .= "\n\t\t" . "unset(\$info['apiKey']);";
		$code .= "\n\t\t" . "unset(\$info['$pk']);";
		$code .= "\n\t\t" . "\$this -> $model -> del$tabla(\$$pk, \$info);";
		$code .= "\n\t\t" . "\$respuesta = array(";
		$code .= "\n\t\t\t" . "'error' => false,";
		$code .= "\n\t\t\t" . "'mensaje' => '$tabla deleted.'";
		$code .= "\n\t\t" . ");";
		$code .= "\n\t\t" . "\$this -> response(\$respuesta);";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n";
		$code .= "}";
		
		fwrite($txt, $code);
		fclose($txt);
		
		$respuesta = array(
			'Sistema'    => 'CI - GeneraCode',
			'Controller' => 'Archivos',
			'Funcion'    => 'generaController',
			'Tabla'      => $tabla,
			'Info'       => $json_data
		);
		$this -> response($respuesta);
		
	}
	
	public function generaModel_POST(){
		$tabla     = $this -> post('tabla');
		$json_data = json_decode($this -> post('info'), TRUE);
		$archivo   = $tabla . "_model.php";
		$campos    = '';
		$pk        = '';
		$int       = array();
		foreach($json_data as $x => $x_value){
			if($x_value['data_type'] == 'int'){
				array_push($int, $x_value['column_name']);
			}
			if($x_value['column_key'] == 'PRI'){
				$pk = $x_value['column_name'];
			}
			if(strlen($campos) != 0){
				$campos .= ", " . $x_value['column_name'];
			}else{
				$campos .= $x_value['column_name'];
			}
		}
		$cuantosIntegers = count($int);
		
		$txt  = fopen('./gens/application/models/' . $archivo, 'w');
		$code = '';
		$code .= "<?php";
		$code .= "\n" . "defined('BASEPATH') or exit('No direct script access allowed');";
		$code .= "\n" . "class $tabla" . "_model extends CI_Model{";
		$code .= "\n";
		$code .= "\n";
		
		foreach($json_data as $x => $x_value){
			$code .= "\t" . 'public $' . $x_value['column_name'] . ";";
			$code .= "\n";
		}
		
		$code .= "\n\t" . "public function __construct(){";
		$code .= "\n\t\t" . "parent::__construct();";
		$code .= "\n\t\t" . "\$this -> load -> database();";
		$code .= "\n\t" . "}";
		$code .= "\n";
		$code .= "\n\t" . "function getAll$tabla(){";
		$code .= "\n\t\t" . "\$this -> db -> select('$campos');";
		$code .= "\n\t\t" . "\$this -> db -> order_by('$pk','asc');";
		$code .= "\n\t\t" . "\$query = \$this -> db -> get('" . strtolower($tabla) . "');";
		$code .= "\n\t\t" . "\$rows = \$query -> custom_result_object('$tabla" . "_model');";
		
		if($cuantosIntegers > 0){
			$code .= "\n\t\t" . "foreach (\$rows as \$row) {";
			for($x = 0; $x < $cuantosIntegers; $x++){
				$code .= "\n\t\t\t" . "\$row -> $int[$x] = intval(\$row -> $int[$x]);";
			}
			$code .= "\n\t\t" . "}";
		}
		
		$code .= "\n\t\t" . "return \$rows;";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "function get$tabla(" . '$where' . "){";
		$code .= "\n\t\t" . "\$this -> db -> select('$campos');";
		$code .= "\n\t\t" . "\$this -> db -> where(\$where);";
		$code .= "\n\t\t" . "\$this -> db -> order_by('$pk','asc');";
		$code .= "\n\t\t" . "\$query = \$this -> db -> get('" . strtolower($tabla) . "');";
		$code .= "\n\t\t" . "\$rows = \$query -> custom_result_object('$tabla" . "_model');";
		
		if($cuantosIntegers > 0){
			$code .= "\n\t\t" . "foreach (\$rows as \$row) {";
			for($x = 0; $x < $cuantosIntegers; $x++){
				$code .= "\n\t\t\t" . "\$row -> $int[$x] = intval(\$row -> $int[$x]);";
			}
			$code .= "\n\t\t" . "}";
		}
		
		$code .= "\n\t\t" . "return \$rows;";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "function add$tabla(" . '$datos' . "){";
		$code .= "\n\t\t" . "\$this -> db -> insert('" . strtolower($tabla) . "', \$datos);";
		$code .= "\n\t\t" . "return \$this -> db -> insert_id();";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "function upd$tabla(\$$pk,\$datos){";
		$code .= "\n\t\t" . "\$this -> db -> where('$pk', \$$pk);";
		$code .= "\n\t\t" . "return \$this -> db -> update('" . strtolower($tabla) . "', \$datos);";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\t" . "function del$tabla(\$$pk,\$datos){";
		$code .= "\n\t\t" . "\$this -> db -> where('$pk', \$$pk);";
		$code .= "\n\t\t" . "return \$this -> db -> update('" . strtolower($tabla) . "', \$datos);";
		$code .= "\n\t" . "}";
		$code .= "\n";
		
		$code .= "\n\r" . "}";
		
		fwrite($txt, $code);
		fclose($txt);
		
		$respuesta = array(
			'Sistema'    => 'CI - GeneraCode',
			'Controller' => 'Archivos',
			'Funcion'    => 'generaModel',
			'Tabla'      => $tabla,
			'Info'       => $json_data
		);
		
		$this -> response($respuesta);
	}
	
	public function generaJs_POST(){
		$tabla     = $this -> post('tabla');
		$json_data = json_decode($this -> post('info'), TRUE);
		$archivo   = $tabla . ".js";
		$campos    = '';
		$pk        = '';
		$int       = array();
		
		foreach($json_data as $x => $x_value){
			if($x_value['data_type'] == 'int'){
				array_push($int, $x_value['column_name']);
			}
			if($x_value['column_key'] == 'PRI'){
				$pk = $x_value['column_name'];
			}
			if(strlen($campos) != 0){
				$campos .= ", " . $x_value['column_name'];
			}else{
				$campos .= $x_value['column_name'];
			}
		}
		$cuantosIntegers = count($int);
		
		
		$codigo = "";
		$codigo .= "" . "var elIndex = 'index.php/';";
		$codigo .= "\n" . "var urlServer = '/generacodesrv/';";
		$codigo .= "\n";
		$codigo .= "\n" . "function getAll${tabla}(){";
		foreach($json_data as $x => $x_value){
			$codigo .= "\n\t" . $x_value['column_name'] . " = \$('" . $x_value['column_name'] . "').val();";
		}
		$codigo .= "\n\t" . "$.ajax({";
		$codigo .= "\n\t\t" . "url: urlServer + elIndex +'$tabla/getAll$tabla',";
		$codigo .= "\n\t\t" . "type: 'GET',";
		$codigo .= "\n\t\t" . "dataType: 'JSON',";
		$codigo .= "\n\t\t" . "data: {";
		foreach($json_data as $x => $x_value){
			$codigo .= "\n\t\t\t" . $x_value['column_name'] . ' : ' . $x_value['column_name'] . ",";
		}
		$codigo .= "\n\t\t\t" . "apkiKey: '11071981'";
		$codigo .= "\n\t\t" . "}";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".done(function(res){";
		$codigo .= "\n\t\t" . "console.log('success');";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".fail(function() {";
		$codigo .= "\n\t\t" . "console.log('error');";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".always(function() {";
		$codigo .= "\n\t\t" . "console.log('complete');";
		$codigo .= "\n\t" . "});";
		$codigo .= "\n" . "}";
		$codigo .= "\n";
		$codigo .= "\n" . "function add${tabla}(){";
		foreach($json_data as $x => $x_value){
			$codigo .= "\n\t" . $x_value['column_name'] . " = \$('" . $x_value['column_name'] . "').val();";
		}
		$codigo .= "\n\t" . "$.ajax({";
		$codigo .= "\n\t\t" . "url: urlServer + elIndex +'${tabla}/add${tabla}',";
		$codigo .= "\n\t\t" . "type: 'POST',";
		$codigo .= "\n\t\t" . "dataType: 'JSON',";
		$codigo .= "\n\t\t" . "data: {";
		foreach($json_data as $x => $x_value){
			$codigo .= "\n\t\t\t" . $x_value['column_name'] . ' : ' . $x_value['column_name'] . ",";
		}
		$codigo .= "\n\t\t\t" . "apkiKey: '11071981'";
		$codigo .= "\n\t\t" . "}";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".done(function(res){";
		$codigo .= "\n\t\t" . "console.log('success');";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".fail(function() {";
		$codigo .= "\n\t\t" . "console.log('error');";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".always(function() {";
		$codigo .= "\n\t\t" . "console.log('complete');";
		$codigo .= "\n\t" . "});";
		$codigo .= "\n" . "}";
		$codigo .= "\n";
		$codigo .= "\n" . "function upd${tabla}(){";
		foreach($json_data as $x => $x_value){
			$codigo .= "\n\t" . $x_value['column_name'] . " = \$('" . $x_value['column_name'] . "').val();";
		}
		$codigo .= "\n\t" . "$.ajax({";
		$codigo .= "\n\t\t" . "url: urlServer + elIndex +'${tabla}/upd${tabla}',";
		$codigo .= "\n\t\t" . "type: 'POST',";
		$codigo .= "\n\t\t" . "dataType: 'JSON',";
		$codigo .= "\n\t\t" . "data: {";
		foreach($json_data as $x => $x_value){
			$codigo .= "\n\t\t\t" . $x_value['column_name'] . ' : ' . $x_value['column_name'] . ",";
		}
		$codigo .= "\n\t\t\t" . "apkiKey: '11071981'";
		$codigo .= "\n\t\t" . "}";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".done(function(res){";
		$codigo .= "\n\t\t" . "console.log('success');";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".fail(function() {";
		$codigo .= "\n\t\t" . "console.log('error');";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".always(function() {";
		$codigo .= "\n\t\t" . "console.log('complete');";
		$codigo .= "\n\t" . "});";
		$codigo .= "\n" . "}";
		$codigo .= "\n";
		$codigo .= "\n" . "function del${tabla}(){";
		foreach($json_data as $x => $x_value){
			$codigo .= "\n\t" . $x_value['column_name'] . " = \$('" . $x_value['column_name'] . "').val();";
		}
		$codigo .= "\n\t" . "$.ajax({";
		$codigo .= "\n\t\t" . "url: urlServer + elIndex +'${tabla}/del${tabla}',";
		$codigo .= "\n\t\t" . "type: 'POST',";
		$codigo .= "\n\t\t" . "dataType: 'JSON',";
		$codigo .= "\n\t\t" . "data: {";
		foreach($json_data as $x => $x_value){
			$codigo .= "\n\t\t\t" . $x_value['column_name'] . ' : ' . $x_value['column_name'] . ",";
		}
		$codigo .= "\n\t\t" . "apkiKey: '11071981'";
		$codigo .= "\n\t\t" . "}";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".done(function(res){";
		$codigo .= "\n\t\t" . "console.log('success');";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".fail(function() {";
		$codigo .= "\n\t\t" . "console.log('error');";
		$codigo .= "\n\t" . "})";
		$codigo .= "\n\t" . ".always(function() {";
		$codigo .= "\n\t\t" . "console.log('complete');";
		$codigo .= "\n\t" . "});";
		$codigo .= "\n" . "}";
		
		$txt = fopen('./gens/app/js/' . $archivo, 'w');
		fwrite($txt, $codigo);
		fclose($txt);
		$respuesta = array(
			"tabla"     => $tabla,
			"json_data" => $json_data,
			"archivo"   => $archivo
		);
		$this -> response($respuesta);
	}
}

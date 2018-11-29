<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

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
		$archivoZip = $_GET['nombre'];
		
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
			if($file != "." && $file != ".." && $file != ".gitkeep"){
				$zip_new -> addFile("gens/application/models/" . $file, "application/models/" . $file);
				array_push($archivos, "gens/application/models/" . $file);
			}
		}
		$modelos -> close();
		$controladores = dir('gens/application/controllers');
		while(($file = $controladores -> read()) !== FALSE){
			if($file != "." && $file != ".." && $file != ".gitkeep"){
				$zip_new -> addFile("gens/application/controllers/" . $file, "application/controllers/" . $file);
				array_push($archivos, "gens/application/controllers/" . $file);
			}
		}
		$controladores -> close();
		$zip_new -> close();
		
		$modelos = dir('gens/application/models');
		while(($file = $modelos -> read()) !== FALSE){
			if($file != "." && $file != ".." && $file != ".gitkeep"){
				unlink("gens/application/models/" . $file);
			}
		}
		$modelos -> close();
		
		$controladores = dir('gens/application/controllers');
		while(($file = $controladores -> read()) !== FALSE){
			if($file != "." && $file != ".." && $file != ".gitkeep"){
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
		$dirController = './gens/application/controllers/';
		$tabla         = $this -> post('tabla');
		$json_data     = json_decode($this -> post('info'), TRUE);
		$archivo       = $tabla . ".php";
		$campos        = '';
		$pk            = '';
		$enteros       = array();
		foreach($json_data as $x => $x_value){
			if($x_value['data_type'] == 'int'){
				array_push($enteros, $x_value['column_name']);
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
		//		$cuantosIntegers = count($enteros);
		$modelo = $tabla . "_model";
		$model  = "M" . $tabla;
		
		$txt = fopen($dirController . $archivo, 'c', 1);
		fwrite($txt, '<?php');
		fwrite($txt, "\n");
		fwrite($txt, "\n" . "use Restserver\Libraries\REST_Controller;");
		fwrite($txt, "\n");
		fwrite($txt, "\n" . "defined('BASEPATH') or exit('No direct script access allowed');");
		fwrite($txt, "\n" . "require APPPATH . 'libraries/REST_Controller.php';");
		fwrite($txt, "\n" . "require APPPATH . 'libraries/Format.php';");
		fwrite($txt, "\n");
		fwrite($txt, "\n" . "class $tabla extends REST_Controller{");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "public function __construct(){");
		fwrite($txt, "\n\t\t" . "parent ::__construct();");
		fwrite($txt, "\n\t\t" . "\$this -> load -> model('$modelo', '$model');");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "public function index_GET(){");
		fwrite($txt, "\n\t\t" . "\$res = array(");
		fwrite($txt, "\n\t\t\t" . "'Creado'     => 'CI - GeneraCode',");
		fwrite($txt, "\n\t\t\t" . "'Tipo'       => 'GET',");
		fwrite($txt, "\n\t\t\t" . "'Controller' => '$tabla'");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$res);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "public function index_POST(){");
		fwrite($txt, "\n\t\t" . "\$res = array(");
		fwrite($txt, "\n\t\t\t" . "'Creado'     => 'CI - GeneraCode',");
		fwrite($txt, "\n\t\t\t" . "'Tipo'       => 'POST',");
		fwrite($txt, "\n\t\t\t" . "'Controller' => '$tabla'");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$res);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function getAll$tabla" . "_GET(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> get();");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "\$$tabla = \$this -> $model -> getAll$tabla();");
		fwrite($txt, "\n\t\t" . "\$respuesta = array(");
		fwrite($txt, "\n\t\t\t" . "'error' => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla loaded',");
		fwrite($txt, "\n\t\t\t" . "'data' => \$$tabla");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$respuesta);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function get$tabla" . "_GET(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> get();");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "\$where = array('$pk' => info['$pk']);");
		fwrite($txt, "\n\t\t" . "\$$tabla = \$this -> $model -> get$tabla(\$where);");
		fwrite($txt, "\n\t\t" . "\$respuesta = array(");
		fwrite($txt, "\n\t\t\t" . "'error' => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla loaded',");
		fwrite($txt, "\n\t\t\t" . "'data' => \$$tabla");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$respuesta);");
		fwrite($txt, "\n\t" . "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\tfunction add$tabla" . "_POST(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> post();");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "\$this -> $model -> add$tabla(\$info);");
		fwrite($txt, "\n\t\t" . "\$respuesta = array(");
		fwrite($txt, "\n\t\t\t" . "'error' => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla added.'");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$respuesta);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function upd$tabla" . "_POST(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> post();");
		fwrite($txt, "\n\t\t" . "\$$pk = \$info['$pk'];");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "unset(\$info['$pk']);");
		fwrite($txt, "\n\t\t" . "\$this -> $model -> upd$tabla(\$$pk, \$info);");
		fwrite($txt, "\n\t\t" . "\$respuesta = array(");
		fwrite($txt, "\n\t\t\t" . "'error'   => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla updated.'");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$respuesta);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function del$tabla" . "_POST(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> post();");
		fwrite($txt, "\n\t\t" . "\$$pk = \$info['$pk'];");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "unset(\$info['$pk']);");
		fwrite($txt, "\n\t\t" . "\$this -> $model -> del$tabla(\$$pk, \$info);");
		fwrite($txt, "\n\t\t" . "\$respuesta = array(");
		fwrite($txt, "\n\t\t\t" . "'error' => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla deleted.'");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$respuesta);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n");
		fwrite($txt, "}");
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
		$dirModel  = './gens/application/models/';
		$tabla     = $this -> post('tabla');
		$json_data = json_decode($this -> post('info'), TRUE);
		$archivo   = $tabla . "_model . php";
		$campos    = '';
		$pk        = '';
		$enteros   = array();
		foreach($json_data as $x => $x_value){
			if($x_value['data_type'] == 'int'){
				array_push($enteros, $x_value['column_name']);
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
		$cuantosIntegers = count($enteros);
		$txt             = fopen($dirModel . $archivo, 'w');
		fwrite($txt, " <?php");
		fwrite($txt, "\n" . "defined('BASEPATH') or exit('No direct script access allowed');");
		fwrite($txt, "\n" . "class $tabla" . "_model extends CI_Model{");
		fwrite($txt, "\n");
		fwrite($txt, "\n");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\t" . 'public $' . $x_value['column_name'] . ";");
			fwrite($txt, "\n");
		}
		fwrite($txt, "\n\t" . "public function __construct(){");
		fwrite($txt, "\n\t\t" . "parent ::__construct();");
		fwrite($txt, "\n\t\t" . "\$this -> load -> database();");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function getAll$tabla(){");
		fwrite($txt, "\n\t\t" . "\$this -> db -> select('$campos');");
		fwrite($txt, "\n\t\t" . "\$this -> db -> order_by('$pk', 'asc');");
		fwrite($txt, "\n\t\t" . "\$query = \$this -> db -> get('" . strtolower($tabla) . "');");
		fwrite($txt, "\n\t\t" . "\$rows = \$query -> custom_result_object('$tabla" . "_model');");
		if($cuantosIntegers > 0){
			fwrite($txt, "\n\t\t" . "foreach(\$rows as \$row) {");
			for($x = 0; $x < $cuantosIntegers; $x++){
				fwrite($txt, "\n\t\t\t" . "\$row -> $enteros[$x] = intval(\$row -> $enteros[$x]);");
			}
			fwrite($txt, "\n\t\t" . "}");
		}
		fwrite($txt, "\n\t\t" . "return \$rows;");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function get$tabla(" . '$where' . "){");
		fwrite($txt, "\n\t\t" . "\$this -> db -> select('$campos');");
		fwrite($txt, "\n\t\t" . "\$this -> db -> where(\$where);");
		fwrite($txt, "\n\t\t" . "\$this -> db -> order_by('$pk', 'asc');");
		fwrite($txt, "\n\t\t" . "\$query = \$this -> db -> get('" . strtolower($tabla) . "');");
		fwrite($txt, "\n\t\t" . "\$rows = \$query -> custom_result_object('$tabla" . "_model');");
		if($cuantosIntegers > 0){
			fwrite($txt, "\n\t\t" . "foreach(\$rows as \$row) {");
			for($x = 0; $x < $cuantosIntegers; $x++){
				fwrite($txt, "\n\t\t\t" . "\$row -> $enteros[$x] = intval(\$row -> $enteros[$x]);");
			}
			fwrite($txt, "\n\t\t" . "}");
		}
		fwrite($txt, "\n\t\t" . "return \$rows;");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function add$tabla(" . '$datos' . "){");
		fwrite($txt, "\n\t\t" . "\$this -> db -> insert('" . strtolower($tabla) . "', \$datos);");
		fwrite($txt, "\n\t\t" . "return \$this -> db -> insert_id();");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function upd$tabla(\$$pk,\$datos){");
		fwrite($txt, "\n\t\t" . "\$this -> db -> where('$pk', \$$pk);");
		fwrite($txt, "\n\t\t" . "return \$this -> db -> update('" . strtolower($tabla) . "', \$datos);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function del$tabla(\$$pk,\$datos){");
		fwrite($txt, "\n\t\t" . "\$this -> db -> where('$pk', \$$pk);");
		fwrite($txt, "\n\t\t" . "return \$this -> db -> update('" . strtolower($tabla) . "', \$datos);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\r" . "}");
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
	
	//	public function generaJs_POST(){
	//		$tabla     = $this -> post('tabla');
	//		$json_data = json_decode($this -> post('info'), TRUE);
	//		$archivo   = $tabla . " . js";
	//		$campos    = '';
	//		$pk        = '';
	//		$enteros   = array();
	//		foreach($json_data as $x => $x_value){
	//			if($x_value['data_type'] == 'int'){
	//				array_push($enteros, $x_value['column_name']);
	//			}
	//			if($x_value['column_key'] == 'PRI'){
	//				$pk = $x_value['column_name'];
	//			}
	//			if(strlen($campos) != 0){
	//				$campos .= ", " . $x_value['column_name'];
	//			}else{
	//				$campos .= $x_value['column_name'];
	//			}
	//		}
	//		$cuantosIntegers = count($enteros);
	//		$codigo = "";
	//		$codigo .= "" . "var elIndex = 'index.php/';";
	//		$codigo .= "\n" . "var urlServer = '/generacodesrv/';";
	//		$codigo .= "\n";
	//		$codigo .= "\n" . "function getAll${tabla}(){";
	//		foreach($json_data as $x => $x_value){
	//			$codigo .= "\n\t" . $x_value['column_name'] . " = \$('" . $x_value['column_name'] . "').val();";
	//		}
	//		$codigo .= "\n\t" . "$.ajax({";
	//		$codigo .= "\n\t\t" . "url: urlServer + elIndex + '$tabla/getAll$tabla',";
	//		$codigo .= "\n\t\t" . "type: 'GET',";
	//		$codigo .= "\n\t\t" . "dataType: 'JSON',";
	//		$codigo .= "\n\t\t" . "data: {";
	//		foreach($json_data as $x => $x_value){
	//			$codigo .= "\n\t\t\t" . $x_value['column_name'] . ' : ' . $x_value['column_name'] . ",";
	//		}
	//		$codigo .= "\n\t\t\t" . "apkiKey: '11071981'";
	//		$codigo .= "\n\t\t" . "}";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . done(function(res){";
	//		$codigo .= "\n\t\t" . "console . log('success');";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . fail(function(){";
	//		$codigo .= "\n\t\t" . "console . log('error');";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . always(function(){";
	//		$codigo .= "\n\t\t" . "console . log('complete');";
	//		$codigo .= "\n\t" . "});";
	//		$codigo .= "\n" . "}";
	//		$codigo .= "\n";
	//		$codigo .= "\n" . "function add${tabla}(){";
	//		foreach($json_data as $x => $x_value){
	//			$codigo .= "\n\t" . $x_value['column_name'] . " = \$('" . $x_value['column_name'] . "').val();";
	//		}
	//		$codigo .= "\n\t" . "$.ajax({";
	//		$codigo .= "\n\t\t" . "url: urlServer + elIndex + '${tabla}/add${tabla}',";
	//		$codigo .= "\n\t\t" . "type: 'POST',";
	//		$codigo .= "\n\t\t" . "dataType: 'JSON',";
	//		$codigo .= "\n\t\t" . "data: {";
	//		foreach($json_data as $x => $x_value){
	//			$codigo .= "\n\t\t\t" . $x_value['column_name'] . ' : ' . $x_value['column_name'] . ",";
	//		}
	//		$codigo .= "\n\t\t\t" . "apkiKey: '11071981'";
	//		$codigo .= "\n\t\t" . "}";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . done(function(res){";
	//		$codigo .= "\n\t\t" . "console . log('success');";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . fail(function(){";
	//		$codigo .= "\n\t\t" . "console . log('error');";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . always(function(){";
	//		$codigo .= "\n\t\t" . "console . log('complete');";
	//		$codigo .= "\n\t" . "});";
	//		$codigo .= "\n" . "}";
	//		$codigo .= "\n";
	//		$codigo .= "\n" . "function upd${tabla}(){";
	//		foreach($json_data as $x => $x_value){
	//			$codigo .= "\n\t" . $x_value['column_name'] . " = \$('" . $x_value['column_name'] . "').val();";
	//		}
	//		$codigo .= "\n\t" . "$.ajax({";
	//		$codigo .= "\n\t\t" . "url: urlServer + elIndex + '${tabla}/upd${tabla}',";
	//		$codigo .= "\n\t\t" . "type: 'POST',";
	//		$codigo .= "\n\t\t" . "dataType: 'JSON',";
	//		$codigo .= "\n\t\t" . "data: {";
	//		foreach($json_data as $x => $x_value){
	//			$codigo .= "\n\t\t\t" . $x_value['column_name'] . ' : ' . $x_value['column_name'] . ",";
	//		}
	//		$codigo .= "\n\t\t\t" . "apkiKey: '11071981'";
	//		$codigo .= "\n\t\t" . "}";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . done(function(res){";
	//		$codigo .= "\n\t\t" . "console . log('success');";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . fail(function(){";
	//		$codigo .= "\n\t\t" . "console . log('error');";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . always(function(){";
	//		$codigo .= "\n\t\t" . "console . log('complete');";
	//		$codigo .= "\n\t" . "});";
	//		$codigo .= "\n" . "}";
	//		$codigo .= "\n";
	//		$codigo .= "\n" . "function del${tabla}(){";
	//		foreach($json_data as $x => $x_value){
	//			$codigo .= "\n\t" . $x_value['column_name'] . " = \$('" . $x_value['column_name'] . "').val();";
	//		}
	//		$codigo .= "\n\t" . "$.ajax({";
	//		$codigo .= "\n\t\t" . "url: urlServer + elIndex + '${tabla}/del${tabla}',";
	//		$codigo .= "\n\t\t" . "type: 'POST',";
	//		$codigo .= "\n\t\t" . "dataType: 'JSON',";
	//		$codigo .= "\n\t\t" . "data: {";
	//		foreach($json_data as $x => $x_value){
	//			$codigo .= "\n\t\t\t" . $x_value['column_name'] . ' : ' . $x_value['column_name'] . ",";
	//		}
	//		$codigo .= "\n\t\t" . "apkiKey: '11071981'";
	//		$codigo .= "\n\t\t" . "}";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . done(function(res){";
	//		$codigo .= "\n\t\t" . "console . log('success');";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . fail(function(){";
	//		$codigo .= "\n\t\t" . "console . log('error');";
	//		$codigo .= "\n\t" . "})";
	//		$codigo .= "\n\t" . " . always(function(){";
	//		$codigo .= "\n\t\t" . "console . log('complete');";
	//		$codigo .= "\n\t" . "});";
	//		$codigo .= "\n" . "}";
	//		$txt = fopen('./gens/app/js/' . $archivo, 'w');
	//		fwrite($txt, $codigo);
	//		fclose($txt);
	//		$respuesta = array(
	//			"tabla"     => $tabla,
	//			"json_data" => $json_data,
	//			"archivo"   => $archivo
	//		);
	//		$this -> response($respuesta);
	//	}
}

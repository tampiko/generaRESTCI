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
		$this -> response($res, 200);
	}
	
	public function generaZip_GET(){
		/* Nombre del Archivo Generado */
		$archivoZip = $_GET['nombre'];
		
		/* Para guardar y eliminar los archivos que se agregan */
		$archivos = array();
		
		$dirs = array(
			"J" => "app/js",
			"M" => "application/models",
			"C" => "application/controllers"
		);
		
		/* Genera un archivo zip para despues crear uno limpio */
		$zip_old = new ZipArchive();
		$zip_old -> open("gens/" . $archivoZip, ZipArchive::CREATE);
		$zip_old -> addEmptyDir($dirs["J"]);
		$zip_old -> addEmptyDir($dirs["M"]);
		$zip_old -> addEmptyDir($dirs["C"]);
		$zip_old -> close();
		unlink('gens/' . $archivoZip);
		$zip_new = new ZipArchive();
		$zip_new -> open("gens/" . $archivoZip, ZipArchive::CREATE);
		$zip_new -> addEmptyDir($dirs["J"]);
		$zip_new -> addEmptyDir($dirs["M"]);
		$zip_new -> addEmptyDir($dirs["C"]);
		
		/* Modelos */
		$modelos = dir('gens/application/models');
		while(($file = $modelos -> read()) !== FALSE){
			if($file != "." && $file != ".." && $file != ".gitkeep"){
				$zip_new -> addFile("gens/application/models/" . $file, "application/models/" . $file);
				array_push($archivos, "gens/application/models/" . $file);
			}
		}
		$modelos -> close();
		/* Modelos */
		
		/* Controladores */
		$controladores = dir('gens/application/controllers');
		while(($file = $controladores -> read()) !== FALSE){
			if($file != "." && $file != ".." && $file != ".gitkeep"){
				$zip_new -> addFile("gens/application/controllers/" . $file, "application/controllers/" . $file);
				array_push($archivos, "gens/application/controllers/" . $file);
			}
		}
		$controladores -> close();
		/* Controladores */
		
		/* Archivos JS */
		$archivosJs = dir('gens/app/js');
		while(($file = $archivosJs -> read()) !== FALSE){
			if($file != "." && $file != ".." && $file != ".gitkeep"){
				$zip_new -> addFile("gens/app/js/" . $file, "app/js/" . $file);
				array_push($archivos, "gens/app/js/" . $file);
			}
		}
		$archivosJs -> close();
		/* Archivos JS */
		
		$zip_new -> close();
		
		/* Elimina Modelos */
		$modelos = dir('gens/application/models');
		while(($file = $modelos -> read()) !== FALSE){
			if($file != "." && $file != ".." && $file != ".gitkeep"){
				unlink("gens/application/models/" . $file);
			}
		}
		$modelos -> close();
		/* Elimina Modelos */
		
		/* Elimina Controladores */
		$controladores = dir('gens/application/controllers');
		while(($file = $controladores -> read()) !== FALSE){
			if($file != "." && $file != ".." && $file != ".gitkeep"){
				unlink("gens/application/controllers/" . $file);
			}
		}
		$controladores -> close();
		/* Elimina Controladores */
		
		/* Elimina Archivos Js */
		$archivosJs = dir('gens/app/js');
		while(($file = $archivosJs -> read()) !== FALSE){
			if($file != "." && $file != ".." && $file != ".gitkeep"){
				unlink("gens/app/js/" . $file);
			}
		}
		$archivosJs -> close();
		/* Elimina Archivos Js */
		
		$page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
		$res      = array(
			'error'    => FALSE,
			'archivos' => $archivos,
			'url'      => $page_url,
			'zip'      => $archivoZip
		);
		$this -> response($res, 200);
	}
	
	public function generaController_POST(){
		$dirController = './gens/application/controllers/';
		$tabla         = $this -> post('tabla');
		$json_data     = json_decode($this -> post('info'), TRUE);
		$archivo       = $tabla . ".php";
		$campos        = '';
		$pk            = '';
		$enteros       = array();
		$modelo        = $tabla . "_model";
		$model         = "M" . $tabla;
		$txt           = fopen($dirController . $archivo, 'c', 1);
		
		foreach($json_data as $x => $x_value){
			if($x_value['DATA_TYPE'] == 'int'){
				array_push($enteros, $x_value['COLUMN_NAME']);
			}
			if($x_value['COLUMN_KEY'] == 'PRI'){
				$pk = $x_value['COLUMN_NAME'];
			}
			if(strlen($campos) != 0){
				$campos .= ", " . $x_value['COLUMN_NAME'];
			}else{
				$campos .= $x_value['COLUMN_NAME'];
			}
		}
		$cuantosIntegers = count($enteros);
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
		fwrite($txt, "\n\t\t" . "\$this -> response(\$res, 200);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "public function index_POST(){");
		fwrite($txt, "\n\t\t" . "\$res = array(");
		fwrite($txt, "\n\t\t\t" . "'Creado'     => 'CI - GeneraCode',");
		fwrite($txt, "\n\t\t\t" . "'Tipo'       => 'POST',");
		fwrite($txt, "\n\t\t\t" . "'Controller' => '$tabla'");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$res, 200);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function getAll$tabla" . "_GET(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> get();");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "\$$tabla = \$this -> $model -> getAll$tabla();");
		fwrite($txt, "\n\t\t" . "\$res = array(");
		fwrite($txt, "\n\t\t\t" . "'error' => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla loaded',");
		fwrite($txt, "\n\t\t\t" . "'data' => \$$tabla");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$res, 200);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function get$tabla" . "_GET(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> get();");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "\$where = array('$pk' => info['$pk']);");
		fwrite($txt, "\n\t\t" . "\$$tabla = \$this -> $model -> get$tabla(\$where);");
		fwrite($txt, "\n\t\t" . "\$res = array(");
		fwrite($txt, "\n\t\t\t" . "'error' => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla loaded',");
		fwrite($txt, "\n\t\t\t" . "'data' => \$$tabla");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$res, 200);");
		fwrite($txt, "\n\t" . "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\tfunction add$tabla" . "_POST(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> post();");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "\$this -> $model -> add$tabla(\$info);");
		fwrite($txt, "\n\t\t" . "\$res = array(");
		fwrite($txt, "\n\t\t\t" . "'error' => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla added.'");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$res, 200);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function upd$tabla" . "_POST(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> post();");
		fwrite($txt, "\n\t\t" . "\$$pk = \$info['$pk'];");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "unset(\$info['$pk']);");
		fwrite($txt, "\n\t\t" . "\$this -> $model -> upd$tabla(\$$pk, \$info);");
		fwrite($txt, "\n\t\t" . "\$res = array(");
		fwrite($txt, "\n\t\t\t" . "'error'   => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla updated.'");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$res, 200);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n\t" . "function del$tabla" . "_POST(){");
		fwrite($txt, "\n\t\t" . "\$info = \$this -> post();");
		fwrite($txt, "\n\t\t" . "\$$pk = \$info['$pk'];");
		fwrite($txt, "\n\t\t" . "unset(\$info['apiKey']);");
		fwrite($txt, "\n\t\t" . "unset(\$info['$pk']);");
		fwrite($txt, "\n\t\t" . "\$this -> $model -> del$tabla(\$$pk, \$info);");
		fwrite($txt, "\n\t\t" . "\$res = array(");
		fwrite($txt, "\n\t\t\t" . "'error' => FALSE,");
		fwrite($txt, "\n\t\t\t" . "'mensaje' => '$tabla deleted.'");
		fwrite($txt, "\n\t\t" . ");");
		fwrite($txt, "\n\t\t" . "\$this -> response(\$res, 200);");
		fwrite($txt, "\n\t" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n");
		fwrite($txt, "}");
		fclose($txt);
		
		$res = array(
			'Sistema'    => 'CI - GeneraCode',
			'Controller' => 'Archivos',
			'Funcion'    => 'generaController',
			'Tabla'      => $tabla,
			'Info'       => $json_data
		);
		$this -> response($res, 200);
	}
	
	public function generaModel_POST(){
		$dirModel  = './gens/application/models/';
		$tabla     = $this -> post('tabla');
		$json_data = json_decode($this -> post('info'), TRUE);
		$archivo   = $tabla . "_model.php";
		$campos    = '';
		$pk        = '';
		$enteros   = array();
		$txt       = fopen($dirModel . $archivo, 'w');
		
		foreach($json_data as $x => $x_value){
			if($x_value['DATA_TYPE'] == 'int'){
				array_push($enteros, $x_value['COLUMN_NAME']);
			}
			if($x_value['COLUMN_KEY'] == 'PRI'){
				$pk = $x_value['COLUMN_NAME'];
			}
			if(strlen($campos) != 0){
				$campos .= ", " . $x_value['COLUMN_NAME'];
			}else{
				$campos .= $x_value['COLUMN_NAME'];
			}
		}
		$cuantosIntegers = count($enteros);
		fwrite($txt, " <?php");
		fwrite($txt, "\n" . "defined('BASEPATH') or exit('No direct script access allowed');");
		fwrite($txt, "\n" . "class $tabla" . "_model extends CI_Model{");
		fwrite($txt, "\n");
		fwrite($txt, "\n");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\t" . 'public $' . $x_value['COLUMN_NAME'] . ";");
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
		fwrite($txt, "\n" . "}");
		fclose($txt);
		$res = array(
			'Sistema'    => 'CI - GeneraCode',
			'Controller' => 'Archivos',
			'Funcion'    => 'generaModel',
			'Tabla'      => $tabla,
			'Info'       => $json_data
		);
		
		$this -> response($res, 200);
	}
	
	public function generaJs_POST(){
		$dirJs     = './gens/app/js/';
		$tabla     = $this -> post('tabla');
		$json_data = json_decode($this -> post('info'), TRUE);
		$archivo   = $tabla . ".js";
		$campos    = '';
		$pk        = '';
		$enteros   = array();
		$txt       = fopen($dirJs . $archivo, 'w');
		
		foreach($json_data as $x => $x_value){
			if($x_value['DATA_TYPE'] == 'int'){
				array_push($enteros, $x_value['COLUMN_NAME']);
			}
			if($x_value['COLUMN_KEY'] == 'PRI'){
				$pk = $x_value['COLUMN_NAME'];
			}
			if(strlen($campos) != 0){
				$campos .= ", " . $x_value['COLUMN_NAME'];
			}else{
				$campos .= $x_value['COLUMN_NAME'];
			}
		}
		$cuantosIntegers = count($enteros);
		fwrite($txt, "var elIndex = 'index.php/';");
		fwrite($txt, "\n" . "var urlServer = '/generacodesrv/';");
		fwrite($txt, "\n");
		fwrite($txt, "\n" . "function getAll${tabla}(){");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\n\t" . $x_value['COLUMN_NAME'] . " = \$('" . $x_value['COLUMN_NAME'] . "').val();");
		}
		fwrite($txt, "\n\t" . "$.ajax({");
		fwrite($txt, "\n\t\t" . "url: urlServer + elIndex + '$tabla/getAll$tabla',");
		fwrite($txt, "\n\t\t" . "type: 'GET',");
		fwrite($txt, "\n\t\t" . "dataType: 'JSON',");
		fwrite($txt, "\n\t\t" . "data: {");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\n\t\t\t" . $x_value['COLUMN_NAME'] . ' : ' . $x_value['COLUMN_NAME'] . ",");
		}
		fwrite($txt, "\n\t\t\t" . "apiKey: sessionStorage.getItem('key')");
		fwrite($txt, "\n\t\t" . "}");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".done(function(res){");
		fwrite($txt, "\n\t\t" . "console.log('success');");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".fail(function(){");
		fwrite($txt, "\n\t\t" . "console.log('error');");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".always(function(){");
		fwrite($txt, "\n\t\t" . "console.log('complete');");
		fwrite($txt, "\n\t" . "});");
		fwrite($txt, "\n" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n" . "function add${tabla}(){");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\n\t" . $x_value['COLUMN_NAME'] . " = \$('" . $x_value['COLUMN_NAME'] . "').val();");
		}
		fwrite($txt, "\n\t" . "$.ajax({");
		fwrite($txt, "\n\t\t" . "url: urlServer + elIndex + '${tabla}/add${tabla}',");
		fwrite($txt, "\n\t\t" . "type: 'POST',");
		fwrite($txt, "\n\t\t" . "dataType: 'JSON',");
		fwrite($txt, "\n\t\t" . "data: {");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\n\t\t\t" . $x_value['COLUMN_NAME'] . ' : ' . $x_value['COLUMN_NAME'] . ",");
		}
		fwrite($txt, "\n\t\t\t" . "apiKey: sessionStorage.getItem('key')");
		fwrite($txt, "\n\t\t" . "}");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".done(function(res){");
		fwrite($txt, "\n\t\t" . "console.log('success');");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".fail(function(){");
		fwrite($txt, "\n\t\t" . "console.log('error');");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".always(function(){");
		fwrite($txt, "\n\t\t" . "console.log('complete');");
		fwrite($txt, "\n\t" . "});");
		fwrite($txt, "\n" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n" . "function upd${tabla}(){");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\n\t" . $x_value['COLUMN_NAME'] . " = \$('" . $x_value['COLUMN_NAME'] . "').val();");
		}
		fwrite($txt, "\n\t" . "$.ajax({");
		fwrite($txt, "\n\t\t" . "url: urlServer + elIndex + '${tabla}/upd${tabla}',");
		fwrite($txt, "\n\t\t" . "type: 'POST',");
		fwrite($txt, "\n\t\t" . "dataType: 'JSON',");
		fwrite($txt, "\n\t\t" . "data: {");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\n\t\t\t" . $x_value['COLUMN_NAME'] . ' : ' . $x_value['COLUMN_NAME'] . ",");
		}
		fwrite($txt, "\n\t\t\t" . "apiKey: sessionStorage.getItem('key')");
		fwrite($txt, "\n\t\t" . "}");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".done(function(res){");
		fwrite($txt, "\n\t\t" . "console.log('success');");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".fail(function(){");
		fwrite($txt, "\n\t\t" . "console.log('error');");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".always(function(){");
		fwrite($txt, "\n\t\t" . "console.log('complete');");
		fwrite($txt, "\n\t" . "});");
		fwrite($txt, "\n" . "}");
		fwrite($txt, "\n");
		fwrite($txt, "\n" . "function del${tabla}(){");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\n\t" . $x_value['COLUMN_NAME'] . " = \$('" . $x_value['COLUMN_NAME'] . "').val();");
		}
		fwrite($txt, "\n\t" . "$.ajax({");
		fwrite($txt, "\n\t\t" . "url: urlServer + elIndex + '${tabla}/del${tabla}',");
		fwrite($txt, "\n\t\t" . "type: 'POST',");
		fwrite($txt, "\n\t\t" . "dataType: 'JSON',");
		fwrite($txt, "\n\t\t" . "data: {");
		foreach($json_data as $x => $x_value){
			fwrite($txt, "\n\t\t\t" . $x_value['COLUMN_NAME'] . ' : ' . $x_value['COLUMN_NAME'] . ",");
		}
		fwrite($txt, "\n\t\t\t" . "apiKey: sessionStorage.getItem('key')");
		fwrite($txt, "\n\t\t" . "}");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".done(function(res){");
		fwrite($txt, "\n\t\t" . "console.log('success');");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".fail(function(){");
		fwrite($txt, "\n\t\t" . "console.log('error');");
		fwrite($txt, "\n\t" . "})");
		fwrite($txt, "\n\t" . ".always(function(){");
		fwrite($txt, "\n\t\t" . "console.log('complete');");
		fwrite($txt, "\n\t" . "});");
		fwrite($txt, "\n" . "}");
		fclose($txt);
		$res = array(
			"tabla"     => $tabla,
			"json_data" => $json_data,
			"archivo"   => $archivo
		);
		$this -> response($res, 200);
	}
}

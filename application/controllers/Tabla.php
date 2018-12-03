<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Tabla extends REST_Controller{
	
	public function __construct(){
		parent ::__construct();
		if(!$this -> input -> is_ajax_request()){
			exit('No direct script access allowed');
		}
		$this -> load -> model('Tabla_model', 'tablaM');
		$this -> load -> model('DetallesTabla_model', 'dtM');
	}
	
	public function index_GET(){
		$res = array(
			'Sistema'    => 'CI - GeneraCode',
			'Controller' => 'Tabla'
		);
		$this -> response($res);
	}
	
	public function getTablasBD_GET(){
		$url    = $this -> get();
		$where  = array('table_schema' => $url['bdatos']);
		$tablas = $this -> tablaM -> getTablasBD($where);
		if($tablas == FALSE){
			$datos = array(
				'error'   => TRUE,
				'mensaje' => 'No contiene tablas aún'
			);
		}else{
			$datos = array(
				'error'   => FALSE,
				'mensaje' => 'Tablas Cargadas',
				'data'    => $tablas
			);
		}
		$this -> response($datos);
	}
	
	public function getTablaDetalles_GET(){
		$url   = $this -> get();
		$where = array(
			'table_schema' => $url['bdatos'],
			'table_name'   => $url['tabla']
		);
		$res   = array(
			'error'   => FALSE,
			'mensaje' => 'Detalles Cargados',
			'data'    => $this -> dtM -> getTablaDetalles($where)
		);
		$this -> response($res);
	}
}

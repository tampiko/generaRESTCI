<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Bdatos extends REST_Controller{
	public function __construct(){
		parent ::__construct();
		$this -> load -> model("Bdatos_model", 'modBd');
	}
	
	public function index_GET(){
		$res = array(
			'Sistema'    => 'CI - GeneraCode',
			'Controller' => 'Bdatos'
		);
		$this -> response($res);
	}
	
	public function getBdatos_GET(){
		$Bdatos = $this -> modBd -> getBdatos();
		if($Bdatos == FALSE){
			$respuesta = array(
				'error'   => FALSE,
				'mensaje' => 'No Hay Bases de Datos por cargar'
			);
		}else{
			$respuesta = array(
				'error'   => FALSE,
				'mensaje' => 'Bases de datos Cargadas',
				'data'    => $Bdatos
			);
		}
		$this -> response($respuesta);
	}
}

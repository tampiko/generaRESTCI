<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tabla_model extends CI_Model{
	
	public $TABLE_NAME;
	
	public function __construct(){
		parent ::__construct();
	}
	
	public function getTablasBD($where){
		$tablasBase = array(
			'usuarios',
			'permisos',
			'modulos',
			'keys'
		);
		$this -> db -> select('table_name');
		$this -> db -> where($where);
		$this -> db -> where_not_in('table_name', $tablasBase);
		$this -> db -> order_by('table_name', 'asc');
		$query = $this -> db -> get('INFORMATION_SCHEMA.tables');
		$rows  = $query -> custom_result_object('Tabla_model');
		return $rows;
	}
}

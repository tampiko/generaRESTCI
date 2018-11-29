<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tabla_model extends CI_Model{
	
	public $table_name;
	
	public function __construct(){
		parent ::__construct();
		$this -> load -> database();
	}
	
	public function getTablasBD($where){
		$this -> db -> select('table_name');
		$this -> db -> where($where);
		$this -> db -> order_by('table_name', 'asc');
		$query = $this -> db -> get('INFORMATION_SCHEMA.tables');
		$row   = $query -> custom_result_object('Tabla_model');
		return $row;
	}
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bdatos_model extends CI_Model{
	
	public $bdatos;
	
	public function __construct(){
		parent ::__construct();
	}
	
	public function getBdatos(){
		$where = "SCHEMA_NAME NOT IN ('information_schema', 'mysql', 'performance_schema', 'phpmyadmin', 'test', 'mydb', 'sys')";
		$this -> db -> select('SCHEMA_NAME AS bdatos');
		$this -> db -> order_by('SCHEMA_NAME', 'asc');
		$this -> db -> where($where);
		$query = $this -> db -> get('INFORMATION_SCHEMA.SCHEMATA');
		$rows  = $query -> custom_result_object('Bdatos_model');
		return $rows;
	}
}

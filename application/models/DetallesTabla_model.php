<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DetallesTabla_model extends CI_Model{
	public $COLUMN_NAME;
	public $DATA_TYPE;
	public $COLUMN_KEY;
	
	public function __construct(){
		parent ::__construct();
		$this -> load -> database();
	}
	
	public function getTablaDetalles($where){
		$this -> db -> select('column_name, data_type, column_key');
		$this -> db -> where($where);
		$this -> db -> order_by('ordinal_position', 'asc');
		$query = $this -> db -> get('INFORMATION_SCHEMA.COLUMNS');
		$rows  = $query -> custom_result_object('DetallesTabla_model');
		return $rows;
	}
}

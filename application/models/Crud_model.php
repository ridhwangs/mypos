<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_model extends CI_Model {

  public function __construct(){
      parent::__construct();
  }

    public function create($table, $data) {
        $this->db->insert($table,  $data);
    }

    public function read($table, $where = null) {
      $this->db->from($table);
      if($where != null){
        $this->db->where($where);
      }
      $query = $this->db->get();
      return $query;
    }

    public function update($table, $where, $data) {
      $this->db->where($where);
      $this->db->update($table, $data);
    }

    public function delete($table, $where) {
      $this->db->where($where)->delete($table);
    }

    public function sum($table, $sum, $where){
      $this->db->select_sum($sum)
        ->where($where);
      $query = $this->db->get($table);
      return $query;
    }
}

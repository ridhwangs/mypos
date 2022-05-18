<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_model extends CI_Model {

  public function __construct(){
      parent::__construct();
      $this->mysql_db = $this->load->database('mysql_db', TRUE);
  }

    public function create($table, $data) {
        $this->db->insert($table,  $data);
    }

    public function read($table, $where = null, $order = null, $sort = null, $limit = null){
      $this->db->from($table);
      if($where != null){
        $this->db->where($where);
      }
      if ($order != null) {
        $this->db->order_by($order, $sort);
      }
      if ($limit != null) {
        $this->db->limit($limit);
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

    public function mysql_create($table, $data) {
        $this->mysql_db->insert($table,  $data);
    }
    
    public function mysql_read($table, $where = null, $order = null, $sort = null, $limit = null){
      $this->mysql_db->from($table);
      if($where != null){
        $this->mysql_db->where($where);
      }
      if ($order != null) {
        $this->mysql_db->order_by($order, $sort);
      }
      if ($limit != null) {
        $this->mysql_db->limit($limit);
      }
      $query = $this->mysql_db->get();
      return $query;
    }

    public function mysql_delete($table, $where) {
      $this->mysql_db->where($where)->delete($table);
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_model extends CI_Model {

    var $table = 'inventory';
    var $column_order = array(null,'inventory.kd_barang','inventory.nm_barang','inventory.quantity_on_hand','inventory.minimum_stock','inventory.satuan','inventory.harga','inventory.remarks');
    var $column_search = array('kd_barang','nm_barang','harga','remarks');
    var $order = array('inventory.quantity_on_hand' => 'ASC','inventory.nm_barang' => 'ASC');

    public function __construct(){
        parent::__construct();
    }

    private function _get_datatables_query($where = null, $filterLainya){
        $this->db->select('
            inventory.id AS id,
            inventory.kd_barang AS kd_barang,
            inventory.nm_barang AS nm_barang,
            inventory.quantity_on_hand AS quantity_on_hand,
            inventory.minimum_stock AS minimum_stock,
            inventory.satuan AS satuan,
            inventory.harga AS harga,
            inventory.remarks AS remarks,
            inventory.status AS status,

            MAX(transaksi_masuk.tanggal) AS terakhir_beli,
            MAX(transaksi_keluar.tanggal) AS terakhir_jual
        ');
        $this->db->where($where); 
        if($filterLainya == 1){
            $whereLainya = "inventory.quantity_on_hand=0";
            $this->db->where($whereLainya);
        }elseif($filterLainya == 2){
            $whereLainya = "inventory.quantity_on_hand<inventory.minimum_stock";
            $this->db->where($whereLainya);
        }elseif($filterLainya == 3){
            $whereLainya = "inventory.quantity_on_hand>inventory.minimum_stock";
            $this->db->where($whereLainya);
        }elseif($filterLainya == 4){
            $whereLainya = "inventory.quantity_on_hand=inventory.minimum_stock";
            $this->db->where($whereLainya);
        }
        // $this->db->where('inventory.kd_barang','AIKCNDWTSZ100BZZZ001');
        // $this->db->where('inventory.quantity_on_hand ','< inventory.minimum_stock');
        $this->db->join('transaksi_keluar', 'transaksi_keluar.kd_barang = inventory.kd_barang','left');
        $this->db->join('transaksi_masuk', 'transaksi_masuk.kd_barang = inventory.kd_barang','left');
        $this->db->from($this->table);
        $this->db->order_by('inventory.nm_barang');
        $this->db->group_by('inventory.kd_barang');
        $i = 0;
        
        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables($where, $filterLainya)
    {
        $this->_get_datatables_query($where, $filterLainya);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($where, $filterLainya)
    {
        $this->_get_datatables_query($where, $filterLainya);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($where, $filterLainya)
    {
        $this->_get_datatables_query($where, $filterLainya);
        return $this->db->count_all_results();
    }

    public function select2($text){
        return $this->db->select("*")
                ->from($this->table)
                ->where('status','1')
                ->like("nm_barang", $text,'both')
                ->or_like("kd_barang", $text,'both')
                ->where('status','1')
                ->order_by('nm_barang', 'ASC')
                ->get();
    }

    public function export_query($where, $filterLainya)
    {

        $this->db->select('
            inventory.id AS id,
            inventory.kd_barang AS kd_barang,
            inventory.nm_barang AS nm_barang,
            inventory.quantity_on_hand AS quantity_on_hand,
            inventory.minimum_stock AS minimum_stock,
            inventory.satuan AS satuan,
            inventory.harga AS harga,
            inventory.remarks AS remarks,
            inventory.status AS status,

            MAX(transaksi_masuk.tanggal) AS terakhir_beli,
            MAX(transaksi_keluar.tanggal) AS terakhir_jual
        ');
      
        $this->db->where($where); 
        if($filterLainya == 1){
            $whereLainya = "inventory.quantity_on_hand=0";
            $this->db->where($whereLainya);
        }elseif($filterLainya == 2){
            $whereLainya = "inventory.quantity_on_hand<inventory.minimum_stock";
            $this->db->where($whereLainya);
        }elseif($filterLainya == 3){
            $whereLainya = "inventory.quantity_on_hand>inventory.minimum_stock";
            $this->db->where($whereLainya);
        }elseif($filterLainya == 4){
            $whereLainya = "inventory.quantity_on_hand=inventory.minimum_stock";
            $this->db->where($whereLainya);
        }
        
        $this->db->join('transaksi_keluar', 'transaksi_keluar.kd_barang = inventory.kd_barang','left');
        $this->db->join('transaksi_masuk', 'transaksi_masuk.kd_barang = inventory.kd_barang','left');
        $this->db->from($this->table);
        $this->db->group_by('inventory.kd_barang');
        $this->db->order_by('inventory.nm_barang');

        $query = $this->db->get();
        return $query;
    }
}

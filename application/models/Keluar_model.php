<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keluar_model extends CI_Model {

    var $table = 'transaksi_keluar';
    var $column_order = array(null,'transaksi_keluar.tanggal','transaksi_keluar.pelanggan','transaksi_keluar.kd_barang','inventory.nm_barang','qty_before','qty','qty_end','satuan','harga_jual_satuan','harga_jual','harga_beli_satuan','harga_beli','margin_satuan','margin');
    var $column_search = array('transaksi_keluar.tanggal','transaksi_keluar.pelanggan','transaksi_keluar.kd_barang','inventory.nm_barang','transaksi_keluar.qty','inventory.harga');
    var $order = array('transaksi_keluar.created_at' => 'DESC');

    public function __construct(){
        parent::__construct();
    }

    private function _get_datatables_query($where){
        $this->db->select('
            transaksi_keluar.tk_id AS tk_id,
            transaksi_keluar.kd_transaksi AS kd_transaksi,
            transaksi_keluar.tanggal AS tanggal,
            transaksi_keluar.pelanggan AS pelanggan,
            transaksi_keluar.kd_barang AS kd_barang,
            SUM(transaksi_keluar.qty) AS sum_qty,
            transaksi_keluar.qty_before AS qty_before,
            (transaksi_keluar.qty_before - transaksi_keluar.qty) AS qty_end,
            transaksi_keluar.harga AS harga_jual_satuan,
            (transaksi_keluar.harga * SUM(transaksi_keluar.qty)) AS harga_jual,
            inventory.nm_barang AS nm_barang,
            inventory.satuan AS satuan,
            AVG(transaksi_masuk.harga) AS harga_beli_satuan,
            AVG(transaksi_masuk.harga * transaksi_keluar.qty) AS harga_beli,
            (transaksi_keluar.harga - AVG(transaksi_masuk.harga)) AS margin_satuan,    
            ((transaksi_keluar.harga * transaksi_keluar.qty) - AVG(transaksi_masuk.harga * transaksi_keluar.qty)) AS margin,          
        ');
        
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->join('inventory', 'inventory.kd_barang = transaksi_keluar.kd_barang','left');
        $this->db->join('transaksi_masuk', 'transaksi_masuk.kd_barang = inventory.kd_barang','left');
        $this->db->group_by('transaksi_keluar.kd_barang');
        $i = 0;
        foreach ($this->column_search as $item) // loop column
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($where) {
        $this->_get_datatables_query($where);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($where) {
        $this->_get_datatables_query($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where) {
        $this->_get_datatables_query($where);
        return $this->db->count_all_results();
    }

    public function rekap_penjualan()
    {
        $this->db->select('
          MONTH(transaksi_keluar.tanggal)  AS bulan,
          YEAR(transaksi_keluar.tanggal) AS tahun,
          SUM(transaksi_keluar.harga * transaksi_keluar.qty) AS harga_jual,
          SUM(transaksi_masuk.harga * transaksi_keluar.qty) AS harga_beli,
          COUNT(transaksi_keluar.kd_barang) AS jumlah_item,

        ');
        $this->db->join('transaksi_masuk', 'transaksi_masuk.kd_barang = transaksi_keluar.kd_barang','left');
        $this->db->from($this->table);
        $this->db->where('YEAR(transaksi_keluar.tanggal)',date('Y'));
        $this->db->group_by('MONTH(transaksi_keluar.tanggal), YEAR(transaksi_keluar.tanggal)');
        $this->db->order_by('MONTH(transaksi_keluar.tanggal)','ASC');
        $query = $this->db->get();
        return $query;
    }

    public function group_transaksi($where = null)
    {
        $this->db->select('
            created_at AS created_at,
            tanggal AS tanggal,
            kd_transaksi AS kd_transaksi,
            SUM(harga * qty) AS jumlah
        ');
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->group_by('kd_transaksi');
        $query = $this->db->get();
        return $query;
    }

    public function export_query($where)
    {

        $this->db->select('
            transaksi_keluar.tk_id AS tk_id,
            transaksi_keluar.tanggal AS tanggal,
            transaksi_keluar.pelanggan AS pelanggan,
            transaksi_keluar.kd_barang AS kd_barang,
            transaksi_keluar.qty AS qty,
            transaksi_keluar.qty_before AS qty_before,
            (transaksi_keluar.qty_before - transaksi_keluar.qty) AS qty_end,
            transaksi_keluar.harga AS harga_jual_satuan,
            (transaksi_keluar.harga * transaksi_keluar.qty) AS harga_jual,
            inventory.nm_barang AS nm_barang,
            inventory.satuan AS satuan,
            AVG(transaksi_masuk.harga) AS harga_beli_satuan,
            AVG(transaksi_masuk.harga * transaksi_keluar.qty) AS harga_beli,
            (transaksi_keluar.harga - AVG(transaksi_masuk.harga)) AS margin_satuan,    
            ((transaksi_keluar.harga * transaksi_keluar.qty) - AVG(transaksi_masuk.harga * transaksi_keluar.qty)) AS margin,          
        ');
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->join('inventory', 'inventory.kd_barang = transaksi_keluar.kd_barang','left');
        $this->db->join('transaksi_masuk', 'transaksi_masuk.kd_barang = inventory.kd_barang','left');
        $this->db->group_by('transaksi_keluar.tk_id');
        $this->db->order_by('transaksi_keluar.tanggal','ASC');

        $query = $this->db->get();
        return $query;
    }
}

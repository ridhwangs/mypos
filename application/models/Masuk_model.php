<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masuk_model extends CI_Model {

    var $table = 'transaksi_masuk';
    var $column_order = array(null,'transaksi_masuk.tanggal','transaksi_masuk.supplier','transaksi_masuk.kd_barang','inventory.nm_barang','qty_before','qty','qty_end','satuan','harga_beli_satuan','harga_beli');
    var $column_search = array('transaksi_masuk.tanggal','transaksi_masuk.supplier','transaksi_masuk.kd_barang','inventory.nm_barang','transaksi_masuk.qty','inventory.harga');
    var $order = array('transaksi_masuk.created_at' => 'ASC');

    public function __construct(){
        parent::__construct();
    }

    private function _get_datatables_query($where){
        $this->db->select('
            transaksi_masuk.tm_id AS tm_id,
            transaksi_masuk.tanggal AS tanggal,
            transaksi_masuk.supplier AS supplier,
            transaksi_masuk.kd_barang AS kd_barang,
            transaksi_masuk.qty AS qty,
            transaksi_masuk.qty_before AS qty_before,
            (transaksi_masuk.qty_before + transaksi_masuk.qty) AS qty_end,
            transaksi_masuk.harga AS harga_beli_satuan,
            (transaksi_masuk.harga * transaksi_masuk.qty) AS harga_beli,
            inventory.nm_barang AS nm_barang,
            inventory.satuan AS satuan,
        ');
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->join('inventory', 'inventory.kd_barang = transaksi_masuk.kd_barang','left');
        $this->db->group_by('transaksi_masuk.tm_id');
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
    
    public function rekap_pembelian()
    {
        $this->db->select('
            strftime("%m", transaksi_masuk.tanggal)  AS bulan,
            strftime("%Y", transaksi_masuk.tanggal) AS tahun,
            SUM(transaksi_masuk.harga * qty) AS harga,
            COUNT(transaksi_masuk.kd_barang) AS jumlah_item,
        ');
        $this->db->from($this->table);
        $this->db->group_by('strftime("%m", transaksi_masuk.tanggal),  strftime("%Y", transaksi_masuk.tanggal)');
        $this->db->order_by('transaksi_masuk.tanggal','DESC');
        $this->db->limit('6');
        $query = $this->db->get();
        return $query;
    }

    public function export_query($where)
    {
        $tgl_awal = $where['tgl_awal'];
        $tgl_akhir = $where['tgl_akhir'];

        $this->db->select('
            transaksi_masuk.tm_id AS tm_id,
            transaksi_masuk.tanggal AS tanggal,
            transaksi_masuk.supplier AS supplier,
            transaksi_masuk.kd_barang AS kd_barang,
            transaksi_masuk.qty AS qty,
            transaksi_masuk.qty_before AS qty_before,
            (transaksi_masuk.qty_before + transaksi_masuk.qty) AS qty_end,
            transaksi_masuk.harga AS harga_beli_satuan,
            (transaksi_masuk.harga * transaksi_masuk.qty) AS harga_beli,
            inventory.nm_barang AS nm_barang,
            inventory.satuan AS satuan,
        ');
        $this->db->where($where);
        $this->db->from($this->table);
        $this->db->join('inventory', 'inventory.kd_barang = transaksi_masuk.kd_barang','left');
        $this->db->order_by('transaksi_masuk.tanggal','ASC');

        $query = $this->db->get();
        return $query;
    }
}

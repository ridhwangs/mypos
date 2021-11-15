<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Beranda extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct() {
       parent::__construct();
        if (!$this->ion_auth->logged_in()) {
     			redirect('auth/login', 'refresh');
        }
        $this->load->helper('url');

        $this->_init();
        
         $this->load->model(array('masuk_model','keluar_model'));

    }

    private function _init()
	  {
		$this->output->set_template('main_layout');
	  }

    public function index(){
      $data = array(
          'page_header' => 'Beranda',
          'rekap_penjualan' => $this->keluar_model->rekap_penjualan()->result()
      );
      // echo "<pre>";
      // print_r($data['rekap_penjualan']);
      // die();
      $this->load->view('beranda/beranda_index', $data);
    }

}

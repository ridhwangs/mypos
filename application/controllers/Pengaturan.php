<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends CI_Controller {

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


    public function perusahaan(){
      $data = array(
          'page_header' => 'Setting Perusahaan',
          'perusahaan' => $this->crud_model->read('perusahaan')->row(),
      );
      $this->load->view('pengaturan/perusahaan/perusahaan_index', $data);
    }

    public function update($params)
    {
      switch ($params) {
        case 'perusahaan':
          $config['upload_path']   = './assets/logo/';
          $config['allowed_types'] = 'jpg|png|jpeg';
          $config['max_size']  = '2048';
          $config['remove_space'] = TRUE;
          $config['encrypt_name']	= TRUE;

          $berkas = $_FILES['berkas']['name'];
          if(!empty($berkas)){
              $files = glob('./assets/logo/*'); // get all file names
              foreach($files as $file){ // iterate files
                if(is_file($file)) {
                  unlink($file); // delete file
                }
              }
              
              $this->load->library('upload', $config);
              if (!$this->upload->do_upload('berkas'))
              {
                  $error = array('error' => $this->upload->display_errors());
              }
          }
         

          $data = [
            'nama_perusahaan' => $this->input->post('nama_perusahaan'),
            'alamat' => $this->input->post('alamat'),
            'no_telp' => $this->input->post('no_telp'),
           
          ];
          if(!empty($berkas)){
            $data['logo'] = $this->upload->data("file_name");
          }
          $this->crud_model->update('perusahaan',[], $data);
          
          $set_flashdata = [
            'message' => 'Data berhasi di simpan' 
          ];

          $this->session->set_flashdata($set_flashdata);
          redirect($this->agent->referrer());
          break;
        
        default:
            show_404();
          break;
      }
    }

}

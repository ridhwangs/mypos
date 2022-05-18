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

    public function backup()
    {
      $data = array(
        'page_header' => 'Backup Database',
      );
      $this->load->view('pengaturan/database/backup/backup_index', $data);
    }

    public function perusahaan(){
      $data = array(
          'page_header' => 'Setting Perusahaan',
          'perusahaan' => $this->crud_model->read('perusahaan')->row(),
      );
      $this->load->view('pengaturan/perusahaan/perusahaan_index', $data);
    }

    public function create($attr)
    {
      switch ($attr) {
        case 'backup':
          $usr = $this->input->get('usr');

          $this->load->dbutil();

          $prefs = array(
            'format' => 'zip',
            'filename' => 'mypos.sql'
          );

          $backup = &$this->dbutil->backup($prefs);
          if(!empty($usr)){
            $db_name = 'mypos-cron_'.$usr.'-' . date("YmdHis") . '.zip'; // file name
          }else{
            $db_name = 'mypos-manual-' . date("YmdHis") . '.zip'; // file name
            
          }
          $save  = 'backup/db/' . $db_name; // dir name backup output destination

          $this->load->helper('file');
          write_file($save, $backup);

          $this->load->helper('download');
          // force_download($db_name, $backup);
          redirect($this->agent->referrer());
          break;

        default:
          show_404();
          break;
      }
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

    public function delete($attr, $id = null)
    {
      switch ($attr) {
        case 'backup':
          unlink('backup/db/' . $id);
          redirect($this->agent->referrer());
          break;

        default:
          # code...
          break;
      }
    }

    public function exit_apps()
    {
        $command = "taskkill /F /IM PHP_DESKTOP_CHROME.exe /T";
        exec($command);     
    }

}

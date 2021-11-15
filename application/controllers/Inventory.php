<?php

defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Inventory extends CI_Controller {

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
        $this->load->model(array('inventory_model'));

		    $this->_init();

    }

    private function _init()
    {
      $this->output->set_template('main_layout');
    }

    public function index(){
      $data = array(
          'page_header' => 'Inventory',
          'perusahaan' => $this->crud_model->read('perusahaan')->row(),
      );
      $this->load->view('inventory/inventory_table', $data);
    }

    public function create($attr)
    {
      switch ($attr) {
        case 'inventory':
            $data = array(
                        'kd_barang' => strtoupper($this->input->post('kd_barang')),
                        'nm_barang' => $this->input->post('nm_barang'),
                        'quantity_on_hand' => $this->input->post('quantity_on_hand'),
                        'minimum_stock' => $this->input->post('minimum_stock'),
                        'satuan' => $this->input->post('satuan'),
                        'harga' => str_replace('.','',$this->input->post('harga')),
                        'remarks' => $this->input->post('remarks'),
                        'created_by' => $this->session->username,
                        'created_at' => date('Y-m-d H:i:s')
                    );
            $num = $this->crud_model->read('inventory', array('kd_barang', $data['kd_barang']))->num_rows();
            if($num > 0){
                $message = preg_replace("/(\n)+/m", ' ', strip_tags( $data['kd_barang']." Gagal disimpan / sudah terdaftar sebelumnya."));
                $notify = array('title' => 'Info :',
                         'message' => $message,
                         'icon' => 'fa fa-info-circle',
                         'type' => 'danger',
                         'validasi' => false
                       );        
            }else{
                $this->crud_model->create('inventory', $data);
                $message = preg_replace("/(\n)+/m", ' ', strip_tags( $data['kd_barang']." Berhasil di simpan"));
                $notify = array('title' => 'Info :',
                          'message' => $message,
                          'icon' => 'fa fa-info-circle',
                          'type' => 'info',
                          'validasi' => true
                        );        
            }
           
            echo json_encode($notify);
            die();
          break;
        default:
            show_404();
          break;
      }
    }

    public function read($attr, $id = null)
    {
      switch ($attr) {
        case 'inventory':
              $where = array('id' => $this->input->post('id'));
              $data = $this->crud_model->read('inventory', $where)->row();
              echo json_encode($data);
            die();
          break;
        case 'harga':
            $where = array('kd_barang' => $this->input->post('kd_barang'));
            $data = $this->crud_model->read('inventory', $where)->row();
            echo json_encode($data);
          die();
        break;
        case 'select2':
          $return_arr = array();
          $row_array = array();
          $text = $this->input->get('text');
          $transaksi = $this->inventory_model->select2($text);
          if ($transaksi->num_rows() > 0) {
              foreach ($transaksi->result_array() as $row) {
                  $row_array['id'] =  $row['kd_barang'];
                  $row_array['text'] = $row['kd_barang'] .' - '.$row['nm_barang'].' '.$row['satuan'];
                  array_push($return_arr, $row_array);
              }
          }
          echo json_encode(array("results" => $return_arr));
          die();
          break;
        
        default:
            show_404();
          break;
      }
    }

    public function update($attr)
    {
      switch ($attr) {
        case 'inventory':
          $where = array('id' => $this->input->post('id'));
          $data = array(
              'kd_barang' => strtoupper($this->input->post('kd_barang')),
              'nm_barang' => $this->input->post('nm_barang'),
              'minimum_stock' => $this->input->post('minimum_stock'),
              'harga' => str_replace('.','',$this->input->post('harga')),
              'satuan' => $this->input->post('satuan'),
              'remarks' => $this->input->post('remarks'),
              'created_by' => $this->session->username,
              'created_at' => date('Y-m-d H:i:s')
          );
          $this->crud_model->update('inventory', $where, $data);
          $message = preg_replace("/(\n)+/m", ' ', strip_tags("Data Berhasil di Simpan"));
          $notify = array('title' => 'Info :',
                    'message' => $message,
                    'icon' => 'fa fa-info-circle',
                    'type' => 'info',
                    'validasi' => true
                  );   
          echo json_encode($notify);
          die();
          break;
        case 'delete':
          $where = array('id' => $this->input->post('id'));
          $data = array('status' => '0');
          $this->crud_model->update('inventory', $where, $data);
          // $this->crud_model->delete('inventory', $where);
          $message = preg_replace("/(\n)+/m", ' ', strip_tags("Data Berhasil di Hapus"));
          $notify = array('title' => 'Info :',
                    'message' => $message,
                    'icon' => 'fa fa-info-circle',
                    'type' => 'info',
                    'validasi' => true
                  );   
          echo json_encode($notify);
          die();
          break;
          case 'return':
            $where = array('id' => $this->input->post('id'));
            $data = array('status' => "1");
            $this->crud_model->update('inventory', $where, $data);
            $message = preg_replace("/(\n)+/m", ' ', strip_tags("Data Berhasil di Return"));
            $notify = array('title' => 'Info :',
                      'message' => $message,
                      'icon' => 'fa fa-info-circle',
                      'type' => 'info',
                      'validasi' => true
                    );   
            echo json_encode($notify);
            die();
            break;
        default:
          show_404();
        break;
      }
    }

    public function import($attr)
    {
      switch ($attr) {
        case 'inventory':
          $pathinfo = pathinfo($_FILES["berkas"]["name"]);
          $filetype = $pathinfo["extension"];
          $fileName = preg_replace('/\s+/', '_', date('sidmyh'));

          $config['upload_path'] = './assets/tmp_upload';
          $config['file_name'] = $fileName;

          $config['allowed_types'] = 'xls|xlsx';
          $config['max_size'] = 134217728;

          $this->load->library('upload', $config);
          $this->upload->initialize($config);
          $inputFileName = './assets/tmp_upload/' . $fileName . '.'.$filetype;
          if (!$this->upload->do_upload('berkas')) {

            $this->session->set_flashdata(array(
              'pesan' => 'Gagal, file tidak terbaca <i class="fa fa-times text-danger" aria-hidden="true"></i>',
              'total' => "0",
              'baru' => "0",
              'sama' => "0",
              'waktu' => date('Y-m-d H:i:s'),
            ));

          } else {
                  $spreadsheet = IOFactory::load($inputFileName);
                  $inputFileType = IOFactory::identify($inputFileName);
                  $objReader = IOFactory::createReader($inputFileType);

                  $sheet = $spreadsheet->getSheet(0);
                  $highestRow = $sheet->getHighestRow();
                  $highestColumn = $sheet->getHighestColumn();

                  $sama = 0;
                  $baru = 0;
                  $total = 0;
                  if(html_escape($sheet->getCell('A3')) == "NO"){
                      //file cocok
                      for ($row = 4; $row <= $highestRow; $row++) {
                      $total++;
                      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
                      if(empty($rowData[0][0])){
                        continue;
                      }
                      $dataRow = array(
                                      'kd_barang' => 'KD-'.$rowData[0][0],
                                      'nm_barang' => $rowData[0][1],
                                      'satuan' => $rowData[0][3],
                                      'harga' => $rowData[0][5],
                                      'quantity_on_hand' => $rowData[0][8],
                                      'created_by' => $this->session->username,
                                      'created_at' => date('Y-m-d'),
                                  );
                      $where = array(
                          'kd_barang' => $dataRow['kd_barang'],
                          'nm_barang' => $dataRow['nm_barang'],
                          'satuan' => $dataRow['satuan'],
                      );
                      $count = $this->crud_model->read("inventory", $where)->num_rows();
                      if ($count > 0) {
                          $sama++;
                          $this->crud_model->update('inventory', $where, $dataRow);
                      }else{
                          $baru++;
                          $this->crud_model->create('inventory', $dataRow);
                      }
                  }
                      $this->session->set_flashdata(array(
                        'pesan' => 'Berhasil  <i class="fa fa-check text-success" aria-hidden="true"></i>',
                        'total' => $total,
                        'baru' => $baru,
                        'sama' => $sama,
                        'waktu' => date('Y-m-d H:i:s'),
                      ));
                  }else{
                    //file tidak cocok
                    $this->session->set_flashdata(array(
                      'pesan' => 'Gagal <i class="fa fa-times text-danger" aria-hidden="true"></i>',
                      'total' => "0",
                      'baru' => "0",
                      'sama' => "0",
                      'waktu' => date('Y-m-d H:i:s'),
                    ));
                  }
          }
          delete_files($inputFileName, true);
          redirect($this->agent->referrer());
          die();
          break;
        default:
            show_404();
          break;
      }
    }
    
    public function datatable($attr)
    {
      switch ($attr) {
        case 'inventory':
            $where = [];
            if (!empty($this->input->post('status'))){
              
              if($this->input->post('status') == '1'){
                $status = '1';
              }else{
                $status = '0';
              }
              $where['inventory.status'] = $status;
            }
            if (!empty($this->input->post('cari'))) {
              $tabel = $this->input->post('table');
              if($tabel == 'kd_barang'){
                $cari = $this->input->post('cari');
              }else{
                $cari = '%'.$this->input->post('cari').'%';
              }
              $where['inventory.' . $tabel . ' LIKE '] = $cari;
            }
            $filterLainya = $this->input->post('filter_lainya');
            $list = $this->inventory_model->get_datatables($where, $filterLainya);
            $data = array();
            $start = $_POST['start'];
            $no = $start;
            foreach ($list as $rows) {

                $no++;
                $row = array();
                if($rows->quantity_on_hand > $rows->minimum_stock){
                  $color = "#2ecc71";
                }elseif ($rows->quantity_on_hand < $rows->minimum_stock) {
                  $color = "#e74c3c";
                }elseif ($rows->quantity_on_hand == $rows->minimum_stock) {
                  $color = "#f1c40f";
                }else{
                  $color = "#2c3e50";
                }
                $row[] = $no;
                $row[] = $rows->kd_barang;
                $badge = '';
                if($rows->status == 0){
                  $badge = ' <span class="badge badge-secondary">Tidak aktif</span>';
                }
                $row[] = $rows->nm_barang.$badge;
                $row[] = "<div class='text-center'>".$rows->quantity_on_hand."</div>";
                $row[] = "<div class='text-center'>".$rows->minimum_stock."</div>";
                $row[] = "<div class='text-right'>".number_format($rows->harga, 0, ',', '.')."</div>";
                $row[] = $rows->satuan;
                $row[] = $rows->remarks;
                $row[] = $rows->terakhir_beli;
                $row[] = $rows->terakhir_jual;
                if($rows->status == 1){
                  $row[] = '<i class="fas fa-circle" style="color: '.$color.';"></i>';
                  $row[] = '<a href="javascript:void(0);" onclick="edit('.$rows->id.')"><i style="color:primary" class="fas fa-pencil-alt fa-1x"></i></a>';
                  $row[] = '<a href="javascript:void(0)" onclick="update('.$rows->id.', \'hapus\')"><i class="fas fa-lock-open fa-1x"></i></a>';
                }else{
                  $row[] = '<i class="fas fa-circle"></i>';
                  $row[] = '<a href="javascript:void(0);"><i style="color:black;cursor:not-allowed;" class="fas fa-pencil-alt fa-1x"></i></a>';
                  $row[] = '<a href="javascript:void(0)" onclick="update('.$rows->id.', \'active\')"><i style="color:red" class="fas fa-lock fa-1x"></i></a>';
                }
               
                
                $data[] = $row;
            }
            $draw = $_POST['draw'];
            $output = array(
                "draw" => $draw,
                "recordsTotal" => $this->inventory_model->count_all($where, $filterLainya),
                "recordsFiltered" => $this->inventory_model->count_filtered($where, $filterLainya),
                "data" => $data,
            );
            //output to json format
            echo json_encode($output);
          die();
          break;
        
        default:
            show_404();
          break;
      }
    }

    public function export()
    {
        $this->load->helper('download');
        $files = glob('./assets/tmp_download/*'); // get all file names
          foreach($files as $file){ // iterate files
            if(is_file($file)) {
              unlink($file); // delete file
            }
          }
          $data = array();
          $tgl_awal = $this->input->get('tgl_awal');
          $tgl_akhir = $this->input->get('tgl_akhir');
          $perushaan = $this->crud_model->read('perusahaan')->row();

          $data['title'] = 'Inventory Data Barang Export';
          $fileName = 'Inventory Data Barang Export'.time();
          
          $spreadsheet = new Spreadsheet();
          $spreadsheet->getSheet(0);
          $spreadsheet->getSheetByName('Inventory | MyPOS');
          $sheet = $spreadsheet->getActiveSheet()->setTitle('Inventory | MyPOS');

          $spreadsheet->getDefaultStyle()->getFont()->setSize(10);

          $HeadstyleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR,
                    'color' => array('argb' => 'FFFF0000'),
                ),
              ),
            'font' => [
                    'bold' => true,
                    'color' => array('argb' => 'FFFFFFFF'),
            ],
            'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('argb' => '00000000'),
            ],
          );


          $sheet->getStyle('A4:J4')->applyFromArray($HeadstyleArray);
          
          $sheet->setCellValue('A1', 'Inventory');
          $sheet->mergeCells("A1:J1");

          $sheet->setCellValue('A2', $perushaan->nama_perusahaan);
          $sheet->mergeCells("A2:J2");
          $sheet->getStyle('A1:J2')->getAlignment()->setHorizontal('center')->setVertical('center');
          $sheet->getStyle('A2:J2')->getFont()->setBold(true)->setSize(20);;

          $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
          $richText->createText('Dokumen ini di export pada ');
          $payable = $richText->createTextRun(date('Y-m-d H:i:s'));
          $payable->getFont()->setBold(true);
          $payable->getFont()->setItalic(true);
          $payable->getFont()->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
          $spreadsheet->getActiveSheet()->getCell('A3')->setValue($richText);
          $sheet->mergeCells("A3:J3");
          $sheet->getStyle('A3:J3')->getAlignment()->setHorizontal('right')->setVertical('center');


          $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(25);


          //header
          $sheet->setCellValue('A4', "No");
          $sheet->setCellValue('B4', "Kode Barang");
          $sheet->setCellValue('C4', "Nama Barang");
          $sheet->setCellValue('D4', "Stock");
          $sheet->setCellValue('E4', "Minimum");
          $sheet->setCellValue('F4', "Price List");
          $sheet->setCellValue('G4', "Satuan");
          $sheet->setCellValue('H4', "Keterangan");
          $sheet->setCellValue('I4', "Terakhir Beli");
          $sheet->setCellValue('J4', "Terakhir Jual");

          $sheet->getStyle('A4:J4')->getAlignment()->setHorizontal('center')->setVertical('center');
          $sheet->getStyle('A4:J4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR);
         

          $IsistyleArray = array(
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                ],
            ],
          );

            $where = [];
            if (!empty($this->input->get('status'))){
              if($this->input->get('status') == '1'){
                $status = '1';
              }else{
                $status = '0';
              }
              $where['inventory.status'] = $status;
            }
            if (!empty($this->input->get('cari'))) {
              $tabel = $this->input->get('table');
              if($tabel == 'kd_barang'){
                $cari = $this->input->get('cari');
              }else{
                $cari = '%'.$this->input->get('cari').'%';
              }
              $where['inventory.' . $tabel . ' LIKE '] = $cari;
            }
            $filterLainya = $this->input->get('filter_lainya');

          $queryHead = $this->inventory_model->export_query($where, $filterLainya);
          $resultHead = $queryHead->result_array();
          
          $rowCountHead = 5;
          $no = 1;
          $total = 0;

          foreach ($resultHead as $key => $rows) {
                $total++;
                $sheet->setCellValue('A' . $rowCountHead, $no++);
                $sheet->setCellValue('B' . $rowCountHead, $rows['kd_barang']);
                $sheet->setCellValue('C' . $rowCountHead, $rows['nm_barang']);
                $sheet->setCellValue('D' . $rowCountHead, $rows['quantity_on_hand']);
                $sheet->setCellValue('E' . $rowCountHead, $rows['minimum_stock']);
                $sheet->setCellValue('F' . $rowCountHead, $rows['harga']);
                $sheet->setCellValue('G' . $rowCountHead, $rows['satuan']);
                $sheet->setCellValue('H' . $rowCountHead, $rows['remarks']);
                $sheet->setCellValue('I' . $rowCountHead, $rows['terakhir_beli']);
                $sheet->setCellValue('J' . $rowCountHead, $rows['terakhir_jual']);

                $firstRow = $rowCountHead - $no + 2;
                $rowTotal = $rowCountHead + 1;

            
                $rowCountHead++;
                $border = $rowCountHead -1;
                $sheet->getStyle('A'.$border.':J'.$border)->applyFromArray($IsistyleArray);
              }

            $rowSum = $rowCountHead;
            
            $spreadsheet->getActiveSheet()->getStyle('F5:F'.$rowSum)->getNumberFormat()->setFormatCode('#,##0');
            
            $sum = $rowSum - 1;
            $sheet->setCellValue('A'.$rowSum, "GRAND TOTAL ($total of items)");
            $sheet->setCellValue('D'.$rowSum, '=SUM(D5:D'.$sum.')');
            $sheet->setCellValue('E'.$rowSum, '=SUM(E5:E'.$sum.')');
            $sheet->setCellValue('F'.$rowSum, '=SUM(F5:F'.$sum.')');

            $sheet->getStyle('A'.$rowSum.':J'.$rowSum)->applyFromArray($HeadstyleArray);

         

          $spreadsheet->getActiveSheet()->getStyle('A5:J'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
          $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
          $sheet->getColumnDimension('A')->setWidth(5);
          
          foreach(range('B','J') as $columnID) {
              $sheet->getColumnDimension($columnID)->setAutoSize(true);
          }
          
          
          $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
          $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);

          $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
          $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

          $sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);


          $sheet->getPageMargins()->setLeft(0.3)->setRight(0.3)->setTop(0.4)->setBottom(0.4)->setHeader(0);
        

          $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
          $fileName = $fileName.'.xlsx';

          $this->output->set_header('Content-Type: application/vnd.ms-excel');
          $this->output->set_header("Content-type: application/csv");
          $this->output->set_header('Cache-Control: max-age=0');
          $writer->save("assets/tmp_download/".$fileName);
          //redirect(HTTP_UPLOAD_PATH.$fileName);
          $filepath = file_get_contents("assets/tmp_download/".$fileName);
          force_download($fileName, $filepath);
          delete_files($filepath, true);
          die();
    }
}

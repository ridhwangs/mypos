<?php

defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;


class Transaksi extends CI_Controller {

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
        $this->load->model(array('masuk_model','keluar_model'));
        $this->load->helper("terbilang");

		    $this->_init();

    }

    private function _init()
    {
      $this->output->set_template('main_layout');
    }

    public function masuk(){
      $data = array(
          'page_header' => 'Transaksi Masuk',
          'perusahaan' => $this->crud_model->read('perusahaan')->row(),
          'rekap_pembelian' => $this->masuk_model->rekap_pembelian()->result()
      );
      $this->load->view('transaksi/masuk/masuk_index', $data);
    }

    public function keluar($id = null){
      if(empty($id)){
        redirect('transaksi/keluar/TK-'.str_pad(date('ymdhis') + 1, 8, '0', STR_PAD_LEFT), 'refresh');
      }
      $where = [
        'DATE(tanggal)' => date('Y-m-d')
      ];
      $data = array(
          'page_header' => 'Transaksi Keluar',
          'group_transaksi' => $this->keluar_model->group_transaksi($where)->result(),
          'detail' => $this->crud_model->read('transaksi_keluar',['kd_transaksi' => $id])->result(),
          'perusahaan' => $this->crud_model->read('perusahaan')->row(),
      );
      
      $this->load->view('transaksi/keluar/keluar_index', $data);
    }

    public function create($attr)
    {
      switch ($attr) {
        case 'masuk':
            $data_barang = $this->crud_model->read('inventory', array('kd_barang' => $this->input->post('kd_barang')))->row();

            $data = array(
              'tanggal' => $this->input->post('tanggal'),
              'supplier' => $this->input->post('supplier'),
              'kd_barang' => $this->input->post('kd_barang'),
              'qty' => $this->input->post('qty'),
              'qty_before' => $data_barang->quantity_on_hand,
              'harga' => str_replace('.','',$this->input->post('harga')),
              'created_by' => $this->session->username,
              'created_at' => date('Y-m-d H:i:s')
            );
            $this->crud_model->create('transaksi_masuk', $data);
            
            $stock_sekarang = $data_barang->quantity_on_hand + $data['qty'];
            $this->crud_model->update('inventory', array('kd_barang' => $data['kd_barang']), array('quantity_on_hand' => $stock_sekarang));
            
            $message = preg_replace("/(\n)+/m", ' ', strip_tags( $data['kd_barang']." Berhasil di simpan"));
            $notify = array('title' => 'Info :',
                          'message' => $message,
                          'icon' => 'fa fa-info-circle',
                          'type' => 'info',
                          'validasi' => true
                        );     
            echo json_encode($notify);
          die();
          break;
        case 'keluar':
          $cek_barang = $this->crud_model->read('inventory', ['kd_barang' => $this->input->post('kd_barang'),'status' => '1'])->num_rows();
          if($cek_barang > 0){
            $data_barang = $this->crud_model->read('inventory', array('kd_barang' => $this->input->post('kd_barang')))->row();
            $data = array(
              'tanggal' => $this->input->post('tanggal'),
              'kd_transaksi' => $this->input->post('kd_transaksi'),
              'kd_barang' => $this->input->post('kd_barang'),
              'qty' => 1,
              'qty_before' => $data_barang->quantity_on_hand,
              'harga' => $data_barang->harga,
              'created_by' => $this->session->username,
              'created_at' => date('Y-m-d H:i:s')
            );
  
            $cek_stock = $this->crud_model->read('inventory', array('kd_barang' => $data['kd_barang']))->row();
  
            if($cek_stock->quantity_on_hand >= $data['qty']){
              $this->crud_model->create('transaksi_keluar', $data);
              
              $stock_sekarang = $data_barang->quantity_on_hand - $data['qty'];
              $this->crud_model->update('inventory', array('kd_barang' => $data['kd_barang']), array('quantity_on_hand' => $stock_sekarang));
                
              $message = preg_replace("/(\n)+/m", ' ', strip_tags( $data['kd_barang']." Berhasil di simpan"));
              $notify = array('title' => 'Info :',
                            'message' => $message,
                            'icon' => 'fa fa-info-circle',
                            'type' => 'info',
                            'validasi' => true
                          );     
              $error = false; 
            }else{
              $message = preg_replace("/(\n)+/m", ' ', strip_tags( $data['kd_barang']." Jumlah stock tidak memadai"));
              $notify = array('title' => 'Warning :',
                            'message' => $message,
                            'icon' => 'fa fa-info-circle',
                            'type' => 'warning',
                            'validasi' => false
                          );  
              $error = true;     
            }
          }else{
            $message = preg_replace("/(\n)+/m", ' ', strip_tags( $this->input->post('kd_barang')." Tidak Ditemukan"));
            $notify = array('title' => 'Warning :',
                          'message' => $message,
                          'icon' => 'fa fa-info-circle',
                          'type' => 'warning',
                          'validasi' => false
                        ); 
            $error = true;   
          }
         
          $set_flashdata = [
            'message' => date('Y-m-d H:i:s').' - '.$message,
            'error' => $error
          ];

          $this->session->set_flashdata($set_flashdata);
          redirect($this->agent->referrer());
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
        
        default:
            show_404();
          break;
      }
    }

    public function update($attr)
    {
      switch ($attr) {
        case 'penjualan':
            $where = [
              'tk_id' => $this->input->post('tk_id')
            ];
            $transak_keluar = $this->crud_model->read('transaksi_keluar',$where)->row();
            $data_barang = $this->crud_model->read('inventory', array('kd_barang' => $transak_keluar->kd_barang))->row();
            $qty_now = $transak_keluar->qty_before - $this->input->post('qty');
            $data = [
              'qty' => $this->input->post('qty')
            ];
        
            $stock_sekarang = $qty_now;

            // echo 'QTY BEFORE : '. $transak_keluar->qty_before.'<br>';
            // echo 'QTY AFTER : '. $transak_keluar->qty.'<br>';
            // echo 'INPUT : '. $this->input->post('qty').'<br>';
            // echo 'STOCK NOW : '. $stock_sekarang.'<br>';
            
     

            if($data['qty']  >  $transak_keluar->qty_before){
              $set_flashdata = [
                'message' => date('Y-m-d H:i:s').' - Tidak boleh melebihi dari jumlah stock ['.$transak_keluar->qty_before.']',
                'error' => true
              ];
    
              $this->session->set_flashdata($set_flashdata);
            }else{
              $this->crud_model->update('transaksi_keluar', $where, $data);
              $this->crud_model->update('inventory', array('kd_barang' => $transak_keluar->kd_barang), array('quantity_on_hand' => $stock_sekarang));  
        
              if($data['qty'] == 0){
                $this->crud_model->delete('transaksi_keluar', $where);
              }
            }

           
            // print_r($where);
            redirect($this->agent->referrer());
            die();
          break;
        
        default:
            show_404();
          break;
      }
    }

    public function delete($attr)
    {
      switch ($attr) {
        case 'masuk':
            $where = array('tm_id' => $this->input->post('tm_id'));
            
            $transaksi = $this->crud_model->read('transaksi_masuk', $where)->row();
            $data_barang = $this->crud_model->read('inventory', array('kd_barang' => $transaksi->kd_barang))->row();
            $stock_sekarang = $data_barang->quantity_on_hand - $transaksi->qty;
            $this->crud_model->update('inventory', array('kd_barang' => $transaksi->kd_barang), array('quantity_on_hand' => $stock_sekarang));

            $this->crud_model->delete('transaksi_masuk', $where);
            $message = preg_replace("/(\n)+/m", ' ', strip_tags(" Berhasil di hapus"));
            $notify = array('title' => 'Info :',
                          'message' => $message,
                          'icon' => 'fa fa-info-circle',
                          'type' => 'info',
                          'validasi' => true
                        );     
            echo json_encode($notify);
            die();
          break;
        case 'keluar':
          $where = array(
            'kd_transaksi' => $this->input->post('kd_transaksi'),
            'kd_barang' => $this->input->post('kd_barang')
          );
          
          $transaksi = $this->crud_model->sum('transaksi_keluar','qty', $where)->row();
          $data_barang = $this->crud_model->read('inventory', array('kd_barang' => $where['kd_barang']))->row();
          $stock_sekarang = $data_barang->quantity_on_hand + $transaksi->qty;

          $this->crud_model->update('inventory', array('kd_barang' => $where['kd_barang']), array('quantity_on_hand' => $stock_sekarang));
          $this->crud_model->delete('transaksi_keluar', $where);

          $message = preg_replace("/(\n)+/m", ' ', strip_tags(" Berhasil di hapus"));
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
    
    public function datatable($attr)
    {
      switch ($attr) {
        case 'masuk':
            $cari = $this->input->post('cari');
            $where = array(
              'DATE(transaksi_masuk.tanggal) >=' => $this->input->post('tgl_awal'),
              'DATE(transaksi_masuk.tanggal) <=' => $this->input->post('tgl_akhir')
            );

            if(!empty($cari)){
              $where['inventory.nm_barang LIKE'] = '%'.$cari.'%';
            }

            $list = $this->masuk_model->get_datatables($where);
            $data = array();
            $start = $_POST['start'];
            $no = $start;
            foreach ($list as $rows) {

                $no++;
                $row = array();

                $row[] = $no;
                $row[] = $rows->tanggal;
                $row[] = $rows->supplier;
                $row[] = $rows->kd_barang;
                $row[] = $rows->nm_barang;
                $row[] = "<div class='text-center'>".$rows->qty_before.'</div>';
                $row[] = "<div class='text-center'>+".$rows->qty.'</div>';
                $row[] = "<div class='text-center'>".$rows->qty_end.'</div>';
                $row[] = "<div class='text-center'>".$rows->satuan.'</div>';
                $row[] = "<div class='text-right'>".number_format($rows->harga_beli_satuan, 0, ',', '.')."</div>";
                $row[] = "<div class='text-right'>".number_format($rows->harga_beli, 0, ',', '.')."</div>";
                $row[] = '<a href="javascript:void(0)" onclick="hapus('.$rows->tm_id.')" ><i style="color:red" class="fas fa-trash-alt fa-1x"></i></a>';
                $data[] = $row;
            }
            $draw = $_POST['draw'];
            $output = array(
                "draw" => $draw,
                "recordsTotal" => $this->masuk_model->count_all($where),
                "recordsFiltered" => $this->masuk_model->count_filtered($where),
                "data" => $data,
            );
            //output to json format
            echo json_encode($output);
          die();
          break;
        case 'keluar':
          $where = array(
            'kd_transaksi' => $this->input->post('kd_transaksi'),
          );
          $list = $this->keluar_model->get_datatables($where);
          $data = array();
          $start = $_POST['start'];
          $no = $start;
          foreach ($list as $rows) {

              $no++;
              $row = array();

              $row[] = $no;
              $row[] = $rows->kd_barang;
              $row[] = $rows->nm_barang;
              $row[] = "<div class='text-center'>".$rows->sum_qty.'</div>';
              $row[] = "<div class='text-center'>".$rows->satuan.'</div>';
              $row[] = "<div class='text-right'>".number_format($rows->harga_jual_satuan, 0, ',', '.')."</div>";
              $row[] = "<div class='text-right'>".number_format($rows->harga_jual, 0, ',', '.')."</div>";
              $row[] = '<a href="javascript:void(0)" onclick="hapus(\''.$rows->kd_transaksi.'\',\''.$rows->kd_barang.'\')" ><i style="color:red" class="fas fa-trash-alt fa-1x"></i></a>';
              $data[] = $row;
          }
          $draw = $_POST['draw'];
          $output = array(
              "draw" => $draw,
              "recordsTotal" => $this->keluar_model->count_all($where),
              "recordsFiltered" => $this->keluar_model->count_filtered($where),
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

    public function export($attr)
    {
      switch ($attr) {
        case 'masuk':
          $this->load->helper('download');
          $data = array();
          $tgl_awal = $this->input->get('tgl_awal');
          $tgl_akhir = $this->input->get('tgl_akhir');

          $data['title'] = 'Pembelian Export '.$tgl_awal.' s/d '.$tgl_akhir;
          $fileName = 'Pembelian Export '.$tgl_awal.'sd'.$tgl_akhir.' - '.time();
          
          $spreadsheet = new Spreadsheet();
          $spreadsheet->getSheet(0);
          $spreadsheet->getSheetByName('Pembelian | MyPOS');
          $sheet = $spreadsheet->getActiveSheet()->setTitle('Pembelian | MyPOS');

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


          $sheet->getStyle('A4:K4')->applyFromArray($HeadstyleArray);
          
          $sheet->setCellValue('A1', 'Transaksi Masuk / Pembelian ');
          $sheet->setCellValue('A2', $tgl_awal.' s/d '.$tgl_akhir);

          $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
          $richText->createText('Dokumen ini di export pada ');
          $payable = $richText->createTextRun(date('Y-m-d H:i:s'));
          $payable->getFont()->setBold(true);
          $payable->getFont()->setItalic(true);
          $payable->getFont()->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
          $spreadsheet->getActiveSheet()->getCell('A3')->setValue($richText);
          $sheet->mergeCells("A3:K3");
          $sheet->getStyle('A3:K3')->getAlignment()->setHorizontal('right')->setVertical('center');


          $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(25);


          //header
          $sheet->setCellValue('A4', "No");
          $sheet->setCellValue('B4', "Tanggal");
          $sheet->setCellValue('C4', "Supplier");
          $sheet->setCellValue('D4', "Kode Barang");
          $sheet->setCellValue('E4', "Nama Barang");
          $sheet->setCellValue('F4', "Qty Before");
          $sheet->setCellValue('G4', "Qty Buy");
          $sheet->setCellValue('H4', "Qty End");
          $sheet->setCellValue('I4', "Satuan");
          $sheet->setCellValue('J4', "Harga Beli Satuan");
          $sheet->setCellValue('K4', "Harga Beli");

          $sheet->getStyle('A4:K4')->getAlignment()->setHorizontal('center')->setVertical('center');
          $sheet->getStyle('A4:K4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR);
         

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

          $where = array(
              'DATE(transaksi_masuk.tanggal) >=' => $tgl_awal,
              'DATE(transaksi_masuk.tanggal) <=' => $tgl_akhir
          );

          $queryHead = $this->masuk_model->export_query($where);
          $resultHead = $queryHead->result_array();
          $rowCountHead = 3;
          $no = 1;
          $total = 0;

          foreach ($resultHead as $key => $rows) {
                $total++;
                if (@$resultHead[$key-1]['tanggal'] != $rows['tanggal']) {
                    $no = 0;
                    $no++;
                    $rowCountHead = $rowCountHead + 2;

                }


                $sheet->setCellValue('A' . $rowCountHead, $no++);
                $sheet->setCellValue('B' . $rowCountHead, $rows['tanggal']);
                $sheet->setCellValue('C' . $rowCountHead, $rows['supplier']);
                $sheet->setCellValue('D' . $rowCountHead, $rows['kd_barang']);
                $sheet->setCellValue('E' . $rowCountHead, $rows['nm_barang']);
                $sheet->setCellValue('F' . $rowCountHead, $rows['qty_before']);
                $sheet->setCellValue('G' . $rowCountHead, $rows['qty']);
                $sheet->setCellValue('H' . $rowCountHead, $rows['qty_end']);
                $sheet->setCellValue('I' . $rowCountHead, $rows['satuan']);
                $sheet->setCellValue('J' . $rowCountHead, $rows['harga_beli_satuan']);
                $sheet->setCellValue('K' . $rowCountHead, $rows['harga_beli']);

                $firstRow = $rowCountHead - $no + 2;
                $rowTotal = $rowCountHead + 1;

                $sheet->setCellValue('E' . $rowTotal, "SUB TOTAL");

                $sheet->setCellValue('F' . $rowTotal, '=SUM(F'.$firstRow.':F'.$rowCountHead.')');
                $sheet->setCellValue('G' . $rowTotal, '=SUM(G'.$firstRow.':G'.$rowCountHead.')');
                $sheet->setCellValue('J' . $rowTotal, '=SUM(J'.$firstRow.':J'.$rowCountHead.')');
                $sheet->setCellValue('K' . $rowTotal, '=SUM(K'.$firstRow.':K'.$rowCountHead.')');


                $rowCountHead++;
                $border = $rowCountHead -1;
                $sheet->getStyle('A'.$border.':K'.$border)->applyFromArray($IsistyleArray);
              }

            $rowSum = $rowCountHead + 2;
            
            $spreadsheet->getActiveSheet()->getStyle('J5:K'.$rowSum)->getNumberFormat()->setFormatCode('#,##0');
            
            $sum = $rowSum - 1;
            $sheet->setCellValue('A'.$rowSum, "GRAND TOTAL ($total of transaksi)");
            $sheet->setCellValue('F'.$rowSum, '=SUM(F5:F'.$sum.')/2');
            $sheet->setCellValue('G'.$rowSum, '=SUM(G5:G'.$sum.')/2');
            $sheet->setCellValue('H'.$rowSum, '=SUM(H5:H'.$sum.')/2');
            $sheet->setCellValue('J'.$rowSum, '=SUM(J5:J'.$sum.')/2');
            $sheet->setCellValue('K'.$rowSum, '=SUM(K5:K'.$sum.')/2');

            $sheet->getStyle('A'.$rowSum.':K'.$rowSum)->applyFromArray($HeadstyleArray);

         

          $spreadsheet->getActiveSheet()->getStyle('A5:K'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
          $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
          $sheet->getColumnDimension('A')->setWidth(5);
          
          foreach(range('B','K') as $columnID) {
              $sheet->getColumnDimension($columnID)->setAutoSize(true);
          }
          
          
          $sheet->getPageMargins()->setLeft(0.3)->setRight(0.3)->setTop(0.4)->setBottom(0.4)->setHeader(0);
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
          break;

        case 'keluar':
          $this->load->helper('download');
          $data = array();
          $tgl_awal = $this->input->get('tgl_awal');
          $tgl_akhir = $this->input->get('tgl_akhir');

          $data['title'] = 'Penjualan Export '.$tgl_awal.' s/d '.$tgl_akhir;
          $fileName = 'Penjualan Export '.$tgl_awal.'sd'.$tgl_akhir.' - '.time();
          
          $spreadsheet = new Spreadsheet();
          $spreadsheet->getSheet(0);
          $spreadsheet->getSheetByName('Penjualan | MyPOS');
          $sheet = $spreadsheet->getActiveSheet()->setTitle('Penjualan | MyPOS');

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


          $sheet->getStyle('A4:O4')->applyFromArray($HeadstyleArray);
          
          $sheet->setCellValue('A1', 'Transaksi Keluar / Penjualan ');
          $sheet->setCellValue('A2', $tgl_awal.' s/d '.$tgl_akhir);

          $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
          $richText->createText('Dokumen ini di export pada ');
          $payable = $richText->createTextRun(date('Y-m-d H:i:s'));
          $payable->getFont()->setBold(true);
          $payable->getFont()->setItalic(true);
          $payable->getFont()->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
          $spreadsheet->getActiveSheet()->getCell('A3')->setValue($richText);
          $sheet->mergeCells("A3:O3");
          $sheet->getStyle('A3:O3')->getAlignment()->setHorizontal('right')->setVertical('center');

          $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(25);


          //header
          $sheet->setCellValue('A4', "No");
          $sheet->setCellValue('B4', "Tanggal");
          $sheet->setCellValue('C4', "Supplier");
          $sheet->setCellValue('D4', "Kode Barang");
          $sheet->setCellValue('E4', "Nama Barang");
          $sheet->setCellValue('F4', "Qty Before");
          $sheet->setCellValue('G4', "Qty Buy");
          $sheet->setCellValue('H4', "Qty End");
          $sheet->setCellValue('I4', "Satuan");
          $sheet->setCellValue('J4', "Harga Jual Satuan");
          $sheet->setCellValue('K4', "Harga Jual");
          $sheet->setCellValue('L4', "Harga Beli Satuan");
          $sheet->setCellValue('M4', "Harga Beli");
          $sheet->setCellValue('N4', "Margin Satuan");
          $sheet->setCellValue('O4', "Margin");

          $sheet->getStyle('A4:O4')->getAlignment()->setHorizontal('center')->setVertical('center');
          $sheet->getStyle('A4:O4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR);
         

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

          $where = array(
              'DATE(transaksi_keluar.tanggal) >=' => $tgl_awal,
              'DATE(transaksi_keluar.tanggal) <=' => $tgl_akhir
          );

          $queryHead = $this->keluar_model->export_query($where);
          $resultHead = $queryHead->result_array();
          $rowCountHead = 3;
          $no = 1;
          $total = 0;

          foreach ($resultHead as $key => $rows) {
                $total++;
                if (@$resultHead[$key-1]['tanggal'] != $rows['tanggal']) {
                    $no = 0;
                    $no++;
                    $rowCountHead = $rowCountHead + 2;

                }


                $sheet->setCellValue('A' . $rowCountHead, $no++);
                $sheet->setCellValue('B' . $rowCountHead, $rows['tanggal']);
                $sheet->setCellValue('C' . $rowCountHead, $rows['pelanggan']);
                $sheet->setCellValue('D' . $rowCountHead, $rows['kd_barang']);
                $sheet->setCellValue('E' . $rowCountHead, $rows['nm_barang']);
                $sheet->setCellValue('F' . $rowCountHead, $rows['qty_before']);
                $sheet->setCellValue('G' . $rowCountHead, $rows['qty']);
                $sheet->setCellValue('H' . $rowCountHead, $rows['qty_end']);
                $sheet->setCellValue('I' . $rowCountHead, $rows['satuan']);
                $sheet->setCellValue('J' . $rowCountHead, $rows['harga_jual_satuan']);
                $sheet->setCellValue('K' . $rowCountHead, $rows['harga_jual']);
                $sheet->setCellValue('L' . $rowCountHead, $rows['harga_beli_satuan']);
                $sheet->setCellValue('M' . $rowCountHead, $rows['harga_beli']);
                $sheet->setCellValue('N' . $rowCountHead, $rows['margin_satuan']);
                $sheet->setCellValue('O' . $rowCountHead, $rows['margin']);

                $firstRow = $rowCountHead - $no + 2;
                $rowTotal = $rowCountHead + 1;

                $sheet->setCellValue('E' . $rowTotal, "SUB TOTAL");

                $sheet->setCellValue('F' . $rowTotal, '=SUM(F'.$firstRow.':F'.$rowCountHead.')');
                $sheet->setCellValue('G' . $rowTotal, '=SUM(G'.$firstRow.':G'.$rowCountHead.')');
                $sheet->setCellValue('J' . $rowTotal, '=SUM(J'.$firstRow.':J'.$rowCountHead.')');
                $sheet->setCellValue('K' . $rowTotal, '=SUM(K'.$firstRow.':K'.$rowCountHead.')');
                $sheet->setCellValue('L' . $rowTotal, '=SUM(L'.$firstRow.':L'.$rowCountHead.')');
                $sheet->setCellValue('M' . $rowTotal, '=SUM(M'.$firstRow.':M'.$rowCountHead.')');
                $sheet->setCellValue('N' . $rowTotal, '=SUM(N'.$firstRow.':N'.$rowCountHead.')');
                $sheet->setCellValue('O' . $rowTotal, '=SUM(O'.$firstRow.':O'.$rowCountHead.')');


                $rowCountHead++;
                $border = $rowCountHead -1;
                $sheet->getStyle('A'.$border.':O'.$border)->applyFromArray($IsistyleArray);
              }

            $rowSum = $rowCountHead + 2;
            
            $spreadsheet->getActiveSheet()->getStyle('J5:O'.$rowSum)->getNumberFormat()->setFormatCode('#,##0');
            
            $sum = $rowSum - 1;
            $sheet->setCellValue('A'.$rowSum, "GRAND TOTAL ($total of transaksi)");
            $sheet->setCellValue('F'.$rowSum, '=SUM(F5:F'.$sum.')/2');
            $sheet->setCellValue('G'.$rowSum, '=SUM(G5:G'.$sum.')/2');
            $sheet->setCellValue('H'.$rowSum, '=SUM(H5:H'.$sum.')/2');
            $sheet->setCellValue('J'.$rowSum, '=SUM(J5:J'.$sum.')/2');
            $sheet->setCellValue('K'.$rowSum, '=SUM(K5:K'.$sum.')/2');
            $sheet->setCellValue('L'.$rowSum, '=SUM(L5:L'.$sum.')/2');
            $sheet->setCellValue('M'.$rowSum, '=SUM(M5:M'.$sum.')/2');
            $sheet->setCellValue('N'.$rowSum, '=SUM(N5:N'.$sum.')/2');
            $sheet->setCellValue('O'.$rowSum, '=SUM(O5:O'.$sum.')/2');

          $sheet->getStyle('A'.$rowSum.':O'.$rowSum)->applyFromArray($HeadstyleArray);
         

          $spreadsheet->getActiveSheet()->getStyle('A5:O'.$spreadsheet->getActiveSheet()->getHighestRow())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
          $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
          $sheet->getColumnDimension('A')->setWidth(5);
          
          foreach(range('B','O') as $columnID) {
              $sheet->getColumnDimension($columnID)->setAutoSize(true);
          }
          
          
          $sheet->getPageMargins()->setLeft(0.3)->setRight(0.3)->setTop(0.4)->setBottom(0.4)->setHeader(0);
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
          break;
        default:
            show_404();
          break;
      }
    }

    public function pembayaran()
    {

      $data = [
        'page_header' => 'Pembayaran '.$this->input->post('kd_transaksi'),
        'kd_transaksi' => $this->input->post('kd_transaksi'),
        'jumlah' => $this->input->post('jumlah'),
        'bayar' => str_replace('.','',$this->input->post('bayar')),
        'kembali' => str_replace('.','',$this->input->post('bayar')) - $this->input->post('jumlah'),
        'perusahaan' => $this->crud_model->read('perusahaan')->row(),
      ];
      $this->crud_model->update('transaksi_keluar',['kd_transaksi' => $data['kd_transaksi']], ['pelanggan' => true]);
      if($data['jumlah'] > $data['bayar']){
        $set_flashdata = [
          'message' => "Pembayaran harus lebih besar dari harga total.",
          'error' => true,
          'autofocus' => 'bayar',
        ];
        $this->session->set_flashdata($set_flashdata);
        redirect($this->agent->referrer());
      }elseif($data['jumlah'] == 0){
        $set_flashdata = [
          'message' => "Tidak ada detail barang.",
          'error' => true,
          'autofocus' => 'kd_barang',
        ];
        $this->session->set_flashdata($set_flashdata);
        redirect($this->agent->referrer());
      }
      
      $this->print();
      $this->load->view('transaksi/keluar/keluar_pembayaran', $data);
    }

    public function pendapatan()
    {
      $where = [
        'DATE(tanggal) >=' => $this->input->get('tgl_awal'),
        'DATE(tanggal) <=' => $this->input->get('tgl_akhir')
      ];
      $data = [
        'page_header' => "Table Pendapatan",
        'group_transaksi' => $this->keluar_model->group_transaksi($where)->result(),
      ];
      $this->load->view('transaksi/keluar/keluar_pendapatan', $data);
    }

    public function print()
    {
      $connector = new WindowsPrintConnector('POS58');
      $printer = new Printer($connector);
      $printer->initialize();

      /* Date is kept the same for testing */
      $date = date('D j M Y H:i:s');

      function buatBaris4Kolom($kolom1, $kolom2, $kolom3, $kolom4) {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 14;
            $lebar_kolom_2 = 3;
            $lebar_kolom_3 = 8;
            $lebar_kolom_4 = 14;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
            $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);
            $kolom4Array = explode("\n", $kolom4);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
                $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n",$hasilBaris) . "\n";
        }   

      // try {
      // $logo = EscposImage::load("./assets/logo/".$perusahaan->logo, false);
      // $printer->graphics($logo);
      // }catch (Exception $e) {
      //     /* Images not supported on your PHP, or image file not found */
      //     $printer->text($e->getMessage() . "\n");
      //     $printer->feed();
      // }

      $perusahaan = $this->crud_model->read('perusahaan')->row();

      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
     
      $printer->text($perusahaan->nama_perusahaan . "\n");
      $printer->selectPrintMode();

      $printer->setFont(Printer::FONT_B);
      $printer->text($perusahaan->alamat . "\n");
      $printer->setFont();

      $printer->setJustification();
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->text("--------------------------------\n");
      $printer->setJustification();
      $printer->setFont(Printer::FONT_C);
      $printer->text(buatBaris4Kolom(date('d F y'), "", date('H:i:s'), $this->input->post('kd_transaksi')));
      $printer->setFont();
      $printer->setJustification();
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->text("--------------------------------\n");
      $printer->setJustification();


      

      $printer->setFont(Printer::FONT_C);
      $transaki = $this->crud_model->read('transaksi_keluar',['kd_transaksi' => $this->input->post('kd_transaksi')])->result();
      $no = 0;
      foreach ($transaki as $key => $rows) {
        $no++;
        $inventory = $this->crud_model->read('inventory', ['kd_barang' => $rows->kd_barang])->row();
        $printer->text($no.". ".$inventory->nm_barang."\n");
        $printer->text(buatBaris4Kolom("   Rp. ".number_format($rows->harga, 0, ',', '.'), "x ".$rows->qty, $inventory->satuan , number_format($rows->harga * $rows->qty, 0, ',', '.')));
      }    
      $printer->setFont();

      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->text("--------------------------------\n");
      $printer->setJustification();

      $printer->setFont(Printer::FONT_C);
      $printer->text(buatBaris4Kolom("", "", "Bayar", 'Rp. '.number_format(str_replace('.','',$this->input->post('bayar')), 0, ',', '.')));
      $printer->text(buatBaris4Kolom("", "", "Total", 'Rp. '.number_format($this->input->post('jumlah'), 0, ',', '.')));
      $kembali = str_replace('.','',$this->input->post('bayar')) - $this->input->post('jumlah');
      if($kembali > 0):
        $printer->text(buatBaris4Kolom("", "", "Kembali", 'Rp. '.number_format($kembali), 0, ',', '.'));
      endif;
      $printer->setFont();
      $printer->feed();

      $printer->setFont(Printer::FONT_C);
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->text("ALHAMDULILLAH MATUR NUWUN\n");
      $printer->text("BELANJA MURAH, BERKAH MELIMPAH RUAH\n");
      $printer->setJustification();
      $printer->setFont();

      $printer->feed();
      $printer->feed();
      $printer->pulse();
      $printer->close();
    }
}

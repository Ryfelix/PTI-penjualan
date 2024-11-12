<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('m_user');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index() {
        if ($this->session->userdata('level') != 'Petugas') {
            redirect('login');
        } else {
            $data['petugas'] = $this->m_user->selectPetugas()->row();
            $this->load->view('petugas/header');
            $this->load->view('petugas/home', $data);
            $this->load->view('petugas/footer');
        }
    }

    function dataPetugas()
    {
    if (!$this->session->userdata('level')=='Admin') {
        redirect('login');
    }else{
        $data['admin'] = $this->m_user->selectAdmin()->row();
        $data['dataPetugas'] = $this->m_user->getPetugas()->result();

        $this->load->view('admin/header',$data);
        $this->load->view('admin/dataPetugas');
        $this->load->view('admin/footer');
    }
    }   

    #Export
    function export() {
        // Check if the user level is 'Admin'
        if ($this->session->userdata('level') !== 'Admin') {
            redirect('login');
        } else {
            $excel = new PHPExcel();
    
            // Setting initial Excel properties
            $excel->getProperties()->setCreator('XYZ')
                ->setLastModifiedBy('XYZ')
                ->setTitle("Data Petugas")
                ->setSubject("Petugas")
                ->setDescription("Laporan Semua Data Petugas")
                ->setKeywords("Data Petugas");
    
            // Style for header cells
            $style_col = array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E1E0F7'),
                ),
                'font' => array('bold' => true),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ),
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                ),
            );
    
            // Style for rows
            $style_row = array(
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ),
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                ),
            );
    
            // Set title for the sheet
            $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA Petugas");
            $excel->getActiveSheet()->mergeCells('A1:E1');
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
            $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
            // Set the date of report generation
            $excel->setActiveSheetIndex(0)->setCellValue('A3', "Tanggal Cetak: " . date("d F Y"));
    
            // Create table header
            $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
            $excel->setActiveSheetIndex(0)->setCellValue('B3', "Id Petugas");
            $excel->setActiveSheetIndex(0)->setCellValue('C3', "Nama");
            $excel->setActiveSheetIndex(0)->setCellValue('D3', "Email");
    
            // Apply style to header cells
            $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
    
            // Fetch data for petugas
            $dataPetugas = $this->m_user->getPetugas()->result();
            $no = 1;
            $numrow = 4; // Start the table from row 4
    
            foreach ($dataPetugas as $data) {
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
                $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->idUser);
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->nama);
                $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data->email);
    
                // Apply row style
                $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
    
                $no++; // Increment the counter
                $numrow++; // Move to the next row
            }
    
            // Set column widths
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    
            // Set row height automatically
            $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
    
            // Set page orientation to LANDSCAPE
            $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    
            // Set the title for the sheet
            $excel->getActiveSheet(0)->setTitle("Laporan Data Petugas");
            $excel->setActiveSheetIndex(0);
    
            // Output the file
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Data Petugas.xlsx"');
            header('Cache-Control: max-age=0');
            
            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $write->save('php://output');
        }
    }

    function exportPDF(){
        $data['dataPetugas'] = $this->m_user->getPetugas()->result();
        $this->pdf->load_view('admin/laporan/laporanPetugas',$data);
        $tgl = date("d/m/Y");
        $this->pdf->render();
        $this->pdf->stream("Laporan-Petugas_".$tgl.".pdf");
        }
}
?>

<?php
function tambah()
{
    // Cek login
    if (!$this->session->userdata('level') == 'Admin') {
        redirect('login');
    } else {
        if ($this->input->method() == 'post') {
            $this->m_user->tambah();
            $this->session->set_flashdata('info', 'Data berhasil ditambah');
            redirect('petugas/dataPetugas');
        } else {
            $data['admin'] = $this->m_user->selectAdmin()->row();
            $data['kodeunik'] = $this->m_user->getkodeunik();

            $this->load->view('admin/header', $data);
            $this->load->view('admin/tambahPetugas');
            $this->load->view('admin/footer');
        }
    }
}

defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model(array('m_barang', 'm_user'));
        date_default_timezone_set('Asia/Jakarta');
    }

    function getkodeunik()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(idBarang, 2)) AS idmax FROM barang");
        $kd = ""; // Kode awal

        if ($q->num_rows() > 0) { // Jika data ada
            foreach ($q->result() as $k) {
                $tmp = ((int)$k->idmax) + 1; // String kode di-set ke integer dan ditambahkan 1 dari kode terakhir
                $kd = sprintf("%02s", $tmp); // Kode ambil 4 karakter terakhir
            }
        } else { // Jika data kosong di-set ke kode awal
            $kd = "01";
        }
        $kar = "B"; // Karakter depan kodenya

        // Gabungkan string dengan kode yang telah dibuat tadi
        return $kar . $kd;
    }

    function tambah()
    {
        if (!$this->session->userdata('level') == 'Petugas') {
            redirect('login');
        } else {
            if ($this->input->method() == 'post') {
                $this->m_barang->tambah();
                $this->session->set_flashdata('info', 'Data berhasil ditambah');
                redirect('barang/tambah');
            } else {
                $data['kodeunik'] = $this->m_barang->getkodeunik();
                $this->load->view('petugas/header');
                $this->load->view('petugas/tambahBarang', $data);
                $this->load->view('petugas/footer');
            }
        }
    }

    public function barang()
{
    if (!$this->session->userdata('level') == 'Petugas') {
        redirect('login');
    } else {
        $data['dataBarang'] = $this->m_barang->getBarang()->result();
        $this->load->view('petugas/header');
        $this->load->view('petugas/dataBarang', $data);
        $this->load->view('petugas/footer');
    }
}

function dataBarang()
{
    if (!$this->session->userdata('level') == 'Admin') {
        redirect('login');
    } else {
        $data['admin'] = $this->m_user->selectAdmin()->row();
        $data['dataBarang'] = $this->m_barang->getBarang()->result();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/dataBarang');
        $this->load->view('admin/footer');
    }
}

public function ubahBarang($idBarang) {
    if (!$this->session->userdata('level') == 'Admin') {
        redirect('login');
    } else {
        if ($this->input->method() == 'post') {
            $this->m_barang->ubahBarang($idBarang);
            $this->session->set_flashdata('info', 'Data berhasil diubah');
            redirect('barang/barang');
        } else {
            $data['dataBarang'] = $this->m_barang->selectBarang($idBarang)->row();
            $this->load->view('petugas/header');
            $this->load->view('petugas/ubahBarang', $data);
            $this->load->view('petugas/footer');
        }
    }
}

function ubah($idBarang)
{
    if (!$this->session->userdata('level') == 'Admin') {
        redirect('login');
    } else {
        if ($this->input->method() == 'post') {
            // Call the model method to update the item data
            $this->m_barang->ubah($idBarang);

            // Set success message and redirect to the dataBarang page
            $this->session->set_flashdata('info', 'Data berhasil diubah');
            redirect('barang/dataBarang');
        } else {
            // Fetch admin data and item data
            $data['admin'] = $this->m_user->selectAdmin()->row();
            $data['dataBarang'] = $this->m_barang->selectBarang($idBarang)->row();

            // Load views with data
            $this->load->view('admin/header', $data);
            $this->load->view('admin/ubahBarang', $data);
            $this->load->view('admin/footer');
        }
    }
}

}
class Penjualan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary models
        $this->load->model(array('m_penjualan', 'm_user', 'm_barang'));
        date_default_timezone_set('Asia/Jakarta');
    }

    // Method to handle the addition of a sale transaction
    public function tambahPenjualan() {
        // Generate unique transaction code and get all available products
        $data['kodeunik'] = $this->m_penjualan->getkodeunik();
        $data['dataBarang'] = $this->m_barang->getBarang()->result();

        // Check if form has been submitted
        if ($this->input->method() == 'post') {
            // Validate input data (e.g. check if 'qty' is a valid number and 'idBarang' is selected)
            $this->form_validation->set_rules('idBarang', 'Barang', 'required');
            $this->form_validation->set_rules('qty', 'Quantity', 'required|numeric');

            // If form validation passes, process the transaction
            if ($this->form_validation->run() == TRUE) {
                // Call the model function to handle the sale transaction
                $this->m_penjualan->tambah();
                // Set a success message
                $this->session->set_flashdata('info', 'Transaksi berhasil disimpan');
                // Redirect to the same page (or to a list of sales if you want)
                redirect('penjualan/tambahPenjualan');
            } else {
                // If validation fails, show an error message
                $this->session->set_flashdata('error', 'Silakan isi semua data dengan benar.');
            }
        }

        // Load the views for the page
        $this->load->view('petugas/header');
        $this->load->view('petugas/tambahPenjualan', $data);
        $this->load->view('petugas/footer');
    }


    public function stok()
    {
    if(!$this->session->userdata('level')=='Petugas') {
    redirect('login');
    }else{
    $data['dataBarang'] = $this->m_barang->getBarang()->result();
    $this->load->view('petugas/header');

    $this->load->view('petugas/stok',$data);
    $this->load->view('petugas/footer');
    }
}

#EXPORT TO EXCEL
public function export() {
    // Check if user is Admin
    if (!$this->session->userdata('level') == 'Admin') {
        redirect('login');
    } else {
        // Initialize PHPExcel
        $excel = new PHPExcel();
        $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/gambar/';

        // Set document properties
        $excel->getProperties()
            ->setCreator('XYZ')
            ->setLastModifiedBy('XYZ')
            ->setTitle("Data Barang")
            ->setSubject("Barang")
            ->setDescription("Laporan Semua Data Barang")
            ->setKeywords("Data Barang");

        // Style for table headers
        $style_col = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'E1E0F7'),
            ),
            'font' => array('bold' => true), // Bold font
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

        // Style for table rows
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

        // Set title of the sheet
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA BARANG");
        $excel->getActiveSheet()->mergeCells('A1:E1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Set header row
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Id Barang");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Nama Barang");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Harga");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Stok");

        // Apply header style
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);

        // Fetch all data from the database
        $dataBarang = $this->m_barang->getBarang()->result();
        $no = 1; // For row numbering
        $numrow = 4; // Start from row 4

        // Loop through the data and fill in the table rows
        foreach ($dataBarang as $data) {
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->idBarang);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->namaBarang);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, 'Rp ' . number_format($data->harga, 0, ',', '.'));
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data->stok);

            // Apply style to the row
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);

            $no++; // Increment row number
            $numrow++; // Move to the next row
        }

        // Set column widths
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(5);

        // Set row height to auto
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set page orientation to landscape
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set sheet title
        $excel->getActiveSheet()->setTitle("Laporan Data Barang");

        // Set headers for the file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Barang.xlsx"');
        header('Cache-Control: max-age=0');

        // Write the Excel file to the output
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}

public function exportPDF(){
    $data['dataBarang'] = $this->m_barang->getBarang()->result();
    $tgl = date("Y/m/d");
    $this->pdf->load_view('admin/laporan/laporanBarang',$data);
    $this->pdf->render();
    set_time_limit (500);
    $this->pdf->stream("Laporan-Barang".$tgl.".pdf");
    }
}
?>

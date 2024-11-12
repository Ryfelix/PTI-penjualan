<?php
function penjualan()
{
    $data['dataPenjualan'] = $this->m_penjualan->getPenjualanPetugas()->result();   
    $this->load->view('petugas/header');
    $this->load->view('petugas/dataPenjualan',$data);
    $this->load->view('petugas/footer');
}

function dataPenjualan()
{
    // Check if user is not an Admin
    if ($this->session->userdata('level') !== 'Admin') {
        // Redirect to login if not an Admin
        redirect('login');
    } else {
        // Fetch data for Admin and Penjualan (sales)
        $data['admin'] = $this->m_user->selectAdmin()->row();
        $data['dataPenjualan'] = $this->m_penjualan->getPenjualan()->result();
        
        // Load the views with the fetched data
        $this->load->view('admin/header', $data);
        $this->load->view('admin/dataPenjualan', $data); // Pass $data to the view
        $this->load->view('admin/footer');
    }
}

#Export to Excel
function export() {
    // Check if the user level is Admin
    if (!$this->session->userdata('level') == 'Admin') {
        redirect('login');
    } else {
        // Create a new PHPExcel instance
        $excel = new PHPExcel();

        // Set initial properties for the Excel file
        $excel->getProperties()
              ->setCreator('XYZ')
              ->setLastModifiedBy('XYZ')
              ->setTitle("Data Penjualan")
              ->setSubject("Penjualan")
              ->setDescription("Laporan Semua Data Penjualan")
              ->setKeywords("Data Penjualan");

        // Define the style for the table header
        $style_col = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'E1E0F7'),
            ),
            'font' => array('bold' => true),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            ),
        );

        // Define the style for the table rows
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            ),
        );

        // Set the title in cell A1
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "Data Penjualan");
        $excel->getActiveSheet()->mergeCells('A1:H1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Set the print date in cell A3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "Tanggal Cetak : " . date("d F Y"));

        // Set the header for the table in row 4
        $excel->setActiveSheetIndex(0)->setCellValue('A4', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B4', "Id Penjualan");
        $excel->setActiveSheetIndex(0)->setCellValue('C4', "Nama Barang");
        $excel->setActiveSheetIndex(0)->setCellValue('D4', "Harga");
        $excel->setActiveSheetIndex(0)->setCellValue('E4', "Tgl Transaksi");
        $excel->setActiveSheetIndex(0)->setCellValue('F4', "QTY");
        $excel->setActiveSheetIndex(0)->setCellValue('G4', "Total");
        $excel->setActiveSheetIndex(0)->setCellValue('H4', "Petugas");

        // Apply style to the header columns
        $excel->getActiveSheet()->getStyle('A4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H4')->applyFromArray($style_col);

        // Retrieve the sales data
        $dataPenjualan = $this->m_penjualan->getPenjualan()->result();
        $no = 1; // Set initial row number
        $numrow = 5; // Start the table data from row 5

        // Loop through the sales data and add it to the table
        foreach ($dataPenjualan as $data) {
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->idPenjualan);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->namaBarang);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, 'Rp ' . $data->harga);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, date('d F Y', strtotime($data->tglTransaksi)));
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data->qty);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, 'Rp ' . number_format($data->harga * $data->qty, 0, ',', '.'));
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data->nama);

            // Apply style to each row
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);

            // Increment row numbers
            $no++;
            $numrow++;
        }

        // Set auto row height
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set paper orientation to LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set the title for the Excel sheet
        $excel->getActiveSheet(0)->setTitle("Laporan Data Penjualan");
        $excel->setActiveSheetIndex(0);

        // Output the Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Penjualan.xlsx"');
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}

function exportPDF(){
    $data['dataPenjualan'] = $this->m_penjualan->getPenjualan()->result();
    $this->pdf->load_view('admin/laporan/laporanPenjualan',$data);
    
    $tgl = date("d/m/Y");
    $this->pdf->render();
    $this->pdf->stream("Laporan-Penjualan_".$tgl.".pdf");
    }
?>
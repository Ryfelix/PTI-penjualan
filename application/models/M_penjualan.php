<?php if ( ! defined('BASEPATH')) exit('No direct script access
allowed');

class M_penjualan extends CI_Model {
    function statistikPenjualan()
    {
        $this->db->select('barang.namaBarang, SUM(penjualan.qty) AS
        qty');
        $this->db->from('penjualan');
        $this->db->join('barang','idBarang');
        $this->db->group_by('barang.namaBarang');
        $this->db->order_by('penjualan.idBarang');
        $query = $this->db->get();
        return $query;
    }

    function jumlahPenjualan()
    {
        $this->db->select('count(penjualan.idPenjualan) AS
        jumTransaksi,SUM(barang.harga*qty) AS jumPendapatan');
        $this->db->from('penjualan');
        $this->db->join('barang','idBarang');
        $query = $this->db->get();
        return $query;
    }
     // Function to generate unique code for 'idPenjualan'
    function getkodeunik()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(idPenjualan, 2)) AS idmax FROM penjualan");
        $kd = ""; // Initial code

        // Check if there are rows
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $k) {
                // Convert string to integer, add 1 to the last code, and format it to 2 digits
                $tmp = ((int)$k->idmax) + 1;
                $kd = sprintf("%02s", $tmp); // Keep 2 digits for the code
            }
        } else {
            // If no data, set the initial code
            $kd = "01";
        }

        $kar = "T"; // Character prefix for the code
        return $kar . $kd; // Combine the prefix and generated code
    }

    // Function to handle adding a new transaction
    function tambah()
    {
        $idPenjualan = $this->input->post('idPenjualan');
        $idBarang = $this->input->post('idBarang');
        $qty = $this->input->post('qty');
        $tgl = date('Y/m/d'); // Get the current date
        $idPetugas = $this->session->userdata('id'); // Get the ID of the logged-in user

        $this->load->model('m_barang');
        
        // Get the price of the selected item
        $harga = $this->m_barang->selectBarang($idBarang)->row();
        
        // Calculate the total amount
        $total = $qty * $harga->harga;

        // Prepare data for insertion into 'penjualan' table
        $data = array(
            'idPenjualan' => $idPenjualan,
            'idBarang' => $idBarang,
            'tglTransaksi' => $tgl,
            'qty' => $qty,
            'idUser' => $idPetugas
        );
        
        // Insert transaction into 'penjualan' table
        $this->db->insert('penjualan', $data);
        
        // Update the stock of the item in the 'barang' table
        $this->db->query("UPDATE barang SET stok = stok - '$qty' WHERE idBarang = '$idBarang'");
        
        // Set flash message indicating success
        $this->session->set_flashdata('info', "Transaksi Berhasil, Total: Rp $total");
    }
    
    function getPenjualanPetugas(){
        $idUser = $this->session->userdata('id');
        $this->db->select('penjualan.*,barang.*,user.nama');
        $this->db->from('penjualan');
        $this->db->join('barang','idBarang');
        $this->db->join('user','idUser');
        $this->db->where('idUser',$idUser);
        $query = $this->db->get();
        return $query;
    }
    

    function getPenjualan(){
        $this->db->select('penjualan.*,barang.*,user.nama');
        $this->db->from('penjualan');
        $this->db->join('barang','idBarang');
        $this->db->join('user','idUser');
        $this->db->order_by('idPenjualan');
        $query = $this->db->get();
        return $query;
    }

        function hapus($idPenjualan){
            $this->db->where('idPenjualan', $idPenjualan);
            $this->db->delete('penjualan');

        if (!$this->session->userdata('level')=='Admin') {
            redirect('login');
            }else{
            $this->m_penjualan->hapus($idPenjualan);
            $this->session->set_flashdata('info', 'SUKSESS : Berhasil di Hapus');
            redirect('penjualan/dataPenjualan');
    }
}
    
}
?>
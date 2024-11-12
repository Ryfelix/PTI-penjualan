<?php
function profil()
{
    $data['petugas'] = $this->m_user->selectPetugas()->row();

    if ($this->session->userdata('level') != 'Petugas') {
        redirect('login');
    } else {
        if ($this->input->method() == 'post') {
            $this->m_user->ubahPetugas();
            $this->session->set_flashdata('info', 'Data berhasil diubah');
            redirect('petugas/profil');
        } else {
            $this->load->view('petugas/header');
            $this->load->view('petugas/profil', $data);
            $this->load->view('petugas/footer');
        }
    }
}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('m_user');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index() {
        if ($this->session->userdata('level') == 'Admin') {
            redirect('admin', 'refresh');
        } elseif ($this->session->userdata('level') == 'Petugas') {
            redirect('petugas', 'refresh');
        } else {
            $this->load->view('login');
        }
    }

    public function login_act() {
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));
        $cekEmailUser = $this->m_user->getEmailUser($email);
        $cekPassUser = $this->m_user->getPassUser($password);

        if ($this->input->method() != 'post') {
            redirect('login');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('peringatan', 'Format email salah');
        } elseif ($cekEmailUser->num_rows() == NULL) {
            $this->session->set_flashdata('peringatan', 'Email tidak ditemukan');
        } elseif ($cekPassUser->num_rows() == NULL) {
            $this->session->set_flashdata('peringatan', 'Password Salah');
        } elseif ($cekEmailUser->num_rows() != NULL && $cekPassUser->num_rows() != NULL) {
            foreach ($cekEmailUser->result() as $data) {
                $data_user = [
                    'id' => $data->idUser,
                    'nama' => $data->nama,
                    'email' => $data->email,
                    'level' => $data->level
                ];
                $this->session->set_userdata($data_user);

                if ($data->level == "Petugas") {
                    redirect('petugas');
                } elseif ($data->level == "Admin") {
                    redirect('admin');
                }
            }
        } else {
            $this->session->set_flashdata('peringatan', 'Password Salah');
        }
        $this->load->view('login');
    }

    public function lupaPassword() {
        if ($this->session->userdata('level') == 'Admin') {
            redirect('admin');
        } elseif ($this->session->userdata('level') == 'Petugas') {
            redirect('petugas');
        } else {
            $this->load->view('lupaPassword');
        }
    }

    public function lupaPassword_act() {
        $email = $this->input->post('email');
        $cekEmailUser = $this->m_user->getEmailUser($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('peringatan', 'Format email salah');
        } elseif ($cekEmailUser->num_rows() == NULL) {
            $this->session->set_flashdata('peringatan', 'Email tidak ditemukan');
        } elseif ($cekEmailUser->num_rows() != NULL) {
            // Generate new password
            $pass = "129FAasdsk25kwBjakjDlff";
            $panjang = 8;
            $len = strlen($pass);
            $start = $len - $panjang;
            $xx = rand(0, $start);
            $yy = str_shuffle($pass);
            $passwordbaru = substr($yy, $xx, $panjang);

            // Configure email library
            $config = [
                'protocol' => 'smtp',
                'smtp_host' => 'smtp web',
                'smtp_port' => 'port SSL/TSL',
                'smtp_user' => '', // your email
                'smtp_pass' => '', // your password
                'mailtype' => 'html',
                'charset' => 'iso-8859-1'
            ];

            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $this->email->from('your_email', 'Description');
            $this->email->to($email);
            $this->email->subject('Lupa Password');
            $this->email->message("
                <html>
                <head></head>
                <body>
                    <br>
                    Kami telah mengatur ulang password Anda. Berikut password baru Anda:
                    <br><br>
                    <p>Password Baru: <b>$passwordbaru</b></p>
                    <p>Anda dapat login kembali dengan password baru Anda <a href='" . base_url() . "login' target='_blank' style='text-decoration:none;font-weight:bold;'>disini</a>.</p>
                </body>
                </html>"
            );

            if ($this->email->send()) {
                $data['password'] = md5($passwordbaru);
                $this->m_user->ubahpasswordUser($email, $data);
                $this->session->set_flashdata('peringatan', 'Email terkirim');
            } else {
                $this->session->set_flashdata('peringatan', 'Email gagal terkirim / Periksa koneksi Anda');
            }
            $this->load->view('lupaPassword');
        }
    }
    public function logout() {
        // Destroy the session data
        $this->session->sess_destroy();
    
        // Redirect to login page
        redirect('login');
    }
}
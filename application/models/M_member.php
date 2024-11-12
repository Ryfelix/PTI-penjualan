<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class m_member extends CI_Model{
    function getMember(){
            $this->db->select('*');
            $this->db->from('member');
            $query = $this->db->get();
            return $query;
        }
    }

    function upload_file($filename) {
        // Load library upload
        $this->load->library('upload');
        
        // Configuration for file upload
        $config['upload_path'] = './excel/';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = 2048; // Max file size in KB
        $config['overwrite'] = true;
        $config['file_name'] = $filename;
        
        // Initialize the upload library with the configuration
        $this->upload->initialize($config);
    
        // Perform file upload and check if the upload was successful
        if ($this->upload->do_upload('file')) {
            // If successful:
            $return = array(
                'result' => 'success',
                'file'   => $this->upload->data(),
                'error'  => ''
            );
            return $return;
        } else {
            // If failed:
            $return = array(
                'result' => 'failed',
                'file'   => '',
                'error'  => $this->upload->display_errors()
            );
            return $return;
        }
    }

    function tambah($data){

        $this->db->insert_batch('member', $data);
        
    }
    

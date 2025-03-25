<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'auth_helper']);
        
        // Periksa apakah user sudah login
        check_login();
    }

    public function index() {
        // Ambil role user dari session
        $data['role'] = $this->session->userdata('role');
        
        // Tampilkan halaman home
        $this->load->view('home/index', $data);
    }
}

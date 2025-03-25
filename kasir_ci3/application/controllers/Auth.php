<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->database();
    }

    // ✅ LOGIN
    public function login() {
        if ($this->session->userdata('logged_in')) {
            redirect('home');
        }

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Ambil data user dari database
            $user = $this->db->get_where('users', ['username' => $username])->row();

            // Validasi password
            if ($user && password_verify($password, $user->password)) {
                $this->session->set_userdata([
                    'user_id'   => $user->id,
                    'username'  => $user->username,
                    'role'      => $user->role,
                    'logged_in' => true
                ]);

                redirect('home');
            } else {
                $this->session->set_flashdata('error', 'Username atau Password salah!');
                redirect('auth/login');
            }
        }

        $this->load->view('auth/login');
    }

    // ✅ REGISTER (Pendaftaran Admin & Operator)
    public function register() {
        if ($this->session->userdata('logged_in')) {
            redirect('home');
        }

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT); // 🔹 Lebih aman daripada md5()
            $role     = $this->input->post('role');

            // Cek apakah username sudah ada
            $existing_user = $this->db->get_where('users', ['username' => $username])->row();
            if ($existing_user) {
                $this->session->set_flashdata('error', 'Username sudah digunakan!');
                redirect('auth/register');
            }

            // Simpan ke database
            $data = [
                'username' => $username,
                'password' => $password,
                'role'     => $role
            ];
            $this->db->insert('users', $data);

            $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
            redirect('auth/login');
        }

        $this->load->view('auth/register');
    }

    // ✅ LOGOUT
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}

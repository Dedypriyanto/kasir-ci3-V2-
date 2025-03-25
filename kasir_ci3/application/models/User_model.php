<?php
class User_model extends CI_Model {

    // Cek user berdasarkan username & password
    public function get_user($username, $password) {
        return $this->db->get_where('users', ['username' => $username, 'password' => $password])->row();
    }

    // Simpan data user baru
    public function insert_user($data) {
        return $this->db->insert('users', $data);
    }
}
?>

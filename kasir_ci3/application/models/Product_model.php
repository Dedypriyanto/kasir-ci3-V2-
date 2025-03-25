<?php
class Product_model extends CI_Model {
    
    // Ambil semua produk
    public function get_all_products() {
        return $this->db->get('products')->result();
    }

    // Ambil produk berdasarkan kategori
    public function get_products_by_category($category) {
        return $this->db->get_where('products', ['category' => $category])->result();
    }
    
    // Tambah produk baru
    public function insert_product($data) {
        return $this->db->insert('products', $data);
    }

    // Ambil produk berdasarkan ID
    public function get_product_by_id($id) {
        return $this->db->get_where('products', ['id' => $id])->row();
    }
    
    // Update produk
    public function update_product($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    // Hapus produk dengan pengecekan transaksi terkait
    public function delete_product($id) {
        // Cek apakah produk masih digunakan dalam transaksi
        $this->db->where('product_id', $id);
        $query = $this->db->get('transaction_details');

        if ($query->num_rows() > 0) {
            return false; // Produk masih memiliki transaksi, tidak bisa dihapus
        }

        // Jika tidak ada transaksi terkait, hapus produk
        $this->db->where('id', $id);
        return $this->db->delete('products');
    }

    // Periksa apakah produk bisa dihapus
    public function can_delete_product($id) {
        $this->db->where('product_id', $id);
        $query = $this->db->get('transaction_details');
        return ($query->num_rows() == 0); // True jika tidak ada transaksi
    }
}
?>

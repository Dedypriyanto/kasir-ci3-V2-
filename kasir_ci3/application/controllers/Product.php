<?php
class Product extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->library('session');
    }
    
    // Menampilkan daftar produk
    public function index() {
        $data['makanan'] = $this->Product_model->get_products_by_category('makanan');
        $data['minuman'] = $this->Product_model->get_products_by_category('minuman');
        $this->load->view('products/index', $data);
    }

    // Menambahkan produk baru
    public function add() {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'category' => $this->input->post('category'), // Tambahkan kategori
                'price' => str_replace('.', '', $this->input->post('price')) // Hapus titik sebelum simpan
            ];
            $this->Product_model->insert_product($data);
            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
            redirect('product');
        }
        $this->load->view('products/add');
    }

    // Edit produk
    public function edit($id) {
        $data['product'] = $this->Product_model->get_product_by_id($id);
    
        if (!$data['product']) {
            show_404();
        }
    
        if ($this->input->post()) {
            $update_data = [
                'name' => $this->input->post('name'),
                'category' => $this->input->post('category'), // Tambahkan kategori
                'price' => str_replace('.', '', $this->input->post('price'))
            ];
            $this->Product_model->update_product($id, $update_data);
            $this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
            redirect('product');
        }
    
        $this->load->view('products/edit', $data);
    }

    // Hapus produk
    public function delete($id) {
        $product = $this->Product_model->get_product_by_id($id);
        
        if (!$product) {
            show_404();
        }

        if (!$this->Product_model->delete_product($id)) {
            // Produk masih digunakan dalam transaksi, tampilkan flash message
            $this->session->set_flashdata('error', 'Gagal menghapus! Produk masih digunakan dalam transaksi.');
        } else {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        }

        redirect('product');
    }
}
?>

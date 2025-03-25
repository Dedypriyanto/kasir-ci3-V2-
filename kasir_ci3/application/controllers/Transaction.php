<?php 

class Transaction extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model');
        $this->load->model('Product_model');
        $this->load->library('session');
    }

    // Menampilkan daftar transaksi
    public function index() {
        $month = $this->input->get('month');
        $year = $this->input->get('year');

        $data['products'] = $this->Product_model->get_all_products();
        $data['transactions'] = $this->Transaction_model->get_all_transactions($month, $year);
        $this->load->view('transactions/index', $data);
    }

    // Menambahkan transaksi baru
    public function add() {
        if ($this->input->post()) {
            $invoice_number = "INV-" . date("Ymd-His");
            $customer_name = $this->input->post('customer_name', true);
            $table_number = $this->input->post('table_number', true);
            $product_ids = $this->input->post('product_id');
            $quantities = $this->input->post('quantity');
            $notes = $this->input->post('note'); // Menambahkan input note

            if (empty($customer_name) || empty($table_number) || empty($product_ids) || empty($quantities)) {
                $this->session->set_flashdata('error', 'Harap isi semua kolom dan pilih minimal satu produk.');
                redirect('transaction');
            }

            if ($table_number < 1 || $table_number > 20) {
                $this->session->set_flashdata('error', 'Nomor meja harus antara 1-20.');
                redirect('transaction');
            }

            // Mulai transaksi database
            $this->db->trans_start();

            // Simpan transaksi utama
            $transaction_data = [
                'invoice_number' => $invoice_number,
                'customer_name' => $customer_name,
                'table_number' => $table_number,
                'total_price' => 0
            ];
            $transaction_id = $this->Transaction_model->insert_transaction($transaction_data);

            $total_price = 0;
            $valid_products = 0;

            // Simpan detail transaksi
            for ($i = 0; $i < count($product_ids); $i++) {
                $product_id = $product_ids[$i];
                $quantity = (int) $quantities[$i];
                $note = isset($notes[$i]) ? trim($notes[$i]) : ""; // Mengambil catatan jika ada

                if ($quantity > 0) {
                    $product = $this->Product_model->get_product_by_id($product_id);
                    if ($product) {
                        $subtotal = $product->price * $quantity;
                        $total_price += $subtotal;

                        $detail_data = [
                            'transaction_id' => $transaction_id,
                            'product_id' => $product_id,
                            'quantity' => $quantity,
                            'price' => $product->price,
                            'note' => $note, // Menyimpan note
                        ];
                        $this->Transaction_model->insert_transaction_detail($detail_data);
                        $valid_products++;
                    }
                }
            }

            // Jika tidak ada produk valid, batalkan transaksi
            if ($valid_products == 0) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Tidak ada produk valid yang ditambahkan.');
                redirect('transaction');
            }

            // Update total harga transaksi
            $this->Transaction_model->update_total_price($transaction_id, $total_price);

            // Selesaikan transaksi database
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Gagal menyimpan transaksi.');
                redirect('transaction');
            }

            // Redirect ke halaman cetak invoice
            redirect('transaction/print_invoice/' . $invoice_number);
        }
    }

    // Cetak invoice transaksi
    public function print_invoice($invoice_number) {
        $transaction = $this->Transaction_model->get_transaction_by_invoice($invoice_number);
        
        if (!$transaction) {
            show_404();
        }

        $data['transaction'] = $transaction;
        $data['details'] = $this->Transaction_model->get_transaction_details($transaction->id);
        $this->load->view('transactions/invoice', $data);
    }

    // Hapus transaksi
    public function delete($transaction_id) {
        if ($this->session->userdata('role') !== 'operator') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki izin untuk menghapus transaksi.');
            redirect('transaction');
        }
    
        if ($this->Transaction_model->delete_transaction($transaction_id)) {
            $this->session->set_flashdata('success', 'Transaksi berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus transaksi.');
        }
        redirect('transaction');
    }
    
    // Menampilkan laporan transaksi dengan filter bulan/tahun
    public function laporan() {
        $month = $this->input->get('month');
        $year = $this->input->get('year');

        $data['transactions'] = $this->Transaction_model->get_transaction_report($month, $year);
        $this->load->view('transactions/report', $data);
    }
}
?>

<?php
class Transaction_model extends CI_Model {
    
    // Insert transaksi utama dan mengembalikan ID transaksi baru
    public function insert_transaction($data) {
        $this->db->insert('transactions', $data);
        return $this->db->insert_id();
    }

    // Insert detail transaksi (dengan note)
    public function insert_transaction_detail($data) {
        return $this->db->insert('transaction_details', $data);
    }

    // Update total harga transaksi
    public function update_total_price($transaction_id, $total_price) {
        $this->db->where('id', $transaction_id);
        return $this->db->update('transactions', ['total_price' => $total_price]);
    }

    // Mengambil semua transaksi dengan informasi tambahan (nama pelanggan & nomor meja)
    public function get_all_transactions() {
        $this->db->select('
            transactions.id, 
            transactions.invoice_number, 
            transactions.customer_name,
            transactions.table_number,
            transactions.total_price, 
            transactions.created_at,
            COALESCE(GROUP_CONCAT(products.name SEPARATOR ", "), "Produk tidak tersedia") AS product_names,
            COALESCE(SUM(transaction_details.quantity), 0) AS quantity
        ');
        $this->db->from('transactions');
        $this->db->join('transaction_details', 'transactions.id = transaction_details.transaction_id', 'left');
        $this->db->join('products', 'transaction_details.product_id = products.id', 'left');
        $this->db->group_by('transactions.id');
        $this->db->order_by('transactions.created_at', 'DESC');

        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : [];
    }

    // Mengambil transaksi berdasarkan nomor invoice
    public function get_transaction_by_invoice($invoice_number) {
        $this->db->select('transactions.*');
        $this->db->from('transactions');
        $this->db->where('transactions.invoice_number', $invoice_number);
        $query = $this->db->get();
        
        return ($query->num_rows() > 0) ? $query->row() : null;
    }

    // Mengambil detail transaksi berdasarkan transaction_id (termasuk note)
    public function get_transaction_details($transaction_id) {
        $this->db->select('
            transaction_details.*, 
            products.name AS product_name, 
            products.price, 
            transaction_details.note
        ');
        $this->db->from('transaction_details');
        $this->db->join('products', 'transaction_details.product_id = products.id', 'left');
        $this->db->where('transaction_details.transaction_id', $transaction_id);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : [];
    }

    // Menghapus transaksi dan semua detailnya
    public function delete_transaction($transaction_id) {
        // Hapus detail transaksi terlebih dahulu
        $this->db->where('transaction_id', $transaction_id);
        $this->db->delete('transaction_details');

        // Hapus transaksi utama
        $this->db->where('id', $transaction_id);
        return $this->db->delete('transactions');
    }

    // Mengambil laporan transaksi dengan filter berdasarkan bulan & tahun (tambahan note)
    public function get_transaction_report($month = null, $year = null) {
        $this->db->select('
            transactions.invoice_number, 
            transactions.customer_name,
            transactions.table_number,
            transactions.created_at, 
            COALESCE(SUM(transaction_details.quantity), 0) AS quantity, 
            COALESCE(SUM(transaction_details.quantity * products.price), 0) AS total_price, 
            COALESCE(GROUP_CONCAT(products.name SEPARATOR ", "), "Produk tidak tersedia") AS product_names,
            COALESCE(GROUP_CONCAT(transaction_details.note SEPARATOR "; "), "") AS notes
        ');
        $this->db->from('transactions');
        $this->db->join('transaction_details', 'transactions.id = transaction_details.transaction_id', 'left');
        $this->db->join('products', 'transaction_details.product_id = products.id', 'left');

        // Filter berdasarkan bulan dan tahun jika tersedia
        if ($month && $year) {
            $this->db->where('MONTH(transactions.created_at)', $month);
            $this->db->where('YEAR(transactions.created_at)', $year);
        }

        $this->db->group_by('transactions.id');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : [];
    }
}
?>

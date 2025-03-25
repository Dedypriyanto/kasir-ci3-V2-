<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model');
    }

    public function index() {
        $data['transactions'] = $this->Transaction_model->get_all_transactions();
        $this->load->view('reports/index', $data);
    }
}

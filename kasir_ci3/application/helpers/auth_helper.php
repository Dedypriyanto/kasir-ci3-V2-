<?php
function check_login() {
    $ci = &get_instance();
    if (!$ci->session->userdata('logged_in')) {
        redirect('auth/login');
    }
}

function check_admin() {
    $ci = &get_instance();
    if ($ci->session->userdata('role') !== 'operator') {
        redirect('home');
    }
}

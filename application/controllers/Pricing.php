<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pricing extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('status') != "login") {
            redirect(base_url("login"));
        } else {
            $this->load->model('M_pricing');
        }
    }

    public function index()
    {
        $where = array(
            'jenis' => 'Harian'
        );

        $data['package_list_harian'] = $this->M_pricing->get_data('package', $where);

        $where = array(
            'jenis' => 'Mingguan'
        );

        $data['package_list_mingguan'] = $this->M_pricing->get_data('package', $where);

        $where = array(
            'jenis' => 'Bulanan'
        );

        $data['package_list_bulanan'] = $this->M_pricing->get_data('package', $where);


        $this->load->view('templates/t_header');
        $this->load->view('templates/t_footbar');
        $this->load->view('templates/t_sidebar', $data);
        $this->load->view('v_pricing');
        $this->load->view('templates/t_footer');
    }
}

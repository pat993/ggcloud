<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login" && $this->session->userdata('username') != "admin") {
         redirect(base_url("login"));
      } else {
         $this->load->model('M_comp_manager');
      }
   }

   public function index()
   {
      $data['user_id'] = $this->session->userdata('user_id');

      $this->load->view('templates/t_header');
      $this->load->view('templates/t_sidebar');
      $this->load->view('v_invoice', $data);
      $this->load->view('templates/t_footer');
   }
}

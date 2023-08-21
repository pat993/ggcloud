<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Satpam extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login") {
         redirect(base_url("login"));
      } else {
         $this->load->model('M_satpam');
      }
   }

   public function index()
   {
      $end_date = date('Y-m-d H:i:s');

      $this->M_satpam->razia($end_date);
   }
}

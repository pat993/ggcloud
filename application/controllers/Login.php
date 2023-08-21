<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login") {
         $this->load->model('M_login');
      } else {
         redirect(base_url("dashboard"));
      }
   }

   function index()
   {
      $this->load->view('templates/t_header');
      $this->load->view('v_login');
      $this->load->view('templates/t_footer');
   }

   function aksi_login()
   {
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      $where = array(
         'username' => $username,
         'status' => 'aktif'
      );
      $cek = $this->M_login->cek_login("user", $where);

      foreach ($cek as $cek_p) {
         $username = $cek_p['username'];
         $email = $cek_p['email'];
         $password_cek = $cek_p['password'];
         $user_id = $cek_p['id'];
      };

      //echo print_r($cek);
      if (password_verify($password, $password_cek)) {

         $data_session = array(
            'username' => $username,
            'email' => $email,
            'status' => "login",
            'user_id' => $user_id
         );

         $this->session->set_userdata($data_session);

         redirect(base_url("dashboard"));
      } else {
         $this->session->set_flashdata('error', "Username / Password tidak sesuai");

         redirect('login');
      }
   }
}

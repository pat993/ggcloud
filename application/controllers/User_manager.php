<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_manager extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login") {
         redirect(base_url("login"));
      } else {
         $this->load->model('M_user_manager');
      }
   }

   public function index()
   {
      $data['user'] = $this->M_user_manager->get_data('user');

      $data['user_count'] = $this->M_user_manager->get_count('user');

      $where = array(
         'status' => 'aktif'
      );

      $data['user_count_active'] = $this->M_user_manager->get_count_active('user', $where);


      $this->load->view('templates/t_header');
      $this->load->view('templates/t_sidebar', $data);
      $this->load->view('v_user_manager');
      $this->load->view('templates/t_footer');
   }

   public function delete_user($id)
   {
      $where = array(
         'id' => $id
      );

      $this->M_user_manager->delete_data('user', $where);

      $this->session->set_flashdata('success', "Berhasil Hapus Data");

      redirect($_SERVER['HTTP_REFERER']);
   }

   public function get_detail($id)
   {
      $where = array(
         'id' => $id
      );

      $data['user'] = $this->M_user_manager->get_ajax_data('user', $where);

      $this->load->view('ajax/a_user_detail.php', $data);
   }

   public function edit_user()
   {
      $id = $this->input->post('txt_id');
      $email = $this->input->post('txt_email');
      $no_hp = $this->input->post('txt_no_hp');
      $status = $this->input->post('txt_status');

      $where = array(
         'id' => $id
      );

      $data = array(
         'email' => $email,
         'no_hp' => $no_hp,
         'status' => $status,
      );

      $this->M_user_manager->update_data('user', $data, $where);
      $this->session->set_flashdata('success', "Berhasil Edit Data");

      redirect($_SERVER['HTTP_REFERER']);
   }
}

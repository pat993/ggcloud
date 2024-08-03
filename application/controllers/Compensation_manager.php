<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Compensation_manager extends CI_Controller
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

      $data['assigned'] = $this->M_comp_manager->get_data('assigned');


      $this->load->view('templates/t_header');
      $this->load->view('templates/t_sidebar');
      $this->load->view('v_compensation_manager', $data);
      $this->load->view('templates/t_footer');
   }

   public function get_data()
   {
      $data = $this->M_comp_manager->get_data('assigned');

      echo json_encode($data);
   }

   public function get_data_history($id)
   {
      $where = array(
         'assign_id' => $id
      );

      $data = $this->M_comp_manager->get_data_history('kompensasi', $where);

      echo json_encode($data);
   }


   public function add_compensation()
   {
      $durasi = $this->input->post('txt_durasi');
      $keterangan = $this->input->post('txt_keterangan');
      $assign_id = $this->input->post('txt_assign_id');

      $data = array(
         'durasi' => $durasi,
         'keterangan' => $keterangan,
         'assign_id' => $assign_id
      );

      $this->M_comp_manager->insert_data('kompensasi', $data);
      // $this->update_config($ip, $port);

      $this->session->set_flashdata('success', "Berhasil Input Data");

      redirect($_SERVER['HTTP_REFERER']);
   }

   public function delete_data($id)
   {
      $where = array(
         'id' => $id
      );

      $this->M_comp_manager->delete_data('kompensasi', $where);
   }
}

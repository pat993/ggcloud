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
         $this->load->model('M_dashboard');
      }
   }

   public function index()
   {
      $data['user_id'] = $this->session->userdata('user_id');

      $data['assigned'] = $this->M_comp_manager->get_assigned('assigned');

      $data['kompensasi'] = $this->M_comp_manager->get_data('kompensasi');

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

      $where = array(
         'id' => $assign_id
      );

      $end_date = $this->M_comp_manager->get_data('assigned', $where);

      foreach ($end_date as $end_date_r) {
         $end_date_p = $end_date_r['end_date_kompensasi'];
      }

      $end_date_kompensasi = $this->M_dashboard->date_calc_jam($end_date_p, $durasi);

      $data = array(
         'end_date_kompensasi' => $end_date_kompensasi
      );

      $where = array(
         'id' => $assign_id
      );

      $this->M_comp_manager->update_data('assigned', $where, $data);

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

   public function add_batch_compensation()
   {
      $durasi = $this->input->post('txt_durasi_b');
      $keterangan = $this->input->post('txt_keterangan_b');

      $where = array(
         'status' => 'active'
      );

      $assign_list = $this->M_comp_manager->get_assigned_id('assigned', $where);

      foreach ($assign_list as $assign_list_r) {
         $where = array(
            'id' => $assign_list_r['id']
         );
   
         $end_date = $this->M_comp_manager->get_data('assigned', $where);
   
         foreach ($end_date as $end_date_r) {
            $end_date_p = $end_date_r['end_date_kompensasi'];
         }
   
         $end_date_kompensasi = $this->M_dashboard->date_calc_jam($end_date_p, $durasi);
   
         $data = array(
            'end_date_kompensasi' => $end_date_kompensasi
         );
   
         $where = array(
            'id' => $assign_list_r['id']
         );
   
         $this->M_comp_manager->update_data('assigned', $where, $data);

         $data = array(
            'end_date_kompensasi' => $end_date_kompensasi
         );

         $where = array(
            'id' => $assign_list_r['id']
         );

         $this->M_comp_manager->update_data('assigned', $where, $data);

         $data = array(
            'durasi' => $durasi,
            'keterangan' => $keterangan,
            'assign_id' => $assign_list_r['id']
         );

         $this->M_comp_manager->insert_data('kompensasi', $data);
      }

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

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_history extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login") {
         redirect(base_url("login"));
      } else {
         $this->load->model('M_purchase');
      }
   }

   public function index()
   {
      $user_id = $this->session->userdata('user_id');

      $data['user_id'] = $user_id;
      
      $data['tanggal'] = '';
      $data['bulan'] = '';
      $data['tahun'] = '';

      //ambil variabel user id dari session
      $user_id = $this->session->userdata('user_id');

      $where = array(
         'user_id' => $user_id
      );

      $data['keterangan'] = 'Menampilkan hasil 10 transaksi terakhir';

      $data['purchase'] = $this->M_purchase->get_data('purchase', $where);

      $this->load->view('templates/t_header');
      $this->load->view('templates/t_footbar');
      $this->load->view('templates/t_sidebar', $data);
      $this->load->view('v_purchase_history');
      $this->load->view('templates/t_footer');
   }

   public function filter()
   {
      $tanggal = $this->input->post('txt_tanggal');
      $bulan = $this->input->post('txt_bulan');
      $tahun = $this->input->post('txt_tahun');

      $data['tanggal'] = '';
      $data['bulan'] = '';
      $data['tahun'] = '';

      $where1 = '';
      $where2 = '';
      $where3 = '';

      if ($tanggal != NUll) {
         $where1 = array(
            'day(purchase_date)' => $tanggal
         );

         $data['tanggal'] = $tanggal;
      }

      if ($bulan != NUll) {
         $where2 = array(
            'month(purchase_date)' => $bulan
         );

         $data['bulan'] = $bulan;
      }

      if ($tahun != NUll) {
         $where3 = array(
            'year(purchase_date)' => $tahun
         );

         $data['tahun'] = $tahun;
      }

      $data['keterangan'] = '<i class="fas fa-filter"></i> Menampilkan hasil filter';

      $data['purchase'] = $this->M_purchase->get_data_filter('purchase', $where1, $where2, $where3);

      $this->load->view('templates/t_header');
      $this->load->view('templates/t_sidebar', $data);
      $this->load->view('v_purchase_history');
      $this->load->view('templates/t_footer');
   }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

use phpseclib\Net\SSH2;

class Dashboard extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login") {
         redirect(base_url("login"));
      } else {
         $this->load->model('M_dashboard');
      }
   }

   public function index()
   {
      $user_id = $this->session->userdata('user_id');

      $where = array(
         'user_id' => $user_id,
         'status' => 'active'
      );

      $data['user_id'] = $user_id;
      $data['device_list'] = $this->M_dashboard->get_data('assigned', $where);
      $this->load->view('templates/t_header');
      $this->load->view('templates/t_footbar');
      $this->load->view('templates/t_sidebar', $data);
      $this->load->view('v_dashboard');
      $this->load->view('templates/t_footer');
   }

   public function rename_device()
   {
      $device_name = $this->input->post('txt_rename_device');
      $assign_id = $this->input->post('txt_assign_id');
      $user_id = $this->session->userdata('user_id');

      if (preg_match("/^[a-zA-Z0-9_\-]+$/", $device_name)) {
         $data = array(
            'custom_name' => $device_name
         );

         $where = array(
            'id' => $assign_id,
            'user_id' => $user_id
         );

         $this->M_dashboard->update_data('assigned', $where, $data);

         redirect($_SERVER['HTTP_REFERER']);
      } else {
         redirect($_SERVER['HTTP_REFERER']);
      }
   }

   public function voucher_claim()
   {
      $err_count = $this->session->userdata('err_count_v');

      if ($err_count == 1) {
         //recaptca
         $response = $this->input->post('g-recaptcha-response');
         $error = "";
         $secret = "6Lf72FUpAAAAAIdtF5CjQ353d9-Y2dYAcyYZVHg6";
         $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response";
         $verify = json_decode(file_get_contents($url));
         $auth = $verify->success;
      } else {
         $auth = 1;
      }

      if ($auth == 1) {

         $voucher_code = $this->input->post('txt_voucher_code');

         $where = array(
            'kode_voucher' => $voucher_code,
            'jenis_voucher' => 'Baru',
         );

         $data_voucher = $this->M_dashboard->cek_voucher('voucher', $where);

         if (count($data_voucher) > 0) {
            foreach ($data_voucher as $data_voucher_r) {
               $voucher_id = $data_voucher_r['voucher_id'];
               $jenis_paket = $data_voucher_r['jenis_paket'];
               $paket_id = $data_voucher_r['paket_id'];
               $durasi = $data_voucher_r['durasi'];
               $harga = $data_voucher_r['harga'];
               $jenis_transaksi = $data_voucher_r['jenis_ecommerce'];
            }

            $enddate_calc = $this->M_dashboard->date_calc($durasi);
            $available_device = $this->M_dashboard->cek_available('device');

            if (count($available_device) > 0) {
               $user_id = $this->session->userdata('user_id');
               $device_identifier = $this->M_dashboard->randomString(32);
               $access_token = $this->M_dashboard->randomString(32);

               if ($this->update_configuration($available_device, $access_token) ==  true) {
                  $data = array(
                     'user_id' => $user_id,
                     'jenis_id' => $jenis_paket,
                     'paket_id' => $paket_id,
                     'harga' => $harga,
                     'jenis_transaksi' => $jenis_transaksi,
                     'status' => 'Lunas'
                  );

                  //insert to invoice
                  $this->M_dashboard->insert_data('invoice', $data);

                  $data = array(
                     'user_id' => $user_id,
                     'device_id' => $available_device[0]['id'],
                     'device_identifier' => $device_identifier,
                     'access_token' => $access_token,
                     'end_date' => $enddate_calc,
                     'end_date_kompensasi' => $enddate_calc
                  );

                  $this->M_dashboard->insert_data('assigned', $data);

                  $data2 = array(
                     'package_id' => $paket_id,
                     'user_id' => $user_id,
                     'purchase_date' => date('Y-m-d H:i:s'),
                     'status' => 'Berhasil',
                     'jenis_pembayaran' => 'Shopee'
                  );

                  $this->M_dashboard->insert_data('purchase', $data2);

                  $assign_id = $this->M_dashboard->get_assign_id('assigned', array('device_identifier' => $device_identifier))[0]['id'];

                  $this->M_dashboard->update_data('voucher', array('id' => $voucher_id), array(
                     'voucher_status' => 'Digunakan',
                     'assign_id' => $assign_id,
                     'tanggal_digunakan' => date('Y-m-d h:i:s')
                  ));

                  $this->M_dashboard->update_data('device', array('id' => $available_device[0]['id']), array(
                     'status_id' => '3'
                  ));

                  $this->session->set_flashdata('success', "Success");

                  $data_session = array(
                     'err_count_v' => 0
                  );

                  $this->session->set_userdata($data_session);
               } else {
                  $this->session->set_flashdata('error', "Error 102: Tidak dapat terhubung ke server");
               }

               redirect($_SERVER['HTTP_REFERER']);
            } else {
               $this->session->set_flashdata('error', "Error 101: Mohon Hubungi Admin");

               redirect($_SERVER['HTTP_REFERER']);
            }
         } else {
            $this->session->set_flashdata('error', "Voucher tidak valid!");

            $data_session = array(
               'err_count_v' => 1
            );

            $this->session->set_userdata($data_session);

            redirect($_SERVER['HTTP_REFERER']);
         }
      } else {
         $this->session->set_flashdata('error', "Captcha error");

         redirect($_SERVER['HTTP_REFERER']);
      }
   }

   public function update_configuration($device_data, $token)
   {
      $server_ip = 'hypercube.my.id';
      $server_port = 22;
      $server_username = 'patra';
      $server_password = '@Nadhira250420';

      $ssh = new SSH2($server_ip, $server_port);
      if (!$ssh->login($server_username, $server_password)) {
         #exit('Login Failed');
         return false;
      } else {

         $current_config = $ssh->exec('cat /etc/haproxy/haproxy.cfg');

         foreach ($device_data as $device_data_r) {
            $port = $device_data_r['port'];
            $token_search = "0000_AVAILABLEDEVICE";
            $search_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token_search;
            $new_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token;

            // Update the token string
            $current_config = str_replace($search_string, $new_string, $current_config);
         }

         $temp_file = '/tmp/haproxy.cfg';
         // Write updated configuration to a temporary file
         $ssh->exec('echo -e "' . addslashes($current_config) . '" > ' . $temp_file);
         // Move the temporary file to the actual configuration file location
         $ssh->exec('sudo mv ' . $temp_file . ' /etc/haproxy/haproxy.cfg');
         // Reload HAProxy to apply the changes
         $ssh->exec('sudo systemctl reload haproxy');

         $ssh->disconnect();

         return true;
      }
   }

   public function voucher_extend()
   {
      $voucher_code = $this->input->post('txt_voucher_ext');
      $assign_id = $this->input->post('txt_assign_id');

      $where1 = array(
         'assigned.id' => $assign_id
      );

      $jenis_paket_ary = $this->M_dashboard->get_jenis_paket('assigned', $where1);

      foreach ($jenis_paket_ary as $jenis_paket_r) {
         $jenis_paket = $jenis_paket_r['jenis_paket'];
      }

      $where = array(
         'kode_voucher' => $voucher_code,
         'jenis_voucher' => 'Perpanjang',
         'jenis_paket' => $jenis_paket
      );

      $data_voucher = $this->M_dashboard->cek_voucher('voucher', $where);

      if (count($data_voucher) > 0) {
         foreach ($data_voucher as $data_voucher_r) {
            $voucher_id = $data_voucher_r['voucher_id'];
            $paket_id = $data_voucher_r['paket_id'];
            $durasi = $data_voucher_r['durasi'];
         }

         $user_id = $this->session->userdata('user_id');

         $where = array(
            'id' => $assign_id,
         );

         $existing_data = $this->M_dashboard->get_existing('assigned', $where);

         if (count($existing_data) > 0) {
            $end_date = $existing_data[0]['end_date'];
            $end_date_kompensasi = $existing_data[0]['end_date_kompensasi'];
            $enddate_calc = $this->M_dashboard->date_calc2($durasi, $end_date);
            $enddate_k_calc = $this->M_dashboard->date_calc2($durasi, $end_date_kompensasi);

            $data = array(
               'end_date' => $enddate_calc,
               'end_date_kompensasi' => $enddate_k_calc
            );

            $this->M_dashboard->update_data('assigned', $where, $data);

            $this->M_dashboard->insert_data('purchase', array(
               'package_id' => $paket_id,
               'user_id' => $user_id,
               'purchase_date' => date('Y-m-d H:i:s'),
               'status' => 'Berhasil',
               'Jenis_pembayaran' => 'Shopee'
            ));

            $this->M_dashboard->update_data('voucher', array('id' => $voucher_id), array(
               'voucher_status' => 'Digunakan',
               'assign_id' => $assign_id,
               'tanggal_digunakan' => date('Y-m-d h:i:s')
            ));

            $this->session->set_flashdata('success', "Perpanjang Perangkat Berhasil");

            redirect($_SERVER['HTTP_REFERER']);
         } else {
            $this->session->set_flashdata('error', "Error 101: Mohon Hubungi Admin");

            redirect($_SERVER['HTTP_REFERER']);
         }
      } else {
         $this->session->set_flashdata('error', "Kode tidak valid!");

         redirect($_SERVER['HTTP_REFERER']);
      }
   }

   public function get_detail($id)
   {
      $user_id = $this->session->userdata('user_id');

      $where = array(
         'user_id' => $user_id,
         'device_id' => $id
      );

      $data['device_info'] = $this->M_dashboard->get_data('assigned', $where);

      $this->load->view('ajax/a_device_info.php', $data);
   }
}

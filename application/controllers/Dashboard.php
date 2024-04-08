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

      if (preg_match("^[a-zA-Z0-9_\\-]+$^", $device_name)) {
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
      $voucher_code = $this->input->post('txt_voucher_code');

      $where = array(
         'kode_voucher' => $voucher_code,
         'jenis_voucher' => 'Baru',
      );

      //cek validitas voucher dan ambil data

      $data_voucher = $this->M_dashboard->cek_voucher('voucher', $where);

      if (count($data_voucher) > 0) {
         foreach ($data_voucher as $data_voucher_r) {
            $voucher_id = $data_voucher_r['voucher_id'];
            $paket_id = $data_voucher_r['paket_id'];
            $durasi = $data_voucher_r['durasi'];
         }

         //hitung durasi
         $enddate_calc = $this->M_dashboard->date_calc($durasi);

         //ambil device yang available
         $available_device = $this->M_dashboard->cek_available('device');

         foreach ($available_device as $available_device_r) {
            $device_id = $available_device_r['id'];
            // $device_ip =  $available_device_r['ip'];
            // $access_port = $available_device_r['port'];
            // $forward_port = $available_device_r['port_forward'];
         }

         if (count($available_device) > 0) {
            //ambil variabel user id dari session
            $user_id = $this->session->userdata('user_id');

            //generate device identifier
            $device_identifier = $this->M_dashboard->randomString(32);

            //generate device token
            $access_token = $this->M_dashboard->randomString(32);

            //proses assign
            $data = array(
               'user_id' => $user_id,
               'device_id' => $device_id,
               'device_identifier' => $device_identifier,
               'access_token' => $access_token,
               'end_date' => $enddate_calc
            );

            //update assign
            $this->M_dashboard->insert_data('assigned', $data);

            $data2 = array(
               'package_id' => $paket_id,
               'user_id' => $user_id,
               'purchase_date' => date('Y-m-d H:i:s'),
               'status' => 'Berhasil',
               'jenis_pembayaran' => 'Shopee'
            );

            //update purchase
            $this->M_dashboard->insert_data('purchase', $data2);


            //ambil assign_id

            $where = array(
               'device_identifier' => $device_identifier
            );

            $assign_id = $this->M_dashboard->get_assign_id('assigned', $where);

            foreach ($assign_id as $assign_id_r) {
               $assign_id_p = $assign_id_r['id'];
            }

            $where2 = array(
               'id' => $voucher_id
            );

            $data2 = array(
               'voucher_status' => 'Digunakan',
               'assign_id' => $assign_id_p,
               'tanggal_digunakan' => date('Y-m-d h:i:s')
            );

            $this->M_dashboard->update_data('voucher', $where2, $data2);

            $where3 = array(
               'id' => $device_id
            );

            $data3 = array(
               'status_id' => '3'
            );

            $this->update_configuration($available_device, $access_token);

            $this->M_dashboard->update_data('device', $where3, $data3);

            $this->session->set_flashdata('success', "Success");

            redirect($_SERVER['HTTP_REFERER']);
         } else {
            $this->session->set_flashdata('error', "Error 101: Mohon Hubungi Admin");

            redirect($_SERVER['HTTP_REFERER']);
         }
      } else {
         $this->session->set_flashdata('error', "Voucher tidak valid!");

         redirect($_SERVER['HTTP_REFERER']);
      }
   }

   public function update_configuration($device_data, $token)
   {
      // Server SSH connection details
      $server_ip = 'hypercube.my.id';
      $server_port = 22;
      $server_username = 'root';
      $server_password = '@Patraana007';

      // SSH connection
      $ssh = new SSH2($server_ip, $server_port);
      if (!$ssh->login($server_username, $server_password)) {
         exit('Login Failed');
      }

      // Read current configuration file
      $current_config = $ssh->exec('cat /etc/haproxy/haproxy.cfg');

      foreach ($device_data as $device_data_r) {

         $token_search = "0000_AVAILABLEDEVICE";
         $port = $device_data_r['port'];

         // Configuration to update
         $search_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token_search . '';
         $new_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token . '';

         // Update the token string
         $new_config = str_replace($search_string, $new_string, $current_config);

         // Write updated configuration back to the file
         $ssh->exec('echo "' . addslashes($new_config) . '" > /etc/haproxy/haproxy.cfg');
      }

      $ssh->exec('sudo systemctl reload haproxy');

      // Close SSH connection
      $ssh->disconnect();
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

      //cek validitas voucher dan ambil data

      $data_voucher = $this->M_dashboard->cek_voucher('voucher', $where);

      if (count($data_voucher) > 0) {
         foreach ($data_voucher as $data_voucher_r) {
            $voucher_id = $data_voucher_r['voucher_id'];
            $paket_id = $data_voucher_r['paket_id'];
            $durasi = $data_voucher_r['durasi'];
         }

         //ambil variabel user id dari session
         $user_id = $this->session->userdata('user_id');

         $where = array(
            'id' => $assign_id,
         );

         //ambil data existing
         $existing_data = $this->M_dashboard->get_existing('assigned', $where);

         if (count($existing_data) > 0) {

            foreach ($existing_data as $existing_data_r) {
               $end_date = $existing_data_r['end_date'];
            }

            //hitung durasi
            $enddate_calc = $this->M_dashboard->date_calc2($durasi, $end_date);

            //proses assign
            $data = array(
               'end_date' => $enddate_calc
            );

            $where = array(
               'id' => $assign_id
            );

            //update assign
            $this->M_dashboard->update_data('assigned', $where, $data);

            $data2 = array(
               'package_id' => $paket_id,
               'user_id' => $user_id,
               'purchase_date' => date('Y-m-d H:i:s'),
               'status' => 'Berhasil',
               'Jenis_pembayaran' => 'Shopee'
            );

            //update purchase
            $this->M_dashboard->insert_data('purchase', $data2);

            $where2 = array(
               'id' => $voucher_id
            );

            $data2 = array(
               'voucher_status' => 'Digunakan',
               'assign_id' => $assign_id,
               'tanggal_digunakan' => date('Y-m-d h:i:s')
            );

            //update status digunakan voucher
            $this->M_dashboard->update_data('voucher', $where2, $data2);

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

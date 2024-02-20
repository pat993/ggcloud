<?php
defined('BASEPATH') or exit('No direct script access allowed');

use phpseclib\Net\SSH2;

class Device_manager extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login") {
         redirect(base_url("login"));
      } else {
         $this->load->model('M_device_manager');
         $this->load->model('M_player');
      }
   }

   public function index()
   {
      $user_id = $this->session->userdata('user_id');

      $data['last_port'] = '';

      $data['user_id'] = $user_id;

      $data['device'] = $this->M_device_manager->get_data('device');

      $data['device_count'] = $this->M_device_manager->get_count('device');
      $data['device_used_count'] = $this->M_device_manager->get_used('device');

      $data['last_port'] = $this->M_device_manager->get_last_port('device');

      $this->load->view('templates/t_header');
      $this->load->view('templates/t_sidebar', $data);
      $this->load->view('v_device_manager');
      $this->load->view('templates/t_footer');
   }

   public function add_device()
   {
      $ip = $this->input->post('txt_ip');
      $ip_local = $this->input->post('txt_ip_local');
      $port = $this->input->post('txt_port');
      $name = $this->input->post('txt_name');
      $type = $this->input->post('txt_type');

      $data = array(
         'ip' => $ip,
         'ip_local' => $ip_local,
         'port' => $port,
         'name' => $name,
         'type' => $type
      );

      $this->M_device_manager->insert_data('device', $data);
      $this->update_config($ip, $port);

      $this->session->set_flashdata('success', "Berhasil Input Data");

      redirect($_SERVER['HTTP_REFERER']);
   }

   public function update_config($ip, $port)
   {
      $ssh = new SSH2('103.82.93.205');
      if (!$ssh->login('patra', '@Patraana007')) {
         exit('Login Failed');
      } else {
         // echo 'allowed ip: ' . $allow_ip;
         // echo 'allowed port: ' . $allow_port;

         $ssh->exec("sudo sh -c 'echo \" \" >> /etc/stunnel/stunnel.conf'");
         $ssh->exec("sudo sh -c 'echo \"[$ip|$port] \" >> /etc/stunnel/stunnel.conf'");
         $ssh->exec("sudo sh -c 'echo \"accept = $port \" >> /etc/stunnel/stunnel.conf'");
         $ssh->exec("sudo sh -c 'echo \"connect = $ip:$port \" >> /etc/stunnel/stunnel.conf'");

         $ssh->exec("sudo systemctl restart stunnel");
      }
   }

   public function edit_device()
   {
      $id = $this->input->post('txt_id');
      $ip = $this->input->post('txt_ip');
      $ip_local = $this->input->post('txt_ip_local');
      $port = $this->input->post('txt_port');
      $name = $this->input->post('txt_name');
      $type = $this->input->post('txt_type');

      $where = array(
         'id' => $id
      );

      $data = array(
         'ip' => $ip,
         'ip_local' => $ip_local,
         'port' => $port,
         'name' => $name,
         'type' => $type
      );

      $this->M_device_manager->update_data('device', $data, $where);
      $this->session->set_flashdata('success', "Berhasil Input Data");

      redirect($_SERVER['HTTP_REFERER']);
   }

   public function delete_device($id)
   {
      $where = array(
         'id' => $id
      );

      $this->M_device_manager->delete_data('device', $where);

      $this->session->set_flashdata('success', "Berhasil Hapus Data");

      redirect($_SERVER['HTTP_REFERER']);
   }

   public function get_detail($id)
   {
      $where = array(
         'device.id' => $id
      );

      $data['device'] = $this->M_device_manager->get_ajax_data('device', $where);

      $this->load->view('ajax/a_device_detail.php', $data);
   }

   public function get_detail_edit($id)
   {
      $where = array(
         'device.id' => $id
      );

      $data['device'] = $this->M_device_manager->get_ajax_data('device', $where);

      $this->load->view('ajax/a_device_edit.php', $data);
   }

   public function configure($id)
   {
      $user_id = $this->session->userdata('user_id');
      $allow_ip = $this->M_player->get_client_ip();

      $where = array(
         'id' => $id
      );

      $dev_data = $this->M_device_manager->get_configure_data('device', $where);

      foreach ($dev_data as $dev_data_r) {
         $data['dev_id'] = $dev_data_r['id'];
         $data['ip'] = 'www.hypercube.my.id';
         $data['port'] = $dev_data_r['port'];

         $dev_port = $dev_data_r['port'];
      }

      $where = array(
         'ip' => $allow_ip,
         'port' => $data['port'],
         'user_id' => $user_id
      );

      $firewall_exist = $this->M_player->check_existing('firewall', $where);

      if (count($firewall_exist) == 0) {
         $ssh = new SSH2('103.82.93.205');
         if (!$ssh->login('patra', '@Patraana007')) {
            exit('Login Failed');
         } else {
            // echo 'allowed ip: ' . $allow_ip;
            // echo 'allowed port: ' . $allow_port;
            $ssh->exec("sudo ufw allow from " . $allow_ip . " to any port " . $dev_port . "");

            $data = array(
               'user_id' => $user_id,
               'assign_id' => 'NULL',
               'ip' => $allow_ip,
               'port' => $dev_port
            );

            $this->M_player->add_firewall('firewall', $data);

            //exit('Success');
         }
      }

      $this->load->view('v_player2', $data);
      // $this->load->view('templates/t_configure', $data);
   }

   public function done_configure($id)
   {
      $where = array(
         'id' => $id
      );

      $data = array(
         'status_id' => 2
      );

      $this->M_device_manager->update_data('device', $data, $where);

      echo 'Berhasil Konfigurasi';
   }
}

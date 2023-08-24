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
      }
   }

   public function index()
   {
      $user_id = $this->session->userdata('user_id');

      $data['user_id'] = $user_id;

      $data['device'] = $this->M_device_manager->get_data('device');

      $data['device_count'] = $this->M_device_manager->get_count('device');
      $data['device_used_count'] = $this->M_device_manager->get_used('device');

      $this->load->view('templates/t_header');
      $this->load->view('templates/t_sidebar', $data);
      $this->load->view('v_device_manager');
      $this->load->view('templates/t_footer');
   }

   public function add_device()
   {
      $ip = $this->input->post('txt_ip');
      $port = $this->input->post('txt_port');
      $name = $this->input->post('txt_name');
      $type = $this->input->post('txt_type');

      $data = array(
         'ip' => $ip,
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
         $ssh->exec("sudo sh -c 'echo \"[" . $ip . "]\" >> /etc/stunnel/stunnel.conf'");
         $ssh->exec("sudo sh -c 'echo \"accept = " . $port . "\" >> /etc/stunnel/stunnel.conf'");
         $ssh->exec("sudo sh -c 'echo \"connect = " . $ip . ":8886\" >> /etc/stunnel/stunnel.conf'");

         $ssh->exec("sudo systemctl restart stunnel");
      }
   }

   public function edit_device()
   {
      $id = $this->input->post('txt_id');
      $ip = $this->input->post('txt_ip');
      $port = $this->input->post('txt_port');
      $name = $this->input->post('txt_name');
      $type = $this->input->post('txt_type');

      $where = array(
         'id' => $id
      );

      $data = array(
         'ip' => $ip,
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
}

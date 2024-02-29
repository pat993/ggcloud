<?php

use function PHPUnit\Framework\isEmpty;
use phpseclib\Net\SSH2;

defined('BASEPATH') or exit('No direct script access allowed');

class Satpam extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('username') == "admin") {
         $this->load->model('M_satpam');
      } else {
         redirect(base_url("dashboard"));
      }
   }

   public function index()
   {
      $device_data = $this->M_satpam->get_data('assigned');

      echo json_encode($device_data);

      $this->M_satpam->razia1();
      $this->M_satpam->razia2();

      if (count($device_data) > 0) {
         $this->edit_configuration($device_data);
      }


      // $firewall_list = $this->M_satpam->get_firewall_list('firewall');

      // echo json_encode($firewall_list);

      // if (count($firewall_list) != 0) {
      //    $ssh = new SSH2('103.82.93.205');
      //    if (!$ssh->login('root', '@Patraana007')) {
      //       exit('Login Failed');
      //    } else {
      //       foreach ($firewall_list as $firewall_list_r) {
      //          // $ssh->exec("sudo ufw deny from " . $firewall_list_r['ip'] . " to any port " . $firewall_list_r['port'] . "");
      //          $ssh->exec("sudo ufw show added | grep '" . $firewall_list_r['ip'] . " to any port " . $firewall_list_r['port'] . "' | awk '{ gsub(\"ufw\",\"ufw delete\",$0); system($0)}'");
      //          $ssh->exec("sudo ufw reload");

      //          $this->M_satpam->update_firewall($firewall_list_r['assign_id']);

      //          // echo "sudo ufw show added | grep '" . $firewall_list_r['ip'] . " to any port " . $firewall_list_r['port'] . "' | awk '{ gsub(\"ufw\",\"ufw delete\",$0); system($0)}'";
      //       }
      //    }
      // }
   }

   public function edit_configuration($device_data)
   {
      // Server SSH connection details
      $server_ip = '103.82.93.205';
      $server_port = 22;
      $server_username = 'root';
      $server_password = '@Patraana007';

      $token_master = 'VACANT_DEVICE';

      // SSH connection
      $ssh = new SSH2($server_ip, $server_port);
      if (!$ssh->login($server_username, $server_password)) {
         exit('Login Failed');
      }

      // Read current configuration file
      $current_config = $ssh->exec('cat /etc/haproxy/haproxy.cfg');

      foreach ($device_data as $device_data_r) {

         $token = $device_data_r['access_token'];
         $port = $device_data_r['port'];

         // Configuration to update
         $search_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token . '';
         $new_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token_master . '';

         // Update the token string
         $new_config = str_replace($search_string, $new_string, $current_config);

         // Write updated configuration back to the file
         $ssh->exec('echo "' . addslashes($new_config) . '" > /etc/haproxy/haproxy.cfg');
      }

      $ssh->exec('sudo systemctl reload haproxy');

      // Close SSH connection
      $ssh->disconnect();
   }
}

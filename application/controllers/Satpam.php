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

      // echo json_encode($device_data);

      $this->M_satpam->razia1();
      $this->M_satpam->razia2();

      if (count($device_data) > 0) {
         $this->edit_configuration($device_data);
      }
   }

   public function edit_configuration($device_data)
   {
      // Server SSH connection details
      $server_ip = 'hypercube.my.id';
      $server_port = 22;
      $server_username = 'patra';
      $server_password = '@Nadhira250420';

      $token_master = '0000_AVAILABLEDEVICE';

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
      }

      echo 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token . '';
      echo '-> acl valid_token_' . $port . ' urlp(token) -m str ' . $token_master . '\n';

      // Configuration to update
      $search_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token . '';
      $new_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token_master . '';

      // Update the token string
      $new_config = str_replace($search_string, $new_string, $current_config);

      // Write updated configuration back to the file
      $ssh->exec('echo "' . addslashes($new_config) . '" > /etc/haproxy/haproxy.cfg');

      $ssh->exec('sudo systemctl reload haproxy');

      // Close SSH connection
      $ssh->disconnect();
   }
}

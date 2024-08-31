<?php
defined('BASEPATH') or exit('No direct script access allowed');

use phpseclib\Net\SSH2;

class Device_manager extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login" && $this->session->userdata('username') != "admin") {
         redirect(base_url("login"));
      } else {
         $this->load->model('M_device_manager');
         $this->load->model('M_player');
         $this->load->model('M_satpam');
      }
   }

   public function index()
   {
      $this->razia();
      $user_id = $this->session->userdata('user_id');

      $data['last_port'] = '';
      $data['last_port_f'] = '';

      $data['user_id'] = $user_id;

      $data['device'] = $this->M_device_manager->get_data('device');

      $data['device_count'] = $this->M_device_manager->get_count('device');
      $data['device_used_count'] = $this->M_device_manager->get_used('device');

      $last_port = $this->M_device_manager->get_last_port('device');

      foreach ($last_port as $last_port_r) {
         $data['last_port'] = $last_port_r['port'] + 1;
         $data['last_port_f'] = $last_port_r['port_forward'] + 1;
      }

      $this->load->view('templates/t_header');
      $this->load->view('templates/t_sidebar', $data);
      $this->load->view('v_device_manager');
      $this->load->view('templates/t_footer');
   }

   public function add_device()
   {
      $ip = $this->input->post('txt_ip_remote');
      $ip_local = $this->input->post('txt_ip_local');
      $port = $this->input->post('txt_port');
      $port_f = $this->input->post('txt_port_f');
      $name = $this->input->post('txt_name');
      $type = $this->input->post('txt_type');

      $data = array(
         'ip' => $ip,
         'ip_local' => $ip_local,
         'port' => $port,
         'port_forward' => $port_f,
         'name' => $name,
         'type' => $type
      );

      $token = "0000_AVAILABLEDEVICE";
      if ($this->add_config($ip, $port, $port_f, $token) == true) {
         $this->M_device_manager->insert_data('device', $data);
         // $this->update_config($ip, $port);

         $this->session->set_flashdata('success', "Berhasil Input Data");
      } else {
         $this->session->set_flashdata('error', "Tidak dapat terhubung ke server");
      }

      redirect($_SERVER['HTTP_REFERER']);
   }

   public function add_config($ip, $access_port, $forward_port, $access_token)
   {
      $server_ip = 'hypercube.my.id';
      $server_port = 22;
      $server_username = 'patra';
      $server_password = '@Nadhira250420';

      $admin_token = 'ADMINTOKENAUTHENTICATION_190119_GGCLOUD';

      // Configuration to add
      $config_to_add = <<<CONFIG
      frontend ws_frontend_$access_port
         bind *:$access_port ssl crt /etc/letsencrypt/live/hypercube.my.id/combination.pem
         acl valid_token_$access_port urlp(token) -m str $access_token
         acl valid_token_$access_port-2 urlp(token) -m str $admin_token
         http-request deny if !valid_token_$access_port !valid_token_$access_port-2
         use_backend ws_server_$access_port

      backend ws_server_$access_port
         server ws_$access_port $ip:$forward_port
         
      CONFIG;

      // SSH connection
      $ssh = new SSH2($server_ip, $server_port);
      if (!$ssh->login($server_username, $server_password)) {
         #exit('Login Failed');
         return false;
      } else
         // Append the configuration block to the haproxy.cfg file
         $ssh->exec('echo "' . addslashes($config_to_add) . '" | sudo tee -a /etc/haproxy/haproxy.cfg');

      $ssh->exec('sudo systemctl reload haproxy');

      // Close SSH connection
      $ssh->disconnect();

      return true;
   }


   public function edit_device()
   {
      $id = $this->input->post('txt_id');
      $ip = $this->input->post('txt_ip');
      $ip_local = $this->input->post('txt_ip_local');
      $port = $this->input->post('txt_port');
      $port_f = $this->input->post('txt_port_f');
      $name = $this->input->post('txt_name');
      $type = $this->input->post('txt_type');

      $where = array(
         'id' => $id
      );

      $data = array(
         'ip' => $ip,
         'ip_local' => $ip_local,
         'port' => $port,
         'port_forward' => $port_f,
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

   public function configure($id = Null)
   {
      $where = array(
         'id' => $id
      );

      $device = $this->M_device_manager->get_data_where('device', $where);

      // echo json_encode($port);

      if (!empty($device)) {

         foreach ($device as $device_r) {
            $dev_port = $device_r['port'];
            $audio_port = $device_r['port_audio'];
            $access_token = 'ADMINTOKENAUTHENTICATION_190119_GGCLOUD';
            // $dev_name = $device_r['custom_name'];
         }

         $data['dev_id'] = $id;
         $data['audio_port'] = $audio_port;

         $d_ip = 'hypercube.my.id';
         $d_port = $dev_port;
         $d_name = 'ADMIN CONTROL';
         $d_token = $access_token;
         $d_end_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 day'));

         // Delay execution for 500 milliseconds
         usleep(500000);

         $this->set_cookie($d_ip, $d_port, $d_name, $d_token, $d_end_date);

         $this->load->view('v_player_configure', $data);
      } else {
         $this->unset_cookie();

         redirect('dashboard');
      }
   }

   public function set_cookie($ip, $port, $nama, $token, $end_date)
   {
      // Set the cookie parameters for username
      $ip_cookie = array(
         'name'   => 'bumi',
         'value'  => $ip,
         'expire' => time() + 1, // Cookie expiration time (1 hour from now)
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($ip_cookie);

      // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
      $port_cookie = array(
         'name'   => 'langit',
         'value'  => $port,
         'expire' => time() + 1,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($port_cookie);

      // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
      $nama_cookie = array(
         'name'   => 'bintang',
         'value'  => $nama,
         'expire' => time() + 1,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($nama_cookie);

      // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
      $token_cookie = array(
         'name'   => 'matahari',
         'value'  => $token,
         'expire' => time() + 1,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($token_cookie);

      // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
      $token_cookie = array(
         'name'   => 'bulan',
         'value'  => $end_date,
         'expire' => time() + 1,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($token_cookie);
   }

   public function unset_cookie()
   {
      // Set the cookie parameters for username
      $ip_cookie = array(
         'name'   => 'bumi',
         'value'  => '',
         'expire' => time() + 1, // Cookie expiration time (1 hour from now)
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($ip_cookie);

      // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
      $port_cookie = array(
         'name'   => 'langit',
         'value'  => '',
         'expire' => time() + 1,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($port_cookie);

      // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
      $nama_cookie = array(
         'name'   => 'bintang',
         'value'  => '',
         'expire' => time() + 1,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($nama_cookie);

      // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
      $token_cookie = array(
         'name'   => 'matahari',
         'value'  => '',
         'expire' => time() + 1,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($token_cookie);

      // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
      $token_cookie = array(
         'name'   => 'bulan',
         'value'  => '',
         'expire' => time() + 1,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($token_cookie);
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

   public function razia()
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

         $search_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token;
         $new_string = 'acl valid_token_' . $port . ' urlp(token) -m str ' . $token_master;

         // Update the token string
         $current_config = str_replace($search_string, $new_string, $current_config);
      }

      // Write updated configuration back to the file
      $temp_file = '/tmp/haproxy.cfg';
      $ssh->exec('echo "' . addslashes($current_config) . '" > ' . $temp_file);
      $ssh->exec('sudo mv ' . $temp_file . ' /etc/haproxy/haproxy.cfg');

      // Reload HAProxy to apply changes
      $ssh->exec('sudo systemctl reload haproxy');

      // Close SSH connection
      $ssh->disconnect();
   }
}

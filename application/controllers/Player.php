<?php
defined('BASEPATH') or exit('No direct script access allowed');

use phpseclib\Net\SSH2;


class Player extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('status') != "login") {
            redirect(base_url("login"));
        } else {
            $this->load->model('M_player');
        }
    }

    public function go($id = Null)
    {
        $user_id = $this->session->userdata('user_id');
        $allow_ip = $this->M_player->get_client_ip();
        //$allow_port = $id;

        // echo $allow_ip;

        $where = array(
            'user_id' => $user_id,
            'device_identifier' => $id
        );

        $device = $this->M_player->get_device('assigned', $where);

        // echo json_encode($port);

        if (!empty($device)) {

            foreach ($device as $device_r) {
                $assign_id = $device_r['assign_id'];
                // $dev_ip = $device_r['ip'];
                $dev_port = $device_r['port'];
            }

            // echo $user_id;
            // echo $dev_ip;
            // echo $dev_port;
            // echo $allow_ip;

            $where = array(
                'ip' => $allow_ip,
                'port' => $dev_port,
                'assign_id' => $assign_id,
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
                        'assign_id' => $assign_id,
                        'ip' => $allow_ip,
                        'port' => $dev_port
                    );

                    $this->M_player->add_firewall('firewall', $data);

                    //exit('Success');
                }
            } else {
                // echo "firewall exist";
            }
            $data2['user_id'] = $user_id;
            $data2['ip'] = 'www.hypercube.my.id';
            $data2['port'] = $dev_port;

            $this->load->view('v_player', $data2);
        } else {
            redirect('dashboard');
        }
    }

    function get_client_ip()
    {
        // API endpoint
        $apiUrl = 'https://api.ipify.org';

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session and store the result in $response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $data = json_decode($response, true);

        // Output the IP address
        if ($data && isset($data['ip'])) {
            echo 'Your IP address is: ' . $data['ip'];
        } else {
            echo 'Unable to retrieve IP address.';
        }
    }
}

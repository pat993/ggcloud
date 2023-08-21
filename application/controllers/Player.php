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

        $where = array(
            'device_identifier' => $id
        );

        $port = $this->M_player->get_port('assigned', $where);

        foreach ($port as $port_r) {
            $dev_ip = $port_r['ip'];
            $dev_port = $port_r['port'];
        }

        // echo $user_id;
        // echo $dev_ip;
        // echo $dev_port;
        // echo $allow_ip;

        $where = array(
            'ip' => $dev_ip,
            'port' => $dev_port,
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
                    'ip' => $dev_ip,
                    'port' => $dev_port
                );

                $this->M_player->add_firewall('firewall', $data);

                exit('Success');
            }
        } else {
            echo "firewall exist";
        }

        $this->load->view('v_player');
    }
}

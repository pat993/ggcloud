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
                $access_token = $device_r['access_token'];
                $dev_name = $device_r['custom_name'];
                $data['end_date'] = $device_r['end_date'];
            }

            // echo $user_id;
            // echo $dev_ip;
            // echo $dev_port;
            // echo $allow_ip;

            // $where = array(
            //     'ip' => $allow_ip,
            //     'port' => $dev_port,
            //     'assign_id' => $assign_id,
            //     'user_id' => $user_id
            // );

            // $firewall_exist = $this->M_player->check_existing('firewall', $where);

            // if (count($firewall_exist) == 0) {
            //     $ssh = new SSH2('103.189.234.196');
            //     if (!$ssh->login('patra', '@Patraana007')) {
            //         exit('Login Failed');
            //     } else {
            //         // echo 'allowed ip: ' . $allow_ip;
            //         // echo 'allowed port: ' . $allow_port;
            //         $ssh->exec("sudo ufw allow from " . $allow_ip . " to any port " . $dev_port . "");

            //         $data = array(
            //             'user_id' => $user_id,
            //             'assign_id' => $assign_id,
            //             'ip' => $allow_ip,
            //             'port' => $dev_port
            //         );

            //         $this->M_player->add_firewall('firewall', $data);

            //         //exit('Success');
            //     }
            // } else {
            //     // echo "firewall exist";
            // }
            $data['user_id'] = $user_id;

            $d_ip = 'hypercube.my.id';
            $d_port = $dev_port;
            $d_name = $dev_name;
            $d_token = $access_token;

            $this->set_cookie($d_ip, $d_port, $d_name, $d_token);

            $this->load->view('v_player', $data);
        } else {
            redirect('dashboard');
        }
    }

    function test()
    {
        $this->load->view('v_ping');
    }

    function get_client_ip()
    {
        $direct_ip = '';
        // Gets the default ip sent by the user
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $direct_ip = $_SERVER['REMOTE_ADDR'];
        }
        // Gets the proxy ip sent by the user
        $proxy_ip     = '';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            $proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
        } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
            $proxy_ip = $_SERVER['HTTP_FORWARDED'];
        } else if (!empty($_SERVER['HTTP_VIA'])) {
            $proxy_ip = $_SERVER['HTTP_VIA'];
        } else if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
            $proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
        } else if (!empty($_SERVER['HTTP_COMING_FROM'])) {
            $proxy_ip = $_SERVER['HTTP_COMING_FROM'];
        }
        // Returns the true IP if it has been found, else FALSE
        if (empty($proxy_ip)) {
            // True IP without proxy
            return $direct_ip;
        } else {
            $is_ip = preg_match('|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $proxy_ip, $regs);
            if ($is_ip && (count($regs) > 0)) {
                // True IP behind a proxy
                return $regs[0];
            } else {
                // Can't define IP: there is a proxy but we don't have
                // information about the true IP
                return $direct_ip;
            }
        }
    }

    public function set_cookie($ip, $port, $nama, $token)
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
    }
}

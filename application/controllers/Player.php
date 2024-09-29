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

    public function data()
    {
        $user_id = $this->session->userdata('user_id');
        // $allow_ip = $this->M_player->get_client_ip();
        //$allow_port = $id;

        // echo $allow_ip;

        $where = array(
            'user_id' => $user_id,
        );

        $device = $this->M_player->get_device('assigned', $where);

        echo json_encode($device);
    }

    public function go($id = Null)
    {
        $user_id = $this->session->userdata('user_id');
        // $allow_ip = $this->M_player->get_client_ip();
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
                $audio_port = $device_r['port_audio'];
                $access_token = $device_r['access_token'];
                $dev_name = $device_r['custom_name'];
                $end_date = $device_r['end_date_kompensasi'];
            }

            $data['user_id'] = $user_id;
            // $data['audio_port'] = $audio_port;

            #$d_ip = 'hypercube.my.id';
            $d_ip = 'hypercube.my.id';
            $d_port = $dev_port;
            $d_name = $dev_name;
            $d_token = $access_token;
            $d_end_date = $end_date;
            $d_audio_port = $audio_port;

            // Delay execution for 500 milliseconds
            usleep(500000);

            $this->set_cookie($d_ip, $d_port, $d_name, $d_token, $d_end_date, $d_audio_port);

            $this->load->view('v_player', $data);
        } else {
            redirect('dashboard');
        }
    }

    public function set_cookie($ip, $port, $nama, $token, $end_date, $audio_port)
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
        $e_date_cookie = array(
            'name'   => 'bulan',
            'value'  => $end_date,
            'expire' => time() + 1,
            'path'   => '/',
            'domain' => '',
            'secure' => FALSE,
            'httponly' => FALSE
        );

        // Set the cookie using the set_cookie function
        $this->input->set_cookie($e_date_cookie);

        // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
        $audio_cookie = array(
            'name'   => 'venus',
            'value'  => $audio_port,
            'expire' => time() + 1,
            'path'   => '/',
            'domain' => '',
            'secure' => FALSE,
            'httponly' => FALSE
        );

        // Set the cookie using the set_cookie function
        $this->input->set_cookie($audio_cookie);
    }

    public function audio_test()
    {
        $this->load->view('v_audio2');
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_player extends CI_Model
{
    function get_client_ip()
    {
        $ip = file_get_contents("http://ipecho.net/plain");
        return $ip;
    }

    function get_port($table, $where)
    {
        $this->db->select('ip, port');
        $this->db->where($where);
        $this->db->join('device', 'device.id=' . $table . '.device_id');

        $result = $this->db->get_where($table)->result_array();

        return $result;
    }

    function check_existing($table, $where)
    {
        $this->db->where($where);

        $result = $this->db->get_where($table)->result_array();

        return $result;
    }

    function add_firewall($table, $data)
    {
        $this->db->insert($table, $data);
    }
}

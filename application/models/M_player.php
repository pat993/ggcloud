<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_player extends CI_Model
{
    function get_client_ip()
    {
        //whether ip is from the share internet  
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from the remote address  
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function get_device($table, $where)
    {
        $this->db->select('assigned.id as assign_id, ip, port, custom_name, access_token, end_date_kompensasi');
        $this->db->where($where);
        $this->db->where('end_date>=CURRENT_TIMESTAMP()');
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

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_player extends CI_Model
{
    function get_client_ip()
    {
        // Function to get the client's IP address
        function getClientIP()
        {
            // Check for shared internet/ISP IP
            if (!empty($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                return $_SERVER['HTTP_CLIENT_IP'];
            }

            // Check for IP address from proxy
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                // If multiple IP addresses are present in the HTTP_X_FORWARDED_FOR header, use the last one
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($ips as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                        return $ip;
                    }
                }
            }

            // Remote address (normal case)
            if (!empty($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
                return $_SERVER['REMOTE_ADDR'];
            }

            // No valid IP found, return empty string
            return '';
        }

        // Get client IP address
        $clientIP = getClientIP();

        // Store the IP in an array
        $clientIPs = array($clientIP);

        echo json_encode($clientIPs);
    }

    function get_device($table, $where)
    {
        $this->db->select('assigned.id as assign_id, ip, port');
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

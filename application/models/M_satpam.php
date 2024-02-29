<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_satpam extends CI_Model
{
   function get_data($table)
   {
      $this->db->select('port, access_token');
      $this->db->where('end_date <= NOW()');
      $this->db->where('status', 'active');
      $this->db->join('device', 'device.id=assigned.device_id');

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }
   function razia1()
   {
      $sql = "UPDATE device INNER JOIN assigned on device.id = assigned.device_id SET status_id = '1' WHERE end_date <= NOW()";

      $this->db->query($sql);
   }

   function razia2()
   {
      $sql = "UPDATE assigned SET status = 'expired' WHERE end_date <= NOW()";

      $this->db->query($sql);
   }


   function update_firewall($id)
   {
      $sql = "UPDATE firewall INNER JOIN assigned on firewall.assign_id = assigned.id SET firewall.status = 'blocked' WHERE assign_id = '" . $id . "'";

      $this->db->query($sql);
   }

   function get_firewall_list($table)
   {
      $this->db->join('assigned', 'firewall.assign_id=assigned.id');
      $this->db->where('assigned.status', 'expired');
      $this->db->where('firewall.status', 'active');

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }
}

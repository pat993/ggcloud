<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_satpam extends CI_Model
{
   function razia($end_date)
   {
      $sql = "UPDATE device INNER JOIN assigned on device.id = assigned.device_id SET status = 'expired', status_id = '1' WHERE end_date <= '" . $end_date . "'";

      $this->db->query($sql);
   }

   function update_firewall($end_date)
   {
      $sql = "UPDATE firewall INNER JOIN assigned on firewall.assign_id = assigned.id SET firewall.status = 'blocked' WHERE end_date <= '" . $end_date . "'";

      $this->db->query($sql);
   }

   function get_firewall_list($table)
   {
      $this->db->join('assigned', 'firewall.assign_id=assigned.id');
      $this->db->where('assigned.status', 'expired');

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }
}

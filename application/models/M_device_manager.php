<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_device_manager extends CI_Model
{
   function get_data($table)
   {
      $this->db->select('*, device.id as device_id');
      $this->db->join('device_status', 'device_status.id = device.status_id');
      $this->db->order_by('device.id', 'DESC');

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function get_ajax_data($table, $where)
   {
      $this->db->select('*, device.id as device_id, assigned.status as device_status');
      $this->db->join('assigned', 'assigned.device_id = device.id', 'left');
      $this->db->join('user', 'user.id = assigned.user_id', 'left');
      $this->db->where($where);
      $this->db->order_by('end_date DESC');
      $this->db->limit(1);


      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function get_last_port($table)
   {
      $this->db->select('port');
      $this->db->order_by('port DESC');
      $this->db->limit(1);

      $result = $this->db->get_where($table)->result_array();

      foreach ($result as $result_r) {
         $last_port = $result_r['port'] + 1;
      }

      return $last_port;
   }

   function get_count($table)
   {
      $result = $this->db->get_where($table);

      return $result->num_rows();
   }

   function get_used($table)
   {
      $this->db->where('status_id !=', '2');

      $result = $this->db->get_where($table);

      return $result->num_rows();
   }

   function insert_data($table, $data)
   {
      $this->db->insert($table, $data);
   }

   function update_data($table, $data, $where)
   {
      $this->db->where($where);

      $this->db->update($table, $data);
   }

   public function delete_data($table, $where)
   {
      $this->db->where($where);
      $this->db->delete($table);
   }

   function get_configure_data($table, $where)
   {
      $result = $this->db->get_where($table, $where)->result_array();

      return $result;
   }
}

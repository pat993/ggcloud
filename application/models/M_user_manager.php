<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user_manager extends CI_Model
{
   function get_data($table)
   {
      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function get_count($table)
   {
      $result = $this->db->get_where($table);

      return $result->num_rows();
   }

   function get_count_active($table, $where)
   {
      $result = $this->db->get_where($table, $where);

      return $result->num_rows();
   }

   public function delete_data($table, $where)
   {
      $this->db->where($where);
      $this->db->delete($table);
   }

   public function get_ajax_data($table, $where)
   {
      $result = $this->db->get_where($table, $where)->result_array();

      return $result;
   }

   function update_data($table, $data, $where)
   {
      $this->db->where($where);

      $this->db->update($table, $data);
   }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_comp_manager extends CI_Model
{
   function get_assigned($table)
   {
      // Subquery to get kompensasi_total
      $this->db->select('assign_id, SUM(durasi) AS durasi');
      $this->db->from('kompensasi');
      $this->db->group_by('assign_id');
      $subquery = $this->db->get_compiled_select();

      // Main query
      $this->db->select('user.username, assigned.custom_name, assigned.end_date, IFNULL(kompensasi_total.durasi, 0) AS durasi, assigned.id AS id_assign, (TIMESTAMPDIFF(HOUR, NOW(), assigned.end_date) + durasi) AS masa_aktif');
      $this->db->from($table);
      $this->db->join('user', 'user.id = assigned.user_id');
      $this->db->join("($subquery) AS kompensasi_total", 'assigned.id = kompensasi_total.assign_id', 'left');
      $this->db->where('assigned.status', 'active');
      $this->db->order_by('assigned.end_date', 'DESC');

      $result = $this->db->get()->result_array();

      return $result;
   }

   function get_assigned_id($tabel)
   {
      $this->db->select('id');
      $this->db->from($tabel);
      $this->db->where('status', 'active');

      $result = $this->db->get()->result_array();

      return $result;
   }

   function get_data($table)
   {
      $this->db->from($table);

      $result = $this->db->get()->result_array();

      return $result;
   }

   function get_data_history($table, $where)
   {
      $this->db->from($table);
      $this->db->where($where);
      $this->db->order_by('id', 'ASC');

      $result = $this->db->get()->result_array();

      return $result;
   }

   function insert_data($table, $data)
   {
      $this->db->insert($table, $data);
   }

   public function delete_data($table, $where)
   {
      $this->db->where($where);
      $this->db->delete($table);
   }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_voucher_manager extends CI_Model
{
   function get_data($table, $where)
   {
      $this->db->select('device_identifier, custom_name');
      $this->db->where($where);
      $this->db->join('user', 'user.id = assigned.user_id');

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function get_count($table)
   {
      $result = $this->db->get_where($table);

      return $result->num_rows();
   }

   function get_count_device($table, $where)
   {
      $result = $this->db->get_where($table, $where);

      return $result->num_rows();
   }

   function get_used($table)
   {
      $this->db->where('voucher_status', 'Digunakan');

      $result = $this->db->get_where($table);

      return $result->num_rows();
   }

   function get_voucher_list($table)
   {
      $this->db->select('*, voucher.id as voucher_id');
      $this->db->join('package', 'package.id = voucher.paket_id', 'left');
      $this->db->order_by('voucher.id', 'DESC');

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function get_ajax_data($table, $where)
   {
      $this->db->select('*, voucher.id AS voucher_id, assigned.status AS assign_status');
      $this->db->join('assigned', 'assigned.id = voucher.assign_id', 'left');
      $this->db->join('user', 'user.id = assigned.user_id', 'left');
      $this->db->join('package', 'package.id = voucher.paket_id', 'left');

      $result = $this->db->get_where($table, $where)->result_array();

      return $result;
   }

   function get_package_info($table, $where)
   {
      $this->db->select('keterangan, harga');
      $result = $this->db->get_where($table, $where)->result_array();

      return $result;
   }

   function get_package_list($table, $where)
   {
      $this->db->select('id, kode_paket, nama, keterangan');
      $this->db->where($where);

      $result = $this->db->get_where($table)->result_array();

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

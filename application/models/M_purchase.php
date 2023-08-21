<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_purchase extends CI_Model
{
   function get_data($table, $where)
   {
      $this->db->select('*, purchase.id as purchase_id');
      $this->db->where($where);
      $this->db->join('package', 'purchase.package_id = package.id');
      $this->db->join('user', 'purchase.user_id = user.id');
      $this->db->order_by('purchase.id', 'Desc');
      $this->db->limit('10');

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function get_data_filter($table, $where1, $where2, $where3)
   {
      $this->db->select('*, purchase.id as purchase_id');
      if ($where1 != '') {
         $this->db->where($where1);
      }
      if ($where2 != '') {
         $this->db->where($where2);
      }
      if ($where3 != '') {
         $this->db->where($where3);
      }
      $this->db->join('package', 'purchase.package_id = package.id');
      $this->db->join('user', 'purchase.user_id = user.id');
      $this->db->order_by('purchase.id', 'Desc');

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }
}

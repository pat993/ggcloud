<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_pricing extends CI_Model
{
   function get_data($table, $where)
   {
      $this->db->select('*, package.id as package_id');
      $this->db->join('jenis_paket', 'jenis_paket.id=package.jenis_paket_id');
      $this->db->order_by('package.id', 'ASC');
      $result = $this->db->get_where($table, $where)->result_array();

      return $result;
   }
}

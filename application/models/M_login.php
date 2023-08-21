<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_login extends CI_Model
{
   function cek_login($table, $where)
   {
      $password = 0;

      $this->db->select('*');
      $this->db->where($where);

      $result = $this->db->get_where($table, $where)->result_array();

      return $result;
   }
}

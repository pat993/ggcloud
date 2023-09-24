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

   function cek_email($email)
   {
      $this->db->where('email', $email);
      $result = $this->db->get('user');
      if ($result->num_rows() > 0) {
         return true;
      } else {
         return false;
      }
   }
}

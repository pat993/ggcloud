<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_login extends CI_Model
{
   function cek_login($table, $username = null, $email = null)
   {
      $this->db->select('*');

      if ($username !== null) {
         $this->db->where($username);
      }

      if ($email !== null) {
         $this->db->or_where($email);
      }

      $this->db->where('status', 'aktif');

      $result = $this->db->get($table)->result_array();

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

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_register extends CI_Model
{
   function input_data($table, $data)
   {
      $this->db->insert($table, $data);
   }

   function randomString($length)
   {
      $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $totalChars = strlen($chars);
      $totalRepeat = ceil($length / $totalChars);
      $repeatString = str_repeat($chars, $totalRepeat);
      $shuffleString = str_shuffle($repeatString);
      return substr($shuffleString, 1, $length);
   }

   function check_existing($username, $email)
   {
      $this->db->select('username, email');
      $this->db->where('username', $username);
      $this->db->or_where('email', $email);

      $result = $this->db->get('user');
      if ($result->num_rows() > 0) {
         return true;
      } else {
         return false;
      }
   }

   function account_verification($table, $data)
   {
      $this->db->select('username');

      return $this->db->get_where($table, $data)->result_array();
   }

   function account_activation($table, $data)
   {
      $this->db->set('status', 'aktif');
      $this->db->where($data);
      $this->db->update($table);
   }
}

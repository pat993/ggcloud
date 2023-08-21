<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dashboard extends CI_Model
{
   function get_data($table, $where)
   {
      $this->db->where($where);

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function get_existing($table, $where)
   {
      $this->db->where($where);

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function cek_voucher($table, $where)
   {
      $this->db->select('voucher.id, paket_id, durasi, voucher.id as voucher_id, jenis_voucher');
      $this->db->join('package', 'voucher.paket_id = package.id', 'left');
      $this->db->where($where);
      $this->db->where('voucher_status', 'Belum Digunakan');

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function cek_available($table)
   {
      $this->db->where('status_id', '2');
      $this->db->limit(1);

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function insert_data($table, $data)
   {
      $this->db->insert($table, $data);
   }

   function update_data($table, $where, $data)
   {
      $this->db->where($where);

      $this->db->update($table, $data);
   }

   function get_assign_id($table, $where)
   {
      $this->db->select('id');
      $this->db->where($where);

      $result = $this->db->get_where($table)->result_array();

      return $result;
   }

   function randomString($length)
   {
      // Set the chars
      $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

      // Count the total chars
      $totalChars = strlen($chars);

      // Get the total repeat
      $totalRepeat = ceil($length / $totalChars);

      // Repeat the string
      $repeatString = str_repeat($chars, $totalRepeat);

      // Shuffle the string result
      $shuffleString = str_shuffle($repeatString);

      // get the result random string
      return substr($shuffleString, 1, $length);
   }

   function date_calc($jumlah)
   {
      $currentDate = date('Y-m-d H:i:s');
      $jumlah_jam = $jumlah * 24;
      return date('Y-m-d H:i:s', strtotime('+' . $jumlah_jam . ' ' . 'hour', strtotime($currentDate)));
   }

   function date_calc2($jumlah, $date)
   {
      $jumlah_jam = $jumlah * 24;
      return date('Y-m-d H:i:s', strtotime('+' . $jumlah_jam . ' ' . 'hour', strtotime($date)));
   }
}

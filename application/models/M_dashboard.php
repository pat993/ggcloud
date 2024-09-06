<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dashboard extends CI_Model
{
   function get_data($table, $where)
   {
      // Subquery to get kompensasi_total
      $this->db->select('assign_id, SUM(durasi) AS durasi');
      $this->db->from('kompensasi');
      $this->db->group_by('assign_id');
      $subquery = $this->db->get_compiled_select();

      // Main query
      $this->db->select('assigned.id as id, user_id, device_id, device_identifier, access_token, custom_name, start_date, end_date, assigned.status as status, IFNULL(kompensasi_total.durasi, 0) AS kompensasi, assigned.id AS id_assign, (TIMESTAMPDIFF(HOUR, NOW(), assigned.end_date) + IFNULL(kompensasi_total.durasi, 0)) AS masa_aktif');
      $this->db->from($table);
      $this->db->join('user', 'user.id = assigned.user_id');
      $this->db->join("($subquery) AS kompensasi_total", 'assigned.id = kompensasi_total.assign_id', 'left');
      $this->db->where($where);
      $this->db->where('assigned.end_date_kompensasi >=', 'NOW()', false); // Ensure proper handling of NOW() in where clause
      $this->db->order_by('assigned.end_date', 'DESC');

      $result = $this->db->get()->result_array();

      return $result;
   }

   function get_jenis_paket($table, $where)
   {
      $this->db->select('jenis_paket');
      $this->db->join('device', 'device.id=assigned.device_id');

      $result = $this->db->get_where($table, $where)->result_array();

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
      $this->db->select('voucher.id, paket_id, durasi, voucher.id as voucher_id, jenis_voucher, jenis_paket, harga, jenis_ecommerce');
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
      // Set timezone ke WIB (Asia/Jakarta)
      date_default_timezone_set('Asia/Jakarta');

      $currentDate = date('Y-m-d H:i:s');
      $jumlah_jam = $jumlah * 24;
      return date('Y-m-d H:i:s', strtotime('+' . $jumlah_jam . ' ' . 'hour', strtotime($currentDate)));
   }

   function date_calc2($jumlah, $date)
   {
      $jumlah_jam = $jumlah * 24;
      return date('Y-m-d H:i:s', strtotime('+' . $jumlah_jam . ' ' . 'hour', strtotime($date)));
   }

   function date_calc_jam($tanggal, $jumlah_jam)
   {
      // Set timezone ke WIB (Asia/Jakarta)
      date_default_timezone_set('Asia/Jakarta');

      return date('Y-m-d H:i:s', strtotime('+' . $jumlah_jam . ' ' . 'hour', strtotime($tanggal)));
   }
}

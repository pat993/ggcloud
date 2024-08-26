<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_invoice extends CI_Model
{
    function get_data($table, $where)
    {
        $this->db->select('invoice.id as id, invoice.harga as harga, user.username as user, jenis_paket.nama_paket as nama_paket, package.keterangan as nama_paket, package.tipe as tipe, tanggal_transaksi, jenis_transaksi, invoice.status as status');
        $this->db->from($table);
        $this->db->join('user', 'invoice.user_id=user.id');
        $this->db->join('jenis_paket', 'invoice.jenis_id=jenis_paket.id');
        $this->db->join('package', 'invoice.paket_id=package.id');
        $this->db->where($where);
        $this->db->order_by('tanggal_transaksi', 'DESC');

        $result = $this->db->get()->result_array();

        return $result;
    }
}

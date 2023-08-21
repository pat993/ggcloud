<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_satpam extends CI_Model
{
   function razia($end_date)
   {
      $sql = "UPDATE device INNER JOIN assigned on device.id = assigned.device_id SET status = 'expired', status_id = '1' WHERE end_date <= '" . $end_date . "'";

      echo $this->db->query($sql);
   }
}

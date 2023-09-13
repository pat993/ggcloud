<?php

use function PHPUnit\Framework\isEmpty;
use phpseclib\Net\SSH2;

defined('BASEPATH') or exit('No direct script access allowed');

class Satpam extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      // if ($this->session->userdata('status') != "login") {
      //    redirect(base_url("login"));
      // } else {
      //    $this->load->model('M_satpam');
      // }

      $this->load->model('M_satpam');
   }

   public function index()
   {
      $end_date = date('Y-m-d H:i:s');

      $this->M_satpam->razia($end_date);
      $this->M_satpam->update_firewall($end_date);
      $firewall_list = $this->M_satpam->get_firewall_list('firewall');

      echo json_encode($firewall_list);

      if (!isEmpty($firewall_list)) {
         $ssh = new SSH2('103.82.93.205');
         if (!$ssh->login('patra', '@Patraana007')) {
            exit('Login Failed');
         } else {
            foreach ($firewall_list as $firewall_list_r) {
               $ssh->exec("sudo ufw denny from " . $firewall_list_r['ip'] . " to any port " . $firewall_list_r['port'] . "");
            }
         }
      }
   }
}

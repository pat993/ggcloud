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
      $firewall_list = $this->M_satpam->get_firewall_list('firewall');

      echo json_encode($firewall_list);

      if (count($firewall_list) != 0) {
         $ssh = new SSH2('103.82.93.205');
         if (!$ssh->login('root', '@Patraana007')) {
            exit('Login Failed');
         } else {
            foreach ($firewall_list as $firewall_list_r) {
               // $ssh->exec("sudo ufw deny from " . $firewall_list_r['ip'] . " to any port " . $firewall_list_r['port'] . "");
               $ssh->exec("sudo ufw show added | grep '" . $firewall_list_r['ip'] . " to any port " . $firewall_list_r['port'] . "' | awk '{ gsub(\"ufw\",\"ufw delete\",$0); system($0)}'");
               $ssh->exec("sudo ufw reload");

               $this->M_satpam->update_firewall($firewall_list_r['assign_id']);

               // echo "sudo ufw show added | grep '" . $firewall_list_r['ip'] . " to any port " . $firewall_list_r['port'] . "' | awk '{ gsub(\"ufw\",\"ufw delete\",$0); system($0)}'";
            }
         }
      }
   }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Voucher_manager extends CI_Controller
{
   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login" && $this->session->userdata('username') != "admin") {
         redirect(base_url("login"));
      } else {
         $this->load->model('M_voucher_manager');
         $this->load->model('M_device_manager');
      }
   }

   public function index()
   {
      $user_id = $this->session->userdata('user_id');

      $data['user_id'] = $user_id;

      $data['voucher_count'] = $this->M_voucher_manager->get_count('voucher');
      $data['voucher_used_count'] = $this->M_voucher_manager->get_used('voucher');


      $where = array(
         'status_id' => '2'
      );

      $data['device_count'] = $this->M_voucher_manager->get_count_device('device', $where);
      // $data['device_used_count'] = $this->M_device_manager->get_used('device');

      $data['voucher_list'] = $this->M_voucher_manager->get_voucher_list('voucher');

      $data['jenis_paket_list'] = $this->M_voucher_manager->jenis_paket('jenis_paket');

      $this->load->view('templates/t_header');
      $this->load->view('templates/t_sidebar');
      $this->load->view('v_voucher_manager', $data);
      $this->load->view('templates/t_footer');
   }

   public function daftar_paket($str, $str2)
   {

      if ($str == 'Baru') {
         $str = 'Tambah Baru';
      } elseif ($str == 'Perpanjang') {
         $str = 'Perpanjangan';
      }

      $where = array(
         'tipe' => $str,
         'jenis_paket_id' => $str2
      );

      $data['package_list'] = $this->M_voucher_manager->get_package_list('package', $where);

      $this->load->view('ajax/a_daftar_paket.php', $data);
   }

   function formatRupiah($nominal)
   {
      return number_format($nominal, 0, ',', '.');
   }

   public function add_voucher()
   {
      $kode_voucher = $this->input->post('txt_kode_voucher');
      $paket_id = $this->input->post('txt_paket_id');
      $tipe = $this->input->post('txt_tipe');
      $jenis = $this->input->post('txt_jenis');
      $ext_ecommerce = $this->input->post('txt_ext_ecommerce');
      $order_id = $this->input->post('txt_order_id');
      $email = $this->input->post('txt_email');
      $expired = $this->input->post('txt_expired');

      $expired_konversi = date("d F Y", strtotime($expired));

      $data = array(
         'kode_voucher' => $kode_voucher,
         'paket_id' => $paket_id,
         'jenis_voucher' => $tipe,
         'jenis_paket' => $jenis,
         'jenis_ecommerce' => $ext_ecommerce,
         'order_id' => $order_id,
         'email_tujuan' => $email,
         'tanggal_expired' => $expired
      );

      $where = array(
         'id' => $paket_id
      );

      $info_paket = $this->M_voucher_manager->get_package_info('package', $where);

      $this->M_voucher_manager->insert_data('voucher', $data);

      // PHPMailer object
      $response = false;
      $mail = new PHPMailer();


      // SMTP configuration
      $mail->isSMTP();
      $mail->Host     = 'iix1533.idcloudhost.com'; // Gunakan IP address server SMTP
      $mail->SMTPAuth = true;
      $mail->Username = 'noreply@ggcloud.id'; // User email
      $mail->Password = '@Patra007'; // Password email
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Atau 'ssl' jika menggunakan port 465
      $mail->Port     = 587; // Port untuk TLS. Gunakan 465 jika SSL.

      // Aktifkan debugging
      //$mail->SMTPDebug = 2; // 2: Debug output verbose
      //$mail->Debugoutput = 'html'; // Output debugging dalam format HTML


      $mail->Timeout = 60; // timeout pengiriman (dalam detik)
      $mail->SMTPKeepAlive = true;

      $mail->setFrom('noreply@ggcloud.id', 'GGCLOUD'); // user email
      $mail->addReplyTo('noreply@ggcloud.id', ''); //user email

      // Add a recipient
      $mail->addAddress($email); //email tujuan pengiriman email

      // Email subject
      $mail->Subject = 'Voucher kamu sampai nih'; //subject email

      // Set email format to HTML
      $mail->isHTML(true);

      // $mail->AddEmbeddedImage(base_url() . 'images/ggcloud.png', 'ggc_logo', 'ggcloud.png');

      // Email body content
      $mailContent = "
      <div style='background-color: #EEF1FF; padding: 15px 0 15px 0'>
        <div style='max-width: 500px;  padding: 10px 10px 20px 10px; border-radius: 10px; margin: auto; background-color: #ffff'>
           <div style='text-align: center; margin-top: 15px'>
              <img src='https://dl.dropboxusercontent.com/scl/fi/s9ip00dlrkvyd1rxcnbc2/ggcloud_min.png?rlkey=6wnd9cs0rh4w5aqr4yngti2ca&st=d2ix8cfz&dl=0' width='70px' ></a>
           </div>
           <div style='padding: 10px; border-radius: 10px'>
              <h3 style='margin-bottom: 2px'>Terima Kasih 🧡</h3>
              Gunakan kode voucher di bawah untuk menambahkan perangkat kamu <br>
              Jenis Paket : " . $info_paket[0]['keterangan'] . "
              <div style='background-color:  #f2f2f2; border-radius: 20px; font-size: 20px; padding: 10px; margin: 20px 0 20px 0; text-align: center; color: green'>
                 " . $kode_voucher . "
              </div>
              <small><i>Voucher Expiration : " . $expired_konversi . "</i></small>
              <br>
              <br>
              <div style='background-color: #f2f2f2;  margin-top: 5px; padding: 5px'>
                 <strong>Detail Pembelian :</strong><br>
              </div>
              <div style='border: 1px solid #f2f2f2; padding : 5px'>
                 Tangal Pembelian : " . date("d F Y") . "<br>
                 Order ID : " . $order_id . "<br>
                 Subtotal : " . $this->formatRupiah($info_paket[0]['harga']) . " IDR<br>
              </div>

              <br>
              <small><b>Penting!</b> Segera lakukan klaim voucher pada akun kamu sebelum tanggal expired!</small>
              <br>
              <br>
              <a style='font-size: 9px; color: #cfcfcf; float: right'>Copyright " . date('Y') . " GGCloud.id. Business Contact: admin@ggcloud.id</a>
           </div>
        </div>
     </div>";

      $mail->Body = $mailContent;

      // Send email
      if (!$mail->send()) {
         echo 'Message could not be sent.';
         echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
         $this->session->set_flashdata('success', "Berhasil Generate Voucher");

         redirect($_SERVER['HTTP_REFERER']);
      }
   }

   public function get_detail($id)
   {
      $user_id = $this->session->userdata('user_id');

      if ($user_id == '1') {
         $where = array(
            'voucher.id' => $id
         );

         $data['voucher_detail'] = $this->M_voucher_manager->get_ajax_data('voucher', $where);

         $this->load->view('ajax/a_voucher_detail.php', $data);
      }
   }

   public function delete_voucher($id)
   {
      $where = array(
         'id' => $id
      );

      $this->M_voucher_manager->delete_data('voucher', $where);

      $this->session->set_flashdata('success', "Berhasil Hapus Data");

      redirect($_SERVER['HTTP_REFERER']);
   }
}

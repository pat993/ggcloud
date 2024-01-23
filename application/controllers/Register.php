<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Register extends CI_Controller
{

   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login") {
         $this->load->model('M_register');
      } else {
         redirect(base_url("dashboard"));
      }
   }

   function index()
   {
      $this->load->view('templates/t_header');
      $this->load->view('v_register');
      $this->load->view('templates/t_footer');
   }

   function aksi_register()
   {
      $username = $this->input->post('username');
      $email = $this->input->post('email');
      $password1 = $this->input->post('password1');
      $password2 = $this->input->post('password2');

      //recaptca
      $response = $this->input->post('g-recaptcha-response');
      $error = "";
      $secret = "6Lf72FUpAAAAAIdtF5CjQ353d9-Y2dYAcyYZVHg6";
      $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response";
      $verify = json_decode(file_get_contents($url));

      if ($verify->success) {
         //password validation
         $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";

         //prevent script inject by user

         if (preg_match("^[a-zA-Z0-9_\\-]+$^", $username) && preg_match($password_regex, $password1)) {
            if ($password1 == $password2) {
               $options = [
                  'cost' => 10,
               ];

               $check_existing = $this->M_register->check_existing($username, $email);

               if ($check_existing != true) {
                  $password_hash = password_hash($password1, PASSWORD_BCRYPT, $options);

                  $activation_code = $this->M_register->randomString(10);

                  $data = array(
                     'username' => $username,
                     'email' => $email,
                     'password' => $password_hash,
                     'activation_code' => $activation_code
                  );

                  $this->M_register->input_data("user", $data);

                  $this->session->set_flashdata('success', "Berhasil register user");
                  $this->session->set_flashdata('username', $username);
                  $this->session->set_flashdata('email', $email);
                  $this->session->set_flashdata('activation_code', $activation_code);

                  redirect('register/success');
               } else {
                  $this->session->set_flashdata('error', "Username / email sudah terdaftar");
                  $this->session->set_flashdata('username', $username);
                  $this->session->set_flashdata('email', $email);

                  redirect('register');
               }
            } else {
               $this->session->set_flashdata('error', "Password tidak sesuai");
               $this->session->set_flashdata('username', $username);
               $this->session->set_flashdata('email', $email);

               redirect('register');
            }
         } else {
            $this->session->set_flashdata('error', "Username / Password tidak sesuai persyaratan");
            $this->session->set_flashdata('username', $username);
            $this->session->set_flashdata('email', $email);

            redirect('register');
         }
      } else {
         $this->session->set_flashdata('error', "Captcha error");
         $this->session->set_flashdata('username', $username);
         $this->session->set_flashdata('email', $email);

         redirect('register');
      }
   }

   function success()
   {
      $username = $this->session->flashdata('username');
      $email = $this->session->flashdata('email');
      $activation_code = $this->session->flashdata('activation_code');

      // PHPMailer object
      $response = false;
      $mail = new PHPMailer();

      // SMTP configuration
      $mail->isSMTP();
      $mail->Host     = 'ggcloud.id'; //sesuaikan sesuai nama domain hosting/server yang digunakan
      $mail->SMTPAuth = true;
      $mail->Username = 'noreply@ggcloud.id'; // user email
      $mail->Password = '@Patra007'; // password email
      $mail->SMTPSecure = 'ssl';
      $mail->SMTPAutoTLS = false;
      $mail->Port     = 465;
      //$mail->SMTPDebug = 1;


      $mail->Timeout = 60; // timeout pengiriman (dalam detik)
      $mail->SMTPKeepAlive = true;

      $mail->setFrom('noreply@ggcloud.id', 'GGCLOUD'); // user email
      $mail->addReplyTo('noreply@ggcloud.id', ''); //user email

      // Add a recipient
      $mail->addAddress($email); //email tujuan pengiriman email
      //$mail->addAddress($email); //email tujuan pengiriman email

      // Email subject
      $mail->Subject = 'Yuk aktivasi akun kamu'; //subject email

      // Set email format to HTML
      $mail->isHTML(true);

      // $mail->AddEmbeddedImage(base_url() . 'images/ggcloud.png', 'ggc_logo', 'ggcloud.png');

      // Email body content
      $mailContent = "
      <div style='background-color: #EEF1FF; padding: 15px 0 15px 0'>
         <div style='max-width: 500px;  padding: 10px; border-radius: 10px; margin: auto; background-color: #ffff'>
            <div style='text-align: center; margin-top: 15px'>
               <img src='https://ggcloud.id/images/ggcloud.png' width='70px' ></a>
            </div>
            <div style='padding: 10px; border-radius: 10px'>
               <h3 style='margin-bottom: 1px'>Aktivasi Pendaftaran</h3><br>
               Dear " . $username . ",<br>
               Terima kasih sudah mendaftar di ggcloud.id, silahkan klik tombol di bawah ini untuk melakukan aktivasi akun kamu<br>
               <br>
               
               <div style='width: 100%; margin: 10px 0 10px 0; text-align: center; '>
               <a style='text-decoration: none; background-color: #98A8F8; border: 0; border-radius: 20px; color: white; padding: 15px; ' href='" . base_url() . "register/activation?token=" . $activation_code . "'>
               ACTIVATE ACCOUNT
               </a>
               
               </div>

               <br>
               <br>
               Regards,<br>
               Ggcloud team<br>
            </div>
            <div style='text-align: right; color: white'><small>Ggcloud.id</small></div>
         </div>
      </div>";

      $mail->Body = $mailContent;

      // Send email
      if (!$mail->send()) {
         echo 'Message could not be sent.';
         echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
         $this->load->view('templates/t_header');
         $this->load->view('v_success');
         $this->load->view('templates/t_footer');
      }
   }

   function activation()
   {
      $token = $this->input->get('token');

      $data = array(
         'activation_code' => $token,
         'status' => 'non-aktif'
      );

      $verify = $this->M_register->account_verification('user', $data);

      if (!$verify) {
         redirect('login');
      } else {
         foreach ($verify as $verify_p) {
            $username = $verify_p['username'];
         }

         $data2 = array(
            'username' => $username
         );

         $this->M_register->account_activation('user', $data2);

         $this->session->set_flashdata('success', "Berhasil aktivasi akun");
         redirect('register/account_verified');
      }
   }

   function account_verified()
   {
      $this->load->view('templates/t_header');
      $this->load->view('v_success_activate');
      $this->load->view('templates/t_footer');
   }
}

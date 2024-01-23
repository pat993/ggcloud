<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once 'vendor/autoload.php';


class Login extends CI_Controller
{

   function __construct()
   {
      parent::__construct();

      if ($this->session->userdata('status') != "login") {
         $this->load->model('M_login');
      } else {
         redirect(base_url("dashboard"));
      }
   }

   function index()
   {
      // include_once APPPATH . "libraries/vendor/autoload.php";

      $google_client = new Google_Client();

      $google_client->setClientId('202490747901-sep9vrasqf7imp704iorairj8n3t8n4f.apps.googleusercontent.com'); //Define your ClientID

      $google_client->setClientSecret('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'); //Define your Client Secret Key

      // $google_client->setRedirectUri('https://8000-pat993-ggcloud-c6xxrtwvwv2.ws-us104.gitpod.io/login'); //Define your Redirect Uri
      $google_client->setRedirectUri('https://ggcloud.id/login'); //Define your Redirect Uri

      $google_client->addScope('email');

      $google_client->addScope('profile');

      if (isset($_GET["code"])) {
         $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

         if (!isset($token["error"])) {
            $google_client->setAccessToken($token['access_token']);

            $this->session->set_userdata('access_token', $token['access_token']);

            $google_service = new Google_Service_Oauth2($google_client);

            $data = $google_service->userinfo->get();

            $current_datetime = date('Y-m-d H:i:s');

            if ($this->M_login->cek_email($data['email'])) {
               //update data
               // $user_data = array(
               //    'email_address' => $data['email'],
               // );

               // $email = $data['email'];

               $where = array(
                  'email' => $data['email']
               );

               $user = $this->M_login->cek_login('user', $where);

               foreach ($user as $user_p) {
                  $username = $user_p['username'];
                  $email = $user_p['email'];
                  $user_id = $user_p['id'];
               };

               $data_session = array(
                  'username' => $username,
                  'email' => $email,
                  'status' => "login",
                  'user_id' => $user_id
               );

               $this->session->set_userdata($data_session);

               redirect(base_url("dashboard"));
            } else {
               $this->session->set_flashdata('error', "Silahkan registrasi terlebih dahulu");
               $this->session->set_flashdata('email', $data['email']);

               redirect('register');
            }
         }
      }

      $username_c = $this->input->cookie('username', TRUE);
      $password_c = $this->input->cookie('password', TRUE);

      echo $username_c;

      if ($username_c != '') {
         $this->auto_login($username_c, $password_c);
      }

      $data['auth_url'] = $google_client->createAuthUrl();
      $data['username_cookie'] = $google_client->createAuthUrl();

      $this->load->view('templates/t_header');
      $this->load->view('v_login', $data);
      $this->load->view('templates/t_footer');
   }

   function aksi_login()
   {
      $err_count = $this->session->userdata('err_count');

      if ($err_count == 1) {
         //recaptca
         $response = $this->input->post('g-recaptcha-response');
         $error = "";
         $secret = "6Lf72FUpAAAAAIdtF5CjQ353d9-Y2dYAcyYZVHg6";
         $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response";
         $verify = json_decode(file_get_contents($url));
         $auth = $verify->success;
      } else {
         $auth = 1;
      }

      if ($auth == 1) {

         $password_cek = '';

         $username = $this->input->post('username');
         $password = $this->input->post('password');
         $where = array(
            'username' => $username,
            'status' => 'aktif'
         );
         $cek = $this->M_login->cek_login("user", $where);

         foreach ($cek as $cek_p) {
            $username = $cek_p['username'];
            $email = $cek_p['email'];
            $password_cek = $cek_p['password'];
            $user_id = $cek_p['id'];
         };

         //echo print_r($cek);
         if (password_verify($password, $password_cek)) {

            $data_session = array(
               'username' => $username,
               'email' => $email,
               'status' => "login",
               'user_id' => $user_id,
               'err_count' => 0
            );

            $this->session->set_userdata($data_session);

            $this->set_cookie($username, $password);

            redirect(base_url("dashboard"));
         } else {
            $this->session->set_flashdata('error', "Username / Password tidak sesuai");

            $data_session = array(
               'err_count' => 1
            );

            $this->session->set_userdata($data_session);

            redirect('login');
         }
      } else {
         $this->session->set_flashdata('error', "Captcha error");

         redirect('login');
      }
   }

   public function auto_login($username, $password)
   {
      $password_cek = '';

      $where = array(
         'username' => $username,
         'status' => 'aktif'
      );
      $cek = $this->M_login->cek_login("user", $where);

      foreach ($cek as $cek_p) {
         $username = $cek_p['username'];
         $email = $cek_p['email'];
         $password_cek = $cek_p['password'];
         $user_id = $cek_p['id'];
      };

      //echo print_r($cek);
      if (password_verify($password, $password_cek)) {

         $data_session = array(
            'username' => $username,
            'email' => $email,
            'status' => "login",
            'user_id' => $user_id,
            'err_count' => 0
         );

         $this->session->set_userdata($data_session);

         redirect(base_url("dashboard"));
      } else {
         $this->session->set_flashdata('error', "Username / Password tidak sesuai");

         $data_session = array(
            'err_count' => 1
         );

         $this->session->set_userdata($data_session);

         redirect('login');
      }
   }

   public function set_cookie($username, $password)
   {
      // Set the cookie parameters for username
      $username_cookie = array(
         'name'   => 'username',
         'value'  => $username,
         'expire' => time() + 360000000, // Cookie expiration time (1 hour from now)
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($username_cookie);

      // Set the cookie parameters for password (Note: storing passwords in cookies is not recommended)
      $password_cookie = array(
         'name'   => 'password',
         'value'  => $password,
         'expire' => time() + 360000000,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($password_cookie);
   }
}

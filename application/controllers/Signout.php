<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Signout extends CI_Controller
{

   public function index()
   {
      $this->session->sess_destroy();

      $this->unset_cookie();

      redirect(base_url('login'));
   }

   public function unset_cookie()
   {
      // Set the cookie parameters for username
      $username_cookie = array(
         'name'   => 'username',
         'value'  => '',
         'expire' => time() - 360000000, // Cookie expiration time (1 hour from now)
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
         'value'  => '',
         'expire' => time() - 360000000,
         'path'   => '/',
         'domain' => '',
         'secure' => FALSE,
         'httponly' => FALSE
      );

      // Set the cookie using the set_cookie function
      $this->input->set_cookie($password_cookie);
   }
}

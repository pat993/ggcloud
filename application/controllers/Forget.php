<?php
defined('BASEPATH') or exit('No direct script access allowed');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

class Forget extends CI_Controller
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
        $data['auth_code'] = '';

        $this->load->view('templates/t_header');
        $this->load->view('v_forget_password', $data);
        $this->load->view('templates/t_footer');
    }

    function reset_password()
    {
        $token = $this->input->get('token');

        $data = array(
            'auth_code' => $token,
            'status' => 'Unused'
        );

        $verify = $this->M_register->get_data('reset_password', $data);

        if (!$verify) {
            redirect('login');
        } else {
            $this->load->view('templates/t_header');
            $this->load->view('v_reset_password', $data);
            $this->load->view('templates/t_footer');
        }
    }

    function reset_process($token = NULL)
    {
        //recaptca
        $response = $this->input->post('g-recaptcha-response');
        $error = "";
        $secret = "6Lf72FUpAAAAAIdtF5CjQ353d9-Y2dYAcyYZVHg6";
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response";
        $verify = json_decode(file_get_contents($url));

        if ($verify->success) {
            $data = array(
                'auth_code' => $token,
                'status' => 'Unused'
            );

            $verify = $this->M_register->get_data('reset_password', $data);

            if (!$verify) {
                redirect('login');
            } else {
                $password1 = $this->input->post('password1');
                $password2 = $this->input->post('password2');

                //password validation
                $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";

                //prevent script inject by user

                if (preg_match($password_regex, $password1)) {
                    if ($password1 == $password2) {
                        $options = [
                            'cost' => 10,
                        ];

                        $password_hash = password_hash($password1, PASSWORD_BCRYPT, $options);

                        foreach ($verify as $verify_p) {
                            $id = $verify_p['id'];
                            $email = $verify_p['email'];
                        }

                        $data = array(
                            'status' => 'Used'
                        );

                        $data2 = array(
                            'password' => $password_hash
                        );

                        $where = array(
                            'email' => $email
                        );

                        $where2 = array(
                            'id' => $id
                        );

                        $this->M_register->update_data('reset_password', $where2, $data);
                        $this->M_register->update_data('user', $where, $data2);

                        $this->session->set_flashdata('success', "Berhasil ganti password");

                        redirect('login');
                    } else {
                        $this->session->set_flashdata('error', "Password tidak sama");

                        redirect('forget/reset_password?token=' . $token);
                    }
                } else {
                    $this->session->set_flashdata('error', "Password tidak sesuai persyaratan");

                    redirect('forget/reset_password?token=' . $token);
                }
            }
        } else {
            $this->session->set_flashdata('error', "Captcha error");

            redirect('forget/reset_password?token=' . $token);
        }
    }

    function reset()
    {
        $email = $this->input->post('email');

        $where = array(
            'email' => $email
        );

        //cek user
        $user_data = $this->M_register->get_data('user', $where);

        if (count($user_data) > 0) {
            //update unused auth to expired

            $where = array(
                'email' => $email,
                'status' => 'Unused'
            );

            $data = array(
                'status' => 'Expired'
            );

            $this->M_register->update_data('reset_password', $where, $data);

            foreach ($user_data as $user_data_p) {
                $user_id = $user_data_p['id'];
                $email = $email;
                $username = $user_data_p['username'];
            }

            $auth_code = $this->generate_authcode();

            $data = array(
                'user_id' => $user_id,
                'email' => $email,
                'auth_code' => $auth_code
            );

            $this->M_register->input_data('reset_password', $data);
            $this->send_mail($email, $auth_code, $username);

            $this->session->set_flashdata('success', "Berhasil mengirimkan permintaan reset password, silahkan cek email kamu");

            redirect('forget');
        } else {
            $this->session->set_flashdata('error', "Email tidak terdaftar");

            redirect('forget');
        }
    }

    function generate_authcode()
    {
        $randomBytes = random_bytes(12); // 6 bytes will be base64-encoded to 8 characters
        $base64String = base64_encode($randomBytes);

        // Remove non-alphanumeric characters
        $cleanString = preg_replace('/[^a-zA-Z0-9]/', '', $base64String);

        // Take the first 16 characters
        $result = substr($cleanString, 0, 16);

        return $result;
    }

    function send_mail($email, $auth_code, $username)
    {
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
        $mail->Subject = 'Lupa password'; //subject email

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
               <h3 style='margin-bottom: 1px'>Permintaan reset password</h3><br>
               Dear " . $username . ",<br>
               Anda telah melakukan permintaan reset password, silahkan klik tombol di bawah ini untuk melakukan reset password akun kamu<br>
               <br>
               
               <div style='width: 100%; margin: 10px 0 10px 0; text-align: center; '>
               <a style='text-decoration: none; background-color: #98A8F8; border: 0; border-radius: 20px; color: white; padding: 15px; ' href='" . base_url() . "forget/reset_password?token=" . $auth_code . "'>
               RESET PASSWORD
               </a>
               </div>
               <br>
               Jika anda merasa tidak melakukan permintaan reset password berikut, silahkan abaikan pesan ini.

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
        }
    }
}

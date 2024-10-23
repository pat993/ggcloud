<?php
defined('BASEPATH') or exit('No direct script access allowed');

use phpseclib\Net\SSH2;

class Ssh_test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('username') != "admin") {
            redirect(base_url("login"));
        }
    }

    public function index()
    {
        // Konfigurasi koneksi SSH
        $server_ip = 'hypercube.my.id';
        $server_port = 22;
        $server_username = 'patra';
        $server_password = '@Nadhira250420';

        // Inisialisasi objek SSH
        $ssh = new SSH2($server_ip, $server_port);

        // Coba login ke server
        if (!$ssh->login($server_username, $server_password)) {
            // Jika login gagal, tampilkan pesan error
            echo 'SSH login failed: ' . $ssh->getLastError();
            return;
        }

        // Jika login berhasil, tampilkan pesan sukses
        echo 'SSH login successful<br>';

        // Daftar perintah yang akan diuji
        $commands = [
            'hostname',  // Mendapatkan hostname dari server
            'uptime',    // Mendapatkan uptime dari server
            'whoami',    // Menampilkan user yang digunakan
            'ls -l /etc/haproxy/', // List file di direktori tertentu
            'cat /etc/haproxy/haproxy.cfg' // Menampilkan isi file konfigurasi HAProxy
        ];

        // Eksekusi setiap perintah dan tampilkan hasilnya
        foreach ($commands as $command) {
            echo "<strong>Running command:</strong> $command<br>";
            $output = $ssh->exec($command);
            if ($output === false) {
                echo "Failed to execute command: $command<br>";
                echo "Error: " . $ssh->getLastError() . "<br>";
            } else {
                echo "Output:<pre>$output</pre><br>";
            }
        }

        // Tambahkan konfigurasi baru ke haproxy.cfg
        $config_to_add = <<<CONFIG
frontend ws_frontend_test
    bind *:6000 ssl crt /etc/letsencrypt/live/hypercube.my.id/combination.pem
    acl valid_token_test urlp(token) -m str TEST_TOKEN
    http-request deny if !valid_token_test
    use_backend ws_server_test

backend ws_server_test
    server ws_test 127.0.0.1:6001
CONFIG;

        // Buat perintah untuk menambahkan konfigurasi
        $command = 'echo "' . addslashes($config_to_add) . '" | sudo tee -a /etc/haproxy/haproxy.cfg';
        echo "<strong>Adding configuration:</strong> $command<br>";

        // Eksekusi perintah untuk menambahkan konfigurasi
        $output = $ssh->exec($command);
        if ($output === false) {
            echo "Failed to add configuration<br>";
            echo "Error: " . $ssh->getLastError() . "<br>";
        } else {
            echo "Configuration added successfully<br>";
        }

        // Reload HAProxy untuk menerapkan perubahan
        $reload_command = 'sudo systemctl reload haproxy';
        echo "<strong>Reloading HAProxy:</strong> $reload_command<br>";
        $output = $ssh->exec($reload_command);
        if ($output === false) {
            echo "Failed to reload HAProxy<br>";
            echo "Error: " . $ssh->getLastError() . "<br>";
        } else {
            echo "HAProxy reloaded successfully<br>";
        }

        // Verifikasi perubahan di haproxy.cfg
        echo "<strong>Updated haproxy.cfg:</strong><br>";
        $output = $ssh->exec('cat /etc/haproxy/haproxy.cfg');
        if ($output === false) {
            echo "Failed to read updated haproxy.cfg<br>";
            echo "Error: " . $ssh->getLastError() . "<br>";
        } else {
            echo "Output:<pre>$output</pre><br>";
        }

        // Tutup koneksi SSH
        $ssh->disconnect();
    }
}

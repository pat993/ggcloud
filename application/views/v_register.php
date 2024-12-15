<div style="max-width: 800px; margin: auto" class="page-content pb-0">

   <div data-card-height="cover" class="card">
      <div class="card-top notch-clear">
         <div class="d-flex">
            <a href="#" onclick="window.location.href='/login';return true;" class="me-auto icon icon-m"><i class="font-14 fa fa-arrow-left color-theme"></i></a>
            <!-- <a href="#" data-toggle-theme class="show-on-theme-light ms-auto icon icon-m"><i class="font-12 fa fa-moon color-theme"></i></a>
            <a href="#" data-toggle-theme class="show-on-theme-dark ms-auto icon icon-m"><i class="font-12 fa fa-lightbulb color-yellow-dark"></i></a> -->
         </div>
      </div>
      <div class="card-center">
         <div class="ps-5 pe-5">
            <!-- <div style="padding-bottom: 20px" class="text-center">
               <img class="text-center" src="images/ggcloud.png" alt="">
            </div> -->
            <h1 class="text-center font-200 font-40 mb-4">Sign Up</h1>
            <!-- <p class="text-center font-12">Register new member</p> -->

            <form action="<?= base_url('register/aksi_register'); ?>" method="POST">

               <div class="input-style no-borders has-icon" style="position: relative">
                  <i class="fa fa-user"></i>
                  <i style="position: absolute; right:5px; top: 15px" class="fa-solid fa-circle-question" data-toggle="tooltip" data-html="true" title="Only character, number and '_' allowed"></i>
                  <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
               </div>
               <!-- <small>* Hanya berupa karakter, nomor dan garis bawah ('_')</small> -->
               <?php if (null !== $this->session->flashdata('email')) {
                  $email = $this->session->flashdata('email');
               } else {
                  $email = '';
               } ?>
               <div class="input-style no-borders has-icon mt-2">
                  <i class="fa fa-at"></i>
                  <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?= $email ?>" required>
               </div>
               <div class="input-style no-borders has-icon mt-2" style="position: relative;">
                  <i class="fa fa-lock"></i>
                  <i style="position: absolute; right:5px; top: 15px" class="fa-solid fa-circle-question" data-toggle="tooltip" data-html="true" title="Has minimum 8 characters in length.<br>
                     At least one uppercase letter.<br>
                     At least one lowercase letter.<br>
                     At least one digit.<br>
                     At least one special character."></i>
                  <input type="password" class="form-control" placeholder="Choose a Password" name="password1" minlength="8" required>
               </div>
               <div class="input-style no-borders has-icon mt-2">
                  <i class="fa fa-lock"></i>
                  <input type="password" class="form-control" placeholder="Confirm your Password" name="password2" minlength="8" required>
               </div>

               <div class="g-recaptcha mt-4" data-sitekey="6LcJIZwqAAAAAGGvyV5CWvUqGVsAlzBK4zFNbJBV" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>

               <div class="form-check icon-check mb-2">
                  <input class="form-check-input" type="checkbox" value="" id="check4" required>
                  <label class="form-check-label" for="check4">
                     <small>Dengan ini saya menyetujui <a href="" data-menu="modal-tos">T.O.S</a> yang berlaku selama menggunakan layanan</small>
                  </label>
                  <i class="icon-check-1 far fa-circle color-gray-dark font-16"></i>
                  <i class="icon-check-2 far fa-check-circle font-16 color-highlight-purple"></i>
               </div>

               <button style="width: 100%" type="submit" name="signup" class="btn btn-full btn-m rounded-sm text-uppercase font-200 btn-grad">Create Account</button>

               <div class="text-center mb-5 mt-4">
                  <a href="#" onclick="window.location.href='/login';return true;" class="font-12">Already Registered? Sign in Here</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- End of Page Content-->

<div id="modal-tos" class="menu menu-box-modal rounded-m" style="max-width: 600px; width: 100%">
   <div class="menu-title">
      <h1>
         TERMS OF SERVICE <button class="close-menu float-right me-4 opacity-50"><i class="fa fa-times"></i></button>
      </h1>
   </div>

   <div class="content mt-2 ms-4 me-4 pb-4">
      <div style="overflow: scroll; height: 80vh">
         <h2>Syarat dan Ketentuan Layanan</h2>

         <p>GGCLOUD adalah penyedia layanan penyewaan perangkat Android berbasis cloud. Dengan menggunakan layanan kami, Anda menyetujui syarat dan ketentuan berikut.</p>

         <h3>Penggunaan Layanan</h3>
         <p>1. Anda setuju untuk menggunakan layanan kami sesuai dengan hukum yang berlaku dan tidak melakukan tindakan yang dapat merugikan kami atau pengguna lain.</p>
         <p>2. Dilarang keras melakukan aktivitas ilegal atau yang melanggar hukum menggunakan layanan kami.</p>

         <h3>Larangan Penggunaan</h3>
         <p>Demi menjaga kenyamanan dan keamanan layanan, kami melarang tindakan-tindakan berikut:</p>
         <ul>
            <li>Peretasan (hacking) sistem kami atau pengguna lain</li>
            <li>Penambangan cryptocurrency (mining)</li>
            <li>Rooting perangkat virtual</li>
            <li>Instalasi perangkat lunak ilegal</li>
            <li>Perusakan sistem perangkat dengan sengaja</li>
            <li>Perjudian online dan tindakan ilegal lainnya</li>
            <li>Aktivitas lain yang melanggar hukum atau merugikan layanan kami</li>
         </ul>
         <p>Pelanggaran terhadap larangan ini dapat mengakibatkan pemblokiran akun tanpa pemberitahuan sebelumnya.</p>

         <h3>Hak Kekayaan Intelektual</h3>
         <p>1. Seluruh konten dan perangkat lunak yang terkait dengan layanan GGCLOUD dilindungi hak cipta.</p>
         <p>2. Pengguna dilarang memperbanyak, mendistribusikan, atau memodifikasi materi dari GGCLOUD tanpa izin tertulis.</p>

         <h3>Privasi dan Keamanan</h3>
         <p>1. Kami menghargai privasi Anda dan akan melindungi data pribadi sesuai dengan kebijakan privasi kami.</p>
         <p>2. Anda bertanggung jawab atas keamanan akun Anda, termasuk menjaga kerahasiaan kata sandi.</p>

         <h3>Pembatasan Tanggung Jawab</h3>
         <p>1. GGCLOUD tidak bertanggung jawab atas kerugian yang timbul akibat penggunaan layanan kami, kecuali ditetapkan lain oleh hukum.</p>
         <p>2. Kami tidak menjamin bahwa layanan akan selalu tersedia tanpa gangguan atau bebas dari kesalahan.</p>

         <h3>Perubahan Layanan dan Syarat</h3>
         <p>1. GGCLOUD berhak untuk mengubah, menangguhkan, atau menghentikan layanan kapan saja tanpa pemberitahuan.</p>
         <p>2. Kami juga berhak mengubah syarat dan ketentuan ini. Perubahan akan diumumkan melalui situs web kami.</p>

         <h3>Penyelesaian Sengketa</h3>
         <p>Segala sengketa yang timbul dari penggunaan layanan kami akan diselesaikan secara musyawarah atau melalui jalur hukum yang berlaku di Indonesia.</p>

         <h3>Kontak</h3>
         <p>Jika Anda memiliki pertanyaan atau keluhan terkait layanan kami, silakan hubungi tim dukungan pelanggan GGCLOUD melalui informasi kontak yang tersedia di situs web kami.</p>

         <p>Dengan menggunakan layanan GGCLOUD, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan ini.</p>
      </div>
   </div>
</div>
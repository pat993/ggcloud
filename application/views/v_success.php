<?php if (null !== $this->session->flashdata('success')) { ?>
   <div style="max-width: 800px; margin: auto" class="page-content pb-0">

      <div data-card-height="cover" class="card">
         <div class="card-top notch-clear">
            <div class="d-flex">
               <a href="login" class="me-auto icon icon-m"><i class="font-14 fa fa-arrow-left color-theme"></i></a>
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

               <p class="text-center">
                  Berhasil registrasi, silahkan cek email untuk aktivasi akun kamu
               </p>

               <div class="text-center mb-5 mt-4">
                  <a href="../login" class="font-12">Kembali ke login</a>
               </div>
            </div>
         </div>
      </div>

   </div>
   <!-- End of Page Content-->
<?php
} else {
   header('location: ../login');
}
?>
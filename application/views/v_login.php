<div style="max-width: 600px; margin: auto" class="page-content pb-0">

   <div data-card-height="cover" class="card">
      <div class="card-top notch-clear">
         <div class="d-flex">
            <a href="#" data-toggle-theme class="show-on-theme-light ms-auto icon icon-m"><i class="font-12 fa fa-moon color-theme"></i></a>
            <a href="#" data-toggle-theme class="show-on-theme-dark ms-auto icon icon-m"><i class="font-12 fa fa-lightbulb color-yellow-dark"></i></a>
         </div>
      </div>
      <div class="card-center">
         <div class="ps-5 pe-5">
            <div style="padding-bottom: 20px" class="text-center">
               <img class="text-center" src="images/ggcloud.png" alt="">
            </div>
            <h1 class="text-center font-800 font-40 mb-1">Sign In</h1>
            <p class="text-center font-12">Sign in to continue</p>

            <form action="<?= base_url('login/aksi_login'); ?>" method="POST">
               <div class="input-style no-borders has-icon validate-field">
                  <i class="fa fa-user"></i>
                  <input type="text" class="form-control validate-name" placeholder="Username" name="username" value="<?php if (isset($_COOKIE['username'])) echo ($_COOKIE['username']); ?>" required>
               </div>

               <div class="input-style no-borders has-icon validate-field mt-4">
                  <i class="fa fa-lock"></i>
                  <input type="password" class="form-control validate-password" placeholder="Password" name="password" required>
               </div>

               <div class="d-flex mt-4 mb-4">
                  <div class="w-50 font-11 pb-2 text-start"><a href="register">Create Account</a></div>
               </div>

               <button style="width: 100%" type="submit" name="signin" class="back-button btn btn-full btn-m shadow-large rounded-sm text-uppercase font-700 bg-highlight">LOGIN</button>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- End of Page Content-->
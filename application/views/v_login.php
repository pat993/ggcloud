<div style="max-width: 600px; margin: auto" class="page-content pb-0">

   <div data-card-height="cover" class="card">
      <div class="card-center">
         <div class="ps-5 pe-5">
            <div style="padding-bottom: 20px" class="text-center">
               <img class="text-center" src="images/ggcloud.png" alt="">
            </div>
            <h1 class="text-center font-200 font-40 mb-4">Account Log In</h1>
            <!-- <p class="text-center font-12">Sign in to continue</p> -->

            <form action="<?= base_url('login/aksi_login'); ?>" method="POST">
               <div class="input-style has-icon">
                  <i class="fa fa-user"></i>
                  <input type="text" class="form-control validate-name" placeholder="Username" name="username" value="<?php if (isset($_COOKIE['username'])) echo ($_COOKIE['username']); ?>" required>
               </div>

               <div class="input-style has-icon mt-4">
                  <i class="fa fa-lock"></i>
                  <input type="password" class="form-control validate-password" placeholder="Password" name="password" required>
               </div>
               <button style="width: 100%" type="submit" name="signin" class="btn btn-full btn-m rounded-sm text-uppercase font-200 btn-grad mt-4">LOGIN</button>

               <div class="text-center mb-5 mt-5">
                  <a href="register" class="font-12">Create Account</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- End of Page Content-->
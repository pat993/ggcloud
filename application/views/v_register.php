<div style="max-width: 600px; margin: auto" class="page-content pb-0">

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

            <form action="<?= base_url('register/aksi_register'); ?>" method="POST">

               <div class="input-style no-borders has-icon validate-field">
                  <i class="fa fa-user"></i>
                  <input type="text" class="form-control validate-name" id="username" placeholder="Username" name="username" required>
               </div>
               <div class="input-style no-borders has-icon validate-field mt-2">
                  <i class="fa fa-at"></i>
                  <input type="email" class="form-control validate-name" id="email" placeholder="Email" name="email" required>
               </div>
               <div class="input-style no-borders has-icon validate-field mt-2">
                  <i class="fa fa-lock"></i>
                  <input type="password" class="form-control validate-text" placeholder="Choose a Password" name="password1" minlength="8" required>
               </div>
               <div class="input-style no-borders has-icon validate-field mt-2">
                  <i class="fa fa-lock"></i>
                  <input type="password" class="form-control validate-text" placeholder="Confirm your Password" name="password2" minlength="8" required>
               </div>

               <button style="width: 100%" type="submit" name="signup" class="btn btn-full btn-m rounded-sm text-uppercase font-200 btn-grad">Create Account</button>

               <div class="text-center mb-5 mt-5">
                  <a href="login" class="font-12">Already Registered? Sign in Here</a>
               </div>
            </form>
         </div>
      </div>
   </div>

</div>
<!-- End of Page Content-->
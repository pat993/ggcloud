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
            <h1 class="text-center font-200 font-40 mb-4">Reset Password</h1>
            <!-- <p class="text-center font-12">Register new member</p> -->

            <form action="<?= base_url('forget/reset_process/' . $auth_code); ?>" method="POST">

               <div class="input-style no-borders has-icon mt-5" style="position: relative;">
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


               <div class="g-recaptcha mt-4" data-sitekey="6Lf72FUpAAAAAB15KrmicPBHlE7AtktemGLWzyyq" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>

               <button style="width: 100%" type="submit" name="signup" class="btn btn-full btn-m rounded-sm text-uppercase font-200 btn-grad">Submit</button>

               <div class="text-center mb-5 mt-2">
                  <a href="#" onclick="window.location.href='/login';return true;" class="font-12">Already Registered? Sign in Here</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- End of Page Content-->
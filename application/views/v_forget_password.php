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
            <h1 class="text-center font-200 font-40 mb-4">Forget Password</h1>
            <!-- <p class="text-center font-12">Register new member</p> -->

            <form action="<?= base_url('forget/reset/'); ?>" method="POST">

               <div class="input-style no-borders has-icon mt-5">
                  <i class="fa fa-at"></i>
                  <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="" required>
               </div>

               <div class="g-recaptcha mt-4" data-sitekey="6LcJIZwqAAAAAGGvyV5CWvUqGVsAlzBK4zFNbJBV" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>

               <button style="width: 100%" type="submit" name="signup" class="btn btn-full btn-m rounded-sm text-uppercase font-200 btn-grad">Send Reset Link</button>

               <div class="text-center mb-5 mt-4">
                  <a href="#" onclick="window.location.href='/login';return true;" class="font-12">Already Registered? Sign in Here</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- End of Page Content-->
<!-- header and footer bar go here-->
<div class="header header-fixed header-logo-center">
   <a href="dashboard" class="header-title"> <img style="width: 30px" src="<?= base_url(); ?>images/ggcloud.png" alt=""> GGCLOUD</a>
   <!-- <a href="#" class="back-button header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a> -->
   <!-- <a href="#" data-toggle-theme class="header-icon header-icon-4"><i class="fas fa-lightbulb"></i></a> -->
   <a href="#" data-menu="menu-sidebar-left-1" class="header-icon header-icon-1"><i class="fas fa-bars"></i></a>
</div>

<!-- Sidebar 1 -->
<div id="menu-sidebar-left-1" class="menu menu-box-left menu-box-detached menu-sidebar" data-menu-width="310">
   <div class="sidebar-content">
      <div class="bg-theme mx-3 rounded-m shadow-m my-3">
         <div class="d-flex px-2 pb-2 pt-2">
            <div class="align-self-center">
               <a href="#"><img src="<?= base_url(); ?>images/ggcloud.png" width="40" class="rounded-sm" alt="img"></a>
            </div>
            <div class="ps-2 align-self-center">
               <h5 class="ps-1 mb-1 pt-1 line-height-xs font-17"><?= $this->session->userdata('username'); ?></h5>
               <h6 class="ps-1 mb-0 font-400 opacity-40 font-12"><?= $this->session->userdata('email'); ?></h6>
            </div>
            <div class="ms-auto">
               <a href="#" data-bs-toggle="dropdown" class="icon icon-m ps-3"><i class="fa fa-ellipsis-v font-18 color-theme"></i></a>
               <div class="dropdown-menu bg-transparent border-0 mb-n5">
                  <div class="card card-style rounded-m shadow-xl me-1">
                     <div class="list-group list-custom-small list-icon-0 px-3 mt-n1">
                        <a href="profile" class="mb-n2 mt-n1">
                           <span>Your Profile</span>
                           <i class="fa fa-angle-right"></i>
                        </a>
                        <a href="signout" class="mb-n1">
                           <span>Sign Out</span>
                           <i class="fa fa-angle-right"></i>
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="card card-style">
         <div class="content my-0">
            <h5 class="font-700 text-uppercase opacity-40 font-12 pt-2 mb-0">Navigation</h5>
            <div class="list-group list-custom-small list-icon-0">
               <a href="<?= base_url(); ?>">
                  <i class="fa font-12 fa-home gradient-green rounded-sm color-white"></i>
                  <span>Homepage</span>
                  <i class="fa fa-angle-right"></i>
               </a>
               <a href="purchase_history">
                  <i class="fa font-12 fa-file gradient-blue rounded-sm color-white"></i>
                  <span>Purchase History</span>
                  <i class="fa fa-angle-right"></i>
               </a>
               <a href="pricing">
                  <i class="fas font-12 fa-dollar-sign gradient-yellow rounded-sm color-white"></i>
                  <span>Pricing</span>
                  <i class="fa fa-angle-right"></i>
               </a>
            </div>
         </div>
      </div>

      <div class="card card-style">
         <div class="content my-0">
            <div class="list-group list-custom-small list-icon-0">
               <a data-menu="menu-add-device" href="#">
                  <i class="fas font-12 fa-mobile-alt gradient-green rounded-sm color-white"></i>
                  <span>Add Device</span>
                  <i class="fa fa-angle-right"></i>
               </a>
            </div>
         </div>
      </div>

      <?php
      if ($_SESSION["username"] == "admin") { ?>
         <div class="card card-style">
            <div class="content my-0">
               <h5 class="font-700 text-uppercase opacity-40 font-12 pt-2 mb-0">Admin Panel</h5>
               <div class="list-group list-custom-small list-icon-0">
                  <a href="<?= base_url(); ?>device_manager">
                     <i class="fas font-12 fa-tablet-alt gradient-red rounded-sm color-white"></i>
                     <span>Device Manager</span>
                     <i class="fa fa-angle-right"></i>
                  </a>
                  <a href="<?= base_url(); ?>voucher_manager">
                     <i class="fas font-12 fa-credit-card gradient-magenta rounded-sm color-white"></i>
                     <span>Voucher Manager</span>
                     <i class="fa fa-angle-right"></i>
                  </a>
                  <a href="<?= base_url(); ?>user_manager">
                     <i class="fas font-12 fa-id-card-alt gradient-orange rounded-sm color-white"></i>
                     <span>User Manager</span>
                     <i class="fa fa-angle-right"></i>
                  </a>
               </div>
            </div>
         </div>
      <?php
      } ?>

   </div>
   <div class="position-sticky w-100 bottom-0 end-0 pb-1">
      <div class="card card-style mb-3">
         <div class="content my-0 py-">
            <div class="list-group list-custom-small list-icon-0">
               <a href="#" data-toggle-theme data-trigger-switch="switch-dark2-mode" class="border-0">
                  <i class="fa font-12 fa-moon gradient-yellow color-white rounded-sm"></i>
                  <span>Dark Mode</span>
                  <div class="custom-control ios-switch">
                     <input data-toggle-theme type="checkbox" class="ios-input" id="switch-dark2-mode">
                     <label class="custom-control-label" for="switch-dark2-mode"></label>
                  </div>
                  <i class="fa fa-angle-right"></i>
               </a>
            </div>
         </div>
      </div>
   </div>
</div>
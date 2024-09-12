<!--start of page content, add your stuff here-->
<div style="max-width: 800px; margin: auto" class="page-content header-clear-medium">

   <!-- <div style="background-color: #FD8A8A; color: white; margin: 5px; padding: 5px 15px 5px 15px; border-radius: 10px; margin-bottom: 15px;">
      Perhatian : untuk saat ini player belum support audio
   </div> -->

   <div class="card card-style preload-img mb-2" data-src="images/banner-ggc_min.png" data-card-height="150">
      <div class="card-center ms-3">
         <h1 class="color-white mb-0 font-200">My Device</h1>
         <!-- <p class="color-white mt-n1 mb-0">Select device to launch</p> -->
      </div>
      <div class="card-center me-3">
         <a href="#" data-menu="menu-submit-voucher" class="btn btn-m float-end rounded-m text-uppercase font-700 btn-grad">Voucher Claim</a>
      </div>
      <div class="card-overlay bg-black opacity-80"></div>
   </div>

   <div class="ms-4 mt-0 mb-0 opacity-70">
      <small><i class="fas fa-long-arrow-alt-right"></i> Swipe untuk melihat perangkat lainnya</small>
   </div>

   <div class="splide double-slider slider-no-arrows slider-no-dots" id="double-slider-home-2">
      <div class="splide__track">
         <div class="splide__list">

            <?php
            foreach ($device_list as $device) {
            ?>
               <?php

               $start = strtotime(date('Y-m-d H:i:s'));
               $end = strtotime($device['end_date_kompensasi']);

               $diff = $end - $start;

               $jam = floor($diff / (60 * 60));

               $start_dur = strtotime($device['start_date']);

               $dur = $end - $start_dur;

               $dur_jam = floor($dur / (60 * 60));

               $jam_percent = ($jam / $dur_jam) * 100;

               ?>
               <div class="splide__slide">
                  <div data-card-height="180" class="card mx-3 rounded-m shadow-l card-style preload-img" data-src="images/gforce_v1.png">
                     <div class="card-bottom ms-3 mb-3" style="color: white">
                        <small>HP</small>
                        <div class="progress mt-0 mb-1" style="height:3px; width: 40px">
                           <div class="progress-bar border-0 bg-blue-purple text-start ps-1" role="progressbar" style="width: <?= $jam_percent; ?>%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="<?= $dur_jam; ?>">
                           </div>
                        </div>
                     </div>
                     <div class="card-bottom me-4 mb-3">
                        <a href="#" onclick="window.open('player/go/<?= $device['device_identifier']; ?>');return true;" class="float-end"><i style="font-size: 40px" class="color-white fas fa-play"></i></a>
                     </div>
                     <div class="card-top ms-3 mt-3">
                        <h3 class="color-white font-200 mb-0"><?= $device['custom_name']; ?></h3>
                        <!-- <p class="color-white font-11 mt-n1 mb-2">Android 8, Octa-core, 4GB RAM, 64GB ROM</p> -->
                     </div>

                     <div class="card-top me-4 mt-3">
                        <button style="width: 10px; padding:0" class="btn color-white mt-0 float-end" data-menu="menu-device-info-<?= $device['id'] ?>"><i class="fas fa-ellipsis-v"></i></button>
                     </div>
                     <!-- <div class="card-top">
                        <p class="text-end me-3 font-10 font-500 opacity-50 color-white mt-3"><button data-menu="menu-edit-device"><i class="fas fa-pencil"></i></button></p>
                     </div> -->
                     <div class="card-overlay bg-black opacity-70"></div>
                  </div>
               </div>
            <?php
            } ?>
            <div class="splide__slide">
               <div data-card-height="180" class="card mx-3 rounded-m shadow-l">
                  <div class="text-center card-center">
                     <a href='<?= base_url(); ?>pricing' class="text-center color-white font-40"><i class="fas fa-plus"></i></a>
                  </div>
                  <div class="card-overlay bg-black opacity-100"></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div id="menu-submit-voucher" class="menu menu-box-bottom menu-box-detached rounded-m" style="max-width: 700px; margin: auto">
   <div class="menu-title mb-2">
      <h1 class="font-200 mb-2">Voucher Claim</h1>
      <p>Tambah perangkat menggunakan voucher</p>
      <!-- <p>Claim voucher to add device</p> -->
   </div>
   <div style="margin: 0px 20px 0px 20px" class="content">
      <form action="<?= base_url() ?>dashboard/voucher_claim/" method="POST">
         <div class="input-style input-style-always-active validate-field no-borders no-icon">
            <input style="border-radius: 10px; background-color: #F8F8F8" class="text-center" type="text" name="txt_voucher_code" autocomplete="off" placeholder="Input kode voucher disini" required>
            <!-- <label for="txt_voucher-code" class="color-theme opacity-20 text-uppercase font-700 font-10 mt-1">Voucher Code</label> -->
         </div>
         <?php
         if (null !== $this->session->userdata('err_count_v')) {
            if ($this->session->userdata('err_count_v') == 1) {
         ?>

               <div class="g-recaptcha " data-sitekey="6Lf72FUpAAAAAB15KrmicPBHlE7AtktemGLWzyyq" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>

         <?php
            }
         }
         ?>
         <button href="#" type="submit" style="width: 100%" class="btn btn-full btn-m rounded-m btn-grad font-700 text-uppercase mb-4 mt-4"><i class="far fa-check-circle"></i></button>
      </form>
   </div>
</div>

<?php
foreach ($device_list as $result) {
   $start = strtotime(date('Y-m-d H:i:s'));
   $end = strtotime($result['end_date_kompensasi']);

   $diff = $end - $start;

   $jam = floor($diff / (60 * 60));
?>
   <div id="menu-device-info-<?= $result['id'] ?>" class="menu menu-box-modal rounded-m" style="max-width: 600px; width: 100%">
      <div class="menu-title">
         <h1>
            Device Info <button class="close-menu float-right me-4 opacity-50"><i class="fa fa-times"></i></button>
         </h1>
      </div>

      <div class="content mt-2 pb-4">
         <div id="a-device-info">
            <table class="table table-borderless rounded-sm mb-0" style="overflow: hidden;" id="tb_device">
               <tr>
                  <td style="width: 150px;">
                     <p>Nama Perangkat</p>
                  </td>
                  <td style="position: relative;"><span><?= $result["custom_name"]; ?>
                        <button data-menu="menu-edit-device-<?= $result['id'] ?>" style="position:absolute; right:15px"> <i class="fa fa-angle-right"></i>
                        </button>
                     </span>
                  </td>
               </tr>
               <tr>
                  <td style="width: 150px">
                     <p>Tanggal Mulai</p>
                  </td>
                  <td><span><?= date('d-m-Y', strtotime($result["start_date"])); ?></span></td>
               </tr>
               <tr>
                  <td style="width: 150px">
                     <p>Tanggal Selesai</p>
                  </td>
                  <td><span><?= date('d-m-Y', strtotime($result["end_date_kompensasi"])); ?></span></td>
               </tr>
               <tr>
                  <td style="width: 150px">
                     <p>Masa Aktif</p>
                  </td>
                  <td><span><?= $result['masa_aktif']; ?> Jam</span>
                     <?php if ($result['kompensasi'] != 0) { ?>
                        <span style="border:1px solid green; padding: 1px 5px 1px 5px; color: green; border-radius: 20px"> + <?= $result['kompensasi']; ?> Jam</span>
                     <?php
                     } ?>
                  </td>
               </tr>
            </table>
            <hr>

            <form action="<?= base_url() ?>dashboard/voucher_extend/" method="POST">
               <input type="hidden" value="<?= $result["id"]; ?>" name="txt_assign_id">
               <div class="ms-2 mt-1 opacity-80">
                  <b>Tambah durasi</b>
               </div>
               <div class="input-style input-style-always-active validate-field no-borders no-icon rounded-xl">
                  <input style="background-color: #F8F8F8; border-radius: 10px" class="text-center" type="text" name="txt_voucher_ext" placeholder="Input kode voucher disini" autocomplete="off" required>
               </div>
               <button href="#" type="submit" style="width: 100%" class="btn btn-full btn-m rounded-m btn-grad font-700 text-uppercase mb-0 mt-2"><i class="far fa-check-circle"></i></button>
               <div style="font-size: 10px; border-bottom: 0" class="opacity-60 ms-2 mt-2">
                  <i class="fas fa-info-circle"></i> Lakukan penambahan durasi perangkat sebelum masa aktif berakhir untuk menghindari penghapusan data
               </div>
            </form>
         </div>
      </div>
   </div>

   <div style="max-width: 700px; margin: auto" id="menu-edit-device-<?= $result['id'] ?>" class="menu menu-box-top menu-box-detached rounded-m">
      <div class="menu-title">
         <h1>Rename Device</h1>
         <!-- <p>Claim voucher to add device</p> -->
      </div>
      <div class="content">
         <form action="<?= base_url() ?>dashboard/rename_device/" method="POST">
            <div class="input-style input-style-always-active no-borders no-icon">
               <input type="hidden" name="txt_assign_id" value="<?= $result["id"]; ?>">
               <input maxlength="10" style="border-radius: 10px; background-color: #F8F8F8" class="text-center" type="text" name="txt_rename_device" value="<?= $result["custom_name"]; ?>" autocomplete="off" required>
               <!-- <label for="txt_voucher-code" class="color-theme opacity-20 text-uppercase font-700 font-10 mt-1">Voucher Code</label> -->
            </div>
            <button href="#" type="submit" style="width: 100%" class="btn btn-full btn-m rounded-m btn-grad font-700 text-uppercase mb-4 mt-4"><i class="far fa-check-circle"></i></button>
         </form>
      </div>
   </div>
<?php
} ?>


<script>

</script>
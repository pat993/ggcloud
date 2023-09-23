  <?php
   function voucherExpired()
   {
      $date = new DateTime();
      $date->add(new DateInterval('P7D'));

      return $date->format('Y-m-d H:m:s');
   }

   function randomString($length = 16)
   {
      // Set the chars
      $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

      // Count the total chars
      $totalChars = strlen($chars);

      // Get the total repeat
      $totalRepeat = ceil($length / $totalChars);

      // Repeat the string
      $repeatString = str_repeat($chars, $totalRepeat);

      // Shuffle the string result
      $shuffleString = str_shuffle($repeatString);

      // get the result random string
      return substr($shuffleString, 1, $length);
   }

   ?>

  <div style="max-width: 1300px; min-height: 100vh; margin: auto; padding: 20px; background-color: white;" class="page-content header-clear-medium">

     <div style="min-height: 80vh">
        <div class="content mb-2">
           <div class="row mb-3">
              <div class="col-sm-4 ml-5">
                 <h1 style="font-size: 38px;">
                    <i class="fas fa-ticket" style="color: #176B87"></i> VOUCHER MANAGER
                 </h1>
                 <div class="ps-1 opacity-80">Voucher management dashboard</div>
              </div>
              <div class="col-sm-2">
                 <div class="card bg-light mb-1" style="width: 100%">
                    <b class="text-center" style="background-color: #176B87; color: white">Total Voucher</b>
                    <h1 class="text-center"><?= $voucher_count; ?></h1>
                 </div>
              </div>
              <div class="col-sm-2">
                 <div class="card bg-light mb-1" style="width: 100%">
                    <b class="text-center" style="background-color: #176B87; color: white">Voucher Digunakan</b>
                    <h1 class="text-center"><?= $voucher_used_count; ?></h1>
                 </div>
              </div>
              <div class="col-sm-2">
                 <div class="card bg-light mb-1" style="width: 100%">
                    <b class="text-center" style="background-color: #176B87; color: white">Voucher Tersedia</b>
                    <h1 style="color: green" class="text-center"><?= $device_count ?></h1>
                 </div>
              </div>
              <div class="col-sm-2">
                 <a href="#" data-menu="menu-create-voucher" class="btn btn-m btn-full mb-3 text-uppercase font-900 shadow-s bg-green-dark"><i class="fas fa-cube"></i> Generate Voucher</a>
              </div>
           </div>

           <table class="table table-borderless table-striped shadow-xs" id="tb_device">
              <thead>
                 <tr class="bg-night-light">
                    <th scope="col" class="color-white">#</th>
                    <th scope="col" class="color-white">Kode Voucher</th>
                    <th scope="col" class="color-white">Status</th>
                    <th scope="col" class="color-white">Paket</th>
                    <th scope="col" class="color-white">Email Penerima</th>
                    <th scope="col" class="color-white">Action</th>
                 </tr>
              </thead>
              <tbody>
                 <?php
                  foreach ($voucher_list as $result) {
                  ?>
                    <tr>
                       <th scope="row"><?= $result["id"]; ?></th>
                       <td><?= $result["kode_voucher"]; ?></td>
                       <th scope="row"><?= $result["voucher_status"]; ?></th>
                       <td><?= $result["keterangan"]; ?></td>
                       <th scope="row"><?= $result["email_tujuan"]; ?></td>
                       <td>
                          <div class="dropleft">
                             <button class="btn btn-light btn-xs" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item" data-menu="menu-voucher-detail" onclick="a_voucher_detail(<?= $result['voucher_id']; ?>)"><i class="fas fa-info-circle"></i> Detail</button>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= base_url() ?>voucher_manager/delete_voucher/<?= $result["voucher_id"]; ?>" onclick="return confirm('Are you sure you want to delete this item?')"><i class="fas fa-trash"></i> Delete</a>
                             </div>
                          </div>
                       </td>
                    </tr>
                 <?php
                  } ?>
              </tbody>
           </table>
        </div>
     </div>
  </div>

  <div id="menu-voucher-detail" class="menu menu-box-modal rounded-m" style="max-width: 600px; width: 100%">
     <div class="content">
        <div class="ms-3 me-3 pb-3" id="voucher-detail">
           <div class="d-flex">
              <div class="mb-2">
                 <h1><i class="fas fa-info-circle"></i> Voucher Info</h1>
              </div>
           </div>
           <div id="a-voucher-detail">
              Loading....
           </div>
        </div>
     </div>
  </div>

  <div id="menu-create-voucher" class="menu menu-box-top menu-box-detached rounded-m" style="max-width: 900px; margin: auto">
     <div class="content">
        <div class="d-flex ms-3 me-3 mb-3">
           <div>
              <h1><i class="fas fa-ticket"></i> Generate Voucher</h1>
           </div>
        </div>
        <div class="ms-3 me-3 mb-4">
           <form method="POST" action="<?= base_url() ?>voucher_manager/add_voucher/">
              <input type="hidden" class="form-control" id="txt_kode-voucher" name="txt_kode_voucher" value="<?= randomString(); ?>" readonly>
              <!-- <label for="txt_kode-voucher" style="background-color: transparent" class="color-green-dark font-600">Kode Voucher</label> -->

              <div class="input-style has-borders no-icon mb-4 input-style-active">
                 Tipe Paket :
                 <select class="form-select" name="txt_tipe" id="txt_tipe" onchange="if (this.selectedIndex) get_daftar_paket();" required>
                    <option selected disabled>-- Pilih Salah Satu --</option>
                    <option value="Baru" selected>Baru</option>
                    <option value="Perpanjang">Perpanjang</option>
                 </select>
              </div>

              <div class="input-style has-borders no-icon mb-4 input-style-active">
                 Jenis Paket :
                 <select class="form-select" name="txt_jenis" id="txt_jenis" onchange="if (this.selectedIndex) get_daftar_paket();" required>
                    <option selected disabled>-- Pilih Salah Satu --</option>
                    <?php foreach ($jenis_paket_list as $jenis_paket_list_p) { ?>
                       <option value="<?= $jenis_paket_list_p['id']; ?>"><?= $jenis_paket_list_p['nama_paket']; ?></option>
                    <?php
                     } ?>
                 </select>
              </div>

              <div class="input-style has-borders no-icon mb-4 input-style-active">
                 <div id="div-daftar-paket">
                    Paket :
                    <select class="form-select" name="txt_paket_id" required>
                       <option selected disabled>-- Pilih Salah Satu --</option>
                    </select>
                 </div>
              </div>

              <div class="input-style has-borders no-icon mb-4 input-style-active">
                 Kode Pemesanan :
                 <input type="text" class="form-control" id="txt_order_id" name="txt_order_id" value="" required>
                 <!-- <label for="txt_ip" style="background-color: transparent" class="color-green-dark font-600">Expired</label> -->
              </div>

              <div class="input-style has-borders no-icon mb-4 input-style-active">
                 Email :
                 <input type="text" class="form-control" id="txt_email" name="txt_email" value="" required>
                 <!-- <label for="txt_ip" style="background-color: transparent" class="color-green-dark font-600">Expired</label> -->
              </div>

              <div class="input-style has-borders no-icon mb-4 input-style-active">
                 Vocher Expired :
                 <input type="text" class="form-control" id="txt_ip" name="txt_expired" value="<?= voucherExpired(); ?> WIB" readonly>
                 <!-- <label for="txt_ip" style="background-color: transparent" class="color-green-dark font-600">Expired</label> -->
              </div>
              <button type="submit" class="btn btn-full btn-m rounded-m bg-blue-dark font-700 text-uppercase ms-auto"><i class="fas fa-save"></i> Simpan</button>
           </form>
        </div>
     </div>
  </div>
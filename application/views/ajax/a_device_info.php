<?php
foreach ($device_info as $result) {

   $start = strtotime(date('Y-m-d H:i:s'));
   $end = strtotime($result['end_date']);

   $diff = $end - $start;

   $jam = floor($diff / (60 * 60));
?>

   <table class="table table-borderless table-striped rounded-sm shadow-xs mb-0" style="overflow: hidden;" id="tb_device">
      <tr>
         <td style="width: 150px">
            <p class="opacity-50">Nama Perangkat</p>
         </td>
         <td><?= $result["custom_name"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">
            <p class="opacity-50">Tanggal Mulai</p>
         </td>
         <td><?= $result["start_date"]; ?> WIB</td>
      </tr>
      <tr>
         <td style="width: 150px">
            <p class="opacity-50">Tanggal Akhir</p>
         </td>
         <td><?= $result["end_date"]; ?> WIB</td>
      </tr>
      <tr>
         <td style="width: 150px">
            <p class="opacity-50">Masa Aktif</p>
         </td>
         <td><?= $jam; ?> Jam</td>
      </tr>
   </table>
   <div style="font-size: 10px; border-bottom: 0" class="opacity-60 ms-2 mt-2">
      <i class="fas fa-info-circle"></i> Lakukan penambahan durasi perangkat sebelum masa aktif berakhir untuk menghindari penghapusan data
   </div>

   <form action="<?= base_url() ?>dashboard/voucher_extend/" method="POST">
      <input type="hidden" value="<?= $result["id"]; ?>" name="txt_assign_id">
      <div class="ms-2 mt-3 opacity-80">
         <b>Tambah durasi</b>
      </div>
      <div class="input-style input-style-always-active validate-field no-borders no-icon rounded-xl">
         <input style="background-color: #F8F8F8; border-radius: 10px" class="text-center" type="text" name="txt_voucher_ext" placeholder="Input kode voucher disini" autocomplete="off" required>
      </div>
      <button href="#" type="submit" style="width: 100%" class="btn btn-full btn-m rounded-m bg-green-dark font-700 text-uppercase mb-0 mt-2"><i class="far fa-check-circle"></i></button>
   </form>
<?php
} ?>
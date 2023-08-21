<?php
foreach ($voucher_detail as $result) {
?>
   <table class="table table-borderless table-striped rounded-sm mb-4" style="overflow: hidden;" id="tb_voucher">
      <tr>
         <td style="width: 150px">Kode Voucher</td>
         <td><?= $result["kode_voucher"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Paket</td>
         <td><?= $result["keterangan"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Status</td>
         <td><?= $result["voucher_status"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Kode pemesanan</td>
         <td><?= $result["order_id"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Email Penerima</td>
         <td><?= $result["email_tujuan"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Tanggal Generated</td>
         <td><?= $result["tanggal_generate"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Tanggal Digunakan</td>
         <td><?= $result["tanggal_digunakan"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">User ID</td>
         <td><?= $result["user_id"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Device ID</td>
         <td><?= $result["device_id"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Assign Status</td>
         <td><?= $result["assign_status"]; ?></td>
      </tr>
   </table>
<?php
} ?>
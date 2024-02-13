<?php
foreach ($voucher_detail as $result) {
?>
   <table class="table table-borderless rounded-sm mb-4" style="overflow: hidden;" id="tb_voucher">
      <tr>
         <td style="width: 150px; color: #B4B4B8">Kode Voucher</td>
         <td><?= $result["kode_voucher"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Paket</td>
         <td><?= $result["keterangan"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Status</td>
         <td><?= $result["voucher_status"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Kode pemesanan</td>
         <td><?= $result["order_id"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Email Penerima</td>
         <td><?= $result["email_tujuan"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Tanggal Generated</td>
         <td><?= $result["tanggal_generate"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Tanggal Digunakan</td>
         <td><?= $result["tanggal_digunakan"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">User ID</td>
         <td><?= $result["user_id"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Device ID</td>
         <td><?= $result["device_id"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Assign Status</td>
         <td><?= $result["assign_status"]; ?></td>
      </tr>
   </table>
<?php
} ?>
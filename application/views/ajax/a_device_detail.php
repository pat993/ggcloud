<?php
foreach ($device as $result) {
?>
   <table class="table table-borderless rounded-sm mb-4" style="overflow: hidden;" id="tb_device">
      <tr>
         <td style="width: 150px; color: #B4B4B8">ID</td>
         <td><?= $result["device_id"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">IP</td>
         <td><?= $result["ip"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Port</td>
         <td><?= $result["port"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Name</td>
         <td><?= $result["name"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Type</td>
         <td><?= $result["type"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Status</td>
         <td><?= $result["device_status"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Last Assign</td>
         <td><?= $result["end_date"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px; color: #B4B4B8">Last User</td>
         <td><?= $result["email"]; ?></td>
      </tr>
   </table>
<?php
} ?>
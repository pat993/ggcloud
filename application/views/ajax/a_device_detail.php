<?php
foreach ($device as $result) {
?>
   <table class="table table-borderless table-striped rounded-sm shadow-xs mb-4" style="overflow: hidden;" id="tb_device">
      <tr>
         <td style="width: 150px">ID</td>
         <td><?= $result["device_id"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">IP</td>
         <td><?= $result["ip"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Port</td>
         <td><?= $result["port"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Name</td>
         <td><?= $result["name"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Type</td>
         <td><?= $result["type"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Status</td>
         <td><?= $result["device_status"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Last Assign</td>
         <td><?= $result["end_date"]; ?></td>
      </tr>
      <tr>
         <td style="width: 150px">Last User</td>
         <td><?= $result["email"]; ?></td>
      </tr>
   </table>
<?php
} ?>
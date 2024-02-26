<?php
foreach ($device as $result) {
?>

   <table class="table table-borderless rounded-sm" style="overflow: hidden;" id="tb_device">
      <tr>
         <td style="width: 70px; vertical-align: middle;">ID</td>
         <td>
            <div class="input-style has-borders input-style-active">
               <input type="text" value="<?= $result["device_id"]; ?>" name="txt_id" readonly>
            </div>
         </td>
      </tr>
      <tr>
         <td style="width: 70px; vertical-align: middle">Remote IP Address</td>
         <td>
            <div class="input-style has-borders input-style-active">
               <input type="text" value="<?= $result["ip"]; ?>" name="txt_ip">
            </div>
         </td>
      </tr>
      <tr>
         <td style="width: 70px; vertical-align: middle">Port</td>
         <td>
            <div class="input-style has-borders input-style-active">
               <input type="text" value="<?= $result["port"]; ?>" name="txt_port">
            </div>
         </td>
      </tr>
      <tr>
         <td style="width: 70px; vertical-align: middle">Port Forward</td>
         <td>
            <div class="input-style has-borders input-style-active">
               <input type="text" value="<?= $result["port_forward"]; ?>" name="txt_port_f">
            </div>
         </td>
      </tr>
      <tr>
         <td style="width: 70px; vertical-align: middle">Local IP Address</td>
         <td>
            <div class="input-style has-borders input-style-active">
               <input type="text" value="<?= $result["ip_local"]; ?>" name="txt_ip_local">
            </div>
         </td>
      </tr>
      <tr>
         <td style="width: 70px; vertical-align: middle">Name</td>
         <td>
            <div class="input-style has-borders input-style-active">
               <input type="text" value="<?= $result["name"]; ?>" name="txt_name">
            </div>
         </td>
      </tr>
      <tr>
         <td style="width: 70px; vertical-align: middle">Type</td>
         <td>
            <div class="input-style has-borders input-style-active">
               <input type="text" value="<?= $result["type"]; ?>" name="txt_type">
            </div>
         </td>
      </tr>
   </table>

<?php
} ?>
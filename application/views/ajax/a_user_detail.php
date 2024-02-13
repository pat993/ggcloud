<?php
foreach ($user as $user_r) {
?>
   <table class="table table-borderless rounded-sm" style="overflow: hidden;" id="tb_device">
      <tr>
         <th style="width: 150px">ID</th>
         <td><?= $user_r["id"]; ?></td>
      </tr>
      <tr>
         <th style="width: 150px">Username</th>
         <td><?= $user_r["username"]; ?></td>
      </tr>
      <tr>
         <th style="width: 150px">Password</th>
         <td><?= $user_r["password"]; ?></td>
      </tr>
      <tr>
         <th style="width: 150px; vertical-align:middle">Email</th>
         <td>
            <div class="input-style has-borders input-style-active">
               <input type="text" value="<?= $user_r["email"]; ?>" name="txt_email">
            </div>
         </td>
      </tr>
      <tr>
         <th style="width: 150px; vertical-align:middle">No HP</th>
         <td>
            <div class="input-style has-borders input-style-active">
               <input type="text" value="<?= $user_r["no_hp"]; ?>" name="txt_no_hp">
            </div>
         </td>
      </tr>
      <tr>
         <th style="width: 150px; vertical-align:middle">Status</th>
         <td>
            <div class="input-style has-borders input-style-active">
               <select class="form-select" name="txt_status" required>
                  <option selected disabled>-- Pilih Salah Satu --</option>
                  <option value="aktif" <?php if ($user_r["status"] == 'aktif') echo 'selected' ?>>Aktif</option>
                  <option value="banned" style="color: red" <?php if ($user_r["status"] == 'banned') echo 'selected' ?>>Banned</option>
                  <option value="non-aktif" <?php if ($user_r["status"] == 'non-aktif') echo 'selected' ?>>Non-aktif</option>
               </select>
            </div>
         </td>
      </tr>
      <tr>
         <th style="width: 150px">Last Login</th>
         <td><?= $user_r["last_login"]; ?></td>
      </tr>
   </table>
   <input type="hidden" value="<?= $user_r["id"]; ?>" name="txt_id">
<?php
} ?>
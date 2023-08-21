Daftar Paket :
<select class="form-select" name="txt_paket_id" required>
   <option selected disabled>-- Pilih Salah Satu --</option>
   <?php
   foreach ($package_list as $result) { ?>
      <option value="<?= $result['id']; ?>">[<?= $result['kode_paket']; ?>] <?= $result['nama']; ?> - <?= $result['keterangan']; ?></option>
   <?php
   } ?>

</select>
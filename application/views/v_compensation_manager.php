<div style="max-width: 1300px; min-height: 100vh; margin: auto; padding: 20px; background-color: white" class="page-content header-clear-medium">
   <div style="min-height: 80vh">
      <div class="content mb-2">
         <div class="row mb-3">
            <div class="col-sm-4 ml-5">
               <h1>Compensation Manager</h1>
            </div>
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
               <a href="#" data-menu="menu-create-account" style="display: flex; align-items: center; justify-content: center; padding: 4px 8px; text-decoration: none; border-radius: 20px; transition: background-color 0.3s ease; font-size: 16px; color: white; background-color: #28a745; width: 100%; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); text-transform: uppercase; font-weight: 900; margin-bottom: 1rem; border: 2px solid transparent;" class="btn btn-m btn-full b mb-3 text-uppercase font-900 shadow-s bg-green-dark">
                  <i class="fa fa-plus" style="margin-right: 8px;"></i> Batch Kompensasi
               </a>
            </div>
            <hr>
         </div>
         <div class="row">
            <div class="col-sm-4">
               <div class="card bg-light mb-1 rounded" style="width: 100%">
                  <b class="text-center rounded-top" style="background-color: #7286D3; color: white">Perangkat Aktif</b>
                  <h1 class="text-center">1</h1>
               </div>
            </div>
            <div class="col-sm-4">
               <div class="card bg-light mb-1 rounded" style="width: 100%">
                  <b class="text-center rounded-top" style="background-color: #7286D3; color: white">Kompensasi Digunakan</b>
                  <h1 class="text-center">1</h1>
               </div>
            </div>
            <div class="col-sm-4">
               <div class="card bg-light mb-1 rounded" style="width: 100%">
                  <b class="text-center rounded-top" style="background-color: #7286D3; color: white">Total Durasi Kompensasi</b>
                  <h1 style="color: green" class="text-center">1</h1>
               </div>
            </div>
         </div>
         <div class="table-responsive">
            <table class="table border" id="tb_device">
               <thead>
                  <tr class="bg-fade-night-dark">
                     <th scope="col" class="color-white">#</th>
                     <th scope="col" class="color-white">Username</th>
                     <th scope="col" class="color-white">Device Name</th>
                     <th scope="col" class="color-white">End Date</th>
                     <th scope="col" class="color-white">Sisa Masa Aktif</th>
                     <th scope="col" class="color-white">Kompensasi</th>
                     <th scope="col" class="color-white">Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  $i = 1;
                  foreach ($assigned as $assigned_p) { ?>
                     <tr>
                        <td data-label="#"><?= $i; ?></td>
                        <td data-label="Username"><?= $assigned_p["username"]; ?></td>
                        <td data-label="Device Name"><?= $assigned_p["custom_name"]; ?></td>
                        <td data-label="End Date"><?= $assigned_p["end_date"]; ?></td>
                        <td data-label="Sisa Masa Aktif"><?= $assigned_p["masa_aktif"]; ?> Jam</td>
                        <td data-label="Kompensasi"><?= $assigned_p["durasi"]; ?></td>
                        <td data-label="Action">
                           <div class="dropleft">
                              <button class="btn btn-light btn-xs" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                 <button class="dropdown-item" data-menu="menu-kompensasi-tambah" onclick='get_data_add(<?= $assigned_p["id_assign"]; ?>)'><i class="fas fa-pen"></i> Tambah Kompensasi</button>
                                 <button class="dropdown-item" data-menu="menu-kompensasi-history" onclick='get_data_history(<?= $assigned_p["id_assign"]; ?>)'><i class="fas fa-pen"></i> History Kompensasi</button>
                              </div>
                           </div>
                        </td>
                     </tr>
                  <?php
                     $i++;
                  } ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

<div id="menu-kompensasi-tambah" class="menu menu-box-top menu-box-detached rounded-m" style="max-width: 900px; margin: auto">
   <div class="content">
      <div class="d-flex ms-3 me-3 mb-3">
         <form method="POST" action="<?= base_url() ?>compensation_manager/add_compensation/">
            <h1>Tambah Kompensasi</h1>
      </div>
      <div class="ms-3 me-3 mb-4">
         <div class="input-style has-borders no-icon mb-4 input-style-active">
            Durasi Kompensasi (Jam):
            <input type="number" class="form-control validate-name" id="txt_durasi" name="txt_durasi" required>
         </div>
         <div class="input-style has-borders no-icon mb-4 input-style-active">
            Keterangan Kompensasi:
            <input type="text" class="form-control validate-name" id="txt_keterangan" name="txt_keterangan" required>
         </div>
         <input type="number" class="form-control validate-name" id="txt_assign_id" name="txt_assign_id" required style="display: none;">
         <button type="submit" style="width: 100%;" class="btn btn-full btn-m rounded-m bg-blue-dark font-700 text-uppercase mt-4"><i class="fas fa-save"></i> Simpan</button>
         </form>
      </div>
   </div>
</div>

<div id="menu-kompensasi-history" class="menu menu-box-top menu-box-detached rounded-m" style="max-width: 900px; margin: auto">
   <div class="content">
      <div class="d-flex ms-3 me-3 mb-3">
         <h1>History Kompensasi</h1>
      </div>
      <div class="ms-3 me-3 mb-4">
         <div class="table-responsive">
            <table id="data-table" class="table table-bordered">
               <thead>
                  <tr>
                     <th>No.</th>
                     <th>Tanggal</th>
                     <th>Durasi</th>
                     <th>Keterangan</th>
                     <th>Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  <!-- Data will be populated here by jQuery -->
               </tbody>
            </table>
            <div id="loading-indicator" style="display: none; text-align: center;">
               Loading Data....
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   function get_data_add(id_assign) {
      $('#txt_assign_id').val(id_assign);
   }

   function get_data_history(id_assign) {
      var tableBody = $('#data-table tbody');

      // Show loading indicator and hide the table
      $('#loading-indicator').show();
      $('#data-table').hide();

      $.ajax({
         url: '<?= base_url() ?>/compensation_manager/get_data_history/' + id_assign,
         method: 'GET',
         dataType: 'json',
         success: function(data) {
            tableBody.html(''); // Clear all existing rows

            data.forEach(function(item, index) {
               var row = `<tr>
                    <td>${index + 1}</td>
                    <td>${item.tanggal}</td>
                    <td>${item.durasi} Jam</td>
                    <td>${item.keterangan}</td>
                    <td>
                        <a class="btn btn-sm btn-light delete-btn" href="#" data-id="${item.id}" data-assign-id="${id_assign}">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>`;
               tableBody.append(row);
            });

            // Attach click event for delete buttons
            $('.delete-btn').click(function(event) {
               event.preventDefault();
               var id = $(this).data('id');
               var id_assign = $(this).data('assign-id');
               if (confirm('Are you sure you want to delete this item?')) {
                  delete_data(id, id_assign);
               }
            });

            // Hide loading indicator and show the table
            $('#loading-indicator').hide();
            $('#data-table').show();
         },
         error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching data:', textStatus, errorThrown);
            $('#loading-indicator').hide();
            $('#data-table').show();
         }
      });
   }



   function delete_data(id, id_assign) {
      $.ajax({
         url: '<?= base_url() ?>/compensation_manager/delete_data/' + id,
         method: 'DELETE',
         success: function() {
            get_data_history(id_assign); // Refresh the table after deletion
         },
         error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error deleting data:', textStatus, errorThrown);
            $('#loading-indicator').hide();
         }
      });
   }
</script>
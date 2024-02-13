  <div style="max-width: 1300px; min-height: 100vh; margin: auto; padding: 20px; background-color: white" class="page-content header-clear-medium">

     <div style="min-height: 80vh">
        <div class="content mb-2">
           <div class="row mb-3">
              <div class="col-sm-6">
                 <h1 style="font-size: 40px;">
                    USER MANAGER
                 </h1>
                 <div class="ps-1 opacity-80">User management dashboard</div>
              </div>
           </div>

           <div class="row">
              <div class=" col-sm-4">
                 <div class="card bg-light mb-1 rounded" style="width: 100%">
                    <b class="text-center text-white rounded-top" style="background-color: #6096B4">User Aktif</b>
                    <h1 class="text-center"><?= $user_count_active; ?></h1>
                 </div>
              </div>
              <div class="col-sm-4">
                 <div class="card bg-light mb-1 rounded" style="width: 100%">
                    <b class="text-center text-white rounded-top" style="background-color: #6096B4">User Non-aktif</b>
                    <h1 class="text-center" style="color: red;"><?= $user_count - $user_count_active; ?></h1>
                 </div>
              </div>
              <div class="col-sm-4">
                 <div class="card bg-light mb-1 rounded" style="width: 100%">
                    <b class="text-center text-white rounded-top" style="background-color: #6096B4">Total</b>
                    <h1 style="color: green" class="text-center"><?= $user_count ?></h1>
                 </div>
              </div>
           </div>
           <table class="table border" style="overflow: hidden;" id="tb_device">
              <thead>
                 <tr class="bg-fade-night-dark">
                    <th scope="col" class="color-white">#</th>
                    <th scope="col" class="color-white">Username</th>
                    <th scope="col" class="color-white">Email</th>
                    <th scope="col" class="color-white">No HP</th>
                    <th scope="col" class="color-white">Last Login</th>
                    <th scope="col" class="color-white">Status</th>
                    <th scope="col" class="color-white">Keterangan</th>
                    <th scope="col" class="color-white">Action</th>
                 </tr>
              </thead>
              <tbody>
                 <?php
                  foreach ($user as $user_r) {
                  ?>
                    <tr>
                       <th class="text-center" scope="row"></th>
                       <td><?= $user_r["username"]; ?></td>
                       <td><?= $user_r["email"]; ?></td>
                       <td><?= $user_r["no_hp"]; ?></td>
                       <td><?= $user_r["last_login"]; ?></td>
                       <td><?= $user_r["status"]; ?></td>
                       <td><?= $user_r["keterangan"]; ?></td>
                       <td>
                          <div class="dropleft">
                             <button class="btn btn-light btn-xs" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item" data-menu="menu-edit-user" onclick="a_user_edit(<?= $user_r['id']; ?>)"><i class="fas fa-info-circle"></i> Edit</button>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= base_url() ?>user_manager/delete_user/<?= $user_r["id"]; ?>" onclick="return confirm('Are you sure you want to delete this item?')"><i class="fas fa-trash"></i> Delete</a>
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

  <div id="menu-edit-user" class="menu menu-box-modal rounded-m" style="max-width: 800px; width: 100%">
     <div class="content">
        <form method="POST" action="<?= base_url() . 'user_manager/edit_user/'; ?>">
           <div id="device-edit" class="ms-2 me-2">
              <div class="d-flex mb-2">
                 <div class="mb-2">
                    <h1><i class="fas fa-user-edit"></i> USER DETAIL</h1>
                 </div>
              </div>
              <div id="a-user-edit">
                 Loading....
              </div>

              <button type="submit" style="width: 100%;" class="btn btn-full btn-m rounded-m bg-blue-dark font-700 text-uppercase mb-4"><i class="fas fa-save"></i> Simpan</button>
           </div>
        </form>
     </div>
  </div>
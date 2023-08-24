  <div style="max-width: 1300px; min-height: 100vh; margin: auto; padding: 20px; background-color: white" class="page-content header-clear-medium">

     <div style=" min-height: 80vh">
        <div class="content mb-2">
           <div class="row mb-3">
              <div class="col-sm-4 ml-5">
                 <h1 style="font-size: 38px;">
                    <i class="fas fa-mobile-alt" style="color: #CD1818"></i> DEVICE MANAGER
                 </h1>
                 <div class="ps-1 opacity-80">Device management dashboard</div>
              </div>
              <div class="col-sm-2">
                 <div class="card bg-light mb-1" style="width: 100%">
                    <b class="text-center" style="background-color: #CD1818; color: white">Total Perangkat</b>
                    <h1 class="text-center"><?= $device_count; ?></h1>
                 </div>
              </div>
              <div class="col-sm-2">
                 <div class="card bg-light mb-1" style="width: 100%">
                    <b class="text-center" style="background-color: #CD1818; color: white">Perangkat Digunakan</b>
                    <h1 class="text-center"><?= $device_used_count; ?></h1>
                 </div>
              </div>
              <div class="col-sm-2">
                 <div class="card bg-light mb-1" style="width: 100%">
                    <b class="text-center" style="background-color: #CD1818; color: white">Perangkat Tersedia</b>
                    <h1 style="color: green" class="text-center"><?= $device_count - $device_used_count ?></h1>
                 </div>
              </div>
              <div class="col-sm-2">
                 <a href="#" data-menu="menu-create-account" class="btn btn-m btn-full mb-3 text-uppercase font-900 shadow-s bg-green-dark"><i class="fas fa-puzzle-piece"></i> Tambah Perangkat</a>
              </div>
           </div>
           <table class="table table-borderless table-striped shadow-xs" style="overflow: hidden;" id="tb_device">
              <thead>
                 <tr class="bg-night-light">
                    <th scope="col" class="color-white">#</th>
                    <th scope="col" class="color-white">IP</th>
                    <th scope="col" class="color-white">Port</th>
                    <th scope="col" class="color-white">Device Name</th>
                    <th scope="col" class="color-white">Type</th>
                    <th scope="col" class="color-white">Status</th>
                    <th scope="col" class="color-white">Action</th>
                 </tr>
              </thead>
              <tbody>
                 <?php
                  foreach ($device as $result) {
                  ?>
                    <tr>
                       <th scope="row"></th>
                       <td><?= $result["ip"]; ?></td>
                       <td><?= $result["port"]; ?></th>
                       <td><?= $result["name"]; ?></td>
                       <td><?= $result["type"]; ?></td>
                       <td><?= $result["status_txt"]; ?></td>
                       <td>
                          <div class="dropleft">
                             <button class="btn btn-light btn-xs" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item" data-menu="menu-device-detail" onclick="a_device_detail(<?= $result['device_id']; ?>)"><i class="fas fa-info-circle"></i> Detail</button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item" data-menu="menu-device-edit" onclick="a_device_edit(<?= $result['device_id']; ?>)"><i class="fas fa-pen"></i> Edit</button>
                                <button class="dropdown-item" data-menu="menu-device-edit" onclick="a_device_edit(<?= $result['device_id']; ?>)"><i class="fas fa-arrow-alt-circle-right"></i> Clean-up</button>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= base_url() ?>device_manager/delete_device/<?= $result["device_id"]; ?>" onclick="return confirm('Are you sure you want to delete this item?')"><i class="fas fa-trash"></i> Delete</a>
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

  <div id="menu-create-account" class="menu menu-box-top menu-box-detached rounded-m" style="max-width: 900px; margin: auto">
     <div class="content">
        <div class="d-flex ms-3 me-3 mb-3">
           <form method="POST" action="<?= base_url() ?>device_manager/add_device/">
              <!-- <div class="ms-auto"> -->
              <h1><i class="fas fa-puzzle-piece"></i> Tambah Perangkat</h1>
              <!-- </div> -->
        </div>
        <div class="ms-3 me-3 mb-4">
           <div class="input-style has-borders no-icon mb-4 input-style-active">
              Nama Perangkat :
              <input type="text" class="form-control validate-name" id="txt_name" name="txt_name">
           </div>
           <div class="input-style has-borders no-icon mb-4 input-style-active">
              Type :
              <input type="text" class="form-control validate-number" id="txt_type" name="txt_type">
           </div>
           <div class="input-style has-borders no-icon mb-4 input-style-active">
              IP Address:
              <input type="text" class="form-control validate-name" id="txt_ip" name="txt_ip">
           </div>
           <div class="input-style has-borders no-icon mb-4 input-style-active">
              Port :
              <input type="number" class="form-control validate-number" id="txt_port" name="txt_port">
           </div>
           <button type="submit" class="btn btn-full btn-m rounded-m bg-blue-dark font-700 text-uppercase ms-auto"><i class="fas fa-save"></i> Simpan</button>
           </form>
        </div>
     </div>
  </div>

  <div id="menu-rename-device" class="menu menu-box-modal rounded-m" style="max-width: 600px; width: 100%">
     <div class="content">
        <div class="ms-3 me-3 pb-3" id="device-detail">
           <div class="d-flex">
              <div class="mb-2">
                 <h1><i class="fas fa-info-circle"></i> Device Info</h1>
              </div>
           </div>
           <div id="a-rename-device">
              Loading....
           </div>
        </div>
     </div>
  </div>

  <div id="menu-device-edit" class="menu menu-box-modal rounded-m" style="max-width: 600px; width: 100%">
     <div class="content">
        <form method="POST" action="<?= base_url() . 'device_manager/edit_device/'; ?>">
           <div id="device-edit" class="ms-3 me-3 mb-3">
              <div class="d-flex">
                 <div class="mb-2">
                    <h1><i class="fas fa-tools"></i> Device Edit</h1>
                 </div>
              </div>
              <div id="a-device-edit">
                 Loading....
              </div>
              <div class="row mb-4 mt-1">
                 <div class="col-sm-6">
                 </div>
                 <div class="col-sm-6">
                    <button type="submit" class="btn btn-full btn-m rounded-m bg-blue-dark font-700 text-uppercase ms-auto"><i class="fas fa-save"></i> Simpan</button>
                 </div>
              </div>
           </div>
        </form>
     </div>
  </div>

  <div id="menu-device-detail" class="menu menu-box-modal rounded-m" style="max-width: 600px; width: 100%">
     <div class="content">
        <form method="POST" action="<?= base_url() . 'device_manager/edit_device/'; ?>">
           <div id="device-edit" class="ms-3 me-3 mb-3">
              <div class="d-flex">
                 <div class="mb-2">
                    <h1><i class="fas fa-tools"></i> Device Detail</h1>
                 </div>
              </div>
              <div id="a-device-detail">
                 Loading....
              </div>
           </div>
        </form>
     </div>
  </div>
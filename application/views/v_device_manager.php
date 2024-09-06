  <div style="max-width: 1300px; min-height: 100vh; margin: auto; padding: 20px; background-color: white" class="page-content header-clear-medium">

     <div style=" min-height: 80vh">
        <div class="content mb-2 ">
           <div class="row mb-3">
              <div class="col-sm-4 ml-5">
                 <h1>
                    Device Manager
                 </h1>
              </div>
              <div class="col-sm-4">
              </div>
              <div class="col-sm-4">
                 <a href="#" data-menu="menu-create-account" style="display: flex; align-items: center; justify-content: center; padding: 4px 8px; text-decoration: none; border-radius: 20px; transition: background-color 0.3s ease; font-size: 16px; color: white; background-color: #28a745; width: 100%; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); text-transform: uppercase; font-weight: 900; margin-bottom: 1rem; border: 2px solid transparent;" class="btn btn-m btn-full b mb-3 text-uppercase font-900 shadow-s bg-green-dark">
                    <i class="fa fa-plus" style="margin-right: 8px;"></i> Tambah Perangkat
                 </a>
              </div>
              <hr>
           </div>
           <div class="row">
              <div class="col-sm-4">
                 <div class="card bg-light mb-1 rounded" style="width: 100%">
                    <b class="text-center rounded-top" style="background-color: #7286D3; color: white">Total Perangkat</b>
                    <h1 class="text-center"><?= $device_count; ?></h1>
                 </div>
              </div>
              <div class="col-sm-4">
                 <div class="card bg-light mb-1 rounded" style="width: 100%">
                    <b class="text-center rounded-top" style="background-color: #7286D3; color: white">Perangkat Digunakan</b>
                    <h1 class="text-center"><?= $device_used_count; ?></h1>
                 </div>
              </div>
              <div class="col-sm-4">
                 <div class="card bg-light mb-1 rounded" style="width: 100%">
                    <b class="text-center rounded-top" style="background-color: #7286D3; color: white">Perangkat Tersedia</b>
                    <h1 style="color: green" class="text-center"><?= $device_count - $device_used_count ?></h1>
                 </div>
              </div>
           </div>
           <div class="table-responsive">
              <table class="table border" id="tb_device">
                 <thead>
                    <tr class="bg-fade-night-dark">
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
                    <?php foreach ($device as $result) { ?>
                       <tr>
                          <td data-label="#"><?= $result["device_id"]; ?></td>
                          <td data-label="IP"><?= $result["ip"]; ?></td>
                          <td data-label="Port"><?= $result["port"]; ?></td>
                          <td data-label="Device Name"><?= $result["name"]; ?></td>
                          <td data-label="Type"><?= $result["type"]; ?></td>
                          <td data-label="Status"><?= $result["status_txt"]; ?></td>
                          <td data-label="Action">
                             <div class="dropleft">
                                <button class="btn btn-light btn-xs" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                   <a href="<?= base_url() ?>device_manager/configure/<?= $result['device_id'] ?>" target="_blank"><button class="dropdown-item"><i class="fas fa-arrow-alt-circle-right"></i> Configure</button></a>
                                   <div class="dropdown-divider"></div>
                                   <button class="dropdown-item" data-menu="menu-device-detail" onclick="a_device_detail(<?= $result['device_id']; ?>)"><i class="fas fa-info-circle"></i> Detail</button>
                                   <button class="dropdown-item" data-menu="menu-device-edit" onclick="a_device_edit(<?= $result['device_id']; ?>)"><i class="fas fa-pen"></i> Edit</button>
                                   <div class="dropdown-divider"></div>
                                   <a class="dropdown-item" href="<?= base_url() ?>device_manager/delete_device/<?= $result["device_id"]; ?>" onclick="return confirm('Are you sure you want to delete this item?')"><i class="fas fa-trash"></i> Delete</a>
                                </div>
                             </div>
                          </td>
                       </tr>
                    <?php } ?>
                 </tbody>
              </table>
           </div>
        </div>
     </div>
  </div>

  <div id="menu-create-account" class="menu menu-box-top menu-box-detached rounded-m" style="max-width: 900px; margin: auto">
     <div class="content" style="overflow: auto;">
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
              Remote IP Address:
              <input type="text" class="form-control validate-name" id="txt_ip" name="txt_ip_remote">
           </div>
           <div class="row mb-0">
              <div class="col-sm">
                 <div class="input-style has-borders no-icon mb-4 input-style-active">
                    Port :
                    <input type="number" class="form-control validate-number" id="txt_port" name="txt_port" value="<?= $last_port; ?>">
                 </div>
                 <div class="input-style has-borders no-icon mb-4 input-style-active">
                    Port Forward (Mikrotik, ODP, dsb) :
                    <input type="number" class="form-control validate-number" id="txt_port_f" name="txt_port_f" value="<?= $last_port_f; ?>">
                 </div>
              </div>
              <div class="col-sm">
                 <div class="input-style has-borders no-icon mb-4 input-style-active">
                    Port Audio :
                    <input type="number" class="form-control validate-number" id="txt_port_a" name="txt_port_a" value="<?= $last_port_a; ?>">
                 </div>
                 <div class="input-style has-borders no-icon mb-4 input-style-active">
                    Port Forward Audio (Mikrotik, ODP, dsb) :
                    <input type="number" class="form-control validate-number" id="txt_port_a_f" name="txt_port_a_f" value="<?= $last_port_a_f; ?>">
                 </div>
              </div>
           </div>
           <div class="input-style has-borders no-icon mb-4 input-style-active">
              Local IP Address:
              <input type="text" class="form-control validate-name" id="txt_ip_local" name="txt_ip_local">
           </div>
           <button type="submit" style="width: 100%;" class="btn btn-full btn-m rounded-m bg-blue-dark font-700 text-uppercase mt-4"><i class="fas fa-save"></i> Simpan</button>
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
           <div id="device-edit" class="ms-3 me-3">
              <div class="d-flex">
                 <div class="mb-2">
                    <h1><i class="fas fa-tools"></i> Device Edit</h1>
                    <small style="color: red;">Perhatian: Jika terdapat perubahan, konfigurasi haproxy harus diubah secara manual</small>
                 </div>
              </div>
              <div id="a-device-edit">
                 Loading....
                 <br>
              </div>
           </div>
           <button type="submit" style="width: 100%;" class="btn btn-full btn-m rounded-m bg-blue-dark font-700 text-uppercase ms-auto mb-4"><i class="fas fa-save"></i> Simpan</button>

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
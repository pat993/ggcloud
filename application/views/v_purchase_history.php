  <div style="max-width: 1100px; margin: auto; background-color: white" class="page-content header-clear-medium">

     <div style="height: 100%;">
        <div class="content mb-2 ms-4 me-4">
           <div class="row mb-3">
              <div class="col-sm-4">
                 <h1>
                    <!-- <i class="fas p-2 font-20 fa-tablet-alt gradient-red rounded-sm color-white"></i> -->
                    Daftar Transaksi
                 </h1>
              </div>
           </div>

           <div class="container p-3 mb-2" style="border: 1px solid #E7E7E7; border-radius: 20px; min-height: 450px">
              <form method="POST" action="<?= base_url() ?>purchase_history/filter">
                 <div class="row mb-0">
                    <div class="col-sm-2">
                       <div class="input-style has-borders no-icon">
                          <label for="form5" class="color-gray"><b>Tanggal</b></label>
                          <select id="form5" name="txt_tanggal">
                             <option value="default" disabled selected>Tanggal</option>
                             <option value="1" <?php if ($tanggal == "1") echo "Selected" ?>>1</option>
                             <option value="2" <?php if ($tanggal == "2") echo "Selected" ?>>2</option>
                             <option value="3" <?php if ($tanggal == "3") echo "Selected" ?>>3</option>
                             <option value="4" <?php if ($tanggal == "4") echo "Selected" ?>>4</option>
                             <option value="5" <?php if ($tanggal == "5") echo "Selected" ?>>5</option>
                             <option value="6" <?php if ($tanggal == "6") echo "Selected" ?>>6</option>
                             <option value="7" <?php if ($tanggal == "7") echo "Selected" ?>>7</option>
                             <option value="8" <?php if ($tanggal == "8") echo "Selected" ?>>8</option>
                             <option value="9" <?php if ($tanggal == "9") echo "Selected" ?>>9</option>
                             <option value="10" <?php if ($tanggal == "10") echo "Selected" ?>>10</option>
                             <option value="11" <?php if ($tanggal == "11") echo "Selected" ?>>11</option>
                             <option value="12" <?php if ($tanggal == "12") echo "Selected" ?>>12</option>
                             <option value="13" <?php if ($tanggal == "13") echo "Selected" ?>>13</option>
                             <option value="14" <?php if ($tanggal == "14") echo "Selected" ?>>14</option>
                             <option value="15" <?php if ($tanggal == "15") echo "Selected" ?>>15</option>
                             <option value="16" <?php if ($tanggal == "16") echo "Selected" ?>>16</option>
                             <option value="17" <?php if ($tanggal == "17") echo "Selected" ?>>17</option>
                             <option value="18" <?php if ($tanggal == "18") echo "Selected" ?>>18</option>
                             <option value="19" <?php if ($tanggal == "19") echo "Selected" ?>>19</option>
                             <option value="20" <?php if ($tanggal == "20") echo "Selected" ?>>20</option>
                             <option value="21" <?php if ($tanggal == "21") echo "Selected" ?>>21</option>
                             <option value="22" <?php if ($tanggal == "22") echo "Selected" ?>>22</option>
                             <option value="23" <?php if ($tanggal == "23") echo "Selected" ?>>23</option>
                             <option value="24" <?php if ($tanggal == "24") echo "Selected" ?>>24</option>
                             <option value="25" <?php if ($tanggal == "25") echo "Selected" ?>>25</option>
                             <option value="26" <?php if ($tanggal == "26") echo "Selected" ?>>26</option>
                             <option value="27" <?php if ($tanggal == "27") echo "Selected" ?>>27</option>
                             <option value="28" <?php if ($tanggal == "28") echo "Selected" ?>>28</option>
                             <option value="29" <?php if ($tanggal == "29") echo "Selected" ?>>29</option>
                             <option value="30" <?php if ($tanggal == "30") echo "Selected" ?>>30</option>
                             <option value="31" <?php if ($tanggal == "31") echo "Selected" ?>>31</option>
                          </select>
                       </div>
                    </div>

                    <div class="col-sm-2">
                       <div class="input-style has-borders no-icon mb-2">
                          <label for="form5" class="color-gray"><b>Bulan</b></label>
                          <select id="form5" name="txt_bulan">
                             <option value="default" disabled selected>Bulan</option>
                             <option value="1" <?php if ($bulan == "1") echo "Selected" ?>>Januari</option>
                             <option value="2" <?php if ($bulan == "2") echo "Selected" ?>>Februari</option>
                             <option value="3" <?php if ($bulan == "3") echo "Selected" ?>>Maret</option>
                             <option value="4" <?php if ($bulan == "4") echo "Selected" ?>>April</option>
                             <option value="5" <?php if ($bulan == "5") echo "Selected" ?>>Mei</option>
                             <option value="6" <?php if ($bulan == "6") echo "Selected" ?>>Juni</option>
                             <option value="7" <?php if ($bulan == "7") echo "Selected" ?>>Juli</option>
                             <option value="8" <?php if ($bulan == "8") echo "Selected" ?>>Agustus</option>
                             <option value="9" <?php if ($bulan == "9") echo "Selected" ?>>September</option>
                             <option value="10" <?php if ($bulan == "10") echo "Selected" ?>>Oktober</option>
                             <option value="11" <?php if ($bulan == "11") echo "Selected" ?>>November</option>
                             <option value="12" <?php if ($bulan == "12") echo "Selected" ?>>Desember</option>
                          </select>
                       </div>
                    </div>

                    <div class="col-sm-2">
                       <div class="input-style has-borders no-icon">
                          <label for="form5" class="color-gray"><b>Tahun</b></label>
                          <select id="form5" name="txt_tahun">
                             <option value="default" disabled selected>Tahun</option>
                             <option value="2022" <?php if ($tahun == "2022") echo "Selected" ?>>2022</option>
                             <option value="2023" <?php if ($tahun == "2023") echo "Selected" ?>>2023</option>
                          </select>
                       </div>
                    </div>

                    <div class="col-sm-5">
                       <div class="row">
                          <div class="col-4">
                             <a type="submit" class="btn btn-full btn-l bg-light rounded-xl" href="<?= base_url(); ?>purchase_history"><i class="fas fa-redo-alt"></i></a>
                          </div>
                          <div class="col-4">
                             <button type="submit" class="btn btn-full btn-l rounded-xl bg-primary text-white"><i class="fas fa-search"></i></button>
                          </div>
                       </div>
                    </div>
                 </div>
              </form>

              <table id="tb_purchase" style="width:100%;">
                 <thead>
                    <tr>
                       <th class="opacity-50">Daftar Transaksi Kamu</th>
                    </tr>
                 </thead>
                 <tbody>
                    <?php
                     foreach ($purchase as $purchase_r) {
                     ?>
                       <tr>
                          <td>
                             <!-- Invoice 1 -->
                             <a data-bs-toggle="collapse" href="#invoice-<?= $purchase_r["purchase_id"]; ?>" aria-expanded="false" aria-controls="invoice-one" class="no-effect card card-style m-0 mb-3 shadow-m">
                                <div class="content">
                                   <div class="d-flex mb-n1">
                                      <div>
                                         <h3>#<?= $purchase_r["purchase_id"]; ?></h3>
                                         <p class="opacity-80 font-10 mt-n2">Click for Details - <?= date("d M Y", strtotime($purchase_r["purchase_date"])) ?></p>
                                      </div>
                                      <!-- <div class="ms-auto align-self-center text-center opacity-70">
                          <i class="fa fa-check-circle color-green-dark fa-2x mt-1"></i><br>
                       </div> -->
                                   </div>
                                </div>
                             </a>
                             <div class="collapse mb-3" id="invoice-<?= $purchase_r["purchase_id"]; ?>">
                                <div class="card card-style m-0 shadow-m">
                                   <div class="content">
                                      <div class="d-flex">
                                         <div class="mt-1">
                                            <p class="color-highlight font-600 mb-n1"><?= $purchase_r["tipe"]; ?></p>
                                            <h1><?= $purchase_r["keterangan"]; ?></h1>
                                         </div>
                                         <!-- <div class="ms-auto">
                             <img src="images/pictures/0t.jpg" width="60" class="rounded-xl shadow-xl">
                          </div> -->
                                      </div>

                                      <div class="row mb-3 mt-4">
                                         <h5 class="col-4 text-start font-15">Invoice</h5>
                                         <h5 class="col-8 text-end font-14 opacity-60 font-400"><?= $purchase_r["purchase_id"]; ?></h5>

                                         <h5 class="col-4 text-start font-15">Pembeli</h5>
                                         <h5 class="col-8 text-end font-14 opacity-60 font-400"><?= $purchase_r["email"]; ?></h5>
                                         <h5 class="col-4 text-start font-15">Tanggal</h5>
                                         <h5 class="col-8 text-end font-14 opacity-60 font-400"><?= date("d M Y", strtotime($purchase_r["purchase_date"])) ?></h5>

                                         <h5 class="col-4 text-start font-15">Jenis Pembayaran</h5>
                                         <h5 class="col-8 text-end font-14 opacity-60 font-400 "><?= $purchase_r["jenis_pembayaran"]; ?></h5>

                                      </div>

                                      <div class="divider"></div>

                                      <div class="d-flex mb-2">
                                         <div>
                                            <h5 class="font-500"><?= $purchase_r["keterangan"]; ?></h5>
                                         </div>
                                         <div class="ms-auto">
                                            <h5><?= number_format($purchase_r["harga"], 0, '', '.'); ?> IDR </h5>
                                         </div>
                                      </div>

                                      <div class="divider mt-4"></div>

                                      <div class="d-flex mb-2">
                                         <div>
                                            <h5 class="opacity-50 font-500">PPn</h5>
                                         </div>
                                         <div class="ms-auto">
                                            <h5>0 IDR </h5>
                                         </div>
                                      </div>
                                      <div class="d-flex mb-3">
                                         <div>
                                            <h4 class="font-700">Sub Total</h4>
                                         </div>
                                         <div class="ms-auto">
                                            <h3><?= number_format($purchase_r["harga"], 0, '', '.'); ?> IDR </h3>
                                         </div>
                                      </div>
                                      <!-- <a href="#" class="btn btn-full btn-l rounded-sm font-800 text-uppercase bg-highlight">Download Invoice in PDF</a> -->
                                   </div>
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
           <form method="POST" action="<?= base_url() ?>device_manager/add_device/">
              <div class="d-flex">
                 <div class="ms-auto">
                    <h1>Tambah Perangkat</h1>
                 </div>
              </div>
              <p>
                 Isi form untuk menambahkan perangkat baru
              </p>
              <div class="input-style has-borders no-icon validate-field mb-4 input-style-active">
                 <input type="text" class="form-control validate-name" id="txt_name" name="txt_name">
                 <label for="txt_name" class="color-dark-dark font-600">Nama Perangkat</label>
              </div>
              <div class="input-style has-borders no-icon validate-field mb-4 input-style-active">
                 <input type="text" class="form-control validate-number" id="txt_type" name="txt_type">
                 <label for="txt_type" class="color-dark-dark font-600">Type</label>
              </div>
              <div class="input-style has-borders no-icon mb-4 input-style-active">
                 <input type="text" class="form-control validate-name" id="txt_ip" name="txt_ip">
                 <label for="txt_ip" class="color-dark-dark font-600">Ip</label>
              </div>
              <div class="input-style has-borders no-icon validate-field mb-4 input-style-active">
                 <input type="number" class="form-control validate-number" id="txt_port" name="txt_port">
                 <label for="txt_port" class="color-dark-dark font-600">Port</label>
              </div>
              <button href="#" type="submit" name="add_device" style="width: 100%" class="btn btn-full btn-m rounded-m bg-green-dark font-700 text-uppercase mb-4 mt-4">Simpan</button>
           </form>
        </div>
     </div>


     <div id="menu-device-detail" class="menu menu-box-modal rounded-m" style="max-width: 600px; width: 100%">
        <div class="content">
           <div id="device-detail">
              <div class="d-flex">
                 <div class="mb-2">
                    <h1>Device Info</h1>
                 </div>
              </div>
              <div id="a-device-detail">
                 Loading....
              </div>
           </div>
        </div>
     </div>

     <div id="menu-device-edit" class="menu menu-box-modal rounded-m">
        <div class="content">
           <form method="POST" action="<?= base_url() . 'device_manager/edit_device/'; ?>">
              <div id="device-edit">
                 <div class="d-flex">
                    <div class="mb-2">
                       <h1>Device Edit</h1>
                    </div>
                 </div>
                 <div id="a-device-edit">
                    Loading....
                 </div>
                 <div class="row mb-2 mt-4">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                       <button type="submit" class="btn btn-full btn-m rounded-m bg-blue-dark font-700 text-uppercase mt-1 mb-1">Simpan</button>
                    </div>
                 </div>
              </div>
           </form>
        </div>
     </div>
<div class="page-content header-clear-medium">
    <div style="max-width: 800px; margin: auto" class="content mb-0" id="tab-group-listing">
        <div class="card card-style">
            <div class="tab-controls" data-highlight="color-highlight-purple">
                <a href="#" class="no-effect font-15 font-600 py-2 border-0" data-active data-bs-toggle="collapse" data-bs-target="#tab-1">
                    <!-- <span class="d-block font-10 mb-n2">Tap to View</span> -->
                    <span class="font-20 d-block pb-2">Harian</span>
                </a>
                <a href="#" class="no-effect font-15 font-600 py-2 border-0" data-bs-toggle="collapse" data-bs-target="#tab-2">
                    <!-- <span class="d-block font-10 mb-n2">Tap to View</span> -->
                    <span class="font-20 d-block pb-2">Mingguan</span>
                </a>
                <a href="#" class="no-effect font-15 font-600 py-2 border-0" data-bs-toggle="collapse" data-bs-target="#tab-3">
                    <!-- <span class="d-block font-10 mb-n2">Tap to View</span> -->
                    <span class="font-20 d-block pb-2">Bulanan</span>
                </a>
            </div>
        </div>
        <!-- Card Style-->
        <div data-bs-parent="#tab-group-listing" class="collapse show" id="tab-1">
            <?php foreach ($package_list_harian as $package_list_harian_p) { ?>
                <div class="card card-style">
                    <div class="content">
                        <h2><?= $package_list_harian_p['nama'] . ' - ' . $package_list_harian_p['nama_paket']; ?></h2>
                        <div class="d-flex">
                            <div>
                                <span class="d-block font-700"><?= $package_list_harian_p['keterangan']; ?></span>

                                <?php
                                $spec_ary = explode('|', $package_list_harian_p['spesifikasi']);

                                foreach ($spec_ary as $spec_ary_p) {
                                ?>
                                    <span style="border: 2px solid #DFDFDF; display:inline-block; margin: 1px" class="p-1 pt-0 pb-0 rounded-m text-center"><?= $spec_ary_p; ?></span>
                                <?php
                                }
                                ?>

                            </div>
                            <div class="ms-auto">
                                <h1 class="pt-2"><?= $package_list_harian_p['harga'] . ' IDR'; ?></h1>
                            </div>
                        </div>
                        <div class="divider mt-3 mb-3"></div>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <img style="vertical-align:middle" src="<?= base_url(); ?>images/antutu.png" width="20" alt="img">
                                <span class="font-700" style=""><?= $package_list_harian_p['antutu_score']; ?></span>
                            </div>

                            <div class="align-self-center ms-auto">
                                <a href="<?= $package_list_harian_p['ket_lain']; ?>" target="_blank" class="btn btn-s btn-grad rounded-sm font-700 text-uppercase">Beli</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } ?>
        </div>
        <!-- List View-->
        <div data-bs-parent="#tab-group-listing" class="collapse" id="tab-2">
            <?php foreach ($package_list_mingguan as $package_list_mingguan_p) { ?>
                <div class="card card-style">
                    <div class="content">
                        <h2><?= $package_list_mingguan_p['nama'] . ' - ' . $package_list_mingguan_p['nama_paket']; ?></h2>
                        <div class="d-flex">
                            <div>
                                <span class="d-block font-700"><?= $package_list_mingguan_p['keterangan']; ?></span>

                                <?php
                                $spec_ary = explode('|', $package_list_mingguan_p['spesifikasi']);

                                foreach ($spec_ary as $spec_ary_p) {
                                ?>
                                    <span style="border: 2px solid #DFDFDF; display:inline-block; margin: 1px" class="p-1 pt-0 pb-0 rounded-m text-center"><?= $spec_ary_p; ?></span>
                                <?php
                                }
                                ?>

                            </div>
                            <div class="ms-auto">
                                <h1 class="pt-2"><?= $package_list_mingguan_p['harga'] . ' IDR'; ?></h1>
                            </div>
                        </div>
                        <div class="divider mt-3 mb-3"></div>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <img style="vertical-align:middle" src="<?= base_url(); ?>images/antutu.png" width="20" alt="img">
                                <span class="font-700" style=""><?= $package_list_mingguan_p['antutu_score']; ?></span>
                            </div>

                            <div class="align-self-center ms-auto">
                                <a href="<?= $package_list_mingguan_p['ket_lain']; ?>" target="_blank" class="btn btn-s btn-grad rounded-sm font-700 text-uppercase">Beli</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } ?>
        </div>
        <!-- 2x2 Grid View -->
        <div data-bs-parent="#tab-group-listing" class="collapse" id="tab-3">
            <?php foreach ($package_list_bulanan as $package_list_bulanan_p) { ?>
                <div class="card card-style">
                    <div class="content">
                        <h2><?= $package_list_bulanan_p['nama'] . ' - ' . $package_list_bulanan_p['nama_paket']; ?></h2>
                        <div class="d-flex">
                            <div>
                                <span class="d-block font-700"><?= $package_list_bulanan_p['keterangan']; ?></span>

                                <?php
                                $spec_ary = explode('|', $package_list_bulanan_p['spesifikasi']);

                                foreach ($spec_ary as $spec_ary_p) {
                                ?>
                                    <span style="border: 2px solid #DFDFDF; display:inline-block; margin: 1px" class="p-1 pt-0 pb-0 rounded-m text-center"><?= $spec_ary_p; ?></span>
                                <?php
                                }
                                ?>

                            </div>
                            <div class="ms-auto">
                                <h1 class="pt-2"><?= $package_list_bulanan_p['harga'] . ' IDR'; ?></h1>
                            </div>
                        </div>
                        <div class="divider mt-3 mb-3"></div>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <img style="vertical-align:middle" src="<?= base_url(); ?>images/antutu.png" width="20" alt="img">
                                <span class="font-700" style=""><?= $package_list_bulanan_p['antutu_score']; ?></span>
                            </div>

                            <div class="align-self-center ms-auto">
                                <a href="<?= $package_list_bulanan_p['ket_lain']; ?>" target="_blank" class="btn btn-s btn-grad rounded-sm font-700 text-uppercase">Beli</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } ?>
        </div>
    </div>
</div>
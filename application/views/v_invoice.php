<?php

function formatRupiah($nominal)
{
    return number_format($nominal, 0, ',', '.');
}

?>

<div style="max-width: 800px; margin: auto" class="page-content pb-0 header-clear-medium">
    <div class="content mb-2">

        <div class="table-responsive">
            <table style="width: 100%; padding-top: 10px" id="tb_device2">
                <thead>
                    <tr>
                        <th style="padding: 10px 0px 10px 0px; border-top: 1px solid gray">
                            <h2>Daftar Pembelian</h2>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($invoice_data as $invoice_data_p) { ?>
                        <tr>
                            <td>
                                <!-- Invoice 1 -->
                                <a data-bs-toggle="collapse" href="#invoice-<?= $i; ?>" aria-expanded="false" aria-controls="invoice-<?= $i; ?>" class="no-effect card card-style mb-2">
                                    <div class="content">
                                        <div class="d-flex mb-n1">
                                            <div>
                                                <h3>Invoice <?= $invoice_data_p['id']; ?></h3>
                                                <p class="opacity-80 font-10 mt-n2">Click for Details - <?= $invoice_data_p['tanggal_transaksi']; ?></p>
                                            </div>
                                            <div class="ms-auto align-self-center text-center opacity-70">
                                                <?php if ($invoice_data_p['status'] == 'Lunas') {
                                                ?>
                                                    <i class="fa fa-check-circle color-green-dark fa-2x mt-1"></i><br>
                                                <?php
                                                } ?>
                                                <?php if ($invoice_data_p['status'] == 'Pembayaran') {
                                                ?>
                                                    <i class="fa fa-exclamation-circle color-yellow-dark fa-2x mt-1"></i><br>
                                                <?php
                                                } ?>
                                                <?php if ($invoice_data_p['status'] == 'Dibatalkan') {
                                                ?>
                                                    <i class="fa fa-times-circle color-red-dark fa-2x mt-1"></i><br>
                                                <?php
                                                } ?>


                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="collapse" id="invoice-<?= $i; ?>">
                                    <div class="card card-style">
                                        <div class="content">
                                            <div class="d-flex">
                                                <div class="mt-1">
                                                    <p class="color-highlight font-600 mb-n1"><?= $invoice_data_p['tipe']; ?></p>
                                                    <h2><?= $invoice_data_p['nama_paket']; ?></h2>
                                                </div>
                                            </div>

                                            <div class="row mb-3 mt-4">
                                                <h5 class="col-4 text-start font-15">Invoice</h5>
                                                <h5 class="col-8 text-end font-14 opacity-60 font-400"><?= $invoice_data_p['id']; ?></h5>

                                                <h5 class="col-4 text-start font-15">Tanggal Transaksi</h5>
                                                <h5 class="col-8 text-end font-14 opacity-60 font-400"><?= $invoice_data_p['tanggal_transaksi']; ?></h5>
                                                <h5 class="col-4 text-start font-15">Jenis Transaksi</h5>
                                                <h5 class="col-8 text-end font-14 opacity-60 font-400"><?= $invoice_data_p['jenis_transaksi']; ?></h5>

                                                <h5 class="col-4 text-start font-15">status</h5>
                                                <h5 class="col-8 text-end font-14 opacity-60 font-400 "><?= $invoice_data_p['status']; ?></h5>

                                            </div>

                                            <div class="divider"></div>

                                            <div class="d-flex mb-2">
                                                <div>
                                                    <h5 class="font-500"><?= $invoice_data_p['nama_paket']; ?></h5>
                                                </div>
                                                <div class="ms-auto">
                                                    <h5><?= formatRupiah($invoice_data_p['harga']); ?> IDR</h5>
                                                </div>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <div>
                                                    <h5 class="opacity-50 font-500">PPNPN*</h5>
                                                </div>
                                                <div class="ms-auto">
                                                    <h5>0 IDR</h5>
                                                </div>
                                            </div>
                                            <div class="d-flex mb-3">
                                                <div>
                                                    <h4 class="font-700">Grand Total</h4>
                                                </div>
                                                <div class="ms-auto">
                                                    <h3><?= formatRupiah($invoice_data_p['harga']); ?> IDR</h3>
                                                </div>
                                            </div>

                                            <!-- <div class="divider"></div>

                                        <a href="#" class="btn btn-full btn-l rounded-sm font-800 text-uppercase bg-highlight">Download Invoice in PDF</a> -->
                                        </div>
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
<?php if (null !== $this->session->flashdata('error')) { ?>

   <div id="notification-3" data-bs-dismiss="notification-3" data-bs-delay="2000" data-bs-autohide="true" class="notification notification-material bg-red-dark notch-push pt-3">
      <p class="p-0 text-center text-white">
         <?= $this->session->flashdata('error'); ?>
      </p>
   </div>

   <script>
      $(window).on('load', function() {
         $('#notification-3').toast('show');
      });
   </script>

<?php
} ?>

<?php if (null !== $this->session->flashdata('success')) { ?>

   <div id="notification-3" data-bs-dismiss="notification-3" data-bs-delay="2000" data-bs-autohide="true" class="notification notification-material bg-green-dark notch-push pt-3">
      <p class="p-0 text-center text-white">
         <?= $this->session->flashdata('success'); ?>
      </p>
   </div>

   <script>
      $(window).on('load', function() {
         $('#notification-3').toast('show');
      });
   </script>

<?php
} ?>

<?php if (null !== $this->session->flashdata('username')) { ?>

   <script>
      $('#username').val('<?= $this->session->flashdata('username'); ?>');
      $('#email').val('<?= $this->session->flashdata('email'); ?>');
   </script>

<?php
} ?>

<script>
   $(function() {
      $('[data-toggle="tooltip"]').tooltip({
         html: true
      })
   })

   $(function() {
      var t = $('#tb_device2').DataTable({
         "pageLength": 10,
         "dom": '<"top"f>rt<"bottom"ip><"clear">',
         "paging": true,
         "info": false,
         "lengthChange": false,
         "order": [
            [0, 'desc']
         ], // Mengatur urutan kolom pertama dalam urutan menurun
         "columnDefs": [{
               "type": "num",
               "targets": 0
            } // Mengatur kolom pertama sebagai angka
         ]
      });

      // Hide the "Show entries" dropdown
      $('#tb_device2').DataTable().settings()[0].oFeatures.bPaginate = false;
   });



   <?php if (isset($user_id)) {
      if ($user_id == '1') { ?>
         $(function() {
            var t = $('#tb_device').DataTable({
               order: [
                  [2, 'asc']
               ],
               "pageLength": 50
            });

            t.on('order.dt search.dt', function() {
               let i = 1;

               t.cells(null, 0, {
                  search: 'applied',
                  order: 'applied'
               }).every(function(cell) {
                  this.data(i++);
               });
            }).draw();
         });

         function get_daftar_paket() {
            var a = document.getElementById("div-daftar-paket");
            a.innerHTML = "Loading....";

            var tipe = document.getElementById("txt_tipe");
            var str = tipe.options[tipe.selectedIndex].value;

            var tipe = document.getElementById("txt_jenis");
            var str2 = tipe.options[tipe.selectedIndex].value;

            // checkExist.innerHTML = ""
            if (a != null) {
               var xmlhttp = new XMLHttpRequest();
               xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                     a.innerHTML = this.responseText;
                  }
               };
               xmlhttp.open("GET", "<?= base_url() ?>voucher_manager/daftar_paket/" + str + "/" + str2, true);
               xmlhttp.send();
            }
         }

         function detailVoucher(str) {
            var checkExist = document.getElementById("voucher-detail");
            // checkExist.innerHTML = ""
            if (checkExist != null) {
               var xmlhttp = new XMLHttpRequest();
               xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                     document.getElementById("voucher-detail").innerHTML = this.responseText;
                  }
               };
               xmlhttp.open("GET", "_ajax/ajx_voucher-detail.php?id=" + str, true);
               xmlhttp.send();
            }
         }

         function a_device_detail(str) {
            var a = document.getElementById("a-device-detail");
            a.innerHTML = "Loading....";

            // checkExist.innerHTML = ""
            if (a != null) {
               var xmlhttp = new XMLHttpRequest();
               xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                     a.innerHTML = this.responseText;
                  }
               };
               xmlhttp.open("GET", "<?= base_url() ?>device_manager/get_detail/" + str, true);
               xmlhttp.send();
            }
         }

         function a_device_info(str) {
            var a = document.getElementById("a-device-info");
            a.innerHTML = "<div class='mb-2 rounded-s' style='background-color: #E7E7E7; height: 50px; width: 300px'></div><div class='mb-2 rounded-s' style='background-color: #E7E7E7; height: 20px;'></div><div class='mb-2 rounded-s' style='background-color: #E7E7E7; height: 20px;'></div><div class='mb-2 rounded-s' style='background-color: #E7E7E7; height: 20px;'></div><div class='mb-4 rounded-s' style='background-color: #E7E7E7; height: 20px;'></div>";

            // checkExist.innerHTML = ""
            if (a != null) {
               var xmlhttp = new XMLHttpRequest();
               xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                     a.innerHTML = this.responseText;
                  }
               };
               xmlhttp.open("GET", "<?= base_url() ?>dashboard/get_detail/" + str, true);
               xmlhttp.send();
            }
         }

         function a_voucher_detail(str) {
            var a = document.getElementById("a-voucher-detail");
            a.innerHTML = "Loading....";

            // checkExist.innerHTML = ""
            if (a != null) {
               var xmlhttp = new XMLHttpRequest();
               xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                     a.innerHTML = this.responseText;
                  }
               };
               xmlhttp.open("GET", "<?= base_url() ?>voucher_manager/get_detail/" + str, true);
               xmlhttp.send();
            }
         }

         function a_device_edit(str) {
            var a = document.getElementById("a-device-edit");
            a.innerHTML = "Loading....";

            // checkExist.innerHTML = ""
            if (a != null) {
               var xmlhttp = new XMLHttpRequest();
               xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                     a.innerHTML = this.responseText;
                  }
               };
               xmlhttp.open("GET", "<?= base_url() ?>device_manager/get_detail_edit/" + str, true);
               xmlhttp.send();
            }
         }

         function a_user_edit(str) {
            var a = document.getElementById("a-user-edit");
            a.innerHTML = "Loading....";

            // checkExist.innerHTML = ""
            if (a != null) {
               var xmlhttp = new XMLHttpRequest();
               xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                     a.innerHTML = this.responseText;
                  }
               };
               xmlhttp.open("GET", "<?= base_url() ?>user_manager/get_detail/" + str, true);
               xmlhttp.send();
            }
         }
   <?php
      }
   } ?>

   var base_url = "<?= base_url(); ?>";
</script>

<script type="text/javascript" src="<?= base_url(); ?>scripts/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?= base_url(); ?>scripts/custom.js"></script>
</body>
<div class="app-title">
  <div>
    <h1><i class="app-menu__icon fa fa-exchange-alt"></i> Transaksi Masuk</h1>
    <p>
      
    </p>
    
  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><?= $perusahaan->nama_perusahaan ?></li>
    <li class="breadcrumb-item">Transaksi Masuk</li>
    <li class="breadcrumb-item"><a href="#">Pembelian</a></li>
  </ul>
</div>
<div class="col-md-12">
    <div class="tile">
        <h3 class="tile-title"><i class="fas fa-shopping-cart"></i> Transaksi Masuk / Pembelian </h3> 
        <div class="tile-body">
            <div class="overlay" style="z-index:9999999;" id="tile-form-masuk">
                <div class="m-loader mr-4">
                    <svg class="m-circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"></circle>
                    </svg>
                </div>
                <h3 class="l-text">Loading</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                           
                            <form class="form-vertical" method="POST" action="<?= site_url('transaksi/create/masuk'); ?>" autocomplete="off" id="form-masuk">
                                <div class="form-group row">
                                    <label class="control-label col-md-2">Tanggal</label>
                                    <div class="col-md-10">
                                    <input class="form-control form-control-sm col-md-6" type="date" name="tanggal" placeholder="tanggal" id="tanggal" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-2">Supplier</label>
                                    <div class="col-md-10">
                                        <input class="form-control form-control-sm" type="text" name="supplier" placeholder="Supplier">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-2">Kode</label>
                                    <div class="col-md-10">
                                        <select class="rounded-0" name="kd_barang" id="select2_kd_barang" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-2">Harga/Qty</label>
                                    <div class="col-md-10 input-group">
                                        <input class="form-control form-control-sm rupiah" type="text" name="harga" placeholder="Harga Beli Satuan" id="harga" required>
                                        <input class="form-control form-control-sm col-md-5" type="number" name="qty" placeholder="00.0" id="qty" required>
                                        <input class="form-control form-control-sm" type="text" name="satuan" placeholder="Satuan" id="satuan" readonly>
                                    </div>
                                    
                                </div>
                                <br>
                                <div class="float-right">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-0"><i class="fa fa-check-circle"></i> Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6" style="border-left: 1px solid #BADA55; height: 250px;">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="table-rekap">
                                    <thead>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Total Transaksi</th>
                                        <th>Total Pembelian (Rp)</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($rekap_pembelian as $key => $rows) {
                                                echo '<tr>
                                                        <td>'.date('F', mktime(0, 0, 0, $rows->bulan, 10)).'</td>
                                                        <td>'.$rows->tahun.'</td>
                                                        <td><div class="text-center">'.$rows->jumlah_item.'</div></td>
                                                        <td><div class="text-right">'.number_format($rows->harga, 0, ',', '.').'</div></td>
                                                    </tr>';
                                            } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tile-footer">
                        <div class="row">
                            <div class="col-md-3">
                                    <form class="form-horizontal" autocomplete="off" id="form-filter">
                                    <div class="form-group row">
                                        <label for="table" class="col-sm-4 col-form-label col-form-label-sm float-right small">Tanggal </label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm" name="tgl_awal" id="tgl_awal">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="cari" class="col-sm-4 col-form-label col-form-label-sm float-right small">Hingga</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" id="tgl_akhir">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="cari" class="col-sm-4 col-form-label col-form-label-sm float-right small">Cari</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" name="cari" id="cari" placeholder="Cari Berdasarkan Nama Barang..." autocomplete="off">
                                        </div>
                                    </div>
                                </form>
                                <div class="tile-footer">
                                    <button type="button" class="btn btn-secondary btn-sm rounded-0" onclick="reset_form()">Reset</button>
                                    <button type="submit" form="form-filter" class="btn btn-sm btn-primary rounded-0"><i class="fas fa-filter"></i> Filter</button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover table-bordered" id="datatable">
                                        <thead class="text-center">
                                            <th class="bg-dark text-white" width="1px">No</th>
                                            <th class="bg-dark text-white">Tanggal</th>
                                            <th class="bg-dark text-white">Supplier</th>
                                            <th class="bg-dark text-white">Kode Barang</th>
                                            <th class="bg-dark text-white">Nama Barang</th>
                                            <th class="bg-dark text-white">Qty Before</th>
                                            <th class="bg-dark text-white">Qty Buy</th>
                                            <th class="bg-dark text-white">Qty End</th>
                                            <th class="bg-dark text-white">Satuan</th>
                                            <th class="bg-dark text-white">Harga Beli Satuan</th>
                                            <th class="bg-dark text-white">Harga Beli</th>
                                            <th class="bg-danger text-white" width="1px"></th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    
                       
                    </div>
                </div>
              
            </div>
          </div>
    </div>
</div>

<script>

    var tanggal = '<?= date('Y-m-d'); ?>';
    var tgl_awal = '<?= date('Y-m-01'); ?>';
    var tgl_akhir = '<?= date('Y-m-d'); ?>';
    
    $("#tanggal").val(tanggal);
    $("#tgl_awal").val(tgl_awal);
    $("#tgl_akhir").val(tgl_akhir);

    var table;
    $(document).ready(function () {
        $("#tile-form-masuk").hide();
        table = $('#datatable').DataTable({
            "pagingType": "full_numbers",
            "searching": false,
            "ajax": {
                "url": "<?php echo site_url('transaksi/datatable/masuk'); ?>",
                "type": "POST",
                "data": function (data) {
                    data.cari = $('#cari').val();
                    data.tgl_awal = $('#tgl_awal').val();
                    data.tgl_akhir = $('#tgl_akhir').val();
                }
            },

            "columnDefs": [
                {
                    "targets": [0, -1],
                    "orderable": false,
                },

            ],
        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
        $("#table-rekap").load(" #table-rekap > *");
    }

    function reset_form() {
        $("#cari").val("");
        $("#tgl_awal").val(tgl_awal);
        $("#tgl_akhir").val(tgl_akhir);
        reload_table();
        
      }

    $('#form-filter').submit(function() {
        $.ajax({
            type: 'POST',
            success: function() {
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown, data) {
                $.notify({
                    title: "Oops!",
                    message: "Terjadi kesalahan, silahkan coba kembali"
                },{
                    type: 'danger'
                });
                $("#tile-form-keluar").hide();
            }
        })
        return false;
    });
    
    $('#form-masuk').submit(function() {
        $("#tile-form-masuk").show();
     
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "JSON",
            success: function(data) {
                reload_table();
                $.notify({
                    title: data.title,
                    message: data.message,
                    icon: data.icon
                },{
                    type: data.type,
                });
                $("#qty").val("");
                $("#harga").val("");
                $("#select2_kd_barang").empty();
                $('#select2_kd_barang').select2('open');
                $("#tile-form-masuk").hide();
            },
            error: function (jqXHR, textStatus, errorThrown, data) {
                $.notify({
                    title: "Oops!",
                    message: "Terjadi kesalahan, silahkan coba kembali"
                },{
                    type: 'danger'
                });
                $("#tile-form-masuk").hide();
            }
        })
        return false;
    });
    
    function hapus(tm_id) {
        Swal.fire({
                title: 'Peringatan!',
                text: "Anda yakin akan menghapus ini?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.value) {
                $.ajax({
                        url : "<?= site_url('transaksi/delete/masuk') ?>",
                        type: "POST",
                        dataType: "JSON",
                        data: { "tm_id": tm_id },
                        success: function(data)
                        {
                            reload_table();
                            Pace.restart();
                            $('#cover-spin').hide(0);
                            $.notify({
                            title: data.title,
                            message: data.message,
                            icon: data.icon
                            },{
                            type: data.type,
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            alert('Error get data from ajax');
                            Pace.restart();
                            $('#cover-spin').hide(0);
                        }
                });
                }
            })
    }

    $("#select2_kd_barang").select2({
        width: "100%",
        placeholder: "Kode Barang / Nama Barang",
          ajax: {
              url: "<?php echo site_url('inventory/read/select2') ?>",
              dataType: 'json',
              data: function (params) {
                  var queryParameters = {
                      text: params.term
                  }
                  return queryParameters;
              }
          },
          cache: false,
          allowClear : true,
          minimumInputLength: 3,
          containerCssClass: ':all:',
          selectOnClose: true,
    });

    $("#select2_kd_barang").on('change', function(e) {
        $.ajax({
            url : "<?php echo site_url('inventory/read/harga')?>",
            type: "POST",
            dataType: "JSON",
            data: { "kd_barang": $("#select2_kd_barang").val()},
            success: function(data){
                $('#satuan').val(data.satuan);
                $("#harga").focus();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
                location.reload();

            }
      });
    });
    
    function ExportTo() {
        var tgl_awal = $("#tgl_awal").val();
        var tgl_akhir = $("#tgl_akhir").val();
        if(tgl_awal === ""){
          $('[name="tgl_awal"]').addClass(" is-invalid");
          $('[name="tgl_awal"]').focus();
          setTimeout(function () {
              $('[name="tgl_awal"]').removeClass(' is-invalid');
          }, 1000);
        }else if (tgl_akhir === ""){
          $('[name="tgl_akhir"]').addClass(" is-invalid");
          $('[name="tgl_akhir"]').focus();
          setTimeout(function () {
              $('[name="tgl_akhir"]').removeClass(' is-invalid');
          }, 1000);
        }else{
          window.location.href='<?= site_url('transaksi/export/masuk/'); ?>?tgl_awal=' + $("#tgl_awal").val() + '&tgl_akhir=' + $("#tgl_akhir").val() + '&cari=' + $("#cari").val();
        }
    };

</script>
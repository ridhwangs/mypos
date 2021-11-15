<div class="col-md-12">
    <div class="tile">
        <h3 class="tile-title"><i class="fas fa-cash-register"></i> Transaksi Keluar / Penjualan </h3> 
        <div class="tile-body">
            <div class="overlay" style="z-index:9999999;" id="tile-form-keluar">
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
                            
                            <form class="form-vertical" method="POST" action="<?= site_url('transaksi/create/keluar'); ?>" autocomplete="off" id="form-keluar">
                            <input class="form-control form-control-sm col-md-6" type="hidden" name="tanggal" placeholder="tanggal" id="tanggal" required>
                                <!-- <div class="form-group row">
                                    <label class="control-label col-md-2">Tanggal</label>
                                    <div class="col-md-10">
                                        <input class="form-control form-control-sm col-md-6" type="date" name="tanggal" placeholder="tanggal" id="tanggal" required>
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <label class="control-label col-md-2">Transaksi</label>
                                    <div class="col-md-10">
                                        <input class="form-control form-control-sm" type="text" name="kd_transaksi" value="<?= $this->uri->segment(3) ?>" id="kd_transaksi" placeholder="Kode Transaksi" readonly>
                                    </div>
                                </div>
                                <div class="form-group row pb-5">
                                    <label class="control-label col-md-2">Kode</label>
                                    <div class="col-md-10">
                                        <select class="" name="kd_barang" id="select2_kd_barang" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-2">Stock</label>
                                    <div class="col-md-10">
                                         <input class="form-control form-control-sm" type="number" placeholder="00.0" id="quantity_on_hand" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-2">Harga/Qty</label>
                                    <div class="col-md-10 input-group">
                                        <input class="form-control form-control-sm rupiah" type="text" name="harga" placeholder="Harga Satuan" id="harga" required>
                                        <input class="form-control form-control-sm col-md-5" type="number" name="qty" placeholder="00.0" id="qty" required>
                                       
                                        <input class="form-control form-control-sm" type="text" name="satuan" placeholder="Satuan" id="satuan" readonly>
                                    </div>
                                </div>
                                 <br>
                                <div class="float-right">
                                    <div class="col-md-12">
                                        <a href="<?= site_url('transaksi/keluar'); ?>">Transaksi Baru</a>
                                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-lg fa-check-circle"></i> Simpan</button>
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
                                        <th>Total Penjualan (Rp)</th>
                                        <th>Total Pembelian (Rp)</th>
                                        <th>Total Margin (Rp)</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($rekap_penjualan as $key => $rows) {
                                                echo '<tr>
                                                        <td>'.date('F', mktime(0, 0, 0, $rows->bulan, 10)).'</td>
                                                        <td>'.$rows->tahun.'</td>
                                                        <td><div class="text-center">'.$rows->jumlah_item.'</div></td>
                                                        <td><div class="text-right">'.number_format($rows->harga_jual, 0, ',', '.').'</div></td>
                                                        <td><div class="text-right">'.number_format($rows->harga_beli, 0, ',', '.').'</div></td>
                                                        <td><div class="text-right">'.number_format($rows->harga_jual-$rows->harga_beli, 0, ',', '.').'</div></td>
                                                    </tr>';
                                            } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tile-footer">
                        <table class="table table-sm table-hover table-bordered small" id="datatable">
                            <thead class="text-center">
                                <th class="bg-dark text-white" width="1px">No</th>
                                <th class="bg-dark text-white">Kode Barang</th>
                                <th class="bg-dark text-white">Nama Barang</th>
                                <th class="bg-dark text-white">Qty</th>
                                <th class="bg-dark text-white">Satuan</th>
                                <th class="bg-dark text-white">Harga Jual Satuan</th>
                                <th class="bg-dark text-white">Harga Jual</th>
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

<script>
    var tanggal = '<?= date('Y-m-d'); ?>';

    $("#tanggal").val(tanggal);
    
    
    var table;
    $(document).ready(function () {
        $('#select2_kd_barang').select2('open');
        $("#tile-form-keluar").hide();
        table = $('#datatable').DataTable({
            "paging": false,
            "searching": false,
            "ajax": {
                "url": "<?php echo site_url('transaksi/datatable/keluar'); ?>",
                "type": "POST",
                "data": function (data) {
                    data.kd_transaksi = $('#kd_transaksi').val();
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
    
    $('#form-keluar').submit(function() {
        $("#tile-form-keluar").show();
     
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
                if(data.validasi == false){
                    $("#qty").focus();
                }else{
                    $("#qty").val("");
                    $("#harga").val("");
                    $("#quantity_on_hand").val("");
                    $("#select2_kd_barang").empty();
                    $('#select2_kd_barang').select2('open');
                }
                $("#tile-form-keluar").hide();
                
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
    
    function hapus(kd_transaksi, kd_barang) {
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
                        url : "<?= site_url('transaksi/delete/keluar') ?>",
                        type: "POST",
                        dataType: "JSON",
                        data: { "kd_transaksi": kd_transaksi, "kd_barang": kd_barang },
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
                $('#harga').autoNumeric('set', data.harga);
                $('#quantity_on_hand').val(data.quantity_on_hand);
                $('#satuan').val(data.satuan);
                $("#qty").val('1');
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
          window.location.href='<?= site_url('transaksi/export/keluar/'); ?>?tgl_awal=' + $("#tgl_awal").val() + '&tgl_akhir=' + $("#tgl_akhir").val() + '&cari=' + $("#cari").val();
        }
    };
</script>
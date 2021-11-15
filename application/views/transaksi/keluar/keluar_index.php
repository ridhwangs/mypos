
<div class="col-md-12">
    <div class="tile" style="height: 80vh">
        <h3 class="tile-title"><i class="fas fa-cash-register"></i> <?= date('d F Y') ?></h3>
        <div class="row">
            <div class="col-md-4 border-right" >
                <div class="tile-body " style="min-height: 100%">
                    <div class="table-responsive" style="max-height:65vh;overflow-y: scroll;">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>No Transaksi</th>
                                    <th>Jumlah</th>
                                    <th width="1px">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sum_pendapatan = 0;
                                    foreach ($group_transaksi as $key => $grows) {
                                        $sum_pendapatan += $grows->jumlah;
                                        echo '<tr>
                                                <td><a href="'.site_url('transaksi/keluar/'.$grows->kd_transaksi).'">'.$grows->kd_transaksi.'</a></td>
                                                <td><div class="text-right">'.number_format($grows->jumlah, 0, ',', '.').'</div></td>
                                                <td>'.$grows->created_at.'</td>
                                            </tr>';
                                    } 
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-center btn-sm btn-outline-secondary rounded-0" colspan="3"><a class="text-dark" href="<?= site_url('transaksi/pendapatan?tanggal='. date('Y-m-d')); ?>">Lihat lebih banyak...</a></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <td>Total Pendapatan Harian</td>
                            <th><div class="text-right"><?= number_format($sum_pendapatan, 0, ',', '.') ?></div></th>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-8">
                <div class="tile-body">
                      <form class="form-vertical" method="POST" action="<?= site_url('transaksi/create/keluar'); ?>" autocomplete="off" id="form-keluar">
                            <input class="form-control form-control-sm col-md-6" type="hidden" name="tanggal" placeholder="tanggal" id="tanggal" required>
                            <input class="form-control form-control-sm" type="hidden" name="kd_transaksi" value="<?= $this->uri->segment(3) ?>" id="kd_transaksi" placeholder="Kode Transaksi" readonly>
                                 
                                <!-- <div class="form-group row">
                                    <label class="control-label col-md-2">Tanggal</label>
                                    <div class="col-md-10">
                                        <input class="form-control form-control-sm col-md-6" type="date" name="tanggal" placeholder="tanggal" id="tanggal" required>
                                    </div>
                                </div> -->
                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" id="kd_barang" name="kd_barang" autofocus required>
                                        </div>
                                        <?php if(!empty($this->session->flashdata('message'))): ?>
                                        <small id="passwordHelpBlock" class="form-text text-muted">
                                            <?= $this->session->flashdata('message'); ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                  
                                </div>
                                <button type="submit" class="btn btn-link p-0 mb-0"></button>
                                 <!-- <div class="form-group row">
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
                                </div> -->
                                    

                        </form>
                </div>
                <div class="tile-footer p-0" >
                    <div class="table-responsive" style="min-height:47vh;max-height:45vh;overflow-y: scroll;">
                        <table class="table table-sm table-hover table-bordered" >
                            <thead class="text-center">
                                <tr>
                                    <th>Nama</th>
                                    <th width="150px">Harga</th>
                                    <th width="100px">Qty</th>
                                    <th width="150px">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sum_qty = 0;
                                    $sum_total = 0;
                                    foreach ($detail as $key => $rows) {
                                        $inventory = $this->crud_model->read('inventory',['kd_barang' => $rows->kd_barang])->row();
                                        $sum_qty += $rows->qty;
                                        $sum_total += $rows->harga * $rows->qty;
                                        ?>
                                             <form class="form-vertical" method="POST" action="<?= site_url('transaksi/update/penjualan'); ?>" id="form-details" autocomplete="off" >
                                             <input type="hidden" name="tk_id" value="<?= $rows->tk_id ?>">
                                        <?php
                                        echo '<tr>
                                                <td>'.$inventory->nm_barang.'</td>
                                                <td><span class="float-left">Rp.</span><div class="text-right">'.number_format($rows->harga, 0, ',', '.').'</div></td>
                                                <td class="text-center"><input type="number" name="qty" id="qty_row" class="form-control form-control-sm rounded-0 text-center" value="'.$rows->qty.'" required></td>
                                                <td><span class="float-left">Rp.</span> <div class="text-right">'.number_format($rows->harga * $rows->qty, 0, ',', '.').'</div></td>
                                            </tr>';
                                        ?>
                                            </form>
                                        <?php
                                    } 
                                ?>
                            </tbody>
                      
                        </table>
                    </div>
                    <table class="table table-sm">
                        <form method="POST" action="<?= site_url('transaksi/pembayaran'); ?>" autocomplete="off">
                        <input type="hidden" name="kd_transaksi" value="<?= $this->uri->segment(3); ?>">
                        <input type="hidden" name="jumlah" value="<?= $sum_total; ?>">
                            <tr>
                                <th class="text-right" colspan="3">Total</th>
                                <th width="350px"><div class="text-right"><span class="float-left">Rp.</span><?= number_format($sum_total, 0, ',', '.') ?></div></th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="3">Bayar</th>
                                <th><input class="form-control form-control-sm rupiah" type="text" name="bayar" placeholder="Pembayaran (Rp.)" id="bayar" required></th>
                            </tr>
                        </form>
                        <tr>
                            <th colspan="4"><i><?= ucwords(number_to_words($sum_total)); ?> Rupiah</i></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var tanggal = '<?= date('Y-m-d'); ?>';
    <?php if(!empty($this->session->flashdata('autofocus'))): ?>
        $("#<?= $this->session->flashdata('autofocus'); ?>").addClass(' is-invalid').focus();
    <?php endif; ?>
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
    
    $('#form-keluars').submit(function() {
        $("#tile-form-keluar").show();
     
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "JSON",
            success: function(data) {
               
                $.notify({
                    title: data.title,
                    message: data.message,
                    icon: data.icon
                },{
                    type: data.type,
                });
             
                $("#kd_barang").val("").focus();
                // $("#table-details").load(" #table-details > *");
                location.reload();
                
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
    }
    $('body').on("keydown", function(e) {
            if(e.keyCode === 37){ // Check for the Ctrl key being pressed, and if the key = [S] (83)
                $("#kd_barang").focus();
                return false;
            }
            if(e.keyCode === 39){ // Check for the Ctrl key being pressed, and if the key = [S] (83)
                $("#bayar").focus();
                return false;
            }
      });
</script>
<style>
  .separator {
    display: flex;
    align-items: center;
    text-align: center;
  }

  .separator::before,
  .separator::after {
    content: '';
    flex: 1;
    border-bottom: 1px dotted #000;
  }

  .separator:not(:empty)::before {
    margin-right: .25em;
  }

  .separator:not(:empty)::after {
    margin-left: .25em;
  }
</style>
<div class="app-title">
  <div>
    <h1><i class="fas fa-dolly-flatbed"></i> Inventory</h1>
    <p>
      
    </p>
    
  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><?= $perusahaan->nama_perusahaan ?></li>
    <li class="breadcrumb-item active"><a href="#">Inventory</a></li>
  </ul>
</div>
<div class="row">
  <div class="col-md-3">
    <div class="tile">
        <div class="tile-title-w-btn">
            <h4 class="title">
              <i class="fas fa-filter"></i> Filter
            </h4>
        </div>
      <div class="tile-body">
        <form class="form-horizontal" autocomplete="off" id="form-filter">
              <div class="form-group row">
                <label for="table" class="col-sm-4 col-form-label col-form-label-sm float-right small">Status </label>
                <div class="col-sm-8">
                  <select id="status" class="form-control form-control-sm">
                    <option value="">Tampilkan Semua</option>
                    <option value="1">Aktif</option>
                    <option value="2">Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="table" class="col-sm-4 col-form-label col-form-label-sm float-right small">Berdasarkan </label>
                <div class="col-sm-8">
                  <select id="table" class="form-control form-control-sm" onchange="berdasarkan()">
                    <option value="">Tampilkan Semua</option>
                    <option value="kd_barang">Kode Barang</option>
                    <option value="nm_barang">Nama Barang</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="cari" class="col-sm-4 col-form-label col-form-label-sm float-right small">Kata kunci</label>
                <div class="col-sm-8">
                  <input type="text" name="cari" id="cari" class="form-control form-control-sm cari">
                </div>
              </div>
              <div class="separator mb-2">Filter lainya</div>
              <div class="form-group row">
                <div class="col-sm-12">
                  <select name="filter_lainya" id="filter_lainya" class="form-control form-control-sm">
                    <option value="">Tampilkan Semua</option>
                    <option value="4">Stock mencapai batas minumum</option>
                    <option value="1">Stock sudah habis</option>
                    <option value="2">Stock di bawah batas minimum</option>
                    <option value="3">Stock di atas batas minimum</option>
                  </select>
                </div>
              </div>
        </form>
    
      </div>
      <div class="tile-footer">
        <button type="button" class="btn btn-secondary btn-sm rounded-0" onclick="reset_form()">Reset</button>
        <button type="submit" form="form-filter" class="btn btn-sm btn-primary rounded-0"><i class="fas fa-filter"></i> Filter</button>
      </div>
    </div>
  </div>
  <div class="col-md-9">
      <div class="tile">
          <div class="tile-title-w-btn">
            <h4 class="title">
             
            </h4>
            <p> <a class="btn btn-sm btn-primary rounded-0" href="javascript:void(0);" onclick="tambah()"><i class="fa fa-plus"></i> Tambah Baru</a> </p>
          </div>
          <div class="overlay tile-loading" style="z-index: 9;">
            <div class="m-loader mr-4">
              <svg class="m-circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"></circle>
              </svg>
            </div>
            <h3 class="l-text">Loading</h3>
          </div>
          <div class="tile-body">
              <div class="row">
                
                <div class="col-md-12">
                
                  <table class="table table-sm table-hover table-striped table-bordered" id="datatable">
                    <thead class="text-center">
                        <th class="bg-dark text-white" width="1px">No</th>
                        <th class="bg-dark text-white">Kode Barang</th>
                        <th class="bg-dark text-white">Nama Barang</th>
                        <th class="bg-dark text-white" width="1px">Stock</th>
                        <th class="bg-dark text-white" width="1px">Minimum</th>
                        <th class="bg-dark text-white">Price List</th>
                        <th class="bg-dark text-white">Satuan</th>
                        <th class="bg-dark text-white">Keterangan</th>
                        <th class="bg-dark text-white" width="1px">Terakhir Beli</th>
                        <th class="bg-dark text-white" width="1px">Terakhir Jual</th>
                        <th class="bg-dark text-white" width="1px"></th>
                        <th class="bg-dark text-white" width="1px"></th>
                        <th class="bg-dark text-white" width="1px"></th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="tile-footer mb-0">
              <button class="btn btn-outline-primary btn-sm rounded-0"  onclick="ExportTo();">Download (.xlsx)</button>
            </div>
      </div>
      <div class="tile-footer">
        
      </div>
  </div>
  </div>
</div>

<div class="clearix"></div>

<!-- Modal -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalCenterTitle">Update Kode Barang <span id="html_kd_barang"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="tile-body">
        <form class="form-horizontal" method="POST" action="<?= site_url('inventory/update/inventory'); ?>" autocomplete="off" id="form-edit">
          <input type="hidden" name="id" id="id">
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Kode</label>
            <div class="col-md-5">
              <input class="form-control small text-uppercase" type="text" name="kd_barang" placeholder="Kode Barang" id="e_kd_barang" required readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Nama</label>
            <div class="col-md-9">
              <input class="form-control small" type="text" name="nm_barang" placeholder="Nama Barang" id="e_nm_barang" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Stock Akhir</label>
            <div class="col-md-9">
              <input class="form-control small col-md-4" type="number" name="quantity_on_hand" id="e_quantity_on_hand" placeholder="00.0">
            </div>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Stock Minimum</label>
            <div class="col-md-9">
              <input class="form-control small col-md-4" type="number" name="minimum_stock" id="e_minimum_stock" placeholder="00.0">
            </div>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Harga Jual</label>
            <div class="col-md-9">
              <input class="form-control small col-md-6 rupiah" type="text" name="harga" id="e_harga" placeholder="Harga">
            </div>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Satuan</label>
            <div class="col-md-9">
              <input class="form-control small col-md-6" type="text" name="satuan" id="e_satuan" placeholder="Satuan">
            </div>
          </div>
          <div class="form-group-textarea row">
            <label class="control-label control-label-sm col-md-3">Keterangan</label>
            <div class="col-md-9">
              <textarea class="form-control small" name="remarks" id="e_remarks"></textarea>
            </div>
          </div>
        </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="form-edit" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i>Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Barang baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="tile-body">
        <form class="form-horizontal" method="POST" action="<?= site_url('inventory/create/inventory'); ?>" autocomplete="off" id="form-inventory">
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Kode</label>
            <div class="col-md-5">
              <input class="form-control small text-uppercase" type="text" name="kd_barang" placeholder="Kode Barang" id="kd_barang" required autofocus>
            </div>
            <label class="control-label control-label-sm col-md-4"><a href="javascript:void(0)" onclick="generate_token(6)">Generate Kode</a></label>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Nama</label>
            <div class="col-md-9">
              <input class="form-control small" type="text" name="nm_barang" placeholder="Nama Barang" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Stock Akhir</label>
            <div class="col-md-9">
              <input class="form-control small col-md-4" type="number" name="quantity_on_hand" placeholder="00.0">
            </div>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Stock Minimum</label>
            <div class="col-md-9">
              <input class="form-control small col-md-4" type="number" name="minimum_stock" placeholder="00.0">
            </div>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Harga Jual</label>
            <div class="col-md-9">
              <input class="form-control small col-md-6 rupiah" type="text" name="harga" placeholder="Harga Jual">
            </div>
          </div>
          <div class="form-group row">
            <label class="control-label control-label-sm col-md-3">Satuan</label>
            <div class="col-md-9">
              <input class="form-control small col-md-6" type="text" name="satuan" placeholder="Satuan">
            </div>
          </div>
          <div class="form-group-textarea row">
            <label class="control-label control-label-sm col-md-3">Keterangan</label>
            <div class="col-md-9">
              <textarea class="form-control small" name="remarks"></textarea>
            </div>
          </div>
        </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="form-inventory" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i>Tambah</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalCenterTitle">Import Inventory / Data Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="tile-body">
        <form class="form-horizontal" method="POST" action="<?= site_url('inventory/import/inventory'); ?>" autocomplete="off" id="form-import" enctype="multipart/form-data">
          <div class="form-group row">
            <div class="col-md-12">
              <input type="file" id="files" name="berkas" required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  />
            </div>
          </div>
        </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="form-import" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i>Import</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

      var table;
      $(document).ready(function () {
          berdasarkan();
          $("#tile-form").hide();
          table = $('#datatable').DataTable({
              "lengthMenu": [[15, 25, 50, -1], [15, 25, 50, "All"]],
              "searching": false,
              "pagingType": "full_numbers",
              "stateSave": true,
              "ajax": {
                  "url": "<?php echo site_url('inventory/datatable/inventory'); ?>",
                  "type": "POST",
                  "data": function (data) {
                    data.table = $("#table").val();
                    data.cari = $("#cari").val();
                    data.filter_lainya = $("#filter_lainya").val();
                    data.status = $("#status").val();
                  },
                  "dataSrc": function(json) {
                      //Make your callback here.
                      $('.tile-loading').hide();
                      return json.data;
                  }
              },

                "columnDefs": [
                  {
                      "targets": [0, -1, -2, -3],
                      "orderable": false,
                  },

              ],
          });
      });

      function reload_table() {
        $('.tile-loading').show();
          table.ajax.reload(null, true);
      }

      function reset_form() {
        $("#form-filter")[0].reset();
        berdasarkan();
        reload_table();
      }

      $('#form-filter').submit(function() {
        $.ajax({
          success: function(data) {
            $('#modal-filter').modal('hide');
            
            reload_table();
            table.fnPageChange(0);
            table.fnReloadAjax();
            return false;
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $.notify({
              title: textStatus,
              message: jqXHR,
            }, {
              type: "danger",
            });
            $('#cover-spin').hide(0);
          }
        })
        return false;
      });
      
      function berdasarkan() {
        var table = $("#table").val();
        if (table === "") {
          $("#cari").attr('disabled', true);
          
          $('#table').on('change', function() {
            $("#cari").val("").focus().prop('required',true);;
          });
        } else {
          $("#cari").attr('disabled', false).prop('required',false);;
        }
      }
      
      function generate_token(length) {
        $('#cover-spin').show(0);
         var result           = '';
         var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
         var charactersLength = characters.length;
         for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
         }
         setTimeout(function () {
             $("#kd_barang").val(result);
             $('#cover-spin').hide(0);
         }, 500);

      }
      
      $('#form-inventory').submit(function() {
        $("#tile-form").show();
          $.ajax({
              type: 'POST',
              url: $(this).attr('action'),
              data: $(this).serialize(),
              dataType: "JSON",
              success: function(data) {
                reload_table();
                document.getElementById("form-inventory").reset();
                $("#tile-form").hide();
                $("#kd_barang").focus();
                $.notify({
                  title: data.title,
                  message: data.message,
                  icon: data.icon
                },{
                  type: data.type,
                });
              },
              error: function (jqXHR, textStatus, errorThrown, data) {
                $.notify({
                    title: "Oops!",
                    message: "Terjadi kesalahan, silahkan coba kembali"
                },{
                    type: 'danger'
                });
              }
          })
          return false;
      });

      $('#form-edit').submit(function() {
          $('#cover-spin').show(0);
          $.ajax({
              type: 'POST',
              url: $(this).attr('action'),
              data: $(this).serialize(),
              dataType: "JSON",
              success: function(data) {
                reload_table();
                document.getElementById("form-edit").reset();
                $('#modal-edit').modal('hide');
                $('#cover-spin').hide(0);
                $.notify({
                  title: data.title,
                  message: data.message,
                  icon: data.icon
                },{
                  type: data.type,
                });
              },
              error: function (jqXHR, textStatus, errorThrown, data) {
                $.notify({
                    title: "Oops!",
                    message: "Terjadi kesalahan, silahkan coba kembali"
                },{
                    type: 'danger'
                });
              }
          })
          return false;
      });

      function tambah() {
        $('#modal-tambah').modal('show');
        $('#modal-tambah').on('shown.bs.modal', function () {
            $('#kd_barang').trigger('focus');
        });
      }

      function import_file() {
        $('#modal-import').modal('show');
      }

      function filter_table() {
        $('#modal-filter').modal('show');
      }


      function edit(id) {
        $('#cover-spin').show(0);
        $.ajax({
            url : "<?php echo site_url('inventory/read/inventory')?>",
            type: "POST",
            dataType: "JSON",
            data:{"id" : id},
            success: function(data){
                $('#cover-spin').hide(0);
                $('#modal-edit').modal('show');
                $("#id").val(data.id);
                $("#e_kd_barang").val(data.kd_barang);
                $("#html_kd_barang").html(data.kd_barang);
                $("#e_nm_barang").val(data.nm_barang);
                $("#e_quantity_on_hand").val(data.quantity_on_hand);
                $("#e_minimum_stock").val(data.minimum_stock);
                $('#e_harga').autoNumeric('set', data.harga);
                $("#e_satuan").val(data.satuan);
                $("#e_remarks").val(data.remarks);
                $('#modal-edit').on('shown.bs.modal', function () {
                    $('#e_harga').trigger('focus');
                });
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                // $('#cover-spin').hide(0);
                alert('Error get data from ajax');
                location.reload();
            }
        });
      }

      function update(id, action) {
        var text, url;
        if(action == "hapus"){
          text = "Anda yakin akan Mengunci Kode ini?";
          url = "<?= site_url() ?>/inventory/update/delete";
        }else{
          text = "Anda yakin akan Membuka kunci ini?";
          url = "<?= site_url() ?>/inventory/update/return";
        }
        Swal.fire({
              title: 'Peringatan!',
              text: text,
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33'
            }).then((result) => {
              if (result.value) {
                $('#cover-spin').show(0);
                $.ajax({
                      url : url,
                      type: "POST",
                      dataType: "JSON",
                      data: { "id": id },
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


      function open_popup(url){
          $('#cover-spin').show(0);
          params  = 'width='+screen.width/2;
          params += ', height='+screen.height;
          params += ', top=0, left=0'
          params += ', fullscreen=yes';
          params += ', directories=no';
          params += ', location=no';
          params += ', menubar=no';
          params += ', resizable=no';
          params += ', status=no';
          params += ', toolbar=no';
          myWindow=window.open(url,'Pop Up',params);
          // Add this event listener; the function will be called when the window closes

            myWindow.onbeforeunload = function(){
              setTimeout(function() {
                $('#cover-spin').hide(0);
                reload_table();
              }, 500);
            };

          if (window.focus) {myWindow.focus()}
          return false;
      }

      function ExportTo() {
          var table = $("#table").val(),
              cari = $("#cari").val(),
              filter_lainya = $("#filter_lainya").val(),
              status = $("#status").val();
          window.location.href='<?= site_url('inventory/export'); ?>?table='+table+'&cari='+cari+'&filter_lainya='+filter_lainya+'&status='+status;
      };

    </script>
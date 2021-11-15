<div class="col-md-12">
    <div class="tile">
        <h3 class="tile-title">[Pengaturan] Tipe Transaksi <a href="javascript:void(0)" data-toggle="modal" data-target="#exampleModalCenter"> Tambah <i class="fas fa-plus"></i></a> </h3> 
        <table class="table table-striped table-bordered" id="editable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Transaksi</th>
            </thead>
            <tbody>
                <?php
                    $no = 0;
                    foreach ($transaction_types as $key => $rows) {
                        $no++;
                        echo '<tr id="'.$rows->id.'">
                                <td>'.$rows->id.'</td>
                                <td>'.$rows->code.'</td>
                                <td>'.$rows->name.'</td>
                            </tr>';
                    } 
                ?>
            </tbody>
        </table>
    </div>
    <div class="tile-footer">
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Tipe Transaksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="tile-body">
            <form id="form-tipe" class="form-horizontal" method="POST" action="<?= site_url('pengaturan/create/tipe'); ?>" autocomplete="off">
                <div class="form-group row">
                    <label class="control-label col-md-4">Kode Transaksi</label>
                    <div class="col-md-7">
                    <input class="form-control col-md-6" type="text" placeholder="Kode Transaksi" name="code" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label col-md-4">Nama Transaksi</label>
                    <div class="col-md-7">
                    <input class="form-control " type="text" placeholder="Nama Transaksi" name="name" required>
                    </div>
                </div>
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="form-tipe" class="btn btn-primary">Tambah</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
    $('#editable').Tabledit({
      deleteButton: true,
      editButton: true,
      restoreButton: false,
      columns: {
          identifier: [0, 'id'],
          editable: [[1, 'code'], [2, 'name']]
        },
      hideIdentifier: true,
      url: "<?= site_url("pengaturan/update/tipe"); ?>",
      onSuccess: function(data, textStatus, jqXHR) {
        $.notify({
            title: data.title,
            message: data.message,
            icon: data.icon
          },{
            type: data.type,
          });
      }
    });
  });
</script>
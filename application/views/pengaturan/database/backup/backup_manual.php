<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div><!-- /.col -->
                <div class="col-sm-6">
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-1">
                            <h3>Manual Database</h3>
                        </div>
                        <div class="card-body p-3">
                            <a class="btn btn-primary btn-sm icon-btn " href="javascript:void(0)" onclick="backup();"><i class="fa fa-database"></i> Backup Database</a>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

<script>
    function backup() {
        $('#cover-spin').show(0);
        if (confirm('Anda yakin akan backup Database?')) {
            location.href = "<?= site_url('pengaturan/backup/create/backup') ?>";
            $.notify({
                title: "Berhasil",
                message: "Backup database berhasil..",
                icon: "success"
            }, {
                type: "success",
            });

        } else {
            $('#cover-spin').hide(0);

        }
    }
</script>
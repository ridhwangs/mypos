<div class="app-title fadeInDown">
  <div>
    <h1><i class="fa fa-dashboard"></i> <?= $page_header; ?></h1>
  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><?= anchor('beranda','Beranda') ?></li>
  </ul>
</div>
<div class="container">
<div class="row d-flex justify-content-center">

  <div class="col-md-6 fadeInLeft">
    <?php if(!empty($this->session->flashdata('message'))): ?>
        <div class="alert alert-success small" role="alert">
            <?= $this->session->flashdata('message') ?>
        </div>
    <?php endif; ?>
    <div class="tile">
        <div class="tile-header">
          <div class="row d-flex justify-content-center">
            <img src="<?= base_url('assets/logo/'. $perusahaan->logo) ?>" width="150px">
          </div>
        </div>
        <hr>
        <div class="tile-body">
          <form class="form-horizontal" method="POST" action="<?= site_url('pengaturan/update/perusahaan'); ?>" autocomplete="off" id="form-setting" enctype="multipart/form-data">
              <div class="form-group-textarea row">
                <label for="nama_perusahaan" class="col-sm-12 col-form-label col-form-label-sm float-right small">Nama Perushaan </label>
                <div class="col-sm-12">
                  <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control form-control-sm" value="<?= $perusahaan->nama_perusahaan ?>" required>
                </div>
              </div>
              <div class="form-group-textarea row">
                <label for="alamat" class="col-sm-12 col-form-label col-form-label-sm float-right small">Alamat </label>
                <div class="col-sm-12">
                  <input type="text" name="alamat" id="alamat" class="form-control form-control-sm" value="<?= $perusahaan->alamat ?>" required>
                </div>
              </div>
              <div class="form-group-textarea row">
                <label for="no_telp" class="col-sm-12 col-form-label col-form-label-sm float-right small">No Telp </label>
                <div class="col-sm-12">
                  <input type="text" name="no_telp" id="no_telp" class="form-control form-control-sm" value="<?= $perusahaan->no_telp ?>" required>
                </div>
              </div>
              <div class="form-group-textarea row">
                <label for="no_telp" class="col-sm-12 col-form-label col-form-label-sm float-right small">Logo </label>
                <div class="col-sm-12">
                  <input type="file" name="berkas" />
                </div>
              </div>
          </form>
        </div>
        <div class="tile-footer">
          <button class="btn btn-sm btn-block btn-primary rounded-0" form="form-setting">Simpan</button>
        </div>
    </div>
    
  </div>

</div>
</div>



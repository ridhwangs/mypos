<div class="container">
<div class="row d-flex justify-content-center">

  <div class="col-md-6 fadeInLeft">
    <?php if(!empty($this->session->flashdata('message'))): ?>
        <div class="alert alert-success small" role="alert">
            <?= $this->session->flashdata('message') ?>
        </div>
    <?php endif; ?>
    <div class="tile">
        <div class="tile-header mb-3">
          <div class="row d-flex justify-content-center">
            <img src="<?= base_url('assets/logo/'. $perusahaan->logo) ?>" width="150px">
          </div>
        </div>
        <div class="tile-body">
            <table class="table table-sm">
                <tr>
                    <td width="100px">Total</td>
                    <td width="1px">:</td>
                    <td>Rp. <?= number_format($jumlah, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <th></th>
                    <th colspan="2"><i><?= ucwords(number_to_words($jumlah)); ?> Rupiah</i></th>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td>:</td>
                    <td>Rp. <?= number_format($bayar, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <th></th>
                    <th colspan="2"><i><?= ucwords(number_to_words($bayar)); ?> Rupiah</i></th>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td>:</td>
                    <td>Rp. <?= number_format($kembali, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <th></th>
                    <th colspan="2"><i><?= ucwords(number_to_words($kembali)); ?> Rupiah</i></th>
                </tr>
            </table>
        </div>
        <div class="tile-footer p-2">
          <a href="<?= site_url('transaksi/keluar'); ?>" id="btn-back" class="btn btn-sm btn-primary rounded-0 " autofocus><i class="fas fa-caret-left"></i> Kembali</a>
        </div>
    </div>
    
  </div>

</div>
</div>
<script>
    $("nav").hide();
    $("#btn-back").focus();
</script>
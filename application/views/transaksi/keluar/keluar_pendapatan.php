<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <a href="<?= site_url('transaksi/keluar'); ?>" class="btn btn-sm btn-danger rounded-0 mb-2" style="margin-top:-20px;"> Kembali</a>
            <div class="tile" style="height: 80vh">
                <div class="title">
                    <form type="GET">
                        <div class="form-group row">
                            <label class="control-label col-md-2">Tanggal</label>
                            <div class="col-md-4">
                                <input class="form-control form-control-sm" type="date" name="tgl_awal" value="<?= $this->input->get('tgl_awal') ?>" id="tgl_awal" placeholder="Tanggal" required>
                            </div>
                            <label class="control-label col-md-2">Hingga</label>
                            <div class="col-md-4">
                                <input class="form-control form-control-sm" type="date" name="tgl_akhir" value="<?= $this->input->get('tgl_akhir') ?>" id="tgl_akhir" placeholder="Tanggal" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-primary rounded-0 btn-block"><i class="fas fa-filter"></i> Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tile-body" style="min-height: 100%">
                    <div class="table-responsive" style="max-height:55vh;overflow-y: scroll;">
                        <table class="table table-sm table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="1px">No.</th>
                                    <th>No Transaksi</th>
                                    <th>Jumlah</th>
                                    <th width="1px">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sum_pendapatan = 0;
                                    $no = 0;
                                    foreach ($group_transaksi as $key => $grows) {
                                        $no++;
                                        $sum_pendapatan += $grows->jumlah;
                                        echo '<tr>
                                                <td>'.$no.'</td>
                                                <td><a href="'.site_url('transaksi/keluar/'.$grows->kd_transaksi).'">'.$grows->kd_transaksi.'</a></td>
                                                <td><div class="text-right">'.number_format($grows->jumlah, 0, ',', '.').'</div></td>
                                                <td>'.$grows->created_at.'</td>
                                            </tr>';
                                    } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <td>Total Pendapatan</td>
                            <th><div class="text-right"><?= number_format($sum_pendapatan, 0, ',', '.') ?></div></th>
                        </tr>
                    </table>
                </div>    
            </div>
        </div>
    </div>
</div>
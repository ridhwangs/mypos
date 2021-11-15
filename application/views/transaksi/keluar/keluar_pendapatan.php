<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="tile" style="height: 80vh">
                <div class="title">
                    <form type="GET">
                        <div class="input-group">
                            <a href="<?= site_url('transaksi/keluar'); ?>" id="btn-back" class="btn btn-sm btn-primary rounded-0 " autofocus><i class="fas fa-caret-left"></i> Kembali</a>
                            <input class="form-control" type="date" id="tanggal" name="tanggal" value="<?= $this->input->get('tanggal') ?>" autofocus required>
                            <button type="submit" class="rounded-0">Filter</button>
                        </div>
                    </form>
                </div>
                <div class="tile-body " style="min-height: 100%">
                 
                    <hr>
                    <div class="table-responsive" style="max-height:60vh;overflow-y: scroll;">
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
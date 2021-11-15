<div class="app-title fadeInDown">
  <div>
    <h1><i class="fa fa-dashboard"></i> <?= $page_header; ?></h1>
  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><?= anchor('beranda','Beranda') ?></li>
  </ul>
</div>
<div class="row">
  <div class="col-md-6 fadeInLeft">
    <div class="tile">
      <h3 class="tile-title">Chart Penjualan <?= date('Y'); ?></h3>
      <div class="embed-responsive embed-responsive-16by9">
        <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6 fadeInRight">
    <div class="tile">
      <h3 class="tile-title">Support Requests</h3>
      <div class="embed-responsive embed-responsive-16by9">
        <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
      </div>
    </div>
  </div>
</div>
<div class="row fadeInUp">
  <div class="col-md-6">
    <div class="tile">
      <h3 class="tile-title">CI Version <?= CI_VERSION ?></h3>
      <ul>
        <li><?= $_SERVER['HTTP_USER_AGENT']; ?></li>
      </ul>
    </div>
  </div>
 <div class="col-md-6">
    <div class="tile">
      <h3 class="tile-title">PHP Version <?= PHP_VERSION ?></h3>
      <ul>
        <li><?= $_SERVER['SERVER_SOFTWARE']; ?></li>
      </ul>
    </div>
  </div>

</div>

<!-- Page specific javascripts-->
<script type="text/javascript" src="<?= base_url('assets/vali-admin/docs/'); ?>js/plugins/chart.js"></script>
<script type="text/javascript">
<?php
    $harga_beli = [];
    foreach ($rekap_penjualan as $key => $rows) {
      array_push($harga_beli, $rows->harga_beli);
    } 
?>
<?php
    $harga_jual = [];
    foreach ($rekap_penjualan as $key => $rows) {
      array_push($harga_jual, $rows->harga_jual);
    } 
?>
  var data = {
    labels: [
      <?php
          foreach ($rekap_penjualan as $key => $rows) {
              echo '"'.date('F', mktime(0, 0, 0, $rows->bulan, 10)).'",';
          } 
      ?>
    ],
    datasets: [
      {
        label: "Chart Penjualan",
        fillColor: "rgba(220,220,220,0.2)",
        strokeColor: "rgba(220,220,220,1)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [<?= implode(',',$harga_jual); ?>]
      },
  
    ]
  };
  var pdata = [
    {
      value: 300,
      color: "#46BFBD",
      highlight: "#5AD3D1",
      label: "Complete"
    },
    {
      value: 50,
      color:"#F7464A",
      highlight: "#FF5A5E",
      label: "In-Progress"
    }
  ]

  var ctxl = $("#lineChartDemo").get(0).getContext("2d");
  var lineChart = new Chart(ctxl).Line(data);

  var ctxp = $("#pieChartDemo").get(0).getContext("2d");
  var pieChart = new Chart(ctxp).Pie(pdata);
</script>
<div class="app-title">
    <div>
      <h1><i class="fa fa-user"></i> <?php echo lang('index_heading');?></h1>
      <p><?php echo lang('index_subheading');?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item active"><a href="<?php base_url($page_header); ?>"><?= $page_header; ?></a></li>
    </ul>
</div>

  <?php $this->load->view($others); ?>

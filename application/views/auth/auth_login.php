
<div class="login-box">
  <?php
    $hidden = array('controller' => $this->uri->segment(3),'page' => $this->uri->segment(4));
    $attributes = array('autocomplete' => 'off', 'class' => 'login-form');
    echo form_open('auth/login', $attributes, $hidden);
   ?>
    <h3 class="login-head"><i class="fas fa-user-lock fa-lg"></i> <?php echo lang('login_heading');?> Form</h3>
    <!-- <?php echo lang('login_subheading');?> -->
  
            <?php echo $message;?>
    <div class="form-group-login">
        <?php echo form_input($identity,'','class="form-control" placeholder="Username" autofocus required');?>
    </div>
    <div class="form-group-login">
      <?php echo form_input($password,'','class="form-control" placeholder="Password" required');?>
    </div>
    <div class="form-group-login">
      <div class="utility">
        <div class="checkbox">
          <?php echo form_checkbox('remember', '1', TRUE, 'id="remember"');?>
          <?php echo lang('login_remember_label', 'remember');?>
        </div>
      </div>
    </div>
    <div class="form-group-login btn-container">
      <?php echo form_submit('submit', lang('login_submit_btn'),'class="btn btn-primary btn-block"');?>
    </div>
  <?= form_close(); ?>
</div>

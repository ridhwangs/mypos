<?php 
$user = $this->ion_auth->user()->row();
?>
<li class="nav-item dropdown"><a class="app-nav__item" href="javascript:void(0)" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i> <?= $user->first_name; ?> <?= $user->last_name; ?></a>
  <ul class="dropdown-menu settings-menu dropdown-menu-right">
    <li><?= anchor('auth/logout', '<i class="fas fa-sign-out-alt"></i> Logout', 'class="dropdown-item"') ?></li>
  </ul>
</li>

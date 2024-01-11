<?php

use app\bootstrap\form\Form;
use app\core\Session;

$errors = [];
if (Session::check('errors')) {
  $errors = Session::get('errors');
}
?>

<?php $form = \app\bootstrap\form\Form::begin(' ', 'post', $user, $errors) ?>

<div class="mb-3">
  <?php echo $form->field('Ad soyad', 'full_name') ?>
</div>

<div class="mb-3">
  <?php echo $form->field('Email', 'email', 'email') ?>
</div>

<div class="mb-3">
  <?php echo $form->field('Sifre', 'password', 'password') ?>
</div>

<div class="mb-3">
  <?php echo $form->field('Sifre tekrari', 'password_confirmation', 'password') ?>
</div>

<button type="submit" class="btn btn-primary">Submit</button>
<?php \app\bootstrap\form\Form::end() ?>

<!-- <form action="" method="post">
  <div class="mb-3">
    <label for="full_name" class="form-label">Full name</label>
    <input name="full_name" value="<?= $user->full_name ?>" class="form-control" id="full_name" aria-describedby="emailHelp">
    <?php
    if (isset($errors['full_name'])) {
      echo '<div class="badge bg-danger invaild-feedback">' . getError("full_name", $errors) . '</div>';
    }
    ?>

  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input name="email" value="<?= $user->email ?>" type="email" class="form-control" id="email">
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input name="password" value="<?= $user->password ?>" type="password" class="form-control" id="password">
  </div>
  <div class="mb-3">
    <label for="password_confirmation" class="form-label">Password Confirmation</label>
    <input name="password_confirmation" type="password_confirmation" class="form-control" id="password_confirmation">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form> -->
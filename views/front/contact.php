<?php

use app\core\Session;

if (Session::check('errors')) {
  foreach (Session::get('errors') as $key => $value) {
    echo '<span class="badge bg-danger">' . $value[0] . '</span>';
  }
}


?>


<form action="" method="post">
  <div class="mb-3">
    <label for="subject" class="form-label">Subject</label>
    <input name="subject" class="form-control" id="subject" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input name="email" type="email" class="form-control" id="email">
  </div>
  <div class="mb-3">
    <label for="message" class="form-label">Message</label>
    <textarea name="message" class="form-control" id="message" cols="30" rows="10"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
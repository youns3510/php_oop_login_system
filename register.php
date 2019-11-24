<?php

include_once __DIR__.'/classes/user.php';
$user = new User();
if (isset($_POST['submit'])) {
    // echo 'posted';
    $user->firstName = $_POST['firstName'];
    $user->lastName = $_POST['lastName'];
    $user->contactNumber = $_POST['contactNumber'];
    $user->address = $_POST['address'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    // var_dump($_POST);
    $user->register();
}
$page_title = 'Register';
include_once __DIR__.'/layouts/header.php';
?>
<div class="col-6 m-auto  border">
  <form action="register.php" method="post">
    <h4 class="h4 text-center">Register</h4>
    <div class="form-group">
      <label>Firstname</label>
      <input type="text" class="form-control" placeholder="Type Your  Firstname" name="firstName">
    </div>
    <div class="form-group">
      <label>Lastname</label>
      <input type="text" class="form-control" placeholder="Type Your Lastname" name="lastName">
    </div>
    <div class="form-group">
      <label>Contact Number</label>
      <input type="text" class="form-control" placeholder="Type Your Contact Number" name="contactNumber">
    </div>
    <div class="form-group">
      <label>Address</label>
      <textarea class="form-control" placeholder="Type Your Address" name="address"></textarea>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" class="form-control" placeholder="Type Your Email" name="email">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" class="form-control" placeholder="Type Your Password" name="password">
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-success" name="submit">Register</button>
    </div>
  </form>
</div>
<?php
include_once __DIR__.'/layouts/footer.php';

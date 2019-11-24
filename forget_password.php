<?php
$page_title = "Forget Password";
include_once __DIR__ . '/classes/user.php';
if (isset($_POST['submit'])) {
  $user = new User();
  $user->email = $_POST['email'];
  $user->forgetPassword();
}
if (isset($_POST['submit_password']) && isset($_POST['password_token'])) {

  $user = new User();
  $user->token = $_POST['password_token'];
  $user->password  = $_POST['password'];
  $user->password2 = $_POST['password2'];
  $user->resetPassword();
}

include_once(__DIR__ . '/layouts/header.php');

if (isset($_GET['password_token'])) {
  $token = $_GET['password_token'];
  ?>
  <div class="col-6 m-auto border">
    <form method="post" action="forget_password.php?password_token=<?php echo $token; ?>">
      <h4 class="h4 text-center">Reset Password</h4>
      <input type="hidden" name="password_token" value="<?php echo $token; ?>">
      <div class="form-group">
        <label>New Password</label>
        <input type="password" class="form-control" placeholder="Type New Password" name="password">
      </div>
      <div class="form-group">
        <label> Confirm New Password</label>
        <input type="password" class="form-control" placeholder="Confirm New Password" name="password2">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-success" name="submit_password" value="submit">submit</button>
      </div>
    </form>
  </div>
<?php } else { ?>

  <div class="col-6 m-auto border">
    <form method="post" action="forget_password.php">
      <h4 class="h4 text-center">Check Email</h4>
      <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" placeholder="Type Your Email" name="email">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-success" name="submit" value="submit">submit</button>
      </div>
    </form>
  </div>


<?php
}
include_once(__DIR__ . '/layouts/footer.php');

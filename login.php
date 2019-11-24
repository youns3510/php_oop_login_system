<?php
$page_title = "Login";
include_once __DIR__ . '/classes/user.php';
$user = new User();
if (isset($_POST['submit'])) {
  $user->email = $_POST['email'];
  $user->password = $_POST['password'];
  // var_dump($_POST);
  $user->login();
}
include_once(__DIR__ . '/layouts/header.php');
if (isset($_SESSION['auth']) && $_SESSION['auth'] == true) {
  header('location:/');
}
?>
<div class="col-6 m-auto border">
  <form method="post" action="login.php">
    <h4 class="h4 text-center">Login</h4>
    <div class="form-group">
      <label>Email</label>
      <input type="email" class="form-control" placeholder="Type Your Email" name="email">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" class="form-control" placeholder="Type Your Password" name="password">
    </div>
    <div class="form-group">
       <a class="btn btn-primary" href="/forget_password.php">Forget Password</a>
      <button type="submit" class="btn btn-success" name="submit" value="submit">Login</button>
    </div>
  </form>
</div>
<?php
include_once(__DIR__ . '/layouts/footer.php');

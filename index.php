<?php
$page_title = "Home";
include_once(__DIR__.'/classes/user.php');
$user = new User();
if(isset($_GET['active_token'])){
$user->token = $_GET['active_token'];
$user->activation();
}
// var_dump($_SESSION).'<br>';
include_once(__DIR__ . '/layouts/header.php');
?>
<div class="alert alert-info">
  Home page
</div>
<!-- user -->
<?php 

if (isset($_SESSION['auth']) && $_SESSION['auth'] == true && $_SESSION['is_admin'] === "0") { ?>
  <div class="alert alert-success">
    User Successfully loged in.
  
  </div>
 
<?php } ?>
<!-- end user -->
<!-- admin -->
<?php if (isset($_SESSION['auth']) && $_SESSION['auth'] == true && $_SESSION['is_admin'] === '1') { ?>
  <div class="alert alert-success">
    Admin Successfully loged in.
  </div>
<?php } ?>
<!-- end admin -->

<?php
include_once(__DIR__ . '/layouts/footer.php');

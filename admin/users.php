<?php
$page_title = "All Users";
include_once(__DIR__ . '/../classes/admin.php');
if ((!isset($_SESSION['auth']) || $_SESSION['auth'] == false) && $_SESSION['is_admin'] == '0') {
  header("Location: /login.php");
  exit();
}
include_once(__DIR__ . '/../layouts/header.php'); ?>
<div class='table-responsive'>
  <table class='table table-bordered'>
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact Number</th>
        <th>Is Admin</th>
        <th>Is Active</th>
        <th>Created At</th>
      </tr>
    </thead>


    <tbody>
      <?php
      $admin = new Admin();

      $stmt = $admin->showUsers();
      if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          extract($row); ?>
          <tr>
            <td><?php echo $id; ?></td>
            <td><?php echo $firstName . ' ' . $lastName; ?></td>
            <td><?php echo $email; ?></td>
            <td><?php echo $contactNumber; ?></td>
            <td><?php echo $is_admin; ?></td>
            <td><?php echo $active; ?></td>
            <td><?php echo date_format(date_create($created), 'd-m-Y'); ?></td>
          </tr>
      <?php }
      }
      ?>

    </tbody>
  </table>

</div>
<?php

include_once(__DIR__ . '/../layouts/footer.php');

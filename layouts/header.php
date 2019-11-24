<?php $host = '//' . $_SERVER['HTTP_HOST'] . '/';
if (!isset($_SESSION)) {
    session_start();
    // session_start([
    //     'cookie_httponly' => false,
    //     'cookie_secure' => false
    // ]);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> <?php echo $page_title; ?> | MY OOP PHP login System</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $host; ?>includes/lib/css/all.min.css" />
    <link rel="stylesheet" href="<?php echo $host; ?>includes/lib/css/bootstrap.min.css" />
    <!-- our custom CSS -->
    <link rel="stylesheet" href="<?php echo $host; ?>includes/lib/css/custom.css" />

</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Auth App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <?php if (isset($_SESSION['auth']) && $_SESSION['auth'] == true && $_SESSION['is_admin'] === '1') { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users.php">All users</a>
                    </li>
                <?php } ?>
            </ul>


            <?php if (!isset($_SESSION['auth']) ||  $_SESSION['auth'] == false) { ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/login.php">login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register.php">register</a>
                    </li>
                </ul>
            <?php } ?>

            <!-- if auth user or admin -->
            <?php if (isset($_SESSION['auth']) && $_SESSION['auth'] == true) { ?>
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <?php echo $_SESSION['firstName']; ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/logout.php?logout=true">log out</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </nav>

    <!-- container -->
    <div class="container mt-5 mb-5">
        <?php if (isset($_SESSION['action']) && $_SESSION['action'] == true) { ?>
            <div class="alert <?php echo 'alert-' . $_SESSION['class']; ?>"><?php echo $_SESSION['msg']; ?></div>
        <?php
            unset($_SESSION['action']);
            unset($_SESSION['class']);
            unset($_SESSION['msg']);
        } ?>
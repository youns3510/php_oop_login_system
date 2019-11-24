<?php

include_once __DIR__ . '/database.php';
include_once __DIR__ . '/sendMail.php';
session_start();

class User
{
    protected $pdo, $table_name, $db_name;
    protected $errors = array();
    // object properties
    public $firstName, $lastName, $contactNumber, $address, $email, $password,  $password2, $is_admin, $token, $active;

    public function __construct()
    {
        $this->table_name = 'users';
        $this->db_name =  "my_oop_php_login_system";
        $this->pdo = (new Database())->getConnection();
    }

    // to check if email exist or not.
    public function emailExist($email)
    {
        $this->email = $this->testInput($email);
        // echo $email."<br>";
        $q = "SELECT * FROM `{$this->db_name}`.`{$this->table_name}` WHERE `email` = ? LIMIT 1;";
        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(1, $this->email);

        // echo $this->email;

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            // echo "exist";
            return true;
        }
        // echo 'doesn\'t exist';

        return false;
    }

    public function login()
    {
        $this->checkError('login');
        if (count($this->errors) > 0) {
            $All_errors = '';
            foreach ($this->errors as $err) {
                $All_errors .= "<span class='mr-2 text-danger'>*</span> {$err}.<br>";
            }
            // echo 'error';
            $_SESSION['action'] = true;
            $_SESSION['msg'] = $All_errors;
            $_SESSION['class'] = 'danger';
            return;
        }

        $this->email = $this->testInput($this->email);
        if ($this->emailExist($this->email)) {

            $this->password = $this->testInput($this->password);
            $q = "SELECT * FROM `{$this->db_name}`.`{$this->table_name}` WHERE `email` = ? LIMIT 1;";
            $stmt = $this->pdo->prepare($q);
            $stmt->bindParam(1, $this->email);

            if ($stmt->execute() && $stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // var_dump($row);
                    if (!password_verify($this->password, $row['password'])) {
                        echo 'pass wrong';
                        $_SESSION['action'] = true;
                        $_SESSION['msg'] = 'error your password  is wrong';
                        $_SESSION['class'] = 'danger';
                        return;
                    }

                    if ($row['active'] === '0') {
                        // echo 'active = 0';
                        $_SESSION['action'] = true;
                        $_SESSION['msg'] = 'you should  verify your account check your email now';
                        $_SESSION['class'] = 'success';
                        return;
                    }


                    $_SESSION['auth'] = true;
                    $_SESSION['firstName'] = $row['firstName'];
                    $_SESSION['lastName'] = $row['lastName'];
                    $_SESSION['contactNumber'] = $row['contactNumber'];
                    $_SESSION['address'] = $row['address'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['is_admin'] = $row['is_admin'];
                    header('location:/');
                }
            }
        }
    }

    public function register()
    {
        $this->email = $this->testInput($this->email);

        if ($this->emailExist($this->email)) {
            // echo 'email exist';
            $_SESSION['action'] = true;
            $_SESSION['msg'] = 'email already exist ';
            $_SESSION['class'] = 'danger';

            return;
        } else {
            $this->firstName = $this->testInput($this->firstName);
            $this->lastName = $this->testInput($this->lastName);
            $this->contactNumber = $this->testInput($this->contactNumber);
            $this->address = $this->testInput($this->address);
            $this->password = $this->testInput($this->password);

            $this->checkError('register');


            if (count($this->errors) > 0) {
                $All_errors = '';
                foreach ($this->errors as $err) {
                    $All_errors .= "<span class='mr-2 text-danger'>*</span> {$err}.<br>";
                }
                $_SESSION['action'] = true;
                $_SESSION['msg'] = $All_errors;
                $_SESSION['class'] = 'danger';

                return;
            }

            $this->token = bin2hex(random_bytes(50));
            $q = "INSERT INTO `{$this->db_name}`.`{$this->table_name}`(`firstName`, `lastName`, `email`, `contactNumber`, `address`, `password`,`token`) VALUES(?,?,?,?,?,?,?)";
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare($q);
            $stmt->bindParam(1, $this->firstName);
            $stmt->bindParam(2, $this->lastName);
            $stmt->bindParam(3, $this->email);
            $stmt->bindParam(4, $this->contactNumber);
            $stmt->bindParam(5, $this->address);
            $stmt->bindParam(6, $this->password);
            $stmt->bindParam(7, $this->token);

            if ($stmt->execute()) {
                $send = (new sendMail())->verifyAccount($this->email, $this->token);
                if ($send) {
                    $_SESSION['action'] = true;
                    $_SESSION['msg'] = 'check your email to verify your account';
                    $_SESSION['class'] = 'success';
                    // header('location:/login.php');
                    return;
                }
            } else {
                $_SESSION['action'] = true;
                $_SESSION['msg'] = 'Register ' . $stmt->errorInfo()[2];
                $_SESSION['class'] = 'danger';

                return;
            }
        }
    }

    public function activation()
    {
        $this->token = $this->testInput($this->token);
        $q = "SELECT * FROM `{$this->db_name}`.`{$this->table_name}` WHERE `token` = ? LIMIT 1;";
        $stmt = $this->pdo->prepare($q);
        $stmt->bindParam(1, $this->token);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $q = "UPDATE `{$this->db_name}`.`{$this->table_name}` SET `active` = '1' ,`token`= NULL  WHERE `token` = '{$this->token}'";
            if ($this->pdo->query($q)) {
                // echo 'activate success';
                $_SESSION['action'] = true;
                $_SESSION['msg'] = 'email successfully activated. ';
                $_SESSION['class'] = 'success';
                //header('location:/login.php');
                return;
            };
        } else {
            $_SESSION['action'] = true;
            $_SESSION['msg'] = 'Sorry,this email doesn\'t exist try to register again. ';
            $_SESSION['class'] = 'danger';
            //header('location:/register.php');
            return;
        }
    }
    public function forgetPassword()
    {
        $this->email = $this->testInput($this->email);
        if ($this->emailExist($this->email)) {
            $this->token = bin2hex(random_bytes(50));
            $q = "UPDATE `{$this->db_name}`.`{$this->table_name}` SET `token`=? WHERE `email` = ?;";
            $stmt = $this->pdo->prepare($q);
            $stmt->bindParam(1, $this->token);
            $stmt->bindParam(2, $this->email);

            if ($stmt->execute()) {
                $send = (new sendMail())->forgetPassword($this->email, $this->token);
                if ($send) {
                    $_SESSION['action'] = true;
                    $_SESSION['msg'] = 'message sent successfully';
                    $_SESSION['class'] = 'success';
                    return;
                }
            }
        } else {
            $_SESSION['action'] = true;
            $_SESSION['msg'] = 'Sorry, this email doesn\'t exist.';
            $_SESSION['class'] = 'danger';
            return;
        }
    }

    public function resetPassword()
    {
        $this->token = $this->testInput($this->token);
        $this->password = $this->testInput($this->password);
        $this->password2 = $this->testInput($this->password2);
        if ($this->password !== $this->password2) {
            $_SESSION['action'] = true;
            $_SESSION['msg'] = 'new password doesn\'t match it\'s confirmaiton feild.';
            $_SESSION['class'] = 'danger';
            return;
        }
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $q = "UPDATE `{$this->db_name}`.`{$this->table_name}` SET `password`= ? ,`token`= NULL  WHERE `token`=?;";
        $stmt = $this->pdo->prepare($q);
        $stmt->bindParam(1, $this->password);
        $stmt->bindParam(2, $this->token);
        $stmt->execute();
        if ($stmt->rowCount()  > 0) {
            $_SESSION['action'] = true;
            $_SESSION['msg'] = 'password reset successfully';
            $_SESSION['class'] = 'success';
            // header('location:/login.php');
        } else {
            $_SESSION['action'] = true;
            $_SESSION['msg'] = 'Sorry, This email doesn\'t exist .';
            $_SESSION['class'] = 'danger';
        }
    }

    private function checkError($action)
    {
        $reg = "/^[A-Za-z .'-]+$/";

        if ($action === 'login') {
            if (empty($this->email)) {
                $this->errors['email'] = 'email address is required ';
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Invalid email format ';
            }
            if (empty($this->password)) {
                $this->errors['password'] = 'password  is required ';
            }
        } else {
            if (empty($this->firstName)) {
                $this->errors['firstName'] = 'first name is required';
            } elseif (!preg_match($reg, $this->firstName)) {
                $this->errors['firstName'] = 'first name is not valid';
            }
            if (empty($this->lastName)) {
                $this->errors['lastName'] = 'last name is required';
            } elseif (!preg_match($reg, $this->lastName)) {
                $this->errors['lastName'] = 'last name is not valid';
            }
            if (empty($this->contactNumber)) {
                $this->errors['contactNumber'] = ' Contact Number is required ';
            } elseif ($this->validate_phone_number($this->contactNumber) == false) {
                $this->errors['contactNumber'] = ' Contact Number  is  not valid ';
            }
            if (empty($this->address)) {
                $this->errors['address'] = 'address  is required ';
            }

            if (empty($this->email)) {
                $this->errors['email'] = 'email address is required ';
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Invalid email format ';
            }
            if (empty($this->password)) {
                $this->errors['password'] = 'password  is required ';
            } elseif (strlen($this->password) < 6) {
                $this->errors['password'] = 'password must be greater thant 6 characters';
            }
        }
    }

    private function testInput($data)
    {
        $data = strip_tags($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    private function validate_phone_number($phone)
    {
        // Allow +, - and . in phone number
        $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        // Remove "-" from number
        $phone_to_check = str_replace('-', '', $filtered_phone_number);
        // Check the lenght of number
        // This can be customized if you want phone number from a specific country
        if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
            return false;
        } else {
            return true;
        }
    }
}

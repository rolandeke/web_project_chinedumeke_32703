<?php

require "config.php";
require "functions.php";
session_start();

$fullname = "";
$username = "";
$email = "";
$password = "";
$error_messages = [];

if (isset($_POST["submit"])) {
    if (!isset($_POST['username'], $_POST['password'], $_POST["fullname"], $_POST['email'])) {

        $error_messages[] = ('Please complete the registration form!');
    }

    if (empty($_POST['fullname'])) {

        $error_messages[] = ('Fullname Is Required');
    } else {
        $fullname = trim($_POST["fullname"]);

    }

    if (empty($_POST['username'])) {

        $error_messages[] = ('Username Is Required');
    } elseif (strtolower($username) == "admin") {
        $error_messages[] = ('Invalid Username. Please Choose another one');

    } else {
        $username = trim($_POST["username"]);

    }

    if (empty($_POST['email'])) {

        $error_messages[] = ('Email Is Required');
    } elseif (!filter_var($_POST["email"])) {
        $error_messages[] = "Please Provide a valid email address";

    } else {
        $email = trim($_POST["email"]);

    }

    if (empty($_POST['password'])) {

        $error_messages[] = ('Password Is Required');
    } elseif (strlen($password) < 8) {
        $error_messages[] = "Password must be at least 8 characters";
    } else {
        $password = trim($_POST["password"]);

    }

    if (empty($error_messages)) {

        try {
            if (check_username_exist($connection, $username)) {
                $error_messages[] = "Username is already taken. Please choose another username";
            } else {
                if (check_email_exist($connection, $email)) {
                    $error_messages[] = "Email already exists. Please choose another email";
                } else {

                    //save user to database
                    if ($stmt = $connection->prepare('INSERT INTO userregister (UserName, UserPassword, FullName,Email) VALUES (?, ?, ?,?)')) {
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt->execute([$username, $password_hash, $fullname, $email]);
                        $success_message = 'Account Successfully created. Click Here to <a href="index.php">Login</a>';
                        // header("Location: index.php");

                    } else {

                        $error_messages[] = 'Oops!!! Internal Server Error, please try again';

                    }

                }
            }
        } catch (Exception $ex) {
            $error_messages[] = $ex->getMessage();
        }

        $connection = null;

    }

}

function check_username_exist($connection, $username)
{

    $isExist = false;
    try {
        $sql = 'SELECT UserID, UserPassword FROM userregister WHERE UserName = :username';

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $isExist = true;

        }

        $connection = null;
        return $isExist;

    } catch (Exception $ex) {
        throw $ex;
    }

}

function check_email_exist($connection, $email)
{

    $isExist = false;
    try {
        $sql = 'SELECT UserID, UserPassword FROM userregister WHERE Email = :email';

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $isExist = true;

        }

        $connection = null;
        return $isExist;

    } catch (Exception $ex) {
        throw $ex;
    }

}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMDT - Sign Up</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row p-5 d-flex justify-content-center margin-top-md-100">
            <div class="col-md-5">
                <div class="login-form-header mb-4">
                    <h4 class="display-5">Online Medicine Dosage Tracker</h4>
                    <p>Sign Up and start keeping track of your drug dosage</p>
                </div>

                <?php
foreach ($error_messages as $error) {
    echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong>' . $error . '</strong>
                </div>';

}

if (isset($success_message) && !empty($success_message)) {
    echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong>' . $success_message . '</strong>
                </div>';

}

?>

                <form action="" method="POST" autocomplete="off">

                    <div class="form-group">
                        <label for="FullName"><b>Full Name</b></label>
                        <input type="text" placeholder="Enter your Full Name" name="fullname"
                            value="<?php echo ($fullname) ?>">
                    </div>

                    <div class="form-group">
                        <label for="Username"><b>Username</b></label>
                        <input type="text" placeholder="Enter your Username" name="username"
                            value="<?php echo ($username) ?>">
                    </div>
                    <div class="form-group">
                        <label for="email"><b>Email</b></label>
                        <input type="email" placeholder="Enter Email" name="email" value="<?php echo ($email) ?>">

                    </div>
                    <div class="form-group">
                        <label for="password"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="password"
                            value="<?php echo ($password) ?>">


                    </div>

                    <button type="submit" class="btn btn-info btn-block my-4" name="submit">Register</button>

                </form>

                <div class="">
                    <p class="register-link">Already have an account? <a href="index.php">Sign in</a>.</p>
                </div>
            </div>
        </div>
    </div>

    <?php display_footer()?>
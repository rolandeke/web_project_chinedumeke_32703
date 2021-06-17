<?php
session_start();

include "config.php";
if (isset($_SESSION['isUserLoggedIn']) == true) {
    header("Location: home.php");
}

$error_messages = [];
$username = "";
$password = "";

if (isset($_POST["submit"])) {

    if (!isset($_POST['username'], $_POST['password'])) {

        $error_messages[] = 'Username and password is required';
    }

    if (empty($_POST['username'])) {
        $error_messages[] = ('Username Is Required');
    } else {
        $username = trim($_POST["username"]);

    }

    if (empty($_POST['password'])) {

        $error_messages[] = ('Password Is Required');
    } else {
        $password = trim($_POST["password"]);

    }

    if (empty($error_messages)) {
        try {
            $sql = "SELECT * FROM userregister WHERE UserName = ? or Email = ?";
            $statement = $connection->prepare($sql);
            if ($statement) {

                $statement->execute([$username, $username]);

                if ($statement->rowCount() > 0) {
                    $row = $statement->fetch(PDO::FETCH_OBJ);

                    if (password_verify($password, $row->UserPassword)) {

                        session_regenerate_id();
                        $_SESSION['isUserLoggedIn'] = true;
                        $_SESSION['username'] = $_POST['username'];
                        $_SESSION['fullname'] = $row->FullName;
                        $_SESSION['user_id'] = $row->UserID;
                        header('Location: home.php');

                    } else {

                        $error_messages = 'Username or password is incorrect';
                    }
                } else {

                    $error_messages[] = "Username or password is incorrect";
                }

                $statement = null;

            } else {
                $error_messages[] = "Oops!!! Internal Server Error, please try again";
            }
        } catch (Exception $ex) {
            $error_messages[] = $ex->getMessage();
        }

    }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid">
        <div class="row p-5 d-flex justify-content-center margin-top-md-100">
            <div class="col-md-5">
                <div class="login-form-header mb-5">
                    <h4 class="display-5">O.M.D.T <sub><small>Please login to continue</small></sub></h4>
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
                    <label for="email"><b>Username</b></label>
                    <input type="text" placeholder="Username" name="username" required value="<?php echo ($username) ?>">

                    <label for="password"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password" required value="<?php echo ($password) ?>">

                    <button type="submit" class="btn btn-info btn-block my-4 form-btn" name="submit">Sign in</button>
                </form>

                <div class="">
                    <p class="register-link">Don't have account? <a href="register.php">Register Now</a>.</p>
                </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>

</html>

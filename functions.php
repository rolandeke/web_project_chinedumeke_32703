<?php

function display_header()
{
    echo '<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Home Page</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">OMDT</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse ml-4" id="navbarSupportedContent">
            <ul class="navbar-nav m-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/dosage.php">Dosage Planner</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' .
        $_SESSION['fullname']
        . '</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Profile</a>
                        <div class="dropdown-divider"></div>
                          <a class="dropdown-item text-danger" href="logout.php">Logout</a>
                    </div>
                </li>

            </ul>

        </div>
    </div>

</nav>';
}

function display_footer()
{
    echo '
         <script src="./js/jquery.min.js" ></script>
        <script src="./js/popper.js"></script>
        <script src="./js/bootstrap.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="./js/main.js"></script>
    </body>

</html>';
}

//this function retrieves a particulars medicine(s) from the database
function get_users_medicine($connection, $user_id)
{
    try {

        $sql = "SELECT * FROM tblmedicine WHERE UserId = :user_id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        if ($stmt->execute()) {

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

    } catch (\Throwable$th) {
        //throw $th;
    } finally {
        $connection = null;
    }

}

//this function retrieves a particulars medicine(s) from the database
function delete_user_dosage_plan($connection, $plan_id)
{
    try {

        $sql = "DELETE FROM tbldosageplanner WHERE plan_id = :plan_id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":plan_id", $plan_id, PDO::PARAM_INT);
        if ($stmt->execute()) {

            return true;

        } else {
            return false;
        }

    } catch (Exception $ex) {
        throw $ex;
    } finally {
        $connection = null;
    }

}

function delete_medicine_by_id($connection, $medicine_id)
{
    try {

        $sql = "DELETE FROM tblmedicine WHERE medicine_id = :medicine_id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":medicine_id", $medicine_id, PDO::PARAM_INT);
        if ($stmt->execute()) {

            return true;

        } else {
            return false;
        }

    } catch (Exception $ex) {
        throw $ex;
    } finally {
        $connection = null;
    }

}

function get_plan_by_id($connection, $plan_id)
{

    try {

        $sql = "SELECT * FROM tbldosageplanner WHERE plan_id = :plan_id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":plan_id", $plan_id, PDO::PARAM_INT);
        if ($stmt->execute()) {

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } else {
            return null;
        }

    } catch (Exception $ex) {
        throw $ex;
    } finally {
        $connection = null;
    }

}

function get_medicine_by_id($connection, $medicine_id)
{

    try {

        $sql = "SELECT * FROM tblmedicine WHERE medicine_id = :medicine_id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":medicine_id", $medicine_id, PDO::PARAM_INT);
        if ($stmt->execute()) {

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } else {
            return null;
        }

    } catch (Exception $ex) {
        throw $ex;
    } finally {
        $connection = null;
    }

}
<?php
session_start();
$user_id = $_SESSION["user_id"];

require "config.php";
require "functions.php";
if (isset($_GET["edit_id"])) {

    try {
        $plan_id = $_GET["edit_id"];
        $data = get_plan_by_id($connection, $plan_id);
        echo json_encode($data);

    } catch (Exception $ex) {
        echo json_encode(array("errors" => $ex->getMessage()));
    }

    return;

}

if (isset($_POST["plan_id"]) && isset($_POST["isPlanUpdate"])) {

    if ($_POST["plan_id"] != null && $_POST["isPlanUpdate"] == true) {
        try {

            $plan_id = $_POST["plan_id"];
            $medicine_id = trim($_POST["medicine_id"]);
            $date_taken = trim($_POST["date_taken"]);
            $time_taken = trim($_POST["time_taken"]);

            if (get_plan_by_id($connection, $plan_id) != null) {
                $sql = "UPDATE tbldosageplanner SET medicine_id = :medicine_id, date_taken = :date_taken, time_taken = :time_taken,user_id = :user_id where plan_id = :plan_id";

                if ($stmt = $connection->prepare($sql)) {
                    $stmt->bindParam(":medicine_id", $medicine_id, PDO::PARAM_INT);
                    $stmt->bindParam(":date_taken", $date_taken, PDO::PARAM_STR);
                    $stmt->bindParam(":time_taken", $time_taken, PDO::PARAM_STR);
                    $stmt->bindParam(":plan_id", $plan_id, PDO::PARAM_INT);
                    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        echo json_encode(array("success" => "Dosage Plan Successfully Updated"));
                    } else {
                        echo json_encode(array("error" => "Error Updating Dosage Plan"));

                    }

                } else {
                    echo json_encode(array("error" => "Oops! Something went wrong"));
                }

            } else {

                echo json_encode(array("error" => "Dosage Plan Does not Exist!"));
            }

        } catch (Exception $ex) {
            echo json_encode(array("errors" => $ex->getMessage()));
        }

    }

} else {
    echo json_encode("No Plan ID or User ID");
}
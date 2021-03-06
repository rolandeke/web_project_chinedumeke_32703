<?php
session_start();
$user_id = $_SESSION["user_id"];
require "config.php";
require "functions.php";
if (isset($_GET["edit_id"])) {

    try {
        $plan_id = $_GET["edit_id"];
        $data = get_plan_by_id($connection, $plan_id);
        if ($data != null) {
            echo json_encode($data);

        } else {
            echo json_encode(array("error" => "Dosage Plan with that ID does not exist"));

        }

    } catch (Exception $ex) {
        echo json_encode(array("errors" => $ex->getMessage()));
    }

    return;

}

if (isset($_GET["medicine_id"]) && isset($_GET["isMedicineUpdate"]) && $_GET["isMedicineUpdate"] == true) {
    try {

        $medicine_id = intval($_GET["medicine_id"]);
        $data = get_medicine_by_id($connection, $medicine_id);

        if ($data != null) {
            echo json_encode($data);
        } else {
            echo json_encode(array("error" => "Medicine with that ID does not exist"));

        }
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

}

//update medicine
if (isset($_POST["medicineId"]) && isset($_POST["isMedicineUpdate"])) {

    if ($_POST["medicineId"] != null && $_POST["isMedicineUpdate"] == true) {
        try {

            $medicine_id = intval($_POST["medicineId"]);
            $medicine_name = trim($_POST["medicine_name"]);
            $dosage_qty = intval(trim($_POST["dosage_qty"]));
            $dosage_unit = trim($_POST["dosage_unit"]);
            $milligrams = intval(trim($_POST["milligrams"]));
            $frequency_qty = intval(trim($_POST["frequency_qty"]));
            $frequency_unit = trim($_POST["frequency_unit"]);

            if (get_medicine_by_id($connection, $medicine_id) != null) {
                $sql = "UPDATE tblmedicine SET medicine_name = :medicine_name, dosage_qty = :dosage_qty, dosage_unit = :dosage_unit,grams=:grams,frequency_qty = :frequency_qty,frequency_unit=:frequency_unit, UserId = :user_id where medicine_id = :medicine_id";

                if ($stmt = $connection->prepare($sql)) {
                    $stmt->bindParam(":medicine_name", $medicine_name, PDO::PARAM_STR);
                    $stmt->bindParam(":dosage_qty", $dosage_qty, PDO::PARAM_INT);
                    $stmt->bindParam(":dosage_unit", $dosage_unit, PDO::PARAM_STR);
                    $stmt->bindParam(":grams", $milligrams, PDO::PARAM_INT);
                    $stmt->bindParam(":frequency_qty", $frequency_qty, PDO::PARAM_INT);
                    $stmt->bindParam(":frequency_unit", $frequency_unit, PDO::PARAM_STR);
                    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                    $stmt->bindParam(":medicine_id", $medicine_id, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        echo json_encode(array("success" => "Dosage Plan Successfully Updated"));
                    } else {
                        echo json_encode(array("error" => "Error Updating Dosage Plan"));

                    }

                } else {
                    echo json_encode(array("error" => "Oops! Something went wrong"));
                }

            } else {

                echo json_encode(array("error" => "Medicine with that ID does not exist"));
            }

        } catch (Exception $ex) {
            echo json_encode(array("errors" => $ex->getMessage()));
        }

    }

}
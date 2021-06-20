<?php
require "config.php";
require "functions.php";
if (isset($_POST["plan_id"]) || isset($_POST["medicine_id"])) {

    try {

        if (isset($_POST["plan_id"])) {
            $plan_id = $_POST["plan_id"];
            if (delete_user_dosage_plan($connection, $plan_id)) {

                echo json_encode(array("success" => "Dosage Plan Deleted Successfully"));
            } else {
                echo json_encode(array("errors" => "Error Deleting Dosage Plan"));

            }

            return;
        }

        if (isset($_POST["medicine_id"])) {
            $medicine_id = $_POST["medicine_id"];
            if (delete_medicine_by_id($connection, $medicine_id)) {

                echo json_encode(array("success" => "Medicine Deleted Successfully"));
            } else {
                echo json_encode(array("errors" => "Error Deleting Medicine"));

            }

            return;
        }

    } catch (Exception $ex) {
        echo json_encode(array("errors" => $ex->getMessage()));
    }
}
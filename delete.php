<?php
require "config.php";
require "functions.php";
if (isset($_POST["plan_id"]) || isset($_POST["medicine_id"])) {

    try {
        $plan_id = $_POST["plan_id"];
        if (delete_user_dosage_plan($connection, $plan_id)) {

            echo json_encode(array("success" => "Dosage Plan Deleted Successfully"));
        } else {
            echo json_encode(array("errors" => "Error Deleting Dosage Plan"));

        }

    } catch (Exception $ex) {
        echo json_encode(array("errors" => $ex->getMessage()));
    }
}
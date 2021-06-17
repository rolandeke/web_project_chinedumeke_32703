<?php
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

}

if (isset($_POST["isPlanUpdate"]) && isset($_POST["dosagePlanID"])) {

    if ($_POST["isPlanUpdate"] == true && $_POST["dosagePlanID"] != null) {
        try {

            $plan_id = $_POST["dosagePlanID"];
            $medicine_id = trim($_POST["medicine_id"]);
            $date_taken = trim($_POST["date_taken"]);
            $time_taken = trim($_POST["time_taken"]);

            $sql = "UPDATE tbldosageplanner SET medicine_id = :medicine_id, date_taken = :date_taken, time_taken = :time_taken where plan_id = :plan_id";

            $stmt = $connection->prepare($sql);
            $stmt->bindParam(":medicine_id", $medicine_id, PDO::PARAM_INT);
            $stmt->bindParam(":date_taken", $date_taken, PDO::PARAM_STR);
            $stmt->bindParam(":time_taken", $time_taken, PDO::PARAM_STR);
            $stmt->bindParam(":plan_id", $plan_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode("updated");
            } else {
                echo json_encode("not updated");

            }

        } catch (Exception $ex) {
            //echo json_encode(array("errors" => $ex->getMessage()));
        }

    }

}
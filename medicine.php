<?php
session_start();
include "config.php";
if (isset($_POST["medicine_name"]) && isset($_POST["dosage_qty"]) && isset($_POST["dosage_unit"]) && isset($_POST["milligrams"]) && isset($_POST["frequency_qty"]) && isset($_POST["frequency_unit"])) {

    $medicine_name = trim($_POST["medicine_name"]);
    $dosage_qty = trim($_POST["dosage_qty"]);
    $dosage_unit = trim($_POST["dosage_unit"]);
    $milligrams = trim($_POST["milligrams"]);
    $frequency_qty = trim($_POST["frequency_qty"]);
    $frequency_unit = trim($_POST["frequency_unit"]);
    $user_id = $_SESSION["user_id"];

    try {
        //SAVE MEDICINE TO THE DATABASE
        $sql = "INSERT INTO tblmedicine(medicine_name,dosage_qty,dosage_unit, grams, frequency_qty,frequency_unit,UserId) VALUES (:medicine_name,:dosage_qty,:dosage_unit,:grams,:frequency_qty,:frequency_unit,:userId)";

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":medicine_name", $medicine_name, PDO::PARAM_STR);
        $stmt->bindParam(":dosage_qty", $dosage_qty, PDO::PARAM_INT);
        $stmt->bindParam(":dosage_unit", $dosage_unit, PDO::PARAM_STR);
        $stmt->bindParam(":grams", $milligrams, PDO::PARAM_INT);
        $stmt->bindParam(":frequency_qty", $frequency_qty, PDO::PARAM_INT);
        $stmt->bindParam(":frequency_unit", $frequency_unit, PDO::PARAM_STR);
        $stmt->bindParam(":userId", $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode("Medicine saved successfully");
        } else {
            echo json_encode("Error saving medicine.Try again");

        }

    } catch (Exception $ex) {
        echo json_encode($ex);
    }

} else {
    echo json_encode("BAD REQUEST!!!! PLEASE SUBMI THE FORM AGAIN");
}

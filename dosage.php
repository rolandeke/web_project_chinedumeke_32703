<?php

//start a session for the current logged in user
session_start();

//require the database file
require "config.php";

//require functions file
require "functions.php";

//check if the user is logged and a session was created
if (!isset($_SESSION['isUserLoggedIn'])) {

    //redirect back to login page if the user tries to access the homepage from the url bar
    header("Location: index.php");

    //exit the script
    exit;
} else {

    try {

        //save user_id if the user is successfully logged in from the login form
        $user_id = $_SESSION["user_id"];

        //create variables for paginating data

        //set how many medicine you want to display per page on the table
        $medicine_per_page = 3;
        $number_of_pages = 0;

        //retrieve all medicine data from the database when the page loads
        $sql = "SELECT * FROM tbldosageplanner WHERE user_id = :user_id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);

        //check if the query executed successfully
        if ($stmt->execute()) {

            //check if the number of rows returned is greater than zero(0)
            if ($stmt->rowCount() > 0) {

                //store the total number of rows in a variable
                $total_rows = $stmt->rowCount();

                //determine the number of pages that would be displayed on the table
                $number_of_pages = ceil($total_rows / $medicine_per_page);

                //check the current pages the user is currently on
                if (!isset($_GET["page"])) {
                    $page = 1;
                } else {
                    $page = $_GET["page"];
                }

                //detemine the limit starting number for the result to the retrieved from the database table
                $starting_limit = ($page - 1) * $medicine_per_page;

                //retrieve data from the table based on the limit and display them to the user

                $sql = "SELECT plan_id,medicine_name,date_taken,time_taken,date_inputted FROM tbldosageplanner INNER JOIN userregister on tbldosageplanner.user_id = userregister.UserID INNER JOIN tblmedicine on tbldosageplanner.medicine_id = tblmedicine.medicine_id WHERE user_id = :user_id LIMIT " . $starting_limit . "," . $medicine_per_page;

                $stmt = $connection->prepare($sql);
                $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);

                //check again if the query was executed successfull
                if ($stmt->execute()) {
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                } else {
                    //send an error to the user
                }

            }

        } else {
            //error executing the sql query
        }

    } catch (Exception $ex) {
        echo $ex->getMessage();
    }

}

?>

<!-- call function that displays the header -->
<?php display_header();?>


<div class="container ">
    <div class="row mt-4">
        <div class="col-12">
            <h1 class="display-4 text-uppercase">OMDT Dosage Planner</h1>
            <p>Record new dosage plan</p>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mt-2 ml-3" data-toggle="modal" data-target="#plannerModal">
                Add New Dosage Plan
            </button>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="plannerModal" tabindex="-1" role="dialog" data-backdrop="static" data-focus="true"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Dosage Planner</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="dosage-errors" id="dosage-errors">


                        </div>


                        <form id="dosagePlanForm">
                            <div class="form-group">
                                <label for="">Select Medicine</label>
                                <select class="form-control" name="medicine_id" id="medicine_id">
                                    <option value="" selected>Please Select Medicine</option>
                                    <?php

$meds = get_users_medicine($connection, $user_id);
foreach ($meds as $row) {
    echo '<option value="' . $row["medicine_id"] . '" selected>' . $row["medicine_name"] . '</option>';
}
?>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Date Taken</label>
                                        <input type="date" class="form-control" id="date_taken" name="date_taken"
                                            id="date_taken">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Time Taken</label>
                                        <input type="time" class="form-control" id="time_taken" name="time_taken">
                                    </div>
                                </div>
                                <input type="hidden" id="plan_id" name="plan_id">
                            </div>
                            <button type="submit" name="submit" id="btnSubmit" class="btn btn-primary btn-block">Save
                                New Plan</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-12 my-2">
                <h4 class=" text-uppercase">View All Dosage Plans</h4>
            </div>

            <div class="col-12">
                <div class="table-responsive ">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>

                                <th scope="col">Medicine Name</th>
                                <th scope="col">Date Taken</th>
                                <th scope="col">Time Taken</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
if (!empty($rows)) {
    foreach ($rows as $row) {

        echo '<tr>

                                <td>' . $row["medicine_name"] . '</td>
                                <td>' . $row["date_taken"] . '</td>
                                <td>' . $row["time_taken"] . '</td>
                                <td><button id="btnDeleteDosagePlan" data-id="' . $row["plan_id"] . '" class="btn btn-danger btn-block mb-2">Delete</button>
                                    <button id="btnUpdateDosagePlan" data-id="' . $row["plan_id"] . '" class="btn btn-info btn-block">Edit</button>
                                </td>
                                </tr>';
    }

}
?>


                        </tbody>
                    </table>

                </div>
                <!-- end of table -->
                <nav aria-label="" class="mt-4">
                    <ul class="pagination">
                        <?php
for ($page = 1; $page <= $number_of_pages; $page++) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
}

?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>


</div>

</div>


<?php display_footer();?>
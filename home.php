<?php
//start a session for the current logged in user
session_start();

//require the database file
require "config.php";

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
        $medicine_per_page = 4;

        //retrieve all medicine data from the database when the page loads
        $sql = "SELECT * FROM tblmedicine WHERE UserId = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);

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

                $sql = "SELECT * FROM tblmedicine WHERE UserId = :id LIMIT " . $starting_limit . "," . $medicine_per_page;
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);

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


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

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
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Dosages</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION['fullname'] ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Profile</a>
						 <a class="dropdown-item" href="#">Dosage Planner</a>
                        <div class="dropdown-divider"></div>
                          <a class="dropdown-item text-danger" href="logout.php">Logout</a>
                    </div>
                </li>

            </ul>

        </div>
    </div>

</nav>

<body class="">
    <div class="container ">
        <div class="row mt-2">
            <div class="col-12 p-2 text-center ">
                <h1 class="display-4 text-uppercase">Welcome to online Medicine dosage tracker</h1>
            </div>
            <div class="col-md-4 ">
                <div class="row">
                    <div class="col-12 p-2 my-2">
                        <h4 class=" text-uppercase">Add A New Drug</h4>
                    </div>

                    <div class="col-12">

					<div class="error-div" id="error-div"></div>


                        <form id="medicineForm">
                            <div class="form-group">
                                <label for="medicine_name">Medicine Name: </label>
                                <input type="text" class="form-control" id="medicine_name" name="medicine_name"
                                    placeholder="Enter the name of the medicine" required>

                            </div>
                           <div class="form-row">
							   <div class="col-md-6">
								    <div class="form-group">
                                <label for="">Dosage Quantity: </label>
                                <input type="number" class="form-control" id="dosage_qty" name="dosage_qty" required
                                    placeholder="Dosage Quantity">

                            	</div>
							   </div>

							   <div class="col-md-6">
								      <div class="form-group">
                                        <label for="">Dosage Units</label>
                                        <select class="form-control" id="dosage_unit" name="dosage_unit" required>
                                            <option value="" selected>Select Unit</option>
                                            <option value="Tablet">Tablet</option>
                                            <option value="Bottle">Bottle</option>
											 <option value="Syringe">Syringe/Injection</option>

                                        </select>
                                    </div>
							   </div>
						   </div>
                           <div class="form-row">
							   <div class="col-md-6">
								    <div class="form-group">
                                <label for="">Grams: </label>
                                <input type="number " class="form-control"  id="milligrams" name="milligrams"
                                    placeholder="Enter milligrams" required>

                            </div>
							   </div>

							   <div class="col-md-6">
								    <div class="form-group">
                                <label for="">Unit: </label>
                                <input type="text" class="form-control"  disabled value="Mg">

                            </div>
							   </div>
						   </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Frequency Quantity</label>
                                        <input type="number" class="form-control" id="frequency_qty" name="frequency_qty"
                                            placeholder="How many should be taken?" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Units</label>
                                        <select class="form-control" id="frequency_unit" name="frequency_unit" required>
                                            <option value="" selected >Select Unit</option>
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="submit" id="btnSubmit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-12 p-2 my-2">
                        <h4 class=" text-uppercase">View All Drugs</h4>
                    </div>

                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Medicine Name</th>
                                        <th scope="col">Dosage Qty.</th>
                                        <th scope="col">Dosage Unit.</th>
                                        <th scope="col">Grams</th>
                                        <th scope="col">Grams Unit</th>
                                        <th scope="col">Frequency Qty.</th>
                                        <th scope="col">Frequency Units</th>
										<th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
foreach ($rows as $row) {

    echo '<tr>
                                        <th scope="row">' . $row["medicine_id"] . '</th>
                                        <td>' . $row["medicine_name"] . '</td>
                                        <td>' . $row["dosage_qty"] . '</td>
                                        <td>' . $row["dosage_unit"] . '</td>
                                        <td>' . $row["grams"] . '</td>
                                        <td>' . $row["grams_unit"] . '</td>
                                        <td>' . $row["frequency_qty"] . '</td>
                                        <td>' . strtoupper($row["frequency_unit"]) . '</td>
										<td><button id="btnDelete" class="btn btn-danger btn-block mb-2">Delete</button><button class="btn btn-info btn-block">Edit</button></td>
                                    </tr>';
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
</body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>

	<script src="./js/main.js"></script>

</html>

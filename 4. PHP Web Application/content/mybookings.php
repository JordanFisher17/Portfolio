<?php
//PHP code in order to start the session
ini_set("session.save_path", "/home/unn_w22055152/sessionData");
session_start();
session_regenerate_id();

unset($_SESSION['excuID']);
unset($_SESSION['participants']);

include "dbconn.php";
include "../assets/scripts/functions.php";
// If the user is not logged in, redirect them to index.php without displaying the page.
if (!isset($_SESSION['userID']))
    {
        header("Location: login.php");
    }
?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../assets/css/styles.css" rel="stylesheet" type="text/css">
        <title>Make My Day</title>
    </head>
 <!-- Code for the Body Section -->
    <body>
        <!-- Code for the creation of the Header and Nav bar - To be standardised across pages -->
        <header>
            <?php
                include "../assets/templates/header.php"
            ?>
        </header>
        
        <nav>
            <?php 
                include "../assets/templates/navbar.php"
            ?>
        </nav>
         <!-- The main section, where the pages content is laid out -->
        <main>
            <div class="centerblock">
                <h3>Your Bookings</h3>

                <?php
                    
                    $sql = "SELECT `a`.`bookingID`, `a`.`excursionID`, `a`.`excursion_date`, `a`.`num_guests`, `a`.`total_booking_cost`, `a`.`booking_notes`, `b`.`excursion_name`
                     FROM `booking` `a`, `excursions` `b` WHERE customerID = ? AND `a`.`excursionID` = `b`.`excursionID`";

                     // Obtain the users bookings from the database as an array and loop over these printing them for the user to view.
                    if ($stmt = mysqli_prepare($conn, $sql))
                    {
                        mysqli_stmt_bind_param($stmt, "i", $_SESSION['userID']);
                        mysqli_stmt_execute($stmt);
                        $queryresult = mysqli_stmt_get_result($stmt);
                        while ($currentrow = mysqli_fetch_assoc($queryresult))
                        {
                            $bookingref = $currentrow['bookingID'];
                            $excursionID = $currentrow['excursionID'];
                            $excursionName = $currentrow['excursion_name'];
                            $bookingdate = $currentrow['excursion_date'];
                            $participants = $currentrow['num_guests'];
                            $price = $currentrow['total_booking_cost'];
                            $notes = $currentrow['booking_notes'];

                            echo "
                            <div class='leftdiv' style='width: 60%; background-color: white;'>
                                
                                    <p><h4>Booking Reference:</h4>$bookingref</p>
                                    <p><h4>Excursion Name:</h4>$excursionName</p>
                                    <p><h4>Number of Guests:</h4>$participants</p>
                                    <p><h4>Booking Price:</h4>£$price</p>
                                    <p><h4>Date:</h4>$bookingdate</p>
                                    <p><h4>Notes:</h4>$notes</p>
                            
                            </div>";
                        }
                    }
                    else // In the event of an error generate an alert to the user.
                    {
                        echo "<script>alert('An Error has occurred')</script>";

                    }

                ?>
            </div>
        </main>
        
        <!-- Footer Block - To be standardised across the pages -->
        <footer>
            <?php
                include "../assets/templates/footer.php"
            ?>
        </footer>
    </body>
</html>
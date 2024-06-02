<?php
    //Start the session and regenerate the session ID.
    ini_set("session.save_path", "/home/unn_w22055152/sessionData");
    session_start();
    session_regenerate_id();
    
    include "dbconn.php";
    include "../assets/scripts/functions.php";
    //Initialising a variable for error handling.
    $bookingError = "";

    // PHP Code to populate the site's dynamic information
    $chosenexcursion = $_SESSION['excuID'];
    
    // Retrieve the details relevant to the excursion being booked.
    $sql = "SELECT excursionID, excursion_name, price_per_person FROM excursions WHERE excursionid = ?";

    // If the user is not logged in, do not display this page and redirect them to login.php
    if (!isset($_SESSION['userID']))
    {
        header("Location: login.php");
    }
    // If the user is logged in draw out the data for their chosen excursion so that this can be injected into the page.
    else if (!$stmt = mysqli_prepare($conn, $sql))
    {
        die("An Error has occurred accessing the database.");

    }
    else
    {
        mysqli_stmt_bind_param($stmt, "i", $chosenexcursion);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $display = mysqli_fetch_assoc($result);
        $excu_id = $display['excursionID'];
        $excu_name = $display['excursion_name'];
        $excu_price = $display['price_per_person'];
        $price = updateprice($excu_price, $_SESSION['participants']);
         
    }

    // PHP Code in order to add a booking into the database on submit.
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $sql = "INSERT INTO `booking` (`bookingID`, `excursionID`, `customerID`, `excursion_date`, `num_guests`, `total_booking_cost`, `booking_notes`) VALUES (NULL, ?, ?, ?, ?, ?, ?)";

        if (validateDate($_POST['bookingdate']) === false)
        {
            $bookingError = "Please enter a valid date";
        }
        else if (!empty($_POST['notes']) && validateText($_POST['notes']) === false)
        {
            $bookingError = "Notes may be too long or contain invalid characters";
        }
        else
        {
        
            $bookingdate = $_POST['bookingdate'];
            $notes = cleanData($_POST['notes']);

            if ($stmt = mysqli_prepare($conn, $sql))
            {
                mysqli_stmt_bind_param($stmt, "iisids", $_SESSION['excuID'], $_SESSION['userID'], $bookingdate, $_SESSION['participants'], $price, $notes);
                mysqli_stmt_execute($stmt);
                header("Location: mybookings.php");
            }
            else
            {
                die("Error making your booking");
            }  
            // Free the dynamic memory allocation and close the query.
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
        }
    }
?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../assets/css/styles.css" rel="stylesheet" type="text/css">
        <title>Make a Booking</title>
    </head>

<!-- Code for the Body Section -->
    <body>
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
    </header>

    <main>

    <div class="centerblock">
        <!--Creation of the booking form using htmlspecialchars and injecting the details of the excursion into the booking form -->
        <?php
                echo "<h3 class='heading'>$excu_name</h3><br>";
        ?>
        <form method="POST"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!--Reference: (W3Schools, Unknown) -->
            <div class='forms'>
            <span class="error"><?php echo $bookingError;?></span>
                <p>Participants:<br><br><?php echo $_SESSION['participants'];?></p>
                <label>Choose your date</label><br><br>
                <input type='date' name='bookingdate'><br>
                <label>Additional Notes</label><br><br>
                <textarea name='notes' rows='5' cols='25' placeholder='Additional Information'></textarea>
                <label>Total Price:<br>£<?php echo $price;?></label><br><br>
                <button type='submit'>Book</button>
            </div>
        </form>

    </main>

     <!-- Footer Block - To be standardised across the pages -->
     <footer>
            <?php
                include "../assets/templates/footer.php"
            ?>
        </footer>
    </body>
</html>
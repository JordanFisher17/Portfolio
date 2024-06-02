<?php
//PHP code in order to start the session and import the database file
ini_set("session.save_path", "/home/unn_w22055152/sessionData");
session_start();
session_regenerate_id();
include ("dbconn.php");

$chosenexcursion = $_REQUEST["chosenexcuID"];
    // In the event of there being no chosen excursion show an error message.
    if (empty($chosenexcursion))
    {
        die("No Excursion chosen");
                    
    }
    // Retrieve the details of the excursion chosen by the user so that these can be injected into the HTML.
    $sql = "SELECT * FROM excursions WHERE excursionid = ?";

    if ($stmt = mysqli_prepare($conn, $sql))
    {
        mysqli_stmt_bind_param($stmt, "i", $chosenexcursion);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt); // Returns the relevant rows from the database
        $display = mysqli_fetch_assoc($result); // Formats the returned rows into an associative array

        $excu_id = $display['excursionID'];
        $excu_name = $display['excursion_name'];
        $excu_description = $display['description'];
        $excu_price = $display['price_per_person'];
        $location =  $display['location'];
        $duration = $display['duration'];
        $img1 = $display['img1'];
        $alt1 = $display['alt1'];
        $img2 = $display['img2'];
        $alt2 = $display['alt2'];
        $img3 = $display['img3'];
        $alt3 = $display['alt3'];
    }
    else 
    {
        die("This page experienced an error, please try again later");
    }
    //If the user wishes to proceed to the booking page, save their excursion choices within session variables for use in the booking.
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $_SESSION['excuID'] = $_REQUEST['chosenexcuID'];
        $_SESSION['participants'] = $_POST['participants'];
        header("Location: book.php");
    }

    //Free dynamic memory and close the database connection.
    mysqli_free_result($result);
    mysqli_close($conn);

   
?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../assets/css/styles.css" rel="stylesheet" type="text/css">
        <title>Details</title>
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
            <div class="excudiv">
               
                <div>
                    <h3><?php echo $excu_name;?></h3>
                </div>

                <div id='content'>
                    <p><?php echo $excu_description;?></p>
                    <br>
                    <p><h4 class='inlineheading'>Location: </h4><?php echo $location;?></p>
                    <br>
                    <p><h4 class='inlineheading'>Price: </h4>£<?php echo $excu_price;?> (per person)</p>
                    <br>
                    <p><h4 class='inlineheading'>Duration: </h4><?php echo $duration;?></p>
                    <br>
                </div>

                <div style='text-align: center;'>
                <form method="POST"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <label>Number of Participants</label><br>
                        <input type='hidden' name=chosenexcuID value=<?php echo $excu_id;?>>
                        <input type='number' name='participants' value=1>
                        <br>
                        <button type='submit'>Book</button>
                      
                </form>
                </div>

                <!--HTML Markup to display the excursions images -->
                <div class='mainlist'>
                    <div class='rightdiv'>
                        <img class='img' src='<?php echo $img1;?>' alt='<?php echo $alt1;?>'>
                    </div>
                    <div class='rightdiv'>
                        <img class='img' src='<?php echo $img2;?>' alt='<?php echo $alt2;?>'>
                    </div>
                    <div class='rightdiv'>
                        <img class='img' src='<?php echo $img3;?>' alt='<?php echo $alt3;?>'>
                    </div>

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
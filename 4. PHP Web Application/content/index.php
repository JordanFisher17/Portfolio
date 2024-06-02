<?php
    // Start the session and regenerate ID. This is done so that the MyBookings link can be seen should the user be logged in.
    ini_set("session.save_path", "/home/unn_w22055152/sessionData");
    session_start();
    session_regenerate_id();
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
        <!-- Code for the creation of the Header and Nav bar - These elements are imported -->
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
            <div id="indeximage">
                <img class='img' src="../assets/images/homeimage.jpeg" alt="An image of a water park" style="border-radius: 10em">
            </div>
            <h4 style='text-align:center;'><em>Image: South-West Waterpark, Somerset</em></h4>
        </main>
        
        <!-- Footer Block - To be standardised across the pages and imported -->
        <footer>
            <?php
                include "../assets/templates/footer.php"
            ?>
        </footer>
    </body>
</html>
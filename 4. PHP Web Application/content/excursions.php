<?php
//PHP code in order to start the session
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
        <title>Excursions List</title>
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
                <?php

                    include "dbconn.php";

                    $sql = "SELECT * FROM excursions";

                    if ($stmt = mysqli_prepare($conn, $sql)) 
                    {
                        mysqli_stmt_execute($stmt);
                        $queryresult = mysqli_stmt_get_result($stmt);
                        //Now the results have been retrieved, a while loop iterates through these printing their details.
                        while ($currentrow = mysqli_fetch_assoc($queryresult))
                        {
                            $excu_id = $currentrow['excursionID'];
                            $excu_name = $currentrow['excursion_name'];
                            $excu_price = $currentrow['price_per_person'];
                            $location =  $currentrow['location'];
                            $duration = $currentrow['duration'];
                            $img1 = $currentrow['img1'];
                            $alt1 = $currentrow['alt1'];
                            
                            echo "
                                <div class='mainlist'>
                                    <div class='rightdiv' style='border: none'>
                                        <img class='img' src='$img1' alt='$alt1'>
                                    </div>

                                    <div class='leftdiv' style='border: none'>
                                        <p>
                                            <br><br>
                                            <strong>$excu_name</strong>
                                            <br><br>
                                            <span>Price: £$excu_price</span>
                                            <br><br>
                                            <span>Location: $location</span>
                                            <br><br>
                                            <span>Duration: $duration</span>
                                        </p>
                                        <br>
                                        <a class='viewbtn' href='excursiondetails.php?chosenexcuID=$excu_id'>View</a>
                                    </div>
                                </div>";
                        }
                        
                        mysqli_free_result($queryresult);
                        mysqli_close($conn);
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
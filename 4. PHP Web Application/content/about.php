<?php
    //Code to start the session and regenerate the session id for security.
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
        <title>About Us</title>
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
            <div class="outerdiv">
                <div class="leftdiv" style="background-color:#78C7C7">
                    <p>
                        <h3>About Us</h3>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sollicitudin, 
                        felis eu tristique rhoncus, risus neque tincidunt tortor, pellentesque sagittis mi 
                        enim sed elit. Morbi eleifend ornare porttitor. Morbi in augue quis sapien condimentum 
                        scelerisque pellentesque nec libero. Curabitur ac sapien mattis, commodo augue eget, porttitor erat. 
                        Aliquam posuere quis nisi sed dignissim. Ut egestas nisl nunc, id bibendum neque maximus nec. 
                        Sed fermentum nunc et elit blandit venenatis. In condimentum nunc erat, et egestas metus ultricies 
                        sit amet. Aenean nec finibus mauris. Vivamus efficitur finibus risus, eu feugiat dui.<br> (Lipsum, unknown)
                    </p>
                </div>
                <div class="rightdiv">
                    <img class="img" src="../assets/images/teamimage.jpg" alt="An image of the Make My Day Team Stood Together">
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

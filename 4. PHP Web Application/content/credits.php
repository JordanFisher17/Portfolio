<?php
    //Start the session and regenerate ID. Required due to the my booking anchor only showing if logged in.
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
            <div class="centerblock" style="width: 90%">
                <div class="credits">
                    <h3>Webpage Credits</h3>
                    <ul class="credits">
                        <li class="credits">
                            CodexWorld (2022) How to Validate Password Strength in PHP. 
                            Available at: "https://www.codexworld.com/how-to/validate-password-strength-in-php/ 
                            (Accessed Sunday 18th June 2023).
                        </li>
                        <li class="credits"> <!--Figma was used in order to create the wire frames for this web application -->
                            Figma (Unknown) Secure Web Development. 
                            Available at: https://www.figma.com/file/tsszyXRSrKyhZaK2ZmLPCJ/Secure-Web-Development?type=design&node-id=12-86&t=u91A9JNNBL7ktaAY-0 
                            (Accessed on multiple occasions, the last of which being on Sunday 18th June 2023).
                        </li>
                        <li class="credits">
                            Google (Unknown) Google Fonts: Playfair Display. 
                            Available at: https://fonts.google.com/specimen/Playfair+Display 
                            (Accessed Sunday 21st May 2023).
                        </li>
    
                        <li class="credits">
                            Lipsum (Unknown) Lorum Ipsum. 
                            Available At: https://www.lipsum.com. 
                            (Accessed Saturday 13th May 2023).
                        </li>
                        <li class="credits">
                            W3Schools (Unknown) PHP Form Validation. 
                            Available at: https://www.w3schools.com/php/php_form_validation.asp 
                            (Accessed Saturday 13th May 2023).
                        </li>
                        
                        <li class="credits">
                            Stuttard, D. and Pinto, M. (2011) 
                            The Web Application Hacker's Handbook: Finding and Exploiting Security Flaws. 
                            Indianapolis: John Wiley & Sons.
                        </li>
                        <li class="credits">
                            Please note that all images used in the creation of this website are the property of Jordan Fisher.
                        </li>
                    </ul>
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

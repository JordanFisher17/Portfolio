<?php
    //Start the session and regenerate it's ID
    ini_set("session.save_path", "/home/unn_w22055152/sessionData");
    session_start();
    session_regenerate_id();

    include "dbconn.php";
    include "../assets/scripts/functions.php";

    //The Management of the contact form being submitted.
    $contactError = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // A series of conditional statements using the defined functions to validate the input data.
        if (validateText($_POST['fname']) === false || validateText($_POST['lname']) === false)
        {
            $contactError = "You must enter a valid first and family name";
        }
        else if (validatePhone($_POST['telephone']) === false)//Ensures a phone number has been entered, As this will be stored as a varchar, no further processing is required.
        {
            $contactError = "Please enter a valid telephone number";
        } 
        else if (validateEmail($_POST['email']) === false) // A Function to ensure that the email address provided an "@" Symbol and no whitespace
        {
            $contactError = "Please enter a valid email address";
        }

        else if (empty($_POST['query']) || preg_match("/[<>#$]/", $_POST['query']))
        {
            $contactError = "Your query is empty or invalid";
        }
        else // If the data has successfully been validated, sanitize this for entry to the database.
        {
            $firstName = cleanData($_POST['fname']);
            $familyName = cleanData($_POST['lname']);
            $email = cleanData($_POST['email']);
            $phone = filter_var($_POST['telephone'], FILTER_SANITIZE_NUMBER_INT);
            $query = cleanData($_POST['query']);
            $sql = "INSERT INTO `queries` (`queryID`, `query_forename`, `query_surname`, `query_email`, `query_phone`, `query_question`) VALUES (NULL, ?, ?, ?, ?, ?)";

            // Using prepared statements insert the data into the database using the sanitized variables.
            if ($stmt = mysqli_prepare($conn, $sql))
            {
                mysqli_stmt_bind_param($stmt, "sssss", $firstName, $familyName, $email, $phone, $query);
                mysqli_stmt_execute($stmt);
                mysqli_close($conn);
                echo "<script>alert('Your Query has been submitted, we will aim to respond within 3 working days')</script>";
            }
            else
            {
                die("There has been an error submitting your query");
            }  
        } 
       
    }
?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../assets/css/styles.css" rel="stylesheet" type="text/css">
        <title>Contact</title>
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
            <!-- PHP Code in order to deal with Error handling -->
            <div class="outerdiv"> 
                <div class="leftdiv" style="background-color: #78C7C7">
                    <p>
                        <h3>Contact Details</h3>
                            Make My Day<br>
                            123 Blue Street<br>
                            Bristol<br>
                            BS1 1AA<br>
                            United Kingdom<br>
                            Telephone: 0117 123456
                    </p>
                <!--HTML to create the contact form. -->
                </div>
                <div class="rightdiv" style="background-color: #78C7C7">
                    <div class="forms">
                    <span class="error"><?php echo $contactError;?></span>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!--Reference: (W3Schools, Unknown) -->
                            <div class="forms">
                            <h3 class="heading">Alternative Contact</h3>
                            <p class="smallprint">Mandatory fields are marked<span class="error">*</span></p>
                            <label>Name</label><span class="error">*</span>
                            <input type="text" name="fname" placeholder="First Name">
                            <input type="text" name="lname" placeholder="Family Name">
                            <br>
                            <label>Contact Number</label><span class="error">*</span>
                            <input type="tel" name="telephone" placeholder="Telephone">
                            <br>
                            <label>Email Address</label><span class="error">*</span>
                            <input type="text" name="email" placeholder="Email Address">
                            <br>
                            <label>Your Query</label><span class="error">*</span><br><br>
                            <textarea name="query" rows="5" cols="25" placeholder="How can we help?"></textarea>
                            <br>
                            <button type="submit">Send</button>
                        </form>
                    </div>
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

<!-- PHP Code in order to deal with Error handling and inputting into the database-->
<?php 
    // Please note that no session is started on this page, it is assumed that if logged in the user would have no need to register.
    include "dbconn.php";
    include "../assets/scripts/functions.php";

    $registrationError = "";

    // On submission of the registration form by the user
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Validate the users input using the functions defined in functions.php
        if (validateText($_POST['fname']) === false || (validateText($_POST['lname']) === false))
        {
            $registrationError = 'You must enter a valid First and Family Name';
        }
        else if (validateDate($_POST['dob']) === false)
        {
            $registrationError = 'You must enter a valid Date of Birth';
        }
        else if (validatePhone($_POST['telephone']) === false)
        {
            $registrationError = 'You must enter a telephone number';
        }
        else if (validateEmail($_POST['email']) === false)
        {
            $registrationError = 'Please enter a valid Email Address';
        }
        else if (!validateText($_POST['addnumber']) || (!validateText($_POST['addstreet']) || (!validateText($_POST['addcity'])
                || (!validateText($_POST['postcode']) || (!validateText($_POST['country']))))))
        {
            $registrationError = 'Please fill out all of the address fields';
        }
        else if (empty($_POST['password']) || (empty($_POST['confpassword'])))
        {
            $registrationError = 'You must enter and confirm your password';
        }
        else if (validatepassword($_POST['password']) === false) // Returns false if the password fails to meet any of the requirements
        {
            $registrationError = "You Password must be:<br>More than 10 Characters<br>Must contain one uppercase character<br> Must Contain one lowercase character<br>Must have one number<br>and one of the following '.!?@$#'";
        }
        else if (($_POST['password']) !== ($_POST['confpassword']))
        {
            $registrationError = 'Your passwords do not match';
        }
        else // If all validation has been successfully completed, sanitize the data in prepartion for insertion to the database.
        {
            $firstName = cleanData($_POST['fname']);
            $familyName = cleanData($_POST['lname']);
            $birthDate = $_POST['dob'];
            $contact = filter_var($_POST['telephone'], FILTER_SANITIZE_NUMBER_INT);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // While the email address has already been validated, this ensures there are no illegal characters
            $address = cleanData($_POST['addnumber'] . ' ' . $_POST['addstreet'] . ', ' . $_POST['addcity']
                         . ', ' . $_POST['postcode'] . ', ' . $_POST['country']);
            $password = $_POST['password'];
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO `customers` (`customerID`,`username`, `password_hash`, `customer_forename`, `customer_surname`, `customer_email`, `date_of_birth`, `custAddress`, `customer_telephone`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)";
            // If the email entered is already in use by another user, generate an error
            if (emailavailable($conn, $email))
            {
                if($stmt = mysqli_prepare($conn, $sql))
                {
                    //Bind the values
                    mysqli_stmt_bind_param($stmt, "ssssssss", $email, $hash, $firstName, $familyName, $email, $birthDate, $address, $contact);
                    mysqli_stmt_execute($stmt);
                    mysqli_close($conn);
                    echo "<script>alert('Registration Successful')</script>";
                }
                else
                {
                    die("Error inserting into database");
                }
            }
            else // The error shown to the user should their email already be taken.
            {
                $registrationError = "Email already in use<br>To reset your password use our Contact Page";
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
        <title>Registration</title>
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
        <!-- The main section, printing the registration form for the user and processing this using htmlspecialchars-->
        <main>
            
            <div class="centerblock">
                <div class="forms">
                    <span class="error"><?php echo $registrationError;?></span>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!--Reference: (W3Schools, Unknown) -->
                        <h3 class="heading">Register</h3>
                        <p class="smallprint">Mandatory fields are marked<span class="error">*</span></p>
                        <label>Name</label><span class="error">*</span>
                        <input type="text" name="fname" placeholder="First Name">
                        <input type="text" name="lname" placeholder="Family Name">
                        <br>
                        <label>Date of Birth</label><span class="error">*</span>
                        <input type="date" name="dob">
                        <br>
                        <label>Contact Number</label><span class="error">*</span>
                        <input type="tel" name="telephone" placeholder="Telephone">
                        <br>
                        <label>Email Address</label><span class="error">*</span>
                        <input type="text" name="email" placeholder="Email Address">
                        <br>
                        <label>Home Address</label><span class="error">*</span>
                        <input type="text" name="addnumber" placeholder="Premises name or number">
                        <input type="text" name="addstreet" placeholder="Street Address">
                        <input type="text" name="addcity" placeholder="City or Town">
                        <input type="text" name="postcode" placeholder="Postcode">
                        <input type="text" name="country" placeholder="Country">
                        <br>
                        <label>Password</label><span class="error">*</span>
                        <input type="password" name="password" placeholder="Password">
                        <input type="password" name="confpassword" placeholder="Confirm Password">
                        <br>
                        <button type="submit" value="submit">Register</button>
                    </form>
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

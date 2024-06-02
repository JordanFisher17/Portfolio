
<?php
//Starting the session and importing the required php files.
    ini_set("session.save_path", "/home/unn_w22055152/sessionData");
    session_start();
    include "dbconn.php";
    include "../assets/scripts/functions.php";

// PHP Code in order to deal with Error handling and the login function
    $loginError = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            if (empty($_POST["useremail"]) || (empty($_POST["password"]))) // Ensure that the user has entered details
                {
                    $loginError = "Invalid Email or Password";

                }
                else
                {
                    $email = cleanData($_POST["useremail"]);
                    $password = $_POST["password"];
                    $sql = "SELECT * FROM `customers` WHERE `customer_email` = ?";

                if ($stmt = mysqli_prepare($conn, $sql)) //Returns a bool that the connection has been established.
                {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $queryresult = mysqli_stmt_get_result($stmt); //Take the rows obtained and place this within a variable

                    if (mysqli_num_rows($queryresult) === 1) // Ensure only a single row has been returned.
                    {
                        $userDetails = mysqli_fetch_assoc($queryresult); // Transform the results into an associative array

                        if (password_verify($password, $userDetails['password_hash'])) // Returns true if the hashed passwords match
                        {
                            $_SESSION['email'] = $userDetails['customer_email']; //Save the email address and customerID into Global session variables.
                            $_SESSION['userID'] = $userDetails['customerID'];
                            
                            // Free the dynamic memory and redirect to the home page.
                            mysqli_close($conn);
                            mysqli_free_result($queryresult);
                            echo "<script>alert('Login Successful')</script>";
                        
                        }
                        else
                        {
                            $loginError = "Invalid password";
                        }
                    }
                    else
                    {
                        $loginError = "User account not found";
                    }
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
        <title>Login</title>
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
                <span class="error"><?php echo $loginError;?></span>
                <div class="forms">
                    <form method="post"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!--Reference: (W3Schools, Unknown) -->
                        <h3 class="heading">Login</h3>
                        <p class="smallprint">Mandatory fields are marked<span class="error">*</span></p>
                        <label>Email Address</label><span class="error">*</span>
                        <input type="text" name="useremail" placeholder="Email Address">
                        <br>
                        <label>Password</label><span class="error">*</span>
                        <input type="password" name="password" placeholder="Password">
                        <br>
                        <button type="submit">Login</button>
                        <br><br>   
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

<?php

/* The Purpose of this PHP File is to create a library of functions that will be used in the creation of the 
   Make My Day Web Application. These functions include data validation for items such as paswords and email addresses
   Author: Jordan Fisher, 22055152 
   Date: May 2023 */

       
    /*
   @param: String
   @return: Bool
    A Function to clean the input data at the point of login. This sanitizes text prior to this being entered into the database
    by utilising a number of standard PHP functions to process the data.
    */
    function cleanData($input)
    {
        $input = htmlspecialchars($input);
        $input = trim($input);
        $input = stripslashes($input);
        $input = strtolower($input);
        return $input;
    }

    //A simple function that updates the total price of the excursion so that this can be printed to the user easily.
    function updateprice($arg1, $arg2)
    {
        $totalprice = $arg1 * $arg2;

        return $totalprice;
    }

     /*
    @param: String
    @return: Bool

    This function reads in the text entered by the user and seeks to validate this for any illegal characters.
    This also ensures that the user did not leave the box empty and ensures that no more than 50 characters have been entered as
    Stuttard and Pinto (2011), identified that allowing user input of a length greater than this as a security risk.
    */
    function validateText($arg)
    {
        if (empty($arg) || strlen($arg) > 50 || preg_match("/[<>#$]/", $arg))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /*
    @param: String
    @return: Bool

    This function ensures that the when registering an account with the web application they use a password
    that includes at least one upper case and one lower case character in addition to one of the shown special characters
    and one number. This aims to improve the web applications security by ensuring that the user uses a strong password.

    Please note that this code was inspired by an example shown in (Codexworld, 2022).
    */
    function validatepassword($arg)
    {
        //The following calls to preg_match all return booleans
        $arg = trim($arg);
        $containUpper = preg_match("/[A-Z]/", $arg);
        $containLower = preg_match("/[a-z]/", $arg);
        $containNumber = preg_match("/[0-9]/", $arg);
        $containSpecial = preg_match("/[.!?@$#]/", $arg);

        if (!$containUpper || !$containLower || !$containNumber || !$containSpecial || strlen($arg) < 10)
        {
            return false;
            
        }
        else
        {
            return true;

        } 
    }

    /*
    @param: String
    @return: Bool

    A simple function that validates the users email address and converts this to all lower case.
    */
    function validateEmail($email)
    {
        $email = trim(strtolower($email)); //Removes whilespace at the beginning and the end and makes all characters lowercase.
        $emailvalid = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (isset($email) && $emailvalid)
        {
        return true;
        }
        else
        {
        return false;
        }
    }

    /*
    @param: String
    @return: Bool

    A function to validate phone numbers by ensuring that they are over 10 numbers in length and 
    made up of only numbers, + and/or -.
    */
    function validatePhone($phone)
    {
        $onlyInts = trim(filter_var($phone, FILTER_SANITIZE_NUMBER_INT));

        if ($onlyInts && strlen($phone) >= 10)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    /*
    @param: String
    @return: Bool

    A basic function to ensure that the user enters a date and that the data input is in fact a date.
    */
    function validateDate($date)
    {
        if (isset($date) && strtotime($date))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /* 
    @param: Database connection, String
    @return: Bool
    The following function is used to ensure that a email addresses are not duplucated within the database 
    */
    function emailavailable($conn, $email)
    {
        $sql = "SELECT * FROM `customers` WHERE `customer_email` = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) //Returns a bool that the connection has been established.
        {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $queryresult = mysqli_stmt_get_result($stmt); //Take the rows obtained and place this within a variable

            // Ensure that the results of the sql query returned no rows. i.e that email address is not already within the database.
            if (mysqli_num_rows($queryresult) > 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }
    
?>
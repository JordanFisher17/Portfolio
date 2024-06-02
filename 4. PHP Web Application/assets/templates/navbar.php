<?php
    $myBookings = "";
    if (isset($_SESSION['userID']))
    {
        $myBookings = "My Bookings";
    }
?>

<ul id="navbar">
    <li><a href="index.php">Home</a></li>
    <li><a href="excursions.php">Excursions List</a></li>
    <li><a href="about.php">About Us</a></li>
    <li><a href="contact.php">Contact</a></li>
    <li><a href="credits.php">Credits</a></li>
    <li><a href="mybookings.php"><?php echo $myBookings;?></a></li>

</ul>
<?php
    //Start the session, clear the session variables before destroying the session and redirecting to index.php
    ini_set("session.save_path", "/home/unn_w22055152/sessionData");
    session_start();
    session_unset();
    session_destroy();

    header("Location: index.php");
?>
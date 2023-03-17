<?php
    session_start();
    session_destroy();
    header('Location: ../Back-end/SignIn.php');
?>
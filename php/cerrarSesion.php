<?php
    session_start();
    if (isset($_COOKIE["sessionid"])){
        setcookie("sessionid","",time() - 3600,"/");
    }
    session_destroy();
    header("Location:../landingpage.php");
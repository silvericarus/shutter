<?php
    function conectarServer(){
        $con = mysqli_connect("localhost","root","","shutter");

        mysqli_set_charset($con,"utf8");

        return $con;
    }
?>
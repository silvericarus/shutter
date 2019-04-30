<?php session_start();
include "./helpers.php";
$id = getId();
/*if (checkID($id,"users")==0){
    header("Location:../admin/dashboard.php");
}*/
$userData = getUser($id);
setlocale(LC_TIME,null);
$con = conectarServer();
$today = date("Y-m-d");
switch (key($_GET)){
    case 'p':
        $consulta = "INSERT INTO reporte VALUES (null,'$today',null,'$_GET[p]','$userData[id]',null)";
        mysqli_query($con,$consulta);

        break;
    case 'c':
        $consulta = "INSERT INTO reporte VALUES (null,'$today','$_GET[c]',null,'$userData[id]',null)";
        mysqli_query($con,$consulta);
        break;
    case 'e':
        $consulta = "INSERT INTO reporte VALUES (null,'$today',null,null,'$userData[id]','$_GET[e]')";
        mysqli_query($con,$consulta);
        break;
}
header("Location:./users/dashboard.php");
?>



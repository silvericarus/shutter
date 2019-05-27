<?php session_start();
include "../helpers.php";
$id = getId();
/*if (checkID($id,"users")==0){
    header("Location:../admin/dashboard.php");
}*/
$userData = getUser($id);
setlocale(LC_TIME,null);
$con = conectarServer();
if (isset($_GET["action"])){
    switch ($_GET["action"]){
        case "d":
            switch ($_GET["tipo"]){
                case "Evento":
                    $consulta = "DELETE from evento WHERE id = $_GET[id_contexto]";
                    mysqli_query($con,$consulta);
                    $consulta = "DELETE from reporte WHERE id = $_GET[id_reporte]";
                    mysqli_query($con,$consulta);
                    header("Location:./reporte.php");
                    break;
                case "Publicacion":
                    $consulta = "DELETE from publicacion WHERE id = $_GET[id_contexto]";
                    mysqli_query($con,$consulta);
                    $consulta = "DELETE from reporte WHERE id = $_GET[id_reporte]";
                    mysqli_query($con,$consulta);
                    header("Location:./reporte.php");
                    break;
                case "Comentario":
                    $consulta = "DELETE from comentario WHERE id = $_GET[id_contexto]";
                    mysqli_query($con,$consulta);
                    $consulta = "DELETE from reporte WHERE id = $_GET[id_reporte]";
                    mysqli_query($con,$consulta);
                    header("Location:./reporte.php");
                    break;
            }
            break;
        case "r":
            $consulta = "DELETE from reporte WHERE id = $_GET[id]";
            mysqli_query($con,$consulta);
            header("Location:./reporte.php");
            break;
    }
}
?>

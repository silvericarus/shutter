<?php session_start();
include "../helpers.php";
$id = getId();
/*if (checkID($id,"users")==0){
    header("Location:../admin/dashboard.php");
}*/
$userData = getUser($id);
setlocale(LC_TIME,null);
$con = conectarServer();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="../../img/favicon.png" type="image/png" sizes="32x32">
    <title>Gestión de Reportes | Shutter</title>
    <link rel="stylesheet" href="../../css/bulma.min.css">
    <link rel="stylesheet" href="../../css/bulma-extensions.min.css">
    <link rel="stylesheet" href="../../css/main.css">

    <script src="../../js/bulma-extensions.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="../../js/navbar.js"></script>
</head>
<body>
    <?php menu("php","admin");?>
    <?php if(isset($_POST["id"])):?>
    <div class="columns">
        <div class="column">
            <?php
            $consulta = "SELECT r.id, r.fecha,r.id_comentario,r.id_publicacion,r.id_evento, c.contenido as c_comentario, p.contenido as c_publicacion, u.nombre_usuario, e.titulo,e.descripcion from reporte r
LEFT JOIN usuario u on r.id_usuario = u.id
LEFT JOIN comentario c on r.id_comentario = c.id
LEFT JOIN evento e on r.id_evento = e.id
LEFT JOIN publicacion p on r.id_publicacion = p.id
where r.id=$_POST[id];";
            $data = mysqli_query($con,$consulta);
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            ?>
            <?php while (!is_null($row)):?>
            <div class="card">
                <div class="card-content">
                    <p class="title is-4">
<!--                        --><?php //var_dump($row);?>
                        <?php echo $row["id_evento"]!=null? $row["titulo"]."<br>".$row["descripcion"]: $row["id_publicacion"]!=null? $row["c_publicacion"]: $row["c_comentario"]?>
                    </p>
                    <p class="subtitle">
                        Tipo de Reporte: <?php echo $row["id_evento"]!=null? "Evento" : $row["id_publicacion"]!=null? "Publicacion": "Comentario";?>
                    </p>
                    <p>
                        <?php echo $row["fecha"]." ".$row["nombre_usuario"];?>
                    </p>
                </div>
                <footer class="card-footer">
                    <p class="card-footer-item">
                        <button class="button is-danger is-rounded" onclick="rejectReport(<?php echo $row["id"]?>)" value="Rechazar">
                            <span class="icon is-small">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <span>Rechazar</span>
                        </button>
                    </p>
                    <p class="card-footer-item">
                        <button class="button is-success is-rounded" onclick="acceptReport(<?php echo $row["id"]?>,<?php echo $row["id_evento"]!=null? $row["id_evento"]: $row["id_publicacion"]!=null? $row["id_publicacion"]: $row["id_comentario"]?>,'<?php echo $row["id_evento"]!=null? "Evento" : $row["id_publicacion"]!=null? "Publicacion": "Comentario";?>')" value="Aceptar">
                            <span class="icon is-small">
                                <i class="fas fa-gavel"></i>
                            </span>
                            <span>Aceptar</span>
                        </button>
                    </p>
                </footer>
            </div>
            <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC);?>
            <?php endwhile;?>
        </div>
    </div>
    <?php else:
    header("Location:dashboard.php");
    endif;
    ?>

    <script>
        function acceptReport(id_reporte,id_contexto,tipo) {
            window.open("./accionesReporte.php?action=d&tipo="+tipo+"&id_contexto="+id_contexto+"&id_reporte="+id_reporte,"_self");
        }

        function rejectReport(id) {
            window.open("./accionesReporte.php?action=r&id="+id,"_self");
        }
    </script>
    <?php renderFooter("php","admin")?>
</body>

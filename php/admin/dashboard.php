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
    <title>Panel de Administrador | Shutter</title>
    <link rel="stylesheet" href="../../css/bulma.min.css">
    <link rel="stylesheet" href="../../css/bulma-extensions.min.css">
    <link rel="stylesheet" href="../../css/main.css">

    <script src="../../js/bulma-extensions.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
    <?php menu("php","admin");?>

    <div class="columns">
        <div class="column">
            <div class="is-opaque">
                <?php
                    $consulta = "SELECT r.id, r.fecha, r.id_comentario, r.id_publicacion, u.nombre_usuario, r.id_evento from reporte r, usuario u where r.id_usuario=u.id;";
                    $data = mysqli_query($con,$consulta);
                    $numRows = mysqli_num_rows($data);
                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                ?>
                <?php if ($numRows>0):?>
                <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php while(!is_null($row)):?>
                        <tr>
                            <td><?php echo $row["fecha"]?></td>
                            <td><?php echo $row["nombre_usuario"]?></td>
                            <td><?php echo $row["id_evento"]!=null? "Evento" : $row["id_publicacion"]!=null? "Publicacion": "Comentario";?></td>
                            <td><form method="post" action="./reporte.php"><input type="hidden" value="<?php echo $row["id"]?>" name="id"><button class="button is-shutter-secondary is-rounded" type="submit" value="Ver">
        <span class="icon is-small">
          <i class="fas fa-eye"></i>
        </span></button></form></td>
                        </tr>
                    <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC);?>
                    <?php endwhile;?>
                    </tbody>
                </table>
                <?php else:?>
                <kbd>No hay reportes ahora mismo.</kbd>
                <?php endif;?>
            </div>
        </div>
    </div>
<?php renderFooter("php","admin")?>
</body>
</html>

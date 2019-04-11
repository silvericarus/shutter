<?php session_start();
include "../helpers.php";
$id = getId();
/*if (checkID($id,"users")==0){
    header("Location:../admin/dashboard.php");
}*/
$userData = getUser($id);
setlocale(LC_TIME,null);
$con = conectarServer();


if (!isset($_GET["u"])) {
    $consulta = "SELECT * from usuario WHERE id = '$id'";
    $consulta2 = "SELECT p.color,p.descripcion,p.tag from preferencia p,tiene t,usuario u 
                        WHERE u.id = t.id_usuario 
                        AND t.id_preferencia = p.id
                        AND u.id = $id";
} else {
    $consulta = "SELECT * from usuario WHERE nombre_usuario = '$_GET[u]'";
    $consulta2 = "SELECT p.color,p.descripcion,p.tag from preferencia p,tiene t,usuario u 
                        WHERE u.id = t.id_usuario 
                        AND t.id_preferencia = p.id
                        AND u.nombre_usuario = '$_GET[u]'";
}
$user = array();
$data = mysqli_query($con, $consulta);
$row = mysqli_fetch_array($data, MYSQLI_ASSOC);
while (!is_null($row)) {
    $user["id"] = $row["id"];
    $user["nombre_usuario"] = $row["nombre_usuario"];
    $user["img"] = $row["img"];
    $user["nivel"] = $row["nivel"];
    $user["biografia"] = $row["biografia"];
    $user["email"] = $row["email"];
    $user["pass"] = $row["pass"];
    $user["rol"] = $row["rol"];
    $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
}


?>
<!doctype html>
<html lang="es" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perfil de <?php echo $user["nombre_usuario"];?> | Shutter</title>

    <link rel="stylesheet" href="../../css/bulma.min.css">
    <link rel="stylesheet" href="../../css/bulma-extensions.min.css">
    <link rel="stylesheet" href="../../css/main.css">

    <script src="../../js/bulma-extensions.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
    <?php menu("/","users");?>
    <section class="hero is-dark is-bold">
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column">
                        <figure class="image is-128x128 is-rounded">
                            <img src="<?php echo $user["img"] != null ?  "../../img/$user[nombre_usuario]/$user[img].png" :  "../../img/userdefault.png";?>">
                        </figure>
                    </div>
                    <div class="column">
                        <h3 class="title is-3 is-spaced"><?php echo $user["nombre_usuario"]?></h3>
                        <h5 class="subtitle is-5">Nivel <?php echo $user["nivel"]?></h5>
                    </div>
                    <div class="column">
                        <span class="is-italic is-family-sans-serif"><?php echo (!is_null($user["biografia"])) ? $user["biografia"] : "No hay nada aquí aún, vuelve más tarde"; ?></span><br>
                        <span class="tag is-info is-rounded"><?php echo $user["rol"]; ?></span>
                    </div>
                    <div class="column">
                        <h5 class="subtitle is-5">Preferencias</h5>
                        <?php
                            $data = mysqli_query($con,$consulta2);

                            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                            echo "<div class=\"tags are-medium\">";
                            while (!is_null($row)){
                                echo "<span class='tag is-rounded tooltip has-text-white' data-tooltip='$row[descripcion]' style='background-color:#"; echo "$row[color]';>$row[tag]</span>";
                                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                            }
                            echo "</div>";
                        ?>
                    </div>
                    <?php if ($user["id"] != $id): ?>
                        <?php
                            $consulta3 = "SELECT id_usuario_2 from sigue where id_usuario_1 = $id and id_usuario_2 = $user[id]";

                            $data = mysqli_query($con,$consulta3);

                            $count = mysqli_num_rows($data);

                            if ($count > 0){
                                echo "<div class=\"column\">
                                        <a class=\"button is-danger is-rounded\">Dejar de seguir</a>
                                    </div>";
                            }else{
                                echo "<div class=\"column\">
                                        <a class=\"button is-success is-rounded\">Seguir</a>
                                    </div>";
                            }
                        ?>

                    <?php else: ?>
                        <div class="column">
                            <a class="button is-success is-rounded">Editar perfil</a>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </section>
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">Actualizaciones de este usuario</h3>
            <div class="timeline is-centered">
                <header class="timeline-header">
                    <span class="tag is-medium">Hoy</span>
                </header>
                <?php
                    $consulta = "SELECT DISTINCT p.contenido, p.imagen, p.fecha from usuario u, publicacion p 
                                where p.id_usuario = u.id 
                                AND p.id_usuario = $user[id] 
                                ORDER BY p.fecha DESC";

                    $data = mysqli_query($con,$consulta);
                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                    while(!is_null($row)){
                        echo "<div class='timeline-item'>" ;
                        echo "    <div class=\"timeline-marker is-icon\">
                                    <i class=\"fa fa-flag\"></i>
                                </div>";
                        echo "<div class=\"timeline-content\">
                <p class=\"heading\">";echo strftime('%d de %B de %Y',strtotime($row["fecha"])); echo"</p>
                <div class=\"card\">
                    <div class=\"card-content\">
                        <div class=\"media\">
                            <div class=\"media-left\">
                                <figure class=\"image is-48x48\">";
                        if ($user["img"] != null){
                            echo "<img src='../../img/$user[nombre_usuario]/$user[img].png'>";
                        }else{
                            echo "<img src='../../img/userdefault.png'>";

                        }
                        echo"</figure>
                                </div>
                            </div>
                            <div class=\"media-content\">
                                <p class=\"title is-4\">$user[nombre_usuario]</p>
                            </div>
                            
                        <div class=\"content\">
                            $row[contenido]";
                            if ($row["imagen"] != null){
                                echo "<figure class=\"image is-5by3\">
                                          <img src=\" ../../img/$user[nombre_usuario]/publicacion/$row[imagen]\">
                                        </figure>";
                            }

                            echo "
                        </div>
                        </div>
                    </div>
                </div>
            </div>";
                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                    }
                ?>
                <div class="timeline-header">
                    <span class="tag is-medium is-shutter-secondary">Fin</span>
                </div>
        </div>
    </div>
</body>
</html>

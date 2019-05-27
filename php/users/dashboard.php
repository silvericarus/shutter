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
    <title>Inicio | Shutter</title>
    <link rel="stylesheet" href="../../css/bulma.min.css">
    <link rel="stylesheet" href="../../css/bulma-extensions.min.css">
    <link rel="stylesheet" href="../../css/main.css">

    <script src="../../js/bulma-extensions.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>

    <script>
        function reportPub(id) {
           if (confirm("Realmente deseas reportar esta publicación? Se informará a los Administradores de ello.")){
               window.open("../report.php?p=" + id, "_self");
           } else{
               return false;
           }
        }
    </script>
</head>
<body>
    <?php menu("/","users");?>
    <?php
        if (isset($_POST["Enviar"])){
            $content = $_POST['content'];
            $imagen = $_FILES['imagen']["name"];
            $today = date("Y-m-d");
            $dir_subida = "../../img/$userData[nombre_usuario]/publicacion";

            if (!isset($_FILES)){
                $imagen = null;
            }

            if(!file_exists($dir_subida)){
                mkdir($dir_subida,0777,true);
            }

            $consulta = "INSERT INTO publicacion VALUES (null,'$content','$today','$imagen',$id)";

            mysqli_query($con,$consulta);

            move_uploaded_file($_FILES["imagen"]["tmp_name"],"../../img/".$userData["nombre_usuario"]."/publicacion/".$imagen);

        }
    ?>
    <section class="hero has-random-blurred-bg-img">
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-opaque">
                        <figure class="image is-64x64">
                            <img src="<?php echo $userData["img"] != null ?  "../../img/$userData[nombre_usuario]/$userData[img]" :  "../../img/userdefault.png";?>">
                        </figure>
                        <h1 class="title">
                            Bienvenido, <?php echo $userData["nombre_usuario"]; ?>!
                        </h1>
                    </div>
                    <div class="column is-opaque">
                        <form action="#" name="submitUpdates" method="post" enctype="multipart/form-data">
                            <div class="field">
                                <div class="control">
                                    <textarea class="textarea has-fixed-size" placeholder="Escribe tus pensamientos aquí" rows="3" name="content"></textarea>
                                </div>
                            </div>
                            <div class="field file">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="imagen">
                                    <span class="file-cta">
                                      <span class="file-icon">
                                        <i class="fas fa-upload"></i>
                                      </span>
                                      <span class="file-label">
                                        Elige un archivo
                                      </span>
                                    </span>
                                </label>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <button class="button is-shutter-secondary" type="submit" name="Enviar" id="enviarPublicacion">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">Actualizaciones de tus contactos</h3>
            <div class="timeline">
                <header class="timeline-header">
                    <span class="tag is-medium">Hoy</span>
                </header>
                <?php
                    $consulta = "select distinct p.id as idPub, u.img, u.nombre_usuario,
                                p.contenido, p.imagen, p.fecha 
                    from usuario u, sigue s, publicacion p
                    where  p.id_usuario = u.id AND (u.id = s.id_usuario_1 OR u.id = s.id_usuario_2)
                      AND s.id_usuario_1 = $id
                    ORDER BY   p.fecha DESC";
                    $data = mysqli_query($con,$consulta);
                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                    while(!is_null($row)){
                        $consulta_comentarios = "select count(*) count from comentario where id_publicacion = $row[idPub]";
                        $datos = mysqli_query($con, $consulta_comentarios);
                        $fila = mysqli_fetch_array($datos, MYSQLI_ASSOC);

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
                    if ($row["img"] != null){
                        echo "<img src='../../img/$row[nombre_usuario]/$row[img]'>";
                    }else{
                        echo "<img src='../../img/userdefault.png'>";

                    }
                                echo"</figure>
                                </figure>
                                </div>
                            </div>
                            <div class=\"media-content\">
                                <a class=\"title is-4\" href='./profile.php?u=$row[nombre_usuario]'>$row[nombre_usuario]</a>
                            </div>

                        <div class=\"content\">
                            $row[contenido]";
                            if ($row["imagen"] != null){
                                echo "<figure class=\"image is-5by3\">
                                          <img src=\"../../img/$row[nombre_usuario]/publicacion/$row[imagen]\">
                                        </figure>";
                            }
                            echo "<p class='buttons'>";
                            if ($fila["count"] != 0){
                                echo "<a class=\"button is-link is-small is-rounded\" href='./publicacion.php?p=$row[idPub]'>$fila[count] comentarios</a>";
                            }else{
                                echo "<a class=\"button is-link is-small is-rounded\" href='./publicacion.php?p=$row[idPub]'>¡Comenta!</a>";
                            }
                            echo " <button class=\"button is-danger is-small is-rounded\" onclick=\"reportPub(".$row['idPub'].")\">
                                        <span class=\"icon is-small\">
                                          <i class=\"fas fa-exclamation-triangle\"></i>
                                        </span>
                                        <span>Reportar</span>
                                      </button></p>";
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
        <div class="column">
            <h3 class="title is-3">Eventos que sigues</h3>
        <?php
            $consulta = "SELECT DISTINCT e.titulo,e.descripcion,e.id from evento e,usuario u,trabajo t WHERE t.id_usuario = u.id AND e.id = t.id_evento and u.id = $id;";

            $data = mysqli_query($con,$consulta);

            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            while (!is_null($row)){
                echo "<div class=\"card is-shutter-small-center\">";
                echo "<header class=\"card-header\">
                        <p class=\"card-header-title is-centered\">
                          $row[titulo]
                        </p>
                        </header>";
                echo "<div class=\"card-content\">
                        <div class=\"content\">
                            $row[descripcion]
                        </div>
                      </div>";
                echo "<footer class=\"card-footer\">
                        <a href=\"./evento.php?e=$row[id]\" class=\"card-footer-item\">Ver más</a>
                      </footer>
                     </div>";
                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            }
        ?>
        </div>
    </div>
<?php renderFooter("php","users");?>
</body>
</html>

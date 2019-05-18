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
<?php
$pubId = $_GET["p"];
$consulta = "SELECT p.contenido, p.imagen, p.fecha,u.img,u.nombre_usuario from usuario u, publicacion p 
                                where p.id_usuario = u.id 
                                AND p.id = $pubId;";

$data = mysqli_query($con,$consulta);
$row = mysqli_fetch_array($data,MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="../../img/favicon.png" type="image/png" sizes="32x32">
    <title>Publicación del <?php echo strftime('%d de %B de %Y',strtotime($row["fecha"])); ?> | Shutter</title>
    <link rel="stylesheet" href="../../css/bulma.min.css">
    <link rel="stylesheet" href="../../css/bulma-extensions.min.css">
    <link rel="stylesheet" href="../../css/main.css">

    <script src="../../js/bulma-extensions.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script>
        function reportCom(id) {
            if (confirm("Realmente deseas reportar este comentario? Se informará a los Administradores de ello.")){
                window.open("../report.php?c=" + id, "_self");
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
        $today = date("Y-m-d");

        $consulta = "INSERT INTO comentario VALUES (null,'$content','$today',$pubId,$userData[id])";
        mysqli_query($con,$consulta);
        header('Location: '.$_SERVER['REQUEST_URI']);

    }
    ?>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-shutter-small-center">
                <h1 class="title">
                    <?php echo strftime('%d de %B de %Y',strtotime($row["fecha"])); ?>
                </h1>
                <?php while(!is_null($row)):?>
                <div class="card">
                    <div class="card-content">
                        <div class="media">
                            <div class="media-left">
                                <figure class="image is-48x48">
                                    <?php
                                if ($row["img"] != null){
                                echo "<img src='../../img/$row[nombre_usuario]/$row[img].png'>";
                                }else{
                                echo "<img src='../../img/userdefault.png'>";
                                }
                                ?>
                                </figure>
                            </div>
                        </div>
                        <div class="media-content">
                            <p class="title is-4"><?php echo $row['nombre_usuario']?></p>
                        </div>

                        <div class="content">
                            <?php echo $row['contenido']?>
                            <?php if ($row["imagen"] != null):?>
                                <figure class="image is-5by3">
                                <img src=" ../../img/<?php echo $row['nombre_usuario']."/publicacion/".$row['imagen']?>">
                            </figure>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC);?>
            <?php endwhile;?>
            </div>
        </div>
    </section>
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">Comentarios de esta publicación</h3>
            <?php
                $consulta = "SELECT c.id as idCom,c.contenido, c.fecha, c.id_usuario, u.img, u.nombre_usuario 
FROM comentario c,publicacion p,usuario u 
WHERE c.id_publicacion= p.id 
AND c.id_usuario = u.id 
AND p.id = $pubId Order BY c.fecha ASC;";
                $data = mysqli_query($con,$consulta);
            ?>
            <?php if (mysqli_num_rows($data)==0): ?>
                <kbd>No hay comentarios en este momento, puedes solucionar eso creando uno!</kbd>
                </div>
            <?php else: ?>
            <div class="timeline">
                <header class="timeline-header">
                    <span class="tag is-medium">Hoy</span>
                </header>
            <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC); ?>
            <?php while(!is_null($row)):?>
                <div class='timeline-item'>
                    <div class="timeline-marker is-icon">
                        <i class="fa fa-flag"></i>
                    </div>
                    <div class="timeline-content">
                        <p class="heading"><?php echo strftime('%d de %B de %Y',strtotime($row["fecha"]));?></p>
                        <div class="card">
                            <div class="card-content">
                                <div class="media">
                                    <div class="media-left">
                                        <figure class="image is-48x48">
                                            <?php
                                            if ($userData["img"] != null){
                                            echo "<img src='../../img/$row[nombre_usuario]/$row[img].png'>";
                                            }else{
                                            echo "<img src='../../img/userdefault.png'>";
                                            }
                                            ?>
                                        </figure>
                                    </div>
                                </div>
                                <div class="media-content">
                                    <a class="title is-4" href="./profile.php?u=<?php echo $row['nombre_usuario']?>"><?php echo $row['nombre_usuario']?></a>
                                </div>
                                <div class="content">
                                    <?php
                                    echo $row['contenido'];
                                    ?>
                                    <p class="buttons">
                                        <button class="button is-danger is-small is-rounded" onclick="reportCom( <?php echo $row['idCom']?>)">
                                            <span class="icon is-small">
                                          <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                            <span>Reportar</span>
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC); ?>
            <?php endwhile;?>
                <div class="timeline-header">
                    <span class="tag is-medium is-shutter-secondary">Fin</span>
                </div>
        </div>
        </div>
            <?php endif;?>
        <div class="column">
            <form method="post" action="#" name="coment" class="is-sticky">
                <div class="field">
                    <label class="label">Comentario</label>
                    <div class="control has-icons-right">
                        <textarea class="textarea has-fixed-size" placeholder="Comentario" rows="5" name="content"></textarea>
                        <span class="icon is-small is-right">
                            <i class="fas fa-bullhorn"></i>
                        </span>
                    </div>
                </div>
                <div class="field">
                    <p class="control">
                        <button class="button is-shutter-secondary" type="submit" value="Enviar" name="Enviar">
                            Enviar
                        </button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>

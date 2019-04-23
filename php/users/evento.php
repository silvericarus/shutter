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
$eventId = $_GET["e"];
$consulta = "SELECT e.titulo,e.reglas,e.descripcion,e.estado,e.limite_participantes from evento e
where e.id = $eventId";

$data = mysqli_query($con,$consulta);
$row = mysqli_fetch_array($data,MYSQLI_ASSOC);
$eventState = $row["estado"];
$eventParticipants = $row["limite_participantes"];
?>
<?php
if (isset($_POST["id"])){
    $consulta1 = "INSERT into vota values ($userData[id],$_POST[id]);";
    mysqli_query($con,$consulta1);
    header("Refresh:0");
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $row["titulo"]?> | Shutter</title>
    <link rel="stylesheet" href="../../css/bulma.min.css">
    <link rel="stylesheet" href="../../css/bulma-extensions.min.css">
    <link rel="stylesheet" href="../../css/main.css">

    <script src="../../js/bulma-extensions.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="../../js/file.js"></script>
</head>
<body>
    <?php menu("/","users");?>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-shutter-small-center">
            <div class="media-content">
                <?php while(!is_null($row)):?>
                    <div class="card">
                        <div class="card-content">
                            <div class="media-content">
                                <h1 class="title is-3">
                                    <?php echo $row["titulo"]?>
                                </h1>
                                <p class="subtitle is-4"><?php echo $row['descripcion']?></p>
                            </div>
                            <div class="content" style="margin-top: 5px;">
                                <div class="columns">
                                    <div class="column">
                                        <?php renderEventRules(explode(". ",$row['reglas']))?>
                                    </div>
                                    <div class="column">
                                        <?php switch ($row["estado"]):
                                            case 'inicio':
                                                echo "<span class=\"tag is-info is-large\">Presentación de Proyectos</span>";
                                                break;
                                            case 'vota':
                                                echo "<span class=\"tag is-warning is-large\">Vota Ya!</span>";
                                                break;
                                            case 'fin':
                                                echo "<span class=\"tag is-dark is-large\">Evento Terminado</span>";
                                                break;
                                        endswitch;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC);?>
                <?php endwhile;?>
            </div>
        </div>
    </section>
<?php if($eventState=="inicio"):?>
    <div class="columns">
        <div class="column is-shutter-small-center">
            <?php
            $consulta ="SELECT COUNT(t.id) as trabajos from trabajo t, evento e where t.id_evento = e.id and e.id = 1;";
            $data = mysqli_query($con,$consulta);
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);?>
            <?php while (!is_null($row)):?>
                <?php if ($row["trabajos"]!=$eventParticipants):?>
                    <form action="#" method="post" name="subirPubli" enctype="multipart/form-data">
                        <div class="field">
                            <div class="file is-large is-boxed has-name">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="publi">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                          <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                          Large file…
                                        </span>
                                      </span>
                                        <span class="file-name">
                                        example.png
                                      </span>
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button class="button is-shutter-secondary" type="submit" name="Enviar" id="enviarPublicacion">Enviar</button>
                            </div>
                        </div>
                    </form>
                <?php else:?>
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="field">
                            <div class="file is-large is-boxed has-name">
                                <label class="file-label">
                                    <input class="file-input" type="file" disabled>
                                    <span class="file-cta">
                                                <span class="file-icon">
                                                  <i class="fas fa-upload"></i>
                                                </span>
                                                <span class="file-label">
                                                  Large file…
                                                </span>
                                              </span>
                                    <span class="file-name">
                                                example.png
                                              </span>
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button class="button is-shutter-secondary" type="submit" id="enviarPublicacion" disabled>Enviar</button>
                            </div>
                        </div>
                    </form>
                <?php endif;?>
            <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC);?>
            <?php endwhile;?>
        </div>
    </div>
<?php elseif ($eventState=="vota"):?>
    <div class="columns">
        <div class="column is-shutter-small-center">
            <?php $consulta = "SELECT t.url,t.id from evento e,trabajo t
where e.id = t.id_evento AND e.id = $eventId ;";
            $data = mysqli_query($con,$consulta);
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            ?>
            <?php while (!is_null($row)):?>
            <div class="card">
                <div class="card-image">
                    <figure class="image is-4by3">
                        <img src="<?php echo $row['url']?>" alt="<?php echo $row['titulo']?>">
                    </figure>
                </div>
                <div class="card-content">
                    <div class="content">
                        <?php if(checkVoteUser($userData["id"],$eventId)==0):?>
                        <form action="#" method ="post" name="vote">
                            <input type="hidden" name="id" value="<?php echo $row['id']?>">
                            <div class="field">
                                <div class="control">
                                    <button class="button is-shutter-secondary is-rounded">Votar</button>
                                </div>
                            </div>
                        </form>
                        <?php else:?>
                        <form action="#" method ="post" name="vote">
                            <div class="field">
                                <div class="control">
                                    <button class="button is-shutter-secondary is-rounded" disabled>Ya has votado</button>
                                </div>
                            </div>
                        </form>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC); ?>
            <?php endwhile;?>
        </div>
    </div>

<?php else: ?>
    <div class="columns">
        <div class="column is-shutter-small-center">
            <?php
                $consulta = "SELECT t.titulo,t.url,t.f_present,u.nombre_usuario,u.img,u.nivel from trabajo t,usuario u where t.id_usuario = u.id AND t.id_evento_ganador = 1;";
                $data = mysqli_query($con,$consulta);
                $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
            ?>
            <?php while(!is_null($row)):?>
                <div class="card">
                    <div class="card-image">
                        <figure class="image is-4by3">
                            <img src="<?php echo $row['url']?>" alt="<?php echo $row['titulo']?>">
                        </figure>
                    </div>
                    <div class="card-content">
                        <div class="media">
                            <div class="media-left">
                                <?php if (is_null($row['img'])):?>
                                <figure class="image is-48x48">
                                    <img src="../../img/userdefault.png">
                                </figure>
                                <?php else:?>
                                <figure class="image is-48x48">
                                    <img src="../../img/<?php echo $row['nombre_usuario']. "/".$row['img'];?>">
                                </figure>
                                <?php endif;?>
                            </div>
                            <div class="media-content">
                                <a class="title is-4" href="./profile.php?u=<?php echo $row['nombre_usuario']?>"><?php echo $row['nombre_usuario']?></a>
                                <div class="field is-grouped is-grouped-multiline">
                                    <div class="control">
                                        <div class="tags has-addons">
                                            <span class="tag is-dark">Nivel</span>
                                            <span class="tag is-info"><?php echo $row['nivel']?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="content">
                            <?php echo $row['titulo']?>
                            <br>
                            <time datetime="<?php echo $row['f_present']?>"><?php echo strftime('%d de %B de %Y',strtotime($row["f_present"]));?></time>
                        </div>
                    </div>
                </div>
            <?php $row = mysqli_fetch_array($data, MYSQLI_ASSOC);?>
            <?php endwhile;?>
        </div>
    </div>
<?php endif;?>
</body>
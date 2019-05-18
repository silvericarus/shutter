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
    <title>Explorar | Shutter</title>
    <link rel="stylesheet" href="../../css/bulma.min.css">
    <link rel="stylesheet" href="../../css/bulma-extensions.min.css">
    <link rel="stylesheet" href="../../css/main.css">

    <script src="../../js/bulma-extensions.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="../../js/tabs.js"></script>
    <script src="../../js/navbar.js"></script>
</head>
<body>
    <?php menu("/","users");?>
    <?php
        if (isset($_POST["Enviar"])){
            if(isset($_POST["user"])){
                //user

            }else{
                //Evento
            }

        }
    ?>
    <div class="tabs-wrapper is-opaque">
        <div class="tabs is-centered">
            <ul>
                <li class="is-active">
                    <a>Usuarios</a>
                </li>
                <li>
                    <a>Eventos</a>
                </li>
            </ul>
        </div>

        <div class="tabs-content">
            <ul>
                <li class="is-active">
                    <h3 class="title is-3">Usuarios</h3>
                    <div class="columns">
                        <div class="column is-two-thirds">
                            <?php
                                if (!isset($_POST["user"])){
                                    $consulta="SELECT nombre_usuario, nivel, biografia from usuario where rol = 'users';";

                                }else{
                                    $consulta = "SELECT nombre_usuario, nivel, biografia from usuario where rol = 'users' and nombre_usuario LIKE '%$_POST[user]%';";
                                }
                            $data = mysqli_query($con,$consulta);
                                $numrows = mysqli_num_rows($data);
                                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                                $colors = [
                                    "is-primary",
                                    "is-secondary",
                                    "is-dark",
                                    "is-success",
                                    "is-warning"
                                ];

                                $counter = 0;
                            ?>
                            <?php if ($numrows>0):?>
                                <?php while(!is_null($row)):?>
                                <div class="tile is-ancestor">
                                    <div class="tile is-parent">
                                        <div class="tile is-child notification <?php echo $colors[array_rand($colors)]?>">
                                            <a class="title is-4" href="./profile.php?u=<?php echo $row['nombre_usuario']?>"><?php echo $row["nombre_usuario"]?></a>
                                            <div class="control">
                                                <div class="tags has-addons">
                                                    <span class="tag is-dark">Nivel</span>
                                                    <span class="tag is-info"><?php echo $row["nivel"]?></span>
                                                </div>
                                            </div>
                                            <p class="subtitle"><?php echo !is_null($row["biografia"])?$row["biografia"]:'&nbsp;'?></p>
                                        </div>
                                    </div>
                                        <?php if($counter%2==0):?>
                                </div>
                                        <?php else:
                                            $counter++;
                                        endif;
                                        ?>

                                <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC);?>
                                <?php endwhile;?>
                            <?php else:?>
                            <kbd>No hay usuarios a mostrar con esos parámetros</kbd>
                            <?php endif;?>
                        </div>
                        <div class="is-divider-vertical"></div>
                        <div class="column">
                            <h3>Filtrar</h3>
                            <form action="#" name="buscarUsers" method="post">
                                <div class="field">
                                    <p class="control has-icons-left">
                                        <input class="input" type="text" placeholder="Nombre de usuario" name="user">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </p>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-shutter-secondary is-fullwidth" type="submit">Buscar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </li>
                <li>
                    <h3 class="title is-3">Eventos</h3>
                    <div class="columns">
                        <div class="column is-two-thirds">
                            <?php
                            if (!isset($_POST['titulo'])){
                                $consulta1 ="SELECT id,titulo, descripcion, estado from evento;";
                            }else{
                                $consulta1 ="SELECT e.id,e.titulo, e.descripcion, e.estado from evento e
LEFT JOIN cubre as c ON c.id_evento = e.id
LEFT JOIN preferencia as p ON p.id = c.id_preferencia
where e.titulo LIKE '%$_POST[titulo]%' OR e.estado = '$_POST[estado]' OR p.tag = '$_POST[preferencia]';";
                            }

                            $data = mysqli_query($con,$consulta1);
                            $numrows = mysqli_num_rows($data);
                            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                            $colors1 = [
                                "is-info",
                                "is-warning",
                                "is-dark"
                            ];
                            $counter = 0;
                            ?>
                            <?php if ($numrows>0):?>
                                <?php while(!is_null($row)):?>
                                    <div class="tile is-ancestor">
                                    <div class="tile is-parent">
                                        <div class="tile is-child notification <?php echo $row["estado"]=="inicio"? $colors1[0]:($row["estado"]=="vota"? $colors1[1]:$colors1[2])?>">
                                            <a class="title is-4" href="evento.php?e=<?php echo $row['id']?>"><?php echo $row["titulo"]?></a>
                                            <div class="control">
                                                <div class="tags has-addons">
                                                    <span class="tag is-dark">Estado</span>
                                                    <span class="tag is-info"><?php echo ucfirst($row["estado"])?></span>
                                                </div>
                                            </div>
                                            <p class="subtitle"><?php echo !is_null($row["descripcion"])?$row["descripcion"]:'&nbsp;'?></p>
                                        </div>
                                    </div>
                                    <?php if($counter%2==0):?>
                                        </div>
                                    <?php else:
                                        $counter++;
                                    endif;
                                    ?>

                                    <?php $row = mysqli_fetch_array($data,MYSQLI_ASSOC);?>
                                <?php endwhile;?>
                            <?php endif;?>
                        </div>
                        <div class="is-divider-vertical"></div>
                        <div class="column">
                            <h3>Filtrar</h3>
                            <form action="#" method="post" name="buscarEventos">
                                <div class="field">
                                    <p class="control has-icons-left">
                                        <input class="input" type="text" placeholder="Título de evento" name="titulo">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-heading"></i>
                                        </span>
                                    </p>
                                </div>
                                <div class="field">
                                    <div class="control is-rounded has-icons-left">
                                        <div class="select">
                                            <select name="estado">
                                                <option>-</option>
                                                <option value="inicio">Inicio</option>
                                                <option value="vota">Vota</option>
                                                <option value="fin">Fin</option>
                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-shoe-prints"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="control is-rounded has-icons-left">
                                        <div class="select">
                                            <select name="preferencia">
                                                <option>-</option>
                                                <?php
                                                    $consulta2 ="SELECT id,tag from preferencia";
                                                    $data = mysqli_query($con,$consulta2);
                                                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                                                ?>
                                                <?php while(!is_null($row)):?>
                                                <option value="<?php echo $row['id']?>"><?php echo $row['tag']?></option>
                                                <?php
                                                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                                                endwhile;
                                                ?>

                                            </select>
                                        </div>
                                        <div class="icon is-small is-left">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-shutter-secondary is-fullwidth" type="submit">Buscar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                    <?php

                    ?>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>

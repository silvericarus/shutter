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
    <title>Crear Evento | Shutter</title>
    <link rel="stylesheet" href="../../css/bulma.min.css">
    <link rel="stylesheet" href="../../css/bulma-extensions.min.css">
    <link rel="stylesheet" href="../../css/bulma-calendar.min.css">
    <link rel="stylesheet" href="../../css/main.css">

    <script src="../../js/bulma-extensions.min.js"></script>
    <script src="../../js/bulma-calendar.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="../../js/tabs.js"></script>
    <script src="../../js/navbar.js"></script>
    <script src="../../js/calendar.js"></script>
</head>
<body>
    <?php menu("/", "users"); ?>
    <?php
        if (isset($_POST["Crear"])){
            if ($_POST["fin_f_votacion"]<=$_POST["fin_f_proyectos"]){
                header("Location:./crearEvento.php?error=dateError");
            }
            $idNuevoEvento = "";

            $consulta = "SELECT AUTO_INCREMENT from information_schema.TABLES WHERE TABLE_SCHEMA = 'shutter' and TABLE_NAME = 'evento'";
            $data = mysqli_query($con,$consulta);
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            while (!is_null($row)){
                $idNuevoEvento = $row["AUTO_INCREMENT"];
                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            }

            $sql = "INSERT INTO evento VALUES (null ,'$_POST[titulo]','$_POST[descripcion]','$_POST[reglas]','$_POST[fin_f_proyectos]','$_POST[fin_f_votacion]','inicio',$_POST[limite_participantes])";
            if(mysqli_query($con,$sql)){
                foreach ($_POST["preferencia"] as $count=>$preferencia){
                    $sql1 = "INSERT INTO cubre VALUES ($preferencia,$idNuevoEvento)";
                    mysqli_query($con,$sql1);
                    var_dump(mysqli_error($con));
                }
                header("Location:./evento.php?e=$idNuevoEvento");
            }





        }
    ?>
    <section class="hero">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-5-tablet is-4-desktop is-3-widescreen">
                        <form action="#" class="box" method="post" name="createEvent">
                            <div class="field">
                                <label class="label">Título</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="text" placeholder="Título" name="titulo" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-heading"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Descripción</label>
                                <div class="control">
                                    <textarea class="textarea" placeholder="Descripción" name="descripcion" required></textarea>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Reglas</label>
                                <div class="control">
                                    <textarea class="textarea" type="text" placeholder="Un punto seguido de un espacio se identificará como una nueva regla." name="reglas" required></textarea>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Fecha Fin Presentación de Proyectos</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="date" placeholder="dd/mm/yyyy" name="fin_f_proyectos" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-file-image"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Fecha Fin Votación (Posterior a Presentación de Proyectos)</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="date" placeholder="dd/mm/yyyy" name="fin_f_votacion" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-poll"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Límite de participantes</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="number" placeholder="0" name="limite_participantes" min="0" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-user-friends"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Preferencias</label>
                                <div class="control has-icons-left">
                                    <div class="select is-multiple">
                                        <select multiple size="6" name="preferencia[]">
                                            <?php
                                                $consulta = "SELECT id,tag FROM preferencia;";

                                                $data = mysqli_query($con,$consulta);

                                                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                                                while (!is_null($row)){
                                                    echo "<option value=\"$row[id]\">$row[tag]</option>";
                                                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                                                }
                                            ?>
                                        </select>
                                        <span class="icon is-small is-left">
                                        <i class="fas fa-heart"></i>
                                    </span>
                                    </div>

                                </div>
                            </div>
                            <div class="field">
                                <input class="button is-shutter-secondary is-fullwidth is-rounded" type="submit" name="Crear" value="Crear">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php renderFooter("php","users")?>
    <script>
        // Initialize all input of type date
        var calendars = bulmaCalendar.attach('[type="date"]', options);

        // Loop on each calendar initialized
        for(var i = 0; i < calendars.length; i++) {
            // Add listener to date:selected event
            calendars[i].on('select', date => {
                console.log(date);
            });
        }

    </script>

</body>

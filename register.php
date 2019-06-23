<?php session_start();
    include "php/helpers.php";
    $con = conectarServer();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="img/favicon.png" type="image/png" sizes="32x32">
    <title>Registrarse | Shutter</title>
    <link rel="stylesheet" href="css/bulma.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/navbar.js"></script>

    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
    <?php
    if (isset($_POST["Enviar"])){
        $idNuevoUsuario = "";
        $name = $_POST["user"];
        $pass = $_POST["pass"];
        $mail = $_POST["mail"];
        $preferencias = $_POST["preferencias"];

        if (checkUser($name)){
            $consulta = "SELECT AUTO_INCREMENT from information_schema.TABLES WHERE TABLE_SCHEMA = 'shutter' and TABLE_NAME = 'usuario'";
            $consulta1 = "INSERT INTO usuario values (null,'$name',null,0,null,'$mail','$pass','users')";

            $data = mysqli_query($con,$consulta);

            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            while (!is_null($row)){
                $idNuevoUsuario = $row['AUTO_INCREMENT'];
                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            }

            $data = mysqli_query($con,$consulta1);


            foreach ($preferencias as $count=>$preferencia){
                $consulta2 = "INSERT INTO tiene values ($idNuevoUsuario,$preferencia)";

                $data = mysqli_query($con,$consulta2);

            }

            $consulta3 = "INSERT INTO sigue values($idNuevoUsuario,$idNuevoUsuario)";

            $data = mysqli_query($con,$consulta3);

            header("Location:./login.php");
        }else{
            mysqli_close($con);
            header("Location:./register.php?nologin=1");
        }
        $data = mysqli_query($con,$consulta);




    }
    ?>
    <?php if (isset($_GET["nologin"])):?>
        <div class="notification is-danger">
            Oh no! Ya hay alguien que ha elegido ese nombre! Prueba con uno diferente.
        </div>
    <?php endif;?>
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-5-tablet is-4-desktop is-3-widescreen">
                        <form action="#" class="box" method="post" name="register">
                            <div class="field has-text-centered">
                                <img src="img/logonombre.png" alt="Logo Nombre" width="167px">
                            </div>
                            <div class="field">
                                <label class="label">Usuario</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="text" placeholder="Nombre de usuario" name="user" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Correo Electrónico</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="email" placeholder="example@shutter.com" name="mail" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Contraseña</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="password" placeholder="Contraseña" name="pass" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Preferencias</label>
                                <div class="control has-icons-left">
                                    <div class="select is-multiple">
                                        <select multiple name="preferencias[]">
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
                                <input class="button is-shutter-primary" name="Enviar" value="Enviar" type="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
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
    <title>Login | Shutter</title>
    <link rel="stylesheet" href="css/bulma.min.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
    <?php
    if (isset($_POST["Enviar"])){
        $name = $_POST["user"];
        $pass = $_POST["pass"];
        $rememberMe = $_POST["rememberMe"];

        $consulta = "SELECT id,rol from usuario WHERE nombre_usuario = '$name' AND pass = '$pass'";
        $data = mysqli_query($con,$consulta);

        if(mysqli_num_rows($data)==0){
            header("Location:./login.php?nologin=1");
        }else{
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            while(!is_null($row)){
                $role = $row["rol"];
                $id = $row["id"];

                $_SESSION["sessionid"] = $id;
                if (!empty($rememberMe)) {
                    $datossession = session_encode();

                    setcookie("sessionid", $datossession, time() + 31557600, "/");
                }

                if ($role=="users"){
                    header("Location:./php/users/dashboard.php");
                }else{
                    header("Location:./php/admin/dashboard.php");
                }
                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            }
        }
    }
    ?>
    <?php if (isset($_GET["nologin"])):?>
    <div class="notification is-danger">
        El usuario o la contraseña no son correctos! Prueba de nuevo con otros datos.
    </div>
    <?php endif;?>
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-5-tablet is-4-desktop is-3-widescreen">
                        <form action="#" class="box" method="post" name="login">
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
                                <label class="label">Contraseña</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="password" placeholder="Contraseña" name="pass" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="checkbox">
                                    <input type="checkbox" name="rememberMe">
                                    Recuérdame en este equipo
                                </label>
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
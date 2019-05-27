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
    <title>Editar perfil | Shutter</title>
    <link rel="stylesheet" href="../../css/bulma.min.css">
    <link rel="stylesheet" href="../../css/bulma-extensions.min.css">
    <link rel="stylesheet" href="../../css/main.css">

    <script src="../../js/bulma-extensions.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
    <?php menu("php","users");?>
    <?php
    if (isset($_POST["Guardar"])){
        if (isset($_FILES["image"]["tmp_name"])){

            $biografia = $_POST["biografia"]!=""?$_POST["biografia"]:NULL;
            $biografia = "'".$biografia."'";
            $destino = "../../img/".$userData["nombre_usuario"]."/";
            if(!file_exists($destino)){
                mkdir($destino,0777,true);
            }
            move_uploaded_file($_FILES["image"]["tmp_name"],$destino."profile.png");
            $consulta = "update usuario SET nombre_usuario='$_POST[nombre_usuario]',
                                            img='profile.png',
                                            biografia=$biografia,
                                            email='$_POST[email]',
                                            pass='$_POST[contra]' where id = $userData[id]";
            mysqli_query($con,$consulta);
        }else{
            $biografia = $_POST["biografia"]!=""?$_POST["biografia"]:NULL;
            $biografia = "'".$biografia."'";
            $consulta = "update usuario SET nombre_usuario='$_POST[nombre_usuario]',
                                            img=NULL,
                                            biografia=$biografia,
                                            email='$_POST[email]',
                                            pass='$_POST[contra]' where id = $userData[id]";
            mysqli_query($con,$consulta);
        }
    }
    ?>
    <div class="columns">
        <div class="column">
            <section class="hero">
                <div class="hero-body">
                    <form action="#" method="post" name="editProfile" enctype="multipart/form-data">
                        <div class="field">
                            <div class="file has-name is-boxed">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="image" accept="image/png">
                                    <span class="file-cta">
                                      <span class="file-icon">
                                        <i class="fas fa-upload"></i>
                                      </span>
                                      <span class="file-label">
                                        Choose a file…
                                      </span>
                                    </span>
                                                <span class="file-name">
                                      example.png
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Nombre de usuario</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="nombre_usuario" value="<?php echo $userData["nombre_usuario"]?>">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-user"></i>
                                </span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Biografía</label>
                            <div class="control has-icons-right">
                                <textarea class="textarea" name="biografia" ><?php echo $userData["biografia"]?></textarea>
                                <span class="icon is-small is-right">
                                  <i class="fas fa-feather"></i>
                                </span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="email" value="<?php echo $userData["email"]?>">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Contraseña</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="contra" value="<?php echo $userData["pass"]?>">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-key"></i>
                                </span>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input class="button is-shutter-secondary is-rounded" type="submit" name="Guardar" value="Guardar">
                            </div>
                        </div>

                    </form>
                </div>
            </section>
        </div>
    </div>
    <?php renderFooter("php","users")?>
</body>
</html>

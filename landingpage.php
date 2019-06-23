<?php session_start();
    include "php/helpers.php";
    $id = getId();
    checkID($id,"nologin");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="icon" href="img/favicon.png" type="image/png" sizes="32x32">
    <title>Bienvenido | Shutter</title>
    <link rel="stylesheet" href="css/bulma.min.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/navbar.js"></script>

    
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
    <!--Barra navegación-->
    <nav class="navbar is-shutter-primary" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="#">
                <img src="img/nombre.png" width="112" height="26" alt="Logo de Shutter">
            </a>

            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="#aboutus">
                    Sobre Nosotros
                </a>

                <a class="navbar-item" href="#winners">
                    Ganadores de los últimos eventos
                </a>

                <a class="navbar-item" href="#lastevents">
                    Últimos eventos
                </a>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <a class="button is-shutter-secondary" href="register.php">
                            Únete
                        </a>
                        <a class="button is-shutter-secondary" href="login.php">
                            Inicia sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!--Fin Barra navegación-->
    <!--Sección Sobre Nosotros-->
    <section class="hero is-medium has-random-blurred-bg-img is-bold">
        <div class="hero-body">
            <div class="container is-opaque">
                <h1 class="title">
                    <span id="aboutus">Sobre nosotros</span>
                </h1>
                <h2 class="subtitle">
                    Hay muchos tipos de fotógrafos, casi tantos como de objetivos, o de cámaras en el mercado. Éste es vuestro punto de encuentro. Conoced nuevos compañeros que comparten vuestra pasión y quizá vuestros gustos. Participad en numerosas competiciones de fotografía organizadas por nuestra comunidad y alzaos con la victoria mientras subid por las escalera de la fama. Conoced en que están trabajando las personas que os interesan, y también contadle a todos los que le interesáis qué es de vuestra vida. Desde los amantes de los felinos aficionados a la fotografía de sus amigos peludos hasta los dueños de un estudio profesional en búsqueda de ampliar sus horizontes con nuevas ideas y proyectos, todos tienen cabida en la comunidad de Shutter. Así que...<strong>¿porque no te unes?</strong>
                </h2>
            </div>
        </div>
    </section>
    <!--Sección imágenes-->
    <?php


    $conector = conectarServer();

    $consulta = "SELECT url,titulo from trabajo WHERE id_evento_ganador IS NOT NULL order by f_present desc LIMIT 3";

    $data = mysqli_query($conector,$consulta);
    ?>

    <section class="hero">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    <span id="winners">Ganadores de los últimos eventos</span>
                </h1>
                <div class="columns">
                    <?php
                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                    while(!is_null($row)){
                        echo "<div class='column'>";
                        echo "<div class=\"card\">
                          <div class=\"card-image\">
                            <figure class=\"image is-3by2\">
                              <img src=\"$row[url]\" alt=\"$row[titulo]\" width='288px'>
                            </figure>
                          </div>
                          <div class=\"card-content\">
                            <div class=\"content\" style='text-align: center'>
                              $row[titulo]
                            </div>
                          </div>
                        </div>";
                        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>

        </div>
    </section>
    <!--Sección Eventos-->
    <?php
        $consulta = "SELECT titulo,descripcion,fin_f_presentacion FROM `evento` order by fin_f_presentacion desc limit 2";

        $data = mysqli_query($conector,$consulta);
    ?>

    <section class="hero">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    <span id="lastevents">Últimos Eventos</span>
                </h1>
                <div class="columns">
                    <?php
                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                    while(!is_null($row)){
                        $fechaahora = time();
                        $diferenciafechas = $fechaahora - strtotime($row["fin_f_presentacion"]);
                        $diferenciafechas = $diferenciafechas / (60 * 60 * 24);
                        $diferenciafechas = abs($diferenciafechas);
                        $diferenciafechas = round($diferenciafechas);
                        echo "<div class='column'>";
                        echo "<div class=\"card\">
                            <header class=\"card-header\">
                                <p class=\"card-header-title\">
                                  $row[titulo]
                                </p>";
                        if($diferenciafechas <= 30){
                            echo "<span class=\"card-header-icon is-warning\">
                                  <span class=\"icon\">
                                    <i class=\"fas fa-clock\" aria-hidden=\"true\" title='¡A este evento le queda poco tiempo de presentación de proyectos!'></i>
                                  </span>
                                </span>";
                        }
                             echo"</header>
                          <div class=\"card-content\">
                            <div class=\"content\" style='text-align: center'>
                              $row[descripcion]
                            </div>
                          </div>
                        </div>";
                        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
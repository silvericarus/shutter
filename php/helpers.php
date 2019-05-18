<?php
    function conectarServer(){
        $con = mysqli_connect("localhost","root","","shutter");

        mysqli_set_charset($con,"utf8");

        return $con;
    }

    function getId(){
        if (isset($_SESSION["sessionid"])) {
            return $_SESSION["sessionid"];
        }else if (isset($_COOKIE["sessionid"])) {
            session_decode($_COOKIE["sessionid"]);
            return $_SESSION["sessionid"];
        }else{
            return null;
        }
    }

    function checkID($id,$mode){
        if ($mode == "nologin"){
            if ($id != null){
                $con = conectarServer();
                $consulta = "SELECT rol from usuario WHERE id = '$id'";
                $data = mysqli_query($con,$consulta);
                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                while(!is_null($row)) {
                    $role = $row["rol"];
                    if ($role == "users"){
                        header("Location:./php/users/dashboard.php");
                    }else{
                        header("Location:./php/admin/dashboard.php");
                    }
                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                }
            }
        }else{
            $con = conectarServer();
            $consulta = "SELECT rol from usuario WHERE id = '$id'";
            $data = mysqli_query($con,$consulta);
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            while(!is_null($row)) {
                $role = $row["rol"];
                if ($role==$mode){
                    return 1;
                }else{
                    return 0;
                }
                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            }
        }


    }

    function menu($ruta,$rol){
        if ($rol == "users"){
            if ($ruta == "/"){
                echo "<nav class=\"navbar is-shutter-primary\" role=\"navigation\" aria-label=\"main navigation\">
  <div class=\"navbar-brand\">
    <a class=\"navbar-item\" href=\"./dashboard.php\">
      <img src=\"../../img/nombre.png\" width=\"112\" height=\"26\">
    </a>

    <a role=\"button\" class=\"navbar-burger burger\" aria-label=\"menu\" aria-expanded=\"false\" data-target=\"navbarBasicExample\">
      <span aria-hidden=\"true\"></span>
      <span aria-hidden=\"true\"></span>
      <span aria-hidden=\"true\"></span>
    </a>
  </div>

  <div id=\"navbarBasicExample\" class=\"navbar-menu\">
    <div class=\"navbar-start\">

      <a class=\"navbar-item\" href='./profile.php'>
        Perfil
      </a>

      <a class=\"navbar-item\" href='./explorar.php'>
         Explorar
      </a>
      
      <a class=\"navbar-item\" href='../cerrarSesion.php'>
         Cerrar Sesión
      </a>
    </div>
  </div>
</nav>";
            }else{
                echo "<nav class=\"navbar\" role=\"navigation\" aria-label=\"main navigation\">
  <div class=\"navbar-brand\">
    <a class=\"navbar-item\" href=\"./dashboard.php\">
      <img src=\"../../img/nombre.png\" width=\"112\" height=\"26\">
    </a>

    <a role=\"button\" class=\"navbar-burger burger\" aria-label=\"menu\" aria-expanded=\"false\" data-target=\"navbarBasicExample\">
      <span aria-hidden=\"true\"></span>
      <span aria-hidden=\"true\"></span>
      <span aria-hidden=\"true\"></span>
    </a>
  </div>

  <div id=\"navbarBasicExample\" class=\"navbar-menu\">
    <div class=\"navbar-start\">

      <a class=\"navbar-item\" href='./profile.php'>
        Perfil
      </a>

      <a class=\"navbar-item\" href='./explorar.php'>
         Explorar
      </a>
      
      <a class=\"navbar-item\" href='../cerrarSesion.php'>
         Cerrar Sesión
      </a>
    </div>
  </div>
</nav>";
            }
        }else{
            if ($ruta == "/"){

            }else{

            }
        }

    }

    function getUser($id){
        $user = array();
        $con = conectarServer();
        $consulta = "SELECT * from usuario WHERE id = '$id'";
        $data = mysqli_query($con,$consulta);
        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        while (!is_null($row)){
            $user["id"] = $row["id"];
            $user["nombre_usuario"] = $row["nombre_usuario"];
            $user["img"] = $row["img"];
            $user["nivel"] = $row["nivel"];
            $user["biografia"] = $row["biografia"];
            $user["email"] = $row["email"];
            $user["pass"] = $row["pass"];
            $user["rol"] = $row["rol"];
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        }
        return $user;
    }

    /**
     * [comprobarNickValido Comprueba si un nick existe ya en la base de datos para que no se repita]
     * @param  [string] $nick [El nick a comprobar]
     * @return [boolean]       [true si es válido y false si no lo es]
     */
    function checkUser($user){
        $conector = conectarServer();
        $consulta = "SELECT nombre_usuario from usuario;";
        $datos = mysqli_query($conector,$consulta);
        $resultado = mysqli_fetch_array($datos,MYSQLI_ASSOC);
        while(!is_null($resultado)){
            if($resultado["nombre_usuario"]==$user){
                mysqli_close($conector);
                return false;
            }else{
                $resultado = mysqli_fetch_array($datos,MYSQLI_ASSOC);
            }
        }
        mysqli_close($conector);
        return true;
    }

    function renderEventRules($rulesArray){
        echo "<ul>";
        foreach ($rulesArray as $rule){
            echo "<li>$rule</li>";
        }
        echo "</ul>";
    }

    function checkVoteUser($userId,$eventId){
        $votes = null;
        $conector = conectarServer();
        $consulta = "SELECT COUNT(*) as votes from vota v,trabajo t,evento e where v.id_trabajo = t.id 
        AND t.id_evento = e.id AND v.id_usuario=$userId AND e.id = $eventId;";
        $data = mysqli_query($conector,$consulta);
        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        while (!is_null($row)){
            $votes = $row["votes"];
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        }
        return $votes;
    }

    function renderFooter($ruta,$rol){
        if ($rol == "users"){
            if ($ruta == "/"){
                echo "<footer class=\"footer is-shutter-primary\">
  <div class=\"content has-text-centered\">
      <div class='columns'>
          <div class='column'>
            <img src='img/nombre.png'>
            <p>
              <strong>Shutter</strong> es una red social creada por <a href='https://www.github.com/silvericarus'>Alberto González Rosa</a> en 2019. 
              Para cualquier consulta, por favor envía un correo electrónico a <a href='mailto:ayuda@shutter.com'>ayuda@shutter.com</a>.
            </p>
          </div>
          <div class='column'>
            <h4>Nuestra Misión</h4>
            <p>
                <i>
                    Proveer a fotógrafos profesionales y aficionados de un punto de encuentro donde ejercer su afición
                    de una forma beneficiosa, permitiendo la construcción de una comunidad abierta a las preferencias
                    de cada persona, donde todos puedan encontrar un lugar y el reto de los eventos les mantenga siempre
                    con una gran motivación por su pasión.
                </i>
            </p>
            <img src='img/made-with-bulma.png'>
          </div>
      </div>
  </div>
</footer>";
            }else{
                echo "<footer class=\"footer is-shutter-primary\">
  <div class=\"content has-text-centered\">
      <div class='columns'>
          <div class='column'>
            <img src='../../img/nombre.png'>
            <p>
              <strong>Shutter</strong> es una red social creada por <a href='https://www.github.com/silvericarus'>Alberto González Rosa</a> en 2019. 
              Para cualquier consulta, por favor envía un correo electrónico a <a href='mailto:ayuda@shutter.com'>ayuda@shutter.com</a>.
            </p>
          </div>
          <div class='column'>
            <h4>Nuestra Misión</h4>
            <p>
                <i>
                    Proveer a fotógrafos profesionales y aficionados de un punto de encuentro donde ejercer su afición
                    de una forma beneficiosa, permitiendo la construcción de una comunidad abierta a las preferencias
                    de cada persona, donde todos puedan encontrar un lugar y el reto de los eventos les mantenga siempre
                    con una gran motivación por su pasión.
                </i>
            </p>
            <img src='../../img/made-with-bulma.png'>
          </div>
      </div>
  </div>
</footer>";
            }
        }else{
            if ($ruta == "/"){

            }else{

            }
        }
    }
?>
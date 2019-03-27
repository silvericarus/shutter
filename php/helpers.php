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

      <a class=\"navbar-item\">
        Perfil
      </a>

      <a class=\"navbar-item\">
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
    <a class=\"navbar-item\" href=\"./users/dashboard.php\">
      <img src=\"../img/nombre.png\" width=\"112\" height=\"26\">
    </a>

    <a role=\"button\" class=\"navbar-burger burger\" aria-label=\"menu\" aria-expanded=\"false\" data-target=\"navbarBasicExample\">
      <span aria-hidden=\"true\"></span>
      <span aria-hidden=\"true\"></span>
      <span aria-hidden=\"true\"></span>
    </a>
  </div>

  <div id=\"navbarBasicExample\" class=\"navbar-menu\">
    <div class=\"navbar-start\">

      <a class=\"navbar-item\">
        Perfil
      </a>

      <a class=\"navbar-item\">
         Explorar
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
?>
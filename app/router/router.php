<?php

include_once "./app/controller/user.php";

class Router
{

    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function router()
    {

        if (isset($_GET["mode"])) {
            if (isset($_SESSION["user_id"])) {

                switch ($_GET["mode"]) {

                    case "cerrarSesion":
                        session_destroy();
                        header("Location:index.php");
                        break;
                    
                    case "restablecer-clave":
                        $this->user->restablecerClave($_GET['id']);
                        break;

                    case "buscar":
                     echo $_GET["producto"];
                        break;

                    default:
                        header("Location:index.php");
                        break;
                }
            } else if($_GET["mode"] == "iniciarSesion"){
                    $this->user->inicioSesion();
            }else if($_GET["mode"] == "recuperarClave"){
                    $this->user->vistaRecuperarClave();
            }else if ($_GET["mode"] =="restablecer-clave"){
                    $this->user->restablecerClave($_GET['id']);

            }else if ($_GET["mode"] =="buscar"){
                    echo $_GET["producto"];

            }else{
                 header("Location:index.php");
            }

            
        } else if (isset($_POST["mode"])) {
            switch ($_POST["mode"]) {
                case "login":
                    $this->user->login($_POST["username"], $_POST["password"]);
                    break;
                case "registrar-usuario":
                    $this->user->registrarUsuario($_POST);
                    break;

                case "recuperar-clave":
                    $this->user->recuperarClave($_POST['email']);
                    break;
                 case "cambiar-clave":
                    $this->user->cambiarClave($_POST['password'],$_POST['id']);
                    break;

                default:
                    header("Location:index.php");
                    break;
            }
        } else {
            $this->user->index();
        }
    }


}

?>
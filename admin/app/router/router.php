<?php

include_once "./app/controller/user.php";
include_once "./app/controller/producto.php";

class Router
{

    private $user;
    private $producto;

    public function __construct()
    {
        $this->user = new User();
        $this->producto = new Producto();

    }

    public function router(){

        if(isset($_GET["mode"])) {
              switch ($_GET["mode"]) {
                    case "iniciarSesion":
                       $this->user->inicioSesion();
                        break;
                    case "cerrarSesion":
                        session_destroy();
                        header("Location:index.php");
                        break;

                    case "agregar-producto":
                      if(isset($_SESSION["user_id"])){
                          $this->producto->agregarProducto();
                      }else{
                          $this->user->inicioSesion();
                      }
                      break;
                                            
                                      
                default:
                      header("Location:index.php");
                      break;
        }
            } else if(isset($_POST["mode"])) {
                  switch ($_POST["mode"]) {
                    case "login":
                       $this->user->login($_POST["username"], $_POST["password"]);
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
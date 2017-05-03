<?php

include_once "./app/controller/user.php";
include_once "./app/controller/producto.php";
include_once "./app/controller/categoria.php";
class Router
{

    private $user;
    private $producto;
    private $categoria;

    public function __construct()
    {
        $this->user = new User();
        $this->producto = new Producto();
        $this->categoria=new Categoria();
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
                          $this->producto->cargarAgregarProducto();
                      }else{
                          $this->user->inicioSesion();
                      }
                      break;

                      case "consultar-productos":
                        if(isset($_SESSION["user_id"])){
                            $this->producto->mostrarTablaProductos();
                        }else{
                            $this->user->inicioSesion();
                        }
                        break;

                      case "editar-producto":
                        if(isset($_SESSION["user_id"]) && isset($_GET['id'])){
                            $this->producto->cargarEditarProducto($_GET['id']);
                        }else{
                            $this->user->inicioSesion();
                        }
                        break;

                     case "consultar-categorias":
                        if(isset($_SESSION["user_id"])){
                                $this->categoria->listarCategorias();
                        }else{
                            $this->user->inicioSesion();
                            }
                        break;

                     case "agregar-categoria":
                        if(isset($_SESSION["user_id"])){
                            $this->categoria->cargarAgregarCategoria();
                        }else{
                            $this->user->inicioSesion();
                            }
                        break;

                    case "editar-categoria":
                        if(isset($_SESSION["user_id"])){
                            $this->categoria-> cargarEditarCategoria($_GET['id']);
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

                    case "agregar-producto":
                      $this->producto->agregarProducto($_POST);
                      break;

                    case "eliminar-producto":
                        if(isset($_SESSION["user_id"])){
                            $this->producto->eliminarProducto($_POST['id']);
                        }else{
                            $this->user->inicioSesion();
                            }
                     break;

                     case "editar-producto":
                      $this->producto->editarProducto($_POST);
                      break;

                      case "eliminar-categoria":
                      $this->categoria->eliminarCategoria($_POST['id']);
                      break;

                      case "agregar-categoria":
                      $this->categoria->agregarCategoria($_POST['nombre']);
                      break;

                      case "editar-categoria":
                      $this->categoria->editarCategoria($_POST['id'],$_POST['nombre']);
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
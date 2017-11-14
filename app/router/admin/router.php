<?php

include_once "./app/controller/admin/user.php";
include_once "./app/controller/admin/producto.php";
include_once "./app/controller/admin/categoria.php";
include_once "./app/controller/admin/venta.php";
include_once "./app/controller/admin/estadistica.php";

class Router{

    private $user;
    private $producto;
    private $categoria;
    private $venta;
    private $estadistica;

    public function __construct()
    {
        $this->user = new User();
        $this->producto = new Producto();
        $this->categoria = new Categoria();
        $this->venta = new Venta();  
        $this->estadistica=new Estadistica();
    }

    public function router()
    {

        if (isset($_GET["mode"])) {
            if (isset($_SESSION["admin_id"])) {

                switch ($_GET["mode"]) {

                    case "cerrarSesion":
                        session_destroy();
                        header("Location:admin.php");
                        break;

                    case "agregar-producto":
                        $this->producto->cargarAgregarProducto();
                        break;

                    case "consultar-productos":
                        $this->producto->mostrarTablaProductos();
                        break;

                    case "editar-producto":
                        $this->producto->cargarEditarProducto($_GET['id']);
                        break;

                    case "consultar-categorias":
                        $this->categoria->listarCategorias();
                        break;

                    case "agregar-categoria":
                        $this->categoria->cargarAgregarCategoria();
                        break;

                    case "editar-categoria":
                        $this->categoria->cargarEditarCategoria($_GET['id']);
                        break;

                    case "ventas-realizadas":
                        $this->venta->cargarVistaVentasRealizadas();
                        break;

                     case "ventas-progreso":
                        $this->venta->cargarVistaVentasProgreso();
                        break;

                    case "facturas":
                        $this->venta->cargarVistaFactura();
                        break;
                    
                    case "restablecer-clave":
                        $this->user->restablecerClave($_GET['id']);
                        break;
                        
                    case "estadisticas":
                        $this->estadistica->cargarEstadisticas();
                        break;

                    default:
                        header("Location:admin.php");
                        break;
                }
            } else if($_GET["mode"] == "iniciarSesion"){
                    $this->user->inicioSesion();
            }else if($_GET["mode"] == "recuperarClave"){
                    $this->user->vistaRecuperarClave();
            }else if ($_GET["mode"] =="restablecer-clave"){
                    $this->user->restablecerClave($_GET['id']);

            }else{
                 header("Location:admin.php");
            }
        } else if (isset($_POST["mode"])) {
            switch ($_POST["mode"]) {
                case "login":
                    $this->user->login($_POST["username"], $_POST["password"]);
                    break;

                case "agregar-producto":
                    $this->producto->agregarProducto($_POST);
                    break;

                case "eliminar-producto":
                        $this->producto->eliminarProducto($_POST['id']);
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
                    $this->categoria->editarCategoria($_POST['id'], $_POST['nombre']);
                    break;

                case "eliminar-pedido":
                    $this->venta->eliminarPedido($_POST['id']);
                    break;

                case "ver-detalle-pedido":
                    $this->venta->cargarVistaDetallePedido($_POST['id']);
                    break;

                case "recuperar-clave":
                    $this->user->recuperarClave($_POST['email']);
                    break;
                 case "cambiar-clave":
                    $this->user->cambiarClave($_POST['password'],$_POST['id']);
                    break;
                case "ver-comprobante":
                    $this->venta->verComprobante($_POST['id']);
                    break;

                case "validar-comprobante":
                    $this->venta->validarComprobante($_POST['accion'],$_POST['id']);
                    break;
                
                default:
                    header("Location:admin.php");
                    break;
            }
        } else {
            $this->user->index();
        }
    }


}

?>
<?php
include_once "./app/controller/user/user.php";
include_once "./app/controller/user/compra.php";
include_once "./app/controller/user/producto.php";
include_once "./app/controller/user/carrito.php";
include_once "./app/controller/user/estadistica.php";

class Router{

    private $user;
    private $compra;
    private $producto;
    private $carrito;
    private $estadistica;

    public function __construct(){
        $this->user = new User();
        $this->compra=new Compra();
        $this->producto=new Producto();
        $this->carrito=new Carrito();
        $this->estadistica=new Estadistica();
    }

    public function router(){

        if (isset($_GET["mode"])) {
            if (isset($_SESSION["user_id"])) {

                switch ($_GET["mode"]) {

                    case "cerrarSesion":
                        session_destroy();
                        header("Location:index.php");
                        break;
                    
                    case "restablecer-clave":
                        if(isset($_GET['id']))
                        $this->user->restablecerClave($_GET['id']);
                        else
                        $this->user->cargarError404();       
                        break;

                    case "buscar":
                        if(isset($_GET["producto"]))
                        $this->producto->cargarVistaBusquedaProducto($_GET["producto"]);
                        else
                        $this->user->cargarError404();
                        break;
                    case "mis-compras":
                        $this->compra->cargarMisCompras();
                        break;
                    
                    case "ver-producto":
                        if(isset($_GET['id']))
                        $this->producto->cargarVistaDetalleProducto($_GET['id']);
                        else
                        $this->user->cargarError404();
                        break;

                    case "productos-categoria":
                         if(isset($_GET['id']))
                        $this->producto->cargarVistaProductosCategoria($_GET['id']);
                        else
                        $this->user->cargarError404();
                        break;

                    case "cambiar-clave":
                        $this->user-> cargarVistaCambiarClave();
                        break;

                    case "carrito-de-compras":
                        $this->carrito-> cargarCarritoDeCompras();
                        break;
                    case "contactanos":
                        $this->user->cargarContactanos();
                        break;

                    case "estadisticas":
                        $this->estadistica->cargarEstadisticas();
                        break;
                    
                    
                    default:
                        $this->user->cargarError404();
                        break;
                }
            } else if($_GET["mode"] == "iniciarSesion"){
                    $this->user->inicioSesion();
            }else if($_GET["mode"] == "recuperarClave"){
                    $this->user->vistaRecuperarClave();
            }else if ($_GET["mode"] =="restablecer-clave"){
                    if(isset($_GET['id']))
                        $this->user->restablecerClave($_GET['id']);
                     else
                        $this->user->cargarError404();

            }else if ($_GET["mode"] =="buscar"){
                 if(isset($_GET['producto']))
                   $this->producto->cargarVistaBusquedaProducto($_GET["producto"]);
                 else
                   $this->user->cargarError404();
            }else if ($_GET["mode"] =="ver-producto"){
                if(isset($_GET['id']))
                   $this->producto->cargarVistaDetalleProducto($_GET['id']);
                   else
                   $this->user->cargarError404();

            }else if ($_GET["mode"] =="productos-categoria"){
                if(isset($_GET['id']))
                   $this->producto->cargarVistaProductosCategoria($_GET['id']);
                   else
                   $this->user->cargarError404();

            }else if ($_GET["mode"] =="contactanos"){
                   $this->user->cargarContactanos();
            }else{
                 $this->user->cargarError404();
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

                case "ver-detalle-pedido":
                        $this->compra->verDetallePedido($_POST['id']);
                        break;

                case "subir-comprobante-pago":
                        $this->compra->subirComprobantePago($_POST['id_pedido']);
                        break;

                case "cargar-productos-categoria":
                        $this->producto->cargarProductosPagina($_POST['id_categoria'], $_POST['pagina'], "categoria");
                        break;

                case "cargar-productos-busqueda":
                        $this->producto->cargarProductosPagina($_POST['producto'], $_POST['pagina'], "busqueda");
                        break;

                case "cambiar-clave-user":
                        $this->user->cambiarClaveUser($_POST['password-vieja'], $_POST['password-nueva']);
                        break;

                case "agregar-al-carrito":
                        $this->carrito->agregarProducto($_POST['id_producto'], $_POST['cantidad-productos']);                              
                         header("Location:index.php?mode=carrito-de-compras");
                        break;

                case "eliminar-producto-carrito":
                        $this->carrito->eliminarProducto($_POST['id']);
                        break;

                case "vaciar-carrito":
                        $this->carrito->vaciarCarrito();
                        break;
                case "realizar-compra":
                        $this->carrito->realizarCompra();
                        break;

                case "enviar-mensaje-tienda":
                        $this->user->enviarMensajeTienda($_POST['nombre'],$_POST['email'],$_POST['asunto'],$_POST['mensaje']);
                        break;

                case "modificar-cant-productos-carrito":
                        $this->carrito->agregarProducto($_POST['id_producto'], $_POST['cantidad-productos']);
                        break;
                
                case "abrir-notificacion":
                        $this->user->abrirNotificacion();
                        break;
                
                default:
                    $this->user->cargarError404();
                    break;
            }
        } else {
            $this->user->index();
        }
    }


}

?>
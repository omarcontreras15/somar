<?php

include_once "./app/controller/controller.php";
include_once "./app/model/user/carrito.php";
include_once "./app/model/producto.php";
include_once "./app/model/user/compra.php";
include_once "./app/model/user.php";

class Carrito extends Controller {
    
    private $carritoModel;
    private $productoModel;
    private $compraModel;
    private $userModel;
    private $view;
    
    
    public function __construct() {
        $this->carritoModel = new CarritoModel();
        $this->productoModel=new ProductoModel();
        $this->compraModel=new compraModel();
        $this->userModel=new userModel();
        $this->view = $this->getTemplate("./app/views/user/index.html");
    }
    
    public function cargarCarritoDeCompras(){
        $nick=$_SESSION['user_id'];
        $contenido= $this->getTemplate("./app/views/user/carrito/carrito-de-compras.html");
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Carrito De Compras");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);
    }
    
    public function agregarProducto($id_producto, $cant_productos){
        $nick=$_SESSION['user_id'];
        $producto= $this->productoModel->consultarProducto($id_producto);
        $producto=$producto['infoP']['cant_disponibles'];
        
        //verificamos que la cantidad de productos a agregar al carrito de compras sea inferior o igual al stock
        if(($producto-$cant_productos)>=0){
            $this->carritoModel->agregarProducto($nick, $id_producto, $cant_productos);
        }
        
    }
    
    public function cargarProductosCarrito($nick){
        $array=$this->carritoModel->cargarProductosCarrito($nick);
        $sizeArray = sizeof($array);
        $tablaCarrito = "";
        $elementotabla = $this->getTemplate("./app/views/user/carrito/components/tabla-carrito-compras.html");
        if($sizeArray>0){
            foreach ($array as $element){
                $temp = $elementotabla;
                $temp= $this->renderView($temp, "{{nombre_producto}}", $element['nombre_producto']);
                $temp= $this->renderView($temp, "{{referencia}}", $element['referencia']);
                $temp= $this->renderView($temp, "{{precio}}", $element['precio']);
                $temp= $this->renderView($temp, "{{precio_total}}", $element['total']);
                $temp= $this->renderView($temp, "{{id_producto}}", $element['id_producto']);
                $temp= $this->renderView($temp, "{{cantidad}}", $element['cantidad']);
                $temp= $this->renderView($temp, "{{url_img1}}", $element['url_img1']);
                $tablaCarrito .= $temp;
            }
        }else{
            $tablaCarrito=false;
        }
        
        return $tablaCarrito;
    }
    
    public function eliminarProducto($id_producto){
        $nick=$_SESSION['user_id'];
        $this->carritoModel->eliminarProducto($nick, $id_producto);
        echo $this->cargarProductosCarrito($nick);
    }
    
    public function vaciarCarrito(){
        $nick=$_SESSION['user_id'];
        $this->carritoModel->vaciarCarrito($nick);
        echo $this->cargarProductosCarrito($nick);
    }
    
    //esta  funcion se encargara de realizar el proceso de compra
    public function realizarCompra(){
        $nick=$_SESSION['user_id'];
        $productosFueraStock="";
        
        //cargamos todos los productos del carrito los cuales se convertiran en el pedido
        $productosCarrito=$this->carritoModel->cargarProductosCarrito($nick);
        $sizeArray = sizeof($productosCarrito);
        
        foreach($productosCarrito as $element){
            if(($element["cant_disponibles"]-$element['cantidad'])<0){
                $productosFueraStock.=$element['nombre_producto'].": ".$element['cant_disponibles']." producto(s) disponible(s) solamente. <br>";
            }
           
        }
        
        // si productos fueraDeStock sigue vacia significa que todos los productos a insertar en el pedido se encuentran disponibles
        $id_pedido=0;
        if($productosFueraStock==""){
            //creamos un registro en la tabla pedido
            $ver=$this->carritoModel->realizarCompra($nick);
            if($ver){
                //obtenemos el ultimo id de los pedidos registrados
                $id_pedido=$this->carritoModel->obtenerUltimoPedido($nick);
                
                foreach($productosCarrito as $element){
                 $this->carritoModel->insertarDetallePedido($id_pedido, $element['id_producto'],$element['cantidad']);   
                }
            }else{
                return false;
            }
            

        }
        echo $productosFueraStock;
        //enviamos el email con el detalle de la compra
        $this->enviarDetallePedido($id_pedido);
    }

    private function enviarDetallePedido($id_pedido){
        $ventana = $this->getTemplate("./app/views/factura/email/factura.html");
        $datosCliente=$this->compraModel->obtenerDatosUsuario($id_pedido);
        foreach($datosCliente as $key => $value){
            $ventana=$this->renderView($ventana,"{{".$key."}}", $value);
        }
       
       $datosDetalleP=$this->compraModel->obtenerDetalleFactura($id_pedido);
        foreach($datosDetalleP as $key => $value){
            $ventana=$this->renderView($ventana,"{{".$key."}}", $value);
        }

        $array=$this->compraModel->listarItemsPedido($id_pedido);
        $sizeArray = sizeof($array);
        $tablaVentas = "";
        $elementotabla = $this->getTemplate("./app/views/factura/email/body-tabla-factura.html");
        if($sizeArray>0){
            foreach ($array as $element){
                $temp = $elementotabla;
                foreach ($element as $key=>$value){
                     $temp=$this->renderView($temp,"{{".$key."}}", $value);
                }
                $tablaVentas .= $temp;
            }
        }
        $ventana = $this->renderView($ventana, "{{OPTION}}", $tablaVentas);
        $ventana = $this->renderView($ventana, "{{URL_SERVER}}", $_SERVER['HTTP_HOST']);

        //enviar correo con la factura de la compra
        $email=$this->userModel->consultarEmail($_SESSION["user_id"]);
        $asunto="Detalle de compra #".$id_pedido;
        $this->enviarEmail($email,$asunto, $ventana);
    }
}

?>
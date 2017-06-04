<?php

include_once "./app/controller/controller.php";
include_once "./app/model/user/carrito.php";
include_once "./app/model/producto.php";

class Carrito extends Controller {

    private $carritoModel;
    private $productoModel;
    private $view;


    public function __construct() {
        $this->carritoModel = new CarritoModel();
        $this->productoModel=new ProductoModel();
        $this->view = $this->getTemplate("./app/views/user/index.html");
    }

    public function cargarCarritoDeCompras(){
        $nick=$_SESSION['user_id'];
        $contenido= $this->getTemplate("./app/views/user/carrito/carrito-de-compras.html");
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Carrito De Compras");

        $htmlTabla=$this->cargarProductosCarrito($nick);
        $contenido = $this->renderView($contenido, "{{TABLA_CARRITO}}", $htmlTabla);
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

        header("Location:index.php?mode=carrito-de-compras");

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
            $tablaCarrito="<tr><td colspan='5'><h3 class='text-center'>No hay productos en el carrito.</h3><td><tr>";
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

    public function realizarCompra(){
      $nick=$_SESSION['user_id'];
    }
}

 ?>
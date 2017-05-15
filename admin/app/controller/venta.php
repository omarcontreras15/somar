<?php
include_once  "./app/controller/controller.php";
include_once  "./app/model/venta.php";

class Venta extends Controller{
    
    private $view;
    private $ventaModel;
    
    public function __construct(){
        $this->ventaModel=new VentaModel();
        $this->view=$this->getTemplate("./app/views/index.html");
    }

    public function cargarVistaVentasRealizadas(){
         //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu=$this->cargarMenuBar();
        $ventana = $this->getTemplate("./app/views/ventas/ventasRealizadas.html");
        
        $this->view = $this->renderView($this->view, "{{TITULO}}","Ventas Realizadas");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        
        $array=$this->ventaModel->listarVentasRelizadas();
        $sizeArray = sizeof($array);
        $tablaVentas = "";
        $elementotabla = $this->getTemplate("./app/views/ventas/components/body-tabla-ventas-realizadas.html");
        if($sizeArray>0){
            foreach ($array as $element){
                $temp = $elementotabla;
                $temp= $this->renderView($temp, "{{ID}}", $element['id_pedido']);
                $temp= $this->renderView($temp, "{{USUARIO}}", $element['nick_usuario']);
                $temp= $this->renderView($temp, "{{FECHA_PEDIDO}}", $element['fecha_pedido']);
                $temp= $this->renderView($temp, "{{VALOR_PEDIDO}}", $element['valor_pedido']);
                $tablaVentas .= $temp;
            }
        }
        $ventana = $this->renderView($ventana, "{{OPTION}}", $tablaVentas);
        $ventana = $this->renderView($ventana, "{{TOTAL_PEDIDOS}}", $sizeArray);   
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $ventana);
        $this->showView($this->view);
    }

    public function eliminarPedido($id){
    $this->ventaModel->eliminarPedido($id);
    }
}
?>
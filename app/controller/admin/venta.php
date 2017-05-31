<?php
include_once  "./app/controller/controller.php";
include_once  "./app/model/admin/venta.php";

class Venta extends Controller{
    
    private $view;
    private $ventaModel;
    
    public function __construct(){
        $this->ventaModel=new VentaModel();
        $this->view=$this->getTemplate("./app/views/admin/index.html");
    }

    public function cargarVistaVentasRealizadas(){
         //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu=$this->cargarMenuBarAdmin();
        $ventana = $this->getTemplate("./app/views/admin/ventas/ventasRealizadas.html");
        
        $this->view = $this->renderView($this->view, "{{TITULO}}","Ventas Realizadas");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        
        $array=$this->ventaModel->listarVentas("realizadas");
        $sizeArray = sizeof($array);
        $tablaVentas = "";
        $elementotabla = $this->getTemplate("./app/views/admin/ventas/components/body-tabla-ventas-realizadas.html");
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

     public function cargarVistaVentasProgreso(){
         //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu=$this->cargarMenuBarAdmin();
        $ventana = $this->getTemplate("./app/views/admin/ventas/ventasProgreso.html");
        
        $this->view = $this->renderView($this->view, "{{TITULO}}","Ventas En Progreso");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        
        $array=$this->ventaModel->listarVentas("progreso");
        $sizeArray = sizeof($array);
        $tablaVentas = "";
        $elementotabla = $this->getTemplate("./app/views/admin/ventas/components/body-tabla-ventas-progreso.html");
        if($sizeArray>0){
            foreach ($array as $element){
                $temp = $elementotabla;
                $id=$element['id_pedido'];
                $temp= $this->renderView($temp, "{{ID}}", $id);
                $temp= $this->renderView($temp, "{{USUARIO}}", $element['nick_usuario']);
                $temp= $this->renderView($temp, "{{FECHA_PEDIDO}}", $element['fecha_pedido']);
                $temp= $this->renderView($temp, "{{VALOR_PEDIDO}}", $element['valor_pedido']);
                if($element['url_comprobante_pago']==""){
                $temp= $this->renderView($temp, "{{ESTADO}}","no-progreso");
                $temp= $this->renderView($temp, "{{CHECK_COMPROBANTE}}","");
                }else{
                $temp= $this->renderView($temp, "{{ESTADO}}","aprobado");
                 $temp= $this->renderView($temp, "{{CHECK_COMPROBANTE}}","<li><a href='#'  onclick='verComprobante($id)'  data-toggle='modal' data-target='#ventanaComprobante'><img src='public/images/comprobar.png'> Chequear comprobante</a></li>");
                }
                $tablaVentas .= $temp;
            }
        }
        $ventana = $this->renderView($ventana, "{{OPTION}}", $tablaVentas);
        $ventana = $this->renderView($ventana, "{{TOTAL_PEDIDOS}}", $sizeArray);   
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $ventana);
        $this->showView($this->view);
    }

public function cargarVistaDetallePedido($id_pedido){
        $ventana = $this->getTemplate("./app/views/admin/ventas/factura/factura.html");
        $datosCliente=$this->ventaModel->obtenerDatosUsuario($id_pedido);
        foreach($datosCliente as $key => $value){
            $ventana=$this->renderView($ventana,"{{".$key."}}", $value);
        }
       
       $datosDetalleP=$this->ventaModel->obtenerDetalleFactura($id_pedido);
        foreach($datosDetalleP as $key => $value){
            $ventana=$this->renderView($ventana,"{{".$key."}}", $value);
        }

        $array=$this->ventaModel->listarItemsPedido($id_pedido);
        $sizeArray = sizeof($array);
        $tablaVentas = "";
        $elementotabla = $this->getTemplate("./app/views/admin/ventas/factura/body-tabla-factura.html");
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
        
        $this->showView($ventana);
    }

    public function eliminarPedido($id){
    $this->ventaModel->eliminarPedido($id);
    }

    public function verComprobante($id){
    $row=$this->ventaModel->obtenerDetalleFactura($id);
    echo $row['url_comprobante_pago'];
    }

    public function validarComprobante($accion, $id){
    
    if($accion=="check"){
        echo "hola";
        $this->ventaModel->cambiarEstadoPedido($id);
    }else{
       $url=$this->ventaModel->borrarComprobantePago($id);
       $this->eliminarArchivo($url);
    }
    }

    private function eliminarArchivo($url){
        //Eliminamos la imagen del evento ubicada en la carpeta upload
        try{
            if($url!="" || !isset($url) || $url!=null){
                unlink($url);
            }
        }catch(Exception $e){
            
        }
    }

}
?>
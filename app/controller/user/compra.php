<?php

include_once "./app/controller/controller.php";
include_once "./app/model/user/compra.php";

class Compra extends Controller{
    private $compraModel;
    private $view;


    public function __construct() {
        $this->compraModel = new compraModel();
        $this->view = $this->getTemplate("./app/views/user/index.html");
    }

    public function cargarMisCompras(){

        $vista= $this->getTemplate("./app/views/user/compras/compras-realizadas.html");
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Mis Compras");
        $nick=$_SESSION['user_id'];
        $array=$this->compraModel->cargarMisCompras($nick);
        $sizeArray = sizeof($array);
        $tablaVentas = "";
         $elementotabla = $this->getTemplate("./app/views/user/compras/components/body-tabla-compras-realizadas.html");
        if($sizeArray>0){
            foreach ($array as $element){
                $id_pedido= $element['id_pedido'];
                 $html="<li><a href='#'  onclick='ventanaSubirComprobante($id_pedido)'  data-toggle='modal' data-target='#ventanaComprobante'><img src='public/images/subir.png'> Subir comprobante</a></li>";
                $temp = $elementotabla;
                $temp= $this->renderView($temp, "{{ID}}",$id_pedido);
                if($element['status']=='aprobado'){
                    $temp= $this->renderView($temp, "{{ESTADO}}","aprobado");
                    $temp= $this->renderView($temp, "{{SUBIR_COMPROBANTE}}", "");
                }else if($element['status']=='progreso' && $element['url_comprobante_pago']!=''){
                    $temp= $this->renderView($temp, "{{ESTADO}}", "progreso");
                    $temp= $this->renderView($temp, "{{SUBIR_COMPROBANTE}}", $html);
                }else{
                    $temp= $this->renderView($temp, "{{ESTADO}}", "no-progreso");
                    $temp= $this->renderView($temp, "{{SUBIR_COMPROBANTE}}", $html);
                }
                $temp= $this->renderView($temp, "{{ESTADO}}", $element['status']);
                $temp= $this->renderView($temp, "{{FECHA_PEDIDO}}", $element['fecha_pedido']);
                $temp= $this->renderView($temp, "{{VALOR_PEDIDO}}", $element['valor_pedido']);
                $tablaVentas .= $temp;
            }
        }
        $vista = $this->renderView($vista, "{{OPTION}}", $tablaVentas);
        $vista = $this->renderView($vista, "{{TOTAL_PEDIDOS}}", $sizeArray);   
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $vista);
        $this->showView($this->view);
    }

    public function verDetallePedido($id_pedido){
        $ventana = $this->getTemplate("./app/views/factura/factura.html");
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
        $elementotabla = $this->getTemplate("./app/views/factura/body-tabla-factura.html");
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

    public function subirComprobantePago($id_pedido){
        $url_comprobante=$this->agregarArchivo('comprobante-pago');
        $this->compraModel->subirComprobante($id_pedido, $url_comprobante);
    }
}
?>
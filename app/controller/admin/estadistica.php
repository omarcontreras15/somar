<?php
include_once  "./app/controller/controller.php";
include_once  "./app/model/estadistica.php";

class Estadistica extends Controller{
    
    private $view;
    private $estadisticaModel;
    
    public function __construct(){
        $this->estadisticaModel=new estadisticaModel();
        $this->view=$this->getTemplate("./app/views/admin/index.html");
    }
    

    public function cargarEstadisticas(){
        //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu=$this->cargarMenuBarAdmin();
        $contenido= $this->getTemplate("./app/views/admin/estadisticas/estadisticas.html");
        $json_mas_visto=$this->estadisticaModel->obtnerProductosMasVistos();
        $contenido = $this->renderView($contenido, "{{MAS_VISTOS}}",$json_mas_visto);
        $json_mas_vendidos=$this->estadisticaModel->obtenerProdcutosMasVendidos();
        $contenido = $this->renderView($contenido, "{{MAS_VENDIDOS}}",$json_mas_vendidos);
        $json_ventas_realizadas=$this->estadisticaModel->obtenerVentas();
        $contenido = $this->renderView($contenido, "{{VENTAS_MES}}",$json_ventas_realizadas);

        $json_clientes=$this->estadisticaModel->obtenerMejoresClientes();
        $contenido = $this->renderView($contenido, "{{MEJORES_CLIENTES}}",$json_clientes);

        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Estadistica");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);
    }

}
?>
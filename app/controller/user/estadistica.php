<?php
include_once  "./app/controller/controller.php";
include_once  "./app/model/estadistica.php";

class Estadistica extends Controller{
    
    private $view;
    private $estadisticaModel;
    
    public function __construct(){
        $this->estadisticaModel=new estadisticaModel();
        $this->view=$this->getTemplate("./app/views/user/index.html");
    }
    

    public function cargarEstadisticas(){

        $contenido= $this->getTemplate("./app/views/user/estadisticas/estadisticas.html");
        $json_mas_visto=$this->estadisticaModel->obtnerProductosMasVistos();
        $contenido = $this->renderView($contenido, " {{MAS_VISTOS}}",$json_mas_visto);
        $json_mas_vendidos=$this->estadisticaModel->obtenerProdcutosMasVendidos();
        $contenido = $this->renderView($contenido, " {{MAS_VENDIDOS}}",$json_mas_vendidos);
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Estadistica");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);
    }

}
?>
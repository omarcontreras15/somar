<?php
include_once  "./app/controller/controller.php";
include_once  "./app/model/categoria.php";

class Categoria extends Controller{
    
    private $view;
    private $categoriaModel;
    
    public function __construct(){
        $this->categoriaModel=new CategoriaModel();
        $this->view=$this->getTemplate("./app/views/index.html");
    }

    public function listarCategorias(){

        $menu=null;
        $ventana = $this->getTemplate("./app/views/categoria/consultarCategoria/tabla-categoria.html");
        if((!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null)){
            
            $menu = $this->getTemplate("./app/views/components/menu-logout.html");
        }else{
            $menu = $this->getTemplate("./app/views/components/menu-login.html");
            
        }
        $this->view = $this->renderView($this->view, "{{TITULO}}","Consultar Categorias");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $ventana);
        
        $array=$this->categoriaModel->listarCategorias();
        $sizeArray = sizeof($array);
        $option = "";
        $elementotabla = $this->getTemplate("./app/views/categoria/consultarCategoria/components/body-tabla-categoria.html");
        if($sizeArray>0){
            foreach ($array as $element){
                $temp = $elementotabla;
                $temp= $this->renderView($temp, "{{ID}}", $element['id']);
                $temp= $this->renderView($temp, "{{NOMBRE_CATEGORIA}}", $element['nombre_categoria']);
                $temp= $this->renderView($temp, "{{CANT_PRODUCTOS}}", $element['cantidad']);
                $option .= $temp;
            }
        }
        $this->view = $this->renderView($this->view, "{{OPTION}}", $option);
        $this->showView($this->view);
    }

    public function eliminarCategoria($id){
        $this->categoriaModel->eliminarCategoria($id);
    }
}
    ?>
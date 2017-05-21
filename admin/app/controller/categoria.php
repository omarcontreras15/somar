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
        //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu=$this->cargarMenuBar();
        $ventana = $this->getTemplate("./app/views/categoria/consultarCategoria/administrarCategoria.html");
        $this->view = $this->renderView($this->view, "{{TITULO}}","Consultar Categorias");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);

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
        $ventana = $this->renderView($ventana, "{{OPTION}}", $option);
        $ventana = $this->renderView($ventana, "{{TOTAL_CATE}}", $sizeArray);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $ventana);
        $this->showView($this->view);
    }

    public function cargarAgregarCategoria(){
         //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu=$this->cargarMenuBar();
        $contenido = $this->getTemplate("./app/views/categoria/agregarCategoria/agregarCategoria.html");
        $this->view = $this->renderView($this->view, "{{TITULO}}","Agregar Categoria");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);
    }

    public function cargarEditarCategoria($id){
         //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu=$this->cargarMenuBar();
        $contenido = $this->getTemplate("./app/views/categoria/editarCategoria/editarCategoria.html");
        $nombre=$this->categoriaModel->buscarNombreCategoria($id);
        $contenido = $this->renderView($contenido, "{{ID}}",$id);
        $contenido = $this->renderView($contenido, "{{NOMBRE}}",$nombre);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Editar Categoria");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);
    }

    public function editarCategoria($id, $nombre){
        $resul=$this->categoriaModel->editarCategoria($id, $nombre);
        echo $resul;
    }

    public function agregarCategoria($nombre){
        $resul=$this->categoriaModel->agregarCategoria($nombre);
        echo $resul;
    }

    public function eliminarCategoria($id){
        $this->categoriaModel->eliminarCategoria($id);
    }
}
    ?>
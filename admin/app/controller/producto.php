<?php
include_once  "./app/controller/controller.php";
include_once  "./app/model/producto.php";

class producto extends Controller{
    
    private $view;
    private $productoModel;
    
    public function __construct(){
        $this->productoModel=new ProductoModel();
        $this->view=$this->getTemplate("./app/views/index.html");
    }
    
    public function agregarProducto(){
        $menu=null;
        $contenido = $this->getTemplate("./app/views/agregarProducto/agregarProducto.html");
        if((!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null)){
            
            $menu = $this->getTemplate("./app/views/components/menu-logout.html");
        }else{
            $menu = $this->getTemplate("./app/views/components/menu-login.html");
            
        }
        $array=$this->productoModel->listarCategorias();
        $sizeArray = sizeof($array);
        $option = "";
        $elementcheck = $this->getTemplate("./app/views/agregarProducto/components/checkbox-cate.html");
        if($sizeArray>0){
            foreach ($array as $element){
                $temp = $elementcheck;
                $temp= $this->renderView($temp, "{{NOM_CATEGORIA}}", $element['nombre_categoria']);
                $temp= $this->renderView($temp, "{{ID_CATEGORIA}}", $element['id_categoria']);
                $option .= $temp;
            }
        }
        $contenido = $this->renderView($contenido, "{{CHECKBOX}}",$option);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Agregar Producto");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);
    }
    
    public function mostrarTablaProductos(){
        $menu=null;
        $ventana = $this->getTemplate("./app/views/consultarProducto/tabla-producto.html");
        if((!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null)){
            
            $menu = $this->getTemplate("./app/views/components/menu-logout.html");
        }else{
            $menu = $this->getTemplate("./app/views/components/menu-login.html");
            
        }
        $this->view = $this->renderView($this->view, "{{TITULO}}","Consultar Productos");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $ventana);
        
        $array=$this->productoModel->listarProductos();
        $sizeArray = sizeof($array);
        $option = "";
        $elementotabla = $this->getTemplate("./app/views/consultarProducto/components/body-tabla-producto.html");
        if($sizeArray>0){
            foreach ($array as $element){
                $temp = $elementotabla;
                $temp= $this->renderView($temp, "{{ID}}", $element['id_producto']);
                $temp= $this->renderView($temp, "{{NOMBRE}}", $element['nombre_producto']);
                $temp= $this->renderView($temp, "{{CANT_DISPONIBLE}}", $element['cant_disponibles']);
                $temp= $this->renderView($temp, "{{PRECIO}}", $element['precio']);
                $temp= $this->renderView($temp, "{{URL_IMAGEN}}", $element['url_img1']);
                $option .= $temp;
            }
        }
        $this->view = $this->renderView($this->view, "{{OPTION}}", $option);
        $this->showView($this->view);
        
    }

    public function subirProducto($form){
      
*/
    }

    //metodo para mover un archivo traido del formulario a la carpeta upload
  private function agregarArchivo($nom_input_file){
    $urlArchivo="";
    if (is_uploaded_file($_FILES[$nom_input_file]['tmp_name'])){
        $nombreDirectorio = "public/upload/";
        $nombreFichero = $_FILES[$nom_input_file]['name'];
        //opcional
        $tipoArchivo=$_FILES[$nom_input_file]['type'];
        $extencionFichero= ".". substr(strrchr($tipoArchivo, "/"), 1);
        //
        //id unico de tiempo
        $idUnico=time();
        
        $urlArchivo = $nombreDirectorio . $idUnico . "_" . $nombreFichero;
        move_uploaded_file($_FILES[$nom_input_file]['tmp_name'], $urlArchivo);
        
            return $urlArchivo;
        }else{
            return false;
            }
    }
    
}
?>
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
    
    
    //Este metodo permite mostrar la vista del formulario para poder agregar productos nuevos a la tienda virtual
    public function cargarAgregarProducto(){
        $menu=null;
        $contenido = $this->getTemplate("./app/views/producto/agregarProducto/agregarProducto.html");
        if((!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null)){
            
            $menu = $this->getTemplate("./app/views/components/menu-logout.html");
        }else{
            $menu = $this->getTemplate("./app/views/components/menu-login.html");
            
        }
        $array=$this->productoModel->listarCategorias();
        $sizeArray = sizeof($array);
        $option = "";
        $elementcheck = $this->getTemplate("./app/views/producto/agregarProducto/components/checkbox-cate.html");
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
    
    //Este metodo permite mostrar la vista de editar producto
    public function cargarEditarProducto($id){
        $menu=null;
        $contenido = $this->getTemplate("./app/views/producto/editarProducto/editarProducto.html");
        if((!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null)){
            
            $menu = $this->getTemplate("./app/views/components/menu-logout.html");
        }else{
            $menu = $this->getTemplate("./app/views/components/menu-login.html");
            
        }
        //Llamamos al metodo consultarProducto de ProductoModel y le pasamos como parametro el id de un producto para
        $array=$this->productoModel->consultarProducto($id);
        $info=$array['infoP'];
        $categorias=$array['categorias'];
        //llamamos al metodo listar categorias en ProductoModel
        $array=$this->productoModel->listarCategorias();
        $option = "";
        $elementcheck = $this->getTemplate("./app/views/producto/editarProducto/components/checkbox-cate.html");
        
        //Aqui se lista todas las categorias del producto y les coloco check a las que esten asociadas al producto a editar
        foreach ($array as $element){
            $temp = $elementcheck;
            $temp= $this->renderView($temp, "{{NOM_CATEGORIA}}", $element['nombre_categoria']);
            $temp= $this->renderView($temp, "{{ID_CATEGORIA}}", $element['id_categoria']);
            if(in_array($element['id_categoria'], $categorias)){
                $temp= $this->renderView($temp, "{{checked}}","checked");
            }else{
                $temp= $this->renderView($temp, "{{checked}}","");
            }
            $option .= $temp;
        }
        
        //Aqui se llenan los input con los valores del producto obtenidos desde la base de datos
        foreach ($info as $key => $value) {
            $contenido = $this->renderView($contenido, "{{".$key."}}",$value);
        }
        //reemplazo los checkbox dentro del contenido del html editarProducto
        $contenido = $this->renderView($contenido, "{{CHECKBOX}}",$option);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Editar Producto");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);
    }
    
    public function editarProducto($form){
        $id=$form['id'];
        $arrayResul=array();
        $urlArray=array();
        $url_imagen1=$this->agregarArchivo("imagen1");
        $url_imagen2=$this->agregarArchivo("imagen2");
        $url_imagen3=$this->agregarArchivo("imagen3");
        $urls=$this->productoModel->consultarUrlsImgProductos($id);
        if($url_imagen1!=""){
            $this->eliminarArchivo($urls['url_img1']);
        }
        if($url_imagen2!=""){
            $this->eliminarArchivo($urls['url_img2']);
        }
        if($url_imagen3!=""){
            $this->eliminarArchivo($urls['url_img3']);
        }
        $resul=$this->productoModel->editarProducto($form,$id,$url_imagen1,$url_imagen2,$url_imagen3);
        if($resul<1){
            $this->eliminarArchivo($url_imagen1);
            $this->eliminarArchivo($url_imagen2);
            $this->eliminarArchivo($url_imagen3);
        }
        
        $urls=$this->productoModel->consultarUrlsImgProductos($id);
        $urlArray['imagen1']=$urls['url_img1'];
        $urlArray['imagen2']=$urls['url_img2'];
        $urlArray['imagen3']=$urls['url_img3'];
        
        $arrayResul['resul']=$resul;
        $arrayResul['url']=$urlArray;
        echo json_encode($arrayResul);
    }
    //este metodo permite listar y motsrar en una datatable todos los prodcutos que tiene registrados la tienda virtual
    public function mostrarTablaProductos(){
        $menu=null;
        $ventana = $this->getTemplate("./app/views/producto/consultarProducto/tabla-producto.html");
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
        $tablaProductos = "";
        $elementotabla = $this->getTemplate("./app/views/producto/consultarProducto/components/body-tabla-producto.html");
        if($sizeArray>0){
            foreach ($array as $element){
                $temp = $elementotabla;
                $temp= $this->renderView($temp, "{{ID}}", $element['id_producto']);
                $temp= $this->renderView($temp, "{{NOMBRE}}", $element['nombre_producto']);
                $temp= $this->renderView($temp, "{{CANT_DISPONIBLE}}", $element['cant_disponibles']);
                $temp= $this->renderView($temp, "{{PRECIO}}", $element['precio']);
                $temp= $this->renderView($temp, "{{URL_IMAGEN}}", $element['url_img1']);
                $tablaProductos .= $temp;
            }
        }
        $this->view = $this->renderView($this->view, "{{OPTION}}", $tablaProductos);
        $this->showView($this->view);
        
    }
    
    //este metodo permite agregar un producto a la tienda virtual
    public function agregarProducto($form){
        $url_imagen1=$this->agregarArchivo("imagen1");
        $url_imagen2=$this->agregarArchivo("imagen2");
        $url_imagen3=$this->agregarArchivo("imagen3");
        $resul=$this->productoModel->agregarProducto($form,$url_imagen1,$url_imagen2,$url_imagen3);
        if($resul<1){
            $this->eliminarArchivo($url_imagen1);
            $this->eliminarArchivo($url_imagen2);
            $this->eliminarArchivo($url_imagen3);
        }
        
        echo $resul;
    }
    
    public function eliminarProducto($id){
        $urls=$this->productoModel->eliminarProducto($id);
        $this->eliminarArchivo($urls['url_img1']);
        $this->eliminarArchivo($urls['url_img2']);
        $this->eliminarArchivo($urls['url_img3']);
        
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
            
            $urlArchivo = $nombreDirectorio .$nom_input_file."-".$idUnico.$extencionFichero;
            move_uploaded_file($_FILES[$nom_input_file]['tmp_name'], $urlArchivo);
            
            return $urlArchivo;
        }else{
            return false;
        }
    }
    
    private function eliminarArchivo($url){
        //Eliminamos la imagen del evento ubicada en la carpeta upload
        try{
            if($url!="" || !isset($url) || $url!=" "){
                unlink($url);
            }
        }catch(Exception $e){
            
        }
    }
    
}
?>
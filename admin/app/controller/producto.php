<?php
include_once  "./app/controller/controller.php";

class producto extends Controller{

private $view;

public function __construct(){
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
      $this->view = $this->renderView($this->view, "{{TITULO}}","Agregar Producto");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);        
}

}
?>
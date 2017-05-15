<?php
class Controller {
    public function getTemplate($route){
        return file_get_contents($route);
    }

    public function showView($view){
        echo $view;
    }

    public function renderView($ubicacion, $cadenaReemplazar, $reemplazo){
        return str_replace($cadenaReemplazar, $reemplazo, $ubicacion);
    }

    public function cargarMenuBar(){
        $menu=null;
        if((!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null)){
            
            $menu = $this->getTemplate("./app/views/components/menu-logout.html");
        }else{
            $menu = $this->getTemplate("./app/views/components/menu-login.html");
            
        }

        return $menu;
    }


}
?>
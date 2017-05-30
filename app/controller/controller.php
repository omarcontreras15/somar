<?php

require './admin/public/lib/PHPMailer/PHPMailerAutoload.php';
require './admin/app/model/categoria.php';

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
    
    
    private function cargarMenuBarFront1(){
        $menu=null;
        if($this->sesionIniciada()){
            $menu = $this->getTemplate("./app/views/components/menu1/menu-login.html");
        }else{
            $menu = $this->getTemplate("./app/views/components/menu1/menu-logout.html");
        }
        
        return $menu;

    }

    private function cargarMenuBarFront2(){
        $menu=null;
        if($this->sesionIniciada()){   
            $menu = $this->getTemplate("./app/views/components/menu2/menu-login.html");
        }else{    
            $menu = $this->getTemplate("./app/views/components/menu2/menu-logout.html");
        } 
        //cargamos las catogorias en el menu2   
        $menu= $this->renderView($menu, "{{MENU_CATEGORIA}}",$this->cargarCategoriasMenu());
        return $menu;
    }

    private function cargarCategoriasMenu(){
        $modelCategoria=new CategoriaModel();
        $htmlcategorias="";
        $array=$modelCategoria->listarCategorias();
        foreach($array as $element){
            $htmlcategorias.='<li><a href="index.php?mode=buscar-productos-categoria&id='.$element['id'].'">'.$element['nombre_categoria'].'</a></li>';
        }

        return $htmlcategorias;
    }

    public function cargarCategoriasMenuLeft(){
        $modelCategoria=new CategoriaModel();
        $htmlcategorias="";
        $array=$modelCategoria->listarCategorias();
        foreach($array as $element){
            $htmlcategorias.='<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a href="index.php?mode=buscar-productos-categoria&id='.$element['id'].'">'.$element['nombre_categoria'].'</a></h4></div></div>';
        }

        return $htmlcategorias;
    }

    public function cargarContenidoPlantilla($view){
         //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu1=$this->cargarMenuBarFront1();
        $menu2=$this->cargarMenuBarFront2();
        //carga los menus dependiendo si el usuario tiene o no sesion iniciada
        $view = $this->renderView($view, "{{MENU_BAR1}}", $menu1);
        $view = $this->renderView($view, "{{MENU_BAR2}}", $menu2);
        if($this->sesionIniciada()){
          $view = $this->renderView($view, "{{SALUDO_NICK}}","Bienvenido - ".$_SESSION["user_id"]);  
        }else{
          $view = $this->renderView($view, "{{SALUDO_NICK}}","");  
        }

        return $view;
    }

    public function sesionIniciada(){
        return !(!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null);
    }

    
    public function enviarEmail($email, $asunto, $contenido){
        $mail = new PHPMailer();
        $mail ->IsSmtp();
        $mail ->SMTPDebug = 2;
        $mail ->SMTPAuth = true;
        $mail ->SMTPSecure = 'ssl';
        $mail ->Host = "smtp.gmail.com";
        $mail ->Port = 465; // or 587
        $mail ->IsHTML(true);
        $mail ->Username = "somarufps@gmail.com";
        $mail ->Password = "tiendasomarufps01";
        $mail ->SetFrom("somarufps@gmail.com");
        $mail ->Subject = $asunto;
        $mail ->Body = $contenido;
        $mail ->AddAddress($email);
        //esta linea debe ir para que funcione correctamente con gmail y SSL
        $mail->SMTPOptions = array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
        );
        return $mail->Send();
    }
    
    
}
?>
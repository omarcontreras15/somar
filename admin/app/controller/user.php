<?php

include_once "./app/controller/controller.php";
include_once "./app/model/user.php";

class User extends Controller {

    private $userModel;
    private $view;


    public function __construct() {
        $this->userModel = new UserModel();
        $this->view = $this->getTemplate("./app/views/index.html");
    }

    public function index() {
         //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu=$this->cargarMenuBar();
        $inicio = $this->getTemplate("./app/views/inicio.html");
        
        $this->view = $this->renderView($this->view, "{{TITULO}}","SOMAR");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $inicio);
        $this->showView($this->view);            
    }

    public function inicioSesion() {
        $menu=null;
        $login = $this->getTemplate("./app/views/login.html");
        if((!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null)){
            $menu = $this->getTemplate("./app/views/components/menu-logout.html");
        }else{
            header("Location:index.php");
            return;
        }
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Iniciar Sesión");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $login);
        $this->showView($this->view);
    }

    public function login($user, $password) {
        $pass = sha1($password);
        $log = $this->userModel->login($user, $pass);
        if($log) {
           $this->index();
          
        } else {
            echo "<script> alert('La clave y el usuario no coinciden ');</script>";
            $this->inicioSesion();
        }
    }

    public function vistaRecuperarClave(){
        $menu=null;
        $login = $this->getTemplate("./app/views/recuperarClave/recuperar-clave.html");
        if((!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null)){
            $menu = $this->getTemplate("./app/views/components/menu-logout.html");
        }else{
            header("Location:index.php");
            return;
        }
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Recuperar contraseña");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $login);
        $this->showView($this->view);
    }

    public function recuperarClave($email){
        $rel=$this->userModel->recuperarClave($email);
        if($rel!=false){
        $this->enviarEmail($email, "prueba", "esto es una prueba");
        return true;
        }else{
        return false;
        }
        
    }

    public function restablecerClave($id_seguridad){
        $email=$this->userModel->consultarInfoIdSeguridad($id_seguridad);
        if($email!=""){
        $menu=null;
        $login = $this->getTemplate("./app/views/recuperarClave/cambiar-clave.html");
        if((!isset($_SESSION["user_id"]) || $_SESSION["user_id"]==null)){
            $menu = $this->getTemplate("./app/views/components/menu-logout.html");
        }else{
            header("Location:index.php");
            return;
        }
         $login = $this->renderView($login, "{{EMAIL}}",$email);
        $login = $this->renderView($login, "{{ID_SEGURIDAD}}",$id_seguridad);
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Cambiar Contraseña");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $login);
        $this->showView($this->view);
        }else{
            header("Location:index.php");
        }

    }


    public function cambiarClave($password, $id_seguridad){
        $rel= $this->userModel->cambiarClave($id_seguridad, $password);
        if($rel==1){
            return true;
        }else{
            return false;
        }
    }
}

?>
<?php

include_once "./app/controller/controller.php";
include_once "./app/model/user.php";
include_once "./app/model/producto.php";

class User extends Controller {
    
    private $userModel;
    private $view;
    
    
    public function __construct() {
        $this->userModel = new UserModel();
        $this->view = $this->getTemplate("./app/views/user/index.html");
    }
    
    public function index() {
        $inicio= $this->getTemplate("./app/views/user/inicio.html");
        //aca llamamos al metodo cargarCategoriasMenuLeft que se encargara de crear el html para las categorias
        //del menu que se encuentra en la parte izquierda de la pagina
        $inicio= $this->renderView($inicio, "{{CATEGORIAS_MENU_LEFT}}",$this->cargarCategoriasMenuLeft());
        //Llamamos al metodo mostrarProductosNuevos() que traera de la bd los productos registrados en la ultima semana
        //estos seran insertados en un widget de la pagina de inicio
        $inicio= $this->renderView($inicio, "{{PRODUCTOS_NUEVOS}}",$this-> mostrarProductosNuevos());
        //Llamamos al metodo mostrarProductosMasVendidos() que traera de la bd los productos mas vendidos por la tienda virtual
        $inicio= $this->renderView($inicio, "{{PRODUCTOS_MAS_VENDIDOS}}",$this-> mostrarProductosMasVendidos());
        
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","SOMAR");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $inicio);
        $this->showView($this->view);
    }
    
    private function mostrarProductosNuevos(){
        $productoModel= new ProductoModel();
        $array=$productoModel->listarProductosNuevo();
        $htmlProductos="";
        foreach ($array as $element) {
            $producto=$this->getTemplate("./app/views/user/components/sliderProducto/slider-producto.html");
            $producto = $this->renderView($producto, "{{PRECIO}}",$element['precio']);
             //calculamos el tamaño del string del nombre del producto si es superior a 30 se corta y rellena de puntos suspensivos 
              if(strlen($element['nombre_producto'])>30){
               $producto = $this->renderView($producto, "{{NOMBRE_PRODUCTO}}",substr($element['nombre_producto'],0,30)."...");
             }else{
                $producto = $this->renderView($producto, "{{NOMBRE_PRODUCTO}}",$element['nombre_producto']);
             } 
             //
            $producto = $this->renderView($producto, "{{ID_PRODUCTO}}",$element['id_producto']);
            $producto = $this->renderView($producto, "{{URL_IMG}}",$element['url_img1']);
            
            //Validamos que hayan producto disponibles para habilitar el boton de agregar al carrito
            if($this->sesionIniciadaUser() && $element['cant_disponibles']>0){
                $formProducto=$this->getTemplate("./app/views/user/components/sliderProducto/components/slider-form-carrito.html");
                $formProducto = $this->renderView($formProducto, "{{ID_PRODUCTO}}",$element['id_producto']);
                $formProducto = $this->renderView($formProducto, "{{CANT_DISPONIBLE}}",$element['cant_disponibles']);
                $producto = $this->renderView($producto, "{{FORM_CARRITO}}",$formProducto);
            }else if($element['cant_disponibles']>0){
                $producto = $this->renderView($producto, "{{FORM_CARRITO}}","<p>Stock: ".$element['cant_disponibles']."</p>");
            }else{
                $producto = $this->renderView($producto, "{{FORM_CARRITO}}","<p>Agotado</p>");
            }
            
            $htmlProductos.=$producto;
        }
        
        return $htmlProductos;
    }
    
    private function mostrarProductosMasVendidos(){
        $productoModel= new ProductoModel();
        $array=$productoModel->listarProductosMasVendido();
        $htmlProductos="";
        foreach ($array as $element) {
            $producto=$this->getTemplate("./app/views/user/components/sliderProducto/slider-producto.html");
            $producto = $this->renderView($producto, "{{PRECIO}}",$element['precio']);

            //calculamos el tamaño del string del nombre del producto si es superior a 30 se corta y rellena de puntos suspensivos 
            if(strlen($element['nombre_producto'])>30){
               $producto = $this->renderView($producto, "{{NOMBRE_PRODUCTO}}",substr($element['nombre_producto'],0,30)."...");
             }else{
                $producto = $this->renderView($producto, "{{NOMBRE_PRODUCTO}}",$element['nombre_producto']);
             } 
             //
            $producto = $this->renderView($producto, "{{ID_PRODUCTO}}",$element['id_producto']);
            $producto = $this->renderView($producto, "{{URL_IMG}}",$element['url_img1']);
            
            //Validamos que hayan producto disponibles para habilitar el boton de agregar al carrito
            if($this->sesionIniciadaUser() && $element['cant_disponibles']>0){
                $formProducto=$this->getTemplate("./app/views/user/components/sliderProducto/components/slider-form-carrito.html");
                $formProducto = $this->renderView($formProducto, "{{ID_PRODUCTO}}",$element['id_producto']);
                $formProducto = $this->renderView($formProducto, "{{CANT_DISPONIBLE}}",$element['cant_disponibles']);
                $producto = $this->renderView($producto, "{{FORM_CARRITO}}",$formProducto);
            }else if($element['cant_disponibles']>0){
                $producto = $this->renderView($producto, "{{FORM_CARRITO}}","<p>Stock: ".$element['cant_disponibles']."</p>");
            }else{
                $producto = $this->renderView($producto, "{{FORM_CARRITO}}","<p>Agotado</p>");
            }
            
            $htmlProductos.=$producto;
        }
        
        return $htmlProductos;
    }
    
    public function inicioSesion() {
        $login= $this->getTemplate("./app/views/user/login.html");
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Iniciar Sesión");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $login);
        $this->showView($this->view);
    }
    
    public function cargarError404(){
        $error404= $this->getTemplate("./app/views/user/404/404.html");
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Error 404!");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $error404);
        $this->showView($this->view);
    }
    
    public function cargarVistaCambiarClave(){
        $contenido= $this->getTemplate("./app/views/user/cambiarClave/cambiar-clave.html");
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Cambiar Contraseña");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);
    }
    
    public function login($user, $password) {
        $pass = sha1($password);
        $log = $this->userModel->loginUser($user, $pass);
        echo $log;
    }
    
    public function registrarUsuario($form){
        $log = $this->userModel->registrarUsuario($form);
        echo $log;
    }
    
    public function vistaRecuperarClave(){
        $vista= $this->getTemplate("./app/views/user/recuperarClave/recuperar-clave.html");
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Restablecer Contraseña");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $vista);
        $this->showView($this->view);
    }
    
    public function recuperarClave($email){
        $rel=$this->userModel->recuperarClave($email);
        if($rel!=false){
            $this->enviarEmail($email, "Restablecer Clave Tienda SOMAR", "<html><head></body><div>Ingrese al siguiente vinculo para restablecer la clave de su cuenta, recuerde que este vinculo tendra una vigencia de 24 Horas<br><a href='http://localhost/somar/index.php?mode=restablecer-clave&id=$rel'>Cambiar Clave</a></div></body></head></html>");
            return true;
        }else{
            return false;
        }
        
    }
    
    public function restablecerClave($id_seguridad){
        $email=$this->userModel->consultarInfoIdSeguridad($id_seguridad);
        if($email!=""){
            $vista= $this->getTemplate("./app/views/user/recuperarClave/cambiar-clave.html");
            $vista = $this->renderView($vista, "{{EMAIL}}",$email);
            $vista = $this->renderView($vista, "{{ID_SEGURIDAD}}",$id_seguridad);
            //cargar el menu en la plantilla principial
            $this->view=$this->cargarContenidoPlantilla($this->view);
            $this->view = $this->renderView($this->view, "{{TITULO}}","Cambiar Contraseña");
            $this->view = $this->renderView($this->view, "{{CONTENT}}", $vista);
            $this->showView($this->view);
        }else{
            $this->cargarError404();
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
    
    public function cambiarClaveUser($passwordVieja,$passwordNueva){
        $nick=$_SESSION['user_id'];
        $passwordVieja=sha1($passwordVieja);
        $passwordNueva=sha1($passwordNueva);
        if($this->userModel->loginUser($nick,$passwordVieja)){
            $rel= $this->userModel->cambiarClaveUser($nick, $passwordNueva);
            if($rel)
            echo true;
            else
                echo false;
            
        }else{
            echo false;
        }
    }

    public function cargarContactanos(){
        $contenido= $this->getTemplate("./app/views/user/contactanos/contactanos.html");
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Contactanos");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);
    }

    public function enviarMensajeTienda($nombre,$email, $asunto, $mensaje){
        $msj="<html><head></body><div><b>Nombre: </b> $nombre<br><b>E-mail: </b> $email<br><b>Asunto: </b> $asunto<br><b>Mensaje: </b> $mensaje </div></body></head></html>";
        $asunto="Mensaje De ".$nombre;
        $emailEnviar="somarufps@gmail.com";
        echo $this->enviarEmail($emailEnviar, $asunto, $msj);

    }
}

?>
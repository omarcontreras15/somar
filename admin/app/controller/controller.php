<?php
require 'public/lib/PHPMailer/PHPMailerAutoload.php';


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

        return $mail->Send();
    }


}
?>
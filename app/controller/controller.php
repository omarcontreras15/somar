<?php
require './public/lib/PHPMailer/PHPMailerAutoload.php';
require './app/model/categoria.php';
require './app/model/admin/venta.php';


class Controller
{
    public function getTemplate($route)
    {
        return file_get_contents($route);
    }

    public function showView($view)
    {
        echo $view;
    }

    public function renderView($ubicacion, $cadenaReemplazar, $reemplazo)
    {
        return str_replace($cadenaReemplazar, $reemplazo, $ubicacion);
    }

    public function cargarMenuBarAdmin()
    {
        $menu = null;
        $ventaModel = new VentaModel();
        if ($this->sesionIniciadaAdmin()) {
            $menu = $this->getTemplate("./app/views/admin/components/menu-login.html");
            //listamos todas las ventas que estan en progreso y que ya subieron comprobante de pago
            $array = $ventaModel->listarVentas("progreso");
            //inicializamos un contador para contar el numero de ventas que estan en progreso y que ya subieron comprobante de pago
            $cont = 0;
            $contenido = "";
            foreach ($array as $element) {
                //realizamos la validación
                if ($element['url_comprobante_pago'] != "") {
                    //contamos el numero de comprobantes subidos
                    $cont++;
                    //solo mostramos los 5 primeros comprobantes
                    if ($cont < 5) {
                        $contenido .= '<a href="#" onclick="verComprobanteNoti('.$element['id_pedido'].')" data-toggle="modal" data-target="#ventanaComprobante" class="list-group-item">Pedido #' . $element['id_pedido'] . ' - ' . $element['nick_usuario'] . '</a>';
                    }

                }
            }
            if ($cont > 0) {
                if ($cont > 5) {
                    $contenido .= '<a href="admin.php?mode=ventas-progreso" class="list-group-item text-center">Ver Más...</a>';
                }
                $badge = "<span class='badge badge-notificacion'>$cont</span>";
                $menu = $this->renderView($menu, "{{BADGE NOTIFICACION}}", $badge);
                $menu = $this->renderView($menu, "{{HTML_NOTIFICACIONES}}", $contenido);
            } else {
                $menu = $this->renderView($menu, "{{BADGE NOTIFICACION}}", "");
                $menu = $this->renderView($menu, "{{HTML_NOTIFICACIONES}}", "No hay nuevos comprobantes de pago a verficar.");
            }
        } else {
            $menu = $this->getTemplate("./app/views/admin/components/menu-logout.html");
        }

        return $menu;
    }

    public function sesionIniciadaAdmin()
    {
        return isset($_SESSION["admin_id"]);
    }

    public function sesionIniciadaUser()
    {
        return isset($_SESSION["user_id"]);
    }

    private function cargarMenuBarFront1()
    {
        $menu = null;
        if ($this->sesionIniciadaUser()) {
            $menu = $this->getTemplate("./app/views/user/components/menu1/menu-login.html");
        } else {
            $menu = $this->getTemplate("./app/views/user/components/menu1/menu-logout.html");
        }

        return $menu;

    }

    private function cargarMenuBarFront2()
    {
        $menu = null;
        if ($this->sesionIniciadaUser()) {
            $menu = $this->getTemplate("./app/views/user/components/menu2/menu-login.html");
        } else {
            $menu = $this->getTemplate("./app/views/user/components/menu2/menu-logout.html");
        }
        //cargamos las catogorias en el menu2   
        $menu = $this->renderView($menu, "{{MENU_CATEGORIA}}", $this->cargarCategoriasMenu());
        return $menu;
    }

    private function cargarCategoriasMenu()
    {
        $modelCategoria = new CategoriaModel();
        $htmlcategorias = "";
        $array = $modelCategoria->listarCategorias();
        foreach ($array as $element) {
            $htmlcategorias .= '<li><a href="index.php?mode=productos-categoria&id=' . $element['id'] . '">' . $element['nombre_categoria'] . '</a></li>';
        }

        return $htmlcategorias;
    }

    public function cargarCategoriasMenuLeft()
    {
        $modelCategoria = new CategoriaModel();
        $htmlcategorias = "";
        $array = $modelCategoria->listarCategorias();
        foreach ($array as $element) {
            $htmlcategorias .= '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a href="index.php?mode=productos-categoria&id=' . $element['id'] . '">' . $element['nombre_categoria'] . '</a></h4></div></div>';
        }

        return $htmlcategorias;
    }

    public function cargarContenidoPlantilla($view)
    {
        //varifica si hay o no una sesion iniciado, dependiendo del caso cargar el menuBar
        $menu1 = $this->cargarMenuBarFront1();
        $menu2 = $this->cargarMenuBarFront2();
        //carga los menus dependiendo si el usuario tiene o no sesion iniciada
        $view = $this->renderView($view, "{{MENU_BAR1}}", $menu1);
        $view = $this->renderView($view, "{{MENU_BAR2}}", $menu2);
        if ($this->sesionIniciadaUser()) {
            $view = $this->renderView($view, "{{SALUDO_NICK}}", "Bienvenido - " . $_SESSION["user_id"]);
        } else {
            $view = $this->renderView($view, "{{SALUDO_NICK}}", "");
        }

        return $view;
    }

    //metodo para enviar emails
    public function enviarEmail($email, $asunto, $contenido)
    {
        $mail = new PHPMailer();
        $mail->IsSmtp();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "somarufps@gmail.com";
        $mail->Password = "tiendasomarufps01";
        $mail->SetFrom("somarufps@gmail.com");
        $mail->Subject = $asunto;
        $mail->Body = $contenido;
        $mail->AddAddress($email);
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

    //metodo para mover un archivo traido del formulario a la carpeta upload
    public function agregarArchivo($nom_input_file)
    {
        $urlArchivo = "";
        if (is_uploaded_file($_FILES[$nom_input_file]['tmp_name'])) {
            $nombreDirectorio = "public/upload/";
            $nombreFichero = $_FILES[$nom_input_file]['name'];
            //opcional
            $tipoArchivo = $_FILES[$nom_input_file]['type'];
            $extencionFichero = "." . substr(strrchr($tipoArchivo, "/"), 1);
            //
            //id unico de tiempo
            $idUnico = time();

            $urlArchivo = $nombreDirectorio . $nom_input_file . "-" . $idUnico . $extencionFichero;
            move_uploaded_file($_FILES[$nom_input_file]['tmp_name'], $urlArchivo);

            return $urlArchivo;
        } else {
            return false;
        }
    }

    public function eliminarArchivo($url)
    {
        //Eliminamos la imagen del evento ubicada en la carpeta upload
        try {
            if ($url != "" || !isset($url) || $url != null) {
                unlink($url);
            }
        } catch (Exception $e) {

        }
    }


}

?>
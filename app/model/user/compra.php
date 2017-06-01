<?php

include_once "./app/model/model.php";

class CompraModel extends Model{

    public function cargarMisCompras($nick){
        $consulta=null;
        $this->connect();
        $consulta = "SELECT id_pedido,DATE_FORMAT(fecha_pedido,'%b %d %Y - %h:%i %p') fecha_pedido, valor_pedido, status, url_comprobante_pago from Pedido where nick_usuario='$nick'";
        $query = $this->query($consulta);
        $this->terminate();
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_unshift($array, $row);
        }
        return $array;
    }
   public function obtenerDatosUsuario($id_pedido){
        $this->connect();
        $consulta="SELECT * FROM usuario where nick=(SELECT nick_usuario from pedido where id_pedido=$id_pedido)";
        $datosU=mysqli_fetch_array($this->query($consulta));
        $this->terminate();
        return $datosU;
    }

    public function obtenerDetalleFactura($id_pedido){
         $this->connect();
        $consulta="SELECT id_pedido, DATE_FORMAT(fecha_pedido,'%b %d %Y - %h:%i %p') fecha_pedido, status, valor_pedido, url_comprobante_pago from pedido where id_pedido=$id_pedido";
        $datosDetalleF=mysqli_fetch_array($this->query($consulta));
        $this->terminate();
        return $datosDetalleF;
    }

    public function listarItemsPedido($id_pedido){
        $array= array();
        $this->connect();
        $consulta="SELECT p.id_producto id_producto, d.cantidad cantidad, p.referencia referencia, p.nombre_producto nombre_producto, p.precio, (d.cantidad*p.precio) valor_total   from producto p, detalle_pedido d where p.id_producto=d.id_producto and d.id_pedido=$id_pedido";
        $consulta=$this->query($consulta);
         while($row = mysqli_fetch_array($consulta)){
            array_unshift($array, $row);
        }
        $this->terminate();
        return $array;
    }

    public function subirComprobante($id_pedido, $url_comprobante){
        $this->connect();
        $update="UPDATE pedido set url_comprobante_pago='$url_comprobante' where id_pedido=$id_pedido";
        $update=$this->query($update);
        $this->terminate();
        return $array;
    }
}
?>
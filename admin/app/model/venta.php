<?php

require_once "./app/model/model.php";

class VentaModel extends Model{


    public function listarVentasRelizadas(){
        $this->connect();
        $consulta = "SELECT id_pedido, nick_usuario,DATE_FORMAT(fecha_pedido,'%b %d %Y - %h:%i %p') fecha_pedido, valor_pedido from Pedido where status='aprobado'";
        $query = $this->query($consulta);
        $this->terminate();
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_unshift($array, $row);
        }
        return $array;
    }

    public function eliminarPedido($id){
        $this->connect();
        $delete = "delete from pedido where id_pedido=$id";
        $this->query($delete);
        $delete = "delete from detalle_pedido where id_pedido=$id";
        $this->query($delete);
        $this->terminate();
    }
}
?>
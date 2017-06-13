<?php

include_once "./app/model/model.php";

class CarritoModel extends Model{

    private function verificarExistenciaProducto($nick, $id_producto){
        $consulta = "SELECT * from carrito where nick='$nick' and id_producto=$id_producto";
        $query = $this->query($consulta);
        while($row = mysqli_fetch_array($query)){
            return true;
        }
        return false;
    }

    public function agregarProducto($nick, $id_producto, $cantidad){
        $this->connect();
        if($this->verificarExistenciaProducto($nick, $id_producto)){
          $sql="UPDATE carrito set cantidad=$cantidad where nick='$nick' and id_producto=$id_producto";  
          echo $sql;
        }else{
          $sql = "INSERT INTO carrito (nick, id_producto, cantidad) values ('$nick', $id_producto, $cantidad)";
          echo $sql;
        }

        $query = $this->query($sql);
        $this->terminate();
        return $query;
    }

    public function cargarProductosCarrito($nick){
        $this->connect();
        $consulta = "SELECT p.id_producto,p.referencia, p.nombre_producto, p.precio, p.url_img1, c.cantidad, (p.precio*c.cantidad)total, p.cant_disponibles from producto p, carrito c where p.id_producto=c.id_producto and c.nick='$nick'";
        $query = $this->query($consulta);
        $this->terminate();
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_unshift($array, $row);
        }
        return $array;

    }

    public function eliminarProducto($nick, $id_producto){
        $this->connect();
        $delete = "DELETE from carrito where id_producto=$id_producto and nick='$nick'";
        $query = $this->query($delete);
        $this->terminate();
    }

    public function vaciarCarrito($nick){
        $this->connect();
        $delete = "DELETE from carrito where nick='$nick'";
        $query = $this->query($delete);
        $this->terminate();
    }

    public function realizarCompra($nick){
        $this->connect();
        $insert = "INSERT INTO pedido (nick_usuario) values('$nick')";
        $query=$this->query($insert);
        $this->terminate();
        return $query;
    }

    public function obtenerUltimoPedido($nick){
        $this->connect();
        $consulta = "SELECT id_pedido from pedido where nick_usuario='$nick' order by id_pedido desc limit 1";
        $query=mysqli_fetch_array($this->query($consulta));
        $this->terminate();
        return $query["id_pedido"];
    }

    public function insertarDetallePedido($id_pedido, $id_producto,$cantidad){
        $this->connect();
        $insert = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad) values($id_pedido,$id_producto,$cantidad)";
        $query=$this->query($insert);
        $this->terminate();
        return $query;
    }


}

?>
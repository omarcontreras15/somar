<?php
require_once "./app/model/model.php";

class ProductoModel extends Model{
    
    public function listarProductos(){
        $this->connect();
        $consulta = "SELECT id_producto, nombre_producto, precio, cant_disponibles, url_img1 from producto";
        $query = $this->query($consulta);
        $this->terminate();
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_unshift($array, $row);
        }
        return $array;
    }
    
    public function listarCategorias(){
        
        $this->connect();
        $consulta = "SELECT * from categoria";
        $query = $this->query($consulta);
        $this->terminate();
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_unshift($array, $row);
        }
        return $array;
        
    }
}
?>
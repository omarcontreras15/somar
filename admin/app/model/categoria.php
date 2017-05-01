<?php
require_once "./app/model/model.php";

class CategoriaModel extends Model{

    public function listarCategorias(){
        $this->connect();
        $consulta = "SELECT id_categoria as id, nombre_categoria, (select count(*) from productos_categoria where id_categoria=id) as cantidad from categoria";
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
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

    public function eliminarCategoria($id){
        $this->connect();
        $delete = "delete from productos_categoria where id_producto=$id";
        $this->query($delete);
        $delete = "delete from categoria where id_categoria=$id";
        $this->query($delete);
        $this->terminate();
    }

        //esta funcion sirve para agregar un nueva categoria y toda su informacion a la base de datos 
    public function agregarCategoria($nombre){
        $id;
        $this->connect();
        $consulta = "SELECT * from categoria where LOWER(nombre_categoria)=LOWER(TRIM('$nombre'))";
        $query = $this->query($consulta);
        $row = mysqli_fetch_array($query);
        if(!isset($row)){
            $insertar = "INSERT INTO categoria (nombre_categoria) VALUES (TRIM('$nombre'))";
            $query = $this->query($insertar);
            if($query==1){
                $consulta = "SELECT id_categoria FROM categoria ORDER BY id_categoria DESC  LIMIT 1";
                $query=mysqli_fetch_array($this->query($consulta));
                $id=$query["id_categoria"];
                $this->terminate();
                return $id;
            }
        }else{
            $this->terminate();
            return "repetido";
        }
    }

    public function buscarNombreCategoria($id){
        $this->connect();
        $consulta = "SELECT nombre_categoria FROM categoria where id_categoria=$id";
        $query=mysqli_fetch_array($this->query($consulta));
        $this->terminate();
        return $query["nombre_categoria"];
    }

    public function editarCategoria($id, $nombre){
        $this->connect();
        $consulta = "SELECT * from categoria where LOWER(nombre_categoria)=LOWER(TRIM('$nombre')) and id_categoria<>$id";
        $query = $this->query($consulta);
        $row = mysqli_fetch_array($query);
        if(!isset($row)){
            $update = "UPDATE categoria set nombre_categoria='$nombre' where id_categoria=$id";
            $query = $this->query($update);
           $this->terminate();
           return $query;
        }else{
            $this->terminate();
            return "repetido";
        }
    }

}

?>
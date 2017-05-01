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
    
    public function agregarProducto($form, $url_img1, $url_img2, $url_img3){
        $id;
        $this->connect();
        $consulta = "SELECT * from producto where nombre_producto='".$form['nombre']."' AND referencia='".$form['referencia']."'";
        $query = $this->query($consulta);
        $row = mysqli_fetch_array($query);
        if(!isset($row)){
            $insertar = "INSERT INTO `producto` (`referencia`, `nombre_producto`, `precio`, `cant_disponibles`, `descripcion`, `url_img1`, `url_img2`, `url_img3`) VALUES ('".$form['referencia']."','".$form['nombre']."',".$form['precio'].",".$form['cantidad'].",'".$form['descripcion']."','$url_img1','$url_img2','$url_img3')";
            $query = $this->query($insertar);
            if($query==1){
                $consulta = "SELECT id_producto FROM producto ORDER BY id_producto DESC  LIMIT 1";
                $query=mysqli_fetch_array($this->query($consulta));
                $id=$query["id_producto"];
                
                if(isset($form['categoria'])){
                    foreach($form['categoria'] as $element){
                        //agregar las categorias a los productos en la tabla productos_categorias
                        $insertar="INSERT INTO productos_categoria (id_producto,id_categoria) values($id,$element)";
                        $this->query($insertar);
                    }
                }
                $this->terminate();
                return $id;
            }
        }else{
            $this->terminate();
            return "repetido";
        }
    }
    
    //esta funcion sirve para eliminar un producto de la base de datos
    
    public function eliminarProducto($id){
        $this->connect();
        $consulta="SELECT url_img1, url_img2, url_img3 from producto where id_producto=$id";
        $urls=mysqli_fetch_array($this->query($consulta));
        $delete = "delete from productos_categoria where id_producto=$id";
        $this->query($delete);
        $delete = "delete from producto where id_producto=$id";
        $this->query($delete);
        $this->terminate();
        return $urls;
    }
}
?>
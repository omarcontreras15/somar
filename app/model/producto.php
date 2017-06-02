<?php
require_once "./app/model/model.php";

class ProductoModel extends Model{
    
    public function listarProductos(){
        $this->connect();
        //listamos todos los productos que tengan el atributo disponibilidad como disponible 
        $consulta = "SELECT id_producto, nombre_producto, precio, cant_disponibles, url_img1 from producto where disponibilidad='disponible'";
        $query = $this->query($consulta);
        $this->terminate();
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_unshift($array, $row);
        }
        return $array;
    }
    

    public function listarProductosNuevo(){
         $this->connect();
        //listamos todos los productos que tengan el atributo disponibilidad como disponible 
        $consulta = "SELECT id_producto, nombre_producto, precio, cant_disponibles, url_img1 from producto where disponibilidad='disponible' and cant_disponibles>0 and DATEDIFF(now(), fecha_registro)<=7 order by fecha_registro asc limit 6";
        $query = $this->query($consulta);
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_unshift($array, $row);
        }
        $this->terminate();
        return $array;
    }

     public function listarProductosMasVendido(){
         $this->connect();
        //listamos todos los productos que tengan el atributo disponibilidad como disponible 
        $consulta = "SELECT p.id_producto, nombre_producto, precio, cant_disponibles, url_img1, IFNULL((select sum(cantidad) from detalle_pedido where id_producto=p.id_producto),0) cant_vendidas from producto p where disponibilidad='disponible' and cant_disponibles>0 order by cant_vendidas desc limit 6";
        $query = $this->query($consulta);
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_unshift($array, $row);
        }
        $this->terminate();
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

    
    //esta funcion sirve para agregar un nuevo producto y toda su informacion a la base de datos 
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

    //esta funcion sirve para actualizar la informacion del producto en la base de datos 
    public function editarProducto($form,$id,$url_imagen1,$url_imagen2,$url_imagen3){
        $id;
        $this->connect();
        //verificamos que no se encuentre registrado un producto con el mismo nombre y referencia
        $consulta = "SELECT * from producto where nombre_producto='".$form['nombre']."' AND referencia='".$form['referencia']." AND id_producto<>$id'";
        $query = $this->query($consulta);
        $row = mysqli_fetch_array($query);
         //si el producto no existe en la base de datos se procede a agregarlo en caso contrario no se agrega
        if(!isset($row)){
        //aquí  actualizamos la informacion dee producto
            $update = "UPDATE producto set referencia='".$form['referencia']."', nombre_producto='".$form['nombre']."', precio=".$form['precio'].", cant_disponibles=".$form['cantidad'].", descripcion='".$form['descripcion']."' where id_producto=$id";
            $update = $this->query($update);
        
        //eliminamos todas las catogorias asociadas al producto
        $delete = "delete from productos_categoria where id_producto=$id";
        $this->query($delete);
            if($update==1){
                
            if($url_imagen1!=""){
                $update = "UPDATE producto set url_img1='$url_imagen1' where id_producto=$id";
                $update = $this->query($update);
            }

            if($url_imagen2!=""){
                $update = "UPDATE producto set url_img2='$url_imagen2' where id_producto=$id";
                $update = $this->query($update);
            }

            if($url_imagen3!=""){
                $update = "UPDATE producto set url_img3='$url_imagen3' where id_producto=$id";
                $update = $this->query($update);
            } 
            //verificamos que por lo menos se haya seleccionado un checkbox  
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
        //no podemos eliminar el producto, le cambiamos la disponibilidad a no_disponible con el objetivo de respetar la integridad referencial
        $update = "UPDATE producto set disponibilidad='no_disponible', url_img1='', url_img2='', url_img3='' where id_producto=$id";
        $this->query($update);
        $this->terminate();
        return $urls;
    }

    public function consultarUrlsImgProductos($id){
        $this->connect();
        $consulta="SELECT url_img1, url_img2, url_img3 from producto where id_producto=$id";
        $urls=mysqli_fetch_array($this->query($consulta));
        $this->terminate();
        return $urls;
    }

    //esta funcion sirve para buscar toda la informacion de un producto en especifico 

    public function consultarProducto($id){
        $consultaP=array();
        $this->connect();
        //consulta toda la informacion del producto con una id especifica
        $consulta="SELECT * from producto where id_producto=$id";
        $datosP=mysqli_fetch_array($this->query($consulta));
        //consulta cuales son las categorias a las cuales esta asociado el id de un producto
        $categorias="SELECT id_categoria from productos_categoria where id_producto=$id";
        $consulta=$this->query($categorias);
        $arrayC=array();
        while($row=mysqli_fetch_array($consulta)){
        array_unshift($arrayC,$row['id_categoria']);
        }
    
        $consultaP=array('infoP'=>$datosP, 'categorias'=>$arrayC);     
        $this->terminate();
        return $consultaP;


    }

}
?>
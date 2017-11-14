<?php
require_once "./app/model/model.php";

class EstadisticaModel extends Model{

    public function obtnerProductosMasVistos(){
        $this->connect();
        $consulta = "SELECT v.id_producto, p.nombre_producto, sum(1) cant_visto from producto_visualizado v, producto p where v.id_producto=p.id_producto group by v.id_producto order by cant_visto desc limit 10";
        $query = $this->query($consulta);
        $cont=0;
        $json="";
        while($row = mysqli_fetch_array($query)){
            $json.="['".$row['nombre_producto']."',".$row['cant_visto']."],";
        }  
        $json= substr($json, 0, -1);
        $this->terminate();
        return $json;
    }

    public function obtenerProdcutosMasVendidos(){
        $this->connect();
        $consulta = "SELECT p.id_producto, nombre_producto, IFNULL((select sum(cantidad) from detalle_pedido where id_producto=p.id_producto),0) cant_vendidas from producto p where disponibilidad='disponible' and cant_disponibles>0 order by cant_vendidas desc limit 10";
        $query = $this->query($consulta);
        $cont=0;
        $json="";
        while($row = mysqli_fetch_array($query)){
            $json.="['".$row['nombre_producto']."',".$row['cant_vendidas']."],";
        }  
        $json= substr($json, 0, -1);
        $this->terminate();
        return $json;
    }

    public function obtenerVentas(){
        //array con los meses del año
        $meses=Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $valor_ventas="['Enero',0],
                    ['Febrero',0],
                    ['Marzo',0],
                    ['Junio',0],
                    ['Julio',0],
                    ['Agosto',0],
                    ['Septiembre',0],
                    ['Octubre',0],
                    ['Noviembre',0],
                    ['Diciembre',0]";

        $this->connect();
        $consulta = "SELECT month(fecha_pedido) mes, sum(valor_pedido) as valor_ventas_mes from pedido where fecha_pedido like (concat('%',YEAR(now()),'%'))  GROUP BY mes";
        $query = $this->query($consulta);
        $this->terminate();
        while($row = mysqli_fetch_array($query)){
               $num=intval($row['mes']-1);
               $valor_ventas = str_replace("'".$meses[$num]."',0", "'".$meses[$num]."',".$row['valor_ventas_mes'], $valor_ventas);
        }  
        return $valor_ventas;
    }

    public function obtenerMejoresClientes(){
        $this->connect();
        $consulta = "SELECT p.nick_usuario nick, sum((select sum(cantidad) from detalle_pedido dp where dp.id_pedido=p.id_pedido)) as cant_productos_comprados from pedido p group by p.nick_usuario order by cant_productos_comprados desc limit 10";
        $query = $this->query($consulta);
        $cont=0;
        $json="";
        while($row = mysqli_fetch_array($query)){
            $json.="['".$row['nick']."',".$row['cant_productos_comprados']."],";
        }  
        $json= substr($json, 0, -1);
        $this->terminate();
        return $json;
    }

}

?>
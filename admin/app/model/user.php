<?php
require_once "./app/model/model.php";

class UserModel extends Model {
    
    
    function login ($usuario, $password) {
        $this->connect();
        $consulta = "SELECT * FROM usuario WHERE (nick = '$usuario' AND password = '$password' AND tipo='admin') OR (email = '$usuario' AND password = '$password' AND tipo='admin')";
        $query = $this->query($consulta);
        $this->terminate();
        while($row = mysqli_fetch_array($query)){
            //agrega el id a la session
            $_SESSION["user_id"] = $row["nick"];
            //agrega la hora en la que inicio sesion
            $_SESSION["ultimoAcceso"] = time();
            return true;
        }
        return false;
        
        
    }
    
    public function recuperarClave($email){
        $id=null;
        $this->connect();
        $consulta = "SELECT * FROM usuario WHERE email='$email'";
        $query = $this->query($consulta);
        $row = mysqli_fetch_array($query);
        if(isset($row)){
            $consulta="SELECT * FROM recuperar_claves WHERE email='$email'";
            $query = $this->query($consulta);
            $row = mysqli_fetch_array($query);
            $id=sha1(time());
            $insert="INSERT INTO recuperar_claves (email, id_seguridad) values ('$email', '$id')";
            if(isset($row)){
                $delete="delete from recuperar_claves where email='$email'";
                $query = $this->query($delete);
            }
            $query=$this->query($insert);
            $this->terminate();
            return $id;
        }
        
        $this->terminate();
        return false;
        
    }


    public function cambiarClave($id_seguridad, $password){
        $query=null;
        $email=$this->consultarInfoIdSeguridad($id_seguridad);
        $this->connect();
        if($email!=""){
            $password=sha1($password);
            $update="UPDATE usuario  set password='$password' where email='$email'";
            $query = $this->query($update);
            $delete="delete from recuperar_claves where email='$email'";
            $this->query($delete);
        }
        echo $query;
        $this->terminate();
        return $query;
    }

    public  function consultarInfoIdSeguridad($id_seguridad){
        $this->connect();
        $consulta = "SELECT email from recuperar_claves where id_seguridad='$id_seguridad' AND DATEDIFF(now(), fecha_registro)<=1";
        $query = $this->query($consulta);
        $row = mysqli_fetch_array($query);
        $this->terminate();
        if(isset($row)){
            return $row['email'];
        }
        return "";
    }
}

?>
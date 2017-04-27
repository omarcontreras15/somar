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
}

?>
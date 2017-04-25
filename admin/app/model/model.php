<?php

class Model {
    private $connection;

    public function connect(){
        $server="sandbox2.ufps.edu.co";
        $user="1151256";
        $pass="1151256";
        $bd="1151256";
        $this->connection = mysqli_connect($server,$user,$pass,$bd) or  die(("Error " . mysqli_error($this->connection)));
    }

    public function query($sql){
        return mysqli_query($this->connection,$sql);
    }

    public function multiQuery($sql){
        return mysqli_multi_query ($this->connection,$sql);
    }


    public function terminate(){
        mysqli_close($this->connection);
    }

}

?>
<?php

class administradorDTO{

        private $nick;
        private $email;
        private $password;
        private $nombre;
        private $apellido;


        function __construct($nick, $email, $password, $nombre, $apellido) {
            $this->nick = $nick;
            $this->email = $email;
            $this->password = $password;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
        }
        
        function getNick() {
            return $this->nick;
        }

        function getEmail() {
            return $this->email;
        }

        function getPassword() {
            return $this->password;
        }

        function getNombre() {
            return $this->nombre;
        }

        function getApellido() {
            return $this->apellido;
        }

        function setNick($nick) {
            $this->nick = $nick;
        }

        function setEmail($email) {
            $this->email = $email;
        }

        function setPassword($password) {
            $this->password = $password;
        }

        function setNombre($nombre) {
            $this->nombre = $nombre;
        }

        function setApellido($apellido) {
            $this->apellido = $apellido;
        }


}

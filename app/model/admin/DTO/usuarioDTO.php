<?php

class usuarioDTO{
   
    private $nick;
    private $email;
    private $password;
    private $nombres;
    private $apellidos;
    private $cc;
    private $direccion;
    private $fecha_Registro;
    private $tipo;
    
    function __construct($nick, $email, $password, $nombres, $apellidos, $cc, $direccion, $fecha_Registro, $tipo) {
        $this->nick = $nick;
        $this->email = $email;
        $this->password = $password;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cc = $cc;
        $this->direccion = $direccion;
        $this->fecha_Registro = $fecha_Registro;
        $this->tipo = $tipo;
       
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

     function getNombres() {
         return $this->nombres;
     }

     function getApellidos() {
         return $this->apellidos;
     }

     function getCc() {
         return $this->cc;
     }

     function getDireccion() {
         return $this->direccion;
     }

     function getFecha_Registro() {
         return $this->fecha_Registro;
     }

     function getTipo() {
         return $this->tipo;
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

     function setNombres($nombres) {
         $this->nombres = $nombres;
     }

     function setApellidos($apellidos) {
         $this->apellidos = $apellidos;
     }

     function setCc($cc) {
         $this->cc = $cc;
     }

     function setDireccion($direccion) {
         $this->direccion = $direccion;
     }

     function setFecha_Registro($fecha_Registro) {
         $this->fecha_Registro = $fecha_Registro;
     }

     function setTipo($tipo) {
         $this->tipo = $tipo;
     }



}

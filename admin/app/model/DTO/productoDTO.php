<?php

class productoDTO{
   
    private $id_producto;
    private $referencia;
    private $nombre_producto;
    private $precio;
    private $cant_disponibles;
    private $descripcion;
    private $url_img1;
    private $url_img2;
    private $url_img3;
    
    function __construct($id_producto, $referencia, $nombre_producto, $precio, $cant_disponibles, $descripcion, $url_img1, $url_img2, $url_img3) {
        $this->id_producto = $id_producto;
        $this->referencia = $referencia;
        $this->nombre_producto = $nombre_producto;
        $this->precio = $precio;
        $this->cant_disponibles = $cant_disponibles;
        $this->descripcion = $descripcion;
        $this->url_img1 = $url_img1;
        $this->url_img2 = $url_img2;
        $this->url_img3 = $url_img3;
    }

    function getId_producto() {
        return $this->id_producto;
    }

    function getReferencia() {
        return $this->referencia;
    }

    function getNombre_producto() {
        return $this->nombre_producto;
    }

    function getPrecio() {
        return $this->precio;
    }

    function getCant_disponibles() {
        return $this->cant_disponibles;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getUrl_img1() {
        return $this->url_img1;
    }

    function getUrl_img2() {
        return $this->url_img2;
    }

    function getUrl_img3() {
        return $this->url_img3;
    }

    function setId_producto($id_producto) {
        $this->id_producto = $id_producto;
    }

    function setReferencia($referencia) {
        $this->referencia = $referencia;
    }

    function setNombre_producto($nombre_producto) {
        $this->nombre_producto = $nombre_producto;
    }

    function setPrecio($precio) {
        $this->precio = $precio;
    }

    function setCant_disponibles($cant_disponibles) {
        $this->cant_disponibles = $cant_disponibles;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setUrl_img1($url_img1) {
        $this->url_img1 = $url_img1;
    }

    function setUrl_img2($url_img2) {
        $this->url_img2 = $url_img2;
    }

    function setUrl_img3($url_img3) {
        $this->url_img3 = $url_img3;
    }


}

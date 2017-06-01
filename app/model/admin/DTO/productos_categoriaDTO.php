<?php

class productos_categoriaDTO{
   
    private $id_producto;
    private $id_categoria;
    
    function __construct($id_producto, $id_categoria) {
        $this->id_producto = $id_producto;
        $this->id_categoria = $id_categoria;
    }
    
    function getId_producto() {
        return $this->id_producto;
    }

    function getId_categoria() {
        return $this->id_categoria;
    }

    function setId_producto($id_producto) {
        $this->id_producto = $id_producto;
    }

    function setId_categoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }



}

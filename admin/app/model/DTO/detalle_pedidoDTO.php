<?php

class detalle_pedidoDTO{

        private $id_pedido;
        private $id_producto;
        private $cantidad;

        function __construct($id_pedido, $id_producto, $cantidad) {
            $this->id_pedido = $id_pedido;
            $this->id_producto = $id_producto;
            $this->cantidad = $cantidad;
        }
        
        function getId_pedido() {
            return $this->id_pedido;
        }

        function getId_producto() {
            return $this->id_producto;
        }

        function getCantidad() {
            return $this->cantidad;
        }

        function setId_pedido($id_pedido) {
            $this->id_pedido = $id_pedido;
        }

        function setId_producto($id_producto) {
            $this->id_producto = $id_producto;
        }

        function setCantidad($cantidad) {
            $this->cantidad = $cantidad;
        }



}

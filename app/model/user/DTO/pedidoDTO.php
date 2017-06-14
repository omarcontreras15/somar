<?php

class pedidoDTO{

        private $id_pedido;
        private $nick_usuario;
        private $fecha_pedido;
        private $status;
        private $url_comprobante_pago;
        private $valor_pedido;

        function __construct($id_pedido, $nick_usuario, $fecha_pedido, $status, $url_comprobante_pago, $valor_pedido) {
            $this->id_pedido = $id_pedido;
            $this->nick_usuario = $nick_usuario;
            $this->fecha_pedido = $fecha_pedido;
            $this->status = $status;
            $this->url_comprobante_pago = $url_comprobante_pago;
            $this->valor_pedido = $valor_pedido;
        }

        function getId_pedido() {
            return $this->id_pedido;
        }

        function getNick_usuario() {
            return $this->nick_usuario;
        }

        function getFecha_pedido() {
            return $this->fecha_pedido;
        }

        function getStatus() {
            return $this->status;
        }

        function getUrl_comprobante_pago() {
            return $this->url_comprobante_pago;
        }

        function getValor_pedido() {
            return $this->valor_pedido;
        }

        function setId_pedido($id_pedido) {
            $this->id_pedido = $id_pedido;
        }

        function setNick_usuario($nick_usuario) {
            $this->nick_usuario = $nick_usuario;
        }

        function setFecha_pedido($fecha_pedido) {
            $this->fecha_pedido = $fecha_pedido;
        }

        function setStatus($status) {
            $this->status = $status;
        }

        function setUrl_comprobante_pago($url_comprobante_pago) {
            $this->url_comprobante_pago = $url_comprobante_pago;
        }

        function setValor_pedido($valor_pedido) {
            $this->valor_pedido = $valor_pedido;
        }

  
}

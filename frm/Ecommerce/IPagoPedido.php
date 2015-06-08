<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PagoPedido
 *
 * @author Lucas Grzina
 */
interface Ftl_IPagoPedido {
    
    public function setPedido ( Ftl_pedido $pedido );
    public function getPedido (  );
    public function mappear ( );
    public function generarPago();
    public static function buscar ( array $params );
    
    
}

?>

<?php

/**
 * Description of Ftl_IPayment
 *
 * @author Lucas Grzina
 */
interface Ftl_IPlataformaPago {
    
    public function setPedido ( Ftl_Pedido $pedido );
    public function getPedido (  );
    public function mappear ( );
    public function genPago();
    public static function buscar ( array $params );
    
//    private static $_estados_pago = array(
//        "in_process"    => "En proceso",
//        "pending"       => "Pendiente",
//        "approved"      => "Aprobado",
//        "rejected"      => "Rechazado"
//    );
    
}

?>

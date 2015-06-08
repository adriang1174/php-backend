<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EcommerceException
 *
 * @author Lucas Grzina
 */
class Ftl_EcommerceException extends Exception{
    protected static $map_errors = array(
        
        /*Pedido*/
        -100 => "El nombre de la tabla de pedidos no esta especificado",
        -102 => "No se encuentra el pedido",
        -103 => "No se pudo insertar el pedido",
        -104 => "Para realizar una actualización de pedido se debe setear un ID",   
        
        /*ArticuloPedido*/
        -120 => "El nombre de la tabla de articulos de pedido no esta especificado",
        -121 => "Uno de los articulos no se pudo guardar.",
        -122 => "No se pudo eliminar el detalle del pedido."
    );
    
    protected $result;
    
    public function __construct($result) {
        
        $this->result = $result;

        $code  = isset($result) ? $result->state : 0;
        
        $message = array_key_exists($code, self::$map_errors) ? self::$map_errors[ $code ] : "Error no especificado";
        
        parent::__construct($message, $code);        
        
    }
//    public function __construct(Ftl_Response $result) {
//        
//        $this->result = $result;
//
//        $code  = isset($result) ? $result->state : 0;
//        
//        $message = array_key_exists($code, self::$map_errors) ? self::$map_errors[ $code ] : "Error no especificado";
//        
//        parent::__construct($message, $code);        
//        
//    }
    
    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
                                . "{$this->getTraceAsString()}";
    }
    
}

?>

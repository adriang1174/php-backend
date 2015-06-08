<?php

require_once PATH_FRM."/Ecommerce/MP/mercadopago.php";

class Ftl_MercadoPagoUtil implements Ftl_IPlataformaPago {

    protected $pedido;
    protected $pref_pago;
    protected $opciones;

    public function getPrefPago() {
        return $this->pref_pago;
    }
    
    public function setPedido ( Ftl_pedido $pedido ){
        $this->pedido = $pedido;
    }
    public function getPedido (  ){
        return $this->pedido;
    }
    
    public function __construct( Ftl_Pedido $pedido = null, $opciones = array() ) {
        
        $this->pedido = $pedido;
        $this->pref_pago = null;
        $this->opciones = Ftl_ArrayUtil::merge( array(
            "success_url" => PAGO_URL_EXITO,
            "pending_url" => PAGO_URL_PENDIENTE,
            "faylure_url" => PAGO_URL_FALLO
        ), $opciones);
    }
    
    public function mappear ( ){
        $preference = array ();
        $payer = array();
        
        
        if ( $this->getPedido()->getCliente() != null ){
            
            $preference["payer"] = array(
                
                "name" => $this->getPedido()->getCliente()->getNombre(),
                "surname" => $this->getPedido()->getCliente()->getApellido(),
                "email" => $this->getPedido()->getCliente()->getEmail()
            );
            
        }
        
        $detalle = $this->getPedido()->getArticulos();
        $items = array();
        
        foreach( $detalle as $k => $articulo ){

            $item = array(
                "title" => ($articulo->getNombre() != null ? $articulo->getNombre() : $articulo->getCodigo()),
                "quantity" => $articulo->getCantidad(),
                "currency_id" => "ARS",
                "unit_price" => (float)$articulo->getPrecioUnitario()
            );
            array_push($items, $item);
        }
        $preference["items"] = $items;
      
        $this->pref_pago = $preference;
        
    }    


    public function genPago( ){
        $mp = new MP (PAGO_ID, PAGO_SECRETO);
        $this->mappear();
        $request_pref = $mp->create_preference($this->pref_pago);

        if ( is_string($request_pref) || (is_array($request_pref) && $request_pref["status"] == 400)){
            
            if ( is_string($request_pref) ){
                $json = Ftl_JsonUtil::decode($request_pref);
            }else{
                $json = Ftl_JsonUtil::decode(Ftl_JsonUtil::encode($request_pref));
            }
            $r = new Ftl_Response();
            $r->state = -140;            
            $r->message = $json->response->message;
            throw new Ftl_EcommerceException($r);
        }
        
        $this->pref_pago = $request_pref;
        
        $this->pedido->setPago(new Ftl_PagoPedido($this->getPedido()->getId(), 'MP', $this->pref_pago["response"]["id"]));
        return $this->pref_pago;
    }
    public static function buscar ( array $params ){
        
    }
}

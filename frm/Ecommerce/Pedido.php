<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pedido
 *
 * @author Lucas Grzina
 */
require_once PATH_FRM . '/Ecommerce/config.php';

class Ftl_Pedido{
    
    
    /*
     * ID de pedido
     */
    protected $id;
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    
    /*
     * Sub Total del pedido
     */
    private $sub_total;
    
    public function getSubTotal(){
        return $this->sub_total;
    }    
    
    
    /*
     * detalle: Set de objetos que heredan de Ftl_ArticuloPedido
     */
    private $detalle;
    
    public function getDetalle() {
        return $this->detalle;
    }    
    
    /*
     * cliente: Objeto que hereda de Ftl_Registrado
     */
    protected $id_cliente;
    private $cliente;

    public function setCliente($cliente){
        $this->cliente = $cliente;
        $this->id_cliente = $cliente->getId();
    }
    public function getCliente(){
        return $this->cliente;
    }    
    
    /*
     * pago: Objeto que hereda de Ftl_PagoPedido
     */
    protected $id_pago;
    private $plataforma_pago;
    
    public function getPlataformaPago() {
        return $this->plataforma_pago;
    }

    public function setPlataformaPago(Ftl_IPagoPedido $plataforma) {
        $this->plataforma_pago = $plataforma;
        //$pago->setPedido($this);
        //$this->id_pago = $pago->getId();
    }

    
    /*
     * Fecha de alta del pedido
     */
    protected $fecha_alta;
    
    public function getFechaAlta() {
        return $this->fecha_alta;
    }

    public function setFechaAlta($fecha_alta) {
        $this->fecha_alta = $fecha_alta;
    }

    
    /*
     * Estado del pedido
     */
    protected $estado;
    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

        
    
    public function  __construct()
    {
        
        $this->detalle = array();
        $this->estado = 1;
        $this->sub_total = 0;
        
    }
    
    
    public function existeArticulo($identificador){
        return array_key_exists( $identificador, $this->detalle );
    }

    public function agregarArticulo( Ftl_ArticuloPedido $articuloPedido ) {

        if ( array_key_exists( $articuloPedido->getCodigoArticulo(), $this->detalle ) ) {

            $this->detalle[ $articuloPedido->getCodigoArticulo() ]->setCantidad($this->detalle[ $articuloPedido->getCodigoArticulo() ]->getCantidad() + $articuloPedido->getCantidad());

        } else {

            $this->detalle[ $articuloPedido->getCodigoArticulo() ] = $articuloPedido;

        }
        
        $this->sub_total += number_format(( $articuloPedido->getCantidad() * $this->detalle[ $articuloPedido->getCodigoArticulo() ]->getPrecioUnitario() ), 2, '.', '');
    }

    public function getArticulos(){
        return $this->detalle;
    }

    public function modificarArticulo( Ftl_ArticuloPedido $articuloPedido ) {

        if ( array_key_exists( $articuloPedido->getCodigoArticulo(), $this->detalle ) ) {
            $cantidadAnt = $this->detalle[ $articuloPedido->getCodigoArticulo() ]->getCantidad();
            $precio      = $this->detalle[ $articuloPedido->getCodigoArticulo() ]->getPrecioUnitario();

            $this->detalle[ $articuloPedido->getCodigoArticulo() ]->setCantidad($articuloPedido->getCantidad());

            $this->sub_total = number_format($this->sub_total - ($cantidadAnt * $precio) + ( $articuloPedido->getCantidad() * $precio ), 2, '.', '');
        } 

    }

    public function eliminarArticulos( Ftl_ArticuloPedido $articuloPedido = null ) {

        if ( isset ( $articuloPedido ) ) {
            $subtotal = ($this->detalle[ $articuloPedido->getCodigoArticulo() ]->getCantidad() * $this->detalle[ $articuloPedido->getCodigoArticulo() ]->getPrecioUnitario());


            if ( array_key_exists( $articuloPedido->getCodigoArticulo(), $this->detalle ) ) {

                unset( $this->detalle[ $articuloPedido->getCodigoArticulo() ] );
                
                if ( count($this->detalle) > 0)
                    $this->sub_total -= number_format($subtotal, 2, '.', '');
                else
                    $this->sub_total = 0;

            }

        } else {

            $this->detalle = array();
            $this->sub_total = 0;
        }

    }    
    
    /*BASE DE DATOS*/
    protected function obtener($parametros,$detalle = false){
        
        if ( !defined( 'TABLA_PEDIDOS' ) || ($detalle && !defined( 'TABLA_ARTICULOS_PEDIDOS' ) ) ){
            $r = new Ftl_Response();
            $r->state = -100;
            throw new Ftl_EcommerceException($r);
        }
        
        $this->_recuperar(TABLA_PEDIDOS, $parametros);
        
        if ( $this->getId() > 0 && $detalle ){
            
            //Recupero el detalle del pedido
            $resDetalle = self::getDB()->fetchAllObject("select * from " . TABLA_ARTICULOS_PEDIDOS . " where id_pedido = :id",array('id' => $this->getId()));

            $articulo = null;
            foreach ( $resDetalle as $i => $lineaDetalle ){
                $articulo = new Ftl_ArticuloPedido();
                $articulo->mapear($lineaDetalle);
                $this->agregarArticulo($articulo);

            }
            
            
        }
    }
    protected function insertar( ){
        
        $r = new Ftl_Response();
        $r->state = 1;
        
        if ( !defined( 'TABLA_PEDIDOS' ) ){
            $r->state = -100;
            throw new Ftl_EcommerceException($r);
        }
        
        $this->fecha_alta = Ftl_DateTimeUtil::getGMT('y-m-d H:i:s');
        
        $datos = $this->_parametrosGuardar();
       
        try{

            Ftl_ClaseBase::getDB()->beginTransaction();

            $res = Ftl_ClaseBase::getDB()->insert( TABLA_PEDIDOS,$datos );
            
            if ( $res ){
                
                $this->setId ( $res );

                foreach($this->detalle as $detalle){
                    
                    $detalle->setIdPedido( $this->getId() );
                    $detalle->insertar( );

                }

                Ftl_ClaseBase::getDB()->commit();

            }else{

                $r->state = -103;
                throw new Ftl_EcommerceException($r);                
                
            }

        }catch(Ftl_EcommerceException $e){
            Ftl_ClaseBase::getDB()->rollback();
            Ftl_ClaseBase::getDB()->close();            
            throw $e;
        }catch(Exception $e){
            Ftl_ClaseBase::getDB()->rollback();
            Ftl_ClaseBase::getDB()->close();
            throw $e;
        }

        Ftl_ClaseBase::getDB()->close();

        return $r;
    }
    protected function actualizar( $cabecera=true,$detalle=true ){
        
        $r = new Ftl_Response();
        $r->state = 1;
        
        if ( !defined( 'TABLA_PEDIDOS' ) ){
            $r->state = -100;
            throw new Ftl_EcommerceException($r);
        }
        
        if ( $this->getId() < 1 ){
            $r->state = -104;
            throw new Ftl_EcommerceException($r);
        }
        
       
        try{
            
            Ftl_ClaseBase::getDB()->beginTransaction();
            
            if ( $cabecera ){
                
                $datos = $this->_parametrosGuardar();
                $res = Ftl_ClaseBase::getDB()->update( TABLA_PEDIDOS,$datos, Ftl_ClaseBase::getDB()->getEscapedQuery("id=:id",array("id"=>$this->getId())) );
                
                if (!$res){
                    $r->state = -103;
                    throw new Ftl_EcommerceException($r);                
                }
            }
            if ( $detalle ) {
                
                $this->_vaciarDetalle(false);
                
                foreach($this->detalle as $detalle){
                    
                    $detalle->setIdPedido( $this->getId() );
                    $detalle->insertar( );

                }
                
            }

            Ftl_ClaseBase::getDB()->commit();
            Ftl_ClaseBase::getDB()->close();

        }catch(Ftl_EcommerceException $e){
            Ftl_ClaseBase::getDB()->rollback();
            Ftl_ClaseBase::getDB()->close();            
            throw $e;
        }catch(Exception $e){
            Ftl_ClaseBase::getDB()->rollback();
            Ftl_ClaseBase::getDB()->close();
            throw $e;
        }

        return $r;        
        
    }
    public function eliminar(){
        
        $r = new Ftl_Response();
        $r->state = 1;
        
        if ( !defined( 'TABLA_PEDIDOS' ) ){
            $r = new Ftl_Response();
            $r->state = -100;
            throw new Ftl_EcommerceException($r);
        }
        
        if ( $this->getId() < 1 ){
            $r->state = -104;
            throw new Ftl_EcommerceException($r);
        }
        
       
        try{
            
            Ftl_ClaseBase::getDB()->beginTransaction();
            
            $this->_vaciarDetalle(false);
            $res = Ftl_ClaseBase::getDB()->delete( TABLA_PEDIDOS, Ftl_ClaseBase::getDB()->getEscapedQuery("id=:id",array("id"=>$this->getId())));                

            Ftl_ClaseBase::getDB()->commit();
            Ftl_ClaseBase::getDB()->close();

        }catch(Ftl_EcommerceException $e){
            Ftl_ClaseBase::getDB()->rollback();
            Ftl_ClaseBase::getDB()->close();            
            throw $e;
        }catch(Exception $e){
            Ftl_ClaseBase::getDB()->rollback();
            Ftl_ClaseBase::getDB()->close();
            throw $e;
        }

        return $r;        
        
    }
    public function vaciarDetalle () {
        return $this->_vaciarDetalle(true);
    }
    protected function _vaciarDetalle($cerrarConexion = true){
        
        $r = new Ftl_Response();
        $r->state = 1;
        
        if ( !defined( 'TABLA_ARTICULOS_PEDIDOS' ) ){
            $r = new Ftl_Response();
            $r->state = -120;
            throw new Ftl_EcommerceException($r);
        }
       
        try{

            $res = Ftl_ClaseBase::getDB()->delete( TABLA_ARTICULOS_PEDIDOS, Ftl_ClaseBase::getDB()->getEscapedQuery("id_pedido=:id",array("id"=>$this->getId())));
            
            if ( $cerrarConexion ){
                Ftl_ClaseBase::getDB()->rollback();
                Ftl_ClaseBase::getDB()->close();
            }
            
        }catch (Ftl_EcommerceException $e){
            if ( $cerrarConexion ){
                Ftl_ClaseBase::getDB()->rollback();
                Ftl_ClaseBase::getDB()->close();
            }
            throw $e;
        }catch(Exception $e){
            if ( $cerrarConexion ){
                Ftl_ClaseBase::getDB()->rollback();
                Ftl_ClaseBase::getDB()->close();
            }
            throw $e;
        }
        
        return true;                
    }
    protected static function _obtenerListadoPaginado ($campos="*",$from,$pagina=1,$reg_x_pagina=50,$filtros=null,$orden=null)
    {
        $res = null;

        $limit  = ($pagina -1) * $reg_x_pagina;
        $offset = $reg_x_pagina;

        $sqlWhere   = ($filtros) ? "WHERE " . $filtros : "";
        $sqlOrder   = ($orden) ? " ORDER BY $orden" : "";

        $sql = "SELECT SQL_CALC_FOUND_ROWS $campos
                FROM " . $from . "
                $sqlWhere
                $sqlOrder
                LIMIT {$limit},{$offset};";

                
        $res = Ftl_ClaseBase::getDB()->fetchAllAssoc($sql);
        $total = Ftl_ClaseBase::getDB()->getFoundRows();
        if ($res != null){
            $res[0]['total'] = $total;
        }
        Ftl_ClaseBase::getDB()->close();

        return $res;


    }
    
    
    /*PAGO*/
    public function getInfoPago(){
        
    }
    
    
    private function _parametrosGuardar(){
        
        //Paso todas las propiedades public y protected a un array para guardar en base de datos.
        $datos = array();
        $reflect = new ReflectionClass($this);
        $propiedades   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);        
        foreach($propiedades as $prop)
        {
            $prop_name = $prop->getName();
            $datos [ $prop_name ] = $this->$prop_name;
        } 
        return $datos;
    }
    
}

?>

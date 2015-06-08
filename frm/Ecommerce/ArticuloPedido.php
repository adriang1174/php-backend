<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ftl_ArticuloPedido
 *
 * @author Lucas Grzina
 */
class Ftl_ArticuloPedido{
    
    
    protected $codigo;
    protected $id_articulo;
    protected $id_pedido;
    protected $precio;
    protected $cantidad;
    protected $fecha_alta;
    protected $estado;
    
    public function getCodigoArticulo() {
        return $this->codigo;
    }

    public function setCodigoArticulo($codigo_articulo) {
        $this->codigo = $codigo_articulo;
    }
    
    public function getIdArticulo() {
        return $this->id_articulo;
    }

    public function setIdArticulo($id_articulo) {
        $this->id_articulo = $id_articulo;
    }

    public function getIdPedido() {
        return $this->id_pedido;
    }

    public function setIdPedido($id_pedido) {
        $this->id_pedido = $id_pedido;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function getPrecioUnitario() {
        return $this->precio;
    }

    public function setPrecioUnitario($precio_unitario) {
        $this->precio = $precio_unitario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }
    
    public function getFechaAlta() {
        return $this->fecha_alta;
    }

    public function setFechaAlta($fecha) {
        $this->fecha_alta = $fecha;
    }    

    public function __construct() {
        $this->estado = 1;
    }
    
    public function insertar (){
        
        if ( !defined( 'TABLA_ARTICULOS_PEDIDOS' ) ){
            $r = new Ftl_Response();
            $r->state = -120;
            throw new Ftl_EcommerceException($r);
        }
        
        $this->fecha_alta = Ftl_DateTimeUtil::getGMT('y-m-d h:i:s');
        
        $datos = $this->_parametrosGuardar();
       
        try{

            $res = Ftl_ClaseBase::getDB()->insert( TABLA_ARTICULOS_PEDIDOS,$datos );

        }catch(Exception $e){
            throw $e;
        }
        
        //Ftl_ClaseBase::getDB()->close();
        return true;
        
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
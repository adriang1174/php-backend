<?php


/**
 * Description of PagoPedido
 *
 * @author Luki
 */
class Ftl_PagoPedido {

    protected $id;
    protected $id_pedido;
    protected $identificador;
    protected $plataforma;
    protected $estado;
    protected $fecha_alta;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdPedido() {
        return $this->id_pedido;
    }

    public function setIdPedido($id_pedido) {
        $this->id_pedido = $id_pedido;
    }

    public function getIdentificador() {
        return $this->identificador;
    }

    public function setIdentificador($identificador) {
        $this->identificador = $identificador;
    }

    public function getPlataforma() {
        return $this->plataforma;
    }

    public function setPlataforma($plataforma) {
        $this->plataforma = $plataforma;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getFechaAlta() {
        return $this->fecha_alta;
    }

    public function setFechaAlta($fecha_alta) {
        $this->fecha_alta = $fecha_alta;
    }

    
    public function __construct( $id_pedido=null,$plataforma='MP',$identificador=null,$estado=null ) {
        
        $this->setIdPedido($id_pedido);
        $this->setPlataforma($plataforma);
        $this->setIdentificador($identificador);
        $this->setEstado($estado);
        $this->setFechaAlta(Ftl_DateTimeUtil::gmtToLocal('-3', 'Y-m-d H:i:s'));
        
    }
    
    public function guardar (){
        
        if ( !defined( 'TABLA_PAGOS_PEDIDOS' ) ){
            $r = new Ftl_Response();
            $r->state = -150;
            throw new Ftl_EcommerceException($r);
        }
        
        
        
        $datos = $this->_parametrosGuardar();
       
        try{
            
            if ( $this->getId() > 0 ){
                
                $res = Ftl_ClaseBase::getDB()->update( TABLA_PAGOS_PEDIDOS,$datos,Ftl_ClaseBase::getDB()->getEscapedQuery("id=:id",array("id"=>$this->getId())) );

                
            }else{

                $this->fecha_alta = Ftl_DateTimeUtil::getGMT('Y-m-d H:i:s');
                $res = Ftl_ClaseBase::getDB()->insert( TABLA_PAGOS_PEDIDOS,$datos );
                if ($res > 0)
                    $this->setId ($res);
                
            }
            

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

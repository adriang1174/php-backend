<?php
/**
 * Description of ClaseGenerica
 *
 * @author Luki
 */
class Ftl_ClaseBase {
    //put your code here

    const LOGIN_MAIL_DNI    = 1;
    const LOGIN_USR_CLAVE   = 2;
    const LOGIN_MAIL_CLAVE  = 3;
    const LOGIN_DNI_CLAVE   = 4;

    public $id;
    public $fecha_alta;
    public $estado;
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFechaAlta() {
        return $this->fecha_alta;
    }

    public function setFechaAlta($fecha_alta) {
        $this->fecha_alta = $fecha_alta;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    
    protected static $db;
    //protected static $tabla;

    public static function getDB ()
    {
        
        if (!self::$db)
            self::$db = Ftl_DB::getInstance();
        return self::$db;

    }


    public function  __construct()
    {
        $this->id = 0;
    }

    protected function _recuperar ( $tabla, $condicion, $campos="*" ){
        
        $consulta = "select $campos from " . $tabla . " where ";

        $a_condiciones = array();
        foreach ($condicion as $k => $v){
        
            array_push($a_condiciones, $k . " = " . self::getDB()->escape($v) . ""); 
            
        }
        
        $consulta .= implode(" and ",$a_condiciones);

        $rs = self::_getDatos($consulta);

        if ( $rs ) $this->mapear( $rs );
        
        
    }

    protected function _recuperarPorId ($tabla,$id)
    {
        $this->_recuperar($tabla, array('id'=>$id));
    }
    protected function _recuperarPorGuid ($tabla,$guid)
    {
        $this->_recuperar($tabla, array('guid'=>$guid));
    }

    protected function _recuperarPorLogin ($tabla,$campos="id",$params=array())
    {

        $opciones = array_merge (
                                    array   (
                                                "tipo" => self::LOGIN_MAIL_DNI,
                                                "datos"=> array (
                                                                    "email"     => "",
                                                                    "tipo_doc"  => "",
                                                                    "nro_doc"   => "",
                                                                    "usuario"   => "",
                                                                    "clave"     => ""
                                                                )
                                            ),
                                    $params
                                );

        $consulta = "SELECT $campos FROM $tabla WHERE ";

        
        
        
        switch ($opciones["tipo"])
        {
            case self::LOGIN_MAIL_DNI:
                $params = array("email" => $opciones["datos"]["email"],"nro_doc"=>$opciones["datos"]["nro_doc"]);
                if ( array_key_exists('tipo_doc', $opciones["datos"]) && $opciones["datos"]["tipo_doc"] != "" )
                        $params["tipo_doc"] = $opciones["datos"]["tipo_doc"];
                break;
            case self::LOGIN_USR_CLAVE:
                $params = array("usuario" => $opciones["datos"]["usuario"],"clave"=>$opciones["datos"]["clave"]);
                break;
            case self::LOGIN_MAIL_CLAVE:
                $params = array("email" => $opciones["datos"]["email"],"clave"=>$opciones["datos"]["clave"]);
                break;
            case self::LOGIN_DNI_CLAVE:
                $params = array("clave" => $opciones["datos"]["clave"],"nro_doc"=>$opciones["datos"]["nro_doc"]);
                if ( array_key_exists('tipo_doc', $opciones["datos"]) && $opciones["datos"]["tipo_doc"] != "" )
                        $params["tipo_doc"] = $opciones["datos"]["tipo_doc"];
                
                break;

        }

        $this->_recuperar($tabla, $params);

    }

    protected static function _getDatos($consulta)
    {
        $filas    = self::getDB()->fetchObject($consulta);
        self::getDB()->close();
        return $filas;
    }

    public function mapear($res)
    {
        foreach($this as $prop => $value)
        {
            if (isset($res->$prop))
            {
                $this->$prop = $res->$prop;
            }
        }
        
    }
    
    public function _guardar($tabla,$datos=array(),$closeConn=true){
        $respuesta = new Ftl_Response();
        $respuesta->state = 1;
        $data = array();
        try {
        
            
            self::$db = FTL_DB::getInstance();
            
            
            if (count($datos)>0){
                foreach($datos as $prop => $value)
                {
                    if (property_exists($this,$prop)){
                        $data[$prop] = $datos[$prop];
                        $this->$prop = $datos[$prop];
                    }
                        
                }            
                
            }else{
                foreach($this as $prop => $value)
                {
                    $data[$prop] = $value;
                }            
            }
            
            if ($this->id > 0)
            {
                $res = self::$db->update( DB_PREFIX.$tabla,$data,'id='.self::$db->escape($this->id) );
            }
            else
            {
                //$datos["fecha_alta"]    = $this->getFechaAlta();
                $res = self::$db->insert( DB_PREFIX.$tabla,$data );
                if ( $res )
                    $this->id = $res;
                
            }
            
            if ($closeConn)
                self::$db->close();            
            
        }catch(Exception $e) {
            if ($closeConn)
                self::$db->close();            
            throw $e;
        }
        

        
        return $respuesta;
          
        
    }

    
    
    public static function _obtenerListadoPaginado ($campos="*",$from,$pagina=1,$reg_x_pagina=50,$filtros=null,$orden=null)
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
        
	$res = self::getDB()->fetchAllAssoc($sql);
        $total = self::getDB()->getFoundRows();
        if ($res != null){
            $res[0]['total'] = $total;
        }
        self::getDB()->close();

        return $res;


    }

    protected static function _cambiarEstado ($tabla,$id,$estado,$guid=false)
    {
        $sqlWhere = self::getDB()->getEscapedQuery(($guid ? "guid = :id" : "id = :id"),array('id'=>$id));

        $res = self::getDB()->update ( $tabla, array("estado"=>$estado), $sqlWhere );

        self::getDB()->close();

        return $res;
    }

    protected static function _eliminar ($tabla,$id,$guid=false)
    {
        $res = null;

        $sqlWhere = self::getDB()->getEscapedQuery(($guid ? "guid = :id" : "id = :id"),array('id'=>$id));

        $res = self::getDB()->delete ( $tabla,$sqlWhere);

        self::getDB()->close();

        return $res;
    }




}
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Class_Invitacion extends Ftl_ClaseBase{

    const TABLE             = 'invitaciones';
    public $id_registrado;
    public $id_invitado;
    public $fid;
    public $request;
    
    public function  __construct($id=null,$guid=false)
    {
        parent::__construct();
        if ($guid){
            $this->_recuperar(DB_PREFIX.self::TABLE, array("md5(id)"=>$id));
        }else{
            $this->_recuperarPorId(DB_PREFIX.self::TABLE, $id);
        }
    }

    public function guardarRequest($id_registrado,$request,$fids){
        $respuesta = new Ftl_Response();
        $respuesta->state = 1;
        
        try{
            
            $aFids = explode(",",$fids);
            $respuesta->data = array("fids"=>$aFids);
            foreach($aFids as $fid){
               
                $this->_guardar(self::TABLE,array(
                    "id" => 0,
                    "id_registrado" => $id_registrado,
                    "fid" => $fid,
                    "fecha_alta" => Ftl_DateTimeUtil::gmtToLocal(TS_OFFSET,'Y-m-d H:i:s'),
                    "request" => $request,
                    "id_invitado" => 0
                ));
            }
        }catch(Exception $ex){
            $respuesta->state = -1;
            $respuesta->message = $ex->getMessage();
        }
        
        return $respuesta;
    }
    
    public function guardarInvitado($id_invitado,$request,$fid)
    {
        $respuesta = new Ftl_Response();
        $respuesta->state = 0;
        try {
            $resp = self::$db->update( 
                        DB_PREFIX.self::TABLE,
                        array("id_invitado"=>$id_invitado),
                        self::getDB()->getEscapedQuery("request = :request AND fid = :fid AND id_invitado = 0",array(
                            "request" => $request,
                            "fid" => $fid
                        )
            ));
            
            $respuesta->state = $resp;
            
        }

        catch(Exception $e) {
            
                $respuesta->state = -1;
                $respuesta->message = $e->getMessage();
            
        }

        return $respuesta;
    }    
    
    public static function getCantPorRegistrado($id_registrado){
        $cant = self::getDB()->count(DB_PREFIX.self::TABLE." i INNER JOIN ".DB_PREFIX.  Class_Foto::TABLE." f ON i.id_invitado = f.id_registrado",self::getDB()->getEscapedQuery("f.estado = 1 AND i.id_registrado = :id_registrado",array(
            "id_registrado" => $id_registrado
        )));
        self::getDB()->close();
        $cant = (int)$cant;    
        
        return $cant;            
    }
    
    public static function obtenerListado ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='fecha_alta DESC')
    {
        $campos = "*";
        $from   = DB_PREFIX . self::TABLE;
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }
   
}

?>

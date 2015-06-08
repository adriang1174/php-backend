<?php

class Ftl_Foto extends Ftl_ClaseBase{
    //put your code here
    const TABLE             = 'fotos';


    public function getIdRegistrado() {
        return $this->id_registrado;
    }

    public function setIdRegistrado($id_registrado) {
        $this->id_registrado = $id_registrado;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getSrc() {
        return $this->src;
    }

    public function setSrc($src) {
        $this->src = $src;
    }

    public function getPos() {
        return $this->pos;
    }

    public function setPos($pos) {
        $this->pos = $pos;
    }

    
    public function  __construct($id=null,$guid=false)
    {
        parent::__construct();
        if ($id){
            if ($guid)
            {
                $this->_recuperarPorGuid(DB_PREFIX . self::TABLE,$id);
            }
            else
            {
                $this->_recuperarPorId(DB_PREFIX . self::TABLE,$id);
            }
        }
    }
    public static function cambiarEstado ($id,$estado,$guid=false)
    {
        return parent::_cambiarEstado(DB_PREFIX . self::TABLE, $id, $estado, $guid);
    }

    public static function eliminar($id,$guid=false)
    {
        return parent::_eliminar(DB_PREFIX . self::TABLE, $id, $guid);
    }


    public static function obtenerListado ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden=null)
    {
        $campos = "f.*,f.estado 'f.estado', f.fecha_alta 'f.fecha_alta',r.nombre,r.apellido";
        $from   = DB_PREFIX . self::TABLE . ' f inner join ' . DB_PREFIX . 'registrados r on f.id_registrado = r.id';
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }
    
    
    public function guardar($cant=0)
    {
        $respuesta = new Ftl_Response();

        $res = null;

        //Si pongo un valor al parametro cant me fijo si la cantidad de fotos NO rechazadas subidas
        //por el usuario es menor que $cant
        if ($cant > 0){
            $checkCant = self::getDB()->count( DB_PREFIX.self::TABLE , self::getDB()->getEscapedQuery ( "id_registrado = :id_registrado and estado <> -1", array('id_registrado'=>$this->getIdRegistrado()) ) );
            if ($checkCant >= $cant){
                $respuesta->state = -2;
                $respuesta->message = "El usuario no puede subir mas de $cant fotos";
                return $respuesta;
            }
        }
        
        $datos = array (
            "id_registrado"         => $this->getIdRegistrado(),
            "titulo"                => $this->getTitulo(),
            "src"                   => $this->getSrc(),
            "pos"                   => $this->getPos(),
            "estado"                => 0
        );

        try {

            if ($this->getId() > 0) {
                $res = self::getDB()->update( DB_PREFIX.self::TABLE,$datos,'id='.self::getDB()->escape($this->getId()) );
            } else {

                $guid = self::getDB()->fetchVal("select uuid();");

                $datos["guid"]          = $guid;
                $datos["fecha_alta"]    = Ftl_DateTimeUtil::gmtToLocal('-3', 'Y-m-d H:i:s');
                $res = self::getDB()->insert( DB_PREFIX.self::TABLE,$datos );
                if ( $res ) {
                    $this->setId ( $res );
                    $this->setGuid($guid);
                }
                
            }

            $respuesta->state = 1;

        }catch (Exception $e) {
            $respuesta->state = -1;
            $respuesta->message = $e->getMessage();

        }

        self::getDB()->close();

        return $respuesta;

    }
    
}
?>

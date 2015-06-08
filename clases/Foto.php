<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Class_Foto extends Ftl_ClaseBase{

    const TABLE             = 'fotos';
    public $id_registrado;
    public $id_categoria;
    public $nombre;
    public $editada;
    public $pos;
    public $votos;
    public $id_usuario_ap;
    public $fecha_ap;
    public $ip;
    public $pais_ip;
    
    private static $categorias = array(
        "1" => "MAGENTA",
        "2" => "AMARILLO",
        "3" => "TURQUESA",
        "4" => "VIOLETA",
        "5" => "AZUL"
        
    );
    
    //private $guid;
    public static function getCategoria($id){
        return self::$categorias[$id];
    }
    public static function getCategorias(){
        return self::$categorias;
    }

    public function  __construct($id=null,$guid=false)
    {
     
        parent::__construct();
        if ($guid){
            $this->_recuperar(DB_PREFIX.self::TABLE, array("md5(id)"=>$id));
        }else{
            $this->_recuperarPorId(DB_PREFIX.self::TABLE, $id);
        }
    }

    public function guardar($datos=array())
    {
        
        $respuesta = new Ftl_Response();
        $respuesta->state = 0;
        try {
            $respuesta =  $this->_guardar(self::TABLE,$datos);
        }

        catch(Exception $e) {
            
                $respuesta->state = -1;
                $respuesta->message = $e->getMessage();
            
        }

        return $respuesta;

    }    
    
    
    public static function obtenerListado ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='fecha_alta DESC')
    {
        $campos = "
            f.id 'f.id',
            id_categoria,
            f.nombre foto_original,
            f.editada foto,
            md5(f.id) guid, 
            f.estado 'f.estado',
            f.votos,
            f.id_registrado,
            f.fecha_alta 'f.fecha_alta',
            r.nombre 'r.nombre',
            
            r.apellido 'r.apellido',
            r.fecha_nac,
            r.uid,
            r.nro_doc,
            r.tipo_doc,
            r.ip 'r.ip',
            r.pais_ip 'r.pais_ip',
            id_usuario_ap";
        $from   = DB_PREFIX . self::TABLE . " f inner join " . DB_PREFIX . "registrados r on f.id_registrado = r.id";
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }
    public static function obtenerRankingVotos ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='fecha_alta DESC')
    {
        
        $campos = "
            f.id 'f.id',
            id_categoria,
            f.editada foto,
            md5(f.id) guid, 
            f.estado 'f.estado',
            f.votos,
            f.id_registrado,
            f.fecha_alta 'f.fecha_alta',
            r.nombre 'r.nombre',
            r.apellido 'r.apellido',
            r.uid,
            r.nro_doc,
            r.tipo_doc";
        $from   = DB_PREFIX . self::TABLE . " f inner join " . DB_PREFIX . "registrados r on f.id_registrado = r.id";
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }
    public static function obtenerRankingInvitaciones ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='fecha_alta DESC')
    {
        
        $campos = "
            f.id 'f.id',
            id_categoria,
            f.editada foto,
            md5(f.id) guid, 
            f.estado 'f.estado',
            IFNULL(cant,0) cant,
            f.id_registrado,
            f.fecha_alta 'f.fecha_alta',
            r.nombre 'r.nombre',
            r.apellido 'r.apellido',
            r.uid,
            r.nro_doc,
            r.tipo_doc";
        $from   = DB_PREFIX . self::TABLE . " f inner join ".DB_PREFIX.Class_Registrado::TABLE." r on f.id_registrado = r.id ";
        $from.= "inner join (SELECT i.id_registrado,COUNT(f.id) cant
                FROM ".DB_PREFIX.  Class_Invitacion::TABLE." i INNER JOIN ".DB_PREFIX.self::TABLE." f ON i.id_invitado = f.id_registrado
                WHERE i.id_invitado > 0
                AND f.estado = 1
                GROUP BY i.id_registrado) aux ON f.id_registrado = aux.id_registrado";
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }
    public static function obtenerListadoGaleria ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='fecha_alta DESC')
    {
        $campos = "
            f.id,
            f.nombre foto_original,
            f.editada foto,
            md5(f.id) guid, 
            f.estado,
            f.votos,
            f.id_registrado,
            f.fecha_alta,
            r.nombre,
            r.apellido,
            r.uid,
            r.nro_doc";
        $from   = DB_PREFIX . self::TABLE . " f inner join " . DB_PREFIX . "registrados r on f.id_registrado = r.id";
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }

    public static function cambiarEstado ($id,$estado,$guid=false)
    {
        return parent::cambiarEstadoPorId(DB_PREFIX . self::TABLE, $id, $estado, $guid);
    }
    
    public function votadaPorUid($uid){
        $cant = self::getDB()->count(DB_PREFIX."votos",self::getDB()->getEscapedQuery("fbid = :uid and id_foto = :id_foto",array(
            "uid" => $uid,
            "id_foto" => $this->id
        )));
        self::getDB()->close();
        $cant = (int)$cant;    
        
        return ($cant > 0 ? true : false);        
    }
}

?>

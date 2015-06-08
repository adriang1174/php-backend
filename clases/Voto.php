<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Class_Voto extends Ftl_ClaseBase{

    const TABLE             = 'votos';
    public $id_foto;
    public $fbid;
    public $ip;
    public $pais_ip;


    
    
    public static function obtenerListado ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='fecha_alta DESC')
    {
        $campos = "
            v.id 'v.id',
            id_foto,
            f.nombre foto,
            f.estado 'f.estado',
            id_categoria,
            votos,
            fbid,
            v.ip 'v.ip',
            es_amigo,
            v.pais_ip 'v.pais_ip',
            r.uid,
            r.nro_doc,
            r.tipo_doc,            
            v.fecha_alta 'v.fecha_alta'";
        $from   = DB_PREFIX . self::TABLE . " v inner join " . DB_PREFIX . "fotos f on v.id_foto = f.id";
        $from   .= " inner join " . DB_PREFIX . "registrados r on r.id = f.id_registrado";
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }
    
}

?>

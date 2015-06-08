<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Class_Sorteo extends Ftl_ClaseBase{

    const TABLE             = 'sorteo';
    
    
    public static function obtenerListado ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='votos DESC')
    {
        $campos = "r.*,r.id as 'r.id',id_categoria";
       // $filtros = "f.estado = 1 AND r.estado = 1 AND f.votos >= 20";
        $from   = DB_PREFIX . self::TABLE . " s inner join " . DB_PREFIX . "registrados r on r.id = s.id_registrado";
        
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }
    
    public static function sortear(){

        $select = "
        (SELECT f.id_categoria,f.id,f.id_registrado,f.votos,r.uid FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id
        WHERE f.estado = 1 AND r.estado = 1 AND f.votos >= 20 AND f.id_categoria = 1 ORDER BY RAND() LIMIT 0,1)
        UNION ALL
        (SELECT f.id_categoria,f.id,f.id_registrado,f.votos,r.uid FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id
        WHERE f.estado = 1 AND r.estado = 1 AND f.votos >= 20 AND f.id_categoria = 2 ORDER BY RAND() LIMIT 0,1)
        UNION ALL
        (SELECT f.id_categoria,f.id,f.id_registrado,f.votos,r.uid FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id
        WHERE f.estado = 1 AND r.estado = 1 AND f.votos >= 20 AND f.id_categoria = 3 ORDER BY RAND() LIMIT 0,1)
        UNION ALL
        (SELECT f.id_categoria,f.id,f.id_registrado,f.votos,r.uid FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id
        WHERE f.estado = 1 AND r.estado = 1 AND f.votos >= 20 AND f.id_categoria = 4 ORDER BY RAND() LIMIT 0,1)
        UNION ALL
        (SELECT f.id_categoria,f.id,f.id_registrado,f.votos,r.uid FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id
        WHERE f.estado = 1 AND r.estado = 1 AND f.votos >= 20 AND f.id_categoria = 5 ORDER BY RAND() LIMIT 0,1)
        ";

        $result = self::getDB()->fetchAllAssoc($select);

        
        $delete = self::getDB()->delete(DB_PREFIX."sorteo");
        $datos = array();

        foreach($result as $k=>$v){
            $datos = array(
                "id_categoria" => $v['id_categoria'],
                "id_registrado" => $v['id_registrado']
            );
            $insert = self::getDB()->insert(DB_PREFIX."sorteo",$datos);    
        }

        
        

        self::getDB()->close();

        return true;
    }

    public static function ganadores (){
        
        $result = self::getDB()->fetchAllAssoc("select * from ".DB_PREFIX."sorteo s inner join ".DB_PREFIX."registrados r on s.id_registrado = r.id");
        self::getDB()->close();

        return $result;
    }

}

?>

<?php
class Class_Estadistica{

	public static function insertarPosteos($fbid){

		$db = Ftl_ClaseBase::getDB();

		$datos = array(
			"fbid" => $fbid,
			"fecha_alta" => Ftl_DateTimeUtil::gmtToLocal(TS_OFFSET,'Y-m-d H:i:s')
		);

		$resp = $db->insert("fb_posteos",$datos);
		$db->close();
		return $resp;


	}
	public static function insertShare($fbid,$channel){

		$db = Ftl_ClaseBase::getDB();

		$datos = array(
			"fbid" => $fbid,
			"tipo" => $channel,
			"fecha_alta" => Ftl_DateTimeUtil::gmtToLocal(TS_OFFSET,'Y-m-d H:i:s')
		);

		$resp = $db->insert("fb_shares",$datos);
		$db->close();
		return $resp;	

	}
	public static function getCantPosteos(){

		$db = Ftl_ClaseBase::getDB();

        $cant = $db->count(DB_PREFIX."posteos");

        $db->close();
        return $cant;   
	}	
    public static function obtenerListadoPosteos($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='fecha_alta DESC')
    {
        $campos = "
        	id,
            fbid,
            fecha_alta";
        $from   = DB_PREFIX . "posteos p";
        //$from   .= " inner join " . DB_PREFIX . "registrados r on r.id = f.id_registrado";
        return Ftl_ClaseBase::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }	
	public static function getCantRegistrados(){

		$db = Ftl_ClaseBase::getDB();

        $cant = $db->count(DB_PREFIX.Class_Registrado::TABLE,"estado = 1");

        $db->close();
        return $cant;   
	}	
	public static function getCantShares(){

		$db = Ftl_ClaseBase::getDB();

        $cant = $db->fetchAllAssoc("SELECT tipo,COUNT(*) cant
									FROM fb_shares
									GROUP BY tipo
									ORDER BY tipo
		");

        $db->close();
        return $cant;   
	}
    public static function obtenerListadoShares ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='fecha_alta DESC')
    {
        $campos = "
        	id,
            fbid,
			tipo,
            fecha_alta";
        $from   = DB_PREFIX . "shares s";
        //$from   .= " inner join " . DB_PREFIX . "registrados r on r.id = f.id_registrado";
        return Ftl_ClaseBase::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }	
	
	 public static function obtenerListadoCodes ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='date_submitted DESC')
    {
        $campos = "
        	code_id,
            user_id,
			name,
			last_name,
			prod,
            points,
			action,
			date_submitted";
        $from   = DB_PREFIX . "estad_codes";
        return Ftl_ClaseBase::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }	

	 public static function obtenerListadoUsers ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='user_id DESC')
    {
        $campos = "
            user_id,
			name,
			last_name,
			email,
            mobile,
			company
			";
        $from   = DB_PREFIX . "users";
        return Ftl_ClaseBase::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }	

	public static function obtenerListadoRanking ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='nro DESC')
    {
        $orden = 'nro DESC,rank asc';
		$campos = "
			nro,
            user_id,
			name,
			last_name,
            points,
			rank";
        $from   = DB_PREFIX . "estad_rank";
        return Ftl_ClaseBase::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }	
	
	public static function obtenerListadoEmpaques ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='cant DESC')
    {
        $orden = 'cant DESC';
		$campos = "
			nro,
            prod,
			cant";
        $from   = DB_PREFIX . "estad_packs";
        return Ftl_ClaseBase::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }
	
	public static function getCantVotos(){

		$db = Ftl_ClaseBase::getDB();

        $cant = $db->fetchAllAssoc("
        	SELECT f.id_categoria,COUNT(*) cant
			FROM fb_votos v INNER JOIN fb_fotos f ON v.id_foto = f.id
			WHERE f.estado = 1
			GROUP BY f.id_categoria
			ORDER BY f.id_categoria
		");

        $db->close();
        return $cant;   
	}
	public static function getCantFotos(){

		$db = Ftl_ClaseBase::getDB();

        $cant = $db->fetchAllAssoc("
        	SELECT f.id_categoria,COUNT(*) cant
			FROM fb_fotos f
			WHERE f.estado = 1
			GROUP BY f.id_categoria
			ORDER BY f.id_categoria
		");

        $db->close();
        return $cant;   
	}
}
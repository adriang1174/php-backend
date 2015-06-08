<?php

class Class_Registrado extends Ftl_Registrado{

    public $token;
    public $real_uid;
    public $titular;
    public $empresa;
    public $marca;
    public $recibir;
    public $ip;
    public $pais_ip;

    

    public function  __construct($id=null,$uid=false)
    {
        
        if ( $uid ){
            parent::__construct();
            parent::_recuperar(DB_PREFIX.self::TABLE, array("uid"=>$id));
        }else{
            parent::__construct($id,false);
        }
        
    }

    public function getCantInvitados()
    {
        return Class_Invitacion::getCantPorRegistrado($this->id);
    }
    
    public function getCantFotos()
    {
        $cant = self::getDB()->count(DB_PREFIX.Class_Foto::TABLE,self::getDB()->getEscapedQuery("id_registrado = :id_registrado and estado in (0,1)",array(
            "id_registrado" => $this->id
        )));
        self::getDB()->close();
        return $cant;        
    }
    
    public function getPosRankingVotos(){
        
        
        $aPosRanking = self::getDB()->fetchObject(self::getDB()->getEscapedQuery("
            SELECT votos,POSITION AS rank FROM (SELECT id_registrado,votos,@i:=@i+1 AS POSITION FROM (
            SELECT  id_registrado,votos,id_categoria
            FROM ".DB_PREFIX.Class_Foto::TABLE." f 
            WHERE id_categoria = (SELECT id_categoria FROM ".DB_PREFIX.Class_Foto::TABLE." WHERE id_registrado = {$this->id} and estado = 1)
            AND f.estado = 1
            ORDER BY votos DESC,fecha_alta ASC) ranking ,(SELECT @i:=0) r) ranking_user WHERE id_registrado = {$this->id}"
        ));
        self::getDB()->close();

        return $aPosRanking;
        
    }
    public function getPosRankingAmigos(){
        $aPosRanking = self::getDB()->fetchObject(self::getDB()->getEscapedQuery("
            SELECT id,cant,POSITION AS rank FROM (SELECT id,cant,@i:=@i+1 AS POSITION FROM (
            SELECT r.id_registrado as id,IFNULL(cant,0) cant,r.fecha_alta 
            FROM ".DB_PREFIX.Class_Foto::TABLE." r LEFT JOIN (
            SELECT i.id_registrado,COUNT(f.id) cant
            FROM ".DB_PREFIX.Class_Invitacion::TABLE." i INNER JOIN ".DB_PREFIX.Class_Foto::TABLE." f ON i.id_invitado = f.id_registrado
            WHERE i.id_invitado > 0
            AND f.estado = 1
            GROUP BY i.id_registrado) aux ON r.id_registrado = aux.id_registrado
            WHERE r.estado = 1 AND id_categoria = (SELECT id_categoria FROM ".DB_PREFIX.Class_Foto::TABLE." WHERE id_registrado = {$this->id} and estado = 1)
            ORDER BY IFNULL(cant,0) DESC,r.fecha_alta ASC
            ) ranking ,(SELECT @i:=0) r) ranking_user WHERE id={$this->id}"            
        ));

        self::getDB()->close();
        return $aPosRanking;
    }
    
    public function getEstadoFotoSubida()
    {
        
        //Obtengo la ultima foto cargada (puede tener otras fotos rechazadas)
        $foto = self::getDB()->fetchObject(self::getDB()->getEscapedQuery("select md5(id) as guid,(CASE WHEN estado < 1 THEN nombre ELSE editada END) AS nombre,estado,votos,id_categoria from ".DB_PREFIX.Class_Foto::TABLE." where id_registrado = :id_registrado order by id DESC limit 0,1",array(
            "id_registrado" => $this->id
        )));

        self::getDB()->close();

        return $foto;        
    }

    
    
    
    
    
    
    
    public function guardar($datos=array())
    {
        
        $respuesta = new Ftl_Response();
        $respuesta->state = 0;
        try {
            $respuesta =  $this->_guardar(self::TABLE,$datos);
        }

        catch(Exception $e) {
            
            if ($e->getCode() == 23000){
                $respuesta->state = -2;
                $respuesta->message = "Los datos ingresados corresponden a otro usuario registrado";
            }else{
                $respuesta->state = -1;
                $respuesta->message = $e->getMessage();
                
            }
            
        }

        return $respuesta;

    }

    public static function obtenerListado ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden=null)
    {

        $campos = "*";
        //$from   = DB_PREFIX . self::TABLE . " r INNER JOIN " . DB_PREFIX . "registrados_provincias p ON r.dom_id_provincia = p.id ";
        $from   = DB_PREFIX . self::TABLE;
        
        
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }    

    public static function obtenerMasVotadosCategorias(){
        $respuesta = new Ftl_Response();
        $db = Ftl_ClaseBase::getDB();
        try{
            $respuesta->state = 1;
            $respuesta->data = array();
            $ranking = $db->fetchAllAssoc("(SELECT f.id,MD5(f.id) guid,id_categoria,id_registrado,editada AS nombre,votos,f.estado FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id  WHERE id_categoria = 1 AND f.estado = 1 AND r.estado = 1 ORDER BY votos DESC, f.fecha_alta ASC LIMIT 0,1)
                                            UNION
                                            (SELECT f.id,MD5(f.id) guid,id_categoria,id_registrado,editada AS nombre,votos,f.estado FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id  WHERE id_categoria = 2 AND f.estado = 1 AND r.estado = 1 ORDER BY votos DESC, f.fecha_alta ASC LIMIT 0,1)
                                            UNION
                                            (SELECT f.id,MD5(f.id) guid,id_categoria,id_registrado,editada AS nombre,votos,f.estado FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id  WHERE id_categoria = 3 AND f.estado = 1 AND r.estado = 1 ORDER BY votos DESC, f.fecha_alta ASC LIMIT 0,1)
                                            UNION
                                            (SELECT f.id,MD5(f.id) guid,id_categoria,id_registrado,editada AS nombre,votos,f.estado FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id  WHERE id_categoria = 4 AND f.estado = 1 AND r.estado = 1 ORDER BY votos DESC, f.fecha_alta ASC LIMIT 0,1)
                                            UNION
                                            (SELECT f.id,MD5(f.id) guid,id_categoria,id_registrado,editada AS nombre,votos,f.estado FROM fb_fotos f INNER JOIN fb_registrados r ON f.id_registrado = r.id  WHERE id_categoria = 5 AND f.estado = 1 AND r.estado = 1 ORDER BY votos DESC, f.fecha_alta ASC LIMIT 0,1)
            ");
            
            if ($ranking){
                foreach($ranking as $k => $row){
                    $respuesta->data[$row["id_categoria"]] = $row;
                }
            }
            
            $db->close();
            
        }catch(Exception $e){
            $respuesta->state = -1;
            $respuesta->message = $e->getMessage();
        }
        return $respuesta;
    }
    
    
    
    public function yaVoto(){
        $cant = self::getDB()->count(DB_PREFIX.Class_Voto::TABLE,self::getDB()->getEscapedQuery("id_registrado = :id_registrado",array(
            "id_registrado" => $this->id
        )));
        self::getDB()->close();
        $cant = (int)$cant;    
        
        return ($cant > 0 ? 1 : 0);
    }    
    public static function yaVotoIP($ip,$id_foto){
        $cant = self::getDB()->count(DB_PREFIX.Class_Voto::TABLE,self::getDB()->getEscapedQuery("id_foto = :id_foto and ip = :ip",array(
            "id_foto" => $id_foto,
            "ip" => $ip
        )));
        self::getDB()->close();
        $cant = (int)$cant;    
        
        return ($cant > 0 ? true : false);
    }    
    
    
    public function guardarFoto($foto)
    {
        $respuesta = new Ftl_Response();
        $respuesta->state = 0;

        try{
            $res = self::getDB()->insert( DB_PREFIX."votos",$datos );
        }
        catch(Ftl_DB_DataBaseException $e)
        {
            throw new Ftl_DB_DataBaseException($e->getMessage(),$e->getCode());
        }
        catch(Exception $e){
            $respuesta->state == -1;
            $respuesta->message = $e->getMessage();
        }

        return $respuesta;
    }

    public static function votarFoto($fbid,$idFoto,$ip,$amigo=false,$pais="")
    {
        $respuesta = new Ftl_Response();
        $respuesta->state = 0;

        try{
            
            //if (!self::yaVotoIP($ip, $idFoto)){
                $datos = array(
                    "fbid" => $fbid,
                    "id_foto" => $idFoto,
                    "ip"=> $ip,
                    "es_amigo" => ($amigo == true ? 1 : 0),
                    "pais_ip" => $pais,
                    "fecha_alta" => Ftl_DateTimeUtil::gmtToLocal(TS_OFFSET,'Y-m-d H:i:s')
                );
                $respuesta->state = self::getDB()->insert( DB_PREFIX."votos",$datos );
                
            /*}else{
                $respuesta->state = -2;
                $respuesta->message = "Solo podés votar una vez la misma foto con la misma IP.";                
            } */           
            
        }
        catch(Exception $e) {
            
            if ($e->getCode() == 23000){
                $respuesta->state = -2;
                $respuesta->message = "Solo podés votar una sola vez la misma foto.";
            }else{
                $respuesta->state = -1;
                $respuesta->message = $e->getMessage();
                
            }
            
        }

        return $respuesta;
    }




}
?>

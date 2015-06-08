<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioBO
 *
 * @author Luki
 */
class Ftl_UsuarioBO extends Ftl_ClaseBase {

    const SESSION_KEY       = 'admin';
    const TABLE             = 'usuarios_bo';

    protected $usuario;
    protected $clave;
    protected $nombre;
    protected $apellido;
    protected $email;
    protected $id_perfil;

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getClave() {
        return $this->clave;
    }

    public function setClave($clave) {
        $this->clave = $clave;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getIdPerfil() {
        return $this->id_perfil;
    }

    public function setIdPerfil($idPerfil) {
        $this->id_perfil = $idPerfil;
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

    public static function login ($params)
    {
        $respuesta = new Ftl_Response();
        $respuesta->state = 0;

        $oUsuario = new Ftl_UsuarioBO();
        $oUsuario->_recuperarPorLogin(DB_PREFIX . self::TABLE, "id,usuario,estado", $params);

        if ($oUsuario->getId() > 0)
        {

            if ( $oUsuario->getEstado() < 1 )
            {
                $respuesta->state = -2;
                $respuesta->message = "Tu cuenta fue desactivada.";
            }
            else
            {
                $respuesta->state = 1;
                $respuesta->data = $oUsuario;
            }
        }

        return $respuesta;

    }

    public static function obtenerListado ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden='fecha_alta DESC')
    {
        $campos = "*";
        $from   = DB_PREFIX . self::TABLE;
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }

    public static function cambiarEstado ($id,$estado,$guid=false)
    {
        return parent::_cambiarEstado(DB_PREFIX . self::TABLE, $id, $estado, $guid);
    }

    public static function eliminar($id,$guid=false)
    {
        return parent::_eliminar(DB_PREFIX . self::TABLE, $id, $guid);
    }

    public function guardar()
    {
        $respuesta = new Ftl_Response();

        $res = null;

        //Me fijo si no hay otro usuario con igual usuario o emai (en caso de haberlo ingresado)
        
        $existe = self::getDB()->count( DB_PREFIX.self::TABLE , self::getDB()->getEscapedQuery ( "id <> :id AND usuario = :usuario", array('id'=>$this->getId(),'usuario'=>$this->getUsuario()) ) );

        if ( $existe )
        {
            $respuesta->state = -2;
            $respuesta->message = "El nombre de usuario ya está siendo utilizado.";
            return $respuesta;
        }

        if ( $this->getEmail() )
        {
            $existe = self::getDB()->count( DB_PREFIX.self::TABLE , self::getDB()->getEscapedQuery ( "id <> :id AND email = :email", array('id'=>$this->getId(),'email'=>$this->getEmail()) ) );

            if ( $existe )
            {
                $respuesta->state = -3;
                $respuesta->message = "El email ya está siendo utilizado por otro usuario.";
                return $respuesta;
            }

        }

        $datos = array (

            "nombre"                => $this->getNombre(),
            "apellido"              => $this->getApellido(),
            "usuario"               => $this->getUsuario(),
            "email"                 => $this->getEmail(),
            "estado"                => $this->getEstado(),
            "id_perfil"             => $this->getIdPerfil()

        );

        try {

            if ($this->getId() > 0)
            {
                $res = self::getDB()->update( DB_PREFIX.self::TABLE,$datos,'id='.self::getDB()->escape($this->getId()) );
            }
            else
            {
                $datos["clave"]         = md5($this->getClave());
                $datos["fecha_alta"]    = $this->getFechaAlta();
                $res = self::getDB()->insert( DB_PREFIX.self::TABLE,$datos );

                if ( $res ) $this->setId ( $res );
            }

            $respuesta->state = 1;

        }catch (Exception $e) {
            $respuesta->state = -1;
            $respuesta->message = $e->getMessage();

        }

        self::getDB()->close();

        return $respuesta;




    }
    public function cambiarClave()
    {
        $respuesta = new Ftl_Response();

        $res = null;
        //self::$db = FTL_DB::getInstance();


        $datos = array (

            "clave"     => md5($this->getClave())

        );

        try
        {

                $res = self::getDB()->update( DB_PREFIX.self::TABLE,$datos,'id='.self::getDB()->escape($this->getId()) );
                $respuesta->state = 1;

        }catch (Exception $e)
        {

                $respuesta->state = -1;
                $respuesta->message = $e->getMessage();

        }

        self::getDB()->close();

        return $respuesta;

    }
}
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Registrado
 *
 * @author Luki
 */
//require_once '../frm/init.php';
class Ftl_Registrado extends Ftl_ClaseBase{
    //put your code here
    const TABLE             = 'registrados';
    const LOGIN_MAIL_DNI    = 1;
    const LOGIN_USR_CLAVE   = 2;
    const LOGIN_MAIL_CLAVE  = 3;
    const LOGIN_DNI_CLAVE   = 4;


    //private $id = 0;
    public $uid;
    public $usuario;
    public $clave;
    public $nombre;
    public $apellido;
    public $tipo_doc;
    public $nro_doc;
    public $email;
    public $sexo;
    public $fecha_nac;
    public $dom_calle;
    public $dom_nro;
    public $dom_piso;
    public $dom_depto;
    public $dom_cp;
    public $dom_id_localidad;
    public $dom_localidad;
    public $dom_id_provincia;
    public $dom_provincia;
    public $dom_id_pais;
    public $dom_pais;
    public $dom_cod_area_telefono;
    public $dom_nro_telefono;
    public $cod_area_celular;
    public $nro_celular;
//    protected $estado;
//    protected $fecha_ult_modificacion;
//    protected $fecha_alta;
    protected $foto;


    public function getUid() {
        return $this->uid;
    }

    public function setUid($uid) {
        $this->uid = $uid;
    }

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

    public function getTipoDoc() {
        return $this->tipo_doc;
    }

    public function setTipoDoc($tipo_doc) {
        $this->tipo_doc = $tipo_doc;
    }

    public function getNroDoc() {
        return $this->nro_doc;
    }

    public function setNroDoc($nro_doc) {
        $this->nro_doc = $nro_doc;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getFechaNac() {
        return $this->fecha_nac;
    }

    public function setFechaNac($fecha_nac) {
        $this->fecha_nac = $fecha_nac;
    }

    public function getCalle() {
        return $this->dom_calle;
    }

    public function setCalle($dom_calle) {
        $this->dom_calle = $dom_calle;
    }

    public function getNroCalle() {
        return $this->dom_nro;
    }

    public function setNroCalle($dom_nro) {
        $this->dom_nro = $dom_nro;
    }

    public function getPiso() {
        return $this->dom_piso;
    }

    public function setPiso($dom_piso) {
        $this->dom_piso = $dom_piso;
    }

    public function getDepto() {
        return $this->dom_depto;
    }

    public function setDepto($dom_depto) {
        $this->dom_depto = $dom_depto;
    }

    public function getCp() {
        return $this->dom_cp;
    }

    public function setCp($dom_cp) {
        $this->dom_cp = $dom_cp;
    }

    public function getIdLocalidad() {
        return $this->dom_id_localidad;
    }

    public function setIdLocalidad($dom_id_localidad) {
        $this->dom_id_localidad = $dom_id_localidad;
    }

    public function getLocalidad() {
        return $this->dom_localidad;
    }

    public function setLocalidad($dom_localidad) {
        $this->dom_localidad = $dom_localidad;
    }

    public function getIdProvincia() {
        return $this->dom_id_provincia;
    }

    public function setIdProvincia($dom_id_provincia) {
        $this->dom_id_provincia = $dom_id_provincia;
    }

    public function getProvincia() {
        return $this->dom_provincia;
    }

    public function setProvincia($dom_provincia) {
        $this->dom_provincia = $dom_provincia;
    }

    public function getIdPais() {
        return $this->dom_id_pais;
    }

    public function setIdPais($dom_id_pais) {
        $this->dom_id_pais = $dom_id_pais;
    }

    public function getPais() {
        return $this->dom_pais;
    }

    public function setPais($dom_pais) {
        $this->dom_pais = $dom_pais;
    }

    public function getCodAreaTelefono() {
        return $this->dom_cod_area_telefono;
    }

    public function setCodAreaTelefono($dom_cod_area_telefono) {
        $this->dom_cod_area_telefono = $dom_cod_area_telefono;
    }

    public function getNroTelefono() {
        return $this->dom_nro_telefono;
    }

    public function setNroTelefono($dom_nro_telefono) {
        $this->dom_nro_telefono = $dom_nro_telefono;
    }

    public function getCodAreaCelular() {
        return $this->cod_area_celular;
    }

    public function setCodAreaCelular($cod_area_celular) {
        $this->cod_area_celular = $cod_area_celular;
    }

    public function getNroCelular() {
        return $this->nro_celular;
    }

    public function setNroCelular($nro_celular) {
        $this->nro_celular = $nro_celular;
    }


    public function getFoto() {
        return $this->foto;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
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

        $oUsuario = new Ftl_Registrado();
        $oUsuario->_recuperarPorLogin(DB_PREFIX . self::TABLE, "id,estado", $params);

        return $oUsuario;

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
        $campos = "*, r.nombre";
        $from   = DB_PREFIX . self::TABLE . " r" ;
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
    }

    public function guardar()
    {
        
        $res = null;
        self::$db = FTL_DB::getInstance();

        $datos = array (

            "foto"                  => $this->getFoto(),
            "nombre"                => $this->getNombre(),
            "apellido"              => $this->getApellido(),
            "usuario"               => $this->getUsuario(),
            "clave"                 => $this->getClave(),
            "tipo_doc"              => $this->getTipoDoc(),
            "nro_doc"               => $this->getNroDoc(),
            "email"                 => $this->getEmail(),
            "sexo"                  => $this->getSexo(),
            "fecha_nac"             => $this->getFechaNac(),
            "dom_calle"             => $this->getCalle(),
            "dom_nro"               => $this->getNroCalle(),
            "dom_piso"              => $this->getPiso(),
            "dom_depto"             => $this->getDepto(),
            "dom_cp"                => $this->getCp(),
            "dom_localidad"         => $this->getLocalidad(),
            "dom_id_localidad"      => $this->getIdLocalidad(),
            "dom_provincia"         => $this->getProvincia(),
            "dom_id_provincia"      => $this->getIdProvincia(),
            "dom_pais"              => $this->getIdPais(),
            "dom_id_pais"           => $this->getIdPais(),
            "dom_cod_area_telefono" => $this->getCodAreaTelefono(),
            "dom_nro_telefono"      => $this->getNroTelefono(),
            "cod_area_celular"      => $this->getCodAreaCelular(),
            "nro_celular"           => $this->getNroCelular(),
            "estado"                => $this->getEstado(),
            "fecha_ult_modificacion"=> date("Y-m-d H:i:s")
        );

        if ($this->getId() > 0)
        {
            $res = self::$db->update( DB_PREFIX.'registrados',$datos,'id='.self::$db->escape($this->getId()) );
        }
        else
        {
            $datos["fecha_alta"]    = $this->getFechaAlta();
            $res = self::$db->insert( DB_PREFIX.'registrados',$datos );
            if ( $res )
                $this->setId ( $res );
        }
         

        self::$db->close();

        return $res;




    }

}
?>

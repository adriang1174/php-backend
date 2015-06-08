<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__) .'/IDataBase.php';
class Ftl_DataBase implements Ftl_IDataBase
{

    protected $link;
    protected $resource;
    protected static $instance = null;

    //Variables de servidor de BBDD
    protected   $host;
    protected   $username;
    protected   $password;
    protected   $base;

    //Tipos de datos de resultados

    const       TYPE_ASSOC      = 0;
    const       TYPE_NUMERIC    = 1;
    const       TYPE_OBJECT     = 2;

    protected function __construct($pHost,$pUserName,$pPassword,$pBase)
    {
        $this->host     = $pHost;
        $this->username = $pUserName;
        $this->password = $pPassword;
        $this->base     = $pBase;
    }
    
    public function connect(){}
    public function close(){}

    public function query($sql, $data = array()){}
    public function execute(){}
    public function insert($table,$data,$returnId=true){}
    public function update($table,$where,$data,$in=false){}
    public function delete($table,$where,$data,$in=false){}

    //Funciones de agregado
    public function max($column,$table,$where){}
    public function count($column,$table,$where){}
    public function sum($column,$table,$where){}



    //Metodos de la clase

    protected function safeSql($str){
        if(get_magic_quotes_gpc())
            $str = stripcslashes ($str);

        $search=array("\\","\0","\n","\r","\x1a","'",'"', ';');
        $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"', '\;');
        $str = str_ireplace($search,$replace,$str);
        return $str;
    }
    protected function unsafeSql($str){
        $search=array("\\","\0","\n","\r","\x1a","'",'"', ';');
        $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"', '\;');
        $str = str_ireplace($search,$replace,$str);
        return $str;
    }


    //Limpieza de datos para sql
    public function scape($data)
    {
            if( !is_array( $data ) ) {

                return $this->safeSql($data);

            }

            $ret = array();
            foreach( $data as $k => $v ) {
                    $ret[ $this->safeSql($k) ] = $this->safeSql( $v );
            }

            return $ret;
    }
    public function unscape($data)
    {
            if( !is_array( $data ) ) {

                return $this->unsafeSql($data);

            }

            $ret = array();
            foreach( $data as $k => $v ) {
                    $ret[ $this->unsafeSql($k) ] = $this->unsafeSql( $v );
            }

            return $ret;
    }



}

?>

<?php

class Ftl_MySql extends Ftl_IDataBase {

    protected $_charset         = DB_CHARSET;  //Charset de la coneccion

    public function  __construct(){}

    /*
     * Funcion: getInstance
     */
    public static function getInstance(){
        if( self::$_instance == null )
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /*
     * Funcion: connect
     * Desc:    Establece la conexión con la base de datos.
     */
    public function connect()
    {
        try
        {

            if( is_resource( $this->_link ) )
            {
                    return true;
            }

            $this->_link = @mysql_connect( DB_HOST, DB_USER, DB_PASS );

            if( !is_resource( $this->_link ) ) {
                throw new Ftl_DB_DataBaseException( "No se pudo realizar la conección con la BBDD", -2 );
            } else {
                //echo "BD=>connect: " . Ftl_DateTimeUtil::getGMT();
            }

            if( !@mysql_select_db( DB_BASE ) )
            {
                throw new Ftl_DB_DataBaseException( @mysql_error(), @mysql_errno() );
            }

            if ($this->_charset)
            {
                $this->setCharset($this->_charset);
            }
            
            return true;
        }
        catch (Exception $e)
        {
            throw new Ftl_DB_DataBaseException( $e->getMessage(), $e->getCode() );
        }

    }

    /*
     * Funcion: close
     * Desc:    Cierra la conexión con la base de datos.
     */
    public function close()
    {
        try
        {
            if ( !is_resource($this->_link) )
            {
                return true;
            }

            if ( !@mysql_close($this->_link) )
            {
                throw new Ftl_DB_DataBaseException(@mysql_error($this->_link), @mysql_errno($this->_link));
            }
            else
            {
                //echo "BD=>close: " . Ftl_DateTimeUtil::getGMT('d/m/Y H:i:s');
                $this->_link = null;
                $this->_inTransaction = false;
            }

            return true;

        }
        catch(Exception $e)
        {
            throw new Ftl_DB_DataBaseException( $e->getMessage(), $e->getCode() );
        }

    }


   /*TRANSACTIONS*/

    /*
     * Funcion: beginTransaction
     * Desc:    Inicializa una transaccion
     */
    public function beginTransaction()
    {

        if ( !$this->connect() ){
            return false;
        }

        if ( $this->inTransaction() ) {
            return true;
        }

        $this->_inTransaction = mysql_query("START TRANSACTION", $this->_link) && mysql_query("BEGIN", $this->_link);

        return $this->inTransaction();

    }

    /*
     * Funcion: commit
     * Desc:    Commitea la transaccion en curso
     */
    public function commit() {

        $this->_inTransaction = !mysql_query("COMMIT", $this->_link);

    }

     /*
     * Funcion: rollback
     * Desc:    vuelve atras la ejecucion de una transaccion
     */
    public function rollback() {
        
        $this->_inTransaction = !mysql_query("ROLLBACK", $this->_link);
    }

    public function inTransaction()
    {
        return $this->_inTransaction;

    }

    /*QUERY & EXECUTE*/

    public function query( $sql, $data = array() )
    {
        try{


            //Si me llega $sql vacio o no puedo realizar la conexion, retorno false
            if ( $sql == "" || !$this->connect() )
            {
                return false;
            }

            $this->flush();

            //Si me llegan datos en $data, los escapeo y los reemplazo en el $sql
            $sql = $this->getEscapedQuery( $sql, $data );
            //echo $sql;
            
            $this->_resource        = @mysql_query( $sql );

            //Si hubo algun error lo guardo en el log de errores de DB y corto la ejecucion
            if ( ! $this->_resource  )
            {
                throw new Ftl_DB_DataBaseException(mysql_error($this->_link), mysql_errno($this->_link));
            }

            $this->_affectedRows    = @mysql_affected_rows($this->_link);

            if (mysql_errno($this->_link))
            {
                throw new Ftl_DB_DataBaseException(mysql_error($this->_link), mysql_errno($this->_link));
            }


            return $this->_resource;

        }catch(Exception $e){

            $this->_resource = null;
            $this->_affectedRows = null;
            throw new Ftl_DB_DataBaseException($e->getMessage(), $e->getCode());

        }

    }
    public function execute( $sql, $data = array() ) {

        return $this->query($sql,$data);

    }

//
    /*PREPARED STATEMENT*/
    public function executePreparedStatement($statement,$data=array()){}




    /*
     * Funcion: fetchAllMode
     * Desc:    Retorna todas las filas del resultado de la consulta $sql segun el modo $mode
     *          $mode: Ftl_DB::FETCH_ASSOC, Ftl_DB::FETCH_NUM, Ftl_DB::FETCH_OBJECT
     */
    protected function fetchAllMode    ( $sql=null, $data=null, $mode = Ftl_DB::FETCH_ASSOC ){

        if (  isset( $sql )  )
        {
                $this->query( $sql, $data );
        }

        if ( is_null ( $this->_resource ) ){
                return array();
        }

        $data = array();

        switch ( $mode )
        {
            case Ftl_DB::FETCH_ASSOC:

                while ($fila = mysql_fetch_assoc( $this->_resource )) {
                    array_push($data, $fila);
                }

                break;
            case Ftl_DB::FETCH_NUM:
                
                while ($fila = mysql_fetch_row( $this->_resource )) {
                    array_push($data, $fila);
                }

                break;
            case Ftl_DB::FETCH_OBJECT:

                while ($fila = mysql_fetch_object( $this->_resource )) {
                    array_push($data, $fila);
                }
                break;
        }


        return $data;



    }

    /*
     * Funcion: fetchMode
     * Desc:    Retorna una filas del resultado de la consulta $sql segun el modo $mode
     *          $mode: Ftl_DB::FETCH_ASSOC, Ftl_DB::FETCH_NUM, Ftl_DB::FETCH_OBJECT
     */
    protected function fetchMode       ( $sql=null, $data=null, $mode = Ftl_DB::FETCH_ASSOC, $col=-1 ) {

        if (  isset( $sql )  )
        {
                $this->query( $sql, $data );
        }

        if ( !is_resource($this->_resource ) ){
                return array();
        }

        $data = array();

        switch ( $mode )
        {
            case Ftl_DB::FETCH_ASSOC:

                $data = mysql_fetch_assoc( $this->_resource );

                break;

            case Ftl_DB::FETCH_NUM:

                if ($col > -1) {
                    $data = mysql_fetch_row( $this->_resource );
                    $data = $data[$col];
                } else
                    $data = mysql_fetch_row( $this->_resource );

                break;

            case Ftl_DB::FETCH_OBJECT:

                $data = mysql_fetch_object( $this->_resource );

                break;

        }

        return $data;

    }

    /*
     * Funcion: getLastInsertId
     * Desc:    Obtiene el id generado por una consulta de insert
     */
    public function getLastInsertId() {

        return mysql_insert_id( $this->_link );

    }


    

    /*CLEAN AND ESCAPE*/
    public function escape( $data = null )
    {

            if ( is_null( $data ) )
            {
                return 'null';
            }

            if( !is_array( $data ) )
            {

                    if( !$this->connect()  ) {
                            return $data;
                    }

                    if ( is_bool($data) )
                    {
                        return ($data === true ? "'1'" : "'0'");
                    }
                    else if ( is_int( $data ) )
                    {
                        return mysql_real_escape_string( $data , $this->_link );
                    }
                    else if (is_string( $data ))
                    {
                        if (Ftl_StringUtil::startsWith($data, Ftl_DB::FN_IDENTIFIER))
                            return mysql_real_escape_string(str_ireplace (Ftl_DB::FN_IDENTIFIER, "", $data), $this->_link );
                        else{
                            return "'" . mysql_real_escape_string( $data , $this->_link ) . "'";
                        }
                    }
                    else
                    {
                        return "'" . mysql_real_escape_string( $data , $this->_link ) . "'";
                    }

            }

            $ret = array();
            foreach( $data as $k => $v ) {
                    $ret[ $k ] = $this->escape( $v );
            }

            return $ret;

    }
    
    public function setCharset($_charset) {
        $this->_charset = $_charset;
        
        if (is_resource($this->_link))
        {
            
            if (!function_exists('mysql_set_charset'))
            {

                mysql_query("set names {$this->_charset}",$this->_link);
            }
            else
            {
                mysql_set_charset($this->_charset, $this->_link);
            }
        }
    }

    private function flush()
    {
        //$this->_lastResults     = null;
        if (is_resource( $this->_resource ) )
                mysql_free_result ( $this->_resource );
        $this->_affectedRows    = null;
        $this->_resource        = null;
    }
}
?>

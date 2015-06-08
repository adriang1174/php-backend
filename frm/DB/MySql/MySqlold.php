<?php

class Ftl_MySql 
{




    // <editor-fold defaultstate="collapsed" desc="Propiedades">
    private $_link;
    private $_resource;
    private $_params;

    private $_isOpenTransaction = false;

    
    private $_colsInfo      = null; //Información de las columnas de un resultado
    private $_rowsAffected  = null; //Nro de filas afectadas por INSERT/UPDATE/DELETE
    private $_rowsReturned  = null; //Nro de filas retornadas por SELECT
    private $_lastInsertId  = null; //Ultimo id generado por INSERT
    private $_lastQuery     = null; //Guardo la ultima consulta
    private $_lastResults   = null; //Guardo los ultimos resultados.
    private $_cacheDir      = null; //Dir donde guardo el cache de las consultas
    private $_cacheQueries  = false;//Indico si guardo la consulta (seleccion) en cache
    private $_cacheInserts  = false;//Indico si guardo la consulta (insert) en cache
    private $_useDiskCache  = false;//Indico si voy a usar un espacio de disco para guardar consultas en cache
    private $_fromDiskCache = false;//Indico si obtuve los resultados desde cache.
    private $_cacheTimeout  = 24;   //horas
    private $_charset       = null;  //Charset de la coneccion

    private $_logErrors     = array();


    private static $_instance = null;
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Setters,Getters">
    public function setFetchType($fetchType=MYSQL_ASSOC) 
    {
        $this->_fetchType = $fetchType;
    }

    public function getColsInfo() {
        return $this->_colsInfo;
    }

    public function getRowsAffected() {
        return $this->_rowsAffected;
    }
    public function getRowsReturned() {
        return $this->_rowsReturned;
    }

    public function getLastInsertId() {
        return $this->_lastInsertId;
    }
    public function getLastResults() {
        return $this->_lastResults;
    }

    public function getLastQuery() {
        return $this->_lastQuery;
    }

    public function getCacheDir() {
        return $this->_cacheDir;
    }

    public function setCacheDir($_cacheDir) {
        $this->_cacheDir = $_cacheDir;
    }

    public function getCacheQueries() {
        return $this->_cacheQueries;
    }

    public function setCacheQueries($_cacheQueries) {
        $this->_cacheQueries = $_cacheQueries;
    }

    public function getCacheInserts() {
        return $this->_cacheInserts;
    }

    public function setCacheInserts($_cacheInserts) {
        $this->_cacheInserts = $_cacheInserts;
    }

    public function getUseDiskCache() {
        return $this->_useDiskCache;
    }

    public function setUseDiskCache($_useDiskCache) {
        $this->_useDiskCache = $_useDiskCache;
    }

    public function getCacheTimeout() {
        return $this->_cacheTimeout;
    }

    public function setCacheTimeout($_cacheTimeout) {
        $this->_cacheTimeout = $_cacheTimeout;
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
    // </editor-fold>


    private function __construct()
    {
        $this->_link                = null;
        $this->_resource            = null;
        $this->_params              = array();
        
        $this->_isOpenTransaction   = false;

        $this->setUseDiskCache(false);
        $this->setCacheDir(DB_PATH_CACHE);
        $this->setCacheInserts(false);
        $this->setCacheQueries(false);
        $this->setCacheTimeout(DB_TIMEOUT_CACHE);


        
    }

    private function flush()
    {
        //$this->setUseDiskCache(false);
        $this->_lastInsertId = null;
        $this->_lastResults = null;
        $this->_colsInfo = null;
        $this->_resource = null;
    }
    
    public static function getInstance()
    {
        if (self::$_instance == null)
        {
            self::$_instance = new Ftl_MySql();
        }
        return self::$_instance;
    }


    public function getLastError()
    {
        $lastPos = count($this->_logErrors);
        return ($lastPos == 0 ? $this->_logErrors[$lastPos] : $this->_logErrors[$lastPos-1]);
    }

    public function connect()
    {

        try
        {
            if( is_resource( $this->_link ) )
            {
                    return true;
            }

            $this->_link = @mysql_connect( DB_HOST, DB_USER, DB_PASS );
            
            if( !is_resource( $this->_link ) )
            {
                throw new Ftl_DB_DataBaseException( "No se pudo realizar la conección con la BBDD", -2 );
            }else
            {
                echo "BD=>connect: " . Ftl_DateTimeUtil::getGMT();
            }


            if ($this->_charset)
            {
                $this->setCharset($this->_charset);
            }

            if( !@mysql_select_db( DB_BASE ) )
            {
                throw new Ftl_DB_DataBaseException( @mysql_error(), @mysql_errno() );
            }


            return true;
        }
        catch (Exception $e)
        {
            $this->_logErrors[] = new Ftl_DB_Error($e->getCode(),$e->getMessage());
            echo $e->getMessage();
            echo "<br><br>";
            return false;
        }
    }


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
                echo "BD=>close: " . Ftl_DateTimeUtil::getGMT('d/m/Y H:i:s');
                $this->_link = null;
            }

            return true;

        }
        catch(Exception $e)
        {
            $this->_logErrors[] = new Ftl_DB_Error($e->getCode(),$e->getMessage(),$sql);
            return false;
        }
        
    }


    /*
     * Ejecuta una consulta pasada por parametro ($query) y guarda la info de esa ejecución.
     * Si es una consulta insert/update/delete/replace guarda los datos de registros afectados y el nuevo id (solo para insert/replace).
     * Si es una consulta query guarda la info de los campos afectados y el resultado devuelto en un array de objetos.
     */
    public function query( $sql )
    {
        try
        {
            if ( is_null($sql) || trim($sql) == '' )
            {
                return false;
            }

            if( !$this->connect() )
            {
                return false;
            }

            $this->flush();


            //Guardo la consulta como última
            $this->_lastQuery= $sql;

            
            //Ejecuto la consulta
            $this->_resource = @mysql_query( $sql );


            //Si hubo algun error lo guardo en el log de errores de DB y corto la ejecucion
            if (!$this->_resource)
            {
                throw new Ftl_DB_DataBaseException(mysql_error($this->_link), mysql_errno($this->_link));
            }

            if ( preg_match( "/^(insert|delete|update|replace)\s+/i", $sql ) )
            {
                    //Si es una consulta de insert/delete/update/replace guardo la cant de registros afectados
                    $this->_rowsAffected = @mysql_affected_rows($this->_link);

                    if (mysql_errno($this->_link))
                    {
                        throw new Ftl_DB_DataBaseException(mysql_error($this->_link), mysql_errno($this->_link));
                    }

                    // Obtengo el ultimo id generado en caso de ser una consulta de insert/replace
                    if ( preg_match("/^(insert|replace)\s+/i",$sql) )
                    {
                            $this->_lastInsertId = @mysql_insert_id($this->_link);
                    }

                    //Si hubo algun error en la ejecución lo guardo y corto la ejecucion.
                    if (mysql_errno($this->_link))
                    {
                        throw new Ftl_DB_DataBaseException(mysql_error($this->_link), mysql_errno($this->_link));
                    }

                    
            }
            else
            {

                    // Guardo la info de las columnas devueltas por la consulta.
                    $i=0;
                    while ($i < @mysql_num_fields($this->_resource))
                    {
                            $this->_colsInfo[$i] = @mysql_fetch_field($this->_resource);
                            $i++;
                    }

                    if (mysql_errno($this->_link))
                    {
                        throw new Ftl_DB_DataBaseException(mysql_error($this->_link), mysql_errno($this->_link));
                    }


                    // Store Query Results
                    $num_rows=0;
                    while ( $row = @mysql_fetch_object($this->_resource) )
                    {
                            // Almaceno los resultados devueltos como objeto en el array de resultados.
                            $this->_lastResults[$num_rows] = $row;
                            $num_rows++;
                    }

                    if (mysql_errno($this->_link))
                    {
                        throw new Ftl_DB_DataBaseException(mysql_error($this->_link), mysql_errno($this->_link));
                    }

                    //Libero los resultados de la consultas
                    @mysql_free_result($this->_resource);

                    //Guardo la cantidad de registros retornados por la consulta
                    $this->_rowsReturned = $num_rows;


                    
            }
            $return_val = true;
            
        }
        catch(Exception $e)
        {
            $this->_logErrors[] = new Ftl_DB_Error($e->getCode(),$e->getMessage(),$sql);

            $return_val = false;
        }
        if (!$this->_isOpenTransaction ) $this->close();
        return $return_val;
    }


    /*
     * Inserta los datos pasados por parametro ($data) en una tabla ($table)
     * Si la tabla tiene una PK autoincrement retorna el nuevo id generado, sino retorna true.
     * Ej:
     *

     * $data =  array
     *          (
     *              'nombre'    => 'Lucas',
     *              'sexo'      => 'M',
     *              'fecha_nac' => '2011-09-01'
     *          );
     * $res = $con->insert ( 'usuarios', $data );
     *
     */
    function insert ( $table, $data )
    {

        if( !$this->connect() )
        {
            echo "No connect";
            return false;
        }

        if ($table == null || trim( $table ) == '')
        {
            return false;
        }

        $sql = "INSERT INTO $table SET " . $this->parseFields($data);
        
        if ( $this->query( $sql ) )
        {
            return ($this->_lastInsertId > 0) ? $this->_lastInsertId : true;
        }
        else
            return false;

    }

    /*
     * Inserta multiples valores ($data) en una tabla ($table) tomando los campos ($fields) pasados por parametro
     * Ej:
     *
     * $fields = 'numbre,sexo';
     * $data =  array
     *          (
     *              array('Lucas','M'),
     *              array('Lucas','M'),
     *              array('Lucas','M'),
     *              array('Lucas','M')
     *          );
     * $res = $con->insertMultiple ( 'usuarios', $fields, $data );
     *
     */

    function insertMultiple ( $table , $fields , $data )
    {
        echo "entro";
        if( !$this->connect() )
        {
            echo "No connect";
            return false;
        }
        
        if ( $table == null || trim( $table ) == '' || !is_array($data) )
        {
            return false;
        }

        $sql = "INSERT INTO $table " . ($fields ? "($fields) " : "") . "VALUES ";

        $parsedValues = array();

        foreach ( $data as $k => $v )
        {
            $parsedValues[] = "(" . $this->parseFields( $v, true) . ")";
        }

        if ( count($parsedValues) <= 0 )
        {
            return false;
        }

        $sql .= implode(',', $parsedValues);

        if ( $this->query( $sql ) )
        {
            return $this->_rowsAffected;
        }
        else
        {
            return false;
        }

    }

    function update ( $table, $data=array(), $where=null )
    {

        if( !$this->connect() )
        {
            echo "No connect";
            return false;
        }
        
        if ($table == null || trim( $table ) == '')
        {
            return false;
        }
        
        $sql = "UPDATE $table SET " . $this->parseFields($data);
        
        if ( !is_null ($where) && trim($where) != '' )
        {
            $sql .= " WHERE " . str_ireplace("where", "", $where);
        }

        

        if ( $this->query( $sql ) )
        {
            return $this->_rowsAffected;
        }
        else
            return false;

    }

    function delete ($table,$where=null)
    {

        
        if ($table == null || trim( $table ) == '')
        {
            return false;
        }

        $sql = "DELETE FROM $table ";

        if ( !is_null ($where) && trim($where) != '' )
        {
            $sql .= "WHERE " . str_ireplace("where", "", $where);
        }
        
        if ( $this->query( $sql ) )
        {
            return $this->_rowsAffected;
        }
        else
            return false;

    }

    function truncate ($table)
    {

        if ($table == null || trim( $table ) == '')
        {
            return false;
        }

        $sql = "TRUNCATE TABLE $table ";

        if ( $this->query( $sql ) )
        {
            return true;
        }
        else
            return false;

    }


    /*----Resultados
     *
     * getResults   (sql,output): Retorna los resultados de una consulta
     * getColum     (sql,$x=0;$y=0)
     *
     */
    public function getResults($sql=null, $output = Ftl_DB::FETCH_OBJECT)
    {

        if ( $sql )
        {
            if ( !$this->query( $sql ) )
            {
                return false;
            }
        }

        switch ($output)
        {
            case Ftl_DB::FETCH_OBJECT:

                return $this->_lastResults;
                break;
            
            case Ftl_DB::FETCH_NUM:
            case Ftl_DB::FETCH_ASSOC:

                if ( $this->_lastResults )
                {
                        $i=0;
                        foreach( $this->_lastResults as $row )
                        {

                                $new_array[$i] = get_object_vars($row);

                                if ( $output == Ftl_DB::FETCH_NUM )
                                {
                                        $new_array[$i] = array_values($new_array[$i]);
                                }

                                $i++;
                        }

                        return $new_array;
                }
                else
                {
                        return null;
                }

                break;


        }


    }

    public function getVal($sql=null,$column=0,$row=0)
    {

        if ( $sql )
        {
            if ( !$this->query( $sql ) )
            {
                return false;
            }
        }

        // Extract var out of cached results based x,y vals
        if ( $this->_lastResults[$row] )
        {
                $values = array_values(get_object_vars($this->_lastResults[$row]));
        }

        
        return (isset($values[$column]))?$values[$column]:null;
    }

    public function getColumn($sql=null,$column=0)
    {

        if ( $sql )
        {
            if ( !$this->query( $sql ) )
            {
                return false;
            }
        }

        $columns = null;
        // Extract var out of cached results based x,y vals
        if ( $this->_lastResults )
        {

            if ( is_numeric( $column ) )
            {
                if ( isset( $this->_colsInfo[$column] ) )
                {
                    $column = $this->_colsInfo[$column]->name;
                }
                else
                {
                    return null;
                }
            }

            $columns = array();

            if ( !isset ( $this->_lastResults[0]-> $column) )
            {
                return null;
            }

            foreach ($this->_lastResults as $row => $fields)
            {
                $columns[] = $fields->$column;
            }

        }

        return $columns;

    }


    public function debug()
    {

            // Start outup buffering
            ob_start();

            echo "<blockquote>";


            if ( $this->_logErrors )
            {
                    echo "<font face=arial size=2 color=000099><b>Last Error --</b> [<font color=000000><b>{$this->getLastError()}</b></font>]<p>";
            }

            if ( $this->_fromDiskCache )
            {
                    echo "<font face=arial size=2 color=000099><b>Results retrieved from disk cache</b></font><p>";
            }

            
            echo "[<font color=000000><b>$this->_lastQuery</b></font>]</font><p>";

                    echo "<font face=arial size=2 color=000099><b>Query Result..</b></font>";
                    echo "<blockquote>";

            if ( $this->_colsInfo )
            {

                    // =====================================================
                    // Results top rows

                    echo "<table cellpadding=5 cellspacing=1 bgcolor=555555>";
                    echo "<tr bgcolor=eeeeee><td nowrap valign=bottom><font color=555599 face=arial size=2><b>(row)</b></font></td>";


                    for ( $i=0; $i < count($this->_colsInfo); $i++ )
                    {
                            echo "<td nowrap align=left valign=top><font size=1 color=555599 face=arial>{$this->_colsInfo[$i]->type} {$this->_colsInfo[$i]->max_length}</font><br><span style='font-family: arial; font-size: 10pt; font-weight: bold;'>{$this->_colsInfo[$i]->name}</span></td>";
                    }

                    echo "</tr>";

                    // ======================================================
                    // print main results

            if ( $this->_lastResults )
            {

                    $i=0;
                    foreach ( $this->getResults(null, Ftl_DB::FETCH_NUM) as $one_row )
                    {
                            $i++;
                            echo "<tr bgcolor=ffffff><td bgcolor=eeeeee nowrap align=middle><font size=2 color=555599 face=arial>$i</font></td>";

                            foreach ( $one_row as $item )
                            {
                                    echo "<td nowrap><font face=arial size=2>$item</font></td>";
                            }

                            echo "</tr>";
                    }

            } // if last result
            else
            {
                    echo "<tr bgcolor=ffffff><td colspan=".(count($this->_colsInfo)+1)."><font face=arial size=2>No Results</font></td></tr>";
            }

            echo "</table>";

            } // if col_info
            else
            {
                    echo "<font face=arial size=2>No Results</font>";
            }


            $html = ob_get_contents();
            ob_end_clean();

            echo $html;

            return $html;

    }



    /*************************************************************************
      La idea es que este método se re-declare en cada clase para aprobechar
      las funciones propias del driver de BD.
      El tratamiento de los datos debe ser similar en todos los casos.
     *************************************************************************/
    public function sanitize($data)
    {
        if( !$this->connect() ) {
                return $data;
        }
        return Ftl_ArrayUtil::map($data, 'mysql_real_escape_string');
    }




    function count($table,$where=null)
    {
        $sql = "SELECT COUNT(1) cant FROM $table ";

        if ( !is_null ($where) && trim($where) != '' )
        {
            $sql .= " WHERE " . str_ireplace("where", "", $where);
        }

        
        if ($this->query($sql))
        {
            if (is_array($this->_lastResults))
            {
                return $this->_lastResults[0]->cant;
            }
            else
                return false;
        }
        else
            return false;

        
    }

    function getCleanQuery($sql,$data=array())
    {


        if (!$this->connect() || is_null($sql) || trim($sql) == '' )
        {
            return $sql;
        }

        /******************************************************************
         * Si me llega un array con datos los limpio y los reemplazo en la
         * cadena original ($sql) para luego ejecutar la consulta.
         ******************************************************************/

        if ( is_array ( $data ) )
        {
            $sql = Ftl_StringUtil::replaceVars( $sql, $data, array( "fnCallback" => 'mysql_real_escape_string' ) );
        }

        return $sql;
    }

    private function parseFields ($data,$multiple=false)
    {
        $fields = array();

        foreach($data as $k => $v)
        {
            if ( is_null($v) )
            {
                $fields[] = ($multiple ? "NULL" : $k . "= NULL") ;
            }
            elseif ( is_bool ($v) )
            {
                $fields[] = ( $v === true ) ? ($multiple ? "1" : $k . "= 1") : ($multiple ? "0" : $k . "= 0") ;
            }
            else
            {
                $fields[] = ($multiple ? "'" . $this->sanitize($v) . "'" : $k . "= '" . $this->sanitize($v) . "'");
            }
        }

        return implode (',',$fields);
    }

    public function escape($data)
    {
            if( !is_array( $data ) ) {

                    if( !$this->connect() ) {
                            return $data;
                    }

                    return mysql_real_escape_string( $data , $this->_link );

            }

            $ret = array();
            foreach( $data as $k => $v ) {
                    $ret[ $this->escape($k) ] = $this->escape( $v );
            }

            return $ret;
    }

    // <editor-fold defaultstate="collapsed" desc="Transacciones">

    public function beginTransaction()
    {
        echo "---------ENtro a beginTransaction---------";

        if( !$this->connect() )
        {
            return false;
        }

        if ( $this->_isOpenTransaction )
        {
            return true;
        }

        mysql_query("START TRANSACTION", $this->_link);

        $this->_isOpenTransaction = mysql_query("BEGIN", $this->_link);

        return true;
    }
    public function commitTransaction()
    {
        echo "/*---------ENtro a commitTransaction---------*/";
        if ( $this->_isOpenTransaction )
        {
            mysql_query("COMMIT", $this->_link);
            $this->_isOpenTransaction = false;
            return true;
        }
        else
        {
           return false;
        }
    }
    public function rollbackTransaction()
    {
         echo "/*---------ENtro a rollbackTransaction---------*/";
        if ( $this->_isOpenTransaction )
        {
            mysql_query("ROLLBACK", $this->_link);
            $this->_isOpenTransaction = false;
            return true;
        }
        else
        {
           return false;
        }
    }

    // </editor-fold>


}


?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PDO
 *
 * @author Luki
 */
class Ftl_PDO2// extends PDO
{

    private static $_instance = null;
    private $_stmt = null;
    private $_link;
    private $_options = null;
    private $_resource;
    private $_charset;

    private $_isOpenTransaction = false;
    private $_colsInfo      = null; //Información de las columnas de un resultado
    private $_rowsAffected  = null; //Nro de filas afectadas por INSERT/UPDATE/DELETE
    private $_rowsReturned  = null; //Nro de filas retornadas por SELECT
    private $_lastInsertId  = null; //Ultimo id generado por INSERT
    private $_lastQuery     = null; //Guardo la ultima consulta
    private $_lastResults   = null; //Guardo los ultimos resultados.


    // <editor-fold defaultstate="collapsed" desc="Setters,Getters">

    public function setFetchType($fetchType=PDO::FETCH_ASSOC)
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

    // </editor-fold>
    public function __construct()
    {

        $this->_link                = null;
        $this->_resource            = null;
        $this->_params              = array();
        $this->_options             = null;

        $this->_isOpenTransaction   = false;

    }

    public static function getInstance()
    {
        if( self::$_instance == null )
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function flush()
    {
        //$this->setUseDiskCache(false);
        $this->_lastInsertId = null;
        $this->_lastResults = null;
        $this->_colsInfo = null;
        $this->_resource = null;
    }


    public function setCharset($_charset) {
        $this->_charset = $_charset;

        if (!is_null($this->_link))
        {
            $this->_link->exec('SET CHARACTER SET ' . $this->_charset);
        }
    }


    
    public function executeSP($sp,$params=null,$fetch=Ftl_DB::FETCH_OBJECT)
    {

//        try
//        {
            $return_val = false;


            if ( is_null($sp) || trim($sp) == '' )
            {
                return false;
            }


            $sql = "CALL $sp ";


            foreach($params as $k=>$v)
            {
                $fields[] = (strpos($k, '@') !== false) ? $k :  ':'.$k;

            }

            $sql .= "(" . implode (',',$fields) . ")";



            if ( $this->query( $sql,$params ) )
            {
                return $this->getResults(null,$fetch);
            }
            else
                return NULL;

//        }
//        catch(PDOException $e)
//        {
//            echo "Exception: " . $e->getMessage(). "<br>";
//            $this->_logErrors[] = new Ftl_DB_Error($e->getCode(),$e->getMessage(),$sql);
//            return false;
//        }


    }

    public function connect()
    {
        try
        {
            if( !is_null( $this->_link ) )
            {
                    return true;
            }

            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_BASE;

            $this->_options = array (
                            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION

            );

            $this->_link = new PDO( $dsn, DB_USER, DB_PASS,  $this->_options);


            if( is_null( $this->_link ) )
            {
                throw new Ftl_DB_DataBaseException( "No se pudo realizar la conección con la BBDD", -2 );
            }

            var_dump($this->_link);

            if ($this->_charset)
            {
                $this->setCharset($this->_charset);
            }

            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }



    }

    public function close()
    {
        try
        {
            if ( is_null($this->_link) )
            {
                return true;
            }
            echo "<-----------CLOSE--------------->";
            $this->_link = null;
            return true;

        }
        catch(PDOException $e)
        {
            return false;
        }

    }

    public function getLink ()
    {
            if( !$this->connect() )
            {
                return false;
            }

            return $this->_link;
    }


    public function query($sql,$data=null)
    {

        try
        {
            $return_val = false;

            
            if ( is_null($sql) || trim($sql) == '' )
            {

                return false;
            }


            if( !$this->connect() )
            {
                return false;
            }

            $this->flush();

            $stmt = $this->_link->prepare($sql);

            if ($data)
            {

                foreach ($data as $k => $v)
                {
                    if (strpos($k, '@') === false){
                        
                        $stmt->bindValue(':'.$k, $v);
                    }
                }
            }
            echo "Inicio:" . Ftl_DateTimeUtil::getGMT('H:i:s') ."<br>";
            $stmt->execute( );
            echo "Fin:" . Ftl_DateTimeUtil::getGMT('H:i:s') ."<br>";
            if ( preg_match( "/^(insert|delete|update|replace)\s+/i", $sql ) )
            {
                    //Si es una consulta de insert/delete/update/replace guardo la cant de registros afectados
                    $this->_rowsAffected = $stmt;

                    // Obtengo el ultimo id generado en caso de ser una consulta de insert/replace
                    if ( preg_match("/^(insert|replace)\s+/i",$sql) )
                    {
                            $this->_lastInsertId = $this->_link->lastInsertId('id');
                    }

                    $return_val = true;
            }
            else
            {
                $this->_resource = $stmt;

                if ($this->_resource){


                   // Guardo la info de las columnas devueltas por la consulta.
                    $num_rows=0;

                    // Store Query Results
                    $result = $this->_resource->setFetchMode(PDO::FETCH_OBJ);
                    

                    if ( $this->_resource->columnCount() > 0 )
                    {
                        while ($row = $this->_resource->fetch()) {
                            $this->_lastResults[$num_rows] = $row;
                            $num_rows++;
                        }

                        //Libero los resultados de la consultas
                        if ($num_rows > 0){
                            do $this->_resource->fetchAll();
                            while ($this->_resource->nextRowSet());
                        }
                    }
                    //Guardo la cantidad de registros retornados por la consulta
                    $this->_rowsReturned = $num_rows;


                    $return_val = true;

                    }
            }
            
            if ($stmt) $stmt->closeCursor();
            //if (!$this->_isOpenTransaction ) $this->close();
            return $return_val;
            
        }
        catch(PDOException $e)
        {
            if ($stmt) $stmt->closeCursor();
            //if (!$this->_isOpenTransaction ) $this->close();
            throw new Ftl_DB_DataBaseException($e->getMessage());
        }


    }

    /*
     * Inserta los datos pasados por parametro ($data) en una tabla ($table)
     * Si la tabla tiene una PK autoincrement retorna el nuevo id generado, sino retorna true.
     * Si se especifica $psmode=false entonces no lo ejecuta como prepared statement
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
    function insert ( $table, $data=null )
    {

        if ($table == null || trim( $table ) == '')
        {
            return false;
        }


        $fields = array();
        $sql = "INSERT INTO $table SET ";

        foreach($data as $k=>$v)
        {
            $fields[] = $k . ' = :'.$k;
        }

        $sql .= implode (',',$fields);

        $this->query( $sql,$data );
        return ($this->_lastInsertId > 0) ? $this->_lastInsertId : true;
    }




    function update ( $table, $data=array(), $where=null )
    {


        if ($table == null || trim( $table ) == '')
        {
            return false;
        }

        $sql = "UPDATE $table SET ";

        $fields = array();

        foreach($data as $k=>$v)
        {
            $fields[] = $k . ' = :'.$k;
        }

        $sql .= implode (',',$fields);

        if ( !is_null ($where) && trim($where) != '' )
        {
            $sql .= " WHERE " . str_ireplace("where", "", $where);
        }

        if ( $this->query( $sql,$data ) )
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
                $fields[] = ($multiple ? $this->escape($v) : $k . "= " . $this->escape($v));
            }
        }

        return implode (',',$fields);
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
            foreach($data as $k => $v)
            {
                $sql = str_replace(":".$k, $this->escape($v), $sql);
            }
        }

        return $sql;
    }

    public function escape($data)
    {
            if ( is_null( $data ) ){
                return NULL;
            }

            if( !is_array( $data ) ) {

                    if( !$this->connect() ) {
                            return $data;
                    }

                    return $this->_link->quote( $data );

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
        echo "---------ENtro a beginTransaction---------<br>";

        if( !$this->connect() )
        {
            return false;
        }

        if ( $this->_isOpenTransaction )
        {
            return true;
        }


        $this->_isOpenTransaction = $this->_link->beginTransaction();
        var_dump($this->_isOpenTransaction);
        return true;
    }
    public function commitTransaction()
    {
        echo "/*---------ENtro a commitTransaction---------*/<br>";
        if ( $this->_isOpenTransaction )
        {
            
            $this->_isOpenTransaction = !$this->_link->commit();
             var_dump($this->_isOpenTransaction);
            return true;
        }
        else
        {
           return false;
        }
    }
    public function rollbackTransaction()
    {
         echo "/*---------ENtro a rollbackTransaction---------*/<br>";
        if ( $this->_isOpenTransaction )
        {
            
            $this->_isOpenTransaction = !$this->_link->rollBack() ;
            var_dump($this->_isOpenTransaction);
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

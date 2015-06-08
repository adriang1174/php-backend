<?php

class Registrado extends Ftl_Registrado{
    //put your code here
//    public function  __construct($id = 0) {
//        //echo $id;
//        parent::__construct($id);
//    }

    public function guardar()
    {
        $res = null;
        $db = FTL_DB::getInstance();

        try {

            $datos = array (

                "foto"                  => $this->getFoto(),
                "nombre"                => $this->getNombre(),
                "apellido"              => $this->getApellido(),
                "sexo"                  => $this->getSexo(),
                "fecha_nac"             => $this->getFechaNac(),
                "fecha_ult_modificacion"=> date("Y-m-d H:i:s")

            );
            

            if ($this->getId() > 0)
            {
                $res = $db->update( DB_PREFIX.'registrados',$datos,'id='.$db->escape($this->getId()) );
            }
            else
            {
                $datos["fecha_alta"]    = $this->getFechaAlta();
                $datos["estado"]        = 1;
                $res = $db->insert( DB_PREFIX.'registrados',$datos );
                if ( $res )
                    $this->setId ( $res );
            }

        }catch(Exception $e) {
            return false;
        }
        $db->close();

        return true;

    }

    public function guardarFoto ()
    {
        $res = null;
        $db = FTL_DB::getInstance();

        $datos = array (

            "foto"                  => $this->getFoto(),
            "fecha_ult_modificacion"=> date("Y-m-d H:i:s")

        );

        $res = $db->update( DB_PREFIX.'registrados',$datos,'id='.$db->escape($this->getId()) );
        

        $db->close();

        return $res;
        
    }

}
?>

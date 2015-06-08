<?php
require_once '../../frm/init.php';


$action = $ioHelper->getEscaped('action',null);

$resp   = array(
    "state" => 1,
    "data"  => null,
    "error" => null
);



switch ($action){

    case "delete":

        $id         = $ioHelper->getEscaped('id',0);
        $encrypt    = $ioHelper->getEscaped('encrypt',false);
        $table      = $ioHelper->getEscaped('table',null);

        if ( $table == null || $table == '' ){

            $resp["state"] = 0;
            $resp["error"] = array("code" => 1,"msg"=>"Se debe especificar la tabla.");
            
        }else if ( $id == 0  ){

            $resp["state"] = 0;
            $resp["error"] = array("code" => 2,"msg"=>"Se debe especificar un id.");

        }else{

            try{
                Ftl_ClaseBase::getDB()->delete( DB_PREFIX . $table, Ftl_ClaseBase::getDB()->getEscapedQuery("id = :id",array("id"=>$id)));
            }catch(Exception $ex){
                $resp["state"] = 0;
                $resp["error"] = array("code" => -1,"msg"=>$ex->getMessage());
            }
        }
        break;
    case "changeStatus":

        $id         = $ioHelper->getEscaped('id',0);
        $encrypt    = $ioHelper->getEscaped('encrypt',false);
        $table      = $ioHelper->getEscaped('table',null);
        $state      = $ioHelper->getEscaped('status',null);

        if ( $table == null || $table == '' ){
            $resp["state"] = 0;
            $resp["error"] = array("code" => 1,"msg"=>"Se debe especificar la tabla.");
        }else if ( $state == null || $state == '' ){
            $resp["state"] = 0;
            $resp["error"] = array("code" => 1,"msg"=>"Se debe especificar el estado.");
        }else if ( $id == 0  ){
            $resp["state"] = 0;
            $resp["error"] = array("code" => 2,"msg"=>"Se debe especificar un id.");
        }else{

            try{
                Ftl_ClaseBase::getDB()->update( DB_PREFIX . $table, array("estado" => $state), Ftl_ClaseBase::getDB()->getEscapedQuery("id = :id",array("id"=>$id)));
            }catch(Exception $ex){
                $resp["state"] = 0;
                $resp["error"] = array("code" => -1,"msg"=>$ex->getMessage());
            }
        }
        break;
    default:
        break;

}

echo Ftl_JsonUtil::encode($resp);

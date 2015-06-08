<?php
    require_once '../frm/init.php';
    
    $page = new Ftl_PageBO();
    $page->setTitle("Listado de Votos");
    $page->loadSripts("tooltip,form,checkbox");
    $page->checkSession();
    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Voto',
            'method'        => 'obtenerListado'
        ),
        'table'             => 'votos',
        'fields'            => array (
            'id_foto'           => array('title'=>'ID Foto','export'=>true,'filter'=>true),
            'foto'              => array('title'=>'Foto','type'=>'image','folder'=>'../uploads/','width'=>100,'height'=>100,'export'=>false),
            'id_categoria'  => array('title'=>'Categoria','type'=>'assoc','data'=>  Class_Foto::getCategorias(),'filter'=>true),            
            
            'tipo_doc'   => array('title'=>'Tipo Doc','type'=>'assoc','data'=>array('1'=>'DNI','2'=>'LC','3'=>'CI','4'=>'LE'),'filter'=>true),
            'nro_doc'   => array('title'=>'Nro Doc','type'=>'text','filter'=>true),
            'votos'             => array('title'=>'Votos','export'=>true),
            
            'uid'           => array('title'=>'FBID (Votado)','export'=>true,'filter'=>false),
            'fbid'              => array('title'=>'FBID (Votante)','export'=>true,'filter'=>false),
            'es_amigo'          => array("title"=> "Es Amigo?","type"=>"assoc","data"=>array("0"=>"NO","1"=>"SI"),"filter"=>false),
            
            'v.ip'                => array('title'=>'IP','export'=>true,'filter'=>true),
            'v.pais_ip'                => array('title'=>'Pais (IP)','export'=>true,'filter'=>false),
            'v.fecha_alta'      => array('title'=>'Fecha de Voto','type'=>'date','format'=>'d/m/Y H:i:s','filter'=>true)
            
        ),
        'fieldId'               => 'v.id',
        'fieldStatus'           => 'estado',
        'canOrder'          => false,
        'orderBy'           => 'v.fecha_alta|DESC',

        'showActions'       => false,
        'canExport'         => true,
        
        'resPerPage'        => 100
        
        

    );

    $list = new Ftl_ListBO( $opciones );
    

    $page->showTop();
    $list->show();
    $page->showFoot();

?>
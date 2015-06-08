<?php
    require_once '../frm/init.php';
    
    $page = new Ftl_PageBO();
    $page->setTitle("Posteos (Timeline)");
    $page->loadSripts("tooltip,form,checkbox");
    $page->checkSession();
    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Estadistica',
            'method'        => 'obtenerListadoPosteos'
        ),
        'table'             => 'votos',
        'fields'            => array (
        	'fbid'              => array('title'=>'FBID','export'=>true,'filter'=>false),
            'fecha_alta'      => array('title'=>'Fecha','type'=>'date','format'=>'d/m/Y H:i:s','filter'=>true)
            
        ),
        'fieldId'               => 'id',
        //'fieldStatus'           => 'estado',
        'canOrder'          => false,
        'orderBy'           => 'fecha_alta|DESC',

        'showActions'       => false,
        'canExport'         => true,
        
        'resPerPage'        => 100
        
        

    );

    $list = new Ftl_ListBO( $opciones );
    

    $page->showTop();
    $list->show();
    $page->showFoot();

?>
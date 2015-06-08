<?php
    require_once '../frm/init.php';
    
    $page = new Ftl_PageBO();
    $page->setTitle("Códigos de empaque ingresados");
    $page->loadSripts("tooltip,form,checkbox");
    $page->checkSession();
    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Estadistica',
            'method'        => 'obtenerListadoCodes'
        ),
        'table'             => 'votos',
        'fields'            => array (
        	'code_id'           => array('title'=>'Codigo','export'=>true,'filter'=>false),
            'user_id'  			=> array('title'=>'Usuario','export'=>true,'filter'=>true),            
            'name'  			=> array('title'=>'Nombre','export'=>true,'filter'=>true),            
            'last_name'			=> array('title'=>'Apellido','export'=>true,'filter'=>true),            
			'prod'  			=> array('title'=>'Producto','export'=>true,'filter'=>true,'type'=>'assoc','data'=>array('CHIPS'=>'CHIPS','TAKIS'=>'TAKIS','GOLDEN'=>'GOLDEN','KIYAKIS'=>'KIYAKIS','HOTNUTS'=>'HOTNUTS','POP'=>'POP','BIG_MIX'=>'BIG_MIX','RUNNERS'=>'RUNNERS','TOSTACHO'=>'TOSTACHO','TOREADAS'=>'TOREADAS'
											,'CHIPOTLES'=>'CHIPOTLES','ONDAS'=>'ONDAS','VALENTONES'=>'VALENTONES','CHURRITOS'=>'CHURRITOS','PAPATINAS'=>'PAPATINAS','REGALO'=>'REGALO')),            
			'points'  			=> array('title'=>'Puntos','export'=>true,'filter'=>false),            
			'action'  			=> array('title'=>'Acción','export'=>true,'filter'=>false),  
            'date_submitted'      => array('title'=>'Fecha','type'=>'date','format'=>'d/m/Y H:i:s','filter'=>true)
            
        ),
        'fieldId'               => 'user_id',
        //'fieldStatus'           => 'estado',
        'canOrder'          => false,
        'orderBy'           => 'date_submitted|DESC',

        'showActions'       => false,
        'canExport'         => true,
        
        'resPerPage'        => 100
   
        

    );

    $list = new Ftl_ListBO( $opciones );
    

    $page->showTop();
    $list->show();
    $page->showFoot();

?>
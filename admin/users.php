<?php
    require_once '../frm/init.php';
    
    $page = new Ftl_PageBO();
    $page->setTitle("Usuarios Registrados");
    $page->loadSripts("tooltip,form,checkbox");
    $page->checkSession();
    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Estadistica',
            'method'        => 'obtenerListadoUsers'
        ),
        'table'             => 'votos',
        'fields'            => array (
            'user_id'  			=> array('title'=>'Usuario','export'=>true,'filter'=>false,'link'=>'http://104.131.83.197:8080/admin/codes.php?'),            
            'name'  			=> array('title'=>'Nombre','export'=>true,'filter'=>true),            
            'last_name'			=> array('title'=>'Apellido','export'=>true,'filter'=>true),            
            'email'				=> array('title'=>'Email','export'=>true,'filter'=>true),     
            'mobile'			=> array('title'=>'Número de móvil','export'=>true,'filter'=>true),     
			'company'			=> array('title'=>'Compañía','export'=>true,'filter'=>false)
        ),
        'fieldId'               => 'user_id',
        //'fieldStatus'           => 'estado',
        'canOrder'          => false,
        'orderBy'           => 'user_id|DESC',

        'showActions'       => false,
        'canExport'         => true,
        
        'resPerPage'        => 100
   
        

    );

    $list = new Ftl_ListBO( $opciones );
    

    $page->showTop();
    $list->show();
    $page->showFoot();

?>
<?php
    require_once '../frm/init.php';
    
	$nro = $_REQUEST['nro'];

	$page = new Ftl_PageBO();
    $page->setTitle("Totales empaques");
    $page->loadSripts("tooltip,form,checkbox");
    $page->checkSession();
    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Estadistica',
            'method'        => 'obtenerListadoEmpaques'
        ),
        'table'             => 'votos',
        'fields'            => array (
			'nro'  			=> array('title'=>'Semana','export'=>true,'filter'=>true,'type'=>'assoc','data'=>array('1'=>'20 de Noviembre al 21 de Diciembre','2'=>'22 de Diciembre al 28 de Diciembre','3'=>'29 de Diciembre al 04 de Enero','4'=>'05 al 14 de Enero')),            
			'prod'  			=> array('title'=>'Producto','export'=>true,'filter'=>false),            
            'cant'  			=> array('title'=>'Cantidad de empaques','export'=>true,'filter'=>false)            
        ),
        'fieldId'               => 'user_id',
        //'fieldStatus'           => 'estado',
        'canOrder'          => false,
        'orderBy'           => 'prod|ASC',

        'showActions'       => false,
        'canExport'         => true,
        
        'resPerPage'        => 100
   
        

    );

    $list = new Ftl_ListBO( $opciones );
    

    $page->showTop();
    $list->show();
    $page->showFoot();

?>

<?php
    require_once '../frm/init.php';
    
    $page = new Ftl_PageBO();
    $page->setTitle("Facturas a emitir");
    $page->loadSripts("tooltip,form,checkbox");
    $page->checkSession();
    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Ftl_Factura',
            'method'        => 'obtenerListado'
        ),
        'table'             => 'F_FAC',
        'fields'            => array (
            'TIPFAC'  			=> array('title'=>'Tipo doc','type'=>'assoc','filter'=>true,'data'=>array('1' => 'FA','9'=>'FB','2'=>'NCA','3'=>'NCB','5'=>'NDA','6'=>'NDB')),            
            'CODFAC'  			=> array('title'=>'Nombre','export'=>true,'filter'=>true),            
            'FECFAC'			=> array('title'=>'Apellido','export'=>true,'filter'=>true),            
            'CNOFAC'				=> array('title'=>'Email','export'=>true,'filter'=>true),     
            'TOTFAC'			=> array('title'=>'Número de móvil','export'=>true,'filter'=>true),     
			   'BAS1FAC'			=> array('title'=>'Compañía','export'=>true,'filter'=>false),
			   'IIVA1FAC'			=> array('title'=>'Compañía','export'=>true,'filter'=>false)      
      ),
        'fieldId'               => 'CODFAC',
        'canOrder'          => false,
        'orderBy'           => 'TIPFAC,CODFAC',

        'showActions'       => false,
        'canExport'         => false,
        
        'resPerPage'        => 100
  

    );

    $list = new Ftl_ListBO( $opciones );
    

    $page->showTop();
    $list->show();
    $page->showFoot();
 ?>

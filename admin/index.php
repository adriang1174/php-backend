<?php
    require_once '../frm/init.php';
    
    $page = new Ftl_PageBO();
    $page->setTitle("Facturas a emitir");
    $page->loadSripts("tooltip,form,checkbox");
    //$page->checkSession();
    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Ftl_Factura',
            'method'        => 'obtenerListado'
        ),
        'table'             => 'F_FAC',
        'fields'            => array (
            'TIPFAC'  			=> array('title'=>'Tipo doc','type'=>'assoc','filter'=>true,'data'=>array('1' => 'FA','9'=>'FB','2'=>'NCA','3'=>'NCB','5'=>'NDA','6'=>'NDB')),            
            'CODFAC'  			=> array('title'=>'Número','filter'=>true),            
            'FECFAC'			=> array('title'=>'Fecha','filter'=>true),            
            'CNOFAC'				=> array('title'=>'Cliente','filter'=>true),     
            'TOTFAC'			=> array('title'=>'Total','filter'=>true),     
			   'BAS1FAC'			=> array('title'=>'Neto s/IVA','filter'=>false),
			   'IIVA1FAC'			=> array('title'=>'IVA','filter'=>false)      
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

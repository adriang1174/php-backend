<?php
    require_once '../frm/init.php';
    
    $page = new Ftl_PageBO();
    $page->setTitle("Facturas a emitir");
    $page->loadSripts("tooltip,form,checkbox");
    //$page->checkSession();
    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Factura',
            'method'        => 'obtenerListado'
        ),
        'table'             => 'F_FAC',
        'fields'            => array (
            'TIPFAC'  			=> array('title'=>'Tipo doc','type'=>'assoc','filter'=>true,'data'=>array('1' => 'FA','9'=>'FB','2'=>'NCA','3'=>'NCB','5'=>'NDA','6'=>'NDB')),            
            'CODFAC'  			=> array('title'=>'Número','filter'=>false),            
            'CODFACD'  			=> array('title'=>'Número Desde','filter'=>true,'show'=>false),            
            'CODFACH'  			=> array('title'=>'Número Hasta','filter'=>true,'show'=>false),                  
            'FECFAC'			=> array('title'=>'Fecha','filter'=>true),            
            'CNOFAC'				=> array('title'=>'Cliente','filter'=>true),     
			   'BAS1FAC'			=> array('title'=>'Neto s/IVA','filter'=>false),
			   'IIVA1FAC'			=> array('title'=>'IVA','filter'=>false) ,
            'TOTFAC'			=> array('title'=>'Total','filter'=>true),           
      ),
        'fieldId'               => 'CODFAC',
        'canOrder'          => false,
        'orderBy'           => 'TIPFAC|ASC,CODFAC|ASC',

        'canExport'         => false,
        'canCAE'            => true,      
        
		  'showActions'       => true,
        'groupExtendedActions' => false,
        'extendedActions'   => array(
            'df' => array('title'=>'Download (original)','mode'=>'js','fn'=>'download_original','ui_icon'=>'ui-icon-circle-arrow-s'),
            'uf' => array('title'=>'Upload (editada)','mode'=>'js','fn'=>'upload_editada','ui_icon'=>'ui-icon-circle-arrow-n')
        ),      
        'resPerPage'        => 100
  

    );

    $list = new Ftl_ListBO( $opciones );
    

    $page->showTop();
    $list->show();
    $page->showFoot();
 ?>

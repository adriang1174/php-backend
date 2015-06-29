<?php
    require_once '../frm/init.php';
    
    $page = new Ftl_PageBO();
    $page->setTitle("Facturas a emitir");
    $page->loadSripts("tooltip,form,checkbox");
    $page->showMenu = false;
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
            'OB1FAC'			=> array('title'=>'CAE'),   
            'OB2FAC'			=> array('title'=>'F. Vto. CAE'),         
      ),
        'fieldId'               => 'CODFAC',
        'canOrder'          => false,
        'orderBy'           => 'TIPFAC|ASC,CODFAC|ASC',

        'canExport'         => false,
        'canCAE'            => true,      
        
		  'showActions'       => false,
        'resPerPage'        => 100
    );

    $io = new Ftl_IOHelper();
    $io->addFromArray($_REQUEST);
    $error ="";
    if($io->get('solicitud','0') == '1')
    {
		$lote = new Ftl_LoteFacturas($io->get('TIPFAC','0'),$io->get('CODFACD','0'),$io->get('CODFACH','0'));
		//Antes de solicitar hay que validar lote
		if($lote->validarLote())
		{
			$lote->solicitarAfip();    
			$lote->guardar();
		}
		else
			$error = "Error. Verifique existan documentos previos con CAE generado";
    }
    
    $list = new Ftl_ListBO( $opciones );
    if($error != '')
    	$list->_jqueryOnLoad["error-msg"] = "UI.alert(".$error.",{title:'Atención'});";
    $page->showTop();
    $list->show();
    $page->showFoot();
 ?>

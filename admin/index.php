<?php
    require_once '../frm/init.php';
	require '../exceptionhandler.php';
	require '../wsaa.class.php';
	require '../wsfe.class.php';
	require 'config.php';

    
    $page = new Ftl_PageBO();
    $page->setTitle("Facturas a emitir");
    $page->loadSripts("tooltip,form,checkbox");
    $page->showMenu( false);
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
        'orderBy'           => 'TIPFAC|ASC,CODFAC|DESC',

        'canExport'         => false,
        'canCAE'            => true,      
        
		  'showActions'       => false,
        'resPerPage'        => 100
    );

    $io = new Ftl_IOHelper();
    $io->addFromArray($_REQUEST);
    $error ="";
    
    //Si se aplico el filtro, entonces creamos el lote Factura, y verificamos Ult Comp
    if($io->get('TIPFAC','0') <> '0')
    {
			$lote = new Ftl_LoteFacturas($io->get('TIPFAC','0'),$io->get('CODFACD',$defaultValue ='0'),$io->get('CODFACH',$defaultValue ='999999'));    
			$opciones['ultnro'] = $lote->getLastComp();
		    if($io->get('solicitud','0') == '1')
		    {
		
				//Antes de solicitar hay que validar lote
				if($lote->validarLote())
				{
					$errors = $lote->solicitarAfip();    
					if(count($errors) == 0)
						$lote->guardar();
					else
					{
						foreach($errors as $err1)	
							$error .= str_replace("\n",'',preg_replace('/[^A-Za-z0-9\ -]/', '',$err1 ));
					}
				}
				else
					$error = "Error. Verifique existan documentos previos con CAE generado";
		    }
	}
    //var_dump($error);
    $list = new Ftl_ListBO( $opciones );
    if(strlen($error) > 0)
    {
    	$list->showError($error);
    }
    $page->showTop();
    $list->show();
    $page->showFoot();
 ?>

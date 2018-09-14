<?php
    require_once '../frm/init.php';
	require '../exceptionhandler.php';
	require '../wsaa.class.php';
	require '../wsfe.class.php';
	require 'config.php';

    
    $page = new Ftl_PageBO();
	$wsfe = new WSFE('./');
	$r = $wsfe->getStatus();
	$page->setTitle("Facturas a emitir");
	//$r->FEDummyResult->AppServer
	$stat_app = ($r->FEDummyResult->AppServer == "OK")?"<font color=\"#00DD22\">".$r->FEDummyResult->AppServer."</font>":"<font color=\"#FF0000\">".$r->FEDummyResult->AppServer."</font>";
	$stat_db = ($r->FEDummyResult->DbServer == "OK")?"<font color=\"#00DD22\">".$r->FEDummyResult->DbServer."</font>":"<font color=\"#FF0000\">".$r->FEDummyResult->DbServer."</font>";
	$stat_auth = ($r->FEDummyResult->AuthServer == "OK")?"<font color=\"#00DD22\">".$r->FEDummyResult->AuthServer."</font>":"<font color=\"#FF0000\">".$r->FEDummyResult->AuthServer."</font>";
	$status = "Servidor de Aplicaciones = ". $stat_app." | Servidor de base de datos: ".$stat_db." | Servidor de autenticaci&oacute;n: ".$stat_auth ;
	//.", DbServer = ".$r->FEDummyResult->DBServer.", AuthServer = ".$r->FEDummyResult->AuthServer
    $page->setStatus($status);
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
            'TIPFAC'  			=> array('title'=>'Tipo doc','type'=>'assoc','filter'=>true,'data'=>array('\'1\'' => 'FA','\'9\''=>'FB','\'2\''=>'NCA','\'3\''=>'NCB','\'5\''=>'NDA','\'6\''=>'NDB','\'7\''=>'FM','\'77\''=>'NCM','\'777\''=>'NDM')),            
            'CODFAC'  			=> array('title'=>'Número','filter'=>false),            
            'CODFACD'  			=> array('title'=>'Número Desde','type'=>'int','filter'=>true,'show'=>false),            
            'CODFACH'  			=> array('title'=>'Número Hasta','type'=>'int','filter'=>true,'show'=>false),                  
            'FECFAC'			=> array('title'=>'Fecha','filter'=>false),            
            'CNOFAC'				=> array('title'=>'Cliente','filter'=>false),     
			   'BAS1FAC'			=> array('title'=>'Neto s/IVA','filter'=>false),
			   'IIVA1FAC'			=> array('title'=>'IVA','filter'=>false) ,
            'TOTFAC'			=> array('title'=>'Total','filter'=>false),      
            'BNOFAC'			=> array('title'=>'CAE'),   
            'OB2FAC'			=> array('title'=>'F. Vto. CAE'),         
			'OB1FAC'			=> array('title'=>'Cod. Barras'),
      ),
        'fieldId'               => 'CODFAC',
        'canOrder'          => false,
        'orderBy'           => 'TIPFAC|ASC,CODFAC|DESC',

        'canExport'         => false,
        'canCAE'            => true,      
        
		  'showActions'       => true,
        'resPerPage'        => 100
    );

    $io = new Ftl_IOHelper();
    $io->addFromArray($_REQUEST);
    $error ="";
    
    //Si se aplico el filtro, entonces creamos el lote Factura, y verificamos Ult Comp
    if($io->get('TIPFAC','0') <> '0')
    {
    		$codfacd = ($io->get('CODFACD','0')=='' ? '0' :  $io->get('CODFACD','0'));
    		$codfach = ($io->get('CODFACH','999999')=='' ? '0' : $io->get('CODFACH','999999'));
			$lote = new Ftl_LoteFacturas($io->get('TIPFAC','0'),$codfacd,$codfach);    
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
					$opciones['ultnro'] = $lote->getLastComp();
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

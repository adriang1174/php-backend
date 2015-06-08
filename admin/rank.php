<?php
    require_once '../frm/init.php';
    
	$nro = $_REQUEST['nro'];
	switch($nro)
	{
		case 1:
				$dates="date_submitted_d=2014-11-20&date_submitted_h=2014-12-21";
				break;
		case 2:
				$dates="date_submitted_d=2014-12-22&date_submitted_h=2014-12-28";
				break;
		case 3:
				$dates="date_submitted_d=2014-12-29&date_submitted_h=2015-01-04";
				break;
		case 4:
				$dates="date_submitted_d=2015-01-05&date_submitted_h=2015-01-11";
				break;
   		case 5:
                                $dates="date_submitted_d=2015-12-05&date_submitted_h=2015-01-18";
                                break;
	}
	$page = new Ftl_PageBO();
    $page->setTitle("Ranking Semanal");
    $page->loadSripts("tooltip,form,checkbox");
    $page->checkSession();
    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Estadistica',
            'method'        => 'obtenerListadoRanking'
        ),
        'table'             => 'votos',
        'fields'            => array (
			'nro'  			=> array('title'=>'Semana','export'=>true,'filter'=>true,'type'=>'assoc','data'=>array('1'=>'20 de noviembre al 21 de Diciembre','2'=>'22 al 28 de Diciembre',
									'3'=>'29 de Diciembre al 04 de Enero','4'=>'05 al 11 de Enero','5'=>'12 al 18 de Enero')),            
			'user_id'  			=> array('title'=>'Usuario','export'=>true,'filter'=>false,'link'=>'http://104.131.83.197:8080/admin/codes.php?'.$dates),            
            'name'  			=> array('title'=>'Nombre','export'=>true,'filter'=>false),            
            'last_name'			=> array('title'=>'Apellido','export'=>true,'filter'=>false),            
			'points'  			=> array('title'=>'Puntos','export'=>true,'filter'=>false),            
			'rank'  			=> array('title'=>'Posición','export'=>true,'filter'=>false)
            
        ),
        'fieldId'               => 'user_id',
        //'fieldStatus'           => 'estado',
        'canOrder'          => false,
        'orderBy'           => 'nro|DESC,rank|DESC',

        'showActions'       => false,
        'canExport'         => true,
        
        'resPerPage'        => 100
   
        

    );

    $list = new Ftl_ListBO( $opciones );
    

    $page->showTop();
    $list->show();
    $page->showFoot();

?>

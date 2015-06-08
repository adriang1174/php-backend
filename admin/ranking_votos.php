<?php
    require_once '../frm/init.php';
    $page = new Ftl_PageBO();
    $page->setCharsetEncoding(Ftl_CharsetEncoding::UTF8);

    $page->checkSession();
    $page->setTitle("Ranking por votos");
    $page->loadSripts("tooltip,form,checkbox");
    

    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Foto',
            'method'        => 'obtenerRankingVotos'
        ),
        'table'             => 'fotos',
        'fields'                => array (
            'f.id'   => array('title'=>'ID Foto','type'=>'int','filter'=>false),            
            'foto'          => array('title'=>'Foto','type'=>'image','folder'=>'../uploads/','width'=>100,'height'=>100,'export'=>false),
            'id_categoria'  => array('title'=>'Categoria','type'=>'assoc','data'=>  Class_Foto::getCategorias(),'filter'=>true,'value'=>1),
            'uid'       => array('title'=>'FB ID','type'=>'text','filter'=>false),
            'tipo_doc'   => array('title'=>'Tipo Doc','type'=>'assoc','data'=>array('1'=>'DNI','2'=>'LC','3'=>'CI','4'=>'LE'),'filter'=>false),
            'nro_doc'   => array('title'=>'Nro Doc','type'=>'text','filter'=>false),
            'r.nombre'    => array('title'=>'Nombre','type'=>'text'),
            'r.apellido'  => array('title'=>'Apellido'),
            'votos'      => array('title'=>'Votos','type'=>'text'),
            
            'f.fecha_alta'=> array('title'=>'Fecha de alta','type'=>'date','format'=>'Y-m-d H:i:s','filter'=>false)
        ),
        
        'fieldId'               => 'f.id',
        'fieldStatus'           => 'f.estado',
        'canOrder'          => false,
        'orderBy'           => 'f.votos|DESC,f.fecha_alta|ASC',
        'showActions'       => false,
        'resPerPage'        => 100,
        'extendedFilters'   => array(
            'f.estado'    => array('title'=>'Estado','type'=>'int','value'=>1,'filter'=>true),
        )

    );

    $list = new Ftl_ListBO( $opciones );
    
    
    $page->showTop();
    $list->show();
    $page->showFoot();

?>
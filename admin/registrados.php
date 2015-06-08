<?php
    require_once '../frm/init.php';
    

    $page = new Ftl_PageBO();
    $page->setCharsetEncoding(Ftl_CharsetEncoding::UTF8);

    $page->checkSession();
    $page->setTitle("Listado de registrados");
    $page->loadSripts("tooltip,form,checkbox");

    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Registrado',
            'method'        => 'obtenerListado'
        ),
        'table'             => 'registrados',
        'fields'                => array (
            'uid'       => array('title'=>'FB ID','type'=>'text','filter'=>true),
            'tipo_doc'   => array('title'=>'TIPO DOC','type'=>'assoc','data'=>array('1' => 'DNI','2'=>'LC','3'=>'CI','4'=>'LE')),
			'nro_doc'   => array('title'=>'DNI','type'=>'text','filter'=>true),
            'nombre'    => array('title'=>'Nombre','type'=>'text','filter'=>true),
            'apellido'  => array('title'=>'Apellido'),
			'sexo'   => array('title'=>'Sexo','type'=>'assoc','data'=>array('M' => 'Masculino','F'=>'Femenino')),
            'email'  => array('title'=>'Email'),
            'nro_celular'  => array('title'=>'Celular'),
			'titular'  => array('title'=>'Titular','type'=>'assoc','data'=>array('0' => 'NO','1'=>'SI')),
			'marca'  => array('title'=>'Marca'),
			'empresa'  => array('title'=>'Empresa'),
            'fecha_nac' => array('title'=>'Fecha Nac','type'=>'date','format'=>'Y-m-d','filter'=>false),
            'estado'    => array('title'=>'Estado','type'=>'state','data'=>array('0' => 'Sin moderar','1'=>'Aprobado')),
            'ip'                => array('title'=>'IP','export'=>true,'filter'=>true),
            'pais_ip'                => array('title'=>'Pais (IP)','export'=>true,'filter'=>false),
            'fecha_alta'=> array('title'=>'Fecha de alta','type'=>'date','format'=>'Y-m-d','filter'=>true)
        ),

        'fieldId'               => 'id',
        'fieldStatus'           => 'estado',
        
        'canOrder'          => false,
        'orderBy'           => 'id|DESC',

        'showActions'       => false,
        
        'resPerPage'        => 100

    );

    $list = new Ftl_ListBO( $opciones );
    
    
    $page->showTop();
    $list->show();
    $page->showFoot();

?>
<?php
    require_once '../frm/init.php';
    $page = new Ftl_PageBO();
    $page->setCharsetEncoding(Ftl_CharsetEncoding::UTF8);

    $page->checkSession();
    $page->setTitle("Listado de fotos");
    $page->loadSripts("tooltip,form,checkbox");
    $page->loadJSController("admin/js/controller/fotos.js?1");

    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Foto',
            'method'        => 'obtenerListado'
        ),
        'table'             => 'fotos',
        'fields'                => array (
            'f.id'   => array('title'=>'ID Foto','type'=>'int','filter'=>false),            
            'foto_original' => array('title'=>'Foto Orig','type'=>'image','folder'=>'../uploads/','width'=>100,'height'=>100,'export'=>false),
            'foto'          => array('title'=>'Foto Edit','type'=>'image','folder'=>'../uploads/','width'=>100,'height'=>100,'export'=>false),
            'id_categoria'  => array('title'=>'Categoria','type'=>'assoc','data'=>  Class_Foto::getCategorias(),'filter'=>true),
            'uid'       => array('title'=>'FB ID','type'=>'text','filter'=>true),
            'tipo_doc'   => array('title'=>'Tipo Doc','type'=>'assoc','data'=>array('1'=>'DNI','2'=>'LC','3'=>'CI','4'=>'LE'),'filter'=>true),
            'nro_doc'   => array('title'=>'Nro Doc','type'=>'text','filter'=>true),
            'r.nombre'    => array('title'=>'Nombre','type'=>'text'),
            'r.apellido'  => array('title'=>'Apellido'),
            'fecha_nac'  => array('title'=>'Fecha Nac','type'=>'date','format'=>'d/m/Y'),
            'votos'      => array('title'=>'Votos','type'=>'text'),
            'f.estado'    => array('title'=>'Estado','type'=>'assoc','data'=>array('0'=>'Sin Moderar','1'=>'Aceptada','-1'=>'Rechazada'),'value'=>'0','filter'=>true),
            'r.ip'          => array('title'=>'IP','export'=>true,'filter'=>true),
            'r.pais_ip'     => array('title'=>'Pais (IP)','export'=>true),
            'id_usuario_ap'  => array('title'=>'Aprob. por','type'=>'external','key'=>'usuariosbo','filter'=>false),

            'f.fecha_alta'=> array('title'=>'Fecha de alta','type'=>'date','format'=>'Y-m-d H:i:s','filter'=>true)
        ),
        
        'fieldId'               => 'f.id',
        'fieldStatus'           => 'f.estado',
        'canChangeStatus'   => true,
        'canOrder'          => false,
        'orderBy'           => 'f.fecha_alta|DESC',
        'toggleAll'         => true,
        'showActions'       => true,
        'groupExtendedActions' => false,
        'extendedActions'   => array(
            'df' => array('title'=>'Download (original)','mode'=>'js','fn'=>'download_original','ui_icon'=>'ui-icon-circle-arrow-s'),
            'uf' => array('title'=>'Upload (editada)','mode'=>'js','fn'=>'upload_editada','ui_icon'=>'ui-icon-circle-arrow-n')
        ),
        
        'resPerPage'        => 100

    );

    $list = new Ftl_ListBO( $opciones );
    $list->addExternalData("usuariosbo", "usuarios_bo", "id", "usuario", "usuario asc");
    
    $page->showTop();
    $list->show();
    $page->showFoot();

?>
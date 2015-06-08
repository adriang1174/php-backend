<?php
    require_once '../frm/init.php';
    $page = new Ftl_PageBO();

    $page->setTitle("Listado de usuarios");
    $page->loadSripts("tooltip,checkbox");
    $page->setCacheable(false);
    $page->showHeader(true);

    $page->checkSession();

    $opciones = array (
        
        'dataSource'    => array(
            'class'     => "Ftl_UsuarioBO",
            'method'    => "obtenerListado"
        ),
        
        'table'         => Ftl_UsuarioBO::TABLE,
        
        'fields'        => array (
            'usuario'   => array('title'=>'Usuario','type'=>'text','filter'=>false,'show'=>true,'export'=>true),
            'nombre'    => array('title'=>'Nombre','type'=>'text','filter'=>false),
            'apellido'  => array('title'=>'Apellido','filter'=>false),
            //'id_perfil' => array('title'=>'Perfil','type'=>'external','key'=>'test','filter'=>true),
            'estado'    => array('title'=>'Estado','type'=>'assoc','data'=>array('1' => 'Activo','-1'=>'Inactivo'),'filter'=>false),
            'fecha_alta'=> array('title'=>'Fecha de alta','type'=>'date','format'=>'d/m/Y','filter'=>true)
        ),
//        'extendedFilters'   => array(
//            'luki2'      => array('type'=>'assoc','value'=>"0",'filter'=>true)
//        ),
        'canOrder'          => true,
        'orderBy'           => 'fecha_alta|DESC',

        'showActions'       => true,

        'canEdit'           => true,
        //'edit'              => array("params" => "a=1"),

        'canDelete'         => true,
        
        'canAdd'            => true,

        'canChangeStatus'   => true,
        


        'extendedActions'   => array (
            'cp'    => array ('mode' => 'fancy','width'=> '400','height'=>'285', 'title'=>'Cambiar clave','ui_icon' => 'ui-icon-key','url'=>'cp-usuarios.php')
        ),


        'resPerPage'        => 50

    );

    $list = new Ftl_ListBO( $opciones );
    //$list->addExternalData("test", "test", "id", "nombre", "nombre asc");
    

    $page->showTop();
    $list->show();
    $page->showFoot();

?>
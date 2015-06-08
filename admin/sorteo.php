<?php
    require_once '../frm/init.php';
    $page = new Ftl_PageBO();
    $page->setCharsetEncoding(Ftl_CharsetEncoding::UTF8);

    $page->checkSession();
    $page->setTitle("Sorteo");
    $page->loadSripts("tooltip,form,checkbox");
    $page->loadJSController("admin/js/controller/sorteo.js?1");

    $categorias =  Class_Foto::getCategorias();

    $opciones = array (
        
        'dataSource'        => array (
            'class'         => 'Class_Sorteo',
            'method'        => 'obtenerListado'
        ),
        'table'             => 'registrados',
        'fields'                => array (
            
            'id_categoria'  => array('title'=>'Categoria','type'=>'assoc','data'=>  $categorias,'filter'=>false),
            'uid'       => array('title'=>'FB ID','type'=>'text','filter'=>false),
            'tipo_doc'   => array('title'=>'Tipo Doc','type'=>'assoc','data'=>array('1'=>'DNI','2'=>'LC','3'=>'CI','4'=>'LE'),'filter'=>false),
            'nro_doc'   => array('title'=>'Nro Doc','type'=>'text','filter'=>false),
            'nombre'    => array('title'=>'Nombre','type'=>'text'),
            'apellido'  => array('title'=>'Apellido'),
            'fecha_nac'  => array('title'=>'Fecha Nac','type'=>'date','format'=>'d/m/Y'),
            //'votos'      => array('title'=>'Votos','type'=>'text'),
            //'f.estado'    => array('title'=>'Estado','type'=>'assoc','data'=>array('0'=>'Sin Moderar','1'=>'Aceptada','-1'=>'Rechazada'),'value'=>'0','filter'=>true),
            'ip'          => array('title'=>'IP','export'=>true,'filter'=>false),
            'pais_ip'     => array('title'=>'Pais (IP)','export'=>true),
            'fecha_alta'=> array('title'=>'Fecha de alta','type'=>'date','format'=>'Y-m-d H:i:s','filter'=>false)
        ),
        
        'fieldId'               => 'r.id',
        //'fieldStatus'           => 'f.estado',
        'canChangeStatus'   => false,
        'canOrder'          => false,
        'orderBy'           => 'id_categoria|ASC',
        
        'showActions'       => false,
       
        'resPerPage'        => 100

    );

    $list = new Ftl_ListBO( $opciones );
    //$list->addExternalData("usuariosbo", "usuarios_bo", "id", "usuario", "usuario asc");
    
    $page->showTop();
?>

<table>
	<tr>
		<td>
			<input type="button" id="btn_sortear" value="Sortear"/>
		</td>
		
	</tr>	
</table>
<?php
    $list->show();
    $page->showFoot();

?>
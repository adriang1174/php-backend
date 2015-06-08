<?php

class Ftl_ListBO {

    const IMAGE_WIDTH           = 50;
    const IMAGE_HEIGHT          = 50;
    const DATETIME_FORMAT       = 'd/m/Y H:i:s';
    const DATE_FORMAT           = 'd/m/Y';

    private $_numColumns = 0;
    private $_mapOrder  = array();
    private $_io        = null;
    private $_opt       = array(

        'toggleAll'             => false,

        'showActions'           => false,
        'showPager'             => true,
        
        'canEdit'               => false,
        'edit'                  => array(
                                            "mode"  => "fancy",
                                            "url"   => null,
                                            "js"    => null,
                                            "width" => 500,
                                            "height"=> 450,
                                            "modal"=> true,
                                            "params"=> ""
                                        ),

        'canDelete'             => false,
        'delete'                => array(
                                            "url"   => "ajax/acciones-listado.php",
                                            "params"=> ""
                                    ),



        'canAdd'                => false,
        'add'                   => array(
                                            
                                            "caption"  => "Nuevo",
                                            "mode"  => "fancy",
                                            "js"   => null,            
                                            "url"   => null,
                                            "width" => 500,
                                            "height"=> 450,
                                            "modal"=> true,
                                            "params"=> ""
                                        ),
        'canOrder'              => true,
        'orderFieldIgnore'      => 'phone',
        'orderBy'               => null,

        'hasStatus'             => true,
        'canChangeStatus'       => false,
        'changeStatus'          => array(
                                            "url"   => "ajax/acciones-listado.php",
                                            "params"=> ""
                                    ),

        'fields'                => array(),
        
        
        //Campo para representar el ID
        'fieldId'               => 'id',

        'encryptFieldId'        => false,

        //Campo para representar el GUID
        'fieldIdEncrypted'      => 'guid',

        //Campo para representar el estado
        'fieldStatus'           => 'estado',

        'extendedFilters'	=> array(),
        
        'extendedActions'       => array(),
        'groupExtendedActions'  => true,
        
        'stateEnabledValue'     => 1,
        'stateDisabledValue'    => -1,
        'stateUnmoderatedValue' => 0,


        'usingPaginator'        => true,
        'resPerPage'            => 50,
        'currentPage'           => 1,
        'totalPages'            => 1,
        'totalResults'          => 1,

        'canExport'             => true,
        'fileNameExport'        => 'export',

        
        //Events
        'jsEvents'                => array(
            "onChangeStatus" => "",
            "onEdit" => "",
            "onAdd" => "",
            "onDelete" => ""
        ),
        

        'table'                 => null,
        'dataSource'            => array(
                                            "class"  => "",
                                            "method" => "",
                                            "filter_type" => "sql"
                                   )




    );


    private $_states = array();
    private $_gender = array(

        'F' => 'Femenino',
        'M' => 'Masculino'

    );

    private $_htmlFilter = "";
    private $_numfilters = 0;
    private $_filtersNames = array();
    private $_jqueryOnLoad = array();
    private $_extendedActions = "";
    private $_extendedFilters = array();


    private $_data = null;
    private $_isSetData = false;
    
    private $_externalData = array();

    private $_hasExternalData = false;

    public function addExternalData($key,$table,$keyID="id",$keyValue="descripcion",$order=null,$params=null){

        if (!array_key_exists($key, $this->_externalData)){
            $opt = array(
                "table"     => $table,
                "keyId"     => $keyID,
                "keyValue"  => $keyValue,
                "params"    => $params,
                "data"      => null
            );


            $sql = "SELECT $keyID,$keyValue FROM " . DB_PREFIX . $table . ( $order != null ? " ORDER BY $order" : "");

            $resp = Ftl_ClaseBase::getDB()->fetchAllAssoc($sql);

            if ($resp){
                $data = array();
                foreach( $resp as $i => $reg ){
                    $data[$reg[$keyID]] = $reg[$keyValue];
                }
                $opt["data"] = $data;
            }
                


            $this->_externalData[ $key ] = $opt;
            $_hasExternalData = true;
        }

    }

    public function setData ( $data )
    {
        $this->_data = $data;
        $this->_isSetData = true;
    }
    public function getData (  )
    {
        return $this->_data;
    }

    public function setTotalResults ( $val )
    {
        $this->_opt['totalResults'] = $val;
    }

    public function getCurrentPage ()
    {
        return $this->_opt['currentPage'];
    }

    public function getResPerPage ()
    {
        return $this->_opt['resPerPage'];
    }

    public function getOrderByForSql ()
    {
        $aux = array();

        //var_dump($this->_mapOrder);

        foreach ( $this->_mapOrder as $k => $v )
        {
            $aux[] = $k . ' ' . $v;
        }

        return implode(',',$aux);
    }
    public function getFieldsForSql ()
    {
        $aux = array();

        //var_dump($this->_mapOrder);

        foreach ( $this->_opt['fields'] as $k => $v )
        {
            $aux[] = $k;
        }

        return implode(',',$aux);
    }
    public function getFilters()
    {
        $res = array();

        $this->_getFilters($this->_opt['fields'], $res, false);
        $this->_getFilters($this->_opt['extendedFilters'], $res, false);

        //var_dump($res);

        return $res;
    }
    public function getFiltersForSql()
    {
        $res = array();
        $value = "";

        $this->_getFilters($this->_opt['fields'], $res, true);
        $this->_getFilters($this->_opt['extendedFilters'], $res, true);
        

        if (count($res) > 0)
            return implode ("AND ",$res);
        else
            return "";
    }

    public function __construct( $options = array() ) {

        $this->_io = new Ftl_IOHelper();
        $this->_io->addFromArray($_REQUEST);
        

        if ( isset( $options['orderFieldIgnore'] ) )
                $this->_opt['orderFieldIgnore'] .= $options['orderFieldIgnore'];

        $this->_opt = Ftl_ArrayUtil::merge($this->_opt,$options);

        //var_dump($this->_opt['fields']);

        $this->_opt[ 'orderFieldIgnore' ] = explode(',', $this->_opt[ 'orderFieldIgnore' ]);

        $this->_states[ $this->_opt['stateEnabledValue'] ]       = 'Habilitado';
        $this->_states[ $this->_opt['stateDisabledValue'] ]      = 'Inabilitado';
        $this->_states[ $this->_opt['stateUnmoderatedValue'] ]   = 'Sin moderar';


        $this->_opt['currentPage'] = $this->_io->getEscaped('page',1);

        if ( count($this->_opt['extendedActions']) > 0 )
        {
            $str_mode = "";
            $aux = "";
            foreach ( $this->_opt['extendedActions'] as $action => $options )
            {
                $str_mode = "";
	    	$querystring = "?" . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,id,encrypt',false) ."&amp;action={$action}&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=:id";
                

                switch ( $options[ 'mode' ] ){

                    case "js":
                        $str_mode .= "href=\"javascript:void(0);\" " . ( isset( $options[ 'fn' ] ) ?  "onclick=\"" . $options[ 'fn' ] . "(:id);\" ": "" );
                        break;
                    case "fancy":
                        $str_mode .= "href=\"javascript:void(0);\" onclick=\"$.fancybox.open({closeBtn: false,type : 'iframe',href : '" .(isset($options[ 'url' ]) ? $options[ 'url' ] :  Ftl_Path::getFileName()) . $querystring . "&amp;fancy=1"  ."'";
                        if (isset( $options[ 'width' ]))
                            $str_mode .= ",width:".$options[ 'width' ]."";
                        if (isset( $options[ 'height' ]))
                            $str_mode .= ",height:".$options[ 'height' ]."";
                        $str_mode .= "});\" ";
                    case "page":
                    default:
                        $str_mode .= "href=\"" .((isset($options[ 'url' ]) ? $options[ 'url' ] :  Ftl_Path::getFileName())) . $querystring . ((isset($options[ 'params' ]) ? $options[ 'params' ] :  ''))."\" " . ( isset( $options[ 'js' ] ) ? " onclick=\"" . $options[ 'js' ] . "\"" : "" ) ."\" ";
                        break;

                }

                //$aux .= "<li class=\"ui-state-default ui-corner-all info-tooltip\" title=\"{$options[ 'title' ]}\"><a href=\"" .(isset($options[ 'url' ]) ? $options[ 'url' ] : ( isset( $options[ 'js' ] ) ? "javascript:void(0);" : Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,id,encrypt',false) ."&amp;action={$action}&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=:id"  )) ."\"" . ( isset( $options[ 'js' ] ) ? " onclick=\"" . $options[ 'js' ] . "\"" : "" ) . "  accion=\"{$options[ 'title' ]}\" id=\"{$action}Row_:id\" title=\"{$options[ 'title' ]}\" ><span class=\"ui-icon {$options[ 'ui_icon' ]}\"></span></a></li>\n";
                $aux .= "<li class=\"ui-state-default ui-corner-all\" title=\"{$options[ 'title' ]}\"><a  $str_mode accion=\"{$options[ 'title' ]}\" id=\"{$action}Row_:id\" title=\"{$options[ 'title' ]}\" ><span class=\"ui-icon {$options[ 'ui_icon' ]}\"></span><span class=\"title\">{$options[ 'title' ]}</span></a></a></li>\n";
            }
            $this->_extendedActions = $aux;

        }

        $this->generateOrder();

//        if ( $this->_io->get('action','') != '' ){
//            $aParams = array();
//            switch ($this->_io->get('action'))
//            {
//                case 'edit':
////                    if ( isset($this->_opt[ 'urlEdit' ]) ){
////                        $params     = Ftl_ArrayUtil::toQueryString($_REQUEST, false,'action,id,encrypt');
////                        $urlEdit    = $this->_opt[ 'urlEdit' ].'?id='.$this->_io->get('id').($params ? '&params='.rawurlencode($params) : '');
////                        Ftl_Redirect::toPage($urlEdit);
////                    }
//                    break;
//                case 'changeStatus':
//                    if ( isset($this->_opt[ 'table' ]) ) {
//
//                        $aParams['table']   = $this->_opt[ 'table' ];
//                        $aParams['status']  = $this->_io->get('status');
//                        $aParams['id']      = $this->_io->get('id');
//                        $aParams['encrypt'] = $this->_io->get('encrypt');
//
//                        $this->_doAction($this->_io->get('action'),$aParams);
//                    }
//
//                    break;
//                case 'delete':
//                    if ( isset($this->_opt[ 'table' ]) ) {
//                        $aParams['table']   = $this->_opt[ 'table' ];
//                        $aParams['id']      = $this->_io->get('id');
//                        $aParams['encrypt'] = $this->_io->get('encrypt');
//
//                        $this->_doAction($this->_io->get('action'),$aParams);
//                    }
//                    break;
//           }
//
//        }

//        if ($this->_io->get('export','0') == '1')
//        {
//            Ftl_Header::XLS($this->_opt['fileNameExport']);
//            $this->export();
//            exit();
//        }

    }




    public function show()
    {

        if ($this->_io->get('export','0') == '1')
        {
            
            $this->export();
            exit();
        }        
        
        
        $data = $this->_getData();
            
            
        $this->_numColumns = 0;




        $html = "<div id=\"table-content\"><table border=\"0\"   cellpadding=\"0\" cellspacing=\"0\" id=\"product-table\" class=\"ui-widget ui-widget-content ui-corner-all\">\n";


        if ( count ( $this->_opt[ 'fields' ] ) > 0 )
        {
            //$this->_numColumns = count ( $this->_opt[ 'fields' ] );



            //$html.= "<tr class=\"ui-filter-titlebar ui-widget-header ui-corner-all\">\n";
            $html.= "<tr>\n";

            if ( $this->_opt[ 'toggleAll' ] )
            {
                $this->_numColumns += 1;
                $this->_jqueryOnLoad['toogle'] = "  $('#chkAll').change(function (){\n
                                                        $('input[name=chkRow]').attr('checked', $(this).is(':checked'));\n
                                                    });\n";
                //$html.= "<th class=\"table-header-check\"><input id=\"chkAll\" name=\"chkAll\" type=\"checkbox\"/></th>\n";
                $html.= "<th ><input id=\"chkAll\" name=\"chkAll\" type=\"checkbox\"/></th>\n";

            }

            

            if ( $this->_opt[ 'showActions' ])
            {
                $this->_numColumns += 1;
                $this->_jqueryOnLoad['tooltip'] = "UI.tooltip('li.info-tooltip');\n";
                //$html.= "<th class=\"table-header-options line-left\"><span>Opciones</span></th>\n";
                $html.= "<th class=\"ui-state-default line-left\"><span>Opciones</span></th>\n";
            }
            
            $html.= $this->parseHeaderColumn();

            $html.= "</tr>\n";

        }

        //var_dump($data);
        
        if ($data === NULL)
        {
            $html.= '<tr><td colspan="' . $this->_numColumns . '" style="text-align:center"> No se encontraron resultados </td></tr>';
        }
        else
        {
            if (isset($data[0]['total']))
                $this->setTotalResults ($data[0]['total']);

            $identifier = 0;
            foreach ( $data as $i => $cols )
            {
                $this->_dataRow = $cols;
                $identifier = (($this->_opt[ 'encryptFieldId' ]) ? $cols[$this->_opt[ 'fieldIdEncrypted' ]] : $cols[$this->_opt[ 'fieldId' ]]);

                $html .= "<tr " . ($i % 2 == 0 ? "" : "class=\"alternate-row\"") . ">\n";

                if ( $this->_opt[ 'toggleAll' ] )
                {
                    $html.= "<td><input id=\"chkRow_{$identifier}\" name=\"chkRow\"  type=\"checkbox\" value=\"{$identifier}\"/></td>\n";
                }
                $html .= $this->parseOptionsColumn($cols);
                foreach( $this->_opt [ 'fields' ] as $field => $options )
                {
                    $html.= $this->parseDataColumn($field, $cols[$field]);
                }

                
                $html .= "</tr>\n";
            }

        }

        $html.= '</table></div>';

        if ( $this->_opt [ 'showPager' ] !== false )
            $html .= $this->drawFooter();

        echo "<form id=\"mainform\" name=\"mainform\" method=\"GET\" action=\"". Ftl_Path::getFileName() . ($this->_numfilters > 0 ? '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, implode(',',$this->_filtersNames),false) : "") . "\">\n";
        if ($this->_numfilters > 0){
            echo "<div id=\"accordion\"><div class=\"ui-state-active ui-helper-clearfix  ui-filter-titlebar\"><span class=\"ui-filter-title\">Filtros</span></div><div class=\"ui-filter-content\">" . str_replace ("numfilters", $this->_numfilters, $this->_htmlFilter);
        }
        
        echo "<input type=\"hidden\" name=\"orderBy\" value=\"".(($this->_opt['canOrder'] == true ) ? $this->_io->get('orderBy',$this->_opt['orderBy']) : $this->_opt['orderBy'] )."\" />\n<input type=\"hidden\" name=\"export\" id=\"exportxls\" value=\"0\" /></form>\n";

        $this->_jqueryOnLoad["ui-button"] = "UI.estilarBotones();\n";
        //$this->_jqueryOnLoad["screen"] = "$('#accordion').css('max-width',screen.width - 80 +'px');\n";
        $this->_jqueryOnLoad["screen"] = "  $('ul#icons').css('height','auto');\n
                                            $('#table-content,#accordion').css('max-width',screen.width - 80 +'px');\n
                                            w_tabla = $('#product-table').css('width').replace('px','');\n
                                            w_div = $('#table-content').css('width').replace('px','');\n

                                            if ( parseInt(w_tabla) < parseInt(w_div) )\n
                                            {\n
                                                $('#product-table').css('width',(parseInt(w_div)-20)+'px');\n
                                            }\n";
        //$this->_jqueryOnLoad["ui-select"] = "$('.tbl-filtros select').selectmenu();\n";
        $this->_jqueryOnLoad["change-status"] = "$('.change-status').click(function (){\n
                        _state = $(this).attr('state');\n
                        _urlState = $(this).attr('url');\n
                        if (confirm('Deséa cambiar el estado del registro a ' + _state + '?')){\n
                            UI.showModalLoader();\nJS.ajax.llamada({\n
                                dataType: 'json',\n
                                url : _urlState,\n
                                alFinalizar: function (json){\n
                                                UI.hideModalLoader();\n".(
                                                    isset($this->_opt [ 'jsEvents' ]) && isset($this->_opt [ 'jsEvents' ][ 'onChangeStatus' ]) && $this->_opt [ 'jsEvents' ][ 'onChangeStatus' ] != "" ? $this->_opt [ 'jsEvents' ][ 'onChangeStatus' ]."(json);":"if (json.state == 1){\n
                                                    location.reload();return;\n
                                                }else{\n
                                                    UI.alert(json.error.msg,{title:'Atención'});\n
                                                }\n"
                                                )."

                               }\n
                            });\n
                         }\n
                     });\n";
        $this->_jqueryOnLoad["delete-selection"] = "$('#btnDeleteRows').click(function (){\n
                        chkSelected = $(\"input[name='chkRow']:checked\").serializeArray();\n
                        aSelected = [];

                        
                        if (chkSelected.length > 0){
                            if (confirm('Está seguro de eliminar los registros seleccionados?')){\n
                                UI.showModalLoader();\n
                                for(i=0;i<chkSelected.length;i++){\n
                                    aSelected.push(chkSelected[i].value);\n
                                }\n                                
                                JS.ajax.llamada({\n
                                    dataType: 'json',\n
                                    url : '" . ($this->_opt[ 'delete' ] != null && $this->_opt[ 'delete' ][ 'url' ] != null ? $this->_opt[ 'delete' ]['url'] : "ajax/acciones-listado.php" ). "?action=deleteSelected&encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&table=" . $this->_opt['table'] . "&fancy=1" . ($this->_opt[ 'delete' ]['params'] != null ? "&" . $this->_opt[ 'delete' ]['params'] : "") . "',\n
                                    data: {id:aSelected.join(',')},\n
                                    alFinalizar: function (json){\n
                                                    UI.hideModalLoader();\n".(
                                                        isset($this->_opt [ 'jsEvents' ]) && isset($this->_opt [ 'jsEvents' ][ 'onDelete' ]) && $this->_opt [ 'jsEvents' ][ 'onDelete' ] != "" ? $this->_opt [ 'jsEvents' ][ 'onDelete' ]."(json);":"if (json.state == 1){\n
                                                        location.reload();return;\n
                                                    }else{\n
                                                        UI.alert(json.error.msg,{title:'Atención'});\n
                                                    }\n"
                                                    )."

                                   }\n
                                });\n                            
                            }\n 
                        }\n 
                     });\n";
        if (count( $this->_jqueryOnLoad ) > 0 )
        {
            $html .= "<script type=\"text/javascript\">\n";
            $html .= "$(document).ready(function (){\n";

            foreach ($this->_jqueryOnLoad as $k=>$v)
            {
                $html .= $v . "\n";
            }
            $html .= "});\n";

            

            $html .= "</script>\n";
        }

        echo $html;

    }

    public function export()
    {
        Ftl_Header::XLS($this->_opt['fileNameExport']);
        $data = $this->_getData(true);

        $this->_numColumns = 0;




        $html = "<table>";


        if ( count ( $this->_opt[ 'fields' ] ) > 0 )
        {
            $this->_numColumns = 0;//count ( $this->_opt[ 'fields' ] );



            $html.= "<tr>";


            $html.= $this->parseHeaderColumn(true);


            $html.= "</tr>\n";

        }

        if ($data == NULL)
        {
            $html.= '<tr><td colspan="' . $this->_numColumns . '" style="text-align:center"> No se encontraron resultados </td></tr>';
        }
        else
        {
            if (isset($data[0]['total']))
                $this->setTotalResults ($data[0]['total']);

            $identifier = 0;
            foreach ( $data as $i => $cols )
            {

                $identifier = (($this->_opt[ 'encryptFieldId' ]) ? $cols[$this->_opt[ 'fieldIdEncrypted' ]] : $cols[$this->_opt[ 'fieldId' ]]);

                $html .= "<tr>";


                foreach( $this->_opt [ 'fields' ] as $field => $options )
                {
                    $html.= $this->parseDataColumn($field, $cols[$field], true);
                }

                $html .= "</tr>\n";
            }

        }

        $html.= '</table>';
        /*$html .= $this->drawFooter();*/


        echo $html;

    }


    private function drawFooter ()
    {
        //$aux = "<tfoot><tr><td colspan=\"{$this->_numColumns}\" class=\"foot\">\n";
        $aux = "";
        if ( $this->_opt['canExport'] || $this->_opt['canAdd'] || $this->_opt[ 'toggleAll' ] ){
            
            //$aux = "<div style=\"float: left;\"><a href=\"javascript:void(0);\" onclick=\"exportXLS();\"><img src=\"images/shared/btn_exportar.gif\"></a></div>";
            //$aux .=  "<div style=\"float: left;\">";
            $aux .=  "<div>";
            if ( $this->_opt['canAdd'] ){
                
                switch ( $this->_opt[ 'add' ][ 'mode' ] ){

                    case "fancy":
                        $aux_add = "onclick=\"$('<a id=\'fancybox-frame\'></a>').fancybox({closeBtn: false,type : 'iframe'";
                        $aux_add .= ",href : '" . ($this->_opt[ 'add' ] != null && $this->_opt[ 'add' ][ 'url' ] != null ? $this->_opt[ 'add' ]['url'] : "am-" . Ftl_Path::getFileName()  )."?fancy=1" . ($this->_opt[ 'add' ]['params'] != null ? "&amp;" . $this->_opt[ 'add' ]['params'] : "") . "'";
                        $aux_add .= ($this->_opt[ 'add' ] != null && $this->_opt[ 'add' ][ 'width' ] != null ? ",width:" . $this->_opt[ 'add' ]['width']  : ""  ) ;
                        $aux_add .= ($this->_opt[ 'add' ] != null && $this->_opt[ 'add' ][ 'height' ] != null ? ",height:" . $this->_opt[ 'add' ]['height']  : ""  ) ;
                        $aux_add .= ($this->_opt[ 'add' ] != null && $this->_opt[ 'add' ][ 'modal' ] != null ? ",modal:" . $this->_opt[ 'add' ]['modal']  : ""  ) ;
                        $aux_add .= ",afterClose : function() {".(isset($this->_opt [ 'jsEvents' ]) && isset($this->_opt [ 'jsEvents' ][ 'onAdd' ]) && $this->_opt [ 'jsEvents' ][ 'onAdd' ] != "" ? $this->_opt [ 'jsEvents' ][ 'onAdd' ]."();":"location.reload();")."return;}}).trigger('click');\"";
                        break;
                    case "page":
                    default:
                        $aux_add = "onclick=\"document.location = '" .($this->_opt[ 'add' ] != null && $this->_opt[ 'add' ][ 'url' ] != null ? $this->_opt[ 'add' ]['url'] : "am-" . Ftl_Path::getFileName()  ). ($this->_opt[ 'add' ]['params'] != null ? "?" . $this->_opt[ 'add' ]['params'] : "") . "';\" " . ( isset( $this->_opt[ 'add' ][ 'js' ] ) ? " onclick=\"" . $this->_opt[ 'add' ][ 'js' ] . "\"" : "" ) ."\" ";
                        break;

                }   
                
                
                $aux .=  "<button class=\"ui-button\" id=\"btnAdd\" ui-icon=\"ui-icon-plusthick\" $aux_add>". $this->_opt[ 'add' ][ 'caption' ] ."</button>";
            }
            if ( $this->_opt['canExport'] ){
                $aux .=  "<button class=\"ui-button\" id=\"btnExport\" ui-icon=\"ui-icon-calculator\" onclick=\"exportXLS();\">Exportar</button>";
            }
            if ( $this->_opt[ 'toggleAll' ] ){
                if ($this->_opt[ 'canDelete' ]){
                    $aux .=  "<button class=\"ui-button\" id=\"btnDeleteRows\" ui-icon=\"ui-icon-trash\">Eliminar Sel.</button>";
                }
                if ($this->_opt[ 'canChangeStatus' ]){
                    $aux .=  "<button class=\"ui-button\" id=\"btnChangeStatus1Rows\" ui-icon=\"ui-icon-check\">Aprobar</button>";
                    $aux .=  "<button class=\"ui-button\" id=\"btnChangeStatus0Rows\" ui-icon=\"ui-icon-closethick\">Rechazar</button>";
                }
                //$aux .=  "<button class=\"ui-button\" id=\"btnExport\" ui-icon=\"ui-icon-calculator\" onclick=\"exportXLS();\">Exportar</button></div>";
            }
            $aux .=  "</div>";
            $aux .= "<script type=\"text/javascript\">\n";
//            $aux .= "function eliminarSeleccion(){\n";
//            $aux .= "console.debug($(\"input[name='chkRow']:checked\").serializeArray());\n";
//            $aux .= "}\n";
            $aux .= "function exportXLS(){\n";
            $aux .= "document.mainform.exportxls.value ='1';\n";
            $aux .= "document.mainform.submit();\n";
            $aux .= "document.mainform.exportxls.value ='0';\n";
            //$aux .= "$( \"#mainform\" ).attr('action','".  Ftl_Path::getFileName() . "?" . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'oper',false) . "');\n";
            $aux .= "}\n";
            $aux .= "</script>\n";
        }
        $aux .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"paging-table\"><tr>";

        if ($this->_opt['toggleAll'])
        {
//            $aux.="<td><div id=\"actions-box\">\n
//				<a class=\"action-slider\" href=\"javascript:void(0);\"></a>\n
//				<div id=\"actions-box-slider\">\n
//					<a class=\"action-edit\" href=\"\">Edit</a>\n
//					<a class=\"action-delete\" href=\"javascript:void(0);\">Delete</a>\n
//				</div>\n
//				<div class=\"clear\"></div>\n
//			</div></td>\n";
        }

        $aux.="<td>{$this->_opt['totalResults']} registro/s encontrado/s</td>\n";
        if ($this->_opt [ 'usingPaginator' ])
        {
            $this->_opt['totalPages'] = ceil ( $this->_opt['totalResults'] / $this->_opt['resPerPage'] );

            $currentPage    = $this->_opt['currentPage'];
            $totalPages     = $this->_opt['totalPages'];

            $aux .= "<td>\n";

            $first  = ( $totalPages > 1 && $currentPage > 1  ) ? '<a class="page-far-left" href="' . Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'page',false) . '&amp;page=1"></a>':'<div class="page-far-left"></div>';
            $ant    = ( $totalPages > 1 && $currentPage > 1  ) ? '<a class="page-left" href="'. Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'page',false) . '&amp;page=' . ($currentPage - 1) .'"></a>':'<div class="page-left"></div>';
            $sig    = ( $totalPages > 1 && $currentPage < $totalPages  ) ? '<a class="page-right" href="'. Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'page',false) . '&amp;page=' . ($currentPage + 1) .'"></a>' : '<div class="page-right"></div>';
            $last   = ( $totalPages > 1 && $currentPage < $totalPages  ) ? '<a class="page-far-right" href="'. Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'page',false) . '&amp;page=' . ($totalPages) .'"></a>' : '<div class="page-far-right"></div>';

            $aux .= $first.$ant."\n";
            $aux .= "<select onchange=\"javascript:document.location='". Ftl_Path::getFileName() . "?" . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'page',false) . "&amp;page=' + $('#cmbPaginas').val();\" id=\"cmbPaginas\" style=\"float:left;margin-left: 4px;margin-right: 2px;margin-top: 1px;\">";

            for($i=1 ; $i <= $totalPages; $i++)
            {
                $aux .= "<option value=\"$i\" ". ($i==$currentPage ? "selected" : "") .">$i</option>\n";
            }
            $aux .= "</select>\n";
            $aux .= $sig.$last."\n";
            $aux .= "</td>\n";

        }


        $aux.="</tr></table>\n";

        return $aux;

    }

    private function parseHeaderColumn( $export = false )
    {

        $aux            = '';
        $filterType     = '';

        foreach ( $this->_opt [ 'fields' ] as $field => $options )
        {
            $cssSpanTitle   = '';
            $imgOrdesAsc    = '';
            $imgOrdesDesc   = '';
            
            if (!$export){
                $title = $options['title'];
            }else{
                $title = Ftl_UTF8::decode($options['title']);
            }


            if ( !$export && $this->_opt['canOrder'] && !in_array($field, $this->_opt['orderFieldIgnore']) )
            {
                $cssSpanTitle   = '';
                //$imgOrdesAsc    = ( array_key_exists($field, $this->_mapOrder) && strtolower($this->_mapOrder[ $field ]) == 'asc' ) ? '<img src="images/table/order-selected-asc.png"/>' : '<img src="images/table/order-asc.png"/>';
                $imgOrdesAsc    = ( array_key_exists($field, $this->_mapOrder) && strtolower($this->_mapOrder[ $field ]) == 'asc' ) ? ' ui-state-hover ui-corner-all' : ' ui-state-default ui-corner-all';
                $imgOrdesAsc    = '<a class="lnk-orden'.$imgOrdesAsc.'" href="' . Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'orderBy',false) . $this->getOrderByForParam($field, 'ASC') . '"><span class="ui-icon ui-icon-triangle-1-n"></span></a>';

                //$imgOrdesDesc   = ( array_key_exists($field, $this->_mapOrder) && strtolower($this->_mapOrder[ $field ]) == 'desc' ) ? '<img src="images/table/order-selected-desc.png"/>': '<img src="images/table/order-desc.png"/>';
                $imgOrdesDesc   = ( array_key_exists($field, $this->_mapOrder) && strtolower($this->_mapOrder[ $field ]) == 'desc' ) ? ' ui-state-hover ui-corner-all': ' ui-state-default ui-corner-all';
                //$imgOrdesDesc    = '<a class="lnk-orden" href="' . Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'orderBy',false) . $this->getOrderByForParam($field, 'DESC') . '">' . $imgOrdesDesc . '</a>';
                $imgOrdesDesc    = '<a class="lnk-orden'.$imgOrdesDesc.'" href="' . Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'orderBy',false) . $this->getOrderByForParam($field, 'DESC') . '"><span class="ui-icon ui-icon-triangle-1-s"></span></a>';

            }

            if (!$export && ( !isset($options['show']) || (isset($options['show']) && $options['show'] == true ) )){
                    $this->_numColumns +=1;
                    //$aux .= "<th class=\"table-header-repeat line-left\"><span " . $cssSpanTitle . ">" . $options['title'] . "</span>" . $imgOrdesAsc . $imgOrdesDesc . "</th>\n";
                    $aux .= "<th class=\"ui-state-default line-left\"><h3 " . $cssSpanTitle . "><div style=\"width: 18px; float: left;\">".$imgOrdesAsc . $imgOrdesDesc . "</div><span class='titulo'>" . $title . "<span></h3></th>\n";
            }else{
                if ($export && (!isset($options['export']) || (isset($options['export']) && $options['export'] == true )) )
                    $aux .= "<th>"  . $title .  "</th>\n";
            }
            if ( isset ($options[ 'filter' ] ) && $options[ 'filter' ] )
            {
                $defValue = (isset ($options[ 'value' ] ) ? $options[ 'value' ] : null);
                $this->_numfilters += 1;

                $this->_htmlFilter .= "<div class=\"filtrito\"><table class=\"tbl-filtros\"><tr><td class=\"title\" ><span>{$title}</span></td><td class=\"content\">";
                if (!$export){
                if ( isset($options[ 'type' ]) )
                {
                    switch ( $options[ 'type' ] )
                    {
                        case 'gender':
                            $this->_htmlFilter .= "<select id=\"".$this->replaceColName($field)."\" name=\"".$this->replaceColName($field)."\" ><option value=\"\">--</option>\n";
                            foreach($this->_gender as $k => $v)
                            {
                                $this->_htmlFilter .= "<option value=\"$k\" ". ($this->_io->get($this->replaceColName($field),$defValue) == $k ? "selected" : "") . ">$v</option>\n";
                            }
                            $this->_htmlFilter .= "</select>";
                            array_push($this->_filtersNames, $field);
                            break;
                        case 'datetime':
                        case 'date':
                            $defValueD = null;
                            $defValueH = null;
                            if ($defValue != null){
                                $defValue = explode('|',$defValue);
                                $defValueD = ( isset($defValue[0]) ? $defValue[0] : null);
                                $defValueH = ( isset($defValue[1]) ? $defValue[1] : null);
                            }
                            $this->_htmlFilter .= "<input type=\"text\" name=\"{$this->replaceColName($field)}_d\" value=\"{$this->_io->get($this->replaceColName($field).'_d',$defValueD)}\" class=\"input-text ui-widget ui-corner-all datepicker\" /> a <input type=\"text\" name=\"{$this->replaceColName($field)}_h\" value=\"{$this->_io->get($this->replaceColName($field).'_h',$defValueH)}\" class=\"input-text ui-widget ui-corner-all datepicker\" />\n";
                            $this->_jqueryOnLoad["datepicker"] = "$( \".datepicker\" ).datepicker({dateFormat: 'yy-mm-dd'});";
                            array_push($this->_filtersNames, $field.'_d');
                            array_push($this->_filtersNames, $field.'_h');

                            break;
                        case "state":
                            $this->_htmlFilter .= "<select id=\"{$this->replaceColName($field)}\" name=\"{$this->replaceColName($field)}\" ><option value=\"\" ". ($this->_io->get($this->replaceColName($field), (isset($defValue) ? $defValue : 2)) == 2 ? "selected" : "") . ">--</option>\n";
                            foreach($this->_states as $k => $v)
                            {
                                $this->_htmlFilter .= "<option value=\"$k\" ". ($this->_io->get($this->replaceColName($field),$defValue) != "" && $this->_io->get($this->replaceColName($field),$defValue)     == $k ? "selected" : "") . ">$v</option>\n";
                            }
                            $this->_htmlFilter .= "</select>";
                            array_push($this->_filtersNames, $field);
                            break;
                        case "assoc":

							$this->_htmlFilter .= "<select id=\"".$this->replaceColName($field)."\" name=\"".$this->replaceColName($field)."\" ><option value=\"\" ". ($this->_io->get($this->replaceColName($field),(isset($defValue) ? $defValue : '')) == '' ? "selected" : "") . ">--</option>\n";

							if (isset($options[ 'data' ]) && is_array($options[ 'data' ])){

								foreach($options[ 'data' ] as $k => $v)
								{
									$this->_htmlFilter .= "<option value=\"$k\" ". ($this->_io->get($this->replaceColName($field),$defValue) != "" && $this->_io->get($this->replaceColName($field),$defValue)     == $k ? "selected" : "") . ">$v</option>\n";
								}

							}
                            $this->_htmlFilter .= "</select>";
                            array_push($this->_filtersNames, $field);
                            break;
                        case "external":

							$this->_htmlFilter .= "<select id=\"".$this->replaceColName($field)."\" name=\"".$this->replaceColName($field)."\" ><option value=\"\" ". ($this->_io->get($this->replaceColName($field),(isset($defValue) ? $defValue : '')) == '' ? "selected" : "") . ">--</option>\n";

							if (isset( $this->_externalData[ $options[ 'key' ] ] ) && is_array( $this->_externalData[ $options[ 'key' ] ][ 'data' ] )){

								foreach($this->_externalData[ $options[ 'key' ] ][ 'data' ] as $k => $v)
								{
									$this->_htmlFilter .= "<option value=\"$k\" ". ($this->_io->get($this->replaceColName($field),$defValue) != "" && $this->_io->get($this->replaceColName($field),$defValue)     == $k ? "selected" : "") . ">$v</option>\n";
								}

							}
                            $this->_htmlFilter .= "</select>";
                            array_push($this->_filtersNames, $field);
                            break;
                        default :
                            $this->_htmlFilter .= "<input type=\"text\" id=\"{$this->replaceColName($field)}\" name=\"{$this->replaceColName($field)}\" class=\"input-text ui-widget ui-corner-all\" value=\"{$this->_io->get($this->replaceColName($field))}\"/>\n";
                            array_push($this->_filtersNames, $field);
                            break;
                    }
                }else {
                    $this->_htmlFilter .= "<input type=\"text\" id=\"{$this->replaceColName($field)}\" name=\"{$this->replaceColName($field)}\" class=\"input-text ui-widget ui-corner-all\" value=\"{$this->_io->get($this->replaceColName($field))}\"/>\n";
                    array_push($this->_filtersNames, $field);
                }
//                if ($this->_numfilters == 1){
//                    $this->_htmlFilter .= "</td><td rowspan=\"numfilters\" class=\"tdBuscar\"><button class=\"ui-button\" id=\"btnExport\" ui-icon=\"ui-icon-search\" onclick=\"document.mainform.submit();\">Filtrar</button></td></tr>";
//                    //$this->_htmlFilter .= "</td><td rowspan=\"numfilters\" class=\"tdBuscar\"><div><button class=\"ui-button\" id=\"btnBuscar\"  onclick=\"document.mainform.submit();\">Buscar</button></div></td></tr>";
//                }else {
                    $this->_htmlFilter .= "</td></tr></table></div>";
//                }
                }

            }

        }
        if ($this->_numfilters > 0){
            $this->_htmlFilter .= "<div class=\"filtrito\"><button class=\"ui-button\" id=\"btnBuscar\" ui-icon=\"ui-icon-search\" onclick=\"document.mainform.submit();\">Buscar</button></div><div class=\"clear\"></div></div></div>";
        }else{
            $this->_htmlFilter .= "<div class=\"clear\"></div></div></div>";
        }
        return $aux;

    }

    private function parseDataColumn ( $key , $value, $export=false )
    {
        
        
        $aux    = $value;
        $fields = $this->_opt['fields'];
        if (array_key_exists($key, $fields))
        {
            //if (!$export || ($export && ( isset($fields[$key]['export']) && $fields[$key]['export'] == true ))){
            if (!$export && ( !isset($fields[$key]['show']) || (isset($fields[$key]['show']) && $fields[$key]['show'] == true ) ) || ($export && ( !isset($fields[$key]['export']) || (isset($fields[$key]['export']) && $fields[$key]['export'] == true ) ))){
            if (isset($fields[$key]['type']))
            {

                switch($fields[$key]['type'])
                {
                    case "gender":
                        if (!is_null($aux))
                        {
                            $gender = (isset($fields[$key]['values'])) ? $fields[$key]['values'] : $this->_gender;
                            $aux    = (isset($gender[$aux])) ? $gender[$aux] : $aux;
                        }
                        break;
                    case "datetime":
                        if (!is_null($aux))
                        {
                            $format = ( isset( $fields[$key]['format'] ) ? $fields[$key]['format'] : self::DATETIME_FORMAT );
                            $aux = date( $format, strtotime( $aux ) );
                        }
                        break;
                    case "date":
                        if (!is_null($aux))
                        {
                            $format = ( isset( $fields[$key]['format'] ) ? $fields[$key]['format'] : self::DATE_FORMAT );
                            $aux = date( $format, strtotime( $aux ) );
                        }
                        break;
                    case "image":
                        if (!is_null($aux) && $aux != "")
                        {
                            $width  = (isset($fields[$key]['width'])) ? $fields[$key]['width'] : self::IMAGE_WIDTH;
                            $height = (isset($fields[$key]['height'])) ? $fields[$key]['height'] : self::IMAGE_HEIGHT;
                            
                            $folder = (isset($fields[$key]['folder'])) ? $fields[$key]['folder'] : '';

                            $aux    = "<a href=\"{$folder}{$aux}\" target=\"_blank\"><img border=0 width=$width height=$height src=\"{$folder}{$aux}\"/></a>";

                        }
                        break;
                    case "file":
                        if (!is_null($aux))
                        {
                            $folder = (isset($fields[$key]['folder'])) ? $fields[$key]['folder'] : '';
                            $aux    = "<a href=\"{$folder}{$aux}\" target=\"_blank\">{$aux}</a>";
                        }
                        break;
                    case "state":

                        if (!is_null($aux))
                        {
                            $state  = (isset($fields[$key]['values'])) ? $fields[$key]['values'] : $this->_states;

                            $aux    = (isset($state[$aux])) ? $state[$aux] : $aux;
                        }

                        break;
                    case "assoc":

                        if (!is_null($aux))
                        {
                            if (isset($fields[$key]['data']) && is_array($fields[$key]['data']))
                            {
                                $aux = (isset($fields[$key]['data'][$value]) ? $fields[$key]['data'][$value] : $value);
                            }
                                else
                                        $aux    = $value;
                        }

                        break;
                    case "external":

                        if (!is_null($aux))
                        {
                            if (isset( $this->_externalData[ $fields[$key][ 'key' ] ] ) && is_array($this->_externalData[ $fields[$key][ 'key' ] ]['data']))
                            {
                                $aux = (isset($this->_externalData[ $fields[$key][ 'key' ] ]['data'][$value]) ? $this->_externalData[ $fields[$key][ 'key' ] ]['data'][$value] : $value);
                            }
                            else{
                                $aux    = $value;
                            }
                            
                        }

                        break;
                    default:
                        if (!is_null($aux))
                        {
                            if (isset($fields[$key]['size']))
                            {
                                $aux = substr($aux, 0, $fields[$key]['size']);
                            }
                        }
                        break;


                }

            }

            if ($export)
                return "<td>&nbsp;" .  Ftl_UTF8::decode ($aux) . "</td>\n";
                //return "<td>{$aux}</td>\n";
            else
                //return "<td>" .  Ftl_UTF8::encode ($aux) . "</td>\n";
               
                //return "<td data-name=\"col_{$key}\">" . (isset($fields[$key]['link']) ? "<a href='" .$fields[$key]['link']. '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt,table',false)."&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $this->_dataRow[$this->_opt['fieldIdEncrypted']] : $this->_dataRow[$this->_opt['fieldId']])."'>{$aux}</a>" : $aux) . "</td>\n";
				return "<td data-name=\"col_{$key}\">" . (isset($fields[$key]['link']) ? "<a href='" .$fields[$key]['link']. '&amp;user_id=' . (($this->_opt['encryptFieldId']) ? $this->_dataRow[$this->_opt['fieldIdEncrypted']] : $this->_dataRow[$this->_opt['fieldId']])."'>{$aux}</a>" : $aux) . "</td>\n";
            }
        }
        

        return "";
    }

    private function parseOptionsColumn ($value)
    {
        $aux = "";
        $aux_extended = "";
        $aux_non_ext = "";
        $width = 0;

        if  ( $this->_opt['showActions'] )
        {
            $this->_jqueryOnLoad["filters"] = "$( \".options-collap\" ).accordion({ autoHeight: false,collapsible: true,active: false,heightStyle: 'fill'});";
            
            if ($this->_opt[ 'canEdit' ] == TRUE && $this->_opt[ 'edit' ] != null){
                //$aux.= "<ul id=\"icons\" class=\"ui-widget ui-corner-all\">";
                $width += 30;
                
                
                switch ( $this->_opt[ 'edit' ][ 'mode' ] ){

                    case "fancy":
                        $aux_edit = "href=\"javascript:void(0);\" onclick=\"$('<a id=\'fancybox-frame\'></a>').fancybox({closeBtn: false,type : 'iframe'";
                        $aux_edit .= ",href : '" . ($this->_opt[ 'edit' ] != null && $this->_opt[ 'edit' ][ 'url' ] != null ? $this->_opt[ 'edit' ]['url'] : "am-" . Ftl_Path::getFileName()  ). '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt',false) ."&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;fancy=1" . ($this->_opt[ 'edit' ]['params'] != null ? "&amp;" . $this->_opt[ 'edit' ]['params'] : "") . "'";
                        $aux_edit .= ($this->_opt[ 'edit' ] != null && $this->_opt[ 'edit' ][ 'width' ] != null ? ",width:" . $this->_opt[ 'edit' ]['width']  : ""  ) ;
                        $aux_edit .= ($this->_opt[ 'edit' ] != null && $this->_opt[ 'edit' ][ 'height' ] != null ? ",height:" . $this->_opt[ 'edit' ]['height']  : ""  ) ;
                        $aux_edit .= ($this->_opt[ 'edit' ] != null && $this->_opt[ 'edit' ][ 'modal' ] != null ? ",modal:" . $this->_opt[ 'edit' ]['modal']  : ""  ) ;
                        $aux_edit .= ",afterClose : function() {".(isset($this->_opt [ 'jsEvents' ]) && isset($this->_opt [ 'jsEvents' ][ 'onEdit' ]) && $this->_opt [ 'jsEvents' ][ 'onEdit' ] != "" ? $this->_opt [ 'jsEvents' ][ 'onEdit' ]."();":"location.reload();")."return;}}).trigger('click');\"";
                        break;
                    case "page":
                    default:
                        //$aux_edit = "href=\"" .($this->_opt[ 'edit' ] != null && $this->_opt[ 'edit' ][ 'url' ] != null ? $this->_opt[ 'edit' ]['url'] : "am-" . Ftl_Path::getFileName()  ). '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt',false) ."&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;" . ($this->_opt[ 'edit' ]['params'] != null ? "&amp;" . $this->_opt[ 'edit' ]['params'] : "") . "\" " . ( isset( $this->_opt[ 'edit' ][ 'js' ] ) ? " onclick=\"" . $this->_opt[ 'edit' ][ 'js' ] . "\"" : "" ) ."\" ";
                        $aux_edit = "href=\"" .($this->_opt[ 'edit' ] != null && $this->_opt[ 'edit' ][ 'url' ] != null ? $this->_opt[ 'edit' ]['url'] : "am-" . Ftl_Path::getFileName()  ). '?encrypt=' . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;" . ($this->_opt[ 'edit' ]['params'] != null ? "&amp;" . $this->_opt[ 'edit' ]['params'] : "") . "\" " . ( isset( $this->_opt[ 'edit' ][ 'js' ] ) ? " onclick=\"" . $this->_opt[ 'edit' ][ 'js' ] . "\"" : "" ) ."\" ";
                        break;

                }                
                
                
                
                
                $aux_non_ext.= "<li class=\"ui-state-default ui-corner-all\" title=\"Editar\"><a $aux_edit accion=\"edit\"  title=\"Editar\" ><span class=\"ui-icon ui-icon-pencil\"></span></a></li>";
                //$aux.= "</ul>";
                
            }            




            if ($this->_opt[ 'canDelete' ] == TRUE && $this->_opt[ 'delete' ] != null && $this->_opt[ 'toggleAll' ] !== true)
            {
                $width += 30;
                $aux_del = "href=\"javascript:void(0);\" onclick=\"if (confirm('Deséa eliminar el registro?')){\nUI.showModalLoader();\nJS.ajax.llamada({dataType: 'json'";
                $aux_del .= ",url : '" . ($this->_opt[ 'delete' ] != null && $this->_opt[ 'delete' ][ 'url' ] != null ? $this->_opt[ 'delete' ]['url'] : "ajax/acciones-listado.php" ). '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt,table',false) ."&amp;action=delete&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;table=" . $this->_opt['table'] . "&amp;fancy=1" . ($this->_opt[ 'delete' ]['params'] != null ? "&amp;" . $this->_opt[ 'delete' ]['params'] : "") . "'";
                $aux_del .= ",alFinalizar: function (json){\n
                                                UI.hideModalLoader();\n".(isset($this->_opt [ 'jsEvents' ]) && isset($this->_opt [ 'jsEvents' ][ 'onDelete' ]) && $this->_opt [ 'jsEvents' ][ 'onDelete' ] != "" ? $this->_opt [ 'jsEvents' ][ 'onDelete' ]."(json);":"if (json.state == 1){\nlocation.reload();return;\n}else{\nUI.alert(json.error.msg,{title:'Atención'});\n}\n");

                $aux_del .= "}})};\"";

                

                $aux_non_ext.= "<li class=\"ui-state-default ui-corner-all \" title=\"Eliminar\"><a $aux_del accion=\"delete\"  title=\"Eliminar\" class=\"info-tooltip\" ><span class=\"ui-icon ui-icon-trash\"></span><span class=\"title\">Eliminar</span></a></li>";
            }




            if ($this->_opt['hasStatus']  && $this->_opt[ 'toggleAll' ] !== true)
            {


                if ($this->_opt['canChangeStatus'] )
                {
                        $width += 30;
                        if ($value[$this->_opt['fieldStatus']] == $this->_opt['stateUnmoderatedValue'])
                        {
                            $width += 30;
                            $aux_non_ext.= "<li class=\"ui-state-default ui-corner-all\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateEnabledValue']]}\"><a class=\"change-status\"  state=\"{$this->_states[$this->_opt['stateEnabledValue']]}\" href=\"javascript:void(0);\" url=\"" . ($this->_opt[ 'changeStatus' ] != null && $this->_opt[ 'changeStatus' ][ 'url' ] != null ? $this->_opt[ 'changeStatus' ]['url'] : "ajax/acciones-listado.php" ). '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt,table',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;table=" . $this->_opt['table'] . "&amp;status=" . $this->_opt['stateEnabledValue'] . "&amp;fancy=1" . ($this->_opt[ 'changeStatus' ]['params'] != null ? "&amp;" . $this->_opt[ 'changeStatus' ]['params'] : "") . "\"><span class=\"ui-icon ui-icon-check\"></span><span class=\"title\">Cambiar a {$this->_states[$this->_opt['stateEnabledValue']]}</span></a></li>\n";
                            $aux_non_ext.= "<li class=\"ui-state-default ui-corner-all\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateDisabledValue']]}\"><a class=\"change-status\" state=\"{$this->_states[$this->_opt['stateDisabledValue']]}\" href=\"javascript:void(0);\" url=\"" . ($this->_opt[ 'changeStatus' ] != null && $this->_opt[ 'changeStatus' ][ 'url' ] != null ? $this->_opt[ 'changeStatus' ]['url'] : "ajax/acciones-listado.php" ). '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt,table',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;table=" . $this->_opt['table'] . "&amp;status=" . $this->_opt['stateDisabledValue'] . "&amp;fancy=1" . ($this->_opt[ 'changeStatus' ]['params'] != null ? "&amp;" . $this->_opt[ 'changeStatus' ]['params'] : "") . "\"><span class=\"ui-icon ui-icon-closethick\"></span><span class=\"title\">Cambiar a {$this->_states[$this->_opt['stateDisabledValue']]}</span></a></li>";
                        }
                        else if ($value[$this->_opt['fieldStatus']] == $this->_opt['stateEnabledValue'])
                        {
                            $aux_non_ext.= "<li class=\"ui-state-default ui-corner-all\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateDisabledValue']]}\"><a class=\"change-status\" state=\"{$this->_states[$this->_opt['stateDisabledValue']]}\" href=\"javascript:void(0);\" url=\"" . ($this->_opt[ 'changeStatus' ] != null && $this->_opt[ 'changeStatus' ][ 'url' ] != null ? $this->_opt[ 'changeStatus' ]['url'] : "ajax/acciones-listado.php" ). '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt,table',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;table=" . $this->_opt['table'] . "&amp;status=" . $this->_opt['stateDisabledValue'] . "&amp;fancy=1" . ($this->_opt[ 'changeStatus' ]['params'] != null ? "&amp;" . $this->_opt[ 'changeStatus' ]['params'] : "") . "\"><span class=\"ui-icon ui-icon-closethick\"></span><span class=\"title\">Cambiar a {$this->_states[$this->_opt['stateDisabledValue']]}</span></a></li>";
                            //$aux.= "<li class=\"change-status ui-state-default ui-corner-all info-tooltip\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateDisabledValue']]}\"><a state=\"{$this->_opt['stateDisabledValue']}\" href=\"javascript:void(0);\" id=\"changeRow_" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "|{$this->_opt['stateDisabledValue']}\"  onclick=\"javascript:if (confirm('Deséa cambiar el estado del registro a {$this->_states[$this->_opt['stateDisabledValue']]}?')) document.location = '" . Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) ."&amp;status=" . $this->_opt['stateDisabledValue'] . "';\"><span class=\"ui-icon ui-icon-closethick\"></span></a></li>";
                        }
                        else
                        {
                            $aux_non_ext.= "<li class=\"ui-state-default ui-corner-all\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateEnabledValue']]}\"><a class=\"change-status\" state=\"{$this->_states[$this->_opt['stateEnabledValue']]}\" href=\"javascript:void(0);\" url=\"" . ($this->_opt[ 'changeStatus' ] != null && $this->_opt[ 'changeStatus' ][ 'url' ] != null ? $this->_opt[ 'changeStatus' ]['url'] : "ajax/acciones-listado.php" ). '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt,table',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;table=" . $this->_opt['table'] . "&amp;status=" . $this->_opt['stateEnabledValue'] . "&amp;fancy=1" . ($this->_opt[ 'changeStatus' ]['params'] != null ? "&amp;" . $this->_opt[ 'changeStatus' ]['params'] : "") . "\"><span class=\"ui-icon ui-icon-check\"></span><span class=\"title\">Cambiar a {$this->_states[$this->_opt['stateEnabledValue']]}</span></a></li>\n";
                            //$aux.= "<li class=\"change-status ui-state-default ui-corner-all info-tooltip\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateEnabledValue']]}\"><a  state=\"{$this->_opt['stateEnabledValue']}\" href=\"javascript:void(0);\" id=\"changeRow_" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) ."|{$this->_opt['stateEnabledValue']}\"   onclick=\"javascript:if (confirm('Deséa cambiar el estado del registro a {$this->_states[$this->_opt['stateEnabledValue']]}?')) document.location = '" . Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) ."&amp;status=" . $this->_opt['stateEnabledValue'] . "';\"><span class=\"ui-icon ui-icon-check\"></span></a></li>\n";
                        }
                }
                else
                {
                        $width += 60;
                        if ($value[$this->_opt['fieldStatus']] == $this->_opt['stateUnmoderatedValue'])
                        {
                            //$aux.= "<li class=\"change-status ui-state-default ui-corner-all info-tooltip\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateEnabledValue']]}\"><a  state=\"{$this->_opt['stateEnabledValue']}\" href=\"javascript:void(0);\" id=\"changeRow_" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "|{$this->_opt['stateEnabledValue']}\" onclick=\"javascript:if (confirm('Deséa cambiar el estado del registro a {$this->_states[$this->_opt['stateEnabledValue']]}?')) document.location = '" . Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) ."&amp;status=" . $this->_opt['stateEnabledValue'] . "';\"><span class=\"ui-icon ui-icon-check\"></span></a></li>\n";
                            $aux_non_ext.= "<li class=\"ui-state-default ui-corner-all\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateEnabledValue']]}\"><a class=\"change-status\" state=\"{$this->_states[$this->_opt['stateEnabledValue']]}\" href=\"javascript:void(0);\" url=\"" . ($this->_opt[ 'changeStatus' ] != null && $this->_opt[ 'changeStatus' ][ 'url' ] != null ? $this->_opt[ 'changeStatus' ]['url'] : "ajax/acciones-listado.php" ). '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt,table',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;table=" . $this->_opt['table'] . "&amp;status=" . $this->_opt['stateEnabledValue'] . "&amp;fancy=1" . ($this->_opt[ 'changeStatus' ]['params'] != null ? "&amp;" . $this->_opt[ 'changeStatus' ]['params'] : "") . "\"><span class=\"ui-icon ui-icon-check\"></span><span class=\"title\">Cambiar a {$this->_states[$this->_opt['stateEnabledValue']]}</span></a></li>\n";
                            $aux_non_ext.= "<li class=\"ui-state-default ui-corner-all\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateDisabledValue']]}\"><a class=\"change-status\" state=\"{$this->_states[$this->_opt['stateDisabledValue']]}\" href=\"javascript:void(0);\" url=\"" . ($this->_opt[ 'changeStatus' ] != null && $this->_opt[ 'changeStatus' ][ 'url' ] != null ? $this->_opt[ 'changeStatus' ]['url'] : "ajax/acciones-listado.php" ). '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt,table',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "&amp;table=" . $this->_opt['table'] . "&amp;status=" . $this->_opt['stateDisabledValue'] . "&amp;fancy=1" . ($this->_opt[ 'changeStatus' ]['params'] != null ? "&amp;" . $this->_opt[ 'changeStatus' ]['params'] : "") . "\"><span class=\"ui-icon ui-icon-closethick\"></span><span class=\"title\">Cambiar a {$this->_states[$this->_opt['stateDisabledValue']]}</span></a></li>";
                            //$aux.= "<li class=\"change-status ui-state-default ui-corner-all info-tooltip\" title=\"Cambiar estado a {$this->_states[$this->_opt['stateDisabledValue']]}\"><a state=\"{$this->_opt['stateDisabledValue']}\" href=\"javascript:void(0);\" id=\"changeRow_" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) . "|{$this->_opt['stateDisabledValue']}\" onclick=\"javascript:if (confirm('Deséa cambiar el estado del registro a {$this->_states[$this->_opt['stateDisabledValue']]}?')) document.location = '" . Ftl_Path::getFileName() . '?' . Ftl_ArrayUtil::toQueryString($this->_io->getAll(), true, 'action,status,id,encrypt',false) ."&amp;action=changeStatus&amp;encrypt=" . (($this->_opt['encryptFieldId']) ? "1" : "0") . "&amp;id=" . (($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']]) ."&amp;status=" . $this->_opt['stateDisabledValue'] . "';\"><span class=\"ui-icon ui-icon-closethick\"></span></a></li>";
                        }

                }


            }
            
            if ($aux_non_ext != ""){
                $aux.= "<ul id=\"icons\" class=\"ui-widget\">";
                $aux.= $aux_non_ext;
                $aux.= "</ul>";
            }
            
            if ($this->_extendedActions != ""){
                $width+=30;
                if ($this->_opt['groupExtendedActions']){
                    $aux.= "<div class=\"options-collap\">\n";
                    $aux.= "<h3>+</h3>\n";
                }else{
                    $width += count($this->_extendedActions) * 30;
                }
                $aux.= "<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">";
                $aux.= Ftl_StringUtil::replaceVars($this->_extendedActions, array('id'=>(($this->_opt['encryptFieldId']) ? $value[$this->_opt['fieldIdEncrypted']] : $value[$this->_opt['fieldId']])));
                
                //$aux.= "</ul></div>";
                $aux.= "</ul>";
                if ($this->_opt['groupExtendedActions']){
                    $aux.= "</div>\n";
                    
                }                
                
            }
            //$width = "auto";
            //$aux.= ($this->_opt['canChangeStatus'])     ? "<a href=\"javascript:void(0);\" accion=\"changeStatus\" id=\"changeStatusRow_{$value[$this->_opt['fieldId']]}\" title=\"Cambiar estado\" class=\"icon-1 info-tooltip\"></a>" : "";
            $aux= "<td data-name=\"col_opciones\" class=\"options-width\" style=\"width:{$width}px\">\n<span style=\"width:{$width}px;display: block;\">".$aux."</span></td>\n";
            
        }

        return $aux;
    }

    private function generateOrder ()
    {
        //var_dump($_REQUEST);
        if ($this->_opt['canOrder'] || $this->_opt['orderBy'])
        {
            //var_dump($this->_opt['orderBy']);
            $orderBy   = explode ( ',' , $this->_io->get('orderBy', $this->_opt['orderBy'] ) );
            foreach ( $orderBy as $order )
            {
                $order = explode( '|', $order);

                if ( count($order) == 2 && !in_array($order[0], $this->_opt[ 'orderFieldIgnore' ]) )
                {
                    $this->_mapOrder[ $order[0] ] = $order[1];
                }
            }
        }
    }

    private function getOrderByForParam ( $field = null , $dir = null )
    {

        $aux = $this->_mapOrder;

        if ( $field && $dir )
        {
            if ( array_key_exists( $field , $aux ) )
            {
                if ( $aux[$field] == $dir )
                    unset ( $aux[$field] );
                else
                    $aux[$field] = $dir;
            }
            else
                $aux[$field] = $dir;
        }

        $str = "";
        foreach ( $aux as $k => $v )
        {
            $str .= $k . '|' . $v . ',';
        }

        return ( count ( $aux ) > 0 ) ? "&amp;orderBy=". substr($str, 0, strlen($str)-1) : "";
    }
    function replaceColName($field){

        return str_ireplace(".", ":", $field);

    }
    function revertColName($field){

        return str_ireplace("|", ".", $field);

    }

    private function _getFilters($collection,&$result,$forSql = false){
        
		$value = "";
        
        if (count($collection) > 0)
        {
            foreach ($collection as $field => $options)
            {
                
                if ( isset ($options[ 'filter' ] ) && $options[ 'filter' ] )
                {

                    
                    $defValue = (array_key_exists('value', $options) && $options[ 'value' ] != null ? $options[ 'value' ] : null );
                    
                    //var_dump($options[ 'value' ] == 0);

                    if ( isset($options[ 'type' ]) )
                    {


                        switch ( $options[ 'type' ] )
                        {
                            case 'datetime':
                            case 'date':
                                $defValueD = null;
                                $defValueH = null;
                                if ($defValue != null){
                                    $defValue = explode('|',$defValue);
                                    $defValueD = ( isset($defValue[0]) ? $defValue[0] : null);
                                    $defValueH = ( isset($defValue[1]) ? $defValue[1] : null);
                                }
                                $d  = $this->_io->get($this->replaceColName($field).'_d',$defValueD);
                                $h  = $this->_io->get($this->replaceColName($field).'_h',$defValueH);

								if ( $forSql ) {


									if ($d != null && $h != null)
										$value = " between '{$d}' and date_add('{$h}',INTERVAL 1 DAY) ";
									else
										if ($d)
											$value = " > '{$d}' ";
											else if ($h)
												$value = " < '{$h}' ";

									if ($value)
										array_push($result, $field . $value);

								} else {
									if ($d != null && $h != null){
										$value = array($d,$h);
                                                                        }else{
										if ($d)
											$value = array($d,null);
											else if ($h)
												$value = array(null,$h);
                                                                        }
                                                                        
                                                                        if ($value)
                                                                            $result [ $field ] = $value;

								}

                                break;
                            case 'text':

								if ( $forSql ) {

									if ( $this->_io->get($this->replaceColName($field),$defValue) != null )
										array_push($result, $field . " = '{$this->_io->get($this->replaceColName($field),$defValue)}' ");

								} else {
                                                                        if ( $this->_io->get($this->replaceColName($field),$defValue) != null )
                                                                            $result [ $field ] = $this->_io->get($this->replaceColName($field),$defValue);

								}

                                break;
                            case 'int':
                            case 'gender':
                            case 'state':
                            default :
                                                                
								if ( $forSql ) {
                                                                        
									if ( $this->_io->get($this->replaceColName($field),$defValue) != null)
										array_push($result, $field . " = '{$this->_io->get($this->replaceColName($field),$defValue)}' ");
                                                                        
                                                                        //exit();
								} else {
                                                                        if ( $this->_io->get($this->replaceColName($field),$defValue) != null)
                                                                            $result [ $field ] = $this->_io->get($this->replaceColName($field),$defValue);

								}
                                break;
                        }
                    }else {
						if ( $forSql ) {
							if ( $this->_io->get($this->replaceColName($field),$defValue) != null )
								array_push($result, $field . " = '{$this->_io->get($this->replaceColName($field),$defValue)}' ");
                                                } else {
                                                        if ( $this->_io->get($this->replaceColName($field),$defValue) != null )
                                                            $result [ $field ] = $this->_io->get($this->replaceColName($field),$defValue);

                                                }
                    }
                }
            }

        }



    }

    private function _getData( $export = false)
    {

        $data = null;

        $callback = null;
        
        if ($this->_opt[ 'dataSource' ]['filter_type'] == 'sql'){
            $filters = $this->getFiltersForSql();
        }else{
            $filters = $this->getFilters();
                    
        }
        if ($this->_isSetData){
            $data = $this->_data;
        }else{
            if (isset($this->_opt[ 'dataSource' ]) && isset($this->_opt[ 'dataSource' ]['method']) && $this->_opt[ 'dataSource' ]['method'] != ""){
                $callback   = (isset($this->_opt[ 'dataSource' ]['class']) && $this->_opt[ 'dataSource' ]['class'] != "" ? array($this->_opt[ 'dataSource' ]['class'],$this->_opt[ 'dataSource' ]['method']) : $this->_opt[ 'dataSource' ]['method']);
                $param_arr  = array(($export ? 1 : $this->getCurrentPage()),($export ? 600000 : $this->getResPerPage()),$filters,$this->getOrderByForSql());
            }else
                if (isset ($this->_opt[ 'table' ]) && $this->_opt[ 'table' ] != null && $this->_opt[ 'table' ] != "" ){

                    $callback = array("Ftl_ClaseBase","_obtenerListadoPaginado");
                    $param_arr = array("*",DB_PREFIX.$this->_opt[ 'table' ],($export ? 1 : $this->getCurrentPage()),($export ? 600000 : $this->getResPerPage()),$filters,$this->getOrderByForSql());

                }

            if ( $callback != null ){
               $data = call_user_func_array ( $callback, $param_arr);
               if (empty($data)){
                   $data = NULL;
               }
            }
            
        }
        
        
        return $data;
    }

}
?>
